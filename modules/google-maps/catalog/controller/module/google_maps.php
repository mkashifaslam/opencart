<?php  
class ControllerModuleGoogleMaps extends Controller {
	protected function index($setting) {
		static $module_map = 0;
		
		$this->language->load('module/google_maps');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$maps =  array();
		if (isset($this->request->post['google_maps_module_map'])) {
			$maps = $this->request->post['google_maps_module_map'];
		} elseif ($this->config->get('google_maps_module_map')) { 
			$maps = $this->config->get('google_maps_module_map');
		}
		$this->data['gmaps'] = array();
		$fistmaplatlong = false;
		foreach ($maps as $map) {
			$split_mts = explode(',', $setting['mts']);
			
			foreach ($split_mts as $smts) {
				if ($smts == $map['mapalias']) {
					if ($fistmaplatlong == false) {
						$this->data['gmap_flatlong'] = $map['latlong'];
						$fistmaplatlong = true;
					}
					$tmpmaptext = $map['maptext'][$this->config->get('config_language_id')];
					$tmpmaptext = str_replace('\n', '', $tmpmaptext);
					$tmpmaptext = str_replace(PHP_EOL, '', $tmpmaptext);

					$tmponeline = $map['onelinetext'][$this->config->get('config_language_id')];
					$tmponeline = str_replace('\n', '', $tmponeline);
					$tmponeline = str_replace(PHP_EOL, '', $tmponeline);

					$this->data['gmaps'][] = array(
						'mapalias'		=> $map['mapalias'],
						'onelinetext'	=> html_entity_decode($tmponeline, ENT_QUOTES, 'UTF-8'),
						'latlong'		=> $map['latlong'],
						'maptext'		=> html_entity_decode($tmpmaptext, ENT_QUOTES, 'UTF-8')
					);				
				
				}
				
			}

		}
		$this->data['gmap_showbox'] = $setting['showbox'];
		$this->data['gmap_maptype'] = $setting['maptype'];
		$this->data['gmap_boxtitle'] = $setting['boxtitle'][$this->config->get('config_language_id')];
		$this->data['gmap_width'] = $setting['width'];
		$this->data['gmap_height'] = $setting['height'];
		$this->data['gmap_zoom'] = $setting['zoom'];
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/google_maps.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/google_maps.tpl';
		} else {
			$this->template = 'default/template/module/google_maps.tpl';
		}
		$this->data['module_map'] = $module_map++;
		
		$this->render();
	}
}
?>