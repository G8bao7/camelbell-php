<?php 
class MySQL_model extends CI_Model{

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
        if ($query->num_rows() > 0)
		{
			$result['datalist']=$query->result_array();
            $result['datacount']=$query->num_rows();
            return $result;
		}
    }
    
	
    function get_status_total_record($health=0){
        
        $this->db->select('*');
        $this->db->from('mysql_status');

        if($health==1){
            $this->db->where("connect", 1);
        }

        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);

        !empty($_GET["connect"]) && $this->db->where("connect", $_GET["connect"]);
        !empty($_GET["threads_connected"]) && $this->db->where("threads_connected >", (int)$_GET["threads_connected"]);
        !empty($_GET["threads_running"]) && $this->db->where("threads_running >", (int)$_GET["threads_running"]);
        
	$this->db->order_by('connect',' asc');
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        else{
            $this->db->order_by('tags',' asc');
            $this->db->order_by('role',' asc');
            $this->db->order_by('host',' asc');
        }
        
        $query = $this->db->get();

        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }
    
    function get_process_total_record(){
        
        $this->db->select('process.*,servers.host as server,servers.port,application.display_name application');
        $this->db->from('mysql_process process');
        $this->db->join('db_servers_mysql servers', 'process.server_id=servers.id', 'left');
        $this->db->join('db_application application', 'servers.application_id=application.id', 'left');
        
        !empty($_GET["application_id"]) && $this->db->where("process.application_id", $_GET["application_id"]);
        !empty($_GET["server_id"]) && $this->db->where("process.server_id", $_GET["server_id"]);
        if(!empty($_GET["sleep"]) && $_GET["sleep"]=1){
            $this->db->where("process.command","Sleep");
        }
        else{
            $this->db->where("process.command <>","Sleep");
			$this->db->where("process.status <>","");
        }

        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }
    
    function get_replication_total_record(){
        
        $this->db->select('repl.*,servers.replicate_ip, servers.host,servers.port,servers.tags');
        $this->db->from('mysql_replication repl');
        $this->db->join('db_servers_mysql servers', 'repl.server_id=servers.id', 'left');
        
        !empty($_GET["host"]) && $this->db->like("repl.host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("repl.tags", $_GET["tags"]);

        if(!empty($_GET["role"]) ){
            $this->db->where($_GET["role"], 1);
        }
        !empty($_GET["delay"]) && $this->db->where("delay >", (int)$_GET["delay"]);
        if(!empty($_GET["order"]) && !empty($_GET["order_type"])){
            $this->db->order_by($_GET["order"],$_GET["order_type"]);
        }
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }
    
    function get_bigtable_total_record(){
        
        $this->db->select('*');
        $this->db->from('mysql_bigtable');
        
        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        $this->db->order_by('table_size','desc');
        #$this->db->order_by('host','desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
    }
    
    function get_total_databases(){
        $this->db->select('d.db_ip, d.db_port, d.db_name, d.tb_count, d.data_size_m, d.upd_time as create_time');
        $this->db->from('mysql_databases AS d');
        !empty($_GET["host"]) && $this->db->like("db_ip", $_GET["host"]);
        !empty($_GET["db_name"]) && $this->db->like("db_name", $_GET["db_name"]);
        if (empty($_GET["order"])){ 
	    $this->db->order_by('data_size_m','desc');
	}else{
	    $this->db->order_by($_GET["order"],'desc');
	}
        $query = $this->db->get();
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }
    
    function get_total_databases2(){
        $this->db->select(' d.db_ip as host, d.db_port as port, d.db_name, COUNT(data_len) AS table_count, SUM(data_len+index_len+0) AS total_size, MAX(t.upd_time) AS last_time');
        $this->db->from('mysql_databases AS d');
        $this->db->join("mysql_tables AS t", "d.db_name = t.db_name",'left');
        !empty($_GET["host"]) && $this->db->like("d.db_ip", $_GET["host"]);
        !empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        !empty($_GET["db_name"]) && $this->db->like("db_name", $_GET["db_name"]);
        
        $this->db->group_by('d.db_name');
        $this->db->order_by('last_time','desc');
        $query = $this->db->get();
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
    }
    
    function get_dailystat_record(){
        
        $this->db->select('*');
        $this->db->from('daily_mysql');
       
	!empty($_GET["server_id"]) && $this->db->where("server_id = ", $_GET["server_id"]);
	!empty($_GET["tags"]) && $this->db->like("tags", $_GET["tags"]);
        
        $stime = !empty($_GET["stime"])? date('Y-m-d',strtotime($_GET["stime"])): date('Y-m-d',time()-3600*24*1);
        $etime = !empty($_GET["etime"])? date('Y-m-d',strtotime($_GET["etime"])): date('Y-m-d',time());
        $stime = !empty($_GET["stime"])? $_GET["stime"]: date('Y-m-d',time()-3600*24*1);
        $etime = !empty($_GET["etime"])? $_GET["etime"]: date('Y-m-d',time());
        $this->db->where("stat_date >=", $stime);
        $this->db->where("stat_date <=", $etime);
	
	/*
        $stime = date('Y-m-d',time()-3600*24*1);
        $this->db->where("stat_date =", $stime);
	*/
        
        $order = !empty($_GET["order"])? $_GET["order"]: 'tps_avg';
        $order_type = !empty($_GET["order_type"])? $_GET["order_type"]: 'desc';
        $this->db->order_by($order,$order_type);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
	{
	    return $query->result_array();
	}
    }
    
    
    function get_slowquery_total_rows($server_id){
	if($server_id && $server_id!=0){
	    $ext = ' and b.serverid_max='.$server_id;
        }
        else{
            $ext='';
        }
        
	$this->db->select('*');
        $this->db->from("mysql_slow_query_review a");
        $this->db->join("mysql_slow_query_review_history b", "a.checksum=b.checksum $ext ",'');
	return $this->db->count_all_results();
    }
    
 
	
    function get_slowquery_total_record($limit,$offset,$server_id){
        if($server_id && $server_id!=0){
            $ext = ' and b.serverid_max='.$server_id;
        }
        else{
            $ext='';
        }
        
        #$this->db->select('a.checksum,a.fingerprint,a.sample,a.first_seen,a.last_seen, b.serverid_max,b.hostname_max,b.db_max,b.user_max,b.ts_min,b.ts_max,sum(b.ts_cnt) ts_cnt, sum(b.Query_time_sum)/sum(b.ts_cnt) Query_time_avg, max(b.Query_time_max) Query_time_max, min(b.Query_time_min) Query_time_min,b.Query_time_sum Query_time_sum, max(b.Lock_time_max) Lock_time_max, min(b.Lock_time_min) Lock_time_min,sum(b.Lock_time_sum) Lock_time_sum,SUM(b.Rows_examined_sum)/SUM(b.ts_cnt) Rows_examined_avg, MAX(b.Rows_examined_max) Rows_examined_max, MIN(b.Rows_examined_min) Rows_examined_min');
        $this->db->select('a.checksum,a.fingerprint,a.sample,a.first_seen,a.last_seen, b.serverid_max,b.hostname_max,b.db_max,b.user_max,b.ts_min,b.ts_max,sum(b.ts_cnt) ts_cnt_sum,b.ts_cnt, sum(b.Query_time_sum) as Query_time_sum, Query_time_pct_95 Query_time_avg, max(b.Query_time_max) Query_time_max, min(b.Query_time_min) Query_time_min, sum(b.Lock_time_sum) Lock_time_sum, Lock_time_pct_95 Lock_time_avg, max(b.Lock_time_max) Lock_time_max, min(b.Lock_time_min) Lock_time_min,Rows_examined_pct_95 Rows_examined_avg, MAX(b.Rows_examined_max) Rows_examined_max, MIN(b.Rows_examined_min) Rows_examined_min');
        $this->db->from("mysql_slow_query_review a");
        $this->db->join("mysql_slow_query_review_history b", "a.checksum=b.checksum $ext ",'');
        $this->db->where("b.user_max not like ", "%search%");
        $this->db->where("a.cur_status ", "0");
	$this->db->group_by('a.checksum');
        $this->db->order_by('Query_time_sum','desc');
        $this->db->limit($limit,$offset);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0)
	{
	    return $query->result_array();
	}
    }
    
	function get_slowquery_record_top10($server_id,$begin_time,$end_time){
   
	    $this->db->where("last_seen >=", $begin_time);
	    $this->db->where("last_seen <=", $end_time);
	    $this->db->select('s.*,sh.*');
	    $this->db->from("mysql_slow_query_review s");
	    $this->db->join("mysql_slow_query_review_history sh", "s.checksum=sh.checksum and sh.serverid_max=$server_id",'');
	    $this->db->group_by('s.checksum');
	    $this->db->order_by('Query_time_sum','desc');
	    $this->db->limit(10);
	    
	    $query = $this->db->get();
	    if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
    
     
    
   

	function get_slowquery_record_by_checksum($checksum){
	    $this->db->select('s.*,sh.*');
	    $this->db->from("mysql_slow_query_review s");
	    $this->db->join("mysql_slow_query_review_history sh", 's.checksum=sh.checksum');
	    $this->db->where('s.checksum',$checksum);
	    $this->db->order_by('sh.id','desc');
	    $this->db->limit(1,0);
	    $query = $this->db->get();
	    if ($query->num_rows() > 0)
	    {
		return $query->row_array();
	    }
	}
    
    function get_slowquery_analyze_day($server_id){
        if($server_id && $server_id!=0){
            $ext = '_'.$server_id;
        }
        else{
            $ext='';
        }
        $query=$this->db->query("select * from (select DATE_FORMAT(last_seen,'%Y-%m-%d') as days,count(*) as count from mysql_slow_query_review$ext  group by days order by days desc limit 10) as total order by days asc ;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    /*
     * 已经增加合适索引的慢查询
    */
    public function update_slowquery_index($checksum){
	$status_index = 5;
	$this->_update_slowquery_status($checksum, $status_index);
    }
    
    /*
     * 忽略的慢查询
    */
    public function update_slowquery_ignore($checksum){
	$status_ignore = 100;
	$this->_update_slowquery_status($checksum, $status_ignore);
    }
    
    /*
     * 需要开发优化的慢查询
    */
    public function update_slowquery_optimize_dev($checksum){
	$status_optimize_dev = 200;
	$this->_update_slowquery_status($checksum, $status_optimize_dev);
    }

    public function _update_slowquery_status($checksum, $new_status){
	$data = array(
		    'cur_status'=>$new_status,
		    'reviewed_on'=>date('y-m-d H:i:s',time()),
		);
	$this->db->where('checksum', $checksum);
	$this->db->update("mysql_slow_query_review", $data);
    }

    
    function get_total_host(){
        $query=$this->db->query("select host  from mysql_status order by host;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }
    
    function get_total_application(){
        $query=$this->db->query("select application from mysql_status group by application order by application;");
        if ($query->num_rows() > 0)
        {
           return $query->result_array(); 
        }
    }

	function get_status_chart_record($server_id,$time){
        $query=$this->db->query("select * from mysql_status_history  where server_id=$server_id and YmdHi=$time limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    

    
    function get_replication_chart_record($server_id,$time){
        $query=$this->db->query("select slave_io_run,slave_sql_run,delay from mysql_replication_history where server_id=$server_id and YmdHi=$time limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    function get_mysql_info_by_server_id($server_id){
        $query=$this->db->query("select * from mysql_status_history where server_id=$server_id order by id desc limit 1;");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    function get_bigtable_chart_record($server_id,$table_name,$time){
        $query=$this->db->query("select table_size from mysql_bigtable_history where server_id=$server_id and table_name='$table_name' and Ymd=$time order by id desc limit 1; ");
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }

    function check_has_record($server_id,$time){
        $query=$this->db->query("select id from mysql_status_history where server_id=$server_id and YmdHi=$time");
        if ($query->num_rows() > 0)
        {
           return true; 
        }
        else{
            return false;
        }
    }
    
    function check_has_record_dailymysql($server_id,$time){
        $query=$this->db->query("select id from daily_mysql where server_id=$server_id and stat_date='$time'");
        if ($query->num_rows() > 0)
        {
           return true; 
        }
        else{
            return false;
        }
    }

    function get_dailymysql_chart_record($server_id,$time){
        $query=$this->db->query("select * from daily_mysql where server_id=$server_id and stat_date='$time' limit 1; ");
	
        if ($query->num_rows() > 0)
        {
           return $query->row_array(); 
        }
    }
    
    
    

}

/* End of file mysql_model.php */
/* Location: ./application/models/mysql_model.php */
