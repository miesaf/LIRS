<?php
	// Database connection. The credentials live in config.php (not in git); copy config.example.php to start.
	mysqli_report(MYSQLI_REPORT_OFF);
	date_default_timezone_set('Asia/Kuala_Lumpur');

	$lirs_config = require __DIR__ . '/config.php';
	$condb = mysqli_connect($lirs_config['db_host'], $lirs_config['db_user'], $lirs_config['db_pass'], $lirs_config['db_name']);
	unset($lirs_config);

	if (!$condb)
	{
		http_response_code(500);
		die("Database connection failed.");
	}

	// The 2016 code relies on MySQL's non-strict mode.
	mysqli_query($condb, "SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");

	// Runs a prepared statement: a mysqli_result for SELECT, true or false otherwise.
	function db_query(string $sql, array $params = [])
	{
		global $condb;
		return mysqli_execute_query($condb, $sql, $params);
	}

	// The first row of a SELECT as an associative array, or null.
	function db_row(string $sql, array $params = []): ?array
	{
		$result = db_query($sql, $params);
		$row = $result ? mysqli_fetch_assoc($result) : null;
		return $row ?: null;
	}

	// Number of rows the last INSERT, UPDATE or DELETE changed.
	function db_affected(): int
	{
		global $condb;
		return (int) mysqli_affected_rows($condb);
	}

	// Escapes a value for HTML output.
	function e($value): string
	{
		return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
	}

	// A value as a JavaScript literal, safe inside a <script> block or an HTML attribute.
	function js($value): string
	{
		return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
	}

	// A text value from $_GET or $_POST (trimmed), or null when it is missing (or not a single value).
	function request_text(array $source, string $key): ?string
	{
		$value = $source[$key] ?? null;
		return is_string($value) ? trim($value) : null;
	}

	// A password from $_POST as typed (not trimmed), or '' when it is missing or not a single value.
	function request_password(string $key): string
	{
		$value = $_POST[$key] ?? null;
		return is_string($value) ? $value : '';
	}

	// A positive whole-number ID from $_GET or $_POST, or null.

	function request_id(array $source, string $key): ?int
	{
		$id = filter_var($source[$key] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
		return $id === false ? null : $id;
	}

	// A date (YYYY-MM-DD) from $_GET or $_POST, or null when it is missing or not a real date.
	function request_date(array $source, string $key): ?string
	{
		$value = request_text($source, $key);
		if ($value === null || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value))
		{
			return null;
		}
		[$y, $m, $d] = array_map('intval', explode('-', $value));
		return checkdate($m, $d, $y) ? $value : null;
	}

	// Shows a browser alert, then goes to $url (or back one page when $url is null). The 2016 pages work this way.
	function js_alert(string $message, ?string $url = null): void
	{
		echo '<html><head><script>window.alert(' . js($message) . ');'
			. ($url === null ? 'window.history.go(-1);' : '') . '</script>'
			. ($url === null ? '' : '<meta http-equiv="refresh" content="0; url=' . e($url) . '" />')
			. '</head></html>';
	}

	// Sends a visitor who is not logged in (or, with $priv, not a "USER" student or a "SUPK" staff) to the login page.
	function require_login(?string $priv = null): void
	{
		if (empty($_SESSION['lirs_login']) || ($priv !== null && ($_SESSION['lirs_priv'] ?? '') !== $priv))
		{
			header("Location: index.php?error=exp");
			exit;
		}
	}

	// Room type codes and reservation status codes.
	const LIRS_ROOM_TYPES = ['TS' => 'Two Single Bed', 'QB' => 'One Queen Bed', 'KB' => 'One King Bed'];
	const LIRS_STATUS = ['AP' => 'Approved', 'DY' => 'Denied', 'PG' => 'Pending'];
	const LIRS_STATUS_COLOUR = ['AP' => '#2eb82e', 'DY' => 'red', 'PG' => '#ff9900'];

	// IDs of the rooms already reserved (pending or approved; with $approved_only, approved only) for any night
	// between $in and $out, leaving out reservation $except.
	function lirs_busy_rooms(string $in, string $out, ?int $except = null, bool $approved_only = false): array
	{
		$result = db_query(
			"SELECT DISTINCT RV_ROOM FROM RESERVE WHERE RV_IN < ? AND RV_OUT > ? AND RV_ID <> ? AND "
			. ($approved_only ? "RV_STAT = 'AP'" : "RV_STAT <> 'DY'"),
			[$out, $in, $except ?? 0]
		);
		$rooms = [];
		while ($result && ($row = mysqli_fetch_assoc($result)))
		{
			$rooms[(string) $row['RV_ROOM']] = true;
		}
		return $rooms;
	}

	// True when $room exists, is available (R_STAT) and is free between $in and $out (leaving out reservation $except).
	function lirs_room_free(string $room, string $in, string $out, ?int $except = null): bool
	{
		$row = db_row("SELECT R_ID FROM ROOM WHERE R_ID = ? AND R_STAT = 1", [$room]);
		return $row !== null && !isset(lirs_busy_rooms($in, $out, $except)[(string) $row['R_ID']]);

	}

	// A date or datetime from the DB as "dd Mon yyyy" (with the time first for a datetime).
	function lirs_date(?string $value, bool $with_time = false): string
	{
		$time = $value ? strtotime($value) : false;
		if ($time === false)
		{
			return '';
		}
		return $with_time ? date('H:i:s, d M Y', $time) : date('d M Y', $time);
	}
?>
