<?php
class ModelFilterFilter extends Model {

   private function get_price($price){
      $price = explode('-', $price);
      return ($price);
    }
   public function getManufacturers(){
                        $manufacturer_data = $this->cache->get('manufacturer.' . (int)$this->config->get('config_store_id'));
  
                        if (!$manufacturer_data) {
                                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY name");
        
                                $manufacturer_data = $query->rows;
                        
                                $this->cache->set('manufacturer.' . (int)$this->config->get('config_store_id'), $manufacturer_data);
                        }
                 
                        return $manufacturer_data;
                }

   public function getCategories($parent_id = 0) {
       $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

                return $query->rows;
        }
   public function getTotalProducts($data = array()) {
      $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

      if (!empty($data['filter_category_id'])) {
         $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";        
      }
      
      if (!empty($data['filter_tag'])) {
         $sql .= " LEFT JOIN " . DB_PREFIX . "product_tag pt ON (p.product_id = pt.product_id)";         
      }
               
      $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
      
      if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
         $sql .= " AND (";
                        
         if (!empty($data['filter_name'])) {
            $implode = array();
            
            $words = explode(' ', $data['filter_name']);
            
            foreach ($words as $word) {
               if (!empty($data['filter_description'])) {
                  $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
               } else {
                  $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
               }           
            }
            
            if ($implode) {
               $sql .= " " . implode(" OR ", $implode) . "";
            }
         }
         
         if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
            $sql .= " OR ";
         }
         
         if (!empty($data['filter_tag'])) {
            $implode = array();
            
            $words = explode(' ', $data['filter_tag']);
            
            foreach ($words as $word) {
               $implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
            }
            
            if ($implode) {
               $sql .= " " . implode(" OR ", $implode) . "";
            }
         }
      
