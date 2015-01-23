<?php

error_reporting(-1);

$tim = microtime(true);
$ltim = $tim;
$timstring = "";
function tims() {
	global $timstring;
	return $timstring;
}
function tim($S) {
	global $tim;
	global $ltim;
	global $timstring;
	$ntim = microtime(true);
	error_log(sprintf("timer : step: %s  total: %s  - (%s)", ($ntim - $ltim), ($ntim - $tim),$S));
	$timstring .= sprintf("\ntimer : step: %s  total: %s  - (%s)", ($ntim - $ltim), ($ntim - $tim),$S);
	$ltim = $ntim;
}
tim("start");
class obj {
}
function Q($S) {
	return "'" . $S . "'";
}
function QQ($S) {
	return "'" . mysql_real_escape_string($S) . "'";
}

function array_or_string($itm) {
	return (is_array($itm)) ? $itm[0] : $itm;
}

function prepare_image_url($img) {
	try {
		if ($img && $img != null && strpos($img, "memorix")) {
//			http://na.memorix.nl/oai2/?image=na:col1:dat491224:134_0392.jpg&type=large")
			return ("http://europeanastatic.eu/api/image?uri=". urlencode($img) ."&size=LARGE&type=TEXT");
		}
	} catch (Exception $e) {		
		return $img;
	}
	return $img;
		
}

function html2text($Document) {
    $Rules = array ('@<script[^>]*?>.*?</script>@si',
                    '@<[\/\!]*?[^<>]*?>@si',
                    '@([\r\n])[\s]+@',
                    '@&(quot|#34);@i',
                    '@&(amp|#38);@i',
                    '@&(amp|#39);@i',
                    '@&(lt|#60);@i',
                    '@&(gt|#62);@i',
                    '@&(nbsp|#160);@i',
                    '@&(iexcl|#161);@i',
                    '@&(cent|#162);@i',
                    '@&(pound|#163);@i',
                    '@&(copy|#169);@i',
                    '@&(reg|#174);@i',
                    '@&#(d+);@e'
             );
    $Replace = array ('',
                      '',
                      '',
                      '',
                      '&',
                      '\'',
                      '<',
                      '>',
                      ' ',
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      chr(174),
                      'chr()'
                );
  return preg_replace($Rules, $Replace, $Document);
}



if ($_GET["action"] == "") $_GET = $_POST;
$action = $_GET['action'];

tim("start action [". $action ."]");

$L = (empty($_GET["lang"])) ? "en" : $_GET["lang"];

include_once("euv2_lang/".$L.".php");
function L($S) {
/*
	static $lang = array(g;
		"en" => "English",
		"e404" => "This search gives no results, please try another search term."
	);
*/
	global $lang;
	if ($lang[$S]) return $lang[$S];
	return "0".$S;
}


require('autoload.php');
use UnitedPrototype\GoogleAnalytics;
//$tracker = new GoogleAnalytics\Tracker('UA-43952072-1', 'glimworm.net');
$tracker = new GoogleAnalytics\Tracker('UA-12776629-1', 'europeana.eu');
date_default_timezone_set('Europe/Berlin');

// Assemble Visitor information
$visitor = new GoogleAnalytics\Visitor();
$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
$visitor->setScreenResolution('1024x768');

// Assemble Session information
$session = new GoogleAnalytics\Session();

// Assemble Page information

$n = microtime();

if ($action == "log") {
	$ev = $_GET["event"];
	$details = empty($_GET["details"]) ? 0 : $_GET["details"];
	error_log(print_r($_GET,true));

//	$page = new GoogleAnalytics\Page("/log-event/".$ev);
//	$page->setTitle('Europeana v2 api log event');


//	$tracker->trackPageview($page, $session, $visitor);
//	$event = new GoogleAnalytics\Event("open-culture-app-v2", "action","log : ".$ev,$detail);
	$event = new GoogleAnalytics\Event("open-culture-app-v2","log : ".$ev,$details);
	$tracker->trackEvent($event, $session, $visitor);
	exit;
}
$page = new GoogleAnalytics\Page("/euv2.php");
$page->setTitle('Europeana v2 api');
$tracker->trackPageview($page, $session, $visitor);
$event = new GoogleAnalytics\Event("open-culture-app-v2", "action",$action,0);
$tracker->trackEvent($event, $session, $visitor);


