<div class="header">
            
            <h1 class="page-title"><?php echo $this->lang->line('host'); ?> <?php echo $this->lang->line('_Disk'); ?></h1>
</div>
        
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_OS Monitor'); ?></li><span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_Disk'); ?></li>
            <span class="right"><?php echo $this->lang->line('the_latest_acquisition_time'); ?>:<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} else {echo $this->lang->line('the_monitoring_process_is_not_started');} ?></span>
</ul>

<div class="container-fluid">
<div class="row-fluid">
 
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php site_url('lp_os/disk') ?>" >
 
 <input type="text" id="host"  name="host" value="<?php echo $setval['host']; ?>" placeholder="<?php echo $this->lang->line('please_input_host'); ?>" class="input-medium" >
 <input type="text" id="tags"  name="tags" value="<?php echo $setval['tags']; ?>" placeholder="<?php echo $this->lang->line('please_input_tags'); ?>" class="input-medium" >
  
  
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('lp_os/disk') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>
</form>                
</div>

<div class="well">
    <table class="table table-hover table-condensed ">
      <thead>
        <tr style="font-size: 12px;">
        <th><?php echo $this->lang->line('host'); ?></th>
        <th><?php echo $this->lang->line('tags'); ?></th>  
        <th><?php echo $this->lang->line('disk_mounted'); ?></th> 
        <th><?php echo $this->lang->line('disk_total'); ?>(G)</th> 
        <th><?php echo $this->lang->line('Usage'); ?></th>
	<th><?php echo $this->lang->line('time'); ?></th> 
        <th><?php echo $this->lang->line('chart'); ?></th>
	    </tr>
      </thead>
      <tbody>
    <?php if(!empty($datalist)) {?>
    <?php foreach ($datalist as $disk):?>
	<tr style="font-size: 12px;">
	<td><?php echo $disk['ip'] ?></td>
	<td><?php echo $disk['tags'] ?></td>
	<td><?php echo $disk['mounted'] ?></td>
	<td><?php echo round($disk['total_size']/1000000,0) ?></td>
	<td><?php echo $disk['used_rate'] ?>%</td>
	<td><?php echo $disk['create_time'] ?></td>
	<td><a href="<?php echo site_url('lp_os/disk_chart/'.$disk['ip']) ?>"><img src="./images/chart.gif"/></a></td>
	</tr>
 <?php endforeach;?>
 <?php }else{  ?>
    <tr>
    <td colspan="6">
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

