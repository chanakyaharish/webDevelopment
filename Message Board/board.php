<?php
session_start();
?>
<html>
<head><title>Message Board</title>
 <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'>
        </script>

</head>
<body>
<h3><i><u>This is a message board where users can view messages,post new messages and reply to old messages.</u></i></h3>
<br/><br/>
<?php

 echo "<form action='login.php'>";
 echo "<input type='submit' value='logout'/>";
 echo "</form>";
error_reporting(E_ALL);
ini_set('display_errors','On');
 

	  if(isset($_SESSION["user"]))
  {
try {
	
	   $postedby= $_SESSION["user"];
  
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	echo "Hello <b> " . $_SESSION["user"] . "</b><br/>";
 
 echo "<form action='board.php' id='message_Form' method='POST'>";
 echo "<input type='hidden' id='msgidholder' name=replyid value=''>";
echo " <textarea placeholder='Write a new post here...' rows='4' cols='50' name='new_message' ></textarea>";
  echo "<input type='submit' value='New Post'/>";
 echo "</form>";
 
 
// 


}catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
  
  
  

}



if(isset($_REQUEST["new_message"]))	{
try{
	if(isset($_REQUEST['replyid'])){
	if( $_REQUEST["new_message"]!=""){
	$replyto=$_REQUEST['replyid'];
	$message=$_REQUEST["new_message"];
	$dbh->beginTransaction();
	
	
	 $dbh->exec('insert into posts values("'.uniqid().'","'.$replyto.'","'.$postedby.'",NOW(),"'.$message.'")')
        or die(print_r($dbh->errorInfo(), true));
  $dbh->commit();
  
	}
	}
	else
	{
		if( $_REQUEST["new_message"]!=""){
	$replyto=NULL;
	$message=$_REQUEST["new_message"];
	$dbh->beginTransaction();
	
	
	 $dbh->exec('insert into posts values("'.uniqid().'","'.$replyto.'","'.$postedby.'",NOW(),"'.$message.'")')
        or die(print_r($dbh->errorInfo(), true));
  $dbh->commit();
  
	}
	}
		
  
  }catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
} 
}



$stmt = $dbh->prepare('select * from posts p,users u where u.username=p.postedby order by datetime DESC');
  $stmt->execute();
  print "<table border='2'><tr><th>Message ID</th><th>Username</th><th>Full Name</th><th>Date/Time</th><th>Message ID(if reply)</th><th>Message</th><th>Reply</th><th></th></tr>";
  while ($row = $stmt->fetch()) {
	  echo "<tr>";
	  echo "<td>";
	  $messageid=$row["id"];
	print_r($row[0]);
	echo "</td>";
	echo "<td>";
	print_r($row[2]);
	echo "</td>";
	echo "<td>";
	print_r($row[7]);
	echo "</td>";
	echo "<td>";
	print_r($row[3]);
	echo "</td>";
	echo "<td>";
	if($row[1]!="NULL"){
	print_r($row[1]);
	}
	echo "</td>";
	echo "<td>";
	print_r($row[4]);
	echo "</td>";

	echo "<td>";
	
    echo "<input type='hidden' name=replyid value='$messageid'>";
    echo "<input type='submit' value='Reply' class='replybtn'>";
    #echo "</form>";
	echo "</td>";
	echo "</tr>";
	
	
  }
  print "</table>";
  
  

  
  }//end of if part
else  
{
	echo "Only logged in users are allowed to view the messages. <br/> Please Visit the Login page<br/>" ;
echo "<a href='login.php'>Login</a>";
}
?>
<script>

$(document).ready(function(){
	$(".replybtn").click(function(){
		var msgid = $(this).parent().siblings().eq(0).html();
		$("#msgidholder").val(msgid);
		$("#message_Form").submit();
//		$.post("/project4/board.php", {"new_message": $("#message_Form textarea").html(),"replyid": msgid}, function(result){
  //      alert("success");
    //});
		
	});
});
</script>
</body>
</html>
