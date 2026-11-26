-- 2026-11 PHP 8.5 port: staff passwords are stored with password_hash(), which needs more than 50 characters.
-- Run once, then run tools/hash-passwords.php to hash the existing plain-text passwords.
ALTER TABLE UPK MODIFY U_PWD varchar(255) NOT NULL;
