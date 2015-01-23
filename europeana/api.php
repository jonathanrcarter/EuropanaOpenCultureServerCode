<?php

session_start();

if ($_GET["action"] != "") $_POST = $_GET;
$action = $_POST["action"];


$browser = $_POST["browser"];
if ($browser && $browser != "") {
	$_SESSION['browser'] = $browser;
}

function get_data($srch)
{

	$totalitems = array();
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

$htop = "";
$htop = $htop .'<!DOCTYPE html>';
$htop = $htop .'<html lang="en">';
$htop = $htop .'  <head>';
$htop = $htop .'    <meta charset="utf-8">';
$htop = $htop .'    <title>Glimworm - Europeana API</title>';
$htop = $htop .'    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
$htop = $htop .'    <meta name="description" content="">';
$htop = $htop .'    <meta name="author" content="">';
$htop = $htop . "<script type='text/javascript' src='ckeditor/ckeditor.js'></script>";
$htop = $htop .'    <!-- Le styles -->';
$htop = $htop .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap.css" rel="stylesheet">';
$htop = $htop .'	<link rel="stylesheet" type="text/css" href="lib/css/prettify.css">';
$htop = $htop .'    <link href="http://www.glimworm.com/_assets/moock/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">';

$htop = $htop .'    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->';
$htop = $htop .'    <!--[if lt IE 9]>';
$htop = $htop .'      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>';
$htop = $htop .'    <![endif]-->';

$htop = $htop .'    <!-- Le fav and touch icons -->';
$htop = $htop .'    <link rel="shortcut icon" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/favicon.ico">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-144-precomposed.png">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-114-precomposed.png">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-72-precomposed.png">';
$htop = $htop .'    <link rel="apple-touch-icon-precomposed" href="http://www.glimworm.com/_assets/moock/bootstrap/ico/apple-touch-icon-57-precomposed.png">';
$htop = $htop .'  </head>';
$htop = $htop .'  <body>';
$htop = $htop .'    <div class="navbar navbar-fixed-top">';
$htop = $htop .'      <div class="navbar-inner">';
$htop = $htop .'        <div class="container">';
$htop = $htop .'          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'            <span class="icon-bar"></span>';
$htop = $htop .'          </a>';
$htop = $htop .'          <a class="brand" href="#">Glimworm - Europeana API</a>';
$htop = $htop .'          <div class="nav-collapse">';
$htop = $htop .'            <ul class="nav">';
$htop = $htop .'              <li <li class="dropdown" id="menu1"><a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">Home <b class="caret"></b></a>';
$htop = $htop .'              	<ul class="dropdown-menu">';
$htop = $htop .'              		<li><a href="api.php">Home</a></li>';
$htop = $htop .'              	</ul>';
$htop = $htop .'              </li>';
$htop = $htop .'              <li><a href="api.php?action=json-srch&srch=war">europeana</a></li>';
$htop = $htop .'            </ul>';
$htop = $htop .'          </div><!--/.nav-collapse -->';
$htop = $htop .'        </div>';
$htop = $htop .'      </div>';
$htop = $htop .'    </div>';
$htop = $htop .'    <div class="container">';
$hbot = "";
$hbot = $hbot .'      <footer>';
$hbot = $hbot .'        <p>&copy; Glimworm 2012</p>';
$hbot = $hbot .'      </footer>';
$hbot = $hbot .'    <!-- Le javascript';
$hbot = $hbot .'    ================================================== -->';
$hbot = $hbot .'    <!-- Placed at the end of the document so the pages load faster -->';
$hbot = $hbot .'    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-transition.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-alert.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-modal.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-dropdown.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-scrollspy.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-tab.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-tooltip.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-popover.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-button.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-collapse.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-carousel.js"></script>';
$hbot = $hbot .'    <script src="http://www.glimworm.com/_assets/moock/bootstrap/js/bootstrap-typeahead.js"></script>';
$hbot = $hbot .'  </body>';
$hbot = $hbot .'</html>';



if ( $action == "json-srch" ) {
	$srch = $_GET["srch"];
	$ch1 = curl_init();
    $url = "http://api.europeana.eu/api/opensearch.json";
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch1, CURLOPT_URL, $url.'?searchTerms='.$srch.'&wskey=ZHKKYAIMYT');
    $data = curl_exec($ch);
	curl_close($ch);
	
	$items = json_decode($data);
//	array_push($totalitems , $items->items);
	$totalitems = array_merge($totalitems , $items->items);
	
	echo $totalitems;
    
}else{
	
	echo $htop;
	echo "<legend>Glimworm -> europeana srch</legend>";
	echo $hbot;
	
}
				

?>