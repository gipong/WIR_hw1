<!DOCTYPE HTML>
<html>
<head>
	<title>WIR_hw1</title>
</head>
<body>
	<div align="center">
	<br /><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
		Search: <input type="text" name="search" />
		<input type="submit" name="submit" value="submit" />
	</form><br />
	</div>
	
	<hr />
	
	<div id="display" align="center">
<?php
	$link = mysql_connect('127.0.0.1','root','') or die('mysqli_connent:error');
	mysql_query("SET NAMES 'utf8'",$link);
	mysql_select_db('se2');

if(isset($_GET['submit'])) {
	$words = $_GET['search'];
	$words = strtolower(strip_tags($words)); 
	$words = explode(' ', $words);
	//var_dump($word);
	$dataset2012 = glob('dataset2012/*.html');
	
	foreach($words as $word) {
		$data = mysql_query("SELECT dataID, tfidf FROM `$word` ORDER BY tfidf DESC");
		$anymatches=@mysql_num_rows($data); 
		//$data = mysql_query("SELECT dataID, tfidf FROM `$word[0]` UNION SELECT dataID, tfidf FROM `$word[1]` ORDER BY tfidf ASC"); 
		if(isset($data) && $anymatches>0) {
			echo "- $word - <br />";
			while($result = mysql_fetch_array($data)) {
				echo 'DataID:<a href="'.$dataset2012[$result[0]].'">'.$result[0].'</a> tfidf: '.$result[1].'<br />';
			}
		}else {
			echo "<p>Sorry, can not find  <b style=\"color: red;\">$word</b>  to match your query!!</p><br />"; 
		}
	}

}
?>	
	</div>
</body>
</html>


