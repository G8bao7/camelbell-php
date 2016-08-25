<div class="header">
  <h1 class="page-title"><?php echo $this->lang->line('tools'); ?> <?php echo $this->lang->line('_SQL Audit'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Tools'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_SQL Audit'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">

<form name="form" class="form-horizontal" method="post" action="<?php site_url('tools/sql_audit') ?>" >

<div style="height: 20px;"></div>
<div class="control-group info">
    <label class="control-label" for="">*<?php echo $this->lang->line('sql_content'); ?></label>
    <div class="controls">
      <input type="textarea" cols="20" id="sql_content"  name="sql_content" style="height:100px;width:80%;" value="<?php echo $setval['sql_content'] ?>"  >
      <span class="help-inline">*<?php echo $this->lang->line('sql_content_tips'); ?></span>
    </div>
</div>

<div class="control-group info">
    <div class="controls">
    <button type="submit" name="submit" value="audit" class="btn btn-primary"><i class="icon-save"></i> <?php echo $this->lang->line('audit'); ?></button>
    </div>
</div>

<hr>
<div class="control-group warning">
    <label class="control-label" for="">*<?php echo $this->lang->line('audit_result'); ?></label>
    <div class="controls">
    <input type="text" id="audit_result" readonly name="audit_result" style="height:320px;width:80%;"  value="<?php echo $audit_result ?>" class="input-large">
    </div>
</div>
 
</form>                


