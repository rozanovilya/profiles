<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['userid'])){
	die("ACCESS DENIED");
}
$profileid = $_GET['id'];
$userid = $_SESSION['userid'];
$header = "Location: edit.php?id=".htmlentities($profileid);

$query = $pdo->prepare("SELECT * FROM Profile WHERE profile_id= :profileid AND user_id= :userid");
$query->bindParam(':profileid',$profileid);
$query->bindParam(':userid',$userid);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
if ($row === false)
{
	die("Row not found or you don't have rights for this row");
}
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])  ) 
{
	if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 )  
	{
		$_SESSION['message'] = 'All field are required';
        $_SESSION['post'] = $_POST;
        header($header);
        return;
    }
    $query = $pdo->prepare("UPDATE Profile SET first_name=:first_name,last_name=:last_name, email=:email, headline=:headline,summary=:summary WHERE profile_id= :profileid AND user_id= :userid");
    $query->bindParam(':profileid',$profileid);
	$query->bindParam(':userid',$userid);
	$query->bindParam(':first_name',$_POST['first_name']);
    $query->bindParam(':last_name',$_POST['last_name']);
    $query->bindParam(':email',$_POST['email']);
    $query->bindParam(':headline',$_POST['headline']);
    $query->bindParam(':summary',$_POST['summary']);
    $query->execute();
    $_SESSION['message']="Record edited";
	header("Location: index.php");
	return;	
}
?>
<body>
<?php	
if (isset($_SESSION['post']) )
{
	$_POST = $_SESSION['post'];
}
else 
{
	$_POST = $row;
}
?>

<form method="post">
First name <input type="text" name="first_name" value="<?=htmlentities($_POST['first_name'])?>"><br>
Last name <input type="text" name="last_name" value="<?=htmlentities($_POST['last_name'])?>"><br>
Email <input type="text" name="email" value="<?=htmlentities($_POST['email'])?>"><br>
Headline <input type="text" name="headline" value="<?=htmlentities($_POST['headline'])?>"><br>
Summary <input type="text" name="summary" value="<?=htmlentities($_POST['summary'])?>"><br> 
<input type="submit" value="Edit">
<br> <a href="index.php">Cancel</a>	
</form>	
<?php
 if ( isset($_SESSION['message']))
 {
 	echo "<p>".$_SESSION['message']."</p>";
 }
unset ($_SESSION['message']); 
?>
</body>	