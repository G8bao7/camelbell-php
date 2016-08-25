<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Zabbix_item extends Front_Controller {
    function __construct(){
	parent::__construct();
        $this->load->model("zabbix_item_model","zabbix_item");
	$this->load->library('form_validation');
    }
    
    /**
     * 首页
     */
    public function index(){
        parent::check_privilege();

        $item_name=isset($_GET["item_name"]) ? $_GET["item_name"] : "";
        $setval["item_name"]=$item_name;
        $ext_where="";
        if(!empty($item_name)){
            $ext_where=" and (stat_item_name like '%$item_name%' or zabbix_item_name like '%$item_name%') ";
        }
        
        $sql="select * from zabbix_item where 1=1 $ext_where order by id asc";
        $zabbix_item=$this->zabbix_item->get_total_record_sql($sql);
        $data['datalist']=$zabbix_item;
        $data['datacount']=count($zabbix_item);
        $data['set_value'] = $setval;
        $data['success_code']=0;
        $this->layout->view("zabbix_item/index",$data);
    }
    

    /**
     * 添加
     */
    public function add(){
        parent::check_privilege();

	$data['error_code']=0;
	if(isset($_POST['submit']) && $_POST['submit']=='add'){
	    $this->form_validation->set_rules('stat_item_name', 'lang:item', 'trim|required');
	    $this->form_validation->set_rules('zabbix_item_name', 'lang:item', 'trim|required|min_length[1]|max_length[100]');
	    $this->form_validation->set_rules('zabbix_item_value_unit', 'lang:item', 'trim|required');
	    $this->form_validation->set_rules('item_type', 'lang:item', 'trim|required');
	    $this->form_validation->set_rules('zabbix_server', 'lang:item', 'trim|required');
	    if ($this->form_validation->run() == FALSE){
		$data['error_code']='validation_error';
	    }else{
		$data['error_code']=0;
		$data = array(
			    'stat_item_name'=>$this->input->post('stat_item_name'),
			    'zabbix_item_name'=>$this->input->post('zabbix_item_name'),
			    'zabbix_item_value_unit'=>$this->input->post('zabbix_item_value_unit'),
			    'item_type'=>$this->input->post('item_type'),
			    'zabbix_server'=>$this->input->post('zabbix_server'),
			);
		$this->zabbix_item->insert($data);

		redirect(site_url('zabbix_item/index'));
	    }
	}
	$setval['zabbix_server']='DC';
	$setval['item_type']='numeric float';
        $data['set_value'] = $setval;
	$this->layout->view("zabbix_item/add",$data);
    }
	
    /**
     * 编辑
     */
    public function edit($id){
        parent::check_privilege();
        $id  = !empty($id) ? $id : $_POST['id'];
        $data['error_code']=0;
	if(isset($_POST['submit']) && $_POST['submit']=='edit'){
	    $this->form_validation->set_rules('stat_item_name', 'lang:item', 'trim|required');
	    $this->form_validation->set_rules('zabbix_item_name', 'lang:item', 'trim|required|min_length[1]|max_length[100]');
	    $this->form_validation->set_rules('zabbix_item_value_unit', 'lang:item', 'trim|required');
	    $this->form_validation->set_rules('item_type', 'lang:item', 'trim|required');
	    $this->form_validation->set_rules('zabbix_server', 'lang:item', 'trim|required');
	    if ($this->form_validation->run() == FALSE){
		$data['error_code']='validation_error';
	    }else{
		$data['error_code']=0;
		$data = array(
			    'stat_item_name'=>$this->input->post('stat_item_name'),
			    'zabbix_item_name'=>$this->input->post('zabbix_item_name'),
			    'zabbix_item_value_unit'=>$this->input->post('zabbix_item_value_unit'),
			    'item_type'=>$this->input->post('item_type'),
			    'zabbix_server'=>$this->input->post('zabbix_server'),
			    );
		$this->zabbix_item->update($data, $id);

		redirect(site_url('zabbix_item/index'));
	    }
	}

	$record = $this->zabbix_item->get_record_by_id($id);
	if(!$id || !$record){
	    show_404();
	}else{
            $data['record']= $record;
        }
          
	$this->layout->view("zabbix_item/edit",$data);
    }
    
    /**
     * delete
     */
    function delete($id){
        parent::check_privilege();
        if($id){
	    $this->zabbix_item->delete($id);
            redirect(site_url('zabbix_item/index'));
        }
    }


    /**
     * 批量添加
     */
    function batch_add(){
	parent::check_privilege();
        
        /*
	 * 提交批量添加后处理
	 */
	$data['error_code']=0;
	if(isset($_POST['submit']) && $_POST['submit']=='batch_add')
        {
            for($n=1;$n<=10;$n++){
		$stat_item_name = $this->input->post('stat_item_name_'.$n);
		$zabbix_item_name = $this->input->post('zabbix_item_name_'.$n);
		$zabbix_item_value_unit = $this->input->post('zabbix_item_value_unit_'.$n);
		$item_type = $this->input->post('item_type_'.$n);
		$zabbix_server = $this->input->post('zabbix_server_'.$n);
		if(!empty($stat_item_name) && !empty($zabbix_item_name)){
		    $data['error_code']=0;
		    $data = array(
			'stat_item_name'=>$stat_item_name,
			'zabbix_item_name'=>$zabbix_item_name,
			'zabbix_item_value_unit'=>$zabbix_item_value_unit,
			'item_type'=>$item_type,
			'zabbix_server'=>$zabbix_server,
		    );
		    $this->zabbix_item->insert($data);
	        }
	    }
	    redirect(site_url('zabbix_item/index'));
	}
	$this->layout->view("zabbix_item/batch_add",$data);
    }
    
}

/* End of file zabbix_item.php */
/* Location: ./application/controllers/zabbix_item.php */
