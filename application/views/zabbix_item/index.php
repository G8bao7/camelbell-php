<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="header">  
    <h1 class="page-title"><?php echo $this->lang->line('_Zabbix Item'); ?> <?php echo $this->lang->line('list'); ?></h1>
</div>
     
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Servers Configure'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Zabbix Item'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">
 
<div class="btn-toolbar">
    <a class="btn btn-primary " href="<?php echo site_url('zabbix_item/add') ?>"><i class="icon-plus"></i> <?php echo $this->lang->line('add'); ?></a>
    <a class="btn btn-primary " href="<?php echo site_url('zabbix_item/batch_add') ?>"><i class="icon-plus"></i> <?php echo $this->lang->line('batch_add'); ?></a>
  <div class="btn-group"></div>
</div>

<div class="well">

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="" >
 
 <input type="text" id="item_name" name="item_name" value="<?php echo $set_value['item_name']; ?>" placeholder="<?php echo $this->lang->line('please_input_text'); ?>" class="input-medium" >
  
  
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('zabbix_item/index') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

</form>                   
</div>

    <table class="table table-hover table-bordered">
      <thead>
        <tr style="font-size:12px;">
        <th><?php echo $this->lang->line('id'); ?></th>
        <th><?php echo $this->lang->line('stat_item_name'); ?></th>
        <th><?php echo $this->lang->line('zabbix_item_name'); ?></th>
        <th><?php echo $this->lang->line('unit'); ?></th>
        <th><?php echo $this->lang->line('item_type'); ?></th>
        <th><?php echo $this->lang->line('zabbix_server'); ?></th>
        <th></th>
	</tr>
      </thead>
      <tbody>
 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 12px;">
	<td><?php echo $item['id'] ?></td>
	<td><?php echo $item['stat_item_name'] ?></td>
	<td><?php echo $item['zabbix_item_name'] ?></td>
	<td><?php echo $item['zabbix_item_value_unit'] ?></td>
	<td><?php echo $item['item_type'] ?></td>
	<td><?php echo $item['zabbix_server'] ?></td>
  
        <td><a href="<?php echo site_url('zabbix_item/edit/'.$item['id']) ?>"  title="<?php echo $this->lang->line('edit'); ?>" ><i class="icon-pencil"></i></a>&nbsp;
        <a href="<?php echo site_url('zabbix_item/delete/'.$item['id']) ?>" class="confirm_delete" title="<?php echo $this->lang->line('add_trash'); ?>" ><i class="icon-trash"></i></a>
        </td>
    </tr>
 <?php endforeach;?>
<tr>
<td colspan="14">
<font color="#000000"><?php echo $this->lang->line('total_record'); ?> <?php echo $datacount; ?></font>
</td>
</tr>
 <?php }else{  ?>
<tr>
<td colspan="14">
<font color="red"><?php echo $this->lang->line('no_record'); ?></font>
</td>
</tr>
<?php } ?>      
      </tbody>
    </table>
</div>


<script type="text/javascript">
	$(' .confirm_delete').click(function(){
		return confirm("<?php echo $this->lang->line('add_to_trash_confirm'); ?>");	
	});
</script>
