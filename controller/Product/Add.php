<?php
    class Add 
    {
        const serverUrl = 'https://dev.shopiaz.net/';
        
        public function submit()
        {
            $attributeName = $_REQUEST['attribute-set'];
            $productName = $_REQUEST['name'];
            $productSku = $_REQUEST['sku'];
            $productPrice = $_REQUEST['price'];
            $productQty = $_REQUEST['qty'];
            // echo '<pre>';
            // var_dump($this->isExistAttributeSet($attributeName));die;
            if ($this->isExistAttributeSet($attributeName) == null) {
                $attributeId = $this->getAttributeId($this->addNewAttributeSet($attributeName));
                $this->addProduct($attributeName, $productSku, $productPrice, $productQty, $attributeId);
                echo 'tao thanh cong';
            }else {
                $attributeId = $this->isExistAttributeSet($attributeName);
                $this->addProduct($attributeName, $productSku, $productPrice, $productQty, $attributeId);
                echo 'tao thanh cong 1';
            }
        }
        
        protected function isExistAttributeSet($name)
        {
            $params = array(
                'searchCriteria[filter_groups][0][filters][0][field]' => 'attribute_set_name',
                'searchCriteria[filter_groups][0][filters][0][condition_type]' => 'eq',
                'searchCriteria[filter_groups][0][filters][0][value]' => $name
            );
            $checkAttributeSetUrl = Add::serverUrl.'rest/all/V1/eav/attribute-sets/list?';
            $ch = curl_init();
            $ch = curl_init($checkAttributeSetUrl.http_build_query($params)); 
            $adminToken = $this->getAdminToken("admin", "admin123");
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$adminToken
            ); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");            
            curl_setopt($ch, CURLOPT_POST, false);
            
            $result = curl_exec($ch);
            
            $arrayResult = json_decode($result, true);
        
            // var_dump($arrayResult);
            return $arrayResult['items'][0]['attribute_set_id'];
            // return count($arrayResult['items']) != 0;
        }
        
        protected function addNewAttributeSet($name)
        {
            $adminToken = $this->getAdminToken("admin", "admin123");
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$adminToken
            ); 
            $requestUrl = Add::serverUrl.'rest/all/V1/eav/attribute-sets';

            $ch = curl_init();
            $ch = curl_init($requestUrl); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");            
            curl_setopt($ch, CURLOPT_POST, true);
            
            $post = array();
            $post["entityTypeCode"] = "catalog_product";
            $attributeSet =array();
            $attributeSet['attribute_set_name'] = $name;
            $attributeSet['entity_type_id'] = 4;
            $post["attributeSet"] = $attributeSet;
            $post["skeletonId"] = 4;
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));     
            $result = curl_exec($ch);
            // echo $result;
            return $result;
        }

        protected function getAdminToken($user, $password)
        {
            //Authentication rest API magento2.Please change url accordingly your url
            $adminUrl = Add::serverUrl.'rest/default/V1/integration/admin/token';
            $ch = curl_init();
            $data = array("username" => $user, "password" => $password);                                                                    
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
            return $token;
        }

        public function getAttributeId($attribute){
            $data = json_decode($attribute,true);
            $data['attribute_set_id'];
            return $data['attribute_set_id'];
        }
        
        protected function addProduct($productName,$productSku,$productPrice,$productQty,$attributeSet)
        {
          
            $adminToken = $this->getAdminToken("admin", "admin123");
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$adminToken
            ); 
            $requestUrl = Add::serverUrl.'rest/all/V1/products';

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
                "attributeSetId": "'.$attributeSet.'",
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
            print_r($result);
        }
    }
    
    $addProductModel = new Add();
    $addProductModel->submit();