<?php

require('autoload.php');
use UnitedPrototype\GoogleAnalytics;

// Initilize GA Tracker
//$tracker = new GoogleAnalytics\Tracker('UA-36359870-2', 'onepayroll.cp1.glimworm.net');
$tracker = new GoogleAnalytics\Tracker('UA-43952072-1', 'glimworm.net');

date_default_timezone_set('Europe/Berlin');

// Assemble Visitor information
$visitor = new GoogleAnalytics\Visitor();
$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
$visitor->setScreenResolution('1024x768');

// Assemble Session information
$session = new GoogleAnalytics\Session();

// Assemble Page information
$page = new GoogleAnalytics\Page("/index.php");
$page->setTitle('My Page from php');

$n = microtime();

$item = new GoogleAnalytics\Item();
$item->setOrderId(n);
$item->setSku('AppTest');
$item->setName('AppTest - from php');
$item->setVariation('No action really, just a test');
$item->setPrice('0');
$item->setQuantity('1');

$transact = new GoogleAnalytics\Transaction();
$transact->setOrderId(n);
$transact->setAffiliation('AppTest');
$transact->setTotal('100');
$transact->setTax('1');
$transact->setShipping('5');
$transact->setCity('Amsterdam');
$transact->setRegion('Noord Holland');
$transact->setCountry('Nederland');
$transact->addItem($item);

$event = new GoogleAnalytics\Event("cat 1", "Action1","label1",0);


// Track page view
$tracker->trackPageview($page, $session, $visitor);
$tracker->trackTransaction($transact, $session, $visitor);
$tracker->trackEvent($event, $session, $visitor);
?> 