if ($action == "json-srch") {
	$srch = $_GET['srch'];
	$typ = $_GET['type'];
	$start = $_GET['start'];
	$page = $_GET['page'];
	$query = $_GET['query'];
	$mls = $_GET['mls'];
	get_data($srch,$typ,$start,$page,$query,$L,$mls); //dumps the content, you can manipulate as you wish to
	tim("end action");


	$page = new GoogleAnalytics\Page("/euv2.php?action=search-details&srch=.$srch&type=$type&query=$query&mls=$mls&lang=$L");
	$page->setTitle('Europeana v2 api - detail page view');
	$tracker->trackPageview($page, $session, $visitor);


	exit;
} else if ($action == "xml_get") {
	$URL = $_GET['srch'];
	//echo "a";
	rijksmuseum_xml($URL);
	tim("end action");
	exit;
} else if ($action == "json-get") {
	$identifier = $_GET['identifier'];
	get_item($identifier,$L); //dumps the content, you can manipulate as you wish to
	tim("end action");

	$page = new GoogleAnalytics\Page("/euv2.php?action=get-item-detail&identifier=$identifier&lang=$L");
	$page->setTitle('Europeana v2 api - detail page view');
	$tracker->trackPageview($page, $session, $visitor);


	exit;
} else if ($action == "json-srch-rijksmuseum") {
	$srch = $_GET['srch'];
	$typ = $_GET['type'];
	rijksmuseum_search($srch,$typ); //dumps the content, you can manipulate as you wish to
	tim("end action");
	exit;
} else if ($action == "json-addlink-rijksmuseum") {
	$a = $_GET['a'];
	$b = $_GET['b'];
	$type = $_GET['type'];
	$comment = $_GET['comment'];
	rijksmuseum_addlink($a, $b, $type, $comment); //dumps the content, you can manipulate as you wish to
	tim("end action");
	exit;
} else if ($action == "json-addlink") {
	$a = $_GET['a'];
	$b = $_GET['b'];
	$type = $_GET['type'];
	$comment = $_GET['comment'];
	addlink($a, $b, $type, $comment); //dumps the content, you can manipulate as you wish to
	tim("end action");
	exit;
} else if ($action == "json-get-rijksmuseum") {
	$identifier = $_GET['identifier'];
	rijksmuseum_getitem($identifier); //dumps the content, you can manipulate as you wish to
	tim("end action");
	exit;
} else if ($action == "notdone") {

	//$h = "<html><head><style type='text/css'>body{margin:0;background-color:black;}</style></head><body><img src='http://jon651.glimworm.com/europeana/images/ding.png'></body></html>";
	$h = "<html><head><style type='text/css'>body{margin:0;background-color:black;}p{color:white;font-family:Helvetica;text-align:center;font-weight:bold;font-size:30px;height:200px;margin-top:250px;}a{color:white;text-decoration:none;font-size:32px;text-transform:uppercase;border-bottom:2px solid white;}</style></head><body><p>Not available yet,<br>would you like to go the the Rijksmuseum studio and print from <a href='#'>there</a>?</p></body></html>";
	
	echo $h;
	tim("end action");
	exit;

} else if ($action == "error") {
	$err = $_GET['err'];
	error_log("error");
	error_log($err);
	tim("end action");
	exit;

} else if ($action == "print") {
	//	http://jon651.glimworm.com/europeana/tes.php?action=print&identifier=
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
	echo "<h1>Print page for identifier $identifier</h1>";
	echo '<a title="Peecho" href="http://www.peecho.com/print/1112">Print</a>';
	tim("end action");
	exit;

} else if ($action == "info") {
	phpinfo();
	tim("end action");
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
	tim("end action");
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
	tim("end action");
	exit;

} else if ($action == "get-featured") {
	get_featured_items($_GET["lang"]);
	tim("end action");
	exit;

} else {
	echo $action;
	tim("end action");
	exit;
	
}

function get_featured_items($LANG) {

	$jsontxt = file_get_contents("eu_featured_items.".$LANG.".json");
	$items = json_decode($jsontxt);

	$help = file_get_contents("eu_help.".$LANG.".txt");

	$f = new obj();
	$retval = new obj();
	$retval->status = 0;
	$retval->status_msg = "searched";
	$retval->data = new obj();
	$retval->data->jsontxt = $jsontxt;
	$retval->data->items = $items;
	$retval->data->help = $help;
	echo json_encode($retval);
	

}

	
function isin($typ,$qf) {
	
	/*
	error_log("isin       ");
	error_log("isin ---1--- : " . "&".$typ."&");
	error_log("isin ---2--- : " . $qf."&");
	error_log("isin ---r--- : " . strpos("&".$typ."&",$qf."&"));
	error_log("isin       ");
	*/
	if (strpos("&".$typ."&",$qf."&")) return 1;
	return 0;
}
function isinarray($arr, $str) {
	foreach ($arr as $a) {
		if ($str === $a) return true;
	}
	return false;
}

function europeana_query($srch) {
	$str = str_replace(" ","_",$srch);
	return $str;
}


function get_type_from_line($t) {
	if ($t == null || $t === "") return false;
	
	if (strpos($t,"qf=") === 0 && strpos($t,":") !== false) {
		$TYPE = substr($t,3,strpos($t,":")-3);
		return $TYPE;
	}
	if (strpos($t,"&qf=") === 0 && strpos($t,":") !== false) {
		$TYPE = substr($t,4,strpos($t,":")-4);
		return $TYPE;
	}
	return false;
}
$DATA_PROVIDERS = array();
function optional($array_of_distinct_values, $string) {
	
	global $DATA_PROVIDERS;
	
	$TYPE = get_type_from_line($string);
	//error_log($string . "------" . $TYPE);
	if ($TYPE === false) return $string;
	
	if ($TYPE == "DATA_PROVIDER") {
		array_push($DATA_PROVIDERS,$string);
	}
	
	if (isinarray($array_of_distinct_values,$TYPE)) return "";
	return $string;
}
function get_data($srch,$typ,$start,$page,$query,$lng,$mls)
{

	/*
		srch		free text
		typ			whole string of options selected in the filter
		start		start range
		page		page number
		query		number relating to the pre made queries
		lng			language
		mls			multi languag search y/n
		
		
		
		distincttypes is the distinct types of query in the 
		
		
		
		
	*/
		
		
		

	global $timstring;
	global $DATA_PROVIDERS;
	
	$DATA_PROVIDERS = array();
	
		$_otyp = $typ;

		//http://europeana.eu/api/v2/search.json?query=Somme&rows=100&start=1&wskey=ZHKKYAIMYT
		$i = rand(1,7);
		$i = 1;
		$LINESPERPAGE = 36;
		
		if (!$start || $start == "") $start = "1";
		if (!$page || $page == "") $page = 0;
		$start = ($page * $LINESPERPAGE) + 1;
		
		
		// this is europeana's everything search
//		if ($srch == null || $srch == "") $srch = "*:*";

		tim("get data pre multiple translate");		

		$srch_t = "";	
		$english2 = null;
		// this is europeana's everything search
		if ($srch == null || $srch == "") {
			$srch = "*:*";
		} else {
			if ($mls == "y") {
				$english2 = english2($srch);
				$srch_t = $english2->retval;
				$srch = $srch_t;
			}
		}
		tim("get data post multiple translate");		

		// translate the refinement qyery into multiple languages if necessary
		if ($mls == "y") {
			if ($typ != "") {
				$typs = explode("&",$typ);
				$typ = "";
				foreach ($typs as $t) {
					if ($t != "") {
						if (strpos($t,"qf=") === 0 && strpos($t,":") === false) {
							$res = english2(substr($t,3));
							$typ .= ("&qf=".$res->retval);
						} else {
							$typ .= ("&".$t);
						}
					}
				}
			}
		}
		
		// find out what types of query are part of the additional types
		// e.g.
		//	TYPE
		//	DATA_PROVIDER
		$distinct_types = array();
		if ($typ != "") {
			$typs = explode("&",$typ);
			foreach ($typs as $t) {
				$TYPE = get_type_from_line($t);
				if ($TYPE !== false && isinarray($distinct_types,$TYPE) === false) array_push($distinct_types,$TYPE);
			}
		}


		
		$hide = "RIGHTS,REUSABILITY";
		if ($query != "") {
		
			$jsontxt = file_get_contents("eu_featured_items.".$lng.".json");
			$fitems = json_decode($jsontxt);
			$fitem = $fitems[$query];
			if ($fitem->hiddenquery != "") {
				$hqarray = explode("&",$fitem->hiddenquery);
				foreach ($hqarray as $hqa) {
					if ($hqa == "") {
						// nothing
					} else {
						$hqa = "&".$hqa;
						$TYPE = get_type_from_line($hqa);
						if ($TYPE === false) {
							$typ .= $hqa;
						} else {
							$typ .= optional($distinct_types,$hqa);
						}
					}
				}
//				$typ = $typ . $fitem->hiddenquery;
			}
			$hide = $fitem->hide;
			if (!$hide || $hide == "") {
				$hide = "RIGHTS,REUSABILITY";
			} else if (strpos($hide,"REUSABILITY") === false) {
				$hide .= ",REUSABILITY";
			}
		} else {
//			$hide = "RIGHTS,DATA_PROVIDER,TYPE";
			$hide = "RIGHTS,TYPE,REUSABILITY";
			$typ = $typ . optional($distinct_types,"&qf=TYPE:IMAGE");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Cat%C3%A1logo+Colectivo+de+la+Red+de+Bibliotecas+de+los+Archivos+Estatales\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Biblioteca+Virtual+del+Patrimonio+Bibliogr%C3%A1fico\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Biblioteca+Virtual+del+Ministerio+de+Defensa\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Rijksmuseum\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Институт+за+балканистика+с+Център+по+тракология\"");
//			$typ = $typ . "&qf=DATA_PROVIDER:\"Central+Library+of+Bulgarian+Academy+of+Sciences\"";
//			$typ = $typ . "&qf=DATA_PROVIDER:\"Museu+Nacional+de+Arqueologia\"";
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Riksantikvarieämbetet\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Royal+Belgian+Institute+of+Natural+Sciences\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"The+Royal+Botanic+Garden+Edinburgh\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"University+of+Tartu,+Natural+History+Museum\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Museum+of+Geology,+University+of+Tartu\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"The+National+Library+of+Poland+-+Biblioteka+Narodowa\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Biblioteca+Valenciana+Digital\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Fondo+Fotográfico+de+la+Universidad+de+Navarra\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Persmuseum\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Museum+Rotterdam\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Historisch+Museum+Rotterdam\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Biblioth%C3%A8que+de+l'Alliance+isra%C3%A9lite+universelle\"");
			$typ = $typ . optional($distinct_types,"&qf=DATA_PROVIDER:\"Amsterdam+Museum\"");
		}
		
