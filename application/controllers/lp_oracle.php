<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Lp_oracle extends Front_Controller {

    function __construct(){
	parent::__construct();
	$this->load->model('servers_oracle_model','server');
	$this->load->model("option_model","option");
	$this->load->model("oracle_model","oracle");
	$this->load->model("os_model","os");  
    }
    
   
    public function index()
    {
        parent::check_privilege();
        $data["datalist"]=$this->oracle->get_status_total_record();

        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        
        $setval["connect"]=isset($_GET["connect"]) ? $_GET["connect"] : "";
        $setval["session_total"]=isset($_GET["session_total"]) ? $_GET["session_total"] : "";
        $setval["session_actives"]=isset($_GET["session_actives"]) ? $_GET["session_actives"] : "";
        $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "";
        $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "";
        $data["setval"]=$setval;

        $this->layout->view("oracle/index",$data);
    }
    
   	
    public function tablespace()
    {
        parent::check_privilege();
        $data["datalist"]=$this->oracle->get_tablespace_total_record();

        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : "";
        
        $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : "create_time";
        $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : "desc";
        $data["setval"]=$setval;

        $this->layout->view("oracle/tablespace",$data);
    }
    
    public function chart()
    {
        parent::check_privilege('');
        $server_id = $this->uri->segment(3);
        $server_id=!empty($server_id) ? $server_id : "0";
        $begin_time = $this->uri->segment(4);
        $begin_time=!empty($begin_time) ? $begin_time : "30";
        $time_span = $this->uri->segment(5);
        $time_span=!empty($time_span) ? $time_span : "min";


        //饼状图表
        $data=array();   
        
        //线性图表
        $chart_reslut=array();

        for($i=$begin_time;$i>=0;$i--){
            $timestamp=time()-60*$i;
            $time= date('YmdHi',$timestamp);
            $has_record = $this->oracle->check_has_record($server_id,$time);
            if($has_record){
                    $chart_reslut[$i]['time']=date('Y-m-d H:i',$timestamp);
                    $dbdata=$this->oracle->get_status_chart_record($server_id,$time);
                    $chart_reslut[$i]['session_total'] = $dbdata['session_total'];
                    $chart_reslut[$i]['session_actives'] = $dbdata['session_actives'];
                    $chart_reslut[$i]['session_waits'] = $dbdata['session_waits'];
                    $chart_reslut[$i]['processes'] = $dbdata['processes'];
                    $chart_reslut[$i]['session_logical_reads_persecond'] = $dbdata['session_logical_reads_persecond'];
                    $chart_reslut[$i]['physical_reads_persecond'] = $dbdata['physical_reads_persecond'];
                    $chart_reslut[$i]['physical_writes_persecond'] = $dbdata['physical_writes_persecond'];
                    $chart_reslut[$i]['physical_read_io_requests_persecond'] = $dbdata['physical_read_io_requests_persecond'];
                    $chart_reslut[$i]['physical_write_io_requests_persecond'] = $dbdata['physical_write_io_requests_persecond'];
                    $chart_reslut[$i]['db_block_changes_persecond'] = $dbdata['db_block_changes_persecond'];
                    $chart_reslut[$i]['os_cpu_wait_time'] = $dbdata['os_cpu_wait_time'];
                    $chart_reslut[$i]['logons_persecond'] = $dbdata['logons_persecond'];
                    $chart_reslut[$i]['logons_current'] = $dbdata['logons_current'];
                    $chart_reslut[$i]['opened_cursors_persecond'] = $dbdata['opened_cursors_persecond'];
                    $chart_reslut[$i]['opened_cursors_current'] = $dbdata['opened_cursors_current'];
                    $chart_reslut[$i]['user_commits_persecond'] = $dbdata['user_commits_persecond'];
                    $chart_reslut[$i]['user_rollbacks_persecond'] = $dbdata['user_rollbacks_persecond'];
                    $chart_reslut[$i]['user_calls_persecond'] = $dbdata['user_calls_persecond'];

            }
            
        }
        $data['chart_reslut']=$chart_reslut;
        //print_r($chart_reslut);
    
        $chart_option=array();
        if($time_span=='min'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='hour'){
            $chart_option['formatString']='%H:%M';
        }
        else if($time_span=='day'){
            $chart_option['formatString']='%m/%d %H:%M';
        }
        
        $data['chart_option']=$chart_option;
      
        $data['begin_time']=$begin_time;
        $data['cur_nav']='chart_index';
        $data["server"]=$servers=$this->server->get_total_record_usage();
        $data['cur_server_id']=$server_id;
        $data["cur_server"] = $this->server->get_servers($server_id);
        $this->layout->view('oracle/chart',$data);
    }
   	
    public function awrreport(){
        parent::check_privilege();
        $data["server"]=$servers=$this->server->get_total_record();

        $setval["server_id"] = !empty($_GET["server_id"])? $_GET["server_id"]: '';
        $setval["tags"] = !empty($_GET["tags"])? $_GET["tags"]: '';
        $stime = !empty($_GET["stime"])? $_GET["stime"]: date('Y-m-d',time()-3600*24*3);
        $etime = !empty($_GET["etime"])? $_GET["etime"]: date('Y-m-d',time());
        $setval["stime"]=$stime;
        $setval["etime"]=$etime;

        $order = !empty($_GET["order"])? $_GET["order"]: 'tps_avg';
        $order_type = !empty($_GET["order_type"])? $_GET["order_type"]: 'desc';
        $setval["order"] = $order;
        $setval["order_type"] = $order_type;

        $data["setval"]=$setval;
        $data["datalist"]=$this->oracle->get_awrreport_record();

        $this->layout->view("oracle/awrreport",$data);
    }

   
    public function awrreport_chart()
    {
        parent::check_privilege();
        $server_id = $this->uri->segment(3);
        $server_id = !empty($server_id) ? $server_id : "0";
        $begin_time = !empty($_GET["stime"])? $_GET["stime"]: date('Y-m-d',time()-86400*14);
        $end_time = !empty($_GET["etime"])? $_GET["etime"]: date('Y-m-d',time());

        $setval["server_id"] = $server_id;
        $setval["stime"] = $begin_time;
        $setval["etime"] = $end_time;

        //饼状图表
        $data = array();   
        
	#$tickInterval = round($begin_time/14);
	$tickInterval = 1;
        $data["tickInterval"] = $tickInterval;

	// 返回结果必须是日期升序排序
        $data_lines = $this->oracle->get_awrreport_chart_record($server_id, $begin_time, $end_time);

        // 每日统计图表
        $chart_reslut_awrreport = array();
	if($data_lines){
	    $i = 0;
	    $cur_time = $begin_time;
	    foreach ($data_lines as $data_line) {
		$stat_date = $data_line['stat_date'];

		// 补足缺失日期的数据
		while (strtotime($cur_time) < strtotime($stat_date)){
		    $chart_reslut_awrreport[$i]['time'] = $cur_time;
		    $chart_reslut_awrreport[$i]['db_time'] = 0;
		    $chart_reslut_awrreport[$i]['db_cpu'] = 0;
		    $chart_reslut_awrreport[$i]['redo_size'] = 0;
		    $chart_reslut_awrreport[$i]['logical_reads'] = 0;
		    $chart_reslut_awrreport[$i]['user_calls'] = 0;
		    $chart_reslut_awrreport[$i]['executes'] = 0;
		    $chart_reslut_awrreport[$i]['transactions'] = 0;

		    $i++;
		    $timestamp = strtotime($cur_time) + 86400;
		    $cur_time = date('Y-m-d',$timestamp);
		}

		$chart_reslut_awrreport[$i]['time'] = $stat_date;
		$chart_reslut_awrreport[$i]['db_time'] = $data_line['db_time'];
		$chart_reslut_awrreport[$i]['db_cpu'] = $data_line['db_cpu'];
		$chart_reslut_awrreport[$i]['redo_size'] = $data_line['redo_size'];
		$chart_reslut_awrreport[$i]['logical_reads'] = $data_line['logical_reads'];
		$chart_reslut_awrreport[$i]['user_calls'] = $data_line['user_calls'];
		$chart_reslut_awrreport[$i]['executes'] = $data_line['executes'];
		$chart_reslut_awrreport[$i]['transactions'] = $data_line['transactions'];
		$i++;
		$timestamp = strtotime($cur_time) + 86400;
		$cur_time = date('Y-m-d',$timestamp);
	    }

	    // 补足缺失日期的数据
	    while (strtotime($cur_time) < strtotime($stat_date)){
		$chart_reslut_awrreport[$i]['time'] = $cur_time;
		$chart_reslut_awrreport[$i]['db_time'] = 0;
		$chart_reslut_awrreport[$i]['db_cpu'] = 0;
		$chart_reslut_awrreport[$i]['redo_size'] = 0;
		$chart_reslut_awrreport[$i]['logical_reads'] = 0;
		$chart_reslut_awrreport[$i]['user_calls'] = 0;
		$chart_reslut_awrreport[$i]['executes'] = 0;
		$chart_reslut_awrreport[$i]['transactions'] = 0;

		$i++;
		$timestamp = strtotime($cur_time) + 86400;
		$cur_time = date('Y-m-d',$timestamp);
	    }
	
	//print_r($chart_reslut_awrreport);

	}
        // 
        $data['chart_reslut_awrreport']=$chart_reslut_awrreport;
        $data["setval"]=$setval; 
        $data['cur_nav'] = 'chart_index';
        $data['cur_server_id'] = $server_id;
        $data['cur_server'] = $this->server->get_host_by_id($server_id);
        $this->layout->view('oracle/awrreport_chart',$data);
    }
       
    public function slowquery(){
        parent::check_privilege();
	$current_url= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'?noparam=1';
	
        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : "";
        $setval["sql_id"]=isset($_GET["sql_id"]) ? $_GET["sql_id"] : "";

        !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
        !empty($_GET["sql_id"]) && $this->db->like("sql_id", $_GET["sql_id"]);
 
        $stime = !empty($_GET["stime"])? $_GET["stime"]: date('Y-m-d H:i',time()-3600*24*2);
        $etime = !empty($_GET["etime"])? $_GET["etime"]: date('Y-m-d H:i',time());
        $setval["stime"]=$stime;
        $setval["etime"]=$etime;

        $show_type = !empty($_GET["show_type"])? $_GET["show_type"]: 'run_time';
        $setval["show_type"]=$show_type;
        $order = !empty($_GET["order"])? $_GET["order"]: 'elapsed_time_per_exec';
        $order_type = !empty($_GET["order_type"])? $_GET["order_type"]: 'desc';
        $setval["order"]=$order;
        $setval["order_type"]=$order_type;
        
        //分页
	$this->load->library('pagination');
	$config['base_url'] = $current_url;
	if($show_type=='first_time'){
	    $this->db->where("s.first_time >= ", $stime);
	    $this->db->where("s.first_time <= ", $etime);
	    $config['total_rows'] = $this->oracle->get_slowquery_first_total_rows();
	}else{
	    $this->db->where("stat_date >=", $stime);
	    $this->db->where("stat_date <=", $etime);
	    $config['total_rows'] = $this->oracle->get_slowquery_total_rows();
	}
	$config['per_page'] = 25;
	$config['num_links'] = 5;
	$config['page_query_string'] = TRUE;
	$config['use_page_numbers'] = TRUE;
	$this->pagination->initialize($config);
	$off_page = !empty($_GET['per_page']) ? $_GET['per_page'] : 1;
	$limit_row = $config['per_page'];
	$offset = ($off_page-1)*$config['per_page'];
        
	# 必须再写次条件，get_slowquery_total_rows调用后，会将条件清空
	if($show_type=='first_time'){
	    $this->db->select('q.*');
	    $this->db->from("oracle_slowquery_summary s");
	    $this->db->join("oracle_slowquery q", "s.first_id=q.id",'');
	    !empty($_GET["host"]) && $this->db->like("s.host", $_GET["host"]);
	    !empty($_GET["sql_id"]) && $this->db->where("s.sql_id", $_GET["sql_id"]);
	    $this->db->where("s.first_time >= ", $stime);
	    $this->db->where("s.first_time <= ", $etime);
	    $this->db->order_by('q.create_time', 'desc');
	    $this->db->limit($limit_row,$offset);
	    $datalist = $this->db->get()->result_array();
	}else{
	    !empty($_GET["host"]) && $this->db->like("host", $_GET["host"]);
	    !empty($_GET["sql_id"]) && $this->db->like("sql_id", $_GET["sql_id"]);
	    $this->db->where("stat_date >=", $stime);
	    $this->db->where("stat_date <=", $etime);
	    $this->db->order_by($order,$order_type);
	    $this->db->limit($limit_row,$offset);
	    $datalist=$this->oracle->get_total_record("oracle_slowquery");
	    #$datalist=$this->oracle->get_total_record_paging("oracle_slowquery", $limit_row, $offset);
	}
        $data["datalist"]=$datalist;
        $data["setval"]=$setval;
        // $data["cur_servers"] = $this->server->get_servers();
        
        $this->layout->view("oracle/slowquery",$data);
    }
    
}

/* End of file oracle.php */
/* Location: ./application/controllers/oracle.php */
