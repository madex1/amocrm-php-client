<?php
header('Content-type:text/html; charset=utf-8;');
include "../amo/amo.php";

$subdomain = "";
$login = "";
$hash = "";

$amo = new Amo($subdomain, $login, $hash);

$jres = $amo->login();

$res = $amo->acc_info();

echo "<pre>";
print_r($res);
echo "</pre>";