//		$typ .= "&reusability=free";
		$typ .= "&reusability=open";
		
		// typ at this point is equal to the hiddenquery in the "query" or the default set of providers
		
		$typs = explode("&",$typ);
		
		error_log("========= start =====");
		error_log(print_r($typs,true));
		error_log("------");
		error_log(print_r($DATA_PROVIDERS, true));
		error_log("========= end ========");
		
		//	typs transfers this to an array
		
		$typ = "";
		$bytype = false;
		$hq = "";
		foreach($typs as $typs_item) {
			if ($typs_item == "") {
				// nothing
			} else if (strpos($typs_item,"qf=BYTYPE:") !== 0 ) {
				$typ .= ("&".$typs_item);
			} else {

				/* find the hidden query from the extra options list */			
				$extra_options = L("extra_options");
				$type_split_into_sections = explode(":",$typs_item);	// qf=BYTYPE:painting
				$option_label = $type_split_into_sections[1];			// painting
				$hq .= $option_label;
				foreach($extra_options["options"] as $option) {
					$hq .= ("\n label : ".$option["label"]);
				
					if ($option["label"] === $option_label) {
						$hq .= ("\n match , hq : " . $option["hiddenquery"]);
						$typ .= $option["hiddenquery"];
					} if (urlencode($option["label"]) === $option_label) {
						$hq .= ("\n match , hq : " . $option["hiddenquery"]);
						$typ .= $option["hiddenquery"];
					}
					
				}

				$bytype = true;
			}
		}
		
		
		
		$url='http://europeana.eu/api/v2/search.json?query='.europeana_query($srch).'&rows='.$LINESPERPAGE.'&start='.$start.'&wskey=ZHKKYAIMYT&profile=portal'.$typ;
		
		error_log("\n\n");
		error_log($url);
		error_log("\n\n");
		
		tim("get data pre curl");		
		
//		$url = $url . "&profile=portal"
		// query=*:*
		// &qf=DATA_PROVIDER:"Catálogo+Colectivo+de+la+Red+de+Bibliotecas+de+los+Archivos+Estatales"
		// &qf=TYPE:IMAGE
		// &profile=portal
		
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		tim("get data post curl");		
		$data = json_decode($data);
		tim("get data post curl and decode");		
		
		$totalitems = array();
		$types = array();
		
		//error_log($url);
		//error_log(print_r($data,true));
		
		foreach ($data->items as $itm) {
			
			$ok = false;
			if ($typ && $typ != "") {
				if ($typ == $itm->type) $ok = true;
			} else { 
				if ($itm->type == "VIDEO" || $itm->type == "IMAGE" || $itm->type == "TEXT") $ok = true;
			}
			$ok = true;
			
			if ($ok == true) {
				$item = new obj();	
				$item->id = $itm->id;
				$item->url = $itm->link;
				$item->image = (is_array($itm->edmPreview)) ? $itm->edmPreview[0] : $itm->edmPreview;
				$item->enclosure = (is_array($itm->edmPreview)) ? $itm->edmPreview[0] : $itm->edmPreview;
				$item->dataProvider = (is_array($itm->dataProvider)) ? $itm->dataProvider[0] : $itm->dataProvider;
				$item->rights = (is_array($itm->rights)) ? $itm->rights[0] : $itm->rights;
				$item->dcCreator = (is_array($itm->dcCreator)) ? $itm->dcCreator[0] : $itm->dcCreator;
				$item->guid = $itm->guid;
				$item->title = $itm->title[0];
				$item->description = "";
				$item->type = $itm->type;
//				$item->rights = $itm->rights;
				
//				$item->image = prepare_image_url($item->image);
//				$item->enclosure = prepare_image_url($item->enclosure);

				array_push($totalitems , $item);
			}
			
//			array_push($types , $itm->type);
			

		}

