<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auditoria_models extends CI_Model {
	public function do_insert($d=NULL){
		if ($d!=NULL) {
			$this->db->insert('auditoria', $d);
		}
		
	}//.do_login
	public function get_all($l=0){
		if($l>0) $this->db->limit($l);
		return $this->db->get('auditoria');		
	}//.get_bylogin
	public function get_byid($id=NULL){
		if ($id != NULL) {
			$this->db->where('id', $id);
			$this->db->limit(1);
			return $this->db->get('auditoria');
		} else {
			return FALSE;
		}
		
	}//.get_bylogin

}//.class

/* End of file auditoria_models.php */
/* Location: ./application/models/auditoria_models.php */ 