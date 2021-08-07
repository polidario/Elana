<?php
include_once("includes/mysql_connect.php");
include_once("includes/shopify.php");

$shopify = new Shopify();
$parameters = $_GET;

include_once("includes/check_token.php");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['product_title']) && isset($_POST['product_body_html']) && $_POST['action_type'] == 'create_product') {
        $product_data = array(
            "product" => array(
                "title" => $_POST['product_title'],
                "body_html" => $_POST['product_body_html'],
                "metafields" => [
                    [
                        "namespace" => "global",
                        "key" => "example_metafield",
                        "value" => "This is a metafield value",
                        "value_type" => "string"
                    ]
                ]
            )
        );

        $create_product = $shopify->rest_api('/admin/api/2021-04/products.json', $product_data, 'POST');
        $create_product = json_decode($create_product['body'], true);

        echo print_r($create_product);
    }

    if(isset($_POST['delete_id']) && $_POST['action_type'] == 'delete') {
        $delete = $shopify->rest_api('/admin/api/2021-04/products/' . $_POST['delete_id'] . '.json', array(), 'DELETE');
        $delete = json_decode($delete['body'], true);
    }

    if(isset($_POST['update_id']) && $_POST['action_type'] == 'update') {

        $getID = explode('/', $_POST['update_id']);

        $update_data = array(
            "product" => array(
                "id" => $_POST['update_id'],
                "title" => $_POST['update_name']
            )
        );

        $update = $shopify->rest_api('/admin/api/2021-04/products/' . end($getID) . '.json', $update_data, 'PUT');
        $update = json_decode($update['body'], true);

        echo print_r($update);
    }
    
}

// $products = $shopify->rest_api('/admin/api/2021-04/products.json', array(), 'GET');
// $products = json_decode($products['body'], true);
$gql_query = array("query" => "{
    products(first: 10) {
      edges {
        node {
          id
          title
          images(first: 1) {
            edges {
              node {
                originalSrc
              }
            }
          }
          status
        }
      }
    }
  }");
$products = $shopify->graphql($gql_query);
$products = json_decode($products['body'], true);

$products_edges = $products['data']['products'];

?>

<?php include_once("header.php"); ?>

<section>
    <aside>
        <h2>Create new product</h2>
        <p>Fill out the following form and click the submit button to create a new product</p>
    </aside>
    <article>
        <div class="card">
            <form action="" method="post">
                <input type="hidden" name="action_type" value="create_product">
                <div class="row">
                    <label for="productTitle">Title</label>
                    <input type="text" name="product_title" id="productTitle">
                </div>

                <div class="row">
                    <label for="productDescription">Description</label>
                    <textarea name="product_body_html" id="productDescription"></textarea>
                </div>

                <div class="row">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </article>
</section>

<section>
    <table>
        <thead>
            <tr>
                <th colspan="2">Product</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($products_edges as $edge) {
                    foreach($edge as $node) {
                        foreach($node as $key => $value) {
                            $image = count($value['images']['edges']) > 0 ? $value['images']['edges'][0]['node']['originalSrc'] : "";
                            ?>
                                <tr>
                                    <td><img width="35" height="35" src="<?php echo $image; ?>"></td>
                                    <td>
                                        <form action="" method="post" class="row side-elements">
                                            <input type="hidden" name="update_id" value="<?php echo $value['id']; ?>">
                                            <input type="text" name="update_name" value="<?php echo $value['title'] ?>">
                                            <input type="hidden" name="action_type" value="update">
                                            <button type="submit" class="secondary icon-checkmark"></button>
                                        </form>
                                    </td>
                                    <td><?php echo $value['status'] ?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="delete_id" value="<?php echo $value['id']; ?>">
                                            <input type="hidden" name="action_type" value="delete">
                                            <button type="submit" class="secondary icon-trash"></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                        }
                    }
                }
            ?>
        </tbody>
    </table>
</section>

<?php include_once("footer.php"); ?>