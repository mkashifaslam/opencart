<?php
class ControllerModuleFilter extends Controller {
  protected function index($setting) {
    $this->language->load('module/filter');
    $this->load->model('filter/filter');
    $this->load->model('tool/image'); 
    if (isset($this->request->get['sort'])) {
      $sort = $this->request->get['sort'];
    } else {
      $sort = 'p.sort_order';
    } 

    if (isset($this->request->get['order'])) {
      $order = $this->request->get['order'];
    } else {
      $order = 'ASC';
    }
    
    if (isset($this->request->get['page'])) {
      $page = $this->request->get['page'];
    } else {
      $page = 1;
    }
    
    if (isset($this->request->get['limit'])) {
      $limit = $this->request->get['limit'];
    } else {
      $limit = $this->config->get('config_catalog_limit');
    }

    $filter_category_ids = array();
    if (isset($this->request->get['path'])) {
      $this->data['categ'] = $this->request->get['path'];
      $parts = explode('_',(string)$this->request->get['path']);
      if(isset($parts[0])){
	$filter_category_ids[] = $parts[0];
	$fcategory_id = $parts[0];
      }
    }
    else
      {
	$fcategory_id = 0;
	$filter_category_ids[] = $fcategory_id;
      }
    
    $this->data['fcategory_id'] = $fcategory_id;
    $filter_sub_category = true;

    $results = $this->model_filter_filter->getManufacturers();

    foreach ($results as $result) {
      $data = array(
		    'filter_manufacturer_id'  => $result['manufacturer_id'],
		    'filter_category_id' => $fcategory_id,
		    'filter_sub_category'=> true
		    );

      $product_total = $this->model_filter_filter->getTotalProducts($data);
      if($product_total>0){
	$this->data['manufacturers'][] = array(
					       'manufacturer_id' => $result['manufacturer_id'],
					       'name'            => $result['name'],
					       'total'	      => $product_total
					       );
      }
    }
    $categories = $this->model_filter_filter->getCategories($fcategory_id);
    $this->data['categories'] = array();		
    foreach ($categories as $category) {
      $data = array(
		    'filter_category_id'  => $category['category_id'],
		    'filter_sub_category' => true
		    );

      $product_total = $this->model_filter_filter->getTotalProducts($data);
      $this->data['categories'][] = array(
					  'category_id' => $category['category_id'],
					  'name'        => $category['name'],
					  'total'	     => $product_total
					  );
    }

    $price_values  = $this->model_filter_filter->getAdvancedValues(-1);
    foreach($price_values as $data){
      eval("\$name = ".$data['name'].";");
      $this->data['fprice'][] = array(
				      'fprice_id' => $data['value'],
				      'name'=> $name,
				      'sort_order'=>$data['sort_order']
				      );
    }
    $this->data['fattributes'] = $this->model_filter_filter->getFilterAttributesByCategory($fcategory_id);

    $this->document->addScript('catalog/view/javascript/filter.js');

  $this->data['heading_title'] = $this->language->get('heading_title');
		

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filter.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/module/filter.tpl';
    } else {
      $this->template = 'default/template/module/filter.tpl';
    }

