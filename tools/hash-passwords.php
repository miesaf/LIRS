<?php
// One-time: hashes every plain-text staff password in UPK. Run from the LIRS folder after
// sql/2026-11-hash-passwords.sql:  php tools/hash-passwords.php
if (PHP_SAPI !== 'cli') {
	http_response_code(404);
	exit;
}

require __DIR__ . '/../connectDB.php';

$width = db_row("SELECT CHARACTER_MAXIMUM_LENGTH AS len FROM information_schema.COLUMNS
	WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'UPK' AND COLUMN_NAME = 'U_PWD'");
if ((int) ($width['len'] ?? 0) < 255) {
	fwrite(STDERR, "Run sql/2026-11-hash-passwords.sql first (UPK.U_PWD is too short for a hash).\n");
	exit(1);
}

$rows = db_query("SELECT U_ID, U_PWD FROM UPK");
$hashed = 0;
$skipped = 0;
foreach (mysqli_fetch_all($rows, MYSQLI_ASSOC) as $row) {
	if (password_get_info($row['U_PWD'])['algo'] !== null) {
		$skipped++;
		continue;
	}
	db_query("UPDATE UPK SET U_PWD = ? WHERE U_ID = ?", [password_hash($row['U_PWD'], PASSWORD_DEFAULT), $row['U_ID']]);
	$hashed++;
}

echo "Hashed: $hashed, already hashed: $skipped\n";