         $sql .= ")";
      }
      
      if (!empty($data['filter_category_id'])) {
         if (!empty($data['filter_sub_category'])) {
            $implode_data = array();
            
            $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            
            $this->load->model('catalog/category');
            
            $categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
               
            foreach ($categories as $category_id) {
               $implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
            }
                     
            $sql .= " AND (" . implode(' OR ', $implode_data) . ")";       
         } else {
            $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
         }
      }     
      
      if (!empty($data['filter_manufacturer_id'])) {
         $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
      }
      
      $query = $this->db->query($sql);
      
      return $query->row['total'];
   }
   public function getProducts($data = array()) {
      if ($this->customer->isLogged()) {
         $customer_group_id = $this->customer->getCustomerGroupId();
      } else {
         $customer_group_id = $this->config->get('config_customer_group_id');
      }  
      
      $cache = md5(http_build_query($data));
      
      $product_data = $this->cache->get('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
      
      if (!$product_data) {
         $sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)"; 
         
         if (!empty($data['filter_tag'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_tag pt ON (p.product_id = pt.product_id)";         
         }
                  
         if (!empty($data['filter_category_id'])) {
            $sql .= " LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";        
         }
         
         $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
         
         if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";
                                 
            if (!empty($data['filter_name'])) {
               $implode = array();
               
               $words = explode(' ', $data['filter_name']);
               
               foreach ($words as $word) {
                  if (!empty($data['filter_description'])) {
                     $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%' OR LCASE(pd.description) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                  } else {
                     $implode[] = "LCASE(pd.name) LIKE '%" . $this->db->escape(utf8_strtolower($word)) . "%'";
                  }           
               }
               
               if ($implode) {
                  $sql .= " " . implode(" OR ", $implode) . "";
               }
            }
            
            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
               $sql .= " OR ";
            }
            
            if (!empty($data['filter_tag'])) {
               $implode = array();
               
               $words = explode(' ', $data['filter_tag']);
               
               foreach ($words as $word) {
                  $implode[] = "LCASE(pt.tag) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%' AND pt.language_id = '" . (int)$this->config->get('config_language_id') . "'";
               }
               
               if ($implode) {
                  $sql .= " " . implode(" OR ", $implode) . "";
               }
            }
         
            $sql .= ")";
         }
         
         if (!empty($data['filter_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
               $implode_data = array();
               
               $implode_data[] = "p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
               
               $this->load->model('catalog/category');
               
               $categories = $this->model_catalog_category->getCategoriesByParentId($data['filter_category_id']);
                              
               foreach ($categories as $category_id) {
                  $implode_data[] = "p2c.category_id = '" . (int)$category_id . "'";
               }
                        
               $sql .= " AND (" . implode(' OR ', $implode_data) . ")";       
            } else {
               $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
            }
         }     
               
         if (!empty($data['filter_manufacturer_id'])) {
            $sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
         }
         
         $sql .= " GROUP BY p.product_id";
         
         $sort_data = array(
            'pd.name',
            'p.model',
            'p.quantity',
            'p.price',
            'rating',
            'p.sort_order',
            'p.date_added'
         ); 
         
         if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
               $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
               $sql .= " ORDER BY " . $data['sort'];
            }
         } else {
            $sql .= " ORDER BY p.sort_order";   
         }
         
         if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
         } else {
            $sql .= " ASC";
         }
      
         if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
               $data['start'] = 0;
            }           
   
            if ($data['limit'] < 1) {
               $data['limit'] = 20;
            }  
         
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
         }
         
         $product_data = array();
               
         $query = $this->db->query($sql);
      
         foreach ($query->rows as $result) {
            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
         }
         
         $this->cache->set('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
      }
      
      return $product_data;
   }
   public function getFilterProducts($data = array()) {
      if ($this->customer->isLogged()) {
         $customer_group_id = $this->customer->getCustomerGroupId();
      } else {
         $customer_group_id = $this->config->get('config_customer_group_id');
      }  
      
      $cache = md5(http_build_query($data));
      
      $product_data = $this->cache->get('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache);
      
      if (!$product_data) {
         $sql = "SELECT p.product_id, 
                  (SELECT AVG(rating) AS total 
                     FROM " . DB_PREFIX . "review r1 
                     WHERE r1.product_id = p.product_id 
                        AND r1.status = '1' 
                     GROUP BY r1.product_id) AS rating 
                 FROM " . DB_PREFIX . "product p 
                  LEFT JOIN " . DB_PREFIX . "product_description pd 
                     ON (p.product_id = pd.product_id) 
                  LEFT JOIN " . DB_PREFIX . "product_to_store p2s 
                     ON (p.product_id = p2s.product_id)"; 
         
         if (!empty($data['filter_category_ids'])) {
            $sql .= " INNER JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";        
         }
         if (!empty($data['filter_attribute_ids'])){
		$sql .= "INNER JOIN (select product_id from ".DB_PREFIX."product_attribute IGNORE INDEX (PRIMARY)
where";
		$implode_data = array();
		foreach($data['filter_attribute_ids'] as $id => $value_array)
		  {
		    $reg = '/^\d+-\d+$/';
		if(preg_match($reg,$value_array[0]))
		    {
			$minmax_array = array();
			$temp_arr = array();
			foreach($value_array as $cur_range){
				$minmax_array = explode('-',$cur_range);

				$temp_arr[] = " ((text>= ".(int)$minmax_array[0].")AND(text<= ".(int)$minmax_array[1].")) ";
			}
			$implode_data[] = " ((attribute_id = ".(int)$id.") AND (language_id=".(int)$this->config->get('config_language_id').") AND (".implode(' OR ',$temp_arr).")) ";		     
		    }else
		    {
   $escaped_data = array();
		      foreach($value_array as $attr_data)
			{
			  $escaped_data[] = $this->db->escape($attr_data);
			}
		      $implode_data[] = " ((attribute_id = ".(int)$id.") AND (language_id=".(int)$this->config->get('config_language_id').") AND (text in ('".implode("','",$escaped_data)."' ))) ";
		    }
		  }
	   
		$sql .= " ( " . implode(' OR ',$implode_data).")";

		$sql .= " 	
	group by product_id
	having count(*) = ". count($implode_data).") as addon
       	on addon.product_id = p.product_id";	
	 }
         $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'"; 
         if (!empty($data['filter_category_ids'])) {
	   if (!empty($data['filter_sub_category'])) {
	     $implode_data = array();
	     $this->load->model('catalog/category');
	     foreach($data['filter_category_ids'] as $filter_category){ 
               $implode_data[] = (int)$filter_category;
               $categories = $this->model_catalog_category->getCategoriesByParentId($filter_category);
               foreach ($categories as $category_id) {
		 $implode_data[] = (int)$category_id;
               }
	     } 
	     $sql .= " AND p2c.category_id in (" . implode(',', $implode_data) . ")";       
	   } else {
	     $sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
	   }
         } 


         if (!empty($data['filter_manufacturer_ids'])) {
	   $implode_data = array();
	   foreach($data['filter_manufacturer_ids'] as $value){
	     $implode_data[] = (int)$value;
	   }
            $sql .= " AND p.manufacturer_id in (" . implode(',',$implode_data) . ")";
         }
         if (!empty($data['filter_price_ids'])) {
	    $implode_data = array();
	   foreach($data['filter_price_ids'] as $price_data){
            $fprice = explode("-",$price_data);
            $implode_data[] = "(p.price >= '" . (float)$fprice[0] . "' AND p.price<= '".(float)$fprice[1]."') ";
	   }
	   $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
         }
         
         $sql .= " GROUP BY p.product_id";
         
         $sort_data = array(
            'pd.name',
            'p.model',
            'p.quantity',
            'p.price',
            'rating',
            'p.sort_order',
            'p.date_added'
         ); 
         
         if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
               $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
               $sql .= " ORDER BY " . $data['sort'];
            }
         } else {
            $sql .= " ORDER BY p.sort_order";   
         }
         
         if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
         } else {
            $sql .= " ASC";
         }
      
         if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
               $data['start'] = 0;
            }           
   
            if ($data['limit'] < 1) {
               $data['limit'] = 20;
            }  
         
            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
         }

         $product_data = array();
         $query = $this->db->query($sql);
        
         foreach ($query->rows as $result) {
            $product_data[$result['product_id']] = $this->getProduct($result['product_id']);
         }
         
         $this->cache->set('product.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$customer_group_id . '.' . $cache, $product_data);
      }
      
      return $product_data;
   }
   public function getProduct($product_id) {
      if ($this->customer->isLogged()) {
         $customer_group_id = $this->customer->getCustomerGroupId();
      } else {
         $customer_group_id = $this->config->get('config_customer_group_id');
      }  
            
      $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
      if ($query->num_rows) {
         $query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
         $query->row['rating'] = (int)$query->row['rating'];
         
         return $query->row;
      } else {
         return false;
      }
   }
   public function getFilterTotalProducts($data = array()) {
      $sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

      if (!empty($data['filter_category_ids'])) {
         $sql .= " INNER JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id)";        
      }
         if (!empty($data['filter_attribute_ids'])){
	 	$sql .= "INNER JOIN (select product_id from ".DB_PREFIX."product_attribute IGNORE INDEX (PRIMARY)
where";
	$implode_data = array();
	   foreach($data['filter_attribute_ids'] as $id => $value_array)
	     {
                         $reg = '/^\d+-\d+$/';
                if(preg_match($reg,$value_array[0]))
                    {
                        $minmax_array = array();
			$temp_arr = array();
                        foreach($value_array as $cur_range){
                                $minmax_array = explode('-',$cur_range);

                                $temp_arr[] = " ((text>= ".(int)$minmax_array[0].")AND(text<= ".(int)$minmax_array[1].")) ";
                        }
			$implode_data[] = " ((attribute_id = ".(int)$id.") AND (language_id=".(int)$this->config->get('config_language_id').") AND (".implode(' OR ',$temp_arr).")) ";
                    }else
                    {
		      $escaped_data = array();
		      foreach($value_array as $attr_data)
			{
			  $escaped_data[] = $this->db->escape($attr_data);
			}
		      $implode_data[] = " ((attribute_id = ".(int)$id.") AND (language_id=".(int)$this->config->get('config_language_id').") AND (text in ('".implode("','",$escaped_data)."' ))) ";
                    }
	     }
	   
	   $sql .= " ( " . implode(' OR ',$implode_data).")";

	$sql .= " 	
	group by product_id
	having count(*) =".count($implode_data).") as addon
       	on addon.product_id = p.product_id";	

	 }     

      $sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
      
            
      if (!empty($data['filter_category_ids'])) {
         if (!empty($data['filter_sub_category'])) {
            $implode_data = array();
            $this->load->model('catalog/category');
     	    foreach ($data['filter_category_ids'] as $value){
	      $implode_data[] = (int)$value;
	      $categories = $this->model_catalog_category->getCategoriesByParentId($value);
               
	      foreach ($categories as $category_id) {
		$implode_data[] = (int)$category_id;
	      }
            }         
            $sql .= " AND p2c.category_id IN (" . implode(',', $implode_data) . ")";      
	     
         } else {
            $sql .= " AND p2c.category_id IN (" . implode(',',$data['filter_category_ids']) . ")";
         }
      }     
      
      if (!empty($data['filter_manufacturer_ids'])) {
	$implode_data = array();
	foreach($data['filter_manufacturer_ids'] as $value)
	  {
	    $implode_data[] = (int)$value;
	  }
         $sql .= " AND p.manufacturer_id in (" . implode(',',$implode_data) . ")";
      }
      if (!empty($data['filter_price_ids'])) {
	$implode_data = array();
	foreach($data['filter_price_ids'] as $data_price){
         $fprice = explode("-",$data_price);
	 $implode_data[]="(p.price >= '" . (float)$fprice[0] . "' AND p.price<= '".(float)$fprice[1]."') ";
	}
	 $sql .= " AND (" . implode(' OR ', $implode_data) . ")";
      }
      $query = $this->db->query($sql);
      return $query->row['total'];
   }
   public function getProductAttributes($product_id) {
      $product_attribute_group_data = array();
      
      $product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
      
      foreach ($product_attribute_group_query->rows as $product_attribute_group) {
         $product_attribute_data = array();
         
         $product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");
         
         foreach ($product_attribute_query->rows as $product_attribute) {
            $product_attribute_data[] = array(
               'attribute_id' => $product_attribute['attribute_id'],
               'name'         => $product_attribute['name'],
               'text'         => $product_attribute['text']       
            );
         }
         
         $product_attribute_group_data[] = array(
            'attribute_group_id' => $product_attribute_group['attribute_group_id'],
            'name'               => $product_attribute_group['name'],
            'attribute'          => $product_attribute_data
         );       
      }
      
      return $product_attribute_group_data;
   }
   public function getFilterAttributes() {
      $product_attribute_group_data = array();
      
      $product_attribute_group_query = $this->db->query("SELECT 
         ag.attribute_group_id, agd.name 
         FROM " . DB_PREFIX . "product_attribute pa 
               LEFT JOIN " . DB_PREFIX . "attribute a 
               ON (pa.attribute_id = a.attribute_id) 
               LEFT JOIN " . DB_PREFIX . "attribute_group ag 
               ON (a.attribute_group_id = ag.attribute_group_id) 
               LEFT JOIN " . DB_PREFIX . "attribute_group_description agd 
               ON (ag.attribute_group_id = agd.attribute_group_id) 
         WHERE a.to_filter = 1 
            AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
         GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
      
      foreach ($product_attribute_group_query->rows as $product_attribute_group) {
         $product_attribute_data = array();
         
         $product_attribute_query = $this->db->query("SELECT 
            a.attribute_id, ad.name 
            FROM ". DB_PREFIX . "attribute a 
               LEFT JOIN " . DB_PREFIX . "attribute_description ad 
               ON (a.attribute_id = ad.attribute_id) 
            WHERE a.to_filter = 1 
               AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' 
               AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            ORDER BY a.sort_order, ad.name");
         
         foreach ($product_attribute_query->rows as $product_attribute) {
            $product_attribute_data[] = array(
               'attribute_id' => $product_attribute['attribute_id'],
               'name'         => $product_attribute['name']    
            );
         }
         
         $product_attribute_group_data[] = array(
            'attribute_group_id' => $product_attribute_group['attribute_group_id'],
            'name'               => $product_attribute_group['name'],
            'attribute'          => $product_attribute_data
         );       
      }
      
      return $product_attribute_group_data;
   }
   //get attributes by parent category id
   public function getFilterAttributesByCategory($fcategory_id) {
      $product_attribute_group_data = array();
      
      $product_attribute_group_query = $this->db->query("SELECT 
         ag.attribute_group_id, agd.name 
         FROM " . DB_PREFIX . "product_attribute pa 
               LEFT JOIN " . DB_PREFIX . "attribute a 
               ON (pa.attribute_id = a.attribute_id) 
               LEFT JOIN " . DB_PREFIX . "attribute_group ag 
               ON (a.attribute_group_id = ag.attribute_group_id) 
               LEFT JOIN " . DB_PREFIX . "attribute_group_description agd 
               ON (ag.attribute_group_id = agd.attribute_group_id) 
               RIGHT JOIN filter_attribute_to_category  fac
               ON (a.attribute_id = fac.attribute_id)
            WHERE fac.category_id = ".(int)$fcategory_id."
         AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
         GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
      
      foreach ($product_attribute_group_query->rows as $product_attribute_group) {
         $product_attribute_data = array();
         $sql = "SELECT 
            a.attribute_id, ad.name,IFNULL(fat.type_id,0) as type_id
            FROM ". DB_PREFIX . "attribute a 
               LEFT JOIN " . DB_PREFIX . "attribute_description ad 
               ON (a.attribute_id = ad.attribute_id) 
               RIGHT JOIN filter_attribute_to_category  fac
               ON (a.attribute_id = fac.attribute_id)
               LEFT JOIN filter_attribute_type fat
               ON (fat.attribute_id = a.attribute_id)
              WHERE fac.category_id = ".$fcategory_id."
               AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' 
               AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' 
            ORDER BY a.sort_order, ad.name";

	 $product_attribute_query = $this->db->query($sql);
         
         foreach ($product_attribute_query->rows as $product_attribute) {
	   if($product_attribute['type_id']>0){
	     $sql = "SELECT
                value,name,sort_order
                FROM filter_attribute_to_values
                WHERE attribute_id = ".(int)$product_attribute['attribute_id']."
                ORDER BY sort_order";
	     $attr_values = $this->db->query($sql);
	     $values = array();
	     foreach($attr_values->rows as $value)
	       {
		 $values[] = array(
		   'value' => $value['value'],
		   'name' => $value['name'],
		   'sort_order' => $value['sort_order']
				   );
	       }
	     $product_attribute_data[] = array(
					       'attribute_id' => $product_attribute['attribute_id'],
					       'name'         => $product_attribute['name'],
					       'type_id'      => $product_attribute['type_id'],
					       'values'       => $values
					       );
	     
	      }
	   else{
	   $product_attribute_data[] = array(
					     'attribute_id' => $product_attribute['attribute_id'],
					     'name'         => $product_attribute['name'],
					     'type_id'      => $product_attribute['type_id'],
					     );
	   }
         }
         
         $product_attribute_group_data[] = array(
            'attribute_group_id' => $product_attribute_group['attribute_group_id'],
            'name'               => $product_attribute_group['name'],
            'attribute'          => $product_attribute_data
         );       
      }
      
      return $product_attribute_group_data;
   }

   //get advanced attr values content
   public function getAttributeValues($attr_id) {
      $_values = array();
      $sql = "SELECT 
         a.attribute_id, fav.value,fav.name, fav.sort_order
         FROM " . DB_PREFIX . "attribute a 
               LEFT JOIN filter_attribute_to_values as fav
               ON (a.attribute_id = fav.attribute_id) 
         WHERE a.attribute_id = ".(int)$attr_id."
         ORDER BY fav.sort_order";

      $_values = $this->db->query($sql);

      foreach ($_values->rows as $_value) {
            $_data[] = array(
               'attribute_id' => $_value['attribute_id'],
               'value'         => $_value['value']    ,
	       'sort_order'=>$_value['sort_order'],
	       'name'=>$_value['name']
            );
      }
      return $_data;
   }
   public function getAdvancedValues($attr_id) {
      $_values = array();
      $sql = "SELECT fav.value,fav.name, fav.sort_order
         FROM filter_attribute_to_values as fav
         WHERE fav.attribute_id = ".(int)$attr_id."
         ORDER BY fav.sort_order";

      $_values = $this->db->query($sql);

      foreach ($_values->rows as $_value) {
            $_data[] = array(
               'value'         => $_value['value']    ,
	       'sort_order'=>$_value['sort_order'],
	       'name'=>$_value['name']
            );
      }
      return $_data;
   }

}
