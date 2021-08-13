<?php
$_API_KEY = 'ebbf39c49d3838a570888b4c4cd52461';
$_NGROK_URL = 'https://be946fb490c5.ngrok.io';
$shop = $_GET['shop'];
$scopes = 'read_products,write_products,read_orders,write_orders,read_script_tags,write_script_tags';
$redirect_uri = $_NGROK_URL . '/elana/token.php';
$nonce = bin2hex( random_bytes( 12 ) );
$access_mode = 'per-user';

$oauth_url = 'https://' . $shop . '/admin/oauth/authorize?client_id=' . $_API_KEY . '&scope=' . $scopes . '&redirect_uri=' . urlencode($redirect_uri) . '&state=' . $nonce . '&grant_options[]=' . $access_mode;

header("Location: " . $oauth_url);
exit();