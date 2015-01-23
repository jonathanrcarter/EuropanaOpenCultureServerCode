<?php

error_reporting(-1);


class obj {
}
function Q($S) {
	return "'" . $S . "'";
}
function QQ($S) {
	return "'" . mysql_real_escape_string($S) . "'";
}



if ($_GET["action"] == "") $_GET = $_POST;
$action = $_GET['action'];

if ($action == "json-srch") {
	$srch = $_GET['srch'];
	get_data($srch); //dumps the content, you can manipulate as you wish to
	exit;
} else if ($action == "xml_get") {
	$URL = $_GET['srch'];
	//echo "a";
	rijksmuseum_xml($URL);
	exit;
} else if ($action == "json-get") {
	$identifier = $_GET['identifier'];
	get_item($identifier); //dumps the content, you can manipulate as you wish to
	exit;
} else if ($action == "json-srch-rijksmuseum") {
	$srch = $_GET['srch'];
	$typ = $_GET['type'];
	rijksmuseum_search($srch,$typ); //dumps the content, you can manipulate as you wish to
	exit;
} else if ($action == "json-addlink-rijksmuseum") {
	$a = $_GET['a'];
	$b = $_GET['b'];
	$type = $_GET['type'];
	$comment = $_GET['comment'];
	rijksmuseum_addlink($a, $b, $type, $comment); //dumps the content, you can manipulate as you wish to
	exit;
} else if ($action == "json-get-rijksmuseum") {
	$identifier = $_GET['identifier'];
	rijksmuseum_getitem($identifier); //dumps the content, you can manipulate as you wish to
	exit;
} else if ($action == "notdone") {

	//$h = "<html><head><style type='text/css'>body{margin:0;background-color:black;}</style></head><body><img src='http://jon651.glimworm.com/europeana/images/ding.png'></body></html>";
	$h = "<html><head><style type='text/css'>body{margin:0;background-color:black;}p{color:white;font-family:Helvetica;text-align:center;font-weight:bold;font-size:30px;height:200px;margin-top:250px;}a{color:white;text-decoration:none;font-size:32px;text-transform:uppercase;border-bottom:2px solid white;}</style></head><body><p>Not available yet,<br>would you like to go the the Rijksmuseum studio and print from <a href='#'>there</a>?</p></body></html>";
	
	echo $h;
	exit;

} else if ($action == "print") {
	//	http://jon651.glimworm.com/europeana/tes.php?action=print&identifier=
	$identifier = $_GET['identifier'];
	
	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select * from muse_rijksmuseum_index where identifier = '" . $identifier .  "'";
	$result=mysql_query($query);

	$btn = "";

	if (mysql_numrows($result) > 0) {
		$img = mysql_result($result,0,"image");
		
$js = '<script type="text/javascript">
(function() {
var p=document.createElement("script");p.type="text/javascript";p.async=true;
var h=("https:"==document.location.protocol?"https://":"http://");
p.src=h+"d3aln0nj58oevo.cloudfront.net/button/script/13643753638481548.js";
var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(p,s);
}).call(this);
</script>';
		
		$btn = $js . sprintf('<img src="%s" height=250><a title="Peecho" href="http://www.peecho.com" class="peecho-print-button" 
data-filetype="jpg" data-width="2000" data-height="2000" data-pages="1" 
data-src="%s">Peecho</a>',$img,$img);
	$btn = "";

	}
	
	echo "
	<style>
	* {
		color : #ffffff;
		font-family : STHeitiTC-Medium,Helvetica;
		line-height : 1.5em;
		background-color : #000000;
		list-style : none;
	}
	</style>
	";
	$h = "<html><head><style type='text/css'>body{margin:0;background-color:black;}p{color:white;font-family:Helvetica;text-align:center;font-weight:bold;font-size:30px;height:200px;margin-top:250px;}a{color:white;text-decoration:none;font-size:32px;text-transform:uppercase;border-bottom:2px solid white;}</style></head><body><p>Not available yet,<br>would you like to go the the Rijksmuseum studio and print from <a href='#'>there</a>?</p></body></html>";
	echo $h;
	
	
//	echo "<h1>Print page for identifier $identifier</h1>";
	echo $btn;
	exit;

} else if ($action == "linkcloud") {
	$identifier = $_GET['identifier'];
	echo "
	<style>
	* {
		color : #ffffff;
		font-family : STHeitiTC-Medium,Helvetica;
		line-height : 1.5em;
		background-color : #000000;
		list-style : none;
	}
	</style>
	";
	echo "<h1>Link cloud for identifier $identifier</h1>";
	rijksmuseum_cloud($identifier); //dumps the content, you can manipulate as you wish to
	exit;

} else if ($action == "cloud-attribute") {
	$attribute = $_GET['attribute'];
	$value = $_GET['value'];
	echo "
	<style>
	* {
		color : #ffffff;
		font-family : STHeitiTC-Medium,Helvetica;
		line-height : 1.5em;
		background-color : #000000;
		list-style : none;
	}
	</style>
	";
	echo "<h1>Link cloud for attribute $attribute = $value</h1>";
	rijksmuseum_cloud_attribute($attribute, $value); //dumps the content, you can manipulate as you wish to
	exit;

} else {
	echo $action;
	
}


function get_data($srch)
{

	for ($i=1; $i < 10; $i++) {
		$url='http://api.europeana.eu/api/opensearch.json?searchTerms='.$srch.'&wskey=ZHKKYAIMYT&startPage='.$i; //rss link for the twitter timeline
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
	
		$items = json_decode($data);
//		array_push($totalitems , $items->items);
		$totalitems = array_merge($totalitems , $items->items);
//		$totalitems = $items->items;
	}


	$retval = new obj();
	$retval->status = 0;
	$retval->status_msg = "searched";
	$retval->data = new obj();
	$retval->data->items = $totalitems;

	echo json_encode($retval);
}
function get_item($identifier)
{

		$url=str_replace(".html",".json?wskey=ZHKKYAIMYT",$identifier);
//		'http://api.europeana.eu/api/opensearch.json?searchTerms='.$srch.'&wskey=ZHKKYAIMYT&startPage='.$i; //rss link for the twitter timeline
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		curl_close($ch);
	
		$items = json_decode($data);


	$retval = new obj();
	$retval->status = 0;
	$retval->status_msg = "searched";
	$retval->data = $items;
	
	$retval->data1 = new obj();
	$retval->data1->identifier = $identifier;
	$retval->data1->url = $url;

	echo json_encode($retval);
}

function english($val) {
	$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=en&q=" . urlencode($val);
	$jsontxt = file_get_contents($url);
	$data = json_decode($jsontxt);
		
	if (count($data->data->translations) > 0) { 
		$newtext = $data->data->translations[0]->translatedText;
		return $newtext;
	}
	return "";
}



function rijksmuseum_cloud_attribute($attribute, $value) {
	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	
	if ($attribute == "publisher") {
		$query="select * from muse_rijksmuseum_index where publisher = '" . $value .  "'";
		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"title")."</a></li>";
		}
	}

	if ($attribute == "creator") {
		$query="select * from muse_rijksmuseum_index where creator = '" . $value .  "'";
		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"title")."</a></li>";
		}
	}

	if ($attribute == "type") {
		$query="select * from muse_rijksmuseum_index where type = '" . $value .  "'";
		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"title")."</a></li>";
		}
	}


	if ($attribute == "link") {
		$query="select * from muse_rijksmuseum_link where url = '" . $value .  "' order by type";

		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"comment")."</a></li>";
		}
	}



}


