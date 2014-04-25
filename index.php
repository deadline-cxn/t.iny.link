<?
$link = mysqli_connect("localhost", "sethcode_iny", "inylink", "sethcode_iny");
if(mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
// if(mysqli_query($link, "CREATE TEMPORARY TABLE myCity LIKE City") === TRUE) { 	printf("Table myCity successfully created.\n"); }
if($result = mysqli_query($link, "SELECT Name FROM City LIMIT 10")) { 	printf("Select returned %d rows.\n", mysqli_num_rows($result)); 	mysqli_free_result($result); }
if($result = mysqli_query($link, "SELECT * FROM City", MYSQLI_USE_RESULT)) {     if(!mysqli_query($link, "SET @a:='this will not work'")) {         printf("Error: %s\n", mysqli_error($link));    }     mysqli_free_result($result);}
// mysqli_close($link);

$url=$_REQUEST['url'];
if(!empty($url)) {
	echo "[$url]";
	
}
else {
	$page_url="http";
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $page_url .= 's';
	$page_url.='://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$xpage=explode("/",$page_url);
	$link=$xpage[count($xpage)-1];
	if(empty($link)) {
		echo "Make a tiny link:<br>";
		echo "<form method=post>LONG URL: <input name=url><input type=submit></form>";
		echo "Copyright (C)2014 Seth Parson<br>";
	}
	else {
		echo $link;
	}
}



?>