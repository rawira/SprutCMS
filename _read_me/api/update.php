<?php

$domain = 'http://domain.com';

$data = array(
	'token' => 'ksf82x9xb9bwx2q',
	'action' => 'update',
	'externalID' => '1875671',
	'categoryTop' => 'Phones',
	'categoryProduct' => 'Smartphones',
	'vendor' => 'Apple',
	'name' => 'iPhone 12 Pro Midnight Black 512Gb',
	'url' => 'https://apple.com',
	'pictureURL' => 'https://i.allo.ua/media/catalog/product/cache/1/image/425x295/799896e5c6c37e11608b9f8e1d047d15/i/p/iphone_11_b_2_2.jpg',
	'price' => '32757',
	'currency' => 'грн',
	'description' => 'IPHONE 12
ТЕХНОЛОГИИ БУДУЩЕГО ДОСТУПНЫ УЖЕ СЕГОДНЯ
Элегантный дизайн
Прочный корпус из цельнолистового стекла и алюминия
Инновационная система камер
Безрамочный дисплей Liquid Retina HD
Надежная защита от воды
Рекордное время автономной работы
Уникальный процессор A13 Bionic
Новый уровень защиты данных',
	'isActive' => '1',
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
