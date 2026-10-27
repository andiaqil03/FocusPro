// popup.js

async function render() {
    const { userId, status = {} } = await chrome.storage.local.get(["userId", "status"]);
    const statusEl = document.getElementById("status");

    document.getElementById("user").textContent = userId || "not linked";
    statusEl.textContent = status.isFocusing ? "Focus session running" : "Not focusing";
    statusEl.className = status.isFocusing ? "on" : "off";
    document.getElementById("sites").textContent =
        status.isFocusing && status.blockedSites?.length ? status.blockedSites.join(", ") : "nothing";

    let note = "";
    if (!userId) {
        note = "Open the Pomodoro page in FocusPro while logged in to link your account.";
    } else if (status.error) {
        note = `Last check failed: ${status.error}`;
    } else if (status.checkedAt) {
        note = `Checked ${new Date(status.checkedAt).toLocaleTimeString()}`;
    }
    document.getElementById("note").textContent = note;
}

document.getElementById("refresh").addEventListener("click", async () => {
    await chrome.runtime.sendMessage({ type: "popup-refresh" });
    render();
});

render();
