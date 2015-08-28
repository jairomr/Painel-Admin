<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Midia_models extends CI_Model {
	public function do_insert($d=NULL,$r=TRUE){
		if ($d!=NULL) {
			$this->db->insert('midia', $d);
			if($this->db->affected_rows()>0){
				auditoria('Upload de arquivo','Um arquivo foi upado ao sistema');
				set_msg('msgok','Alteração feita com sucesso', 'sucesso');
			}else{
				set_msg('msgerro','Erro ao inserir, contate o desenvolvedor!','perigo');
			}
			if($r) redirect(current_url());
		}else{
			set_msg('msgerro','Erro ao salvar','perigo');
		}
	}

	public function do_upload($d){
		$c['upload_path']='./uploads/';
		$c['allowed_types']='gif|jpg|png';
		$this->load->library('upload', $c);
		if ($this->upload->do_upload($d)){
			return $this->upload->data();
		}
		else{
			return $this->upload->display_errors();
			
		}
	}
	public function get_all(){
		return $this->db->get('midia');
	}
	public function get_byid($id=NULL){
		if ($id != NULL) {
			$this->db->where('id', $id);
			$this->db->limit(1);
			return $this->db->get('midia');
		} else {
			return FALSE;
		}
	}
	public function do_update($d=NULL, $c=NULL, $r=TRUE){
		if ($d != NULL && is_array($c)) {
			$this->db->update('midia', $d,$c);
			if($this->db->affected_rows()>0){
				set_msg('msgok','Alteração feita com sucesso', 'sucesso', '');
			}else{
				auditoria('Erro update da midia','Erro ocorrido ao chamar a função do_update do Models Usuarios');
				redirect(current_url());
				
			}
			if($r) redirect(current_url());
		} 		
	}
}//.class

/* End of file midia_models.php */
/* Location: ./application/models/midia_models.php */ 