// Page motion, built on Motion (the framework-agnostic version of Framer Motion).
//
//   data-reveal              fade + rise in when scrolled into view
//   data-stagger             same, applied to each direct child one after another
//   data-count="42"          count up from 0 to the number
//   data-progress-ring       SVG circle whose stroke draws in to its data-offset
//
// Everything is skipped when the user prefers reduced motion; the CSS in app.css only hides
// these elements when motion is allowed, so content is always visible otherwise.

import { animate, inView, stagger } from 'motion';

const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const enter = { type: 'spring', stiffness: 260, damping: 30, mass: 0.9 };

function reveal() {
    document.querySelectorAll('[data-reveal]').forEach(el => {
        inView(el, () => {
            animate(el, { opacity: [0, 1], y: [14, 0] }, { ...enter, delay: Number(el.dataset.reveal) || 0 });
        }, { amount: 0.15 });
    });

    document.querySelectorAll('[data-stagger]').forEach(list => {
        inView(list, () => {
            // Motion ignores a live HTMLCollection, so pass a real array.
            animate(Array.from(list.children), { opacity: [0, 1], y: [12, 0] }, { ...enter, delay: stagger(0.05) });
        }, { amount: 0.1 });
    });

    // The markup already carries the final offset. Drive the value through onUpdate (the same way
    // countUp does) rather than animating the SVG property directly, which left the inline start
    // value in place, and snap to the final offset if the animation hasn't finished in time.
    document.querySelectorAll('[data-progress-ring]').forEach(ring => {
        const full = parseFloat(ring.getAttribute('stroke-dasharray'));
        const offset = parseFloat(ring.dataset.offset);
        if (!Number.isFinite(full) || !Number.isFinite(offset)) {
            return;
        }
        let started = false;
        const settle = () => (ring.style.strokeDashoffset = offset);
        ring.style.strokeDashoffset = full;
        inView(ring, () => {
            started = true;
            animate(full, offset, { duration: 1.2, ease: [0.16, 1, 0.3, 1], delay: 0.15, onUpdate: v => (ring.style.strokeDashoffset = v) }).then(settle, settle);
            setTimeout(settle, 2000);
        });
        setTimeout(() => started || settle(), 4000);
    });
}

function countUp() {
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = Number(el.dataset.count);
        if (!Number.isFinite(target)) {
            return;
        }
        const decimals = Number(el.dataset.decimals) || 0;
        const format = v => v.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });

        if (reducedMotion) {
            el.textContent = format(target);
            return;
        }
        inView(el, () => {
            animate(0, target, { duration: 0.9, ease: [0.16, 1, 0.3, 1], onUpdate: v => (el.textContent = format(v)) });
        });
    });
}

// Small toast used for flash messages and the Pomodoro timer: window.toast('Saved', 'success').
const toastIcons = {
    success: '<path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/>',
    error: '<circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>',
    info: '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
};
const toastTone = { success: 'text-brand-600', error: 'text-red-600', info: 'text-ink-500' };

// Pass { action: { label, onClick } } to add a button such as Undo.
function toast(message, type = 'success', { duration = 3500, action = null } = {}) {
    let root = document.getElementById('toast-root');
    if (!root) {
        root = document.createElement('div');
        root.id = 'toast-root';
        root.setAttribute('aria-live', 'polite');
        root.className = 'pointer-events-none fixed inset-x-0 top-20 z-[60] flex flex-col items-center gap-2 px-4 sm:inset-x-auto sm:right-4 sm:items-end';
        document.body.appendChild(root);
    }

    const el = document.createElement('div');
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');
    el.className = 'pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border border-ink-200 bg-white px-4 py-3 text-sm text-ink-800 shadow-lift';
    el.innerHTML = `<svg class="mt-0.5 h-5 w-5 shrink-0 ${toastTone[type] || toastTone.info}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${toastIcons[type] || toastIcons.info}</svg><div class="min-w-0 flex-1"></div>`;
    el.querySelector('div').textContent = message;
    if (action) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = '-my-1 -mr-1 shrink-0 cursor-pointer rounded-lg px-2 py-1 text-sm font-semibold text-brand-700 transition hover:bg-brand-50';
        button.textContent = action.label;
        button.addEventListener('click', event => {
            event.stopPropagation();
            button.disabled = true;
            action.onClick();
        });
        el.appendChild(button);
    }
    root.appendChild(el);

    if (!reducedMotion) {
        animate(el, { opacity: [0, 1], y: [-12, 0], scale: [0.97, 1] }, enter);
    }

    const close = () => {
        const done = () => el.remove();
        reducedMotion ? done() : animate(el, { opacity: 0, y: -8 }, { duration: 0.18, ease: 'easeIn' }).then(done);
    };
    // Hovering or focusing the toast holds it open, so there's time to reach an Undo button.
    let timeout = setTimeout(close, duration);
    const hold = () => clearTimeout(timeout);
    const release = () => (timeout = setTimeout(close, 2000));
    el.addEventListener('mouseenter', hold);
    el.addEventListener('mouseleave', release);
    el.addEventListener('focusin', hold);
    el.addEventListener('focusout', release);
    el.addEventListener('click', close);
}

window.toast = toast;
window.motion = { animate, reducedMotion };

if (!reducedMotion) {
    reveal();
}
countUp();
window.__motionReady = true;
