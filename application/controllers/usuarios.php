<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		init_painel();
		if(esta_logado(FALSE)){init_painel_at();}
		load_meta();
	}
	public function index()
	{
		redirect('usuarios/gerenciar');
	}
	public function login()
	{
		$this->form_validation->set_rules('usuario', 'Usuario', 'trim|required|min_length[3]|strtolower');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|min_length[3]|strtolower');
		if ($this->form_validation->run()==TRUE){
			$usuario = $this->input->post('usuario', TRUE);
			$senha = md5($this->input->post('senha', TRUE));
			if ($this->usuarios->do_login($usuario, $senha)==TRUE) {
				$query = $this->usuarios->get_bylogin($usuario)->row();
				$dados= array(
					'user_id'     => $query->id,
					'user_nome'   => $query->nome,
					'user_login'  => $query->login,
					'user_admin'  => $query->adm,
					'user_ip'     => getenv("REMOTE_ADDR"),
					'user_logado' => TRUE
				);
				$this->session->set_userdata($dados);
				$redir= $this->input->post('redir');
				auditoria('Login','Login efetudado com sucesso');
				if($redir!=''){
					redirect($redir);
				}else{redirect('painel');}
			} else {
				$query=$this->usuarios->get_bylogin($usuario)->row(); 
				if(empty($query)){
					set_msg('errologin','Usuario inexistemte','aviso');
				}elseif($query->senha != $senha){
					set_msg('errologin','Senha incorreta','aviso');
				}elseif($query->ativo == 0){
					set_msg('errologin','Usuario inativo','aviso');
				}else{
					set_msg('errologin','Erro desconhecido contate o Desenvolvedor!!!!','aviso');
				}

			}
			redirect('usuarios/login');
			
		}
		
		set_tema('titulo','Login');
		set_tema('conteudo',load_modulo('usuarios','login'));
		set_tema('rodape','');
		load_template();
	}//.login

	public function logoff(){
		auditoria('Logoff','Logoff feito com sucesso');
		$this->session->unset_userdata(array('user_id'=>'','user_nome'=>'','user_admin'=>'','user_logado'=>''));
		$this->session->sess_destroy();
		$this->session->sess_create();
		set_msg('logoffok','Logoff efetudado com sucesso', 'sucesso');
		redirect('usuarios/login');
	}//.logoof

	public function nova_senha(){
		$this->form_validation->set_rules('email', 'E-Mail', 'trim|required|valid_email|strtolower');
		if ($this->form_validation->run()==TRUE){
			$email = $this->input->post('email');
			$query = $this->usuarios->get_byemail($email);
			if ($query->num_rows()==1) {
				$nova_senha=substr(str_shuffle('qwertyuiopasdfghjklzxcvbnm1234567890-+!#&*'),0,8);
				$mensage='<p>Você solicito uma nova senha para o painel administração do site apartir de agora use a seguinte senha <strong>'.$nova_senha.'</strong></p>
				<p>Mude esta senha para uma segura e de sua perefencia</p>';
				if($this->sistema->enviar_email($email,'Nova senha',$mensage)){
					$dados['senha']=md5($nova_senha);
					$this->usuarios->do_update($dados, array('email'=>$email),FALSE);
					set_msg('msgok', 'Uma nova senha foi enviado ao seu email', 'sucesso');
					auditoria('Mudar de senha','Uma nova senha foi enviado ao email do usuario');
					redirect('usuarios/nova_senha');
				}else{
					set_msg('msgerro', 'Ocorreu um erro ao enviar a senha contate o adminstrador', 'aviso');
					auditoria('Mudar de senha ERRO','ERRO ao mudar senha verifique o Controllers/usuarios linha 87');
					redirect('usuarios/nova_senha');
					}
			} else {
				set_msg('msgerro', 'Este email não existe em nosso banco de dados', 'aviso');
				auditoria('Mudar de senha ERRO email',"Email '".$email."' invalido ");
				redirect('usuarios/nova_senha');
			}
			

		}
		set_tema('titulo','Nova Senha');
		set_tema('conteudo',load_modulo('usuarios','nova_senha'));
		set_tema('rodape','');
		load_template();
	}//.Nova_senha
	public function cadastro(){
		esta_logado();
		$this->form_validation->set_message('is_unique','Este %s já esta cadastrado');
		$this->form_validation->set_message('matches','O campo %s esta diferente do campo %s');
		$this->form_validation->set_rules('nome', 'Nome', 'trim|required|ucwords');
		$this->form_validation->set_rules('login', 'Login', 'trim|required|min_length[3]|is_unique[usuarios.login]|strtolower');
		$this->form_validation->set_rules('email', 'E-Mail', 'trim|required|valid_email|is_unique[usuarios.email]|strtolower');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|min_length[6]|strtolower');
		$this->form_validation->set_rules('senha2', 'Repita Senha', 'trim|required|min_length[6]|matches[senha]|strtolower');
		if($this->form_validation->run()==TRUE){
			$dados=elements(array('nome','login','email'),$this->input->post());
			$dados['senha']=md5($this->input->post('senha'));
			if(is_admin())$dados['adm']=($this->input->post('adm')==1) ? 1 : 0 ;
			auditoria('Cadastrar','Novo usuario foi cadastrado');
			$this->usuarios->do_insert($dados);
		}
		set_tema('titulo','Cadastro');
		set_tema('conteudo',load_modulo('usuarios','cadastro'));
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
            });
        </script>',FALSE);
		set_tema('titulo','Gerenciar');
		set_tema('sub','Gerenciar todos os usuarios');
		set_tema('conteudo',load_modulo('usuarios','gerenciar'));
		load_template();
	}
	public function alterar_senha(){
		esta_logado();
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|min_length[6]|strtolower');
		$this->form_validation->set_rules('senha2', 'Repita Senha', 'trim|required|min_length[6]|matches[senha]|strtolower');
		if($this->form_validation->run()==TRUE){
			$user=$this->input->post('login');
			$dados['senha']=md5($this->input->post('senha'));
			$this->usuarios->do_update($dados, array('id'=>$this->input->post('idusuario')),FALSE);
			set_msg('msgok', 'Senha alterada com sucesso', 'sucesso');
			auditoria('Senha alterada','Senha do usuario "'.$user.'" foi alterada');
			redirect('usuarios/gerenciar');
		}
		set_tema('titulo','Alterar Senha');
		set_tema('conteudo',load_modulo('usuarios','alterar_senha'));
		load_template();
	}
	public function editar(){
		esta_logado();
		$this->form_validation->set_rules('nome', 'Nome', 'trim|required|ucwords');
		if($this->form_validation->run()==TRUE){
			$user=$this->input->post('login');
			$dados['nome']=$this->input->post('nome');
			$dados['ativo']=($this->input->post('ativo')==1) ? 1 : 0 ;
			if(is_admin())$dados['adm']=($this->input->post('adm')==1) ? 1 : 0 ;
			$this->usuarios->do_update($dados, array('id'=>$this->input->post('idusuario')),FALSE);
			set_msg('msgok', 'Usuario editado com sucesso', 'sucesso');
			$m='Usuario "'.$user.'" foi editado';
			auditoria('Editar',$m);
			redirect('usuarios/gerenciar');
		}
		
		set_tema('titulo','Editar');
		set_tema('conteudo',load_modulo('usuarios','editar'));
		load_template();
	}
	public function exclurir(){
		esta_logado();
		if (is_admin(TRUE)){
			$iduser=$this->uri->segment(3);
			if($iduser!=NULL){
			$query=$this->usuarios->get_byid($iduser);
				if($query->num_rows()==1){
					$query = $query->row();
					if($query->id != 1){
						$this->usuarios->do_delete(array('id'=>$query->id),FALSE,FALSE);
						auditoria("Exclusão",'Usuario "'.$query->nome.'" foi exclurido com sucesso');
						set_msg('msgok','Usuario '.$query->nome.' foi exclurido com sucesso','sucesso');
						redirect('usuarios/gerenciar');
						
					}else{
						auditoria("Tentativa de Exclusão",'Tentiva de exclusão no MegaUsuario');
						set_msg('msgerro','Usuario '.$query->nome.' não pode ser deletado','perigo');
						redirect('usuarios/gerenciar');
						
						
					}
				}else{
					set_msg('msgerro','Usuario não foi encontrado para exclusão','perigo');
					redirect('usuarios/gerenciar');
				}
			}else{
				set_msg('msgerro','Não foi definido um usuario para exclusão','aviso');
				redirect('usuarios/gerenciar');
			}
		} else {
			redirect('usuarios/gerenciar');
		}
		
	}

}

/* End of file usuarios.php */
/* Location: ./application/controllers/usuarios.php */