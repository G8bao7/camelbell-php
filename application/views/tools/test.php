<script src="lib/bootstrap/js/jquery.pin.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="header">
    <div class="stats">
    
    <p class="stat"><span class="number"><?php echo $servers_os_count; ?></span>OS</p>
    <p class="stat"><span class="number"><?php echo $servers_redis_count; ?></span>Redis</p>
    <p class="stat"><span class="number"><?php echo $servers_mongodb_count; ?></span>MongoDB</p>
    <p class="stat"><span class="number"><?php echo $servers_oracle_count; ?></span>Oracle</p>
    <p class="stat"><span class="number"><?php echo $servers_mysql_count; ?></span>MySQL</p>
    
    </div>
<h1 class="page-title"><?php echo $this->lang->line('dashboard'); ?></h1>
</div>
        
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('dashboard'); ?></li><span class="divider">/</span></li>
    <span class="right"><?php echo $this->lang->line('lepus_version'); ?>:<?php echo $lepus_status['lepus_version']; ?>&nbsp;&nbsp; <?php echo $this->lang->line('lepus_status'); ?>:<?php if($lepus_status['lepus_running']==1){ ?><span class="label label-success"><?php echo $this->lang->line('lepus_running'); ?></span><?php }else{?><span class="label label-important"><?php echo $this->lang->line('lepus_not_run'); ?></span><?php } ?>&nbsp;&nbsp; <?php echo $this->lang->line('last_check_time'); ?>:<?php echo $lepus_status['lepus_checktime']; ?> &nbsp;&nbsp; <?php echo " Now:" ?> <?php echo $showtime=date("Y-m-d H:i:s");?> </span>
</ul>

 

<div class="container-fluid">
<div class="row-fluid">
 
<script src="lib/bootstrap/js/bootstrap-switch.js"></script>
<link href="lib/bootstrap/css/bootstrap-switch.css" rel="stylesheet"/>
                    
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php echo site_url('index/index2') ?>" >

  
  <select name="db_type" class="input-small" style="width: 120px;">
  <option value="" <?php if($setval['db_type']=='') echo "selected"; ?> ><?php echo $this->lang->line('db_type'); ?></option>
  <option value="mysql" <?php if($setval['db_type']=='mysql') echo "selected"; ?> ><?php echo $this->lang->line('mysql'); ?></option>
  <option value="oracle" <?php if($setval['db_type']=='oracle') echo "selected"; ?> ><?php echo $this->lang->line('oracle'); ?></option>
  <option value="mongodb" <?php if($setval['db_type']=='mongodb') echo "selected"; ?> ><?php echo $this->lang->line('mongodb'); ?></option>
  <option value="redis" <?php if($setval['db_type']=='redis') echo "selected"; ?> ><?php echo $this->lang->line('redis'); ?></option>
  
  </select>
  
  <input type="text" id="host"  name="host" value="<?php echo $setval['host']; ?>" placeholder="<?php echo $this->lang->line('please_input_host'); ?>" class="input-medium" >
 <input type="text" id="tags"  name="tags" value="<?php echo $setval['tags']; ?>" placeholder="<?php echo $this->lang->line('please_input_tags'); ?>" class="input-medium" >
  
  
  <select name="order" class="input-small" style="width: 100px;">
  <option value=""><?php echo $this->lang->line('sort'); ?></option>
  <option value="db_type_sort" <?php if($setval['order']=='db_type_sort') echo "selected"; ?> ><?php echo $this->lang->line('default'); ?></option>
  <option value="host" <?php if($setval['order']=='host') echo "selected"; ?> ><?php echo $this->lang->line('host'); ?></option>
  <option value="db_type" <?php if($setval['order']=='db_type') echo "selected"; ?> ><?php echo $this->lang->line('db_type'); ?></option>
  <option value="tags" <?php if($setval['order']=='tags') echo "selected"; ?> ><?php echo $this->lang->line('tags'); ?></option>

  </select>
  <select name="order_type" class="input-small" style="width: 70px;">
  <option value="asc" <?php if($setval['order_type']=='asc') echo "selected"; ?> ><?php echo $this->lang->line('asc'); ?></option>
  <option value="desc" <?php if($setval['order_type']=='desc') echo "selected"; ?> ><?php echo $this->lang->line('desc'); ?></option>
  </select>

  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('tool/test') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

 &nbsp;&nbsp;&nbsp;
 <label class="checkbox"><div id="toggle-state-switch-button" class="btn btn-info"><i class="icon-refresh"></i> <?php echo $this->lang->line('auto_refresh'); echo "60s"; ?></div>
    <div id="toggle-state-switch" class="make-switch" data-on="success" data-off="danger" data-on-label="ON" data-text-label="">
    <input type="checkbox" name="reflesh" id="reflesh" value="" checked="checked" >
    </div>
  </label>

</form>                
</div>


