<?php
$servername = "localhost";
$username = "open_table";
$password = "open_table@123";
$dbname = "open_table";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {  die("Connection failed: " . mysqli_connect_error());}
//require_once("inc/functions.php");

//$api_key = "90385822bce3940ae2836d9163da1af0";

$api_key = "6b1c0de26bf5278e5d1b17e5d301302f";

//$shared_secret = "ce7cb76c9b6130f2750bd3546afa8492";
$shared_secret = "ea7875bac1af3618e315339177170bd2";

$params = $_GET;

$hmac = $_GET['hmac'];

$params = array_diff_key($params, array('hmac' => ""));

ksort($params);

$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

if (hash_equals($hmac, $computed_hmac)) {

                $query = array(

                                "client_id" => $api_key,

                                "client_secret" => $shared_secret,

                                "code" => $params['code']

                );

                $access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                curl_setopt($ch, CURLOPT_URL, $access_token_url);

                curl_setopt($ch, CURLOPT_POST, count($query));

                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));

                $result = curl_exec($ch);

                curl_close($ch);

                $result = json_decode($result, true);

                $access_token = $result['access_token'];

                echo $access_token;							
				
				$sql = "INSERT INTO `shopify_recommend` (`shopname`, `shop_domain`, `token`, `create_date`, `modified_date`) VALUES ('".$params['shop']."', '".$params['shop']."', '".$access_token."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."');";
				if ($conn->query($sql) === TRUE) {  
				echo "New record created successfully";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
					}				

} else {

                die('This request is NOT from Shopify!');

}