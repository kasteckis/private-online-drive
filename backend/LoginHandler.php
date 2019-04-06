<?php
//Prideda headerius, jei ne ant localhosto turetu ju nereiketi nes jie prideti
//.htaccess faile, taciau localhostas ju neleidzia todel reiktu situs naudoti
//tik localiai o serveri turetu veikti .htaccess failas
//Jeigu meta CORS pranesimus problemos su headeriais
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: Content-Type");

require 'includes/mysql_connection.php';
require 'includes/config.php';

	function LoginMe() //nick, psw
	{
		$rest_json = file_get_contents("php://input");
		$_POST = json_decode($rest_json, true);

		$username = mysqli_real_escape_string($conn, $a); //Nuo sql injection
		$password = mysqli_real_escape_string($conn, $b); //Nuo sql injection
		$sqlGetUserInformation = "SELECT * FROM Users WHERE nick='$username'";
		$getUserInformationResults = mysqli_query($conn, $sqlGetUserInformation);

		if (mysqli_num_rows($getUserInformationResults) > 0)
		{
	  		// output data of each row
		    while($row = mysqli_fetch_assoc($getUserInformationResults))
		    {
				if(password_verify($password, $row['password']))
				{
					$_SESSION['id'] = $row['id'];
					$_SESSION['nick'] = $row['nick'];
					$_SESSION['status'] = $row['status'];
					$_SESSION['password'] = $row['password'];
					$_SESSION['suspended'] = $row['suspended'];
					$_SESSION['lastLogged'] = $row['lastLogged'];

					//Atnaujina kada paskutini kartą buvo jungtasi.
					$tempId = $row['id'];
					$currentDate = date('Y-m-d H:i:s');

					$sqlUpdateUserLastLoggedDate = "UPDATE Users SET lastLogged='$currentDate' WHERE id='$tempId'";
					mysqli_query($conn, $sqlUpdateUserLastLoggedDate);
					header('Location: /manager');
					return "Sveiki atvykę, ".$row['nick']."!<br>";
				}
				else
				{
					return "Blogas slaptažodis<br>";
				}
		    }
		}
		else
		{
			//Tas pats lyg įvestas blogas slaptažodis.
			return "Nerastas vartotojas<br>";
		}
	}


	function Logout()
	{
		$_SESSION['id'] = null;

		$_SESSION['nick'] = null;

		$_SESSION['status'] = null;

		$_SESSION['password'] = null;

		$_SESSION['suspended'] = null;

		$_SESSION['lastLogged'] = null;

		header('Location: /index');

		return true;
	}
?>
