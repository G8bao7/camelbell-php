<?php 
class Tools_model extends CI_Model{

    function insert($table,$data){		
	$this->db->insert($table, $data);
    }

    function get_total_rows($table){
	$this->db->from($table);
	return $this->db->count_all_results();
    }
    
    function get_total_record($table){
        $query = $this->db->get($table);
	if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }
    
    function get_total_record_paging($table,$limit,$offset){
        $query = $this->db->get($table,$limit,$offset);
	if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }
    
    function get_total_record_sql($sql){
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0){
	    $result['datalist']=$query->result_array();
            $result['datacount']=$query->num_rows();
            return $result;
	}
    }

}

/* End of file tools_model.php */
/* Location: ./application/models/tools_model.php */
