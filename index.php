<?
$db = new mysqli("localhost", "sethcode_iny", "inylink", "sethcode_iny");
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']'); }
$url=$_REQUEST['url'];
$codepool="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.";

if(!empty($url)) {
	echo "url=[$url]<br>";
	$result = $db->query("select * from `system` where `var`='code'");
	while($row = $result->fetch_assoc()){
		echo $row['var'] . " = [" . $row['val'] ."]<br>";
		$code=$row['val'];
	}
	$x=strlen($code);
	echo "code_length:[$x]<br>";
	echo "codepool[$codepool]<br>";
	$codeloc=strpos($codepool,$code);
	echo "codeloc[$codeloc]<br>";
	$newcodeloc=$codeloc+1;
	echo "newcodeloc[$newcodeloc]<br>";
	
	
	
	
/*	code
	url
	hits
	submit_ip
	submit_date */
	
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

$db->close();

?>