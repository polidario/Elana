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

/**
 * ===================================================
 *          CREATE A BILLING CHARGE
 * ===================================================
 */

include_once("billing/oneTimeBilling.php");

/**
 * ===================================================
 *       HERE DISPLAY ANYTHING ABOUT THE STORE
 * ===================================================
 */

// $access_scopes = $shopify->rest_api('/admin/oauth/access_scopes.json', array(), 'GET');
// $response = json_decode($access_scopes['body'], true);

$webhook_data = json_decode('
    {
        "webhook": {
            "topic": "products/create",
            "address": "https://dd93-45-91-22-40.ngrok.io/elana/webhook_example.php",
            "format": "json"
        }
    }
', TRUE);

$webhook = $shopify->rest_api('/admin/api/2021-07/webhooks.json', $webhook_data, 'POST');
$response = json_decode($webhook['body'], TRUE);

echo print_r($response);

?>

<?php include_once("header.php"); ?>


    <section>
        <div class="alert columns twelve">
            <dl>
                <dt>
                    <p>Welcome to Elana Shopify app</p>
                </dt>
            </dl>
        </div>
    </section>
    <footer></footer>



<?php include_once("footer.php"); ?>