var customerID = __st.cid;

var mshop = shopUrl.split("https://");
$('product-recommendations').html("<h1>RecommendIt....</h1><div id='recommendData' style='display:inline-block'></div>");


	var settings_token = {
  "url": "https://shopify.elancethemes.com/get_token.php?shop="+mshop[1],
  "method": "GET",
  "timeout": 0,
};

$.ajax(settings_token).done(function (response_token) {
  
  var shoptoken = response_token;
  
  

/***********functions start***************/
function getsiteidbydb() {
    var data;
 $.ajax({
        url: "https://shopify.elancethemes.com/get_siteid.php?shop="+mshop[1],
        method: "GET",
        async: false, 
        success: function (resp) {
            data = resp;
          
        },
        error: function () {}
    }); // ajax asynchronus request 
    //the following line wouldn't work, since the function returns immediately
   return data;
}

function registerCustomer(LastName,FirstName,CompanyName,Address,City,State,PostalCode,ContactEmail,PhoneNumber){
    var settings = {
  "url": "https://aapim-recommendit-eus.azure-api.net/RegisterCustomer",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Darms-RecommendIt-Apim": "d5b11ad7967441c9b6997edb4dffd71b",
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({
    "CustomerId": "00000000-0000-0000-0000-000000000000",
    "LastName": ""+LastName+"",
    "FirstName": ""+FirstName+"",
    "CompanyName": ""+CompanyName+"",
    "Address": ""+Address+"",
    "City": ""+City+"",
    "State": ""+State+"",
    "PostalCode": ""+PostalCode+"",
    "ContactEmail": ""+ContactEmail+"",
    "PhoneNumber": ""+PhoneNumber+""
  }),
};

$.ajax(settings).done(function (response) {
  console.log(response);
  //var obj = JSON.parse(response);
  var customerId = response.customerId;
   getSite(customerId);
});
    
}

function addSite(CustomerId,siteId){
    var settings = {
          "url": "https://aapim-recommendit-eus.azure-api.net/AddSite",
          "method": "POST",
          "timeout": 0,
          "headers": {
            "Darms-RecommendIt-Apim": "d5b11ad7967441c9b6997edb4dffd71b",
            "Content-Type": "application/json"
          },
          "data": JSON.stringify({
            "CustomerId": ""+CustomerId+"",
            "SiteGuid": ""+siteId+"",
            "Name": ""+mshop[1]+"",
            "Url": ""+mshop[1]+""
          }),
        };
        
        $.ajax(settings).done(function (response) {
          //console.log(response);
		  recommendData(CustomerId,siteId);
        });

}


function getSite(customerGuid){
    var settings = {
  "url": "https://aapim-recommendit-eus.azure-api.net/GetSiteGuidByCustomerGuidFunction/?customerGuid="+customerGuid,
  "method": "GET",
  "timeout": 0,
  "headers": {
    "Darms-RecommendIt-Apim": "d5b11ad7967441c9b6997edb4dffd71b"
  },
};

$.ajax(settings).done(function (response) {
  console.log(response);
   //var obj = JSON.parse(response);
   if(response == "00000000-0000-0000-0000-000000000000"){
          addSite(customerGuid,getsiteidbydb());
		  //recommendData(customerGuid,"ee537a37-d4d2-4b74-af0f-945ae9be364e");
   }
   else{
	   /**********Show recommendations*************/
	   //alert();
          //addSite(customerGuid,"ee537a37-d4d2-4b74-af0f-945ae9be364e");
		  recommendData(customerGuid,getsiteidbydb());
   }
});
    
}

function recommendData(CustomerId,SiteGuid){
	
	var settings = {
  "url": "https://aapim-recommendit-eus.azure-api.net/Recommendations",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Darms-RecommendIt-Apim": "d5b11ad7967441c9b6997edb4dffd71b",
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({
    "CustomerId": ""+CustomerId+"",
    "SiteGuid": ""+SiteGuid+"",
    "NumberOfRecommendations": 9
  }),
};

$.ajax(settings).done(function (response) {
  //console.log(response);
  var recommendations = response.recommendations;
  for (var i = 0; i < recommendations.length; i++) {
      var productId = recommendations[i].itemID;
       //getProductData(productId,marr);
       var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    
    xhr.addEventListener("readystatechange", function() {
      if(this.readyState === 4) {
        var obj = JSON.parse(this.responseText);
        var ptitle  = obj.product.title;
        var pimg    = obj.product.image.src;
        var vprice  = obj.product.variants[0].price;
        var vtitle  = obj.product.variants[0].title;
        var phandle  = obj.product.handle;
       $('#recommendData').append('<div class="column" style="float: left;width: 25%; padding: 10px;"><a href="/products/'+phandle+'" style="text-decoration:none;"><img src="'+pimg+'" width="250" height="150"><h2>'+ptitle+'</h2><p>$'+vprice+'</p></a></div>');
        
      }
    });
    //$('product-recommendations').append("<div style='clear:both'></div>");
    xhr.open("GET", ""+shopUrl+"/admin/api/2022-10/products/"+productId+".json");
    xhr.setRequestHeader("X-Shopify-Access-Token", ""+shoptoken+"");
    xhr.send();
       
}

});
}
/**********functions end ***************/


if(customerID){
  console.log("customerId : ",customerID);
var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function() {
  if(this.readyState === 4) {
    var obj_user = JSON.parse(this.responseText);
    console.log(obj_user);
    var LastName = obj_user.customer.last_name;
    var FirstName = obj_user.customer.first_name;
    var CompanyName = obj_user.customer.addresses[0].company;
    var Address = obj_user.customer.addresses[0].address1;
    var City = obj_user.customer.addresses[0].city;
    var State = obj_user.customer.addresses[0].province;
    var PostalCode = obj_user.customer.addresses[0].zip;
    var ContactEmail = obj_user.customer.email;
    var PhoneNumber = obj_user.customer.addresses[0].phone;
    
    // WARNING: For GET requests, body is set to null by browsers.

       var settings = {
              "url": "https://aapim-recommendit-eus.azure-api.net/GetCustomerGuidByContactEmailFunction?contactEmail="+ContactEmail+"",
              "method": "GET",
              "timeout": 0,
              "headers": {
                "Darms-RecommendIt-Apim": "d5b11ad7967441c9b6997edb4dffd71b"
              },
            };
            
            $.ajax(settings).done(function (response) {
				/***********Already a customer*************/
				 //var obj = JSON.parse(response);
				getSite(response);
              //console.log("ANkitk",response);
            }).fail(function() {
              registerCustomer(LastName,FirstName,CompanyName,Address,City,State,PostalCode,ContactEmail,PhoneNumber);
              
        });
    }
});

xhr.open("GET", ""+shopUrl+"/admin/api/2022-10/customers/"+customerID+".json");
xhr.setRequestHeader("X-Shopify-Access-Token", ""+shoptoken+"");
xhr.send();
    
}else{


  var farr2 = [];
  var marr = [];
  var tarr = [];
function getProductData(pid, farr2){
    //var farr2 = [];
    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    
    xhr.addEventListener("readystatechange", function() {
      if(this.readyState === 4) {
        var obj = JSON.parse(this.responseText);
        var ptitle  = obj.product.title;
        var pimg    = obj.product.image.src;
        var vprice  = obj.product.variants[0].price;
        var vtitle  = obj.product.variants[0].title;
         
        //arr.push({ptitle:ptitle,pimg:pimg,vprice:vprice,vtitle:vtitle});
         farr2.push({ptitle:ptitle,pimg:pimg,vprice:vprice,vtitle:vtitle});
      }
    });
    
    xhr.open("GET", ""+shopUrl+"/admin/api/2022-10/products/"+pid+".json");
    xhr.setRequestHeader("X-Shopify-Access-Token", ""+shoptoken+"");
    xhr.send();
}             
 /*************Testing recommendations*******************/ 


 
var settings = {
  "url": "https://aapim-recommendit-eus.azure-api.net/Recommendations",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Darms-RecommendIt-Apim": "d5b11ad7967441c9b6997edb4dffd71b",
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({
    "CustomerId": "dca58b69-d06f-4ee6-89b9-96c87c999ba8",
    "SiteGuid": "5ce20f8b-b834-4d2f-bf45-52fcbea7bf27",
    "NumberOfRecommendations": 9
  }),
};

$.ajax(settings).done(function (response) {
  //console.log(response);
  var recommendations = response.recommendations;
  for (var i = 0; i < recommendations.length; i++) {
      var productId = recommendations[i].itemID;
       //getProductData(productId,marr);
       var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;
    
    xhr.addEventListener("readystatechange", function() {
      if(this.readyState === 4) {
        var obj = JSON.parse(this.responseText);
        var ptitle  = obj.product.title;
        var pimg    = obj.product.image.src;
        var vprice  = obj.product.variants[0].price;
        var vtitle  = obj.product.variants[0].title;
        var phandle  = obj.product.handle;
       $('#recommendData').append('<div class="column" style="float: left;width: 25%; padding: 10px;"><a href="/products/'+phandle+'" style="text-decoration:none;"><img src="'+pimg+'" width="250" height="150"><h2>'+ptitle+'</h2><p>$'+vprice+'</p></a></div>');
        
      }
    });
    //$('product-recommendations').append("<div style='clear:both'></div>");
    xhr.open("GET", ""+shopUrl+"/admin/api/2022-10/products/"+productId+".json");
    xhr.setRequestHeader("X-Shopify-Access-Token", ""+shoptoken+"");
    xhr.send();
       
}

});
}
});
 

