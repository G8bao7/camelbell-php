<div class="header">
  <h1 class="page-title"><?php echo $this->lang->line('mysql'); ?> <?php echo $this->lang->line('_Databases Statistic'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_MySQL Monitor'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Databases Statistic'); ?></li>
    <span class="right"><?php echo 'Total'; ?>:<?php if(!empty($db_count)){ echo $db_count;} else {echo '0';} ?></span>
</ul>

<div class="container-fluid">
<div class="row-fluid">
 
<script src="lib/bootstrap/js/bootstrap-switch.js"></script>
<link href="lib/bootstrap/css/bootstrap-switch.css" rel="stylesheet"/>
                    
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('lp_mysql/databases') ?>" >
 
  <input type="text" id="host"  name="host" value="" placeholder="<?php echo $this->lang->line('please_input_host'); ?>" class="input-medium" >
  <input type="text" id="db_name"  name="db_name" value="" placeholder="<?php echo $this->lang->line('database'); ?> <?php echo $this->lang->line('name'); ?>" class="input-medium" >

  &nbsp;&nbsp;&nbsp;
  <?php echo $this->lang->line('sort'); ?>
  <select name="order" class="input-small" style="width: 180px;">
  <option value="db_ip" <?php if($setval['order']=='db_ip') echo "selected"; ?> ><?php echo $this->lang->line('host'); ?></option>
  <option value="tb_count" <?php if($setval['order']=='tb_count') echo "selected"; ?> ><?php echo $this->lang->line('table').$this->lang->line('counts'); ?></option>
  <option value="data_size_m" <?php if($setval['order']=='data_size_m') echo "selected"; ?> ><?php echo $this->lang->line('size'); ?></option>
  </select>
  
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('lp_mysql/databases') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>
</form>                
</div>


<div class="well">
  <table class="table table-hover table-condensed ">
    <thead>
      <tr style="font-size: 12px;">
        <th><?php echo $this->lang->line('host'); ?></th> 
	<th><?php echo $this->lang->line('db_name'); ?></th>
        <th><?php echo $this->lang->line('table_count'); ?></th>
	<th><?php echo $this->lang->line('total_size'); ?>(MB)</th>
        <th><?php echo $this->lang->line('time'); ?></th>
      </tr>
    </thead>
  <tbody>
 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 12px;">
        <td><?php echo $item['db_ip'] ?>:<?php echo $item['db_port'] ?></td>
        <td><?php echo $item['db_name'] ?></td>
        <td><?php echo $item['tb_count'] ?></td>
        <td><?php echo $item['data_size_m'] ?></td>
        <td><?php echo $item['create_time'] ?></td>
    </tr>
 <?php endforeach;?>
 <?php }else{  ?>
<tr>
<td colspan="12">
<font color="red"><?php echo $this->lang->line('no_record'); ?></font>
</td>
</tr>
<?php } ?>      
      </tbody>
    </table>
</div>

 <script type="text/javascript">
    $('#refresh').click(function(){
        document.location.reload(); 
    })
 </script>