function rijksmuseum_cloud($identifier) {
	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select * from muse_rijksmuseum_index where identifier = '" . $identifier .  "'";
	$result=mysql_query($query);
	if (mysql_numrows($result) > 0) {
	
	
		echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=publisher&value=".mysql_result($result,0,"publisher")."'>publisher : ".mysql_result($result,0,"publisher")."</a></li>";
		echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=creator&value=".mysql_result($result,0,"creator")."'>Creator : ".mysql_result($result,0,"creator")."</a></li>";
		echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=type&value=".mysql_result($result,0,"type")."'>Type : ".mysql_result($result,0,"type")."</a></li>";
		
	
	
		$query3="select * from muse_rijksmuseum_link where identifier = '" . $identifier .  "' order by type";
		$result3=mysql_query($query3);
		for ($r=0; $r < mysql_numrows($result3); $r++) {
		
			echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=link&value=". mysql_result($result3,$r,"url")."'>".mysql_result($result3,$r,"type") . " : " . mysql_result($result3,$r,"comment")."</a></li>";
		}
	
	}


}
function rijksmuseum_getitem($identifier) {
// https://www.rijksmuseum.nl/api/oai/68271713-6d46-4010-a3f9-d2ddb5890e1e/?verb=GetRecord&identifier=oai:rijksmuseum.nl/collection:COLLECT.5216&metadataPrefix=oaidc

	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select * from muse_rijksmuseum_index where identifier = '" . $identifier .  "'";
	$result=mysql_query($query);
	
	$query999="INSERT INTO `muse_rijksmuseum_tracking` (`id`, `type`, `sort`, `content`) VALUES ('0', 'item', 'get', '$identifier');";
	$result999=mysql_query($query999);
	//echo $query999;
	
	$item = new obj();
	$items = new obj();
	$links = array();
	
	$pieces = explode(".", $identifier,2);	
	
	
	$url2 = "https://www.rijksmuseum.nl/api/oai/68271713-6d46-4010-a3f9-d2ddb5890e1e/?verb=GetRecord&identifier=oai:rijksmuseum.nl/collection:".$pieces[1]."&metadataPrefix=oaidc";


	
	if (mysql_numrows($result) > 0) {

		$r = 0;

		$description = mysql_result($result,$r,"description");
		$description_en = mysql_result($result,$r,"description_en");
		
		if ($description != "" && $description_en == "") {
			$description_en = english($description);
			if ($description_en != "") {
				$replace_english_query = sprintf("update ignore rijksmuseum_index set description_en = %s where identifier = '" . $identifier .  "'",QQ($description_en));
				mysql_query($replace_english_query);
			
			}
		}
		
		if ($description_en != "") $description = "English description provided by google translate : ".$description_en . "\n\nOriginal Dutch description: " . $description;


		$title = mysql_result($result,$r,"title");
		$title_en = mysql_result($result,$r,"title_en");
		
		$title = $title_en . "\n(Dutch:$title)";
		

		
		$item->id = mysql_result($result,$r,"id");
		$item->url = "http://jon651.glimworm.com/europeana/tes.php?action=json-get-rijksmuseum&identifier=" . mysql_result($result,$r,"identifier");
		$item->enclosure = mysql_result($result,$r,"image")."&200x200";
		$item->thumbnail = mysql_result($result,$r,"image")."&200x200";
		$item->image = mysql_result($result,$r,"image");
		$item->guid = mysql_result($result,$r,"identifier");
		$item->identifier = mysql_result($result,$r,"identifier");
		$item->description_nl = mysql_result($result,$r,"description");
		$item->description_en = mysql_result($result,$r,"description_en");
		$item->description = $description;
		$item->language = mysql_result($result,$r,"language");
		$item->publisher = mysql_result($result,$r,"publisher");
		$item->rights = mysql_result($result,$r,"rights");
		$item->dat = mysql_result($result,$r,"dat");
		$item->creator = mysql_result($result,$r,"creator");
		$item->coverage = mysql_result($result,$r,"coverage");
		$item->type = strtolower(mysql_result($result,$r,"type"));
		$item->title = $title;
		$item->title_nl = mysql_result($result,$r,"title");
		$item->title_en = mysql_result($result,$r,"title_en");
		$item->subject = mysql_result($result,$r,"subject");
		$item->link = mysql_result($result,$r,"link");
		
		
		$ch = curl_init();
		$timeout = 15;
		curl_setopt($ch,CURLOPT_URL,$url2);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data2 = curl_exec($ch);
		curl_close($ch);
	
		$items = json_decode($data2);
		
		$item->rawdata = $data2;


		$query3="select * from rijksmuseum_link where identifier = '" . $identifier .  "' order by type";
		$result3=mysql_query($query3);
		for ($r=0; $r < mysql_numrows($result3); $r++) {

			$item2 = new obj();
			$item2->id = mysql_result($result3,$r,"id");
			$item2->identifier = mysql_result($result3,$r,"identifier");
			$item2->url = mysql_result($result3,$r,"url");
			$item2->type = strtolower(mysql_result($result3,$r,"type"));
			$item2->comment = mysql_result($result3,$r,"comment");
			$item2->uid = mysql_result($result3,$r,"uid");
			$item2->ts = mysql_result($result3,$r,"ts");
			array_push($links , $item2);
		}

		
		
	
	}
	
	$url = "";
	

	$buts = array();
		
	$but1 = new obj();
	$but1->title = "Peecho print";
	$but1->url = "http://jon651.glimworm.com/europeana/tes.php?action=print&identifier=".$identifier;
	//$but1->url = "http://jon651.glimworm.com/europeana/tes.php?action=notdone";
	array_push($buts , $but1);
/*
	$but1 = new obj();
	$but1->title = "Linkedin Culture Connect";
	$but1->url = "http://jon631.glimworm.com/tnw/deg.php?identifier=".$identifier;
	array_push($buts , $but1);

	$but1 = new obj();
	$but1->title = "Browse at Bol.com";
	$but1->url = "http://jon631.glimworm.com/tnw/bol.php?identifier=".$identifier;
	array_push($buts , $but1);
*/	
		
	$retval = new obj();
	$retval->status = 0;
	$retval->status_txt = "get executed " . $action_comment;
	$retval->action_error = $action_error;
	$retval->data = $item;
	
	$retval->data1 = new obj();
	$retval->data1->identifier = $identifier;
	$retval->data1->identifier2 = $pieces[1];
	$retval->data1->url = $url;
	$retval->data1->items = $items;
	$retval->data1->url2 = $url2;
	$retval->data1->links = $links;
	$retval->data1->query3 = $query3;
	$retval->data1->buts = $buts;
	
	echo json_encode($retval);


}
function rijksmuseum_addlink($a, $b, $type, $comment) {

	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	
	/*
	
	database format
	===============
	
	drop table if exists rijksmuseum_link;
	create table rijksmuseum_link (
	id int(6) NOT NULL AUTO_INCREMENT,
	ts timestamp not null default 0,
	identifier varchar(255) not null default '',
	url varchar(255) not null default '',
	type varchar(255) not null default '',
	comment varchar(255) not null default '',
	uid varchar(255) not null default '',
	primary key (id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;


	*/
	$s = "insert into muse_rijksmuseum_link  (id,ts,identifier,url,type,comment,uid) values (0,now() ";
	$s = $s . "," . Q($a);
	$s = $s . "," . Q($b);
	$s = $s . "," . Q(strtolower($type));
	$s = $s . "," . Q($comment);
	$s = $s . ",'');";
	
	$result=mysql_query($s);
	
	
	$links = array();
		$query3="select * from muse_rijksmuseum_link where identifier = '" . ($a) .  "' order by type";
		$result3=mysql_query($query3);
		for ($r=0; $r < mysql_numrows($result3); $r++) {

			$item2 = new obj();
			$item2->id = mysql_result($result3,$r,"id");
			$item2->identifier = mysql_result($result3,$r,"identifier");
			$item2->url = mysql_result($result3,$r,"url");
			$item2->type = strtolower(mysql_result($result3,$r,"type"));
			$item2->comment = mysql_result($result3,$r,"comment");
			$item2->uid = mysql_result($result3,$r,"uid");
			$item2->ts = mysql_result($result3,$r,"ts");
			array_push($links , $item2);
		}
	
	
	

	$retval = new obj();
	$retval->sql = $s;
	$retval->status = 0;
	$retval->status_txt = "link added " . $action_comment;
	$retval->action_error = $action_error;
	$retval->links = $links;
	echo json_encode($retval);
	
	
}
function rijksmuseum_search($srch,$typ) {

	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	
	/*
	
	database format
	===============
	
	create table rijksmuseum_index (
	id int(6) NOT NULL AUTO_INCREMENT,
	identifier varchar(255) not null default '',
	language varchar(255) not null default '',
	publisher varchar(255) not null default '',
	rights varchar(255) not null default '',
	dat varchar(255) not null default '',
	description text not null default '',
	creator varchar(255) not null default '',
	coverage varchar(255) not null default '',
	type varchar(255) not null default '',
	title varchar(255) not null default '',
	subject varchar(255) not null default '',
	image varchar(255) not null default '',
	link  varchar(255) not null default '',
	primary key (id)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;


	*/
	$query999="INSERT INTO `muse_rijksmuseum_tracking` (`id`, `type`, `sort`, `content`) VALUES ('0', 'term', 'srch', '$srch');";
	$result999=mysql_query($query999);

	$query="select * from muse_rijksmuseum_index where description like '%" . $srch .  "%' or description_en like '%" . $srch .  "%' or title like '%" . $srch .  "%' or title_en like '%" . $srch .  "%' or creator like '%" . $srch .  "%' or subject like '%" . $srch .  "%' or publisher like '%" . $srch .  "%' or dat like '%" . $srch .  "%' ORDER BY (type != 'schilderij'),type,id limit 150";

	if ($typ && $typ != "") {
		$query="select * from muse_rijksmuseum_index where (type = '".$typ."' or type_en = '".$typ."') and ( description like '%" . $srch .  "%' or description_en like '%" . $srch .  "%' or title like '%" . $srch .  "%' or title_en like '%" . $srch .  "%' or creator like '%" . $srch .  "%' or subject like '%" . $srch .  "%' or publisher like '%" . $srch .  "%' or dat like '%" . $srch .  "%') ORDER BY (type != 'schilderij'),type,id limit 150";
	}
	$result=mysql_query($query);
	// limit 3000
	
	$totalitems = array();
	
	for ($r=0; $r < mysql_numrows($result); $r++) {

		$title = mysql_result($result,$r,"title");
		$title_en = mysql_result($result,$r,"title_en");
		
		if ($title_en != "") $title = $title_en;


		$item = new obj();
		$item->id = mysql_result($result,$r,"id");
		$item->url = "http://jon651.glimworm.com/europeana/tes.php?action=json-get-rijksmuseum&identifier=" . mysql_result($result,$r,"identifier");
		$item->enclosure = mysql_result($result,$r,"image")."&200x200";
		$item->image = mysql_result($result,$r,"image");
		$item->guid = mysql_result($result,$r,"identifier");
		$item->identifier = mysql_result($result,$r,"identifier");
		$item->title = $title;
		$item->description = "";	//mysql_result($result,$r,"description");
		
		array_push($totalitems , $item);
//		$totalitems = array_merge($totalitems , $items->items);
//		$totalitems = $items->items;
	}
	
	$_where = " description like '%" . $srch .  "%' or description_en like '%" . $srch .  "%' or title like '%" . $srch .  "%' or title_en like '%" . $srch .  "%' or creator like '%" . $srch .  "%' or subject like '%" . $srch .  "%' or publisher like '%" . $srch .  "%' or dat like '%" . $srch .  "%'";
	

	$query="select distinct type_en as itm from muse_rijksmuseum_index where " . $_where;
	$result=mysql_query($query);
	$types = array();
	for ($r=0; $r < mysql_numrows($result); $r++) {
//		$item = "type:".mysql_result($result,$r,"itm");
		$item = mysql_result($result,$r,"itm");
		array_push($types , $item);
	}

	$query="select distinct creator as itm from muse_rijksmuseum_index where " . $_where;
	$result=mysql_query($query);
	$creators = array();
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$item = mysql_result($result,$r,"itm");
		array_push($creators , $item);
	}
	
	$query="select distinct dat as itm from muse_rijksmuseum_index where " . $_where;
	$result=mysql_query($query);
	$dats = array();
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$item = mysql_result($result,$r,"itm");
		array_push($dats , $item);
	}
	
	
	
	$retval = new obj();
	$retval->status = 0;
	$retval->status_txt = "search executed " . $action_comment;
	$retval->action_error = $action_error;
	$retval->data = new obj();
	$retval->data->srch = $srch;
	$retval->data->items = $totalitems;
	$retval->data->types = $types;
	$retval->data->creators = $creators;
	$retval->data->dats = $dats;
	echo json_encode($retval);
}

