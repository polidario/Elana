<?php
include_once("includes/shopify.php");
include_once("includes/mysql_connect.php");

$shopify = new Shopify();

$data = json_decode(file_get_contents("php://input"), true);

$shopify->set_url($data['shop']);

$query = "SELECT * FROM shops WHERE shop_url='" . $data['shop'] . "' LIMIT 1";
$result = $mysql->query($query);

if( $result->num_rows < 1 ) {
    echo "There's no Shop: " . $data['shop'] . " in our database";
    return;
}

$store_data = $result->fetch_assoc();

$shopify->set_token($store_data['access_token']);

$gql = $shopify->graphql(array('query' => $data['query']));
$gql = json_decode($gql['body'], true);

echo json_encode($gql);