//		$types = array_unique($types);

/*
		array_push($types , "#total results : ".$data->totalResults);
		array_push($types , "#url : ".$url);
		array_push($types , "#pages : ");
		array_push($types , "&start=1|1");
		array_push($types , "&start=2|2");
*/
		$theme = "";
		if ($query != "") {
			$fi = file_get_contents("eu_featured_items.".$lng.".json");
			$fif = json_decode($fi);
			//array_push($types , "#queryset : ".$fif[$query]->txt);
			$theme = $fif[$query]->txt;
		}

		$extra_options = null;
		
		if (count($totalitems) > 0 && $bytype == false) {
			$extra_options = L("extra_options");
//			array_push($types , "#BYTYPE");
			$ccnt = 0;
			foreach($extra_options["options"] as $option) {
				$pos = "";
				if ($option["themes"] && $query != "") {
					$pos = strpos($option["themes"], $query);
				}
				$disp = ($pos != "") ? "y" : "n";
				$disp = ($pos === false) ? "n" : "y";
				if ($disp === "y") {
					if ($ccnt == 0) {
						array_push($types , "#BYTYPE");
					}
					array_push($types , "&qf=BYTYPE:".urlencode($option["label"])."|".($option["label"]) . "");
//					array_push($types , "&qf=BYTYPE:".urlencode($option["label"])."|".($option["label"]) ."-".($option["themes"]) . " q=" .$query. "(" .$pos. "/ [[".print_r($pos,true)."]] / $disp )");
					$ccnt++;
				}
			}
		}


		if ($data->facets) {
			foreach ($data->facets as $facet) {
			
				if (!strpos((",,".$hide.",") , (",".$facet->name.","))) {

					$section_head_not_added = true;
					foreach ($facet->fields as $field) {
						$xxx = "";
						$x = isin($typ,"&qf=".$facet->name.":\"".urlencode($field->label)."\"");	// match quote
						$xx = isin($typ,"&qf=".$facet->name.":".urlencode($field->label)."");		// match noquote
						$x2 = isin($typ,"&qf=".$facet->name.":\"".str_replace("%27","'",urlencode($field->label))."\"");
						$xx2 = isin($typ,"&qf=".$facet->name.":".str_replace("%27","'",urlencode($field->label))."");
						$x3 = isin($typ,"&qf=".$facet->name.":\"".str_replace("%2C",",",urlencode($field->label))."\"");
						$xx3 = isin($typ,"&qf=".$facet->name.":".str_replace("%2C",",",urlencode($field->label))."");
						$x1 = isin($typ,"&qf=".$facet->name.":\"".($field->label)."\"");
						$xx1 = isin($typ,"&qf=".$facet->name.":".($field->label).""); // match no quote

						$xxx = "[".$x."-".$xx."-".$x2."-".$xx2."-".$x1."-".$xx1."(".$typ."/".$facet->name.")]";
						$xcnt = ($x+$xx+$x1+$xx1+$x2+$xx2+$x3+$xx3);
						/* exclude the option is already selected in the types */
						
						
						// if xcnt is not 0 then it is already selected in the typ string which contains all of the selected searches, therefore anythig about 1 should not be displayed… unless this is an institution
						
						if (strpos($facet->name,"DATA_PROVIDER") !== false){
							if ($section_head_not_added == true) {
								$section_head_not_added = false;
								array_push($types , ("#".$facet->name));	// push heading

								foreach ($DATA_PROVIDERS as $__typs) {
									$_typs = substr($__typs,1);
									if (strpos($_typs,"DATA_PROVIDER") == 3) { 	//	"qf=DATA" (index of Data will be 3)
										$lbl = substr($_typs,18,strlen($_typs)-19);
										$lbl = urldecode($lbl);
										
										$__pos = strpos(urldecode($_otyp) , $_typs );
										$__pos1 = strpos($_otyp , $_typs );
										
										error_log("jcjcjcjc ($__pos)($__pos1) ".$_typs."|".$lbl. " // ". urldecode($_otyp));
//										error_log("jcjcjcjc 0 ($__pos) ".$_typs);
//										error_log("jcjcjcjc 1 ($__pos) ".$_otyp);
										
										if ($__pos === false && $__pos1 === false) {
											array_push($types , "&".$_typs."|".$lbl);
										}

									}
								}
								/*
								foreach ($typs as $_typs) {
									if (strpos($_typs,"DATA_PROVIDER") == 3) { 	//	"qf=DATA" (index of Data will be 3)
										$lbl = substr($_typs,18,strlen($_typs)-19);
										$lbl = urldecode($lbl);
										error_log("jcjcjcjc ".$_typs."|".$lbl. " // ". urldecode($_otyp));
										
										if (strpos(urldecode($_otyp) , $_typs ) === false) {
											array_push($types , "&".$_typs."|".$lbl);
										}

									}
								}
								*/
							}
							$xcnt = 1;
						}
						


						/*
						$xcnt2 = 0;
						
						if ($xcnt == 0 && strpos($facet->name,"DATA_PROVIDER") !== false) {

							error_log("jcjcjc checking // ".$facet->name." // xcnt =".$xcnt. " // match ".(strpos($facet->name,"DATA_PROVIDER")) . " // field = ".$field->label . " (".urlencode($field->label).") // TYPS = " . implode("&",$typs));

							$xcnt2 = 0;
							foreach ($typs as $_typs) {
								if (strpos($_typs,"DATA_PROVIDER") !== false) {
								
									$xcnt20 = (strpos($_typs,urlencode($field->label))) ? 1 : 0;
									$xcnt21 = (strpos($_typs,str_replace("%27","'",urlencode($field->label)))) ? 1 : 0;
									$xcnt22 = (strpos($_typs,str_replace("%2C","'",urlencode($field->label)))) ? 1 : 0;

									$xcnt20a = (strpos($_typs,urlencode($field->label)));
									$xcnt21a = (strpos($_typs,str_replace("%27","'",urlencode($field->label))));
									$xcnt22a = (strpos($_typs,str_replace("%2C","'",urlencode($field->label))));
									
									$xcnt2 += ($xcnt20 + $xcnt21 + $xcnt22);

									error_log("jcjc searching for ".urlencode($field->label)." in ".$_typs . " xcnt2 = $xcnt2 [$xcnt20a / $xcnt21a / $xcnt22a]");

								}
							}
							error_log("jcjc searching for ".$field->label." in  xcnt2 = $xcnt2");

							if ($xcnt2 == 0) $xcnt++;
						}
						*/
						
						//&qf=DATA_PROVIDER:"Listasafn+Reykjav%C3%ADkur%2FReykjavik+Art+Museum"
						
//						if ($xcnt == 0) { // this was the wrong way round
						if ($xcnt == 0) {
							if ($section_head_not_added == true) {
								//array_push($types , "#_".$facet->name." ".$hide);
								array_push($types , ("#".$facet->name));
								$section_head_not_added = false;
							}
							if (strpos($field->label," ")) {
								array_push($types , "&qf=".$facet->name.":\"".urlencode($field->label)."\"|".$field->label . " (".$field->count.") ");
							} else {
								array_push($types , "&qf=".$facet->name.":".urlencode($field->label)."|".$field->label . " (".$field->count.") ");
							}
						} else {
//							array_push($types , "&qf=".$facet->name.":".urlencode($field->label)."|".$field->label . " (".$field->count.") $xxx ");
						}
					}
				}
			}
		}

