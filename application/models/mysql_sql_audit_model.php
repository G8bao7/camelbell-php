<?php 
class MySQL_SQL_Audit_model extends CI_Model{

    protected $table='mysql_sql_audit';
    
    /*
     * 根据id获取单条记录
     */
    function get_record_by_id($id){
	$query = $this->db->get_where($this->table, array('id' =>$id));
	if ($query->num_rows() > 0){
	    return $query->row_array();
	}
    }
    
    /*
    * 插入数据
    */
    public function insert($data){		
	$this->db->insert($this->table, $data);
	$query = $this->db->query('SELECT LAST_INSERT_ID()');
	$row = $query->row_array();
	$lastIdInserted = $row['LAST_INSERT_ID()'];
	return $lastIdInserted;
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

/* End of file daily_mysql_model.php */
/* Location: ./application/models/daily_mysql_model.php */
