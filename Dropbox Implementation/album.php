<?php

// display all errors on the browser
error_reporting(E_ALL);
ini_set('display_errors','On');



if(isset($_POST["user"]) && isset($_POST["pass"]))
{
$user=$_POST["user"];

$pass=$_POST["pass"];
$pass=($pass);

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  $dbh->beginTransaction();
 
  $dbh->commit();
  
   $stmt = $dbh->prepare('select * from login where user="'.$user.'" and password="'.$pass.'"');
  $stmt->execute();
 
 

  while ($row = $stmt->fetch()) {
  if(isset($row))
	  echo "login successful";
  $_SESSION["user"] = $user;
	header("location: album.php?");
  }

}
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}

// if there are many files in your Dropbox it can take some time, so disable the max. execution time
set_time_limit(0);

require_once("DropboxClient.php");

// you have to create an app at https://www.dropbox.com/developers/apps and enter details below:
$dropbox = new DropboxClient(array(
	'app_key' => "j3bp8byokq85av6",      // Put your Dropbox API key here
	'app_secret' => "e3t7aee7crjqnqo",   // Put your Dropbox API secret here
	'app_full_access' => false,
),'en');


// first try to load existing access token
$access_token = load_token("access");
if(!empty($access_token)) {
	$dropbox->SetAccessToken($access_token);
	//echo "loaded access token:";
	//print_r($access_token);
}
elseif(!empty($_GET['auth_callback'])) // are we coming from dropbox's auth page?
{
	// then load our previosly created request token
	$request_token = load_token($_GET['oauth_token']);
	if(empty($request_token)) die('Request token not found!');
	
	// get & store access token, the request token is not needed anymore
	$access_token = $dropbox->GetAccessToken($request_token);	
	store_token($access_token, "access");
	delete_token($_GET['oauth_token']);
}

// checks if access token is required
if(!$dropbox->IsAuthorized())
{
	// redirect user to dropbox auth page
	$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?auth_callback=1";
	$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
	$request_token = $dropbox->GetRequestToken();
	store_token($request_token, $request_token['t']);
	die("Authentication required. <a href='$auth_url'>Click here.</a>");
}

echo "<pre>";
//echo "<b>Account:</b>\r\n";
//print_r($dropbox->GetAccountInfo());

$files = $dropbox->GetFiles("",false);


?>
<form action="album.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>




<?php
if(isset($_FILES['fileToUpload'])){
	$UploadName=$_FILES['fileToUpload']['name'];
	$file_type = $_FILES['fileToUpload']['type']; //returns the mimetype

$allowed = array('image/jpeg','image/jpg','image/png','image/gif');
if(!in_array($file_type, $allowed)) {
	
 echo( "Only jpg files are allowed");
  
}

	else{
	
	
	
	
	//echo "\r\n\r\n<b>Uploading $UploadName:</b>\r\n";
	$meta = $dropbox->UploadFile($_FILES['fileToUpload']['tmp_name'], $UploadName);
	
	
	}
}
if(isset($_GET['Delete'])){
	$tmp=$_GET['Delete'];
	$dropbox->Delete($tmp);
	
}
echo "<form action='album.php' method='GET'>";
   print "<table border='2'><tr><th>FileName</th><th>Delete</th></tr>";
   $files = $dropbox->GetFiles("",false);
   $sum=0;
   foreach($files as $f){
	   print "<tr>";
	 print "<td><a href='album.php?path=$f->path'>".$f->path."</a></td>";
  
    print "<td><button value='$f->path' name='Delete' type='submit'>Delete</button></td>";
	$time_pre = microtime(true);

$time_post = microtime(true);
$exec_time = $time_post - $time_pre;
if(isset($exec_time)){
$sum=$sum+$exec_time;

	print "<td>.$exec_time.</td></tr>";
	}
   }
  echo " </form>";

if(isset($sum))
{
	print "total time to all the picture= ".$sum."";
}
 if(isset($_GET['path'])){ 

 $path=$_GET['path'];
 
 
  
  echo "<img src='".$dropbox->GetLink($path,false)."' height='300' width='200'/></br>";
  
  foreach($files as $f){
	  if($f->path==$path){
		 
		  $test = "test_".basename($f->path);
		  
		  
	  $dropbox->DownloadFile($f,$test);}
  }
 }
 
function store_token($token, $name)
{
	if(!file_put_contents("tokens/$name.token", serialize($token)))
		die('<br />Could not store token! <b>Make sure that the directory `tokens` exists and is writable!</b>');
}

function load_token($name)
{
	if(!file_exists("tokens/$name.token")) return null;
	return @unserialize(@file_get_contents("tokens/$name.token"));
}

function delete_token($name)
{
	@unlink("tokens/$name.token");
}





function enable_implicit_flush()
{
	@apache_setenv('no-gzip', 1);
	@ini_set('zlib.output_compression', 0);
	@ini_set('implicit_flush', 1);
	for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
	ob_implicit_flush(1);
	echo "<!-- ".str_repeat(' ', 2000)." -->";
}


?>
