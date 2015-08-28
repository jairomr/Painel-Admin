<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		init_painel();
		esta_logado();
		init_painel_at();
		$this->load->model('auditoria_models','auditoria');
		load_meta();
	}
	public function index()
	{
		$this->logs();
	}
	public function logs(){
		
		set_tema('js',load_js(array('data-table','dataTables.bootstrap.min','auditoria')),FALSE);
		set_tema('js',' <script type="text/javascript">
            $(function(){
                $(".confDelete").click(function(){
                    if(confirm("Deseja reamente deletar este registro?\nEsta operação não pode ser desfeita!"))return true;else return false;
                });
            });
        </script>',FALSE);
		set_tema('titulo','Auditoria');
		set_tema('sub','Auditoria do sistema');
		set_tema('conteudo',load_modulo('auditoria','logs'));
		load_template();
	}
	
}

/* End of file auditoria.php */
/* Location: ./application/controllers/auditoria.php */