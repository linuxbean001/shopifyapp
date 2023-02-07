 <?php 
 $domain_shop = $_GET['shop'];
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
 
 /*********check script**************/
 
 $curl_tag = curl_init();

curl_setopt_array($curl_tag, array(
  CURLOPT_URL => 'https://'.$domain_shop.'/admin/api/2022-10/script_tags.json',
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

$response_tag = curl_exec($curl_tag);

curl_close($curl_tag);

$res_tag = json_decode($response_tag);
//print_r($res_tag);
if(!$res_tag->script_tags){
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://'.$domain_shop.'/admin/api/2022-10/script_tags.json',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{"script_tag":{"event":"onload","src":"https://shopify.elancethemes.com/product-recommendation.js"}}',
  CURLOPT_HTTPHEADER => array(
    'X-Shopify-Access-Token: '.$aceess_token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;

} /*********************/
 
 
 
 
 $curl = curl_init();

 curl_setopt_array($curl, array(
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

$response = curl_exec($curl);

curl_close($curl);

$res = json_decode($response);
//print_r($res);
$id = $res->shop->id;
$name = $res->shop->name;
$domain = $res->shop->domain;
$province = $res->shop->province;
$country = $res->shop->country;
$address1 = $res->shop->address1;
$customer_email = $res->shop->customer_email;
$city = $res->shop->city;
$zip = $res->shop->zip;
$shop_owner = $res->shop->shop_owner;
$nameArr = explode("@",$customer_email);
$firstname = $nameArr[0];
$lastname = $nameArr[0];
$phone = $res->shop->phone;

//if($customer_email){
    

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


if($customerId){

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
//echo $site_guid;
    
}
//}
else{
  /*Register new customer*/  
    $curl2 = curl_init();

curl_setopt_array($curl2, array( 
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/RegisterCustomer',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
 "CustomerId":"00000000-0000-0000-0000-000000000000",
 "LastName":"'.$lastname.'",
 "FirstName":"'.$firstname.'",
 "CompanyName":"'.$shop_owner.'",
 "Address":"'.$address1.'",
 "City":"'.$city.'",
 "State":"'.$province.'",
 "PostalCode":"'.$zip.'",
 "ContactEmail":"'.$customer_email.'",
 "PhoneNumber":"'.$phone.'"
}',
  CURLOPT_HTTPHEADER => array(
    'Darms-RecommendIt-Apim: d5b11ad7967441c9b6997edb4dffd71b',
    'Content-Type: application/json'
  ),
));

$response2 = curl_exec($curl2);

curl_close($curl2);
//echo $response2;
$cust_data = json_decode($response2);
//print_r($cust_data);
$customerId = $cust_data->customerId;

/******Add Site**********/
$curl3 = curl_init();

curl_setopt_array($curl3, array( 
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/AddSite',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
 "CustomerId":"'.$customerId.'",
 "SiteGuid":"00000000-0000-0000-0000-000000000000",
 "Name":"'.$name.'", 
 "Url":"'.$domain.'"
}',
  CURLOPT_HTTPHEADER => array(
    'Darms-RecommendIt-Apim: d5b11ad7967441c9b6997edb4dffd71b',
    'Content-Type: application/json'
  ),
));

$response3 = curl_exec($curl3);

curl_close($curl3);

$site_data = json_decode($response3);
$site_guid = $site_data->siteGuid;
/**********update site guid*************/
$query = "UPDATE shopify_recommend SET shopGuid='".$site_guid."' WHERE token='".$aceess_token."'";
if ($conn->query($query) === TRUE) {  
				echo "&nbsp;";
				} else {
					echo "Error: " . $query . "<br>" . $conn->error;
					}	
}
 
function getapimProducts($customerId,$site_guid){
    $garr = array();
    $curl_g = curl_init();

curl_setopt_array($curl_g, array(
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/GetProducts/?customerGuid='.$customerId.'&siteGuid='.$site_guid ,
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

$response_g = curl_exec($curl_g);

curl_close($curl_g);
//echo $response_g;
$res_g = json_decode($response_g); 
foreach($res_g as $row_g){
    $itemId = $row_g->itemId;
    $garr[] =  $itemId; 
}

return $garr;
} 

if($site_guid && $customerId){
$rarr = array();
$varr = array();
 
 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://'.$domain_shop.'/admin/api/2022-10/products.json',
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

$prods  = $res->products;

/**************Get recommendations**********************/
 $curl_rec = curl_init();

curl_setopt_array($curl_rec, array(
  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/Recommendations',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
 "CustomerId": "'.$customerId.'",
 "SiteGuid": "'.$site_guid.'",
 "NumberOfRecommendations": 9 
}',
  CURLOPT_HTTPHEADER => array(
    'Darms-RecommendIt-Apim: d5b11ad7967441c9b6997edb4dffd71b',
    'Content-Type: application/json'
  ),
));

$response_rec = curl_exec($curl_rec);

curl_close($curl_rec);
//echo $response_rec;

$result_re = json_decode($response_rec);
$arr_re = array();

foreach($result_re->recommendations as $rec){
    
   $itemID = $rec->itemID;
    $variantID = $rec->variantID;
    $arr_re[]= $itemID;
}




$gprods = getapimProducts($customerId,$site_guid);

if(isset($_POST['submit'])){
   
    foreach($res->products as $row){
        if (in_array($row->id, $gprods)){
            //echo "Already exists".$row->id;
            $title = $row->title;
            $body_html = $row->body_html;
            $itemId = $row->id;
            $variants = $row->variants;
            foreach($variants as $rvar){
                $vid = $rvar->id;
                $vtitle = $rvar->title;
                $vdesc = $rvar->title;
                $varr = array("Name"=>"$vtitle","Description"=>"$vdesc","VariantID"=> "$vid");
            }
            $rarr=array("CustomerId"=>"'.$customerId.'","SiteGuid"=>"'.$site_guid .'","Items"=>array(array("Name"=>"$title","Description"=>"$body_html","ItemID"=> "$itemId","CanRecommend"=>true,"Variants"=>array($varr))));
            $json_data = json_encode($rarr);
            //echo $json_data;
            if($json_data){
                $curl2 = curl_init();
            
                curl_setopt_array($curl2, array(
                  CURLOPT_URL => 'https://aapim-recommendit-eus.azure-api.net/UpdateProducts',
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
        else{
            
            //echo "Not exists".$row->id;
            $title = $row->title;
            $body_html = $row->body_html;
            $itemId = $row->id;
            $variants = $row->variants;
            foreach($variants as $rvar){
                $vid = $rvar->id;
                $vtitle = $rvar->title;
                $vdesc = $rvar->title;
                $varr = array("Name"=>"$vtitle","Description"=>"$vdesc","VariantID"=> "$vid");
            }
            $rarr=array("CustomerId"=>"'.$customerId.'","SiteGuid"=>"'.$site_guid .'","Items"=>array(array("Name"=>"$title","Description"=>"$body_html","ItemID"=> "$itemId","CanRecommend"=>false,"Variants"=>array($varr))));
            $json_data = json_encode($rarr);
            //echo $json_data;
            
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
       
    }
    
}

?>
<h1>RecommendIt....</h1>
<style>
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #6f953f;
  color: white;
}
input#submit{
    
        background: #6f953f;
    color: #fff;
    font-size: 15px;
    border: none;
    padding: 10px;
    box-shadow: 5px 10px;
    cursor: pointer;
}
</style>
<!--<h4> CustomerId:"58895a2f-a3e2-4c10-8661-2770ac781b10"<br>
 SiteGuid:"ee537a37-d4d2-4b74-af0f-945ae9be364e"</h4>-->

 <form method="POST" name="frm1" id="frm1">
     <p><input type="submit" name="submit" id="submit" value="Start Sync"></p>
 </form>
 
 <table cellspacing="5" cellspadding="5" width="80%" border=1 id="customers">
     <tr>
        <th>ID</th>
         <th>Title</th>
         <th>Image</th>
         <th>Price</th>
         <th>Can Recommend</th>
     </tr>
     <?php 
     foreach($prods as $row){
     ?>
     <tr>
        <td><?php echo $row->id;?></td>
         <td><?php echo $row->title;?></td>
         <td><img src="<?php echo $row->image->src;?>" height="80" width="150"></td>
         <td><?php echo "$".$row->variants[0]->price;?></td>
         <td>
         <?php 
             if (in_array($row->id, $arr_re))
              {
                echo "<b>Yes Synced</b><img src='yes.png' height='16' width='16'><br>";
                ?>
                <a href="unrec.php?id=<?php echo $row->id;?>&shops=<?php echo  $domain_shop;?>">UnRecommend Now</a>
                <?php
              }
            else
              {
                  ?>
                   <b>N/A</b><img src="no.png" height="16" width="16"><br>
                   <a href="rec.php?id=<?php echo $row->id;?>&shops=<?php echo  $domain_shop;?>">Recommend & Sync Now</a>
                  <?php
               
              }
        ?>
  </td>
     </tr>
     <?php 
     }
     ?>
 </table>
 <?php }?>