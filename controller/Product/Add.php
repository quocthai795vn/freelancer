<?php
    class Add 
    {
        const serverUrl = 'https://dev.shopiaz.net/';
        
        /**
         * @var string 
         */
        protected $adminToken;
        
        public function submit()
        {
            $attributes = $_REQUEST['attribute'];
            $options = $_REQUEST['optionValue'];
            $attributeListParams = $this->convertValueToArray($attributes, $options);
            
            $attributeName = $_REQUEST['attribute-set'];
            $productName = $_REQUEST['name'];
            $productSku = $_REQUEST['sku'];
            $productPrice = $_REQUEST['price'];
            $productQty = $_REQUEST['qty'];
            
            $this->adminToken = $this->getAdminToken("admin", "admin123");
            $productAttributeList = $this->getProductAttributes();
                    
            //Get product attributes name for search
            $productAttributesName = array();
            if ($productAttributeList) {
                $productAttributesName = array_column($productAttributeList['items'], 'default_frontend_label');
            }
            
            //Check exist product attribute
            $attributeSaver = array();
            if (!empty($attributeListParams)) {
                foreach ($attributeListParams as $attribute => $option) {
                    if (!in_array($attribute, $productAttributesName)) {
                        $attributeSaver[] = $this->productAttributeNotExist($attribute, $option);
                    }
                    else {
                        $attributeSaver[] = $this->productAttributeExist($attribute, $option, $productAttributeList);
                    }
                }
            }
            $this->addProduct($productName, $productSku, $productPrice, $productQty, json_encode($attributeSaver));
        }
        
        /**
         * 
         * @param array $attributes
         * @param array $options
         * @return type
         */
        protected function convertValueToArray($attributes, $options)  
        {
            $arrayList = array();
            foreach ($attributes as $key => $attribute) {
                $arrayList[$attribute] = $options[$key];
            }
            return $arrayList;
        }

        /**
         * Get all product attributes
         * 
         * @return $array
         */
        protected function getProductAttributes()
        {
            $params = $this->getSearchCriteria(0, 'is_visible', 'eq', 1);
            $checkAttributeSetUrl = Add::serverUrl.'rest/all/V1/products/attributes?';
            $ch = curl_init();
            $ch = curl_init($checkAttributeSetUrl.http_build_query($params)); 
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$this->adminToken
            ); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");            
            curl_setopt($ch, CURLOPT_POST, false);
            
            $result = curl_exec($ch);
            
            $arrayResult = json_decode($result, true);
        
            return $arrayResult;
        }
        
        /**
         * Get search criteria 
         * 
         * @param int $serial
         * @param string $field
         * @param string $conditionType
         * @param string $value
         */
        protected function getSearchCriteria($serial, $field, $conditionType, $value)
        {
            $prefix = sprintf('searchCriteria[filter_groups][%s][filters][%s]', $serial, $serial);
            return 
                array(
                    $prefix.'[field]' => $field,
                    $prefix.'[condition_type]' => $conditionType,
                    $prefix.'[value]' => $value
                );
        }

        /**
         * Create product attribute if not exist
         * 
         * @param string $attribute
         * @param string $option
         * @return array
         */
        protected function productAttributeNotExist($attribute, $option)
        {
            $response = json_decode($this->createNewAttribute($attribute), true);
			$this->createNewOption($response['attribute_code'], $option);
            return 
                array(
                    'attribute_code' => $response['attribute_code'],
                    'value' =>  $option
                );
        }
        
        /**
         * Find product attribute name and add product option
         * 
         * @param string $attribute
         * @param string $option
         * @param array $productAttributeList
         * @return array
         */
        protected function productAttributeExist($attribute, $option, $productAttributeList) 
        {
            foreach ($productAttributeList['items'] as $item) {
                if ($item['default_frontend_label'] == $attribute) {
                    $this->createNewOption($item['attribute_code'], $option);
                    return 
                        array(
                            'attribute_code' => $item['attribute_code'],
                            'value' =>  $option
                        );
                }
            }
        }
        
        /**
         * @param string $attribute_code
         * @param string $option
         */
        protected function createNewOption($attribute_code, $option)
        {
			$option = (string)$option;
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$this->adminToken
            ); 
            $requestUrl = Add::serverUrl.sprintf('rest/all/V1/products/attributes/%s/options', $attribute_code);

            $ch = curl_init();
            $ch = curl_init($requestUrl); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");            
            curl_setopt($ch, CURLOPT_POST, true);
            
            $optionParams['option'] = array();
            $optionParams['option']['label'] = $option;
            $optionParams['option']['value'] = $option;
            $optionParams['option']['sort_order'] = 0;
            $optionParams['option']['is_default'] = true;
            $optionParams['option']['store_labels'] = array("store_id" => 0, "label" => $option);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($optionParams));     
            $result = curl_exec($ch);
            return $result;
        }
        
        /**
         * Create new product attribute 
         * 
         * @param string $attributeName
         * @return string
         */
        protected function createNewAttribute($attributeName)
        {
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$this->adminToken
            ); 
            $requestUrl = Add::serverUrl.'rest/all/V1/products/attributes';

            $ch = curl_init();
            $ch = curl_init($requestUrl); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");            
            curl_setopt($ch, CURLOPT_POST, true);
            
            $attributeParams = array();
            $attribute = array();
            $attribute['is_wysiwyg_enabled'] = true;
            $attribute['is_html_allowed_on_front'] = true;
            $attribute['used_for_sort_by'] = true;
            $attribute['is_filterable'] = true;
            $attribute['is_filterable_in_search'] = true;
            $attribute['is_used_in_grid'] = true;
            $attribute['is_filterable_in_grid'] = true;
            $attribute['position'] = 0;
            $attribute['apply_to'] = array();
            $attribute['is_searchable'] = true;
            $attribute['is_visible_in_advanced_search'] = true;
            $attribute['is_comparable'] = true;
            $attribute['is_used_for_promo_rules'] = true;
            $attribute['is_visible_on_front'] = true;
            $attribute['used_in_product_listing'] = true;
            $attribute['is_visible'] = true;
            $attribute['scope'] = 'store';
            $attribute['attribute_code'] = strtolower(str_replace(' ','_',trim($attributeName)));
            $attribute['frontend_input'] = 'multiselect';
            $attribute['entity_type_id'] = 4;
            $attribute['is_required'] = true;
            $attribute['options'] = array();
            $attribute['is_user_defined'] = true;
            $attribute['default_frontend_label'] = $attributeName;
            $attribute['frontend_labels'] = array();
            $attribute['backend_type'] = 'varchar';
            $attribute['is_unique'] = true;
            $attribute['validation_rules'] = array();
            $attributeParams['attribute'] = $attribute;
      
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($attributeParams));     
            $result = curl_exec($ch);
            return $result;
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
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$this->adminToken
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
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$this->adminToken
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
        
        protected function addProduct($productName, $productSku, $productPrice, $productQty, $attributeCode)
        {
          
            $headers = array(
                'Content-Type: application/json',                                                                                
                'Authorization: Bearer '.$this->adminToken
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
                "attributeSetId": 4,
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
                "customAttributes": '.$attributeCode.'
              },
              "saveOptions": true
            }';
            var_dump($post);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);     
            $result = curl_exec($ch);
            print_r($result);
        }
    }
    
    $addProductModel = new Add();
    $addProductModel->submit();
