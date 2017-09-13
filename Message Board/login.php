

<?php
session_start();


session_unset();

if(isset($_POST["user"]) && isset($_POST["pass"]))
{
$user=$_POST["user"];

$pass=$_POST["pass"];
$pass=md5($pass);

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  $dbh->beginTransaction();
 
  $dbh->commit();
  
   $stmt = $dbh->prepare('select * from users where username="'.$user.'" and password="'.$pass.'"');
  $stmt->execute();
 
 

  while ($row = $stmt->fetch()) {
  if(isset($row))
	  echo "login successful";
  $_SESSION["user"] = $user;
	header("location: board.php?");
  }

}
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
?>


<h1>Login Page</h1>

<br/>
<p>
<form action="login.php" method="POST">
User Name:
<input type="text" name="user"> <br/> <br/>
Password:
<input type="password" name="pass"><br/>
<input type="submit"/>

<p>