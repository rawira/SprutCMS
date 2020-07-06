<?php

require_once '../config.php';
require_once '../core/system.php';

error_reporting(E_ERROR);

set_time_limit(0);

$db = new Database(DB_NAME, DB_USER, DB_PASSWORD, DB_HOST);

$url = array();

$goods = new Goods();

$dt = $db->query("select max(ID) mID from goods")->result_array();

$maxID = $dt[0]['mID'];

echo 'Target: ' . ($maxID + 100000) . ' : ';

for ($i = 0; $i <= $maxID + 100000; $i = $i + 100000) {

	echo $i . " ";

	$goods->setLimit($i, 100000);

	$gg = $goods->get();

	foreach ($gg['data'] as $good) {
		$lurl = '/' . $good['ID'] . '-' . generateCHPU($good['name']) . '/';
		array_push($url, $lurl);
	}
}

echo "\nGenerate sitemap...\n";
generateSiteMapXML($url);
echo "Ok\n";

// Generate sitemap.xml
function generateSiteMapXML($prepare_array) {
	include "../lib/SitemapGenerator.php";

	$generator = new \Icamys\SitemapGenerator\SitemapGenerator(SITE_URL);

// will create also compressed (gzipped) sitemap
	$generator->createGZipFile = true;

// determine how many urls should be put into one file
	// according to standard protocol 50000 is maximum value (see http://www.sitemaps.org/protocol.html)
	$generator->maxURLsPerSitemap = 50000;

// sitemap file name
	$generator->sitemapFileName = "sitemap.xml";

// sitemap index file name
	$generator->sitemapIndexFileName = "sitemap.xml";

	foreach ($prepare_array as $url) {

// adding url `loc`, `lastmodified`, `changefreq`, `priority`, `alternates`
		$generator->addUrl($url, new DateTime(), 'weekly', '0.5', array());
	}

// generating internally a sitemap
	$generator->createSitemap();

// writing early generated sitemap to file
	$generator->writeSitemap();
}

// Преобразуем кеворд в WEB-формат
function minusKey($key) {
	$key = str_replace(' ', '-', $key);
	$key = str_replace('+', '-', $key);
	$key = str_replace(':', '-', $key);
	$key = str_replace(';', '-', $key);
	$key = str_replace('"', '', $key);
	$key = str_replace('`', '', $key);
	$key = str_replace('"', '', $key);
	return $key;
}

function generateCHPU($str) {
	$converter = array(
		'а' => 'a', 'б' => 'b', 'в' => 'v',
		'г' => 'g', 'д' => 'd', 'е' => 'e',
		'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
		'и' => 'i', 'й' => 'y', 'к' => 'k',
		'л' => 'l', 'м' => 'm', 'н' => 'n',
		'о' => 'o', 'п' => 'p', 'р' => 'r',
		'с' => 's', 'т' => 't', 'у' => 'u',
		'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
		'ь' => '', 'ы' => 'y', 'ъ' => '',
		'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

		'А' => 'A', 'Б' => 'B', 'В' => 'V',
		'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
		'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
		'И' => 'I', 'Й' => 'Y', 'К' => 'K',
		'Л' => 'L', 'М' => 'M', 'Н' => 'N',
		'О' => 'O', 'П' => 'P', 'Р' => 'R',
		'С' => 'S', 'Т' => 'T', 'У' => 'U',
		'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
		'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
		'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
	);
	$str = strtr($str, $converter);
	$str = strtolower($str);
	$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
	$str = trim($str, "-");
	$str = substr($str, 0, strrpos(substr($str, 0, 40), '-'));
	$str = trim($str, "-");
	return $str;
}
?>