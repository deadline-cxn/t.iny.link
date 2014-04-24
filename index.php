<?
$page_url="http";
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $page_url .= 's';
$page_url.='://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
echo "($page_url)";
?>
