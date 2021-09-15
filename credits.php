<?php
include_once("includes/mysql_connect.php");
include_once("includes/shopify.php");

/**
 * ===================================================
 *          CREATE THE VARIABLES: 
 *          - $shopify
 *          - $parameters
 * ===================================================
 */

$shopify = new Shopify();
$parameters = $_GET;

/**
 * ===================================================
 *          CHECKING THE SHOPIFY STORE
 * ===================================================
 */

include_once("includes/check_token.php");

$access_scopes = $shopify->rest_api('/admin/oauth/access_scopes.json', array(), 'GET');
$response = json_decode($access_scopes['body'], true);

$array = json_decode('
                {
                    "application_credit": {
                        "description": "application credit for refund",
                        "amount": 5.0,
                        "test": true
                    }
                }
            ', true);

$credit = $shopify->rest_api('/admin/api/2021-07/application_credits.json', $array, 'POST');
$credit = json_decode($credit['body'], true);

echo print_r($credit);