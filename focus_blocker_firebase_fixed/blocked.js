// blocked.js — fills in blocked.html: which site was blocked, the session's block list,
// today's count, a focus tip and the breathing prompt.

const tips = [
    "<b>Write it down.</b> If something urgent popped into your head, note it on paper and deal with it at your next break.",
    "<b>Shrink the task.</b> Stuck? Do the next five minutes only. Starting is the hardest part.",
    "<b>Your break is coming.</b> The site will still be there when the timer says so.",
    "<b>Notice the urge, then let it pass.</b> Most distractions fade within a minute if you don't feed them.",
    "<b>Close what you don't need.</b> Fewer open tabs means fewer reasons to wander.",
    "<b>Single-task.</b> One thing at a time gets done faster than three things at once.",
    "<b>Drink some water.</b> Restlessness is sometimes just your body asking for a pause.",
];

function hostOf(url) {
    try {
        return new URL(url).hostname.replace(/^www\./, "");
    } catch {
        return null;
    }
}

function setLead(host) {
    const lead = document.getElementById("lead");
    lead.textContent = "";
    if (host) {
        const strong = document.createElement("strong");
        strong.textContent = host;
        lead.append(strong, " is blocked while your focus session is running.");
        document.title = `${host} blocked · FocusPro`;
    } else {
        lead.textContent = "This site is blocked while your focus session is running.";
    }
    return host;
}

function renderSites(sites, currentHost) {
    const row = document.getElementById("sitesRow");
    const list = document.getElementById("sites");
    list.textContent = "";
    if (!sites.length) {
        row.hidden = true;
        return;
    }
    for (const site of sites) {
        const chip = document.createElement("span");
        const isCurrent = currentHost && (currentHost === site || currentHost.endsWith("." + site));
        chip.className = isCurrent ? "chip current" : "chip";
        chip.textContent = site;
        list.append(chip);
    }
    row.hidden = false;
}

function renderStatus(status) {
    const el = document.getElementById("sessionStatus");
    if (status && status.isFocusing === false) {
        el.textContent = "Session ended. You can go back now";
    }
}

function renderCount(blockedToday) {
    const d = new Date();
    const today = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, "0")}-${String(d.getDate()).padStart(2, "0")}`;
    if (blockedToday?.date === today && blockedToday.count > 0) {
        document.getElementById("blockedCount").textContent = blockedToday.count;
        document.getElementById("countRow").hidden = false;
    }
}

async function init() {
    document.getElementById("tip").innerHTML = tips[Math.floor(Math.random() * tips.length)];

    const tab = await chrome.tabs.getCurrent();
    const key = tab ? `blocked:${tab.id}` : null;

    const [{ status, blockedToday, appOrigin }, session] = await Promise.all([
        chrome.storage.local.get(["status", "blockedToday", "appOrigin"]),
        key ? chrome.storage.session.get(key) : Promise.resolve({}),
    ]);

    let currentHost = setLead(hostOf(session[key]));
    renderSites(status?.blockedSites || [], currentHost);
    renderStatus(status);
    renderCount(blockedToday);

    if (appOrigin) {
        document.getElementById("backToSession").href = `${appOrigin}/pomodoro`;
    }

    // The background worker may record the URL / count a moment after this page loads.
    chrome.storage.onChanged.addListener((changes, area) => {
        if (area === "session" && key && changes[key]?.newValue) {
            currentHost = setLead(hostOf(changes[key].newValue));
        }
        if (area === "local" && changes.blockedToday) {
            renderCount(changes.blockedToday.newValue);
        }
        if (area === "local" && changes.status) {
            renderSites(changes.status.newValue?.blockedSites || [], currentHost);
            renderStatus(changes.status.newValue);
        }
    });

    document.getElementById("closeTab").addEventListener("click", () => {
        if (tab) {
            chrome.tabs.remove(tab.id);
        } else {
            window.close();
        }
    });
}

// Breathing prompt, in step with the 8-second CSS animation (4s in, 4s out).
function startBreathing() {
    const label = document.getElementById("breathLabel");
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        label.textContent = "Take a slow breath";
        return;
    }
    let inhale = true;
    setInterval(() => {
        inhale = !inhale;
        label.style.opacity = 0;
        setTimeout(() => {
            label.textContent = inhale ? "Breathe in" : "Breathe out";
            label.style.opacity = 1;
        }, 400);
    }, 4000);
}

startBreathing();
init().catch(error => console.error("Focus Blocker page:", error));
