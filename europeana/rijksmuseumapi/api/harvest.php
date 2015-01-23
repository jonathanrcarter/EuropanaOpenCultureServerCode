<?php
ini_set("memory_limit","1024M");
error_reporting(-1);

require 'config.php';
require 'class-harvest.php';

$harvest = new Harvest(Config::API_KEY);

$harvest->on("datawritten", function($i) {
    echo "$i.json written\n";
});

$harvest->on("recordsloaded", function() {
    echo "Loaded records\n";
});

echo "\n\nstarting harvest";
$harvest->harvest();
echo "\n\nending harvest";
echo "\n\n";