<?

if (isset($argv[1])) {
    inc_c($argv[1],strlen($argv[1])-1);
	exit();
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
	if(!empty($lnk->url)) {
		echo "Exists!<br>";
	}
	else {
		echo "Does not exist<br>";

		$result = $db->query("select * from `system` where `var`='code'");
		while($row = $result->fetch_assoc()){
			echo $row['var'] . " = [" . $row['val'] ."]<br>";
			$code=$row['val'];
		}
		echo "old_code[$code]<br>";
		$code=inc_c($code,strlen($code)-1);
		echo "new_code[$code]<br>";
		$url=addslashes($url);
		$result=$db->query("select * from `link` where `url`='$url'");	
		$lnk=$result->fetch_object();
		if(empty($lnk->code)) {
			$result=$db->query("insert into `link` (`url`,`code`) 
											values  ('$url','$code');");
			$result=$db->query("update `system` set `val`='$code' where `var`='code'");
		} else {
			$code=$lnk->code;
		}
	}
	$result=$db->query("select * from `link` where `code`='$code'");
	$lnk=$result->fetch_object();
	
	echo " OUTLINK:<br>$lnk->url<br>$lnk->code<br>";
	echo "iny.link url:<br>";
	echo "<a href=http://iny.link/$lnk->code>http://iny.link/$lnk->code</a><br>";
	
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
		echo "Make a tiny link:<br>";
		echo "<form method=post>LONG URL: <input name=url><input type=submit></form>";
		echo "Copyright (C)2014 Seth Parson<br>";
	}
	else {
		$result=$db->query("select * from `link` where `code`='$code'");
		$lnk=$result->fetch_object();
		echo "<META http-equiv=\"refresh\" content=\"0;URL=$lnk->url\">";
		//echo "OUTLINK:<br>$lnk->url<br>$lnk->code<br>";
		//echo "iny.link url:<br>";
		//echo "<a href=http://iny.link/$lnk->code>http://iny.link/$lnk->code</a><br>";
	}
}

$db->close();

?>