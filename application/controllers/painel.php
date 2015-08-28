<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Painel extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		init_painel();
		init_painel_at();
		load_meta();

	}
	public function index()
	{
		$this->inicio();
	}
	public function inicio()
	{
		if(esta_logado(FALSE)):
			set_tema('titulo','Início');
			set_tema('conteudo','<h1>teste<h1>');
			set_tema('template','painel_view');
			load_template();

		else:
			set_msg('errologin','Acesso restrito, faça login antes de prosseguir','aviso');	
			redirect('usuarios/login');
		endif;
	}

}

/* End of file painel.php */
/* Location: ./application/controllers/painel.php */