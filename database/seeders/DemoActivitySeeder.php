<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Fills one user's account with ~3 months of realistic activity so the dashboard and
 * analytics have something to show:
 *
 *   php artisan db:seed --class=DemoActivitySeeder
 *   DEMO_USER_EMAIL=someone@example.com php artisan db:seed --class=DemoActivitySeeder
 *
 * Persona: a final-year CS student building their FYP alongside a part-time internship.
 * Output is deterministic (fixed random seed) relative to the day it runs.
 *
 * Re-running is safe: it first removes the tasks it created (matched by title) and the
 * user's focus sessions from before today, then seeds again. Other tasks are left alone.
 */
class DemoActivitySeeder extends Seeder
{
    private const DAYS = 92;

    private const TASKS = [
        'FYP' => [
            'Write FYP chapter 1: introduction', 'Write FYP chapter 2: literature review', 'Draft FYP chapter 3: methodology',
            'Write FYP chapter 4: implementation', 'Write FYP chapter 5: testing and results', 'Design ERD for tasks and sessions',
            'Build Pomodoro timer UI', 'Integrate Firebase Realtime Database', 'Build distraction blocker extension',
            'Implement analytics charts', 'Add PDF export for reports', 'Usability testing with 5 classmates',
            'Fix timer drift in background tabs', 'Prepare FYP progress slides', 'Meet supervisor for progress review',
            'Record FYP demo video', 'Write user manual appendix', 'Prepare final FYP presentation',
        ],
        'Study' => [
            'Revise Software Engineering lecture 6', 'Complete Data Mining assignment 1', 'Complete Data Mining assignment 2',
            'Read paper on spaced repetition', 'Prepare for Cloud Computing quiz', 'Group project meeting: HCI prototype',
            'Finish HCI low-fidelity wireframes', 'Revise for Cloud Computing midterm', 'Study for Data Mining midterm',
            'Summarise Information Security chapter 4', 'Submit Professional Ethics reflection', 'Past-year paper: Software Engineering',
            'Watch recorded lecture: Big Data', 'Lab exercise: Hadoop MapReduce', 'Peer review for HCI group report',
        ],
        'Work' => [
            'Fix login redirect bug on staging', 'Write unit tests for payment module', 'Review pull request: user settings page',
            'Update internship weekly log', 'Prepare demo for team stand-up', 'Refactor invoice export query',
            'Document REST API endpoints', 'Pair programming session with mentor', 'Submit internship logbook to faculty',
            'Final internship presentation', 'Hand over project notes to team', 'Migrate reports page to Tailwind',
        ],
        'Personal' => [
            'Renew driving licence', 'Update resume with internship project', 'Apply for graduate developer roles',
            'Pay PTPTN instalment', 'Book flight home for semester break', 'Clean and organise desk setup',
            'Call mum on Sunday', 'Plan budget for next month', 'Back up laptop to external drive',
            'Write LinkedIn post about internship', 'Fix bicycle brakes', 'Prepare documents for convocation',
        ],
        'Health' => [
            'Book dental check-up', 'Gym: upper body routine', '5 km run at the park', 'Meal prep for the week',
            'Sleep before midnight all week', 'Badminton with housemates', 'Annual health screening', 'Stretch after long coding days',
        ],
    ];

    /** Weighted picks: [value => weight]. */
    private const PRIORITY_WEIGHTS = ['P1' => 12, 'P2' => 30, 'P3' => 35, 'P4' => 23];
    private const CYCLE_WEIGHTS = [1 => 18, 2 => 34, 3 => 28, 4 => 20];

    public function run(): void
    {
        $email = env('DEMO_USER_EMAIL', 'andi@gmail.com');
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->command?->error("No user with email {$email}. Register the account first.");
            return;
        }

        mt_srand(20260727);

        $today = Carbon::today();
        $start = $today->copy()->subDays(self::DAYS);

