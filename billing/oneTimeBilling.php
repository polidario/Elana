<?php

if(isset($_GET['charge_id'])) {
    $query = array(
        "query" => '{
            node(id: "gid://shopify/AppPurchaseOneTime/' . $_GET['charge_id'] . '") {
                ... on AppPurchaseOneTime {
                    status
                }
            }
        }'
    );

    $check_charge = $shopify->graphql($query);
    $check_charge = json_decode($check_charge['body'], TRUE);

    if(!empty($check_charge['data']['node'])) {
        if($check_charge['data']['node']['status'] != 'ACTIVE') {
            echo "Oh! It looks like you haven't paid our Shopify app yet. So we can't allow you to use the app. Apologize";
            die;
        }
    } else {
        echo "Woah! Looks like you're trying to create your own charge ID. You cannot do that.";
        die;
    }
} else {
    $query = array("query" => 'mutation {
        appPurchaseOneTimeCreate(
            name: "Elana One-Time Charge"
            price: { amount: 19.99 currencyCode: USD }
            test: true
            returnUrl: "https://' . $shopify->get_url() . '/admin/apps/elana"
            ) {
                appPurchaseOneTime {
                    id
                }
                confirmationUrl
            }
        }');
    
    $charge = $shopify->graphql($query);
    $charge = json_decode($charge['body'], TRUE);
    
    echo "<script>top.window.location = '" . $charge['data']['appPurchaseOneTimeCreate']['confirmationUrl'] . "'</script>";
    die;
}