//		error_log("jcjcjc URL=" . $url);

		$retval = new obj();
		$retval->status = 0;
		$retval->status_msg = ("success ".count($totalitems)." results");
		$retval->srch_t = $srch_t;
		$retval->english2 = $english2;
		$retval->data = new obj();
		$retval->data->items = $totalitems;
		$retval->data->types = $types;
		$retval->data->totalResults = $data->totalResults;
		$retval->data->theme = $theme;
		$retval->data->perpage = $LINESPERPAGE;
		$retval->data->url = $url;
		$retval->data->status_msg = (count($totalitems) == 0) ? L("e404") : "";
		$retval->tim = tims();
		$retval->hq = $hq;
//		$retval->eudata = $data;
		$retval->distinct_types = $distinct_types;
		$retval->extra_options = $extra_options;
		
		//error_log(print_r($retval,true));
		
		echo json_encode($retval, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		//echo json_encode($retval);

}
function A($arr) {
	$rv = "";
	$comma = "";
	foreach($arr as $a) {
		$rv = $rv . $comma .$a;
		$comma = ", ";
	}
//	return $a;
	return $rv;
}
function PL($prefLabel) {

	if ($prefLabel->en) return $prefLabel->en[0];
	if ($prefLabel->def) return $prefLabel->def[0];
	if ($prefLabel->nl) return $prefLabel->nl[0];
	return "..";
}
function itm($identifier,$url,$comment,$type) {
	$item2 = new obj();
	$item2->id = "";
	$item2->identifier = $identifier;
	$item2->url = $url;
	$item2->type = $type;
	$item2->comment = $comment;
	$item2->uid = "";
	$item2->ts = "";
	return $item2;
}

function mta($L, $V) {
	$item2 = new obj();
	$item2->label = $L;
	$item2->value = $V;
	return $item2;
}


function get_links($itm) {
	$links = array();
		foreach ($itm->agents  as $ag) {
			$item2 = new obj();
			$item2->id = "";
			$item2->identifier = $identifier;
			$item2->url = $ag->about;
			$item2->type = "who";
			$item2->comment = PL($ag->prefLabel);
			$item2->uid = "";
			$item2->ts = "";
			array_push($links , $item2);
		}
		foreach ($itm->aggregations  as $ag) {
			$item2 = new obj();
			$item2->id = "";
			$item2->identifier = $identifier;
			$item2->url = $ag->webResources[0]->about;
			$item2->type = "general link";
			$item2->comment = PL($ag->edmDataProvider);
			$item2->uid = "";
			$item2->ts = "";
			array_push($links , $item2);
		}
		$eua = $itm->europeanaAggregation;
//		array_push($links , itm($identifier,$eua->about,"aggregation","general link"));
		array_push($links , itm($identifier,$eua->edmLandingPage,L("View_in_Europeana"),"general link"));
//		array_push($links , itm($identifier,array_or_string($eua->edmPreview),"Preview","general link"));
		
		
		foreach ($itm->timespans  as $ag) {
			$item2 = new obj();
			$item2->id = "";
			$item2->identifier = $identifier;
			$item2->url = $ag->about;
			$item2->type = "when";
			$item2->comment = PL($ag->prefLabel);
			$item2->uid = "";
			$item2->ts = "";
			array_push($links , $item2);
		}
		
		return $links;
}
function getLandingPage($itm) {
	$eua = $itm->europeanaAggregation;
	return $eua->edmLandingPage;
}
function  get_euro_itm($identifier) {
		$url='http://europeana.eu/api/v2/record'.$identifier.'.json?wskey=ZHKKYAIMYT';
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		$data = json_decode($data);
		return $data->object;
}

