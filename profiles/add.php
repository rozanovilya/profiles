<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['userid']))
{
	die("ACCESS DENIED");
}
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])  ) 
{
	if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 )  
	{
		$_SESSION['message'] = 'All field are required';
        $_SESSION['post'] = $_POST;
        header("Location: add.php");
        return;
    }
    $query = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary)
    	VALUES (:user_id,:first_name,:last_name,:email,:headline,:summary) ');
    $query->bindParam(':user_id',$_SESSION['userid']);
    $query->bindParam(':first_name',$_POST['first_name']);
    $query->bindParam(':last_name',$_POST['last_name']);
    $query->bindParam(':email',$_POST['email']);
    $query->bindParam(':headline',$_POST['headline']);
    $query->bindParam(':summary',$_POST['summary']);
    $query->execute();
	$_SESSION['message']="Record added";
	header("Location: index.php");
	return;              
}

?>
<html>
<head>
</head>
<body>
<?php
$_POST = $_SESSION['post'];
?>
<form method="post">
First name <input type="text" name="first_name" value="<?=htmlentities($_POST['first_name'])?>"><br>
Last name <input type="text" name="last_name" value="<?=htmlentities($_POST['last_name'])?>"><br>
Email <input type="text" name="email" value="<?=htmlentities($_POST['email'])?>"><br>
Headline <input type="text" name="headline" value="<?=htmlentities($_POST['headline'])?>"><br>
Summary <input type="text" name="summary" value="<?=htmlentities($_POST['summary'])?>"><br> 
<input type="submit" value="Add new profile">
</form>	
<?php
 if ( isset($_SESSION['message']))
 {
 	echo "<p>".$_SESSION['message']."</p>";
 }
unset ($_SESSION['message']); 
?>
</body>
</html>