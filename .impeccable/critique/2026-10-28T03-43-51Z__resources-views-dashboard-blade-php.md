---
target: App dashboard
total_score: 21
max_score: 40
na_heuristics: 
p0_count: 0
p1_count: 3
target_identity: "file:C:\\Users\\andim\\Favorites\\Personal Projects\\FocusPro\\resources\\views\\dashboard.blade.php"
target_fingerprint: "sha256:a479397b9100081251189586ca300e47c4ccb351fa751840d9363d9d7787a6e6"
target_path: "C:\\Users\\andim\\Favorites\\Personal Projects\\FocusPro\\resources\\views\\dashboard.blade.php"
timestamp: 2026-10-28T03-43-51Z
slug: resources-views-dashboard-blade-php
---
# Critique: App dashboard (resources/views/dashboard.blade.php)
Method: dual-agent (A: design review · B: detector + browser evidence)

## Design Health Score — 21/40 (Acceptable)
| # | Heuristic | Score | Key issue |
|---|---|---|---|
| 1 | Visibility of status | 2 | Lifetime totals only; no today/active-timer status; ring renders empty |
| 2 | Match real world | 2 | "Cycles" unexplained; "all caught up" shown to brand-new accounts |
| 3 | User control | 2 | Rows only open edit; no complete/focus from dashboard |
| 4 | Consistency | 2 | Inline complete in Tasks not here; empty CTA secondary vs primary; Cycles vs Cycles completed |
| 5 | Error prevention | 3 | Read-only surface |
| 6 | Recognition vs recall | 3 | Status dot colour-only + title |
| 7 | Flexibility | 1 | No shortcuts, quick-add, or start-on-task |
| 8 | Aesthetic | 3 | Clean; Completed card duplicates ring |
| 9 | Error recovery | 2 | No error/degraded states; content at opacity 0 until JS |
| 10 | Help | 1 | No first-run guidance, no cycles explanation, no extension mention |

## Design Specificity
Category-interchangeable SaaS dashboard (header, 4 KPI cards, list card, donut). Nothing authored for focus work: no today frame, no task→pomodoro link, no blocker presence.
Detector: CLI clean (0 findings, 6 files). Overlay: gpt-thin-border-wide-shadow on closed nav dropdown (components/dropdown.blade.php:32; also modal.blade.php:68, welcome1.blade.php:36) — not dashboard markup; overused-font flag (Plus Jakarta Sans 100%).

## Priority Issues
- [P1-a] Progress ring never draws: strokeDashoffset stuck at full 326.7 vs target 245.0 (motion.js:34 inline style likely overrides line-36 animate). Fix: render final state in markup, animate from-to on the same property, timeout fallback. /impeccable harden
- [P1-b] Due/priority chips hidden below sm (dashboard.blade.php:51); overdue signal lost on mobile. Fix: compact due line under title on mobile. /impeccable adapt
- [P1-c] No task-to-focus action; rows only open edit (line 43); timer has no task context. Fix: hero next-task with Start 25 min, row Focus/Complete actions, N/F shortcuts. /impeccable shape → layout
- [P2] KPI row is lifetime vanity totals duplicating analytics; Completed card duplicates ring. Fix: today/week metrics, drop one, rename Cycles. /impeccable distill
- [P2] Zero-data state false ("all caught up", 0 of 0, four zeros). Fix: first-run branch on totalTasks===0 with primary CTA, try 25 min, install blocker. /impeccable onboard

## Persona Red Flags
Alex: no shortcuts, no inline complete, entrance animations every visit, View all 50x20 target.
Sam: colour-only status dot; count-up rewrites text; 25% unassociated with label; 40px buttons.
Jordan: false "all caught up", zeros, unexplained Cycles, no extension prompt, secondary first CTA.

## Minor Observations
Server-time greeting; "Dr." first name; header CTA wrap on mobile; string-sorted priority; weak active nav state; no <header> landmark; inline Firebase key in pomodoro/index.blade.php:106.

## Questions
- Why is the first screen a report card, not a one-keystroke next focus block?
- Overdue as alarm or invitation?
- Why doesn't the dashboard show whether the blocker is active / what it blocked today?
