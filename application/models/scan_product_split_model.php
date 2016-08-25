<?php 
class scan_product_split_model extends CI_Model{

    protected $table='tools_scan_product_split';

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
   
    function get_record_by_key($uniq_id){
	$this->db->where("uniq_id", $uniq_id);
        $this->db->order_by('tb_name',' asc');
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }

}

/* End of file scan_product_split_model.php */
/* Location: ./application/models/scan_product_split_model.php */
