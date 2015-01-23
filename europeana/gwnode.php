<?php



error_reporting(-1);


class obj {
}
function Q($S) {
	return "'" . $S . "'";
}


require_once("gwnode_fn.php");

if ($_GET["action"] == "") $_GET = $_POST;
$action = $_GET['action'];

if ($action == "get-node-by-id") {
	/*
		example : http://jon651.glimworm.com/europeana/gwnode.php?action=get-node-by-id&id=2840
	*/
	$srch = $_GET['id'];
	get_node_by_id($id);
	exit;
}

if ($action == "get-node-by-name") {
	$name = $_GET['name'];
	get_node_by_name($name); 
	exit;
}
if ($action == "add-node") {
	$name = $_GET['name'];
	$type = $_GET['type'];
	$url = $_GET['url'];
	add_node($name, $type, $url); 
	exit;
}
if ($action == "add-node") {
	$from = $_GET['from'];
	$to = $_GET['to'];
	$type = $_GET['type'];
	add_relationship($from, $to, $type); 
	exit;
}
if ($action == "get-route") {
	$from = $_GET['from'];
	$to = $_GET['to'];
	get_route($from, $to); 
	exit;
}



?>