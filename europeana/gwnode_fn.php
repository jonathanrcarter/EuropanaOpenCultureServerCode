<?php

function add_node($name, $type, $url) {
	/*
		POST http://localhost:7474/db/data/node/ { "name" : "glimworm.com" , "type" : "website" , "url" : "http://www.glimworm.com" }
	*/

}
function get_node_by_name($name) {

}
function add_relationship($from, $to, $type) {

}
function get_route($from, $to) {

}

function get_node_by_id($id) {
	/*
		GET http://localhost:7474/db/data/node/1
	*/
	
	
	$url = sprintf("http://localhost:7474/db/data/node/%s",$id);

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
	$retval->status_msg = "got-node";
	$retval->data = $items;
	
	$retval->data1 = new obj();
	$retval->data1->id = $id;
	$retval->data1->url = $url;

	echo json_encode($retval);

}


?>