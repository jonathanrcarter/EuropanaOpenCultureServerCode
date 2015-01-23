<?php
require 'class-api-request.php';
require 'class-record-parser.php';
require 'class-event-based-class.php';
function Q($S) {
	return "'" . mysql_real_escape_string($S) . "'";
}

class Harvest extends EventBasedClass {
    private $requestNr = 0;
    private $api;

    function __construct($apikey) {
        $this->api = new ApiRequest($apikey);
    }
    

    public function harvest($resumptiontoken = false) {

    	echo "STARTING LOOP …";

		do {
	    	$resumptiontoken = $this->harvest2($resumptiontoken);
	    	echo "SLEEPING …";
	    	sleep(5);
    	} while ($resumptiontoken != false);

    }


    public function harvest2($resumptiontoken = false) {
    
        if ($resumptiontoken) {
            $args = array(
                "resumptiontoken" => $resumptiontoken
            );
        } else {
            $args = array();
        }

        $xml = $this->api->listRecords($args);
        if (!$xml) {
            die('Did not get back records');
        }

        $this->emit("recordsloaded");

        $p = new RecordParser($xml);

		$r = $p->getRecords();

		//var_dump($r);
		/* jc 
	
		drop table if exists rijksmuseum_index;
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
	
		$dbhost = "localhost";
		$username = "root";
		$password = "glimworm";
		$database = "muse";
	
		for ($i=0; $i < count($r); $i++) {
	
			$item = $r[$i];
//			var_dump($item);
		
			$s = "insert into rijksmuseum_index2 (id,identifier,language,publisher,rights,dat,description,creator,coverage,type,title,subject,image,link) values (0 ";
			$s = $s . "," . Q($item['identifier']);
			$s = $s . "," . Q($item['language']);
			$s = $s . "," . Q($item['publisher']);
			$s = $s . "," . Q($item['rights']);
			$s = $s . "," . Q($item['date']);
			$s = $s . "," . Q($item['description']);
			$s = $s . "," . Q($item['creator']);
			$s = $s . "," . Q($item['coverage']);
			$s = $s . "," . Q($item['type']);
			$s = $s . "," . Q($item['title']);
			$s = $s . "," . Q($item['subject']);
			$s = $s . "," . Q($item['formats'][0]);
			$s = $s . "," . Q($item['formats'][1]);
			$s = $s . ");";
		
			echo "record $i\n$s\n";
		
			$db = mysql_connect($dbhost,$username,$password);
            mysql_select_db($database) or die("Unable to select database");
            mysql_query("SET NAMES utf8", $db);
            mysql_query( "SET CHARACTER SET utf8", $db );
            $result=mysql_query($s);
		}
//	exit;


        $this->requestNr++;
//   	file_put_contents("./output/" . $this->requestNr . ".json", $p->asJson());		// this is the line

        $this->emit("datawritten", $this->requestNr);

        $rt = $p->getResumptionToken();
        
        if ($rt) {
//            $this->harvest($rt);
            return $rt;
        } else {
//            die("No resumptiontoken!");
        	return false;
        }
        
    }
}
