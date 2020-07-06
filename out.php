<?php

error_reporting(0);

require_once 'config.php';
require_once 'core/system.php';

$url = urlencode(Request::getValue('to'));
$aff = 'https://trk.timeat.com.ua/6R1BB4?sub_id_9=pinshop&sub_id_10=';
$utm = UTM::getUTM();
$redirectURL = $aff . $url . $utm;

header('location:' . $redirectURL);
exit;
?>