<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="header">  
    <h1 class="page-title"><?php echo $this->lang->line('_Zabbix Item'); ?> <?php echo $this->lang->line('batch_add'); ?></h1>
</div>
     
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Servers Configure'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Zabbix Item'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('zabbix_item/batch_add') ?>" >
<input type="hidden" name="submit"  value="batch_add"/> 
<div class="btn-toolbar">
    <button type="submit" class="btn btn-primary confirm_add"><i class="icon-save"></i> <?php echo $this->lang->line('save'); ?></button>
    <a class="btn btn " href="<?php echo site_url('zabbix_item/index') ?>"><i class="icon-list"></i> <?php echo $this->lang->line('list'); ?></a>
  <div class="btn-group"></div>
</div>

<?php if ($error_code!==0) { ?>
<div class="alert alert-error">
<button type="button" class="close" data-dismiss="alert">×</button>
<?php echo validation_errors(); ?>
</div>
<?php } ?>

<div class="well">
  
   <table class="table table-hover table-bordered  ">
	<tr>
        <th><?php echo $this->lang->line('stat_item_name'); ?></th>
        <th><?php echo $this->lang->line('zabbix_item_name'); ?></th>
        <th><?php echo $this->lang->line('unit'); ?></th>
        <th><?php echo $this->lang->line('item_type'); ?></th>
        <th><?php echo $this->lang->line('zabbix_server'); ?></th>
	</tr>
	
<?php for($n=1;$n<=8;$n++){ ?>
<input type="hidden" name="submit" value="batch_add"/>                             
<input type="hidden" name="ids[]" value="<?php echo $n ?>" /> 
    <tr style="font-size:12px;">
        <td><input type="text" name="stat_item_name_<?php echo $n ?>" class="" placeholder="<?php echo $this->lang->line('stat_item_name'); ?>" value=""></td>
        <td><input type="text" name="zabbix_item_name_<?php echo $n ?>" class="" placeholder="<?php echo $this->lang->line('zabbix_item_name'); ?>" value=""></td>
        <td><input type="text" name="zabbix_item_value_unit_<?php echo $n ?>" class="" placeholder="<?php echo $this->lang->line('unit'); ?>" value=""></td>

       <td>
	    <select name="item_type_<?php echo $n ?>" id="item_type" class="input-big">
	     <option value="os" >os </option>
	     <option value="disk" >disk </option>
	    </select>
       </td>

	<td>
	    <select name="zabbix_server_<?php echo $n ?>" id="zabbix_server" class="input-big">
	     <option value="DBA" > DBA </option>
	     <option value="DC" > DC </option>
	    </select>
	</td>


    </tr>
<?php } ?> 
                                                                                                     

</table>

   
</div>


</form>

<script type="text/javascript">
	$(' .confirm_add').click(function(){
		return confirm('确定要批量提交所有服务器？');	
	});
</script>
