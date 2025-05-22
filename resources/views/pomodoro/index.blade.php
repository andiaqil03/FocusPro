@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 text-center">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Focus Session</h2>

    <div class="bg-blue-100 p-8 rounded-lg shadow-lg inline-block w-full max-w-xl">
        <h3 class="text-xl font-bold text-gray-900 mb-4 bg-white px-4 py-2 rounded-md">"Focus Creates Clarity and Purpose"</h3>

        <!-- Timer & Settings -->
        <div class="grid grid-cols-3 gap-6 text-gray-900">
            <div class="flex flex-col items-center">
                <input type="number" id="workDuration" value="25" min="1" class="w-20 text-center bg-white text-gray-900 rounded-md p-3 text-lg font-bold border border-gray-400 shadow-md">
                <p class="text-lg font-semibold mt-2">Minutes</p>
            </div>
            <div class="flex flex-col items-center">
                <h1 id="timer" class="text-5xl font-extrabold text-gray-900 bg-white px-6 py-3 rounded-md border border-gray-400 shadow-md">25:00</h1>
                <p class="text-lg font-semibold mt-2">Timer</p>
            </div>
            <div class="flex flex-col items-center">
                <input type="number" id="breakDuration" value="5" min="1" class="w-20 text-center bg-white text-gray-900 rounded-md p-3 text-lg font-bold border border-gray-400 shadow-md">
                <p class="text-lg font-semibold mt-2">Break</p>
            </div>
        </div>

        <!-- Controls -->
        <div class="mt-6">
            <button onclick="startTimer()" id="startBtn" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg">Start</button>
            <button onclick="pauseTimer()" id="pauseBtn" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg hidden">Pause</button>
            <button onclick="resetTimer()" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg ml-2">Reset</button>
        </div>

        <!-- Blocked Websites Input -->
        <div class="mt-6 text-left">
            <label for="blockedSites" class="block text-lg font-semibold text-gray-900">Blocked Websites</label>
            <textarea id="blockedSites" rows="3" class="w-full mt-2 p-3 border border-gray-400 rounded-lg text-gray-900"
                      placeholder="e.g. youtube.com, facebook.com"></textarea>
            <p class="text-sm text-gray-600 mt-1">Enter one or more sites separated by commas.</p>
        </div>

        <!-- Stats -->
        <div class="mt-6 text-gray-900">
            <p><span id="cycleCounter">{{ $cyclesCompleted }}</span> Cycle(s) Completed</p>
            <p><span id="sessionCounter">{{ $sessionsCompleted }}</span> Pomodoro Session(s) Done</p>
        </div>

        <!-- End Button -->
        <button onclick="endSession()" class="bg-gray-700 hover:bg-gray-800 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg mt-6">
            End Session
        </button>
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

    document.addEventListener("DOMContentLoaded", () => {
        console.log("Firebase initialized successfully");
    });

    let workTime = 25 * 60;
    let breakTime = 5 * 60;
    let timeLeft = workTime;
    let isWork = true;
    let cyclesCompleted = 0;
    let isRunning = false;
    let timer;

    function updateDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById("timer").innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }

    function updateTimerValues() {
        workTime = parseInt(document.getElementById("workDuration").value) * 60;
        breakTime = parseInt(document.getElementById("breakDuration").value) * 60;
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

    function checkDistraction() {
        const blocked = JSON.parse(localStorage.getItem("blockedSites") || "[]");
        blocked.forEach(site => {
            if (document.referrer.includes(site) || location.href.includes(site)) {
                alert(`🚫 Focus Mode Active!\nYou're trying to access: ${site}\nStay focused!`);
            }
        });
    }

    function startTimer() {
        if (!isRunning) {
            const blockedSites = getBlockedSites();

            try {
                firebase.database().ref('focusStatus/' + userId).set({
                    isFocusing: true,
                    blockedSites: blockedSites
                }).then(() => {
                    console.log("Focus status updated successfully in Firebase.");
                }).catch(error => {
                    console.error("Error updating focus status in Firebase:", error);
                });
            } catch (error) {
                console.error("Unexpected error with Firebase:", error);
            }

            isRunning = true;
            document.getElementById("startBtn").classList.add('hidden');
            document.getElementById("pauseBtn").classList.remove('hidden');

            timer = setInterval(() => {
                if (timeLeft > 0) {
                    timeLeft--;
                    updateDisplay();
                    checkDistraction();
                } else {
                    clearInterval(timer);
                    if (isWork) {
                        alert("Work session done! Take a break.");
                        timeLeft = breakTime;
                        isWork = false;
                    } else {
                        alert("Break over! Back to work.");
                        timeLeft = workTime;
                        isWork = true;
                        cyclesCompleted++;
                        document.getElementById("cycleCounter").innerText = cyclesCompleted;
                    }
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
    }

    function resetTimer() {
        clearInterval(timer);
        isRunning = false;
        timeLeft = workTime;
        updateDisplay();
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
            }).catch(error => {
                console.error("Error updating focus status in Firebase:", error);
            });
        } catch (error) {
            console.error("Unexpected error with Firebase:", error);
        }

        const totalTime = (workTime / 60) * cyclesCompleted;
        
        if (cyclesCompleted === 0) {
            alert("No cycles completed yet. Please complete at least one cycle before ending the session.");
            return;
        }

         // Save the session to Laravel database
         fetch("{{ route('pomodoro.storeSession') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
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
                let sessionCount = parseInt(document.getElementById("sessionCounter").innerText) + 1;
                document.getElementById("sessionCounter").innerText = sessionCount;

                alert("Session saved successfully!");
                location.reload();
            } else {
                alert("Failed to save session. Please try again.");
            }
        })
        .catch(error => console.error("Error:", error));

        alert(`Session ended. You completed ${cyclesCompleted} cycle(s).`);
        //temporary remove this increment
        //document.getElementById("sessionCounter").innerText = parseInt(document.getElementById("sessionCounter").innerText) + 1;
        resetTimer();
    }

    updateDisplay();
</script>
@endsection