function get_item($identifier, $lang = "en")
{
//http://europeana.eu/api/v2/record/92062/ED02C5CC49DEACF9A0B864E121D83955C355B971.json?wskey=ZHKKYAIMYT
		tim("get item - start");
		$url='http://europeana.eu/api/v2/record'.$identifier.'.json?wskey=ZHKKYAIMYT';
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$data = curl_exec($ch);
		tim("get item - got data from remote");
		$data = json_decode($data);
		tim("get item - got data from remote - decoded");

		
		$totalitems = array();
		$itm = $data->object;
			
		$item = new obj();	
		$item->id = $itm->about;
		$item->identifier = $item->id;
		$item->type = $itm->type;
		foreach ($itm->aggregations as $itm1) {
			$item->img = $itm1->edmObject;
		}
		$item->img = prepare_image_url($item->img);
		
//		$item->title = array_or_string($itm->title);
//		$item->originaltitle = $itm->title;

		$item->title = array_or_string($itm->proxies[0]->dcTitle->def);
		$item->untranslated_title = $item->title;
		if ($item->title && $item->title != "") {
			$tr = translateObject($identifier."-title-".$lang,$item->title,$lang);
			if ($tr != "") {
				//$item->title .= "\n\nTranslated by Google:\n".$tr;
				$item->title .= "\n\n".$tr;
			}
		}
		
		$item->originaltitle = $itm->proxies[0]->dcTitle;
		
		
		
		
			
		/*$desc = $itm->proxies[0]->dcSubject->def[0];
			
		if ($desc == null){*/
			$desc=$itm->proxies[0]->dcDescription->def[0];
		//}
		
		if ($desc && $desc != "") {
			$tr = translateObject($identifier."-description-".$lang,$desc,$lang);
			if ($tr != "") {
				//$desc .= "\n\nTranslated by Google:\n".$tr;
				$desc .= "\n\n".$tr;
			}
		}
			
		$item->description = $desc;
		
		
		
		
			//$item->creator = $itm->agents[0]->prefLabel[0]->en;
		array_push($totalitems , $item);
		
		$met = array();
		$links = array();



		require("db.php");
		$database = "OpenCultureApp";
		$db = mysql_connect($dbhost,$username,$password);
		mysql_select_db($database) or die("Unable to select database");
		mysql_query("SET NAMES utf8", $db);
		mysql_query( "SET CHARACTER SET utf8", $db );

		tim("get item - connect to DB");


		$query3="select * from musev21_eu_link where identifier = '" . $identifier .  "' order by type";
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
		$links = array_merge($links , get_links($itm));
		
		$eua = $itm->europeanaAggregation;
		$agg = $itm->aggregations;

		$item->ccsearchterm = $item->untranslated_title;

		/* list of fields from david's email */


		$item2 = new obj();
		$item2->label = L("Title");
		$item2->value = A($itm->proxies[0]->dcTitle->def);
//		if ($item2->value != "") array_push($met , $item2);
		
		$item2 = new obj();
		$item2->label = L("Creator");
		$item2->value = A($itm->proxies[0]->dcCreator->def);
		if ($item2->value != "") {
			$item->ccsearchterm = $item2->value;
			$item2->value = "con:".$item2->value;
			array_push($met , $item2);

		}

		$item2 = mta(L("Type"),$itm->type);
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = new obj();
		$item2->label = L("Created");
		$item2->value = A($itm->proxies[0]->dctermsCreated->def);
		if ($item2->value != "") array_push($met , $item2);

		$item2 = new obj();
		$item2->label = L("Rights");
		$item2->value = A($itm->proxies[0]->dcRights->def);
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = new obj();
		$item2->label = L("Type");
		$item2->value = A($itm->proxies[0]->dcType->def);
		if ($item2->value != "") array_push($met , $item2);


		/* end : list of fields from david's email */


//		$item2 = mta("Rights","§".PL($eua->edmRights));
//		if ($item2->value != "") array_push($met , $item2);


		$rights = $agg[0]->edmRights->def;

		foreach ($rights as $right) {
			
			$CC = "o";
			$BY = "n";
			$SA = "t";
			$PD = "s";
			$NC = "q";
			$ND = "r";
			$CC0 = "u";
			
			$OPEN=" P";

			// see
			// http://deanbirkett.name/playground/europeana/styleguide/#cc

			$rights_found = true;
			$rights_str = $right.$OPEN;
			if ($right == "http://creativecommons.org/licenses/by/1.0/") $rights_str = "§".$CC.$BY.$OPEN;
			else if ($right == "http://creativecommons.org/licenses/by-sa/1.0/") $rights_str = "§".$CC.$BY.$SA.$OPEN;
			else if ($right == "http://creativecommons.org/licenses/by/3.0/") $rights_str = "§".$CC.$BY.$OPEN;
			else if ($right == "http://creativecommons.org/licenses/by-sa/3.0/") $rights_str = "§".$CC.$BY.$SA.$OPEN;
			else if ($right == "http://creativecommons.org/publicdomain/mark/1.0/") $rights_str = "§".$PD.$OPEN; 
			else $rights_found = false;

			if ($rights_found === true) {
				$item2 = mta(L("Rights"),$rights_str."|".$right);
				if ($item2->value != "") array_push($met , $item2);
				//$item2 = mta("Rightsaa","§".$agg->edmRights->def);
				//if ($item2->value != "") array_push($met , $item2);
			}
		}


		$item2 = mta(L("Language"),PL($eua->edmLanguage));
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = mta(L("Country"),PL($eua->edmCountry));
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = mta("Collection Name",A($eua->edmCollectionName));
//		if ($item2->value != "") array_push($met , $item2);
		

		$item2 = new obj();
		$item2->label = L("Contributor");
		$item2->value = A($itm->proxies[0]->dcContributor->def);
		if ($item2->value != "") array_push($met , $item2);


		$item2 = new obj();
		$item2->label = L("Format");
		$item2->value = A($itm->proxies[0]->dcFormat->def);
//		if ($item2->value != "") array_push($met , $item2);


		$item2 = new obj();
		$item2->label = L("Source");
		$item2->value = A($itm->proxies[0]->dcSource->def);
//		if ($item2->value != "") array_push($met , $item2);




		$item2 = new obj();
		$item2->label = L("Extent");
		$item2->value = A($itm->proxies[0]->dctermsExtent->def);
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = new obj();
		$item2->label = L("Part_of");
		$item2->value = A($itm->proxies[0]->dctermsIsPartOf->def);
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = new obj();
		$item2->label = L("Issued");
		$item2->value = A($itm->proxies[0]->dctermsIssued->def);
//		if ($item2->value != "") array_push($met , $item2);

		$item2 = new obj();
		$item2->label = L("Medium");
		$item2->value = A($itm->proxies[0]->dctermsMedium->def);
		if ($item2->value != "") array_push($met , $item2);

		$item2 = mta(L("Rights"),"§o");
//		array_push($met , $item2);

//		$item2 = mta("Europeana identifier",$identifier);
//		array_push($met , $item2);


		
		$desc=$itm->proxies[0]->dcDescription->def[0];



//		$item->twitter_image = "http://www.europeana.eu/portal/sp/img/europeana-logo-en.png";
		$item->twitter_image = $item->img;
		$item->twitter_text = $item->untranslated_title . " #europeana";
		$item->twitter_link = getLandingPage($itm);	//"http://www.europeana.eu/";
		
		$item->facebook_appid = "185778248173748";	//"333368490063557";


		$item->ccwikipedia = "http://en.wikipedia.org/wiki/". str_replace(" ","_",$item->ccsearchterm);
		$item->ccwikipediasearch = "http://en.wikipedia.org/wiki/Special:Search?search=".urlencode($item->ccsearchterm)."&go=Go";
		$item->ccgooglesearch = "http://www.google.com/search?q=".urlencode($item->ccsearchterm);
		
//		$item->button2 = "images/glyphicons_382_youtube1.png";
//		$item->button2_link = "http://www.youtube.com/?nomobile=1&svr=y";
//		$item->button3 = "/images/glyphicons_395_flickr.png";
//		$item->button3_link = "http://www.flickr.com/?svr=y";

		$item->button2 = "images/v2/wikipedia.png";
		$item->button2_link = "http://en.wikipedia.org/wiki/Special:Search?search=".urlencode($item->ccsearchterm)."&go=Go";

		$item->button3 = "/images/v2/wikimedia.png";
		$item->button3_link = "http://commons.wikimedia.org/wiki/Special:Search?search=".urlencode($item->ccsearchterm)."&go=Go";
		
		
//		$links2 = array();
//		foreach ($itm->aggregations  as $agg) {
//			foreach ($agg->webResources as $wr) {
//				array_push($links2 , $wr);
//			}
//		}
		
		tim("get item - prepare response");

		$retval = new obj();
		$retval->status = 0;
		$retval->status_msg = "searched";
		$retval->data = $totalitems;
		$retval->data1 = new obj();
		$retval->data1->buts = array();
		$but1->title = "Peecho print";
		$but1->url = "http://jon651.glimworm.com/europeana/tes.php?action=notdone";
		$retval->data1->links = $links;
		$retval->data1->meta = $met;
//		$retval->data1->links2 = $links2;
		$retval->data1->original = $data;
//		$retval->data1->aggregations = $itm->aggregations;
		echo json_encode($retval);
		tim("get item - end");
		
}

