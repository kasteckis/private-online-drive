<?php
session_start();
require 'includes/mysql_connection.php';
require 'includes/config.php';

//Includins visus skriptus is backendo, nežinau ar funkcijas į vieną .php failą kraut ar į atskirus
foreach(glob("backend/*.php") as $back)
{
    require $back;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $WebsiteTitle; ?></title>
<link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" /> 
<link rel="stylesheet" href="css/styleNewUser.css"> 

</head>

<body>
<?php

	if($_SESSION['status'] == "admin")
	{
		?>
		<div class="background">
			<div class="back">
				<?php
				//Mygtukas atgal
				echo '<form action="/usermanager">';
				echo '<input type="submit" value="Back" />';
				echo '</form>';
				?>
			</div>
			<div class="box">
				<?php
				echo "<h3>Create new user</h3>";
				echo '<form method="POST">';?>
				<div class="inputs">
					<?php
					echo '<input type="text" name="nick" placeholder="Nickname"></input><br>';
					echo '<select name="role">';
					echo '<option value="user">User</option>';
					echo '<option value="admin">Admin</option>';
					echo '</select>';
					echo '<input type="password" name="password" placeholder="Pasword"></input>';
					echo "<button type='submit' name='createNewUser'>Create</button>"; ?>
				</div>
				<?php
				echo '</form>';

				if(isset($_POST['createNewUser']))
				{
					//Validacija
					$canICreateUser = true;
					
					$nick = mysqli_real_escape_string($conn, $_POST['nick']);
					$status = mysqli_real_escape_string($conn, $_POST['role']);
					$password = mysqli_real_escape_string($conn, $_POST['password']);
					$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
					$suspended = 0;
					$lastLogged = date("0000-00-00 00:00");

					$validationErrors = CheckForValidation($nick, $status, $password, $hashedPassword, $suspended, $lastLogged);

					if(empty($validationErrors))
					{
						CreateUser($nick, $status, $hashedPassword, $suspended, $lastLogged); //UserManagement.php
						echo "Account with name ".$nick." was created!<br>";
						echo '<meta http-equiv="refresh" content="0; url=./usermanager" />';
					}
					else
					{
						foreach ($validationErrors as $key => $value)
						{
							echo "<font color='red'>".$value."</font><br>";
						}
					}
				}?>
			</div>
		</div>
		<?php
	}
	else
	{
		echo '<meta http-equiv="refresh" content="0; url=./errorAuthorization.shtml" />';
		echo "You are not authorised to view this page!<br>";
	}

?>



</body>

</html>