<div id='dbstatus' class="well monitor " style=" <?php if($this->input->cookie('lang_current')=='zh-hans') echo  'font-family: 微软雅黑;' ?>  ;">
    <table class="table table-hover table-condensed tooltip-lepus">
      
      <thead>
        <tr style="font-size: 13px;">
        <th colspan="4"><center><?php echo $this->lang->line('servers'); ?></center></th>
        <th colspan="6"><center><?php echo $this->lang->line('db'); ?></center></th>
        <th colspan="7"><center><?php echo $this->lang->line('os'); ?></center></th>
        <th ></th>
        </tr>
        <tr style="font-size: 12px;" >
        <th><?php echo $this->lang->line('type'); ?></th> 
        <th><?php echo $this->lang->line('host'); ?></th>
        <th><?php echo $this->lang->line('role'); ?></th> 
        <th><?php echo $this->lang->line('tags'); ?></th>
	<!--
        <th><?php echo $this->lang->line('version'); ?></th>
	-->
        <th><?php echo $this->lang->line('connect'); ?></th>
        <th><?php echo $this->lang->line('sessions'); ?></th>
        <th><?php echo $this->lang->line('actives'); ?></th>
        <th><?php echo $this->lang->line('waits'); ?></th>
        <th><?php echo $this->lang->line('repl'); ?></th>
        <th><?php echo $this->lang->line('delay'); ?></th>
	<!--
        <th><?php echo $this->lang->line('tbs'); ?></th>
	-->
        <th><?php echo $this->lang->line('zabbix'); ?></th>
        <th><?php echo $this->lang->line('process'); ?></th>
        <th><?php echo $this->lang->line('load'); ?></th>
        <th><?php echo $this->lang->line('cpu'); ?></th>
        <th><?php echo $this->lang->line('mem'); ?></th>
        <th><?php echo $this->lang->line('net'); ?></th>
        <th><?php echo $this->lang->line('disk'); ?></th>
        <th><?php echo $this->lang->line('time'); ?></th>
      </tr>
      </thead>
      <tbody>
 <?php if(!empty($db_status)) {?>
 <?php foreach ($db_status  as $item):?>
    <tr style="font-size: 12px;">
        <td><?php echo check_dbtype($item['db_type']) ?></td>
        <td><?php echo $item['shost'] ?>:<?php echo $item['sport'] ?></td>
        <td><?php echo check_db_status_role($item['role']) ?></td>
        <td><?php echo $item['tags'] ?></td>
	<!--
        <td><?php echo check_value($item['version']) ?></td>
	-->
        <td><?php echo check_db_status_level($item['connect'],$item['connect_tips']) ?></td>
        <td><?php echo check_db_status_level($item['sessions'],$item['sessions_tips']) ?></td>
        <td><?php echo check_db_status_level($item['actives'],$item['actives_tips']) ?></td>
        <td><?php echo check_db_status_level($item['waits'],$item['waits_tips']) ?></td>
        <td><?php echo check_db_status_level($item['repl'],$item['repl_tips']) ?></td>
        <td><?php echo check_db_status_level($item['repl_delay'],$item['repl_delay_tips']) ?></td>
	<!--
        <td><?php echo check_db_status_level($item['tablespace'],$item['tablespace_tips']) ?></td>
	-->
        <td><?php echo check_db_status_level($item['zabbix'],$item['zabbix_tips']) ?></td>
        <td><?php echo check_db_status_level($item['process'],$item['process_tips']) ?></td>
        <td><?php echo check_db_status_level($item['load_1'],$item['load_1_tips']) ?></td>
        <td><?php echo check_db_status_level($item['cpu'],$item['cpu_tips']) ?></td>
        <td><?php echo check_db_status_level($item['memory'],$item['memory_tips']) ?></td>
        <td><?php echo check_db_status_level($item['network'],$item['network_tips']) ?></td>
        <td><?php echo check_db_status_level($item['disk'],$item['disk_tips']) ?></td>
        <td><?php echo $item['uptime_time'] ?></td>
  </tr>
 <?php endforeach;?>
 <?php }else{  ?>
<tr>
<td colspan="16">
<font color="red"><?php echo $this->lang->line('no_record'); ?></font>
</td>
</tr>
<?php } ?>      
      </tbody>
    </table>
</div>

<script  type="text/javascript">

  $('#toggle-state-switch').bootstrapSwitch('toggleState');
  $('#toggle-state-switch').bootstrapSwitch('setState', true); // true || false
    
    function reflesh(){
        //var check_status=$("#reflesh").attr("checked");
        //alert(check_status);
        var arrays = new Array();   //创建一个数组对象
        var items = document.getElementsByName("reflesh");  //获取name为check的一组元素(checkbox)
        for(i=0; i < items.length; i++){  //循环这组数据
	       if(items[i].checked){      //判断是否选中
		    arrays.push(items[i].value);  //把符合条件的 添加到数组中. push()是javascript数组中的方法.
	       }
        }
        //alert( "选中的个数为："+arrays.length  );
        check_count=arrays.length;

        if (check_count==1){ //判断选择框是否选中
                document.location.reload();    
        }
	}
	setInterval("reflesh()",60*1000);//每10秒钟刷新一次 
</script>

<script type="text/javascript">
     $(function(){
		// tooltip demo
    	$('.tooltip-lepus').tooltip({
      		selector: "a[data-toggle=tooltip]"
    	})
		
	 })
	

$(".thead").pin()
 </script>




