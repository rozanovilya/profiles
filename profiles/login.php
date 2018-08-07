<?php
session_start(); 
require_once "pdo.php";
unset($_SESSION['errormessage']);
$email = htmlentities($_POST['email']);
$pass = htmlentities($_POST['pass']);
$options = [
    'cost' => 11,
    'salt' => "Соленый текст для соли",
//я знаю, что сейчас метод со своем солью устарел, но пара функций password_hash и password_verify не заработала из-за проблем с кавычками    
];
$hash = password_hash($pass, PASSWORD_BCRYPT, $options);
if (isset($email) &&  isset($pass))
{
	if (strlen($email) >1)
	{
	$query = $pdo->prepare("SELECT user_id, name, email, password FROM users WHERE email=:email" );
	$query->bindParam(':email',$email);
	$query->execute();
	$row = $query->fetch(PDO::FETCH_ASSOC);

	if (!isset($row['email'])) {
		$_SESSION['errormessage'] = "Email not found";

	}
	else
		if ($hash == $row['password'])
		 {
			$_SESSION['userid']= $row['user_id'];
			unset($_SESSION['errormessage']);
			header("Location: index.php");
			return;
		}
		else
		{
			$_SESSION['errormessage'] = "Password is incorrect";
		}
	}	

}
 ?>
<head>
<script type="text/javascript">
	function doValidate() {
		email = document.getElementById('email').value;
		pass = document.getElementById('pass').value;
		if (email == null || email ==""){
			alert("Email shouldn't be empty");
			return false;
		}
		if (email.search('@') == -1){
			alert("Email must contain @");
			return false;
		}
		if (pass == null || pass ==""){
			alert("password shouldn't be empty");
			return false;
		}
		return true;		
	}
 </script>
</head>	


<body>
<h1> Enter e-mail and password</h1>
<?php
if (isset($_SESSION['errormessage'])) echo "<p>".$_SESSION['errormessage']."</p>";
unset($_SESSION['errormessage']);
	?>
<form method="post">
E-mail <input type="text" name="email" id="email"><br/>
Password <input type="text" name="pass" id="pass"><br/>
<input type="submit" onclick="return doValidate();" value="Log in">	

</form>	
</body>	