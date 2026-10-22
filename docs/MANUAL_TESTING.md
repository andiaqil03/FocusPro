# Manual Testing Guide

## 1. Start everything

1. Start WampServer and wait for its tray icon to turn green. This runs MySQL on port 3306
   (user `root`, no password), which is what `.env` expects.
2. In the project folder, run:

```bash
php artisan serve

# Only needed while editing CSS/JS; otherwise `npm run build` once is enough
npm run dev
```

Open http://127.0.0.1:8000.

To browse the database, open phpMyAdmin at http://localhost/phpmyadmin5.2.3/ and log in with
user `root`, an empty password, and server choice **MySQL** (not MariaDB, which is on port 3307).
The app's data is in the `focuspro` database.

Reset to a clean database with demo accounts at any time:

```bash
php artisan migrate:fresh --seed
```

| Account | Email | Password | Role |
|---|---|---|---|
| Normal user | `test@example.com` | `password` | user |
| Admin | `admin@example.com` | `password` | admin |

The admin's email and password come from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env` when set.

Tip: use a normal window for one account and a private/incognito window for the other, so you can be logged in as both at once.

## 2. Checklist

### Auth
- [ ] Register a new account → lands on `/dashboard`
- [ ] Log out, log back in → `/dashboard`
- [ ] Log in as admin → lands on `/admin/dashboard`
- [ ] While logged out, visit `/dashboard` → redirected to `/login`
- [ ] Forgot password → email is written to `storage/logs/laravel.log` (MAIL_MAILER=log); open the link from there

### Tasks (`/tasks`)
- [ ] Create a task with every field filled; create one with only the required fields
- [ ] Submit an empty title → validation error shown
- [ ] Edit a task, change status and priority → changes saved
- [ ] Mark as completed → moves to the "Completed" table, progress % goes up
- [ ] Delete → gone
- [ ] Search by a word in the title/description; filter by category; sort by due date and by priority (P1 first)
- [ ] Export PDF (all / completed) → file downloads and contents match

### Pomodoro (`/pomodoro`)
Set Minutes = 1 and Break = 1 so a cycle takes 2 minutes.
- [ ] Start → timer counts down, Pause/Start work, Reset returns to the set duration
- [ ] Let one work + break cycle finish → alerts appear, cycle counter increases
- [ ] End Session with 0 cycles → warning, nothing saved
- [ ] End Session after ≥1 cycle → "Session saved", session count increases after reload
- [ ] With blocked sites entered (e.g. `youtube.com, facebook.com`), press Start and check Firebase
      Console → Realtime Database → `focusStatus/<your user id>` shows `isFocusing: true` and the list.
      End Session → `isFocusing: false`
- [ ] With the browser extension installed and a session running, open a blocked site → blocked

### Analytics (`/analytics`)
- [ ] Totals match what you did above (sessions, cycles, tasks, completion rate)
- [ ] Switch 7 / 30 days → chart updates
- [ ] Download PDF → numbers match the page

### Profile (`/profile`)
- [ ] Change name/email → saved
- [ ] Upload a JPG/PNG photo → shows in the navbar/profile (needs `php artisan storage:link` once)
- [ ] Upload a non-image (e.g. a .txt) → rejected
- [ ] Change password, log out, log in with the new one
- [ ] Delete account → logged out, can't log back in

### Admin (`/admin/dashboard`, as admin)
- [ ] Totals reflect all users' data
- [ ] Users list shows every user; change a user's role to admin and back
- [ ] Delete a normal user → removed; deleting an admin is refused
- [ ] As a normal user, visit `/admin/dashboard` → blocked

### Security spot checks
- [ ] As user A, copy the URL of one of your tasks' edit page; log in as user B and open it → 403
- [ ] While logged out, visit `/tasks/export-pdf` → redirected to login

## 3. Automated tests

```bash
php artisan test
```

Tests use an in-memory SQLite database (see `phpunit.xml`), so they never touch your MySQL data.
