<?php
class ControllerModuleGoogleMaps extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/google_maps');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_maps', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['gmaps_version'] = $this->language->get('gmaps_version');
		$this->data['gmaps_info'] = $this->language->get('gmaps_info');
		
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_caution'] = $this->language->get('text_caution');
		
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_settigns'] = $this->language->get('entry_settigns');
		$this->data['entry_ballon_text'] = $this->language->get('entry_ballon_text');
		$this->data['entry_theme_box'] = $this->language->get('entry_theme_box');
		$this->data['entry_theme_box_title'] = $this->language->get('entry_theme_box_title');
		$this->data['entry_theme_show_box'] = $this->language->get('entry_theme_show_box');
		$this->data['entry_options'] = $this->language->get('entry_options');
		$this->data['entry_latlong'] = $this->language->get('entry_latlong');
		$this->data['entry_widthheight'] = $this->language->get('entry_widthheight');
		$this->data['entry_zoom'] = $this->language->get('entry_zoom');
		$this->data['entry_mts'] = $this->language->get('entry_mts');
		$this->data['entry_mapid'] = $this->language->get('entry_mapid');
		$this->data['entry_maptype'] = $this->language->get('entry_maptype');
		
		$this->data['confirm_mapid'] = $this->language->get('confirm_mapid');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_addmap'] = $this->language->get('button_addmap');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/google_maps', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/google_maps', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['google_maps_module'])) {
			$this->data['modules'] = $this->request->post['google_maps_module'];
		} elseif ($this->config->get('google_maps_module')) { 
			$this->data['modules'] = $this->config->get('google_maps_module');
		}
		
		$this->data['gmaps'] = array();
		
		if (isset($this->request->post['google_maps_module_map'])) {
			$this->data['gmaps'] = $this->request->post['google_maps_module_map'];
		} elseif ($this->config->get('google_maps_module_map')) { 
			$this->data['gmaps'] = $this->config->get('google_maps_module_map');
		} 		
					
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->template = 'module/google_maps.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_maps')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		if (isset($this->request->post['google_maps_module_map'])) {
			foreach ($this->request->post['google_maps_module_map'] as $key => $value) {
				if (!$value['mapalias']) {
					$this->error['warning'] = $this->language->get('error_mapid');
				}			
			}
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>