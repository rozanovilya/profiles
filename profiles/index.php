<?php
session_start();
require_once "pdo.php";
$query = $pdo->prepare("SELECT * FROM Profile");
$query->execute();
$profiles = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<html>
<head>

</head>
<body>
<h1>Profiles Database</h1>
<?php
 if (!isset($_SESSION['userid'])) { 
 echo '<a href="login.php">Please log in</a><br>';
}

if (count($profiles)==0){
	echo '<p>No profiles found</p>';
}
else {
	echo '<table border="1">';
		echo '<thead>';
			echo '<td>First Name</td>';
			echo '<td>Last Name</td>';
			echo '<td>Email</td>';
			echo '<td>Headline</td>';
			echo '<td>Summary</td>';
			if (isset($_SESSION['userid'])){
				echo '<td>Action</td>';
			}
		echo '</thead>';
		foreach ($profiles as $pr){
			echo '<tr>';
				echo '<td>'.$pr['first_name'].'</td>';
				echo '<td>'.$pr['last_name'].'</td>';
				echo '<td>'.$pr['email'].'</td>';
				echo '<td>'.$pr['headline'].'</td>';
				echo '<td>'.$pr['summary'].'</td>';
				if (isset($_SESSION['userid'])){
					echo "<td><a href="."edit.php?id=".$pr['user_id'].">Edit</a>"." / "."<a href="."delete.php?id=".$pr['user_id'].">Delete</a>"."</td>";
				}
			echo '<tr>';
		}

	echo '</table>';
}


if (isset($_SESSION['userid'])) { 
	echo '<a href="add.php">Add new profile</a><br>';
	echo '<a href="logout.php">Log out</a><br>';
}
?>


</body>	


</html>