        DB::transaction(function () use ($user, $start, $today) {
            $this->clearPreviousDemoData($user, $today);

            // The account should look as old as its history.
            if ($user->created_at->gt($start)) {
                $user->forceFill(['created_at' => $start->copy()->subDays(2)->setTime(21, 14)])->save();
            }

            $sessions = $this->seedSessions($user, $start, $today);
            $tasks = $this->seedTasks($user, $start, $today);

            $this->command?->info(sprintf(
                'Seeded %s: %d tasks, %d focus sessions, %d cycles (%s to %s).',
                $user->email, $tasks, $sessions['count'], $sessions['cycles'], $start->toDateString(), $today->toDateString()
            ));
        });
    }

    private function clearPreviousDemoData(User $user, Carbon $today): void
    {
        DB::table('tasks')->where('user_id', $user->id)->whereIn('title', array_merge(...array_values(self::TASKS)))->delete();
        DB::table('pomodoro_sessions')->where('user_id', $user->id)->where('created_at', '<', $today)->delete();
    }

    /** Sessions per day grow as the habit forms, with quieter weekends, holidays and an exam-week peak. */
    private function seedSessions(User $user, Carbon $start, Carbon $today): array
    {
        $holidays = [Carbon::create(null, 8, 31)->toDateString(), Carbon::create(null, 9, 16)->toDateString()];
        $rows = [];
        $cycles = 0;

        for ($day = $start->copy(); $day->lt($today); $day->addDay()) {
            $progress = $start->diffInDays($day) / self::DAYS;      // 0 → 1 across the period
            $expected = 0.9 + 1.9 * $progress;                       // ~1 a day at first, ~3 by the end

            if ($day->isWeekend()) {
                $expected *= 0.45;
            }
            if (in_array($day->toDateString(), $holidays, true)) {
                $expected *= 0.15;
            }
            // Midterm crunch: the two weeks before the last ten days.
            if ($day->between($today->copy()->subDays(24), $today->copy()->subDays(10))) {
                $expected *= 1.4;
            }
            // Some days just don't happen.
            if ($this->chance(0.12)) {
                continue;
            }

            $count = $this->poisson($expected);
            $hours = $this->sessionHours($count);

            foreach ($hours as $hour) {
                $sessionCycles = $this->weighted(self::CYCLE_WEIGHTS);
                // Mostly 25-minute blocks; occasionally the longer 50-minute setting.
                $blockMinutes = $this->chance(0.12) ? 50 : 25;
                $endedAt = $day->copy()->setTime($hour, mt_rand(0, 59), mt_rand(0, 59));

                $rows[] = [
                    'user_id' => $user->id,
                    'cycles' => $sessionCycles,
                    'session_duration' => $sessionCycles * $blockMinutes,
                    'created_at' => $endedAt,
                    'updated_at' => $endedAt,
                ];
                $cycles += $sessionCycles;
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('pomodoro_sessions')->insert($chunk);
        }

        return ['count' => count($rows), 'cycles' => $cycles];
    }

    private function seedTasks(User $user, Carbon $start, Carbon $today): int
    {
        $rows = [];
        $titles = [];
        foreach (self::TASKS as $category => $list) {
            foreach ($list as $title) {
                $titles[] = [$category, $title];
            }
        }

        // Shuffle deterministically, then spread creation dates across the period.
        for ($i = count($titles) - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            [$titles[$i], $titles[$j]] = [$titles[$j], $titles[$i]];
        }

        $span = $start->diffInDays($today) + 6;   // a few tasks are planned ahead
        $total = count($titles);

        foreach ($titles as $index => [$category, $title]) {
            $createdAt = $start->copy()
                ->addDays((int) round($index / $total * $span) + mt_rand(-2, 2))
                ->setTime(mt_rand(8, 22), mt_rand(0, 59));
            if ($createdAt->gt(now())) {
                $createdAt = now()->subHours(mt_rand(1, 48));
            }
            if ($createdAt->lt($start)) {
                $createdAt = $start->copy()->setTime(mt_rand(9, 20), mt_rand(0, 59));
            }

            $priority = $this->weighted(self::PRIORITY_WEIGHTS);
            $hasDueDate = ! $this->chance(0.15);
            $due = $hasDueDate ? $createdAt->copy()->startOfDay()->addDays($this->dueLead($priority)) : null;

            $status = $this->statusFor($due, $today, $createdAt);
            $updatedAt = $createdAt->copy();

            if ($status === 'completed') {
                // Finished somewhere between creation and a day or two after the due date.
                $finishBy = ($due ?? $createdAt->copy()->addDays(10))->copy()->addDays(mt_rand(-1, 1))->setTime(mt_rand(9, 23), mt_rand(0, 59));
                $finishBy = $finishBy->min(now())->max($createdAt->copy()->addHour());
                $updatedAt = $createdAt->copy()->addSeconds(mt_rand(3600, max(3601, (int) $createdAt->diffInSeconds($finishBy))));
            } elseif ($status === 'in_progress') {
                $updatedAt = $createdAt->copy()->addHours(mt_rand(2, 72))->min(now());
            }

            $rows[] = [
                'user_id' => $user->id,
                'title' => $title,
                'description' => $this->descriptionFor($category, $title),
                'category' => $category,
                'due_date' => $due?->toDateString(),
                'priority' => $priority,
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];
        }

        DB::table('tasks')->insert($rows);

        return count($rows);
    }

    private function statusFor(?Carbon $due, Carbon $today, Carbon $createdAt): string
    {
        $ageDays = $createdAt->diffInDays($today);

        // Work that was due weeks ago has almost always been dealt with by now.
        if (($due && $due->lt($today->copy()->subDays(14))) || (! $due && $ageDays > 45)) {
            return $this->pick(['completed' => 97, 'in_progress' => 3]);
        }

        // Recently past due (or old, undated) work is mostly done, but a few things always slip.
        if (($due && $due->lt($today)) || (! $due && $ageDays > 21)) {
            return $this->pick(['completed' => 86, 'pending' => 8, 'in_progress' => 6]);
        }

        return $this->pick(['completed' => 22, 'in_progress' => 30, 'pending' => 48]);
    }

    /** Urgent work is due soon; low-priority work gets more slack. */
    private function dueLead(string $priority): int
    {
        return match ($priority) {
            'P1' => mt_rand(0, 3),
            'P2' => mt_rand(2, 7),
            'P3' => mt_rand(4, 12),
            default => mt_rand(7, 21),
        };
    }

    private function descriptionFor(string $category, string $title): ?string
    {
        if ($this->chance(0.45)) {
            return null;
        }

        return match ($category) {
            'FYP' => $this->pickOne(['Check against the supervisor\'s feedback from last meeting.', 'Keep screenshots for the report.', 'Commit to GitHub when done.', 'Target: first full draft, polish later.']),
            'Study' => $this->pickOne(['Use the lecture slides and tutorial answers.', 'Group work: coordinate in the WhatsApp group.', 'Focus on weak topics first.', 'Submit on the e-learning portal.']),
            'Work' => $this->pickOne(['Ticket assigned during stand-up.', 'Ask the mentor if blocked for more than an hour.', 'Deploy to staging before Friday.', 'Remember to log hours.']),
            'Personal' => $this->pickOne(['Do it on the weekend.', 'Bring IC and a copy of documents.', 'Set a reminder for next month too.']),
            default => $this->pickOne(['Keep it consistent this week.', 'Morning works best.', 'Ask housemates to join.']),
        };
    }

    /** Spread a day's sessions across realistic hours (morning, afternoon, late night). */
    private function sessionHours(int $count): array
    {
        $slots = [8 => 4, 9 => 8, 10 => 10, 11 => 9, 14 => 8, 15 => 9, 16 => 7, 17 => 4, 20 => 7, 21 => 9, 22 => 7, 23 => 3];
        $hours = [];
        for ($i = 0; $i < $count; $i++) {
            $hours[] = $this->weighted($slots);
        }
        sort($hours);

        return $hours;
    }

    private function poisson(float $lambda): int
    {
        $limit = exp(-$lambda);
        $k = 0;
        $p = 1.0;
        do {
            $k++;
            $p *= mt_rand() / mt_getrandmax();
        } while ($p > $limit);

        return min($k - 1, 6);
    }

    private function weighted(array $weights): int|string
    {
        $roll = mt_rand(1, array_sum($weights));
        foreach ($weights as $value => $weight) {
            if (($roll -= $weight) <= 0) {
                return $value;
            }
        }

        return array_key_last($weights);
    }

    private function pick(array $weights): string
    {
        return (string) $this->weighted($weights);
    }

    private function pickOne(array $items): string
    {
        return $items[mt_rand(0, count($items) - 1)];
    }

    private function chance(float $probability): bool
    {
        return mt_rand() / mt_getrandmax() < $probability;
    }
}
