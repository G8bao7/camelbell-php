<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Tools extends Front_Controller {
    public $scripts_dir = "/mysql/camelbells/php/scripts";

    function __construct(){
	parent::__construct();
        $this->load->model("option_model","option");
        $this->load->model("tools_model","tools");
        $this->load->model("mysql_sql_audit_model","sql_audit");
        $this->load->model("scan_alto_dbconfig_model","m_scan_alto_dbconfig");
        $this->load->model("scan_product_split_model","m_scan_product_split");
	
	$this->load->library('form_validation');
    }

    public function index()
    {
	$data="";
        $this->layout->view("tools/index",$data);
    }
    

    /**
     * 发送post请求
     * @param strint $id 待审核的sql内容的主键ID
     * @return string
     */
    public function send_post_inception($id) {
	$post_data_inception = array('id' => $id);
	
	$url = "http://172.21.100.200:8003/inception/";
	$postdata = http_build_query($post_data_inception);
	return $postdata;
	/*
	$options = array(
	    'http' => array(
	      'method' => 'POST',
	      'header' => 'Content-type:application/x-www-form-urlencoded',
	      'content' => $postdata,
	      'timeout' => 5 // 超时时间（单位:s）
	    )
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	
	return $result;
	*/
    }
	 
    public function sql_audit(){
        parent::check_privilege();
	$audit_result = "";
	$data['error_code']=0;
	if(isset($_POST['submit']) && $_POST['submit']=='audit'){
	    $this->form_validation->set_rules('sql_content',  'lang:sql_content', 'trim|required');
	    if ($this->form_validation->run() == FALSE){
		$data['error_code']='validation_error';
	    }else{
		$sql_content = $this->input->post('sql_content');
		$audit_result = "TEST $sql_content";
		$data['error_code'] = 0;
		$data = array(
			    'sql_content'=>$this->input->post('sql_content'),
			);
		$id = $this->sql_audit->insert($data);
		$audit_result = "id $id, TExT $sql_content";
		# call inception api
		$post_result = $this->send_post_inception($id);
		
		# query audit result
		$record = $this->sql_audit->get_record_by_id($id);
		if(!$id || !$record){
		    show_404();
		}else{
		    $data['audit_result']= $record["audit_result"];
		    $audit_result = "id $id, audit $post_result";
		}
	    }
	}

	$data['audit_result'] = $audit_result;
        $setval["sql_content"]=isset($_GET["sql_content"]) ? $_GET["sql_content"] : "";
        $data["setval"]=$setval;

        $this->layout->view("tools/sql_audit",$data);
    }


    /**
     * 发送post请求
     * @param strint $id 待审核的sql内容的主键ID
     * @return string
     */
    public function post_scan_alto_dbconfig($find_key) {
	$post_data_inception = array('find_key' => $find_key);
	$url = "http://172.21.100.200:8003/scan_alto_dbconfig/";
	$postdata = http_build_query($post_data_inception);
	return $postdata;
    }

    #  
    public function scan_product_split(){
        parent::check_privilege();
	$sleepms=isset($_GET["sleepms"]) ? $_GET["sleepms"] : '1000';
	$setval["sleepms"] = $sleepms;
	$parallels=isset($_GET["parallels"]) ? $_GET["parallels"] : '8';
	$setval["parallels"] = $parallels;
	$data_source=isset($_GET["data_source"]) ? $_GET["data_source"] : 'rs';
	$setval["data_source"] = $data_source;
	$setval["table_name"] = isset($_GET["table_name"]) ? $_GET["table_name"] : '';
	$setval["str_where"] = isset($_GET["str_where"]) ? $_GET["str_where"] : '';
	if((isset($_GET["table_name"])) && (isset($_GET["str_where"]))){
	    $table_name = $_GET["table_name"];
	    $str_where = $_GET["str_where"];
	    $setval["table_name"] = $table_name;
	    $setval["str_where"] = $str_where;
	    /*
	    if (strpos($table_name, ";")){
		show_404();
	    }
	    */

	    # call scripts
	    $uniq_id = uniqid();
	    $r_str_where = $str_where;
	    #$r_str_where = str_replace('_', '\_', $r_str_where);
	    #$r_str_where = str_replace('"', '\"', $r_str_where);
	    $scanCmd = "/usr/bin/python ".$this->scripts_dir."/scan_product_split.py -u '$uniq_id' -w '$r_str_where' -t '$table_name' -p $parallels -s $sleepms";
	    exec($scanCmd, $out,$states);

	    # query  result
	    $datalist = $this->m_scan_product_split->get_record_by_key($uniq_id);
	    if($datalist){
		$data['datalist']= $datalist;
		$data["row_count"]=count($datalist);
	    }
	}
	$setval["str_where"] = '';

        $data["setval"]=$setval;

        $this->layout->view("tools/scan_product_split",$data);
    }
    

    #  
    public function scan_alto_dbconfig(){
        parent::check_privilege();
	$setval["env"] = "";
	$setval["find_key"] = "";
	if((isset($_GET["env"])) && (isset($_GET["find_key"]))){
	    $find_key = $_GET["find_key"];
	    $env = $_GET["env"];
	    $setval["env"] = $env;
	    $setval["find_key"] = $find_key;
	    # call scripts
	    $scanCmd = "/usr/bin/python ".$this->scripts_dir."/scan_alto_dbconfig.py  key -v '$find_key' -e '$env' ";
	    #print_r($scanCmd);
	    exec($scanCmd, $out,$states);

	    # query  result
	    $datalist = $this->m_scan_alto_dbconfig->get_record_by_key($find_key);
	    if($datalist){
		$data['datalist']= $datalist;
		$data["conf_count"]=count($datalist);
	    }
	}

        $data["setval"]=$setval;

        $this->layout->view("tools/scan_alto_dbconfig",$data);
    }

    #  
    public function otp(){
        parent::check_privilege();
	$otp="";	
	if(isset($_POST['submit']) && $_POST['submit']=='query'){
	    # call scripts
	    $scanCmd = "/usr/bin/python ".$this->scripts_dir."/zj_otp.py  ";
	    exec($scanCmd, $out,$states);
	    $otp = $out[0];
	}

	$data["otp"] = $otp;

        $this->layout->view("tools/otp",$data);
    }


    public function test(){
        $mysql_statistics = array();
        $dbrow = $this->db->query("SELECT  (SELECT COUNT(*) AS num FROM db_servers_mysql WHERE is_delete=0) AS num_mysql,  (SELECT COUNT(*) AS num FROM db_servers_oracle WHERE is_delete=0) AS num_oracle,  (SELECT COUNT(*) AS num FROM db_servers_mongodb WHERE is_delete=0) AS num_mongodb, (SELECT COUNT(*) AS num FROM db_servers_redis WHERE is_delete=0) AS num_redis, (SELECT COUNT(*) AS num FROM db_servers_os WHERE is_delete=0) AS num_os
	    ")->row();
	#print_r($dbrow);
        $data["servers_mysql_count"] = $dbrow->num_mysql;
        $data["servers_oracle_count"] = $dbrow->num_oracle;
        $data["servers_mongodb_count"] = $dbrow->num_mongodb;
        $data["servers_redis_count"] = $dbrow->num_redis;
        $data["servers_os_count"] = $dbrow->num_os;


        #$lepus_status=$this->lepus->get_lepus_status();
        $lepus_status="a";
        $data['lepus_status']=$lepus_status;
        $setval["host"]=isset($_GET["host"]) ? $_GET["host"] : ""; 
        $setval["tags"]=isset($_GET["tags"]) ? $_GET["tags"] : ""; 
        $setval["db_type"]=isset($_GET["db_type"]) ? $_GET["db_type"] : ""; 
        $setval["order"]=isset($_GET["order"]) ? $_GET["order"] : ""; 
        $setval["order_type"]=isset($_GET["order_type"]) ? $_GET["order_type"] : ""; 
        $data["setval"]=$setval;

        #$db_status = $this->lepus->get_db_status();
        $db_status = $this->db->query("SELECT s.host AS shost,s.port AS sport,d.*  FROM db_servers_mysql AS s  LEFT JOIN db_status AS d ON s.host=d.host AND s.port=d.port   WHERE s.monitor=1 AND s.is_delete=0  UNION ALL SELECT s.host AS shost,s.port AS sport,d.*  FROM db_servers_oracle AS s LEFT JOIN db_status AS d ON s.host=d.host AND s.port=d.port WHERE s.monitor=1 AND s.is_delete=0 UNION ALL  SELECT s.host AS shost,s.port AS sport,d.* FROM db_servers_mongodb AS s LEFT JOIN db_status AS d ON s.host=d.host AND s.port=d.port  WHERE s.monitor=1 AND s.is_delete=0")->result_array();
	#print_r($db_status[0]);
        $data['db_status'] = $db_status;

        $this->layout->view("tools/test",$data);
    }

    
}

/* End of file tools.php */
/* Location: ./application/controllers/tools.php */
