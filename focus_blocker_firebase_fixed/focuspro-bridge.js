// focuspro-bridge.js
//
// Runs on FocusPro pages. The Pomodoro page announces the logged-in user's ID and every focus
// start/stop with window.postMessage; this forwards those to the background worker so the
// extension follows whoever is logged in and reacts instantly instead of waiting for its
// 30-second Firebase poll.

window.addEventListener("message", event => {
    if (event.source !== window || event.origin !== window.location.origin) {
        return;
    }

    const data = event.data;
    if (!data || data.source !== "focuspro") {
        return;
    }

    if (data.type === "user" || data.type === "status-changed") {
        chrome.runtime.sendMessage({ type: `focuspro-${data.type}`, userId: data.userId });
    }
});
