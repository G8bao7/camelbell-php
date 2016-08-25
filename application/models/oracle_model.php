<?php 
class Oracle_model extends CI_Model{

	function insert($table,$data){		
		$this->db->insert($table, $data);
	}   

	function get_total_rows($table){
		$this->db->from($table);
		return $this->db->count_all_results();
	}


    
    function get_total_record($table){
        $query = $this->db->get($table);
	if ($query->num_rows() > 0)
	{
	    return $query->result_array();
	}
    }
    
    function get_total_record_paging($table,$limit,$offset){
        $query = $this->db->get($table,$limit,$offset);
	if ($query->num_rows() > 0)
	{
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
    
	
    function get_status_total_record($health=''){
        
        $this->db->select('*');
        $this->db->from('oracle_status ');
        if($health==1){
            $this->db->where("connect", 1);
        }
        
        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        !empty($_GET["connect"]) && $this->db->where("connect", $_GET["connect"]);
        !empty($_GET["session_total"]) && $this->db->where("session_total >", (int)$_GET["session_total"]);
        !empty($_GET["session_actives"]) && $this->db->where("session_actives >", (int)$_GET["session_actives"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
            $this->db->order_by('tags asc');
        }
        
        $query = $this->db->get();
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }
    
    function get_tablespace_total_record(){
        
        $this->db->select('*');
        $this->db->from('oracle_tablespace ');
        $this->db->where('create_time > date_sub(curdate(), interval 1 day) and 1=', 1);
       
        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
            #$this->db->order_by('avail_size/total_size asc');
            $this->db->order_by("used_rate", 'desc');
            #$this->db->order_by('avail_size asc');
        }
        
        $query = $this->db->get();
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }

    
    
    function get_total_host(){
        $query=$this->db->query("select * from oracle_status order by host;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    function get_total_application(){
        $query=$this->db->query("select application from oracle_status group by application order by application;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    function get_status_chart_record($server_id,$time){
        $query=$this->db->query("select * from oracle_status_history  where server_id=$server_id and YmdHi=$time limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
   
    function check_has_record($server_id,$time){
        $query=$this->db->query("select id from oracle_status_history where server_id=$server_id and YmdHi=$time");
        if ($query->num_rows() > 0)
        {
           return true; 
        }
        else{
            return false;
        }
    }
    
    function get_slowquery_total_rows(){
        $this->db->select('id');
        $this->db->from("oracle_slowquery s");
        return $this->db->count_all_results();
    }
 
    function get_slowquery_first_total_rows(){
        $this->db->select('id');
        $this->db->from("oracle_slowquery_summary s");
        return $this->db->count_all_results();
    }
 
    function get_awrreport_record(){
        $this->db->select('*');
        $this->db->from('oracle_awrreport');

        !empty($_GET["server_id"]) && $this->db->where("server_id = ", $_GET["server_id"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);

        $stime = !empty($_GET["stime"])? $_GET["stime"]: date('Y-m-d',time()-3600*24*3);
        $etime = !empty($_GET["etime"])? $_GET["etime"]: date('Y-m-d',time());
        $this->db->where("stat_date >=", $stime);
        $this->db->where("stat_date <=", $etime);

        $order = !empty($_GET["order"])? $_GET["order"]: 'stat_date';
        $order_type = !empty($_GET["order_type"])? $_GET["order_type"]: 'desc';
        $this->db->order_by($order,$order_type);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
    }

    function get_awrreport_chart_record($server_id, $begin_time, $end_time){
        $this->db->select('*');
        $this->db->from('oracle_awrreport');
	$this->db->where("server_id = ", $server_id);
	$this->db->where("stat_date >=", $begin_time);
	$this->db->where("stat_date <=", $end_time);
        $this->db->order_by("stat_date", "asc");
        $query = $this->db->get();
        if ($query->num_rows() > 0){
           return $query->result_array(); 
        }
    }
}

/* End of file oracle_model.php */
/* Location: ./application/models/oracle_model.php */
