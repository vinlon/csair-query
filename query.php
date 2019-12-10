<?php

require 'vendor/autoload.php';

date_default_timezone_set('Asia/Shanghai');

$httpClient = new \GuzzleHttp\Client();

$ticket = "6be308e7c6c0ff97bc8d631cb5c0f265";

$url = "https://b2c.csair.com/ita/rest/intl/shop/calendar?execution=$ticket";

var_dump($url);

$response = (string)$httpClient->request("GET", $url)->getBody();

$result = json_decode($response, true);

$fileName = __DIR__ . "/result.csv";
$fp = fopen($fileName, "a");

$prices = [];
$time = date('Y-m-d H:i:s');
$rows = $result["ita"]["calendar"]['day'] ?? [];
if (empty($rows)) {
	echo "invalid response $response" . PHP_EOL;
}
foreach($rows as $row) {
	$date = $row['date'];
	$price = $row['solution']['displayTotal']['amount'];
	$prices[$date] = $price;
}
fputcsv($fp, array_merge([$time], array_values($prices)));

fclose($fp);