<?

if (isset($argv[1])) {
    inc_c($argv[1],strlen($argv[1])-1);
	exit();
}
function put_ads() {
		echo "
<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
<!-- hey -->
<ins class=\"adsbygoogle\"
     style=\"display:inline-block;width:728px;height:90px\"
     data-ad-client=\"ca-pub-9784595369821502\"
     data-ad-slot=\"9276856171\"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script> 

<script type=\"text/javascript\">
				var _gaq = _gaq || [];  
				_gaq.push(['_setAccount', 'UA-36907330-2']);
				_gaq.push(['_trackPageview']);
				(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			</script>
			";
}
function inc_c($code,$codeloc) {
	$codepool="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.";
	//echo "code[$code]\n";
	//echo "codepool[$codepool]\n";
	//echo "what[".$code[$codeloc]."]\n";	
	$x=strpos($codepool,$code[$codeloc]);
	$ox=$x;
	$x++;
	if($x>strlen($codepool)-1) {
		$x=0;
		if($codeloc==0) {
			$code=$code.$codepool[$x];
		}
		else {			
			$code=inc_c($code,$codeloc-1);
		}
	}
	//echo "Old[".$codepool[$ox]."]\n";
	//echo "New[".$codepool[$x]."]\n";
	$code[$codeloc]=$codepool[$x];
	//echo $code."\n";
	return $code;
}
$db = new mysqli("localhost", "sethcode_iny", "inylink", "sethcode_iny");
if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']'); }

$url=$_REQUEST['url'];
if(!empty($url)) {	
	// echo "url=[$url]<br>";	
	$result = $db->query("select * from `link` where `url`='$url'");
	$lnk=$result->fetch_object();
	if(!empty($lnk->url)) { }
	else {
		$result = $db->query("select * from `system` where `var`='code'");
		while($row = $result->fetch_assoc()){
			$code=$row['val'];
		}
		$code=inc_c($code,strlen($code)-1);
		if(empty($lnk->code)) {
			$result=$db->query("insert into `link` (`url`,`code`) 
											values  ('$url','$code');");
			$result=$db->query("update `system` set `val`='$code' where `var`='code'");
		} else {
			$code=$lnk->code;
		}
		$result=$db->query("select * from `link` where `code`='$code'");
		$lnk=$result->fetch_object();
	}
	echo "LINK:<br>$lnk->url<br>$lnk->code<br>";	
	echo "<a href=http://iny.link/$lnk->code>http://iny.link/$lnk->code</a><br>";
	echo "<a href=http://t.iny.link/$lnk->code>http://t.iny.link/$lnk->code</a><br>";
	
	put_ads();
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
	$code=$xpage[count($xpage)-1];
	
	if(empty($code)) {
		echo "<html><head>";
		echo "<title>iny.link - shorten your urls</title>";
		echo "<link rel=\"stylesheet\" href=\"t.css\" type=\"text/css\">";
		echo "</head><body>";
		echo "<style>";
		echo "
		body {
			background-color: #99F;			
		}
		
		h1 {
		font-size: xx-large;
		color: yello;
		}
		
		";
		echo "</style>";
		
		echo "<h1>Make a tiny link:</h1>";
		
		echo "<form method=post>LONG URL:<input name=url><input type=submit></form>";
		
		put_ads();
		for($i=0;$i<20;$i++) echo "<p>&nbsp;</p>";
		echo "Copyright (C)2014 Seth Parson<br>";
		
		echo "</body></html>";
	}
	else {
		$result=$db->query("select * from `link` where `code`='$code'");
		$lnk=$result->fetch_object();
		echo "<META http-equiv=\"refresh\" content=\"0;URL=$lnk->url\">";
	}
}

$db->close();

?>