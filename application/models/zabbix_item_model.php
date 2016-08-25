<?php 
class Zabbix_item_model extends CI_Model{

    protected $table='zabbix_item';
    
    /*
     * 获取所有
     */
    function get_total_record(){
	$query = $this->db->get($this->table);
	if ($query->num_rows() > 0)
	{
	    return $query->result_array();
	}
    }

    
    function get_total_record_sql($sql){
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0){
	    return $query->result_array();
	}
		
    }

    /*
     * 根据id获取单条记录
     */
    function get_record_by_id($id){
	$query = $this->db->get_where($this->table, array('id' =>$id));
	if ($query->num_rows() > 0)
	{
	    return $query->row_array();
	}
    }
    
    /*
    * 插入数据
    */
    public function insert($data){	
	$this->db->insert($this->table, $data);
    }
    
    /*
     * 更新信息
    */
    public function update($data,$id){
	$this->db->where('id', $id);
	$this->db->update($this->table, $data);
    }
    
    /*
     * 删除信息
    */
    public function delete($id){
	$this->db->where('id', $id);
	$this->db->delete($this->table);
    }
	
}

/* End of file zabbix_item_model.php */
/* Location: ./application/models/zabbix_item_model.php */
