# LIRS
Ledang Inn Reservation System (LIRS)

Ledang Inn Reservation System adalah satu projek bagi kursus Web Development di dalam pelan pengajian Diploma Sains Komputer, Universiti Teknologi MARA (UiTM). Projek ini berfungsi sebagai sistem tempahan bilik bagi rumah tamu di UiTM Cawangan Johor Kampus Segamat iaitu Ledang Inn. Sistem ini hanyalah tugas kerja kursus dan tidak diguna pakai oleh pihak UiTM.

A 2016 coursework project (Web Development, Diploma in Computer Science, UiTM): a room-booking system for the
university guest houses at UiTM Johor, Segamat campus. Students log in with their student ID and IC number, apply for a
room for their dates and follow the application; UPK staff manage houses, rooms, students and staff accounts, and
approve or deny the applications. Native PHP with the SB Admin 2 (Bootstrap 3) theme and MySQL. Archived in the
owner's project archive; it runs on PHP 8.5.

## Setup

1. PHP 8.5 with mysqli, and MySQL or MariaDB.
2. Create a database and import `sql/lirs_db_structure.sql` (tables, houses, programmes and rooms; no accounts).
   On a database made from an older dump, run `sql/2026-11-hash-passwords.sql` and then `php tools/hash-passwords.php`.
3. Copy `config.example.php` to `config.php` and fill in the database credentials.
4. Add a UPK staff account: `php tools/add-staff.php <staff ID> "<name>" ["<phone>"]` (asks for the password).
   Staff add students from the Students menu; a student's password is their IC number.
5. Serve the folder, e.g. `php -S 127.0.0.1:8131 -t .` (the built-in server ignores `.htaccess`).

`Data Dictionary (LIRS).pdf` describes the tables.

## Licence

GPL-3.0, see `LICENSE`.
