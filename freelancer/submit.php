<?php
    //Form data
    $productName = $_REQUEST['name'];
    $productSku = $_REQUEST['sku'];
    $productPrice = $_REQUEST['price'];
    $productQty = $_REQUEST['qty'];

    //Authentication rest API magento2.Please change url accordingly your url
    $adminUrl='https://dev.shopiaz.net/rest/default/V1/integration/admin/token';
    $ch = curl_init();
    $data = array("username" => "admin", "password" => "admin123");                                                                    
    $data_string = json_encode($data);                       
    $ch = curl_init($adminUrl); 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, 
        array(                                                                          
            'Content-Type: application/json',                                                                                
            'Content-Length:' . strlen($data_string)
        )                                                                       
    );       
    $token = curl_exec($ch);
    $token = json_decode($token);

    //Use above token into header
    $headers = array(
        'Content-Type: application/json',                                                                                
        'Authorization: Bearer '.$token
    ); 


    $requestUrl = 'https://dev.shopiaz.net/rest/all/V1/products';
    //Please note 24-MB01 is sku

    $ch = curl_init();
    $ch = curl_init($requestUrl); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");            
    curl_setopt($ch, CURLOPT_POST, true);

    $post = '{
      "product": {
        "sku": "'.$productSku.'",
        "name": "'.$productName.'",
        "attributeSetId": "4",
        "price": '.$productPrice.',
        "status": 1,
        "visibility": 4,
        "typeId": "simple",
        "weight": 0,
        "extensionAttributes": {
          "website_ids": [
            0
          ],
          "stockItem": {
            "stockId": 1,
            "qty": '.$productQty.',
            "isInStock": true,
            "isQtyDecimal": false,
            "useConfigMinQty": true,
            "minQty": 0,
            "useConfigMinSaleQty": 0,
            "minSaleQty": 0,
            "useConfigMaxSaleQty": true,
            "maxSaleQty": 0,
            "useConfigBackorders": false,
            "backorders": 0,
            "useConfigNotifyStockQty": true,
            "notifyStockQty": 20,
            "useConfigQtyIncrements": false,
            "qtyIncrements": 0,
            "useConfigEnableQtyInc": false,
            "enableQtyIncrements": false,
            "useConfigManageStock": true,
            "manageStock": true,
            "lowStockDate": "string",
            "isDecimalDivided": true,
            "stockStatusChangedAuto": 0,
            "extensionAttributes": {}
          }
        },
        "options": [],
        "tierPrices": [],
        "customAttributes": [
        ]
      },
      "saveOptions": true
    }';
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);                                                                  
    $result = curl_exec($ch);
    $result=  json_decode($result);
    print_r($result);