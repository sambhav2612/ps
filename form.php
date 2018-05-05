<?php
session_start();

require_once "cookiya.php";

/** Database configuration */

define("server", "127.0.0.1");
define("username", "root");
define("password", "");
define("database", "prac");

try {
	$connect = mysqli_connect(server, username, password) or die('Error connecting to MySQL server: ' . mysql_error());
	$db = mysqli_select_db($connect, database) or die("Error selecting MySQL database: " . mysqli_error($connect));
	
	// check if table exists
	if (mysqli_query($connect, "SELECT 1 FROM form") === FALSE) {
		$sql = file_get_contents("data.sql");
		
		if (substr(trim($sql), -1, 1) == ";") {
			mysqli_query($connect, $sql) or die('Error performing query ' . $sql . '\': ' . mysqli_error($connect)); 
		}

		echo "<body>Database and table form created successfully.</body>";
		
		$_SESSION["database"] = "set";
		$_SESSION["table"] = "set";

		mysqli_close($connect);
	} 
} catch (mysqli_sql_exception $e) {
	throw $e;
}

/** */

$nameErr = $emailErr = $usrErr = $pswdErr = "";

if(isset($_SERVER["REQUEST_METHOD"])) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($_POST["fullname"]))	
			$nameErr = "Name is required.";
		else	$fullname = trimmer($_POST["fullname"]);
		
		if (empty($_POST["email"]))	
			$emailErr = "Email is required.";
		else 	{
			$email = trimmer($_POST["email"]);
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$emailErr = "Invalid email format";
			}
		}
		
		if (empty($_POST["username"]))
			$usrErr = "Username is required.";
		else 	$username = trimmer($_POST["username"]);
		
		if (empty($_POST["pswd"]))	
			$pswdErr = "Password is required.";
		else $pswd = trimmer($_POST["pswd"]);

		$connect = mysqli_connect(server, username, password, database) or die('Error connecting to MySQL server: ' . mysql_error());
		$insert_sql = "INSERT INTO form (fullname, email, username, password) VALUES ($fullname, $email, $username, $pswd)";
		
		try {
			if (mysqli_query($connect, $insert_sql) or die('Error performing query ' . $insert_sql . '\': ' . mysqli_error($connect))) {
				echo "Data inserted sucessfully.";
			}
		} catch (mysqli_sql_exception $e) {
			throw $e;
		}
	}
}

function trimmer($value) {
	$value = trim($value);
	$value = stripslashes($value);
	$value = htmlspecialchars($value);

	return $value;
}

session_unset();
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Form</title>

	<style type="text/css"> 
		body { 
			margin: 250px 0px;
			padding: 0px;
			text-align: center;
			align:center;
		}
		
		form {
			display: inline-block;
			text-align:center;
		}

		.error {
			color: red;
		} 
	</style>

</head>
<body>

	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<table>
			<tbody>
				<tr><td><label for="fullname">Full Name: </label></td>
				<td><input type="text" name="fullname" id="fullname" required></td>
				<td><span class="error">* <?php echo $nameErr;?></span></td>
				</tr>

				<tr><td><label for="email">Email: </label></td>
				<td><input type="email" name="email" id="email" required></td>
				<td><span class="error">* <?php echo $emailErr;?></span></td>
				</tr>

				<tr><td><label for="username">Username: </label></td>
				<td><input type="text" name="username" id="username" required></td>
				<td><span class="error">* <?php echo $usrErr;?></span></td>
				</tr>

				<tr><td><label for="pswd">Password: </label></td>
				<td><input type="password" name="pswd" id="pswd" required></td>
				<td><span class="error">* <?php echo $pswdErr;?></span></td>
				</tr>

				<tr><td><input type="submit" value="Login" required></td></tr>
			</tbody>
		</table>
	</form>

</body>
</html>