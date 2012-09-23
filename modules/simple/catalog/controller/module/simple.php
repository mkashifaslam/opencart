<?php  
class ControllerModuleSimple extends Controller {
	protected function index($setting) {
		$this->language->load('module/simple');
		
    	$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
    $this->document->addScript('http://userapi.com/js/api/openapi.js?49');
    	
		$this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/simple.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/simple.tpl';
		} else {
			$this->template = 'default/template/module/simple.tpl';
		}
		
		$this->render();
	}
	/*vk example
<!-- VK Widget -->
<div id="vk_groups" class="round-box glow" style="border: 1px solid #D92525;float:left;padding:5px;margin:0;">
  </div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 0, width: "200"}, 29055554);
</script>	

*/
}
?>
