<?
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
?>