function rijksmuseum_xml($URL){

	//include 'XmlToJson.php';
	//$XML = XmlToJson::Parse("https://www.rijksmuseum.nl/api/oai/c9a1be67-e911-41b9-9484-5591d1a7355d/?verb=listrecords&metadataPrefix=oai_dc");
	//echo json_encode($XML);
	
	require_once("xml2json/xml2json.php");

	// Filename from where XML contents are to be read.
	//$testXmlFile = "https://www.rijksmuseum.nl/api/oai/c9a1be67-e911-41b9-9484-5591d1a7355d/?verb=listrecords&metadataPrefix=oai_dc";//$URL;
	$testXmlFile = "jon651.glimworm.com/europeana/test.xml";//$URL;

	//Read the XML contents from the input file.
	//file_exists($testXmlFile) or die('Could not find file ' . $testXmlFile);
	$xmlStringContents = file_get_contents($testXmlFile); 
	$jsonContents = "";

	// Convert it to JSON now. 
	// xml2json simply takes a String containing XML contents as input.
	$jsonContents = xml2json::transformXmlStringToJson($xmlStringContents);
	
	//echo $xmlStringContents;

	$xml = new SimpleXMLElement($xmlStringContents);
	var_dump($xml);
	
	
//	var_dump($xml->ListRecords);
//	echo json_encode($xml->ListRecords);
	
	//echo "count" + count($xml->ListRecords->record);
	
/*	
	for ($i=0 ; $i < count($xml->record); $i++) {
		echo "Order $i :: ";
		var_dump($xml->record[$i]);
	}
	
*/	

	//echo($jsonContents);
};

?>
