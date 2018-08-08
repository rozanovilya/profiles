<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['userid'])){
	die("ACCESS DENIED");
}
$profileid = $_GET['id'];
$userid = $_SESSION['userid'];

$query = $pdo->prepare("SELECT * FROM Profile WHERE profile_id= :profileid AND user_id= :userid");
$query->bindParam(':profileid',$profileid);
$query->bindParam(':userid',$userid);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
if ($row === false)
{
	die("Row not found or you don't have rights for this row");
}
if (isset($_POST['delete']))
{
	$query = $pdo->prepare("DELETE FROM Profile WHERE profile_id=:profileid");
	$query->bindparam(':profileid',$profileid);
	$query->execute();
	$_SESSION['message']="Row deleted";
	header( 'Location: index.php' ) ;
    return;
}
?>
<body>
<?php
echo '<p> Are you sure to delete the record of '.$row['first_name'].' '.$row['last_name'].' ?</p>';
?>
<form method="post">
<input type="submit" value="Delete" name="delete">
<br> <a href="index.php">Cancel</a>	
</form>

</body>	