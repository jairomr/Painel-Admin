<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_models extends CI_Model {
	public function do_insert($d=NULL,$r=TRUE){
		if ($d!=NULL) {
			$this->db->insert('usuarios', $d);
			if($this->db->affected_rows()>0){
				set_msg('msgok','Alteração feita com sucesso', 'sucesso');
			}else{
				set_msg('msgerro','Erro ao inserir, contate o desenvolvedor!','perigo');
			}
			if($r) redirect(current_url());
		}else{
			set_msg('msgerro','Erro ao salvar','perigo');
		}
	}

	public function do_update($d=NULL, $c=NULL, $r=TRUE){
		if ($d != NULL && is_array($c)) {
			$this->db->update('usuarios', $d,$c);
			if($this->db->affected_rows()>0){
				set_msg('msgok','Alteração feita com sucesso', 'sucesso', '');
			}else{
				auditoria('Erro update','Erro ocorrido ao chamar a função do_update do Models Usuarios');
				redirect(current_url());
				
			}
			if($r) redirect(current_url());
		} 		
	}
	public function do_delete($d=NULL, $m=TRUE, $r=TRUE){
		if ($d != NULL && is_array($d)) {
			$this->db->delete('usuarios', $d);
			if($this->db->affected_rows()>0){
				if($m) set_msg('msgok','Dados deletado com sucesso.', 'sucesso', '');
			}else{
				auditoria('Erro delete','Erro ocorrido ao chamar a função do_delete do Models Usuarios');
				redirect(current_url());
			}
			if($r) redirect(current_url());
		}else{auditoria('Erro delete','Erro ocorrido ao chamar a função do_delete do Models Usuarios checar dados passado pelo usuario');}		
	}

	public function do_login($usuario=NULL, $senha=NULL){
		if ($usuario && $senha) {
			$this->db->where('login', $usuario);
			$this->db->where('senha', $senha);
			$this->db->where('ativo', 1);
			$query= $this->db->get('usuarios');
			if ($query->num_rows == 1) {
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}//.do_login
	public function get_all(){
		return $this->db->get('usuarios');
	}
	
	public function get_bylogin($login=NULL){
		if ($login != NULL) {
			$this->db->where('login', $login);
			$this->db->limit(1);
			return $this->db->get('usuarios');
		} else {
			return FALSE;
		}
		
	}//.get_bylogin
	public function get_byemail($email=NULL){
		if ($email != NULL) {
			$this->db->where('email', $email);
			$this->db->limit(1);
			return $this->db->get('usuarios');
		} else {
			return FALSE;
		}
		
	}//.get_bylogin
	public function get_byid($id=NULL){
		if ($id != NULL) {
			$this->db->where('id', $id);
			$this->db->limit(1);
			return $this->db->get('usuarios');
		} else {
			return FALSE;
		}
		
	}//.get_bylogin

}//.class

/* End of file usuarios_models.php */
/* Location: ./application/models/usuarios_models.php */ 