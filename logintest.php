<?php 
header('Content-Type: application/json');
require_once "autoload.php";


$apiURL = $_GET["apiURL"];
$username = $_GET["username"];
$password = $_GET["password"];
$accountId = $_GET["accountId"];
$appkey = $_GET["appkey"];

$client = new CalcFusionClient($apiURL);
$output = $client->login($username, $password, $accountId, $appkey);
echo $output;

?>