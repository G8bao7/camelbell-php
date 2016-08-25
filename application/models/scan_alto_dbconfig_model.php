<?php 
class scan_alto_dbconfig_model extends CI_Model{

    protected $table='tools_scan_alto_dbconfig';

    function get_total_rows(){
	$this->db->from($table);
	return $this->db->count_all_results();
    }
    
    function get_total_record(){
        $query = $this->db->get($table);
	if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }
   
    function get_record_by_key($find_key){

	$this->db->select("env_name, app_name, count(*) as fnum, group_concat(remark SEPARATOR '\n') as remark");
	$this->db->from($this->table);
	$this->db->where("find_key", $find_key);
        $this->db->group_by('env_name');
        $this->db->group_by('app_name');
        $this->db->order_by('app_name',' asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }

}

/* End of file scan_alto_dbconfig_model.php */
/* Location: ./application/models/scan_alto_dbconfig_model.php */
