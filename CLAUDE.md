# CLAUDE.md

Project memory for Claude Code. Keep it short and current. Never commit credentials, server addresses, account names
or database names; the hosting details live in the owner's private notes.

## What this is

**LIRS — Ledang Inn Reservation System** (2016 coursework, UiTM Diploma in Computer Science): native PHP pages, the SB
Admin 2 theme (Bootstrap 3, assets committed under `bower_components/`, `dist/`), MySQL. Students apply for a room in a
UiTM Johor (Segamat) guest house; UPK staff manage houses, rooms, students and staff and approve or deny applications.
**Archived project** in the owner's project archive (https://app.dimensikini.xyz/LIRS/).

- History: written in 2016 with `mysql_*` (the `old/` drafts, removed in the port), moved to mysqli in Feb 2021 for
  PHP 7.4, ported to PHP 8.5 on 2026-11-26 (ClickUp `86d4e29px`, under `86d4e29pu`). The folder alone runs on PHP 8.5
  through its own `.htaccess` (`SetHandler application/x-lsphp85`).

## Data

Tables (`sql/lirs_db_structure.sql`, upper-case names: the host is case-sensitive): `HOUSE`, `ROOM` (`R_TYPE` TS/QB/KB,
`R_STAT` 1 = available), `PROGRAMME`, `STUDENT` (login = `S_ID` + `S_IC`, the IC number), `RESERVE` (`RV_STAT`
PG pending / AP approved / DY denied; foreign keys to `STUDENT` and `ROOM`), `UPK` (staff login; `U_PWD` hash).
Student rows hold IC numbers, phones and emails: never copy real data into the repo or an issue.

Two roles: `USER` (student: own details, own reservations) and `SUPK` (staff: everything). A room is free for a stay
when no pending or approved reservation overlaps it (`lirs_busy_rooms()`); approval is refused when another approved one
overlaps.

## Code conventions (2026 port — keep them)

- `connectDB.php` reads `config.php` (git-ignored; copy `config.example.php`), sets `mysqli_report(MYSQLI_REPORT_OFF)`,
  a non-strict `sql_mode` and the `Asia/Kuala_Lumpur` time zone, and defines `db_query($sql, [$params])` (prepared
  statements), `db_row()`, `e()` (HTML escape), `js()` (JSON for scripts and attributes), `request_text()`,
  `request_id()`, `request_date()`, `request_password()`, `js_alert()` (the alert-then-redirect pages) and
  `require_login('USER'|'SUPK')`. Never interpolate request data into SQL.
- Session keys are prefixed `lirs_` (other apps on the same host share the session cookie).
- Pages start with `<?php ob_start(); ?>` so `session_start()` and `header()` work after HTML.
- Staff passwords: `password_hash()` / `password_verify()`; a plain-text one left in the DB is hashed on its first login.
- `.htaccess` denies `config*.php`, `connectDB.php`, `sql/`, `tools/`, `*.md`, `*.sql`, logs. UI text is English.
- No AI attribution in commits or PRs.

## Local test

PHP 8.5 with mysqli and a local MariaDB (the owner uses EnvKit). Import a dump, run `sql/2026-11-hash-passwords.sql`
and `php tools/hash-passwords.php`, add staff with `tools/add-staff.php`, then
`php -d display_errors=1 -d error_reporting=-1 -S 127.0.0.1:8131 -t .`. The built-in server ignores `.htaccess`, so
check the deny rules on the real host.

## Deploy

No git on the host. Upload `master` with `git archive master | ssh … 'tar -x -C …/public_html/LIRS'`; `config.php`
(mode 600) stays on the server and is never overwritten. Files removed from git (`old/`, `error_log`, the root `.sql`
files) must be deleted on the server by hand. Schema changes go in `sql/` and are run by hand. Every change on the
server needs the owner's OK: show the exact commands first. Smoke test: pages answer 200 on PHP 8.5, and `config.php`,
`connectDB.php`, `sql/`, `tools/`, `error_log` answer 403.
