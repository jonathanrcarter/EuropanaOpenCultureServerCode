<?php

// curl "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=en&q=vierde+kwart+18e+eeuw"
	
	
//$jsontxt = file_get_contents("https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=en&q=vierde+kwart+18e+eeuw");


//		$jsontxt = file_get_contents("https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=en&q=Het+korporaalschap+van+kapitein+Frans+Banninck+Cocq+en+luitenant+Willem+van+Ruytenburch,+bekend+als+de+'Nachtwacht'+(voormalige+titel)");
function Q($S) {
	return "'" . mysql_real_escape_string($S) . "'";
}

function _convert($fld) {

	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select distinct $fld as fld from muse_rijksmuseum_index where $fld != '' and ".$fld."_en = '' limit 5000";
	$result=mysql_query($query);
	
	$cnt = 0;
	
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$val = mysql_result($result,$r,"fld");
		

		$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=en&q=" . urlencode($val);
		
		
		$jsontxt = file_get_contents($url);
		$data = json_decode($jsontxt);
		
		if (count($data->data->translations) > 0) { 
			$newtext = $data->data->translations[0]->translatedText;
			$sql = sprintf("update muse_rijksmuseum_index set ".$fld."_en = %s where $fld = %s",Q($newtext),Q($val));
			mysql_query($sql);
			$cnt++;
			echo "\n $cnt : $val";
//			echo "<pre> $sql </pre>";
		} else {
			echo "\n $cnt : ERROR";
			echo $url;
			var_dump($jsontxt);
			var_dump($data->data->translations[0]->translatedText);
			echo "\n";
		}
		
	}
	echo "<pre> $cnt updated </pre>";

}






_convert("title");

	
?>