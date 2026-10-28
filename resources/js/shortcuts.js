// Single-key shortcuts. Any link or button with data-shortcut="n" is clicked when N is pressed,
// unless the user is typing, holding a modifier, or a dialog is open.

const typing = el => el.closest?.('input, textarea, select, [contenteditable=""], [contenteditable="true"]');
const dialogOpen = () => [...document.querySelectorAll('[role="dialog"], [aria-modal="true"]')].some(el => el.offsetParent !== null);

document.addEventListener('keydown', event => {
    if (event.defaultPrevented || event.repeat || event.ctrlKey || event.metaKey || event.altKey) {
        return;
    }
    if (typing(event.target) || dialogOpen()) {
        return;
    }

    const target = document.querySelector(`[data-shortcut="${CSS.escape(event.key.toLowerCase())}"]`);
    if (target) {
        event.preventDefault();
        target.click();
    }
});
