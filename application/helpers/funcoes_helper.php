<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//Carrega os mudolos
	function load_modulo($modulo=NULL, $tela=NULL, $diretorio='painel')
	{
		$CI =& get_instance();
		if ($modulo!=NULL) {
			return $CI->load->view("$diretorio/$modulo", array('tela'=>$tela),TRUE);
		} else {
			return FALSE;}
	}//.load_modulo
	//Set valores no array tema
	function set_tema($prop,$valor,$replace=TRUE){
		$CI =& get_instance();
		$CI->load->library('sistema');
		if ($replace) {
			$CI->sistema->tema[$prop] = $valor;
		}else{
			if (!isset($CI->sistema->tema[$prop])) {$CI->sistema->tema[$prop] = $valor;	} 
			else { $CI->sistema->tema[$prop] .= $valor;}//.isset(CI)
		}//.if replace
	}//.set_tema

	//Obtem o tema
	function get_tema(){
		$CI =& get_instance();
	 	$CI->load->library('sistema'); 
	 	return $CI->sistema->tema;
	 }
	//Carrega a template passado o array $tema
	function load_template(){
		$CI =& get_instance();
		$CI->load->library('sistema');
		$CI->parser->parse($CI->sistema->tema['template'],get_tema());
	}
	//Carregar CSS
	function load_css($arquivo=NULL, $pasta='css', $media='all'){
    if($arquivo!=NULL):
       $CI =& get_instance();
       $CI->load->helper('url');
       $retorno='';
       if (is_array($arquivo)) {
          	foreach ($arquivo as $css) {
          		$retorno.='<link rel="stylesheet" href="'.base_url("$pasta/$css.css").'" media="'.$media.'"/>'."\n";
          	}
          } else {
          	$retorno.='<link rel="stylesheet" href="'.base_url("$pasta/$arquivo.css").'" media="'.$media.'"/>'."\n";
          }
             
    endif;
    return $retorno;
	}//.load_css


	//Carregar JS
	function load_js($arquivo=NULL, $pasta='js', $remoto=FALSE){
    if($arquivo!=NULL):
       $CI =& get_instance();
       $CI->load->helper('url');
       $retorno='';
       if (is_array($arquivo)) {
          	foreach ($arquivo as $js) {
          		if ($remoto) {
          			$retorno.= '<script src="'.$js.'"></script>'."\n";
          		} else {
          			$retorno.= '<script src="'.base_url("$pasta/$js.js").'"></script>'."\n";
          		}
          	}
          } else {
          	if ($remoto) {
          			$retorno.= '<script src="'.$arquivo.'"></script>'."\n";
          		} else {
          			$retorno.= '<script src="'.base_url("$pasta/$arquivo.js").'"></script>'."\n";
          		}
          }
             
    endif;
    return $retorno;
	}//.load_js
	function load_meta(){
		$CI =& get_instance();
		$CI->load->library('sistema');
		set_tema('author','Jairo Matos');
		set_tema('description','Sisitema do Painel Admin');
	}//.load_meta

	//mostra erros de validaçao
	function erros_validacao(){
		if (validation_errors()) echo '<div class="alert alert-danger"><strong>Erro!</strong>'.validation_errors().'</div>';
		}//.erros_validacao

	//Verifica o user esta logado
	function esta_logado($redir=TRUE){
		$CI =& get_instance();
		$CI->load->library('session');
		$user_status = $CI->session->userdata('user_logado');
		if (!isset($user_status)||$user_status!=TRUE) {
			if ($redir) {
				$CI->session->set_userdata(array('redir_para'=>current_url()));
				set_msg('errologin','Acesso restrito, faça login antes de prosseguir','aviso');
				redirect('usuarios/login');
			} else {
				return FALSE;
			}
		} else {
			return TRUE;
		}
	}//.esta_logado
	// Verificar se o usuario é administrador
	function is_admin($msg=FALSE){
		$CI =& get_instance();
		$admin=$CI->session->userdata('user_admin');
		if (!isset($admin)||$admin!=TRUE) {
			if ($msg) set_msg('msgerro','Seu usuario não tem permição para executar esta operação.','aviso');
			return FALSE;
		} else {
			return TRUE;
		}
	}//.is_admin
	function login_name(){
		$CI =& get_instance();
		$login=$CI->session->userdata('user_login');
		return ' '.strtoupper($login);
	}

	//Define a mensagem flash
	function set_msg($id='infomsg',$msg=NULL,$tipo='info',$titulo=NULL){
		$CI =& get_instance();
		switch ($tipo) {
			case 'info':
				if ($titulo==NULL) {
					$CI->session->set_flashdata($id, '<div class="alert alert-info">
                    <strong>Info! </strong>'.$msg.'
                </div>');
				} else {
					$CI->session->set_flashdata($id, '<div class="alert alert-info">
                    <strong>'.$titulo.' </strong>'.$msg.'
                </div>');
				}
				break;
			case 'sucesso':
				if ($titulo==NULL) {
					$CI->session->set_flashdata($id, '<div class="alert alert-success">
                    <strong>Sucesso! </strong>'.$msg.'
                </div>');
				} else {
					$CI->session->set_flashdata($id, '<div class="alert alert-success">
                    <strong>'.$titulo.' </strong>'.$msg.'
                </div>');
				}
				break;
			case 'perigo':
				if ($titulo==NULL) {
					$CI->session->set_flashdata($id, '<div class="alert alert-danger">
                    <strong>Perigo! </strong>'.$msg.'
                </div>');
				} else {
					$CI->session->set_flashdata($id, '<div class="alert alert-danger">
                    <strong>'.$titulo.' </strong>'.$msg.'
                </div>');
				}
				break;
			case 'aviso':
				if ($titulo==NULL) {
					$CI->session->set_flashdata($id,'<div class="alert alert-warning">
                    <strong>Aviso! </strong>'.$msg.'
                </div>');
				} else {
					$CI->session->set_flashdata($id, '<div class="alert alert-warning">
                    <strong>'.$titulo.' </strong>'.$msg.'
                </div>');
				}
				break;
			default:
				if ($titulo==NULL) {
					$CI->session->set_flashdata($id, '<div class="alert alert-info">
                    <strong>Info! </strong>'.$msg.'
                </div>');
				} else {
					$CI->session->set_flashdata($id, '<div class="alert alert-info">
                    <strong>'.$titulo.' </strong>'.$msg.'
                </div>');
				}
				break;
		}
	}//.set_msg

	function auditoria($op,$ob,$q=TRUE){
		$CI =& get_instance();
		$CI->load->library('session');
		$CI->load->model('auditoria_models', 'auditoria');
		if($q){
			$query=$CI->db->last_query();
		}else{
			$query='';
		}
		if(esta_logado(FALSE)){
			$user_login=$CI->session->userdata('user_login');
			$ip=$CI->session->userdata('user_ip');
		}else{
			$user_login='Desconecido';
			$ip=getenv("REMOTE_ADDR");
			}
		
		$dados=array(
			'usuario'=>$user_login,
			'operacao'=>$op,
			'query'=>$query,
			'observacao'=>$ob,
			'ip'=>$ip
			);
		$CI->auditoria->do_insert($dados);
	}//.auditoria

	//Verifica se tem mensagem
	function get_msg($id=NULL, $printar=TRUE){
		$CI =& get_instance();
		if ($CI->session->flashdata($id)){
			if ($printar) {
				echo $CI->session->flashdata($id);
			} else {
				return $CI->session->flashdata($id);
			}
			
		} else {
			return FALSE;
		}
		

	}//.get_msg
	

	//Inicialisa css e js do painel
	function init_painel_at(){
		$CI =& get_instance();
		$CI->load->library('sistema');
		set_tema('css',load_css('sb-admin'),FALSE);
		#set_tema('css', load_css('morris', 'css/plugins'),FALSE);
		set_tema('css', load_css('app'), FALSE);
		#set_tema('js', load_js(array('raphael.min','morris.min','morris-data'),'js/plugins/morris'),FALSE);
	}//.init_template
	
	//Inicializa o painel
	function init_painel(){
		$CI =& get_instance();
		$CI->load->library(array('sistema','session','form_validation'));
		$CI->load->helper(array('form','url','array','text'));
		$CI->load->model('usuarios_models','usuarios');
		set_tema('titulo_padrao','JDev');
		set_tema('rodape','<footer style="
			margin-top: 1%;
	width:100%;
	text-align: center;
	color:#fff;">
			<h6>&copy Todos os direitos reservado a JDev</h6>
	</footer>');
		set_tema('template','painel_view');
		set_tema('css', load_css(array('bootstrap.min')),FALSE);
		set_tema('css', load_css('font-awesome.min', 'font-awesome/css'),FALSE);
		set_tema('js', load_js(array('jquery','bootstrap.min')),FALSE);
	}//.init_painel
 

 	function trumb($i=NULL, $l=100, $a=75, $g=TRUE, $alt=''){
 		$CI =& get_instance();
 		$CI->load->helper('file');
 		$trumb=$l.'x'.$a.$i;
 		$trumbinfo=get_file_info('./uploads/trumbs/'.$trumb);
 		if($trumbinfo!=FALSE){
 			$r=base_url('uploads/trumbs/'.$trumb);
 		}else{
 			$CI->load->library('image_lib');
 			$c['image_library']='gd2';
 			$c['source_image']='./uploads/'.$i;
 			$c['new_image']='./uploads/trumbs/'.$trumb;
 			$c['maintain_ratio']=TRUE;
 			$c['width']=$l;
 			$c['height']=$a;
 			$CI->image_lib->initialize($c);
 			if($CI->image_lib->resize()){
 				$CI->image_lib->clear();
 				$r=base_url('uploads/trumbs/'.$trumb);
 			}else{
 				$r=FALSE;
 			}
 		}
 		if($g&&$r!=FALSE)$r='<img src="'.$r.'" alt="'.$alt.'">';
 		return $r;
 	}