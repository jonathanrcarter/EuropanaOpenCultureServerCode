<?php

error_reporting(-1);


class obj {
}
function Q($S) {
	return "'" . $S . "'";
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
	rijksmuseum_search($srch); //dumps the content, you can manipulate as you wish to
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
}else {
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
	
	$item = new obj();
	$items = new obj();
	$links = array();
	
	$pieces = explode(".", $identifier,2);	
	
	
	$url2 = "https://www.rijksmuseum.nl/api/oai/68271713-6d46-4010-a3f9-d2ddb5890e1e/?verb=GetRecord&identifier=oai:rijksmuseum.nl/collection:".$pieces[1]."&metadataPrefix=oaidc";


	
	if (mysql_numrows($result) > 0) {

		$r = 0;
		
		$item->id = mysql_result($result,$r,"id");
		$item->url = "http://jon651.glimworm.com/europeana/tes.php?action=json-get-rijksmuseum&identifier=" . mysql_result($result,$r,"identifier");
		$item->enclosure = mysql_result($result,$r,"image")."&200x200";
		$item->thumbnail = mysql_result($result,$r,"image")."&200x200";
		$item->image = mysql_result($result,$r,"image");
		$item->guid = mysql_result($result,$r,"identifier");
		$item->identifier = mysql_result($result,$r,"identifier");
		$item->description = mysql_result($result,$r,"description");
		$item->language = mysql_result($result,$r,"language");
		$item->publisher = mysql_result($result,$r,"publisher");
		$item->rights = mysql_result($result,$r,"rights");
		$item->dat = mysql_result($result,$r,"dat");
		$item->creator = mysql_result($result,$r,"creator");
		$item->coverage = mysql_result($result,$r,"coverage");
		$item->type = mysql_result($result,$r,"type");
		$item->title = mysql_result($result,$r,"title");
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


		$query3="select * from muse_rijksmuseum_link where identifier = '" . $identifier .  "' order by type";
		$result3=mysql_query($query3);
		for ($r=0; $r < mysql_numrows($result3); $r++) {

			$item2 = new obj();
			$item2->id = mysql_result($result3,$r,"id");
			$item2->identifier = mysql_result($result3,$r,"identifier");
			$item2->url = mysql_result($result3,$r,"url");
			$item2->type = mysql_result($result3,$r,"type");
			$item2->comment = mysql_result($result3,$r,"comment");
			$item2->uid = mysql_result($result3,$r,"uid");
			$item2->ts = mysql_result($result3,$r,"ts");
			array_push($links , $item2);
		}

		
		
	
	}
	
	$url = "";
	
	
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
	$s = $s . "," . Q($type);
	$s = $s . "," . Q($comment);
	$s = $s . ",'');";
	
	$result=mysql_query($s);

	$retval = new obj();
	$retval->sql = $s;
	$retval->status = 0;
	$retval->status_txt = "link added " . $action_comment;
	$retval->action_error = $action_error;
	echo json_encode($retval);
	
	
}
function rijksmuseum_search($srch) {

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

	$query="select * from muse_rijksmuseum_index where description like '%" . $srch .  "%' or title like '%" . $srch .  "%' or creator like '%" . $srch .  "%' or subject like '%" . $srch .  "%' or publisher like '%" . $srch .  "%' or dat like '%" . $srch .  "%' order by id limit 100";
	$result=mysql_query($query);
	
	
	$totalitems = array();
	
	for ($r=0; $r < mysql_numrows($result); $r++) {

		$item = new obj();
		$item->id = mysql_result($result,$r,"id");
		$item->url = "http://jon651.glimworm.com/europeana/tes.php?action=json-get-rijksmuseum&identifier=" . mysql_result($result,$r,"identifier");
		$item->enclosure = mysql_result($result,$r,"image")."&200x200";
		$item->image = mysql_result($result,$r,"image");
		$item->guid = mysql_result($result,$r,"identifier");
		$item->identifier = mysql_result($result,$r,"identifier");
		$item->title = mysql_result($result,$r,"title");
		$item->description = mysql_result($result,$r,"description");
		
		array_push($totalitems , $item);
//		$totalitems = array_merge($totalitems , $items->items);
//		$totalitems = $items->items;
	}
	
	
	$retval = new obj();
	$retval->status = 0;
	$retval->status_txt = "search executed " . $action_comment;
	$retval->action_error = $action_error;
	$retval->data = new obj();
	$retval->data->srch = $srch;
	$retval->data->items = $totalitems;
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