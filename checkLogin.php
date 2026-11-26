<?php
	session_start();

	require_once __DIR__ . '/connectDB.php';

	$enteredID	= request_text($_POST, 'uname') ?? '';
	$enteredPW	= request_password('pword');
	$enteredTP	= request_text($_POST, 'type') ?? '';

	// Logs the account in and goes to the main page.
	function login(string $ident, string $priv, string $name): never
	{
		session_regenerate_id(true);
		$_SESSION['lirs_login']	= "YES";
		$_SESSION['lirs_ident']	= $ident;
		$_SESSION['lirs_priv']	= $priv;
		$_SESSION['lirs_name']	= $name;

		header("Location: main.php");
		exit;
	}

	// Checking Student Account: the student ID, and the IC number as the password ######################################
	function user(string $enteredID, string $enteredPW): string
	{
		$row = db_row("SELECT S_ID, S_NAME, S_IC FROM STUDENT WHERE S_ID = ?", [$enteredID]);

		if ($row && $enteredPW !== '' && hash_equals((string) $row["S_IC"], $enteredPW))
		{
			login((string) $row["S_ID"], "USER", (string) $row["S_NAME"]);
		}

		return "failed";
	}

	// Checking Staff (UPK) Account ###################################################################################
	function admin(string $enteredID, string $enteredPW): string
	{
		$row = db_row("SELECT U_ID, U_NAME, U_PWD FROM UPK WHERE U_ID = ?", [$enteredID]);
		if (!$row || $enteredPW === '')
		{
			return "failed";
		}

		$stored	= (string) $row["U_PWD"];
		$hashed	= password_get_info($stored)['algo'] !== null;
		$valid	= $hashed ? password_verify($enteredPW, $stored) : hash_equals($stored, $enteredPW);
		if (!$valid)
		{
			return "failed";
		}

		// A password still in plain text (tools/hash-passwords.php not run yet) is hashed on its first login.
		if (!$hashed || password_needs_rehash($stored, PASSWORD_DEFAULT))
		{
			db_query("UPDATE UPK SET U_PWD = ? WHERE U_ID = ?", [password_hash($enteredPW, PASSWORD_DEFAULT), $row["U_ID"]]);
		}

		login((string) $row["U_ID"], "SUPK", (string) $row["U_NAME"]);
	}

	if (!ctype_digit($enteredID))
	{
		$problem = in_array($enteredTP, ["1", "2"], true) ? "failed" : "type";
	}
	elseif ($enteredTP === "1")
	{
		$problem = user($enteredID, $enteredPW);
	}
	elseif ($enteredTP === "2")
	{
		$problem = admin($enteredID, $enteredPW);
	}
	else
	{
		$problem = "type";
	}

	header("Location: index.php?error=$problem");
	exit;
?>
