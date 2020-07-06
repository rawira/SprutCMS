<?php

$domain = 'http://domain.com';

$data = array(
	'token' => 'ksf82x9xb9bwx2q',
	'action' => 'delete',
	'externalID' => 'B07TWSWH8Z',
);

$data_string = json_encode($data);

$result = file_get_contents($domain . '/api.php', null, stream_context_create(array(
	'http' => array(
		'method' => 'POST',
		'header' => 'Content-Type: application/json' . "\r\n"
		. 'Content-Length: ' . strlen($data_string) . "\r\n",
		'content' => $data_string,
	),
)));

echo $result;

?>
