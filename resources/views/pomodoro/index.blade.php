@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 text-center">
    <h2 class="text-3xl font-bold text-gray-900 mb-6">Focus Session</h2>

    <!-- White Background -->
    <div class="bg-white p-8 rounded-lg shadow-lg inline-block w-full max-w-xl">
        <h3 class="text-xl font-bold text-gray-900 mb-4 bg-white px-4 py-2 rounded-md">"Focus Creates Clarity and Purpose"</h3>

        <!-- Timer & Settings -->
        <div class="grid grid-cols-3 gap-6 text-gray-900">
            <!-- Work Duration -->
            <div class="flex flex-col items-center">
                <input type="number" id="workDuration" value="25" min="1"
                    class="w-20 text-center bg-white text-gray-900 rounded-md p-3 text-lg font-bold border border-gray-400 shadow-md">
                <p class="text-lg font-semibold mt-2">Minutes</p>
            </div>

            <!-- Timer Display -->
            <div class="flex flex-col items-center">
                <h1 id="timer" class="text-5xl font-extrabold text-gray-900 bg-white px-6 py-3 rounded-md border border-gray-400 shadow-md">25:00</h1>
                <p class="text-lg font-semibold mt-2">Timer</p>
            </div>

            <!-- Break Duration -->
            <div class="flex flex-col items-center">
                <input type="number" id="breakDuration" value="5" min="1"
                    class="w-20 text-center bg-white text-gray-900 rounded-md p-3 text-lg font-bold border border-gray-400 shadow-md">
                <p class="text-lg font-semibold mt-2">Break</p>
            </div>
        </div>

        <!-- Timer Controls -->
        <div class="mt-6">
            <button onclick="startTimer()" id="startBtn"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg">Start</button>

            <button onclick="pauseTimer()" id="pauseBtn"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg hidden">Pause</button>

            <button onclick="resetTimer()"
                class="bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg ml-2">Reset</button>
        </div>

        <!-- Cycle & Session Tracker -->
        <h3 class="text-gray-900 mt-6 text-lg font-semibold bg-white px-4 py-2 rounded-md shadow-md">
            <span id="cycleCounter">{{ $cyclesCompleted }}</span> Cycle(s) Completed
        </h3>

        <h3 class="text-gray-900 mt-4 text-lg font-semibold bg-white px-4 py-2 rounded-md shadow-md">
            <span id="sessionCounter">{{ $sessionsCompleted }}</span> Focus Session(s) Done
        </h3>

        <!-- End Session Button -->
        <button onclick="endSession()"
            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md text-lg mt-6">
            End Session
        </button>
    </div>
</div>

<script>
    let workTime = parseInt(document.getElementById("workDuration").value) * 60;
    let breakTime = parseInt(document.getElementById("breakDuration").value) * 60;
    let timeLeft = workTime;
    let isWork = true;
    let cyclesCompleted = 0;
    let isRunning = false;
    let timer;

    function updateDisplay() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        document.getElementById("timer").innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }

    function updateTimerValues() {
        workTime = parseInt(document.getElementById("workDuration").value) * 60;
        breakTime = parseInt(document.getElementById("breakDuration").value) * 60;
        resetTimer();
    }

    document.getElementById("workDuration").addEventListener("change", updateTimerValues);
    document.getElementById("breakDuration").addEventListener("change", updateTimerValues);

    function startTimer() {
    if (!isRunning) {
        isRunning = true;
        document.getElementById("startBtn").classList.add('hidden');
        document.getElementById("pauseBtn").classList.remove('hidden');

        timer = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                updateDisplay();
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
                    
                    // ✅ FIX: Increase the cycle count when a full work + break session is completed
                    cyclesCompleted++;
                    document.getElementById("cycleCounter").innerText = cyclesCompleted;
                }
                startTimer(); // Restart the timer for next phase
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

        if (cyclesCompleted === 0) {
            alert("No cycles completed yet. Please complete at least one cycle before ending the session.");
            return;
        }

        let totalSessionTime = (workTime / 60) * cyclesCompleted; // Total minutes spent

        alert(`Session ended! You completed ${cyclesCompleted} cycle(s).`);

        fetch("{{ route('pomodoro.storeSession') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                cycles: cyclesCompleted,
                session_duration: totalSessionTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === "Session recorded successfully") {
                // ✅ Update session count UI after saving
                let sessionCount = parseInt(document.getElementById("sessionCounter").innerText) + 1;
                document.getElementById("sessionCounter").innerText = sessionCount;

                alert("Session saved successfully!");
                location.reload();
            } else {
                alert("Failed to save session. Please try again.");
            }
        })
        .catch(error => console.error("Error:", error));
    }


    updateDisplay();
</script>
@endsection
