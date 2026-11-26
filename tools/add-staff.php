<?php
// Adds a UPK staff account, or sets a new password for one. Run from the LIRS folder:
//   php tools/add-staff.php <staff ID number> "<name>" ["<phone>"]
// and type the password when asked.
if (PHP_SAPI !== 'cli') {
	http_response_code(404);
	exit;
}

[, $id, $name, $phone] = $argv + [null, null, null, ''];
if (!ctype_digit((string) $id) || !$name) {
	fwrite(STDERR, "Usage: php tools/add-staff.php <staff ID number> \"<name>\" [\"<phone>\"]\n");
	exit(1);
}

require __DIR__ . '/../connectDB.php';

function ask(string $prompt): string
{
	fwrite(STDOUT, $prompt);
	system('stty -echo 2>/dev/null');
	$value = rtrim((string) fgets(STDIN), "\r\n");
	system('stty echo 2>/dev/null');
	fwrite(STDOUT, "\n");
	return $value;
}

$password = ask("Password: ");
if ($password === '') {
	fwrite(STDERR, "No password given.\n");
	exit(1);
}
if (ask("Again: ") !== $password) {
	fwrite(STDERR, "The passwords do not match.\n");
	exit(1);
}

$ok = db_query(
	"INSERT INTO UPK (U_ID, U_NAME, U_PWD, U_PHONE) VALUES (?, ?, ?, ?)
	 ON DUPLICATE KEY UPDATE U_NAME = VALUES(U_NAME), U_PWD = VALUES(U_PWD), U_PHONE = VALUES(U_PHONE)",
	[$id, $name, password_hash($password, PASSWORD_DEFAULT), $phone]
);
echo $ok ? "Saved staff $id.\n" : "Failed: " . mysqli_error($condb) . "\n";
