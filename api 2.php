<?php

require_once 'config.php';
require_once 'core/system.php';

error_reporting(E_ERROR);

$json = file_get_contents('php://input');
$obj = json_decode($json, 1);

$result = array();

// Error JSON
if (!$obj || empty($obj)) {
	$result['status'] = 'Error';
	$result['info'] = 'JSON Error';
	die(json_encode($result));
}

// Access denied
if ($obj['token'] !== API_TOKEN) {
	$result['status'] = 'Error';
	$result['info'] = 'Forbidden';
	die(json_encode($result));
}

/*
externalID
categoryTop
categoryProduct
vendor
name
url
pictureURL
price
currency
description
isActive
 */

$db = new Database(DB_NAME, DB_USER, DB_PASSWORD, DB_HOST);

if (!$db) {
	$result['status'] = 'Error';
	$result['info'] = '[1] DB Error';
	die(json_encode($result));
}

if (isset($obj['description'])) {
	$obj['description'] = addslashes($obj['description']);
}

if (isset($obj['name'])) {
	$obj['name'] = addslashes($obj['name']);
}

if (strtolower($obj['action']) == 'insert') {

// Check category and insert if not exists
	$sql = "INSERT IGNORE INTO categories (name) VALUES('{$obj['categoryTop']}')";

	if (!$db->query($sql)) {
		$result['status'] = 'Error';
		$result['info'] = '[2] DB Error';
		die(json_encode($result));
	}

	$sql = "INSERT IGNORE INTO categories (name) VALUES('{$obj['categoryProduct']}')";

	if (!$db->query($sql)) {
		$result['status'] = 'Error';
		$result['info'] = '[3] DB Error';
		die(json_encode($result));
	}

	if (!empty($obj['categoryTop'])) {
		$sql = "SELECT ID FROM categories WHERE name='{$obj['categoryTop']}'";
		$datarow = $db->query($sql)->result();

		$catID = $datarow[0]->ID;

		if ($catID > 0) {
			$sql = "UPDATE categories SET parentID = '{$catID}' WHERE name='{$obj['categoryProduct']}'";
			$db->query($sql);
		}
	}

// Insert Goods
	// Get Category ID
	$sql = "SELECT ID FROM categories WHERE name = '{$obj['categoryProduct']}'";

	$datarow = $db->query($sql)->result();

	$catID = $datarow[0]->ID;

	if ($catID <= 0) {
		$result['status'] = 'Error';
		$result['info'] = $obj['categoryProduct'] . ' not Found!';
		die(json_encode($result));
	}

	$sql = <<<H1
INSERT INTO goods (
    externalID,
    categoryID,
    vendor,
    name,
    url,
    pictureURL,
    price,
    currency,
    description,
    isActive
) VALUES (
    '{$obj['externalID']}',
    '{$catID}',
    '{$obj['vendor']}',
    '{$obj['name']}',
    '{$obj['url']}',
    '{$obj['pictureURL']}',
    '{$obj['price']}',
    '{$obj['currency']}',
    '{$obj['description']}',
    '{$obj['isActive']}'
)
ON DUPLICATE KEY UPDATE price = '{$obj['price']}', isActive = '{$obj['isActive']}';
H1;

	if (!$db->query($sql)) {
		$result['status'] = 'Error';
		$result['info'] = '[5] DB Error';
		die(json_encode($result));
	}

} else if (strtolower($obj['action']) == 'delete') {
	$eID = $obj['externalID'];

	if (!empty($eID)) {
		$sql = "DELETE FROM goods WHERE externalID = '{$eID}'";
		$db->query($sql);
	} else {
		$result['status'] = 'Error';
		$result['info'] = 'Error: externalID';
		die(json_encode($result));
	}
} else if (strtolower($obj['action']) == 'update') {

// Check category and insert if not exists
	$sql = "INSERT IGNORE INTO categories (name) VALUES('{$obj['categoryTop']}')";

	if (!$db->query($sql)) {
		$result['status'] = 'Error';
		$result['info'] = '[2] DB Error';
		die(json_encode($result));
	}

	$sql = "INSERT IGNORE INTO categories (name) VALUES('{$obj['categoryProduct']}')";

	if (!$db->query($sql)) {
		$result['status'] = 'Error';
		$result['info'] = '[3] DB Error';
		die(json_encode($result));
	}

	if (!empty($obj['categoryTop'])) {
		$sql = "SELECT ID FROM categories WHERE name='{$obj['categoryTop']}'";
		$datarow = $db->query($sql)->result();

		$catID = $datarow[0]->ID;

		if ($catID > 0) {
			$sql = "UPDATE categories SET parentID = '{$catID}' WHERE name='{$obj['categoryProduct']}'";
			$db->query($sql);
		}
	}

// Insert Goods
	// Get Category ID
	$sql = "SELECT ID FROM categories WHERE name = '{$obj['categoryProduct']}'";

	$datarow = $db->query($sql)->result();

	$catID = $datarow[0]->ID;

	if ($catID <= 0) {
		$result['status'] = 'Error';
		$result['info'] = $obj['categoryProduct'] . ' not Found!';
		die(json_encode($result));
	}

	$sql = <<<H1
INSERT INTO goods (
    externalID,
    categoryID,
    vendor,
    name,
    url,
    pictureURL,
    price,
    currency,
    description,
    isActive
) VALUES (
    '{$obj['externalID']}',
    '{$catID}',
    '{$obj['vendor']}',
    '{$obj['name']}',
    '{$obj['url']}',
    '{$obj['pictureURL']}',
    '{$obj['price']}',
    '{$obj['currency']}',
    '{$obj['description']}',
    '{$obj['isActive']}'
)
ON DUPLICATE KEY UPDATE
price = '{$obj['price']}',
categoryID = '{$catID}',
vendor = '{$obj['vendor']}',
name = '{$obj['name']}',
url = '{$obj['url']}',
pictureURL = '{$obj['pictureURL']}',
price = '{$obj['price']}',
currency = '{$obj['currency']}',
description = '{$obj['description']}',
isActive = '{$obj['isActive']}';
H1;

	if (!$db->query($sql)) {
		$result['status'] = 'Error';
		$result['info'] = 'Update Error';
		die(json_encode($result));
	}

}

// All OK
$result['status'] = 'Ok';
$result['info'] = 'Ok';
die(json_encode($result));

?>