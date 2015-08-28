<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Midia extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		init_painel();
		esta_logado();
		init_painel_at();
		$this->load->model('midia_models','midia');
		load_meta();
	}
	public function index()
	{
		
	}
	public function cadastrar(){
		$this->form_validation->set_rules('nome', 'Titulo', 'trim|required|ucfirst');
		$this->form_validation->set_rules('descricao', 'Desccrição', 'trim');
		if($this->form_validation->run()==TRUE){
			$upload= $this->midia->do_upload('arquivo');
			if (is_array($upload)&&$upload['file_name']!='') {
				$dados=elements(array('nome','descricao', 'alt'),$this->input->post());
				$dados['arquivo']=$upload['file_name'];
				$this->midia->do_insert($dados);
			} else {
				set_msg('msgerro', $upload, 'aviso');
				redirect(current_url());
			}
		}
		set_tema('titulo','Cadastro');
		set_tema('conteudo',load_modulo('midia','cadastrar'));
		load_template();
	}
	public function gerenciar(){
		esta_logado();
		set_tema('js',load_js(array('data-table','dataTables.bootstrap.min','table')),FALSE);
		set_tema('js',' <script type="text/javascript">
            $(function(){
                $(".confDelete").click(function(){
                    if(confirm("Deseja reamente deletar este registro?\nEsta operação não pode ser desfeita!"))return true;else return false;
                });
				$("input").click(function(){
					(this).select();
				});
            });
			
  

        </script>',FALSE);
		set_tema('titulo','Gerenciar');
		set_tema('sub','Gerenciar todos os midia');
		set_tema('conteudo',load_modulo('midia','gerenciar'));
		load_template();
	}
	public function editar(){
		esta_logado();
		$this->form_validation->set_rules('nome', 'Titulo', 'trim|required|ucwords');
		if($this->form_validation->run()==TRUE){
			$dados['nome']=$this->input->post('nome');
			$dados['descricao']=$this->input->post('descricao');
			$dados['alt']=$this->input->post('alt');	
			$this->midia->do_update($dados, array('id'=>$this->input->post('id')),FALSE);
			set_msg('msgok', 'Usuario editado com sucesso', 'sucesso');
			auditoria('Editar','Foto foi editada');
			redirect('midia/gerenciar');
		}
		
		set_tema('titulo','Editar');
		set_tema('conteudo',load_modulo('midia','editar'));
		load_template();
	}
	public function exclurir(){
		esta_logado();
		if (is_admin(TRUE)){
			$iduser=$this->uri->segment(3);
			if($iduser!=NULL){
			$query=$this->midia->get_byid($iduser);
				if($query->num_rows()==1){
					$query = $query->row();
					if($query->id != 1){
						$this->midia->do_delete(array('id'=>$query->id),FALSE,FALSE);
						auditoria("Exclusão",'Usuario "'.$query->nome.'" foi exclurido com sucesso');
						set_msg('msgok','Usuario '.$query->nome.' foi exclurido com sucesso','sucesso');
						redirect('midia/gerenciar');
						
					}else{
						auditoria("Tentativa de Exclusão",'Tentiva de exclusão no MegaUsuario');
						set_msg('msgerro','Usuario '.$query->nome.' não pode ser deletado','perigo');
						redirect('midia/gerenciar');
						
						
					}
				}else{
					set_msg('msgerro','Usuario não foi encontrado para exclusão','perigo');
					redirect('midia/gerenciar');
				}
			}else{
				set_msg('msgerro','Não foi definido um usuario para exclusão','aviso');
				redirect('midia/gerenciar');
			}
		} else {
			redirect('midia/gerenciar');
		}
		
	}

}

/* End of file midia.php */
/* Location: ./application/controllers/midia.php */