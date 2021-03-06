<?php
include 'includes/header.php';
require 'includes/config.php';
?>
<!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous"> -->

<body>
<?php

	if($_SESSION['status'] == "admin" || $_SESSION['specAccesToViewFile'] > date('Y-m-d H:i:s'))
	{
    ?>
    <div class="container">
      <?php
			$page='usermanager';
      include 'includes/navbar.php';
			include 'includes/file-nav.php';

			?>
			<div class="sub-page-main">
				<div class="display-menu">
					<!-- Or delete just the button if no buttons on the page -->
				</div>
			<?php

      echo "<div class='main'>";
		// apsaugos jeigu useris cia pateko be sesijos reiksmes
		if($_SESSION['viewingFiles'] == null)
		{
			echo "You should not be here.<br>";
			return;
		}

		$usersDirectory = "./files/".$_SESSION['viewingFiles'];
		$fileList = scandir($usersDirectory);
    echo "<h2>Viewing <b>".$_SESSION['viewingFiles']."</b> files!</h2>";
    echo '<div class="changebox1">';

		echo "<form method='POST'>";
		$thereAreNoFiles = true;
		foreach ($fileList as $key => $value)
		{
			if($value == "." || $value == "..")
				continue;

        echo '<div class="file-list">';
				if(!($_SESSION['specAccesToViewFile'] > date('Y-m-d H:i:s')))
				echo "<input type='checkbox' class='list-check' name='selectedItemsToDelete[]' value='".$value."'>";
          echo "<i class='far fa-file'></i>
    			<a href='./files/".$_SESSION['viewingFiles']."/".$value."'>".$value."</a>";

        echo '</div>';


			$thereAreNoFiles = false;
		}

		//Jeigu nera failu direktorijoja, nerodys delete mygtuko

		if(!$thereAreNoFiles)
		{
			if(!($_SESSION['specAccesToViewFile'] > date('Y-m-d H:i:s')))
				echo "<button class='butonas' style='margin-top:15px;' type='submit' class='changebox-button' name='delete'>Delete selected</button><br>";
		}
		echo "</form>";

		if(isset($_POST['delete']))
		{
			if(isset($_POST['selectedItemsToDelete']))
			{
				$selectedItems = $_POST['selectedItemsToDelete'];
			}
			else
			{
				echo "<font color='red'>Select at least one file!</font><br>";
			}

			if(!empty($selectedItems))
			{
				DeleteTheseFiles2($selectedItems, $_SESSION['viewingFiles']); //FileUpload.php
				//echo '<meta http-equiv="refresh" content="0; />';
			}
		}

		if($thereAreNoFiles)
			echo "<font color='red'>This user has no files in his directory!</font><br>";

		echo "<br><br>";
    echo '</div>';
	}
	else
	{
		echo '<meta http-equiv="refresh" content="0; url=./errorAuthorization.shtml" />';
		echo "You are not authorised to view this page!<br>";
	}
?>
</div>
</div>
</div>



</body>

</html>
