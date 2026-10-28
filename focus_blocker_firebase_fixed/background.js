// background.js
//
// MV3 service workers are stopped whenever Chrome feels like it, so nothing here relies on
// in-memory state. Blocking is done with declarativeNetRequest rules, which Chrome keeps
// enforcing while the worker sleeps; the worker only wakes up to refresh those rules.

const DATABASE_URL = "https://focuspro-fyp-default-rtdb.asia-southeast1.firebasedatabase.app";
const REFRESH_ALARM = "refresh-focus-status";
const BLOCKED_PAGE = "/blocked.html";

// "https://www.YouTube.com/watch?v=1" -> "youtube.com"
function normalizeSite(site) {
    return String(site)
        .trim()
        .toLowerCase()
        .replace(/^[a-z]+:\/\//, "")
        .replace(/^www\./, "")
        .split(/[/?#:]/)[0];
}

function isBlockedUrl(url, sites) {
    let host;
    try {
        host = new URL(url).hostname.replace(/^www\./, "");
    } catch {
        return false;
    }
    return sites.some(site => host === site || host.endsWith("." + site));
}

async function fetchFocusStatus(userId) {
    const response = await fetch(`${DATABASE_URL}/focusStatus/${encodeURIComponent(userId)}.json`, {
        cache: "no-store",
    });
    if (!response.ok) {
        throw new Error(`Firebase returned HTTP ${response.status}`);
    }
    const data = (await response.json()) || {};
    const sites = Array.isArray(data.blockedSites) ? data.blockedSites : [];

    return {
        isFocusing: data.isFocusing === true,
        blockedSites: [...new Set(sites.map(normalizeSite).filter(Boolean))],
    };
}

async function applyRules({ isFocusing, blockedSites }) {
    const existing = await chrome.declarativeNetRequest.getDynamicRules();
    const sites = isFocusing ? blockedSites : [];

    await chrome.declarativeNetRequest.updateDynamicRules({
        removeRuleIds: existing.map(rule => rule.id),
        addRules: sites.map((site, index) => ({
            id: index + 1,
            priority: 1,
            action: { type: "redirect", redirect: { extensionPath: BLOCKED_PAGE } },
            // requestDomains also matches subdomains, e.g. m.youtube.com
            condition: { requestDomains: [site], resourceTypes: ["main_frame"] },
        })),
    });

    // Rules only catch new page loads, so redirect tabs that were already open.
    if (sites.length) {
        const tabs = await chrome.tabs.query({});
        for (const tab of tabs) {
            if (tab.url && isBlockedUrl(tab.url, sites)) {
                await rememberBlockedUrl(tab.id, tab.url);
                chrome.tabs.update(tab.id, { url: chrome.runtime.getURL(BLOCKED_PAGE) });
            }
        }
    }
}

// The redirect rule drops the original URL, so note it per tab for blocked.html to show.
function rememberBlockedUrl(tabId, url) {
    return chrome.storage.session.set({ [`blocked:${tabId}`]: url });
}

function localDate() {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
}

async function countBlockedAttempt() {
    const { blockedToday } = await chrome.storage.local.get("blockedToday");
    const today = localDate();
    const count = blockedToday?.date === today ? blockedToday.count + 1 : 1;
    await chrome.storage.local.set({ blockedToday: { date: today, count } });
}

// Fires before the request, i.e. before the declarativeNetRequest redirect happens.
chrome.webNavigation.onBeforeNavigate.addListener(async ({ tabId, url, frameId }) => {
    if (frameId !== 0) {
        return;
    }
    const { status } = await chrome.storage.local.get("status");
    if (!status?.isFocusing || !isBlockedUrl(url, status.blockedSites || [])) {
        return;
    }
    await rememberBlockedUrl(tabId, url);
    await countBlockedAttempt();
});

chrome.tabs.onRemoved.addListener(tabId => {
    chrome.storage.session.remove(`blocked:${tabId}`);
});

async function refresh() {
    const { userId } = await chrome.storage.local.get("userId");
    if (!userId) {
        await chrome.storage.local.set({ status: { isFocusing: false, blockedSites: [], error: "no-user" } });
        await applyRules({ isFocusing: false, blockedSites: [] });
        return;
    }

    try {
        const status = await fetchFocusStatus(userId);
        await chrome.storage.local.set({ status: { ...status, checkedAt: Date.now() } });
        await applyRules(status);
    } catch (error) {
        // Keep the last known rules when offline instead of silently unblocking.
        console.error("Could not refresh focus status:", error);
        const { status } = await chrome.storage.local.get("status");
        await chrome.storage.local.set({ status: { ...status, error: String(error.message || error) } });
    }
}

function ensureAlarm() {
    chrome.alarms.create(REFRESH_ALARM, { periodInMinutes: 0.5 });
}

chrome.runtime.onInstalled.addListener(() => {
    ensureAlarm();
    refresh();
});

chrome.runtime.onStartup.addListener(() => {
    ensureAlarm();
    refresh();
});

chrome.alarms.onAlarm.addListener(alarm => {
    if (alarm.name === REFRESH_ALARM) {
        refresh();
    }
});

// Messages from focuspro-bridge.js (the FocusPro tab) and popup.html.
chrome.runtime.onMessage.addListener((message, sender, sendResponse) => {
    const setUserAndRefresh = userId => {
        chrome.storage.local.set({ userId }).then(refresh).then(() => sendResponse({ ok: true }));
        return true;
    };

    if (message?.type === "focuspro-user" || message?.type === "focuspro-status-changed") {
        // Remember where the FocusPro app lives so blocked.html can link back to it.
        if (sender.url) {
            chrome.storage.local.set({ appOrigin: new URL(sender.url).origin });
        }
        const userId = String(message.userId || "").trim();
        return /^\d+$/.test(userId) ? setUserAndRefresh(userId) : undefined;
    }

    if (message?.type === "popup-refresh") {
        refresh().then(() => sendResponse({ ok: true }));
        return true;
    }
});
