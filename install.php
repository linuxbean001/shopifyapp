<?php 
$scopes = "read_orders,write_products,read_customers,write_customers,read_files,write_files,read_products,read_product_listings,read_script_tags,write_script_tags,read_themes,write_themes";

$redirect_uri = "https://shopify.elancethemes.com/generate_token.php";

$api_key = "6b1c0de26bf5278e5d1b17e5d301302f";

$shop = $_GET['shop'];

$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

header("Location: " . $install_url);

?>