function english2($val) {

	$langs = array("en","fr","nl","de","pl","es","bg","sv");
	$newtext = $val;
	$responses = array();
	$texts = array();
	
	
	if ($val == false || $val == "") {
		$ob = new obj();
		$ob->responses = array();
		$ob->text = "";
		$ob->retval = "";
		return $ob;
	}
	

	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select * from musev21_eu_trans where txt = '". mysql_escape_string($val) ."';";
	error_log($query);
	$result=mysql_query($query);
	if (mysql_numrows($result) > 0) {

		$responses = array();
		$detected_lang = mysql_result($result,0,"detected_lang");

		foreach ($langs as $lang) {
			if (mysql_result($result,0,$lang) != "") {
				$newtext .= "+OR+";
				$newtext .= mysql_result($result,0,$lang);
				array_push($responses,"language ".$lang." found from sql (detected ".$detected_lang."), text [".mysql_result($result,0,$lang)."]");
				array_push($texts, strtolower(mysql_result($result,0,$lang)));
			}
		}
		
		$newtext = implode("+OR+",array_unique($texts));
		
		$ob = new obj();
		$ob->responses = $responses;
		$ob->text = $newtext;
		$ob->retval = "(".$newtext.")";
		return $ob;
	}
	
	$query = ("insert into musev21_eu_trans (id,txt) values(0,'". mysql_escape_string($val) ."')");
	error_log($query);
	mysql_query($query);

	$newid = mysql_insert_id();
	error_log($newid);
	

	
	


	

	foreach ($langs as $lang) {
		$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&target=".$lang."&q=" . urlencode($val);
		$jsontxt = file_get_contents($url);
		$data = json_decode($jsontxt);
		
		$ob = new obj();
		$ob->target = $lang;
		$ob->response = $data;
		array_push($responses, $ob);
		
		if (count($data->data->translations) > 0) { 
			$newtext .= "+OR+";
			$newtext .= $data->data->translations[0]->translatedText;
			array_push($texts, strtolower($data->data->translations[0]->translatedText));

			$query = "update musev21_eu_trans set ".$lang." = '". mysql_escape_string($data->data->translations[0]->translatedText) ."' , detected_lang = '".$data->data->translations[0]->detectedSourceLanguage."' where id=".$newid;
			error_log($query);
			
			mysql_query($query);
		}
	}
	$newtext = implode("+OR+",array_unique($texts));
	
	$ob = new obj();
	$ob->responses = $responses;
	$ob->text = $newtext;
	$ob->retval = "(".$newtext.")";
	return $ob;
//	return "(".$newtext.")";
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

function translate($val, $target = "en") {
	$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=".$target."&q=" . urlencode($val);


	$jsontxt = file_get_contents($url);
	$data = json_decode($jsontxt);

	if (count($data->data->translations) > 0) {
		$newtext = $data->data->translations[0]->translatedText;
		return $newtext;
	}
	return $txt;
}


function translateObject($key, $val, $target = "en") {

	require("db.php");
	$database = "OpenCultureApp";
	$db = mysql_connect($dbhost,$username,$password);
	mysql_select_db($database) or die("Unable to select database");
	mysql_query("SET NAMES utf8", $db);
	mysql_query( "SET CHARACTER SET utf8", $db );
	$query="select * from eu_trans_object where _key = '$key';";
	error_log($query);
	$result=mysql_query($query);
	if (mysql_numrows($result) > 0) {

		$dl = mysql_result($result,0,"detected_lang");
		$txt = mysql_result($result,0,"translation");
		return html2text($txt);
	}

	$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&source=nl&target=".$target."&q=" . urlencode($val);
	$url = "https://www.googleapis.com/language/translate/v2?key=AIzaSyCni0vI8g62dvNS2xDkJ5EYIoul3nbIqoc&target=".$target."&q=" . urlencode($val);


	$jsontxt = file_get_contents($url);
	$data = json_decode($jsontxt);
	
	error_log($jsontxt);

	if (count($data->data->translations) > 0) {
		$newtext = $data->data->translations[0]->translatedText;
		$dl = $data->data->translations[0]->detectedSourceLanguage;

		$query = ("insert into musev21_eu_trans_object (id,_key,txt,detected_lang,translation) values(0,'".$key."','". mysql_escape_string($val) ."','".$dl."','".mysql_escape_string($newtext)."')");
		error_log($query);
		mysql_query($query);
		return html2text($newtext);
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
		$query="select * from musev21_rijksmuseum_index where publisher = '" . $value .  "'";
		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"title")."</a></li>";
		}
	}

	if ($attribute == "creator") {
		$query="select * from musev21_rijksmuseum_index where creator = '" . $value .  "'";
		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"title")."</a></li>";
		}
	}

	if ($attribute == "type") {
		$query="select * from musev21_rijksmuseum_index where type = '" . $value .  "'";
		$result=mysql_query($query);
		for ($r=0; $r < mysql_numrows($result); $r++) {
			echo "<li>&raquo; <a href='tes.php?action=linkcloud&identifier=".mysql_result($result,$r,"identifier")."'>".mysql_result($result,$r,"title")."</a></li>";
		}
	}


	if ($attribute == "link") {
		$query="select * from musev21_rijksmuseum_link where url = '" . $value .  "' order by type";

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
	$query="select * from musev21_rijksmuseum_index where identifier = '" . $identifier .  "'";
	$result=mysql_query($query);
	if (mysql_numrows($result) > 0) {
	
	
		echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=publisher&value=".mysql_result($result,0,"publisher")."'>publisher : ".mysql_result($result,0,"publisher")."</a></li>";
		echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=creator&value=".mysql_result($result,0,"creator")."'>Creator : ".mysql_result($result,0,"creator")."</a></li>";
		echo "<li>&raquo; <a href='tes.php?action=cloud-attribute&attribute=type&value=".mysql_result($result,0,"type")."'>Type : ".mysql_result($result,0,"type")."</a></li>";
		
	
	
		$query3="select * from rijksmuseum_link where identifier = '" . $identifier .  "' order by type";
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
	$query="select * from musev21_rijksmuseum_index where identifier = '" . $identifier .  "'";
	$result=mysql_query($query);
	
	$query999="INSERT INTO `musev21_rijksmuseum_tracking` (`id`, `type`, `sort`, `content`) VALUES ('0', 'item', 'get', '$identifier');";
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
	//$but1->url = "http://jon651.glimworm.com/europeana/tes.php?action=print&identifier=".$identifier;
	$but1->url = "http://jon651.glimworm.com/europeana/tes.php?action=notdone";
	array_push($buts , $but1);

	/*$but1 = new obj();
	$but1->title = "Cloud of links";
	$but1->url = "http://jon651.glimworm.com/europeana/tes.php?action=linkcloud&identifier=".$identifier;
	array_push($buts , $but1);*/
	
		
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
	$s = "insert into musev21_eu_link  (id,ts,identifier,url,type,comment,uid) values (0,now() ";
	$s = $s . "," . Q($a);
	$s = $s . "," . Q($b);
	$s = $s . "," . Q(strtolower($type));
	$s = $s . "," . Q(mysql_escape_string($comment));
	$s = $s . ",'');";
	
	$result=mysql_query($s);
	
	
	$links = array();
		$query3="select * from musev21_eu_link where identifier = '" . ($a) .  "' order by type";
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
	
	$itm = get_euro_itm($a);
	$links = array_merge($links , get_links($itm));
	
	

	$retval = new obj();
	$retval->sql = $s;
	$retval->status = 0;
	$retval->status_txt = "link added " . $action_comment;
	$retval->action_error = $action_error;
	$retval->links = $links;
	echo json_encode($retval);
	
	
}
function addlink($a, $b, $type, $comment) {

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
	$s = "insert into musev21_eu_link  (id,ts,identifier,url,type,comment,uid) values (0,now() ";
	$s = $s . "," . Q($a);
	$s = $s . "," . Q($b);
	$s = $s . "," . Q(strtolower($type));
	$s = $s . "," . Q(mysql_escape_string($comment));
	$s = $s . ",'');";
	
	$result=mysql_query($s);
	
	
	$links = array();
		$query3="select * from musev21_eu_link where identifier = '" . ($a) .  "' order by type";
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
	
	$itm = get_euro_itm($a);
	$links = array_merge($links , get_links($itm));
	
	

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
	$query999="INSERT INTO `musev21_rijksmuseum_tracking` (`id`, `type`, `sort`, `content`) VALUES ('0', 'term', 'srch', '$srch');";
	$result999=mysql_query($query999);

	$query="select * from musev21_rijksmuseum_index where description like '%" . $srch .  "%' or description_en like '%" . $srch .  "%' or title like '%" . $srch .  "%' or title_en like '%" . $srch .  "%' or creator like '%" . $srch .  "%' or subject like '%" . $srch .  "%' or publisher like '%" . $srch .  "%' or dat like '%" . $srch .  "%' ORDER BY (type != 'schilderij'),type,id limit 150";

	if ($typ && $typ != "") {
		$query="select * from musev21_rijksmuseum_index where (type = '".$typ."' or type_en = '".$typ."') and ( description like '%" . $srch .  "%' or description_en like '%" . $srch .  "%' or title like '%" . $srch .  "%' or title_en like '%" . $srch .  "%' or creator like '%" . $srch .  "%' or subject like '%" . $srch .  "%' or publisher like '%" . $srch .  "%' or dat like '%" . $srch .  "%') ORDER BY (type != 'schilderij'),type,id limit 150";
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
	

	$query="select distinct type_en as itm from musev21_rijksmuseum_index where " . $_where;
	$result=mysql_query($query);
	$types = array();
	for ($r=0; $r < mysql_numrows($result); $r++) {
//		$item = "type:".mysql_result($result,$r,"itm");
		$item = mysql_result($result,$r,"itm");
		array_push($types , $item);
	}

	$query="select distinct creator as itm from musev21_rijksmuseum_index where " . $_where;
	$result=mysql_query($query);
	$creators = array();
	for ($r=0; $r < mysql_numrows($result); $r++) {
		$item = mysql_result($result,$r,"itm");
		array_push($creators , $item);
	}
	
	$query="select distinct dat as itm from musev21_rijksmuseum_index where " . $_where;
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
