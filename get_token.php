<?php 
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
 
$servername = "localhost";
$username = "open_table";
$password = "open_table@123";
$dbname = "open_table";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 $domain_shop = $_GET['shop'];
 
 $sql  = "SELECT * FROM `shopify_recommend` WHERE shopname = '".$domain_shop."' ";
$result = mysqli_query($conn,$sql);
 $row = mysqli_fetch_assoc($result);
 echo $aceess_token = $row['token'];
?>