<?
include(".config.php"); // .config.php stores $dbhost, $dbdb, $dbpass, and $dbname for mysql operations
if(isset($argv[1])) { inc_c($argv[1],strlen($argv[1])-1); exit(); }
function put_ads() {  echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script><!-- hey --><ins class=\"adsbygoogle\" style=\"display:inline-block;width:728px;height:90px\" data-ad-client=\"ca-pub-9784595369821502\" data-ad-slot=\"9276856171\"></ins> <script> (adsbygoogle = window.adsbygoogle || []).push({}); </script><script type=\"text/javascript\">	var _gaq = _gaq || []; 	_gaq.push(['_setAccount', 'UA-36907330-2']); _gaq.push(['_trackPageview']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })(); </script>"; }
function inc_c($code,$codeloc) {
        $codepool="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.";
	$x=strpos($codepool,$code[$codeloc]);
	$ox=$x; $x++;
	if($x>strlen($codepool)-1) {
		$x=0;
		if($codeloc==0) { $code=$code.$codepool[$x]; }
		else { $code=inc_c($code,$codeloc-1); }
	}
	$code[$codeloc]=$codepool[$x];
	return $code;
}
$db = new mysqli( $dbhost, $dbdb, $dbpass, $dbname);
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']'); }
$act=$_REQUEST['act'];
if(!empty($act)) {
	if($act=="pull") { system("pull"); exit; }
	if($act=="dump") {
		$r=$db->query("select * from `link`");
                echo "<a href=\"http://t.iny.link\">Back to t.iny.link</a>";
		echo "<table border=0>";
		echo "<tr><td>Short Link</td><td>Hits</td><td>Long Link</td></tr>";
		while($l=$r->fetch_object()) {
				// if(empty($l->submit_ip)) $l->submit_ip="(unknown)";
				// if($l->submit_date=="0000-00-00 00:00:00") $l->submit_date="(unknown)";
			echo"<tr><td>http://t.iny.link/$l->code</td><td>$l->hits</td><td>$l->url </td></tr>";
		} 
		echo "</table>";
		exit; 
	}
}
$url=$_REQUEST['url'];
if(!empty($url)) {
	$result = $db->query("select * from `link` where `url`='$url'");
	$lnk=$result->fetch_object();
	if(!empty($lnk->url)) { }
	else {
		$result = $db->query("select * from `system` where `var`='code'");
		while($row = $result->fetch_assoc()){ $code=$row['val']; }
		$code=inc_c($code,strlen($code)-1);
		if(empty($lnk->code)) {
			$sip=$_SERVER['REMOTE_ADDR'];
			$result=$db->query("insert into `link` (`url`,`code`,`submit_ip`) values ('$url','$code','$sip');");
			$result=$db->query("update `system` set `val`='$code' where `var`='code'");
		} else { $code=$lnk->code; }
		$result=$db->query("select * from `link` where `code`='$code'");
		$lnk=$result->fetch_object();
	}
	echo "<a href=http://t.iny.link/$lnk->code>http://t.iny.link/$lnk->code</a><br>";
	put_ads();
}
else {
	$page_url="http";
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $page_url .= 's';
	$page_url.='://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$xpage=explode("/",$page_url);
	$code=$xpage[count($xpage)-1];
	if(empty($code)) {
		echo "<html><head>";
		echo "<title>t.iny.link - shorten your urls</title>";
		echo "<link rel=\"stylesheet\" href=\"t.css\" type=\"text/css\">";
		echo "</head><body>";
		echo "<style>";
		echo "body { background-color: #aaf; } h1 { font-size: xx-large; color: black; }";
		echo "</style>";
		echo "<div align=center>";
		echo "<h1>Make a t.iny.link!</h1>";
		echo "<form method=post>LONG URL:<input name=url size=80><input type=submit></form>";
		put_ads();
		echo "<p>&nbsp;</p>";
                echo "<a href=\"https://twitter.com/share\" class=\"twitter-share-button\" data-via=\"sethcoder\" data-lang=\"en\">Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"https://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>";
                echo "<a href=\"https://twitter.com/sethcoder\" class=\"twitter-follow-button\" data-show-count=\"true\" data-lang=\"en\">Follow @sethcoder</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=\"//platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);}}(document,\"script\",\"twitter-wjs\");</script>";
		echo "<p>&nbsp;</p>";
                echo "t.iny.link is open source! Download it from <a href=\"http://t.iny.link/h\">http://t.iny.link/h</a>";
		echo "<p>&nbsp;</p>";
		echo "<p><a href=\"http://t.iny.link?act=dump\">Statistics</a></p>";
		echo "<p>&nbsp;</p>";
		echo "Copyright (C)2014 Seth Parson ~ <a href=\"http://t.iny.link/3\">http://t.iny.link/3</a><br>";
 		echo "</div>";
		echo "</body></html>";
	}
	else {
		$result=$db->query("select * from `link` where `code`='$code'");
		$lnk=$result->fetch_object();
                $lnk->hits=$lnk->hits+1;
                $db->query("update `link` set `hits` = '$lnk->hits' where `code`='$lnk->code'");
		echo "<META http-equiv=\"refresh\" content=\"0;URL=$lnk->url\">";
	}
}
$db->close();
?>
