@extends('layouts.app')

@section('title', 'Focus')

@section('content')
@php $circumference = 2 * M_PI * 120; @endphp

<div class="page">
    <div class="page-header" data-reveal>
        <div>
            <h1 class="page-title">Focus session</h1>
            <p class="page-subtitle">Work in focused cycles. Blocked sites stay blocked until you end the session.</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-5">
        {{-- Timer --}}
        <section class="card flex flex-col items-center px-6 py-8 sm:py-10 lg:col-span-3" data-reveal="0.05" aria-label="Timer">
            <span id="modeBadge" class="chip-brand">
                <span id="modeDot" class="h-1.5 w-1.5 rounded-full bg-brand-600"></span>
                <span id="modeLabel">Focus</span>
            </span>

            @if ($task)
                <p class="mt-4 max-w-full truncate text-center text-sm text-ink-500">
                    Focusing on <a href="{{ route('tasks.edit', $task) }}" class="font-medium text-ink-900 underline decoration-ink-300 underline-offset-4 hover:decoration-ink-900">{{ $task->title }}</a>
                </p>
            @endif

            <div class="relative my-8 h-64 w-64 sm:h-72 sm:w-72">
                <svg viewBox="0 0 260 260" class="h-full w-full -rotate-90" aria-hidden="true">
                    <circle cx="130" cy="130" r="120" fill="none" class="stroke-ink-100" stroke-width="10" />
                    <circle id="timerRing" cx="130" cy="130" r="120" fill="none" stroke-width="10" stroke-linecap="round"
                            class="stroke-brand-600 transition-[stroke-dashoffset,stroke] duration-1000 ease-linear"
                            stroke-dasharray="{{ $circumference }}" stroke-dashoffset="0" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span id="timer" class="text-6xl font-semibold tabular-nums tracking-tight text-ink-900 sm:text-7xl" role="timer" aria-live="off">25:00</span>
                    <span id="timerHint" class="mt-2 text-sm text-ink-500">Ready when you are</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="resetTimer()" class="btn-secondary h-12 w-12 rounded-full p-0" aria-label="Reset timer" title="Reset">
                    <x-icon name="rotate-ccw" class="h-5 w-5" />
                </button>
                <button type="button" onclick="startTimer()" id="startBtn" class="btn-primary btn-lg min-w-[9rem] rounded-full">
                    <x-icon name="play" class="h-4 w-4" /> Start
                </button>
                <button type="button" onclick="pauseTimer()" id="pauseBtn" class="btn-secondary btn-lg hidden min-w-[9rem] rounded-full">
                    <x-icon name="pause" class="h-4 w-4" /> Pause
                </button>
                <button type="button" onclick="endSession()" class="btn-secondary h-12 w-12 rounded-full p-0 hover:border-red-200 hover:bg-red-50 hover:text-red-600" aria-label="End session" title="End session">
                    <x-icon name="circle-stop" class="h-5 w-5" />
                </button>
            </div>

            <dl class="mt-10 grid w-full max-w-sm grid-cols-2 divide-x divide-ink-100 rounded-xl border border-ink-100 bg-ink-50/50 text-center">
                <div class="px-4 py-3">
                    <dt class="text-xs text-ink-500">Cycles completed</dt>
                    <dd class="mt-0.5 text-lg font-semibold tabular-nums text-ink-900"><span id="cycleCounter">{{ $cyclesCompleted }}</span></dd>
                </div>
                <div class="px-4 py-3">
                    <dt class="text-xs text-ink-500">Sessions saved</dt>
                    <dd class="mt-0.5 text-lg font-semibold tabular-nums text-ink-900"><span id="sessionCounter">{{ $sessionsCompleted }}</span></dd>
                </div>
            </dl>
        </section>

        {{-- Settings --}}
        <div class="space-y-6 lg:col-span-2" data-stagger>
            <section class="card card-body">
                <h2 class="section-title flex items-center gap-2"><x-icon name="clock" class="h-4 w-4 text-ink-400" /> Durations</h2>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div>
                        <label for="workDuration" class="label">Focus</label>
                        <div class="relative">
                            <input type="number" id="workDuration" value="25" min="1" max="180" inputmode="numeric" class="input pr-12 tabular-nums">
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-sm text-ink-400">min</span>
                        </div>
                    </div>
                    <div>
                        <label for="breakDuration" class="label">Break</label>
                        <div class="relative">
                            <input type="number" id="breakDuration" value="5" min="1" max="60" inputmode="numeric" class="input pr-12 tabular-nums">
                            <span class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 text-sm text-ink-400">min</span>
                        </div>
                    </div>
                </div>
                <p class="hint">Changing a duration resets the current timer.</p>
            </section>

            <section class="card card-body">
                <h2 class="section-title flex items-center gap-2"><x-icon name="ban" class="h-4 w-4 text-ink-400" /> Blocked websites</h2>
                <p class="mt-1 text-sm text-ink-500">Blocked by the FocusPro browser extension while a session runs.</p>
                <label for="blockedSites" class="sr-only">Blocked websites</label>
                <textarea id="blockedSites" rows="3" class="input mt-4 resize-none" placeholder="youtube.com, instagram.com"></textarea>
                <p class="hint">Separate sites with commas.</p>
            </section>
        </div>
    </div>
