<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class JD_Sistema
{
	protected $ci;
	public $tema = array();
	public function __construct()
	{
        $this->ci =& get_instance();
        $this->ci->load->helper('funcoes');
        $this->ci->load->helper('menu');
        $this->ci->load->helper('bootstrap');

	}

	//Enviar email
	public function enviar_email($to, $subject, $message, $formato='html' ){
		$this->ci->load->library('email');
		$config['mailtype'] = $formato;
		$this->ci->email->initialize($config);
		$this->ci->email->from('admi@jdev.com', 'Admistração');
		$this->ci->email->to($to);
		$this->ci->email->subject($subject);
		$this->ci->email->message($message);
		if($this->ci->email->send()){
			return TRUE;

		}else{
			return $this->ci->email->print_debugger();
		}
	}//.eviar_email

	

}

/* End of file sistema.php */
/* Location: ./application/libraries/sistema.php */
