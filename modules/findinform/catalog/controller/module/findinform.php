<?php
class ControllerModuleFindinform extends Controller {
  public function captcha(){
    $this->load->library('captcha');
    $captcha = new Captcha();
    $this->session->data['captcha'] = $captcha->getCode();
    $captcha->showImage();
  }
	protected function index($setting) {
	  $this->document->addStyle('catalog/view/theme/default/stylesheet/simplemodal.css');
	  $this->document->addScript('catalog/view/javascript/findinform.js');
	  $this->document->addScript('catalog/view/javascript/jquery/jquery.simplemodal.js');
	  $this->document->addScript('catalog/view/javascript/jquery/jquery.hotkeys.js');
	  $this->language->load('module/findinform');
	  $this->data['entry_captcha'] = $this->language->get('entry_captcha');
	  $this->data['heading_title'] = $this->language->get('heading_title');
	  $this->data['info_message'] = $this->language->get('info_message');
	  if (isset($this->error['captcha'])){
	    $this->data['error_captcha'] = $this->error['captcha'];
	  }
	  else
	    {
	      $this->data['error_captcha'] = '';
	    }
	  if(isset($this->request->post['captcha'])){
	    $this->data['captcha'] = $this->request->post['captcha'];
	  }
	  else
	    {
	      $this->data['captcha'] = '';
	    }

	  if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/findinform.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/module/findinform.tpl';
	  } else {
	    $this->template = 'default/template/module/findinform.tpl';
	  }

	  $this->render();
	}
	public function send($setting){
  if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                        $this->language->load('mail/forgotten');

                        $password = substr(md5(rand()), 0, 7);

                        $this->model_affiliate_affiliate->editPassword($this->request->post['email'], $password);

                        $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

                        $message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
                        $message .= $this->language->get('text_password') . "\n\n";
                        $message .= $password;

                        $mail = new Mail();
                        $mail->protocol = $this->config->get('config_mail_protocol');
                        $mail->parameter = $this->config->get('config_mail_parameter');
                        $mail->hostname = $this->config->get('config_smtp_host');
                        $mail->username = $this->config->get('config_smtp_username');
                        $mail->password = $this->config->get('config_smtp_password');
                        $mail->port = $this->config->get('config_smtp_port');
                        $mail->timeout = $this->config->get('config_smtp_timeout');
                        $mail->setTo($this->request->post['email']);
                        $mail->setFrom($this->config->get('config_email'));
                        $mail->setSender($this->config->get('config_name'));
                        $mail->setSubject($subject);
                        $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                        $mail->send();

                        $this->session->data['success'] = $this->language->get('text_success');

                        $this->redirect($this->url->link('affiliate/login', '', 'SSL'));

       	         }
	else{
		
	}
	$this->response->setOutput(json_encode($this->data));
}
	private function validate(){
	  if(empty($this->session->data['captcha'])||
	     ($this->session->data['captcha'] != $this->request->post['captcha'])){
	    $this->error['captcha'] = $this->language->get('error_captcha');
	    $this->error['warning'] = $this->language->get('error_captcha');
	  }
	}
}
?>
