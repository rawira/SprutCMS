<?php

require_once 'config.php';
require_once 'core/system.php';

error_reporting(E_ERROR);

set_time_limit(0);

$links_per_page = _HTML_SITEMAP_LINK_PER_PAGE_;

$page = (int) $_GET['page'];

$start_limit = 0;

if (isset($page) && !empty($page)) {
	if ($page > 0) {
		$start_limit = $page * $links_per_page;
	}
}

$db = new Database(DB_NAME, DB_USER, DB_PASSWORD, DB_HOST);

$dt = $db->query("select count(ID) mID from goods")->result_array();

$pages = ceil($dt[0]['mID'] / $links_per_page);

$siteURL = SITE_URL;
$siteNAME = SITE_NAME;
$YEAR = date("Y", time());

$start_html = <<<H1
<!doctype html>
<html lang="ru" prefix="og: http://ogp.me/ns#">
<head>
    <base href='{$siteURL}' />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Sitemap - {$siteNAME}</title>
    <meta name="description" content="The sitemap includes links to some of our most popular pages.">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{$siteURL}/templates/assets/img/favicon.png">
    <!-- all css here -->
    <link rel="stylesheet" href="/templates/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/templates/assets/css/bundle.css">
    <link rel="stylesheet" href="/templates/assets/css/plugins.css">
    <link rel="stylesheet" href="/templates/assets/css/style.css">
    <link rel="stylesheet" href="/templates/assets/css/external.css">
    <link rel="stylesheet" href="/templates/assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="/templates/assets/js/jsquery.js"></script>
    <script src="/templates/assets/js/vendor/modernizr-2.8.3.min.js"></script>

<style>
.pagination {
  display: inline-block;
}

.pagination a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
  border-radius: 5px;
}

.pagination a:hover:not(.active) {
  background-color: #ddd;
  border-radius: 5px;
}
</style>

</head>
<body>

<!--header area start-->
<div class="header_area">
    <!--header middel-->
    <div class="header_middle middle_two">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="logo">
                        <a href="/"><img src="/templates/assets/img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-9">
                </div>
            </div>
    </div>
</div>

<!--header area end-->
<h3>Sitemap</h3>
H1;

$html = '<hr style="margin:0;padding:0;"><table cellspacing="1" cellpadding="1" align="center" class="pagination"><tr>';

$goods = new Goods();
$goods->setLimit($start_limit, $links_per_page);
$gg = $goods->get();

$i = 1;
foreach ($gg['data'] as $good) {
	$html .= "<td align=\"center\"><a href='" . SITE_URL . '/' . $good['ID'] . '-' . generateCHPU($good['name']) . "/'>" . $good['name'] . "</a></td>";
	if ($i % 5 == 0) {
		$html .= '</tr><tr>';
	}
	$i++;
}

$html .= '</tr></table><hr style="margin:0;padding:0;">';

$html_page = '<hr style="margin:0;padding:0;">Pages:<div class="pagination">';
for ($i = 0; $i < $pages; $i++) {
	$html_page .= "<a href='" . SITE_URL . '/sitemap.php?page=' . $i . "'>" . $i . "</a>";
}

$html_page .= '</div><hr style="margin:0;padding:0;">';

$end_html = <<<H1
</table>
<!--newsletter area start-->
<div class="newsletter_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2 col-md-6">
                <div class="footer_logo">
                    <a href="#"><img src="/templates/assets/img/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-lg-7 offset-3">
                <div class="newslatter_inner fix">
                </div>
            </div>
        </div>
    </div>
</div>
<!--newsletter area end-->

<!--footer area start-->
<div class="footer_area">
    <div class="container">
        <div class="copyright_area">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <div class="widget_copyright">
                        <p>&copy; 2018-{$YEAR} <a href="/">{$siteNAME}</a>. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- all js here -->
<script src="/templates/assets/js/vendor/jquery-1.12.0.min.js"></script>
<script src="/templates/assets/js/plugins.js"></script>
<script src="/templates/assets/js/main.js"></script>
<script src="/templates/assets/js/popper.js"></script>
<script src="/lazysizes.min.js"></script>
<script src="/templates/assets/js/bootstrap.min.js"></script>

<script>
[].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
img.setAttribute('src', img.getAttribute('data-src'));
img.onload = function() {
img.removeAttribute('data-src');
};
});
</script>

</body>
</html>
H1;

$result_html = $start_html . $html . $html_page . $end_html;

die($result_html);

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