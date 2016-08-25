<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="header">  
    <h1 class="page-title"><?php echo $this->lang->line('_Zabbix Item'); ?> <?php echo $this->lang->line('edit'); ?></h1>
</div>
     
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Servers Configure'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Zabbix Item'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">

<form name="form" class="form-horizontal" method="post" action="<?php echo site_url('zabbix_item/edit') ?>" >
<input type="hidden" name="submit" value="edit"/> 
<input type='hidden'  name='id' value=<?php echo $record['id'] ?> />
<div class="btn-toolbar">
    <button type="submit" class="btn btn-primary"><i class="icon-save"></i> <?php echo $this->lang->line('save'); ?></button>
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
   
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('stat_item_name'); ?></label>
    <div class="controls">
      <input type="text" id="stat_item_name"  name="stat_item_name" value="<?php echo $record['stat_item_name']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
   
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('zabbix_item_name'); ?></label>
    <div class="controls">
      <input type="text" id="zabbix_item_name"  name="zabbix_item_name" value="<?php echo $record['zabbix_item_name']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
  
   
   <div class="control-group">
    <label class="control-label" for="">*<?php echo $this->lang->line('unit'); ?></label>
    <div class="controls">
      <input type="text" id="zabbix_item_value_unit"  name="zabbix_item_value_unit" value="<?php echo $record['zabbix_item_value_unit']; ?>" >
      <span class="help-inline"></span>
    </div>
   </div>
  
   <div class="control-group success">
    <label class="control-label" for="">*<?php echo $this->lang->line('item_type'); ?></label>
    <div class="controls">
        <select name="item_type" id="item_type" class="input-big">
         <option value="os" <?php echo set_selected("os",$record['item_type']) ?> >os </option>
         <option value="disk" <?php echo set_selected("disk",$record['item_type']) ?> >disk </option>
        </select>
        <span class="help-inline"></span>
    </div>
   </div>

   <div class="control-group success">
    <label class="control-label" for="">*<?php echo $this->lang->line('zabbix_server'); ?></label>
    <div class="controls">
        <select name="zabbix_server" id="zabbix_server" class="input-big">
         <option value="DBA" <?php echo set_selected("DBA",$record['zabbix_server']) ?> > DBA </option>
         <option value="DC" <?php echo set_selected("DC",$record['zabbix_server']) ?> > DC </option>
        </select>
        <span class="help-inline"></span>
    </div>
   </div>


   
</div>

</form>

