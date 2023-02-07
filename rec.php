<?php 
 $rarr = array();
$varr = array();
 $idp = $_REQUEST['id'];
$domain_shop = $_REQUEST['shops'];
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

$sql  = "SELECT * FROM `shopify_recommend` WHERE shopname = '".$domain_shop."' ";
$result = mysqli_query($conn,$sql);
 $row = mysqli_fetch_assoc($result);
$aceess_token = $row['token'];
 $curl_shop = curl_init();
 curl_setopt_array($curl_shop, array(
  CURLOPT_URL => 'https://'.$domain_shop.'/admin/api/2022-10/shop.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'X-Shopify-Access-Token: '.$aceess_token),
));

$response = curl_exec($curl_shop);

curl_close($curl_shop);

$res_shop = json_decode($response);

$id = $res_shop->shop->id;
$name = $res_shop->shop->name;
$domain = $res_shop->shop->domain;
$province = $res_shop->shop->province;
$country = $res_shop->shop->country;
$address1 = $res_shop->shop->address1;
$customer_email = $res_shop->shop->customer_email;
$city = $res_shop->shop->city;
$zip = $res_shop->shop->zip;
$shop_owner = $res_shop->shop->shop_owner;
$nameArr = explode("@",$customer_email);
$firstname = $nameArr[0];
$lastname = $nameArr[0];
$phone = $res_shop->shop->phone;

    

$curl_email = curl_init();

curl_setopt_array($curl_email, array(
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/GetCustomerGuidByContactEmailFunction/?contactEmail='.$customer_email,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Darms-RecommendIt-Apim: d5b11ad7967441c9b6997edb4dffd71b'
  ),  
));

$response_email = curl_exec($curl_email);

curl_close($curl_email);
$customerId =json_decode($response_email); 
//$customerId = "58895a2f-a3e2-4c10-8661-2770ac781b10";



$curl_site = curl_init();

curl_setopt_array($curl_site, array(
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/GetSiteGuidByCustomerGuidFunction/?customerGuid='.$customerId,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Darms-RecommendIt-Apim: d5b11ad7967441c9b6997edb4dffd71b'
  ),
));

$response_site = curl_exec($curl_site);

curl_close($curl_site);
$site_guid = json_decode($response_site);

    


if($site_guid && $customerId){
 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://'.$domain_shop.'/admin/api/2022-10/products/'.$idp.'.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'X-Shopify-Access-Token: '.$aceess_token
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$res = json_decode($response);
$row = $res->product;
$title = $row->title;
$body_html = $row->body_html;
$itemId = $row->id;
$variants = $row->variants;
foreach($variants as $rvar){
    $vid = $rvar->id;
    $vtitle = $rvar->title;
    $vdesc = $rvar->title;
    $varr = array("Name"=>"$vtitle","Description"=>"$vtitle","VariantID"=> "$vid");
}
$rarr=array("CustomerId"=>$customerId,"SiteGuid"=>$site_guid,"Items"=>array(array("Name"=>"$title","Description"=>"$title","ItemID"=> "$itemId","CanRecommend"=>true,"Variants"=>array($varr))));
 $json_data = json_encode($rarr);

if($json_data){
    $curl2 = curl_init();

curl_setopt_array($curl2, array(
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/AddProducts',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$json_data,
  CURLOPT_HTTPHEADER => array(
    'Darms-RecommendIt-Apim: d5b11ad7967441c9b6997edb4dffd71b',
    'Content-Type: application/json'
  ),
));

$response2 = curl_exec($curl2);

curl_close($curl2);
//echo $response2;

 echo "<script>window.location = 'index.php?shop=".$domain_shop."';</script>";   

}
}
?>