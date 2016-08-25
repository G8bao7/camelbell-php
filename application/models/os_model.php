<?php 
class Os_model extends CI_Model{
    
    protected $table='os_status';
    
	function get_total_rows($table){
		$this->db->from($table);
		return $this->db->count_all_results();
	}
    

    function get_status_total_record(){
        $this->db->select('*');
        $this->db->from('os_status');
       
        !empty($_GET["host"]) && $this->db->like("ip", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        $this->db->order_by('zabbix desc, create_time desc, ip asc');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			$result['datalist']=$query->result_array();
            $result['datacount']=$query->num_rows();
            return $result;
		}
	}
    


    
    function get_os_chart_record($host,$time){
        $query=$this->db->query("select * from os_status_history where ip='$host' and YmdHi=$time limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    function get_os_diskio_chart_record($host,$time){
        $query=$this->db->query("select * from os_diskio_history where ip='$host' and YmdHi=$time limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    function get_last_record($host){
        $query=$this->db->query("select * from os_status_history where ip='$host' order by id desc limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }

     function get_disk_record($host){
        $query=$this->db->query("select * from os_disk where ip='$host'; ");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    function get_total_host(){
        $this->db->from('os_status s');

        !empty($_GET["host"]) && $this->db->like("ip", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        $query=$this->db->get();
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

    function get_diskinfo_record($host){
        $query=$this->db->query("select * from os_disk where ip='$host';");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    function get_total_disk_record(){
        $this->db->select('s.ip,s.tags,s.create_time,d.device, d.mounted, d.total_size, d.used_size, d.avail_size, d.used_rate, d.file_system, d.create_time');
        $this->db->from('os_status s');
        $this->db->join('os_disk d', 'd.ip=s.ip', 'left');

        !empty($_GET["host"]) && $this->db->like("s.ip", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("s.tags", $_GET["tags"]);
        
        $order = !empty($_GET["order"])? $_GET["order"]: 'used_rate';
        $order_type = !empty($_GET["order_type"])? $_GET["order_type"]: 'desc';
	$this->db->order_by($order, $order_type);
	
        // $this->db->limit(3, 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
	{
	    return $query->result_array();
	}
    }

    
    function get_total_diskio_record(){
        $this->db->select('*');
        $this->db->from('os_diskio');

        !empty($_GET["host"]) && $this->db->like("ip", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
            $this->db->order_by('id asc');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0)
	{
	    $result['datalist']=$query->result_array();
            $result['datacount']=$query->num_rows();
            return $result;
	}
    }

    function check_has_record($host,$time){
        $query=$this->db->query("select id from os_status_history where ip='$host' and YmdHi=$time");
        if ($query->num_rows() > 0)
        {
           return true; 
        }
        else{
            return false;
        }
    }
    

}

/* End of file os_model.php */
/* Location: ./application/models/os_model.php */
