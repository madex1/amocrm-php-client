<?php
header('Content-type:text/html; charset=utf-8;');
include "../amo/amo.php";

$amo = new Amo();
//$amo->subdomain = 'smartcitytaxi';

$jres = $amo->login();

//$res = json_decode($jres, true);
$res = $amo->acc_info();

echo "<pre>";
//print_r($res);
echo "</pre>";