</div>

<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
<script>
    const userId = "{{ Auth::user()->id }}";

    // Define config first
    const firebaseConfig = {
        apiKey: "AIzaSyAO8Fr1OHyfbBNiK3cuC33qdNDgEymbe1I",
        authDomain: "focuspro-fyp.firebaseapp.com",
        databaseURL: "https://focuspro-fyp-default-rtdb.asia-southeast1.firebasedatabase.app",
        projectId: "focuspro-fyp",
        storageBucket: "focuspro-fyp.firebasestorage.app",
        messagingSenderId: "935476789668",
        appId: "1:935476789668:web:350e24ef51aff668b68131"
    };

    // Then initialize
    const app = firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // Picked up by the Focus Blocker extension's content script (focuspro-bridge.js), which links
    // the extension to this user and makes it re-read Firebase right away.
    function notifyExtension(type) {
        window.postMessage({ source: "focuspro", type, userId }, window.location.origin);
    }

    function notify(message, type = "info") {
        window.toast ? window.toast(message, type) : console.log(message);
    }

    document.addEventListener("DOMContentLoaded", () => {
        console.log("Firebase initialized successfully");
        notifyExtension("user");
    });

    const RING_LENGTH = {{ $circumference }};
    const PAGE_TITLE = document.title;
    const savedCycles = {{ (int) $cyclesCompleted }};

    let workTime = 25 * 60;
    let breakTime = 5 * 60;
    let timeLeft = workTime;
    let isWork = true;
    let cyclesCompleted = 0;
    let isRunning = false;
    let timer;

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const rest = seconds % 60;
        return `${minutes}:${rest < 10 ? '0' : ''}${rest}`;
    }

    function updateDisplay() {
        const phaseLength = isWork ? workTime : breakTime;
        document.getElementById("timer").innerText = formatTime(timeLeft);
        document.getElementById("timerRing").style.strokeDashoffset = RING_LENGTH * (1 - timeLeft / phaseLength);
        document.title = isRunning ? `${formatTime(timeLeft)} · ${isWork ? "Focus" : "Break"}` : PAGE_TITLE;
    }

    function updateMode() {
        const ring = document.getElementById("timerRing");
        const badge = document.getElementById("modeBadge");
        ring.classList.toggle("stroke-brand-600", isWork);
        ring.classList.toggle("stroke-amber-500", !isWork);
        badge.className = isWork ? "chip-brand" : "chip-amber";
        document.getElementById("modeDot").className = `h-1.5 w-1.5 rounded-full ${isWork ? "bg-brand-600" : "bg-amber-500"}`;
        document.getElementById("modeLabel").innerText = isWork ? "Focus" : "Break";
        document.getElementById("timerHint").innerText = isRunning
            ? (isWork ? "Stay with it" : "Step away for a moment")
            : (timeLeft === (isWork ? workTime : breakTime) ? "Ready when you are" : "Paused");
    }

    function updateTimerValues() {
        workTime = Math.max(1, parseInt(document.getElementById("workDuration").value) || 25) * 60;
        breakTime = Math.max(1, parseInt(document.getElementById("breakDuration").value) || 5) * 60;
        resetTimer();
    }

    document.getElementById("workDuration").addEventListener("change", updateTimerValues);
    document.getElementById("breakDuration").addEventListener("change", updateTimerValues);

    function getBlockedSites() {
        const raw = document.getElementById("blockedSites").value;
        const list = raw.split(',').map(site => site.trim()).filter(site => site);
        localStorage.setItem("blockedSites", JSON.stringify(list));
        return list;
    }

    // Prefill the blocked sites from the last session.
    try {
        const saved = JSON.parse(localStorage.getItem("blockedSites") || "[]");
        if (Array.isArray(saved) && saved.length) {
            document.getElementById("blockedSites").value = saved.join(", ");
        }
    } catch (error) { /* ignore unreadable storage */ }

    function startTimer() {
        if (!isRunning) {
            const blockedSites = getBlockedSites();

            try {
                firebase.database().ref('focusStatus/' + userId).set({
                    isFocusing: true,
                    blockedSites: blockedSites
                }).then(() => {
                    console.log("Focus status updated successfully in Firebase.");
                    notifyExtension("status-changed");
                }).catch(error => {
                    console.error("Error updating focus status in Firebase:", error);
                });
            } catch (error) {
                console.error("Unexpected error with Firebase:", error);
            }

            isRunning = true;
            document.getElementById("startBtn").classList.add('hidden');
            document.getElementById("pauseBtn").classList.remove('hidden');
            updateMode();

            timer = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    updateDisplay();
                } else {
                    clearInterval(timer);
                    isRunning = false;
                    if (isWork) {
                        notify("Focus block done. Take a break.", "success");
                        timeLeft = breakTime;
                        isWork = false;
                    } else {
                        notify("Break over. Back to work.", "info");
                        timeLeft = workTime;
                        isWork = true;
                        cyclesCompleted++;
                        document.getElementById("cycleCounter").innerText = savedCycles + cyclesCompleted;
                    }
                    updateDisplay();
                    startTimer();
                }
            }, 1000);
        }
    }

    function pauseTimer() {
        clearInterval(timer);
        isRunning = false;
        document.getElementById("startBtn").classList.remove('hidden');
        document.getElementById("pauseBtn").classList.add('hidden');
        updateDisplay();
        updateMode();
    }

    function resetTimer() {
        clearInterval(timer);
        isRunning = false;
        isWork = true;
        timeLeft = workTime;
        updateDisplay();
        updateMode();
        document.getElementById("startBtn").classList.remove('hidden');
        document.getElementById("pauseBtn").classList.add('hidden');
    }

    function endSession() {
        clearInterval(timer);
        isRunning = false;

        try {
            firebase.database().ref('focusStatus/' + userId).set({
                isFocusing: false
            }).then(() => {
                console.log("Focus status updated successfully in Firebase.");
                notifyExtension("status-changed");
            }).catch(error => {
                console.error("Error updating focus status in Firebase:", error);
            });
        } catch (error) {
            console.error("Unexpected error with Firebase:", error);
        }

        const totalTime = (workTime / 60) * cyclesCompleted;

        if (cyclesCompleted === 0) {
            resetTimer();
            notify("Session ended. Complete at least one full cycle to save it.", "info");
            return;
        }

        // Save the session to Laravel database
        fetch("{{ route('pomodoro.storeSession') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                cycles: cyclesCompleted,
                session_duration: totalTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === "Session recorded successfully") {
                document.getElementById("sessionCounter").innerText = parseInt(document.getElementById("sessionCounter").innerText) + 1;
                notify(`Session saved: ${cyclesCompleted} cycle(s) completed.`, "success");
                setTimeout(() => location.reload(), 1500);
            } else {
                notify("Couldn't save the session. Please try again.", "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            notify("Couldn't save the session. Check your connection and try again.", "error");
        });

        resetTimer();
    }

    updateDisplay();
    updateMode();

    @if ($autoStart)
        // Arrived from "Start 25 min" on the dashboard: begin right away.
        document.addEventListener("DOMContentLoaded", startTimer);
    @endif
</script>
@endsection