    $this->render();
  }
  public function getIndex() { 
    $this->language->load('module/filter');
      $this->load->model('filter/filter');
    
    $this->load->model('tool/image'); 
    if (isset($this->request->post['sort'])) {
      $sort = $this->request->post['sort'];
    } else {
      $sort = 'p.sort_order';
    } 

    if (isset($this->request->post['order'])) {
      $order = $this->request->post['order'];
    } else {
      $order = 'ASC';
    }
    if (isset($this->request->post['fcategory_id'])){
      $fcategory_id = (int)$this->request->post['fcategory_id'];
    }else{
      $fcategory_id = 0;
    }
    if (isset($this->request->post['page'])) {
      $page = $this->request->post['page'];
    } else {
      $page = 1;
    }
      
    if (isset($this->request->post['limit'])) {
      $limit = $this->request->post['limit'];
    } else {
      $limit = $this->config->get('config_catalog_limit');
    }

    if (isset($this->request->post['fprice'])) {
      $filter_price_ids = $this->request->post['fprice'];
    }
    if (isset($this->request->post['manufacturers'])) {
      $filter_manufacturer_ids = $this->request->post['manufacturers'];
    }
    if (isset($this->request->post['categories'])) {
      $filter_category_ids = $this->request->post['categories'];
      $filter_sub_category = true;
    }
    else
      {
	$filter_category_ids = array();
	$filter_category_ids[] = $fcategory_id;
	$filter_sub_category = true;
      }
      
    if (isset($this->request->post['fattributes'])){
      $fattributes = $this->request->post['fattributes'];
      $filter_attribute_ids = $this->request->post['fattributes'];
    }
    $this->data['fcategory_id'] = $fcategory_id;
    $this->data['text_adv_search'] = $this->language->get('text_adv_search');		
    $this->data['text_empty'] = $this->language->get('text_empty');
    $this->data['text_critea'] = $this->language->get('text_critea');
    $this->data['text_search'] = $this->language->get('text_search');
    $this->data['text_keyword'] = $this->language->get('text_keyword');
    $this->data['text_category'] = $this->language->get('text_category');
    $this->data['text_sub_category'] = $this->language->get('text_sub_category');
    $this->data['text_quantity'] = $this->language->get('text_quantity');
    $this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
    $this->data['text_model'] = $this->language->get('text_model');
    $this->data['text_price'] = $this->language->get('text_price');
    $this->data['text_tax'] = $this->language->get('text_tax');
    $this->data['text_points'] = $this->language->get('text_points');
    $this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
    $this->data['text_display'] = $this->language->get('text_display');
    $this->data['text_list'] = $this->language->get('text_list');
    $this->data['text_grid'] = $this->language->get('text_grid');		
    $this->data['text_sort'] = $this->language->get('text_sort');
    $this->data['text_limit'] = $this->language->get('text_limit');
      
    $this->data['entry_search'] = $this->language->get('entry_search');
    $this->data['entry_description'] = $this->language->get('entry_description');
      
    $this->data['button_search'] = $this->language->get('button_search');
    $this->data['button_cart'] = $this->language->get('button_cart');
    $this->data['button_wishlist'] = $this->language->get('button_wishlist');
    $this->data['button_compare'] = $this->language->get('button_compare');
    $this->data['products'] = array();
    $data = array(
		  'filter_category_ids'  => $filter_category_ids, 
		  'filter_sub_category' => $filter_sub_category, 
		  'sort'                => $sort,
		  'order'               => $order,
		  'start'               => ($page - 1) * $limit,
		  'limit'               => $limit,
		  'filter_price_ids'	  => (isset($filter_price_ids))?$filter_price_ids:'',
		  'filter_manufacturer_ids' => (isset($filter_manufacturer_ids))?$filter_manufacturer_ids:'',
		  'filter_attribute_ids' => (isset($filter_attribute_ids))?$filter_attribute_ids:''
		  );
    $product_total = $this->model_filter_filter->getFilterTotalProducts($data);
    $results = $this->model_filter_filter->getFilterProducts($data);
    foreach ($results as $result) {
      if ($result['image']) {
	$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
      } else {
	$image = false;
      }
	
      if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
	$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
      } else {
	$price = false;
      }
	
      if ((float)$result['special']) {
	$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
      } else {
	$special = false;
      }	
	
      if ($this->config->get('config_tax')) {
	$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
      } else {
	$tax = false;
      }				
	
      if ($this->config->get('config_review_status')) {
	$rating = (int)$result['rating'];
      } else {
	$rating = false;
      }
      $this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => mb_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200,'UTF-8') . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
    }
    $pagination = new Pagination();
    $pagination->total = $product_total;
    $pagination->page = $page;
    $pagination->limit = $limit;
    $pagination->text = $this->language->get('text_pagination');
    $pagination->url = $this->url->link('module/filter', 'page={page}');
      
    $this->data['pagination'] = $pagination->render();

    $this->data['sort'] = $sort;
    $this->data['order'] = $order;
    $this->data['limit'] = $limit;
    $this->data['page'] = $page;
    $this->response->setOutput(json_encode($this->data));
  }
  public function getProducts()
  {
    $this->language->load('product/search');

    $this->load->model('catalog/category');

    $this->load->model('catalog/product');

    $this->load->model('tool/image');

    $this->data['products'] = array();

    $pstart = $this->request->post['pstart'];
    $pend = $this->request->post['pend'];
    if (isset($pstart) && isset($pend)) {
      $data = array(
		    'attrid'        =>16,
		    'pstart'        =>$pstart,
		    'pend'          => $pend
		    );
      $results = $this->model_catalog_product->getProductsByPower($data);

      foreach ($results as $result) {
	if ($result['image']) {
	  $image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
	} else {
	  $image = false;
	}

	if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
	  $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
	} else {
	  $price = false;
	}

	if ((float)$result['special']) {
	  $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
	} else {
	  $special = false;
	}
	if ($this->config->get('config_tax')) {
	  $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
	} else {
	  $tax = false;
	}

	if ($this->config->get('config_review_status')) {
	  $rating = (int)$result['rating'];
	} else {
	  $rating = false;
	}

	$json[] = array(
			'id'  => $result['product_id'],
			'thumb'       => $image,
			'name'        => $result['name'],
			'description' => mb_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200,'UTF-8') . '..',
			'price'       => $price,
			'special'     => $special,
			'tax'         => $tax,
			'href'        => $this->url->link('product/product', '&product_id=' . $result['product_id'])
			);
      }
    }
    if( (!isset($json)) || (count($json) == 0) )
      $this->response->setOutput(json_encode(array('bool'=>false)));
    else
      $this->response->setOutput(json_encode($json));
  }
}
?>
