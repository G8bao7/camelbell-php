<div class="header">            
    <h1 class="page-title"><?php echo $this->lang->line('_Oracle'); ?> <?php echo $this->lang->line('_Slowquery Analysis'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Oracle Monitor'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Slowquery Analysis'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">
 
<script type="text/javascript">
$(document).ready(function(){
  
	//hide message_body after the first one
	//$(".table .message_body:gt(0)").hide();
    $(".table .message_body").hide();
	$(".collpase_all_message").hide();
	
	//toggle message_body
	$(".message_head").click(function(){
		$(this).next(".message_body").slideToggle(200)
		return false;
	});

    //collapse all messages
	$(".collpase_all_message").click(function(){
	   		$(this).hide()
		$(".show_all_message").show()
		$(".message_body").slideUp(200)
		return false;
	});

	//show all messages
	$(".show_all_message").click(function(){
		$(this).hide()
		$(".collpase_all_message").show()
		$(".message_body").slideDown()
		return false;
	});

});

</script>
<style type="text/css">

/* message display page */

.message_head {
	padding: 2px 5px;
	cursor: pointer;
	position: relative;
}

.message_head cite {
	font-size: 100%;
	font-weight: bold;
	font-style: normal;
}

</style>

<script language="javascript" src="./lib/DatePicker/WdatePicker.js"></script>

<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span class="ui-icon ui-icon-search" style="float: left; margin-right: .3em;"></span>
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('lp_oracle/slowquery') ?>" >
  <input type="text" id="host"  name="host" value="<?php echo $setval['host'] ?>" placeholder="<?php echo $this->lang->line('please_input_host'); ?>" class="input-small" >
  <input type="text" id="sql_id"  name="sql_id" value="<?php echo $setval['sql_id'] ?>" placeholder="<?php echo $this->lang->line('please_input_sql_id'); ?>" class="input-medium" >
  &nbsp;
  <?php echo $this->lang->line('time'); ?>
  <input class="Wdate" style="width:120px;" type="text" name="stime" id="start_time>" value="<?php echo $setval['stime'] ?>" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,dateFmt:'yyyy-MM-dd HH:mm'})"/> -
  <input class="Wdate" style="width:120px;" type="text" name="etime" id="end_time>" value="<?php echo $setval['etime'] ?>" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,startDate:'1980-05-01',dateFmt:'yyyy-MM-dd HH:mm'})"/>
  &nbsp;
  <?php echo $this->lang->line('type'); ?>
  <select name="show_type" class="input-small" style="width: 100px;">
  <option value="run_time" <?php if($setval['show_type']=='run_time') echo "selected"; ?> >Run Time</option>
  <option value="first_time" <?php if($setval['show_type']=='first_time') echo "selected"; ?> >First Time</option>
  </select>
  
  &nbsp;
  <?php echo $this->lang->line('sort'); ?>
  <select name="order" class="input-small" style="width: 100px;">
  <option value="elapsed_time_per_exec" <?php if($setval['order']=='elapsed_time_per_exec') echo "selected"; ?> ><?php echo $this->lang->line('elapsed_time_per_exec'); ?></option>
  <option value="executions" <?php if($setval['order']=='executions') echo "selected"; ?> ><?php echo $this->lang->line('executions'); ?></option>
  <option value="gets_per_exec" <?php if($setval['order']=='gets_per_exec') echo "selected"; ?> ><?php echo $this->lang->line('gets_per_exec'); ?></option>
  <option value="elapsed_pct" <?php if($setval['order']=='elapsed_pct') echo "selected"; ?> ><?php echo $this->lang->line('elapsed_pct'); ?></option>
  <option value="stat_date" <?php if($setval['order']=='stat_date') echo "selected"; ?> ><?php echo $this->lang->line('stat_time'); ?></option>
  </select>

  <select name="order_type" class="input-small" style="width:80px;">
  <option value="desc" <?php if($setval['order_type']=='desc') echo "selected"; ?> ><?php echo $this->lang->line('desc'); ?></option>
  <option value="asc" <?php if($setval['order_type']=='asc') echo "selected"; ?> ><?php echo $this->lang->line('asc'); ?></option>
  </select>
  &nbsp;
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('lp_oracle/slowquery') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

  <!--添加一个空的表单变量让URL中order_type=desc?noparam=0变成order_type=desc&noparam=0?，使order by生效  -->
  <input type="hidden" name="noparam" value="0" />
</form>                 
</div>

<div class="well">
    <table class="table table-hover table-condensed " style="font-size: 12px;">
      <thead>
        <tr>
	    <th><?php echo $this->lang->line('host'); ?></th>
	    <th><?php echo $this->lang->line('sql_id'); ?></th>
	    <th><?php echo $this->lang->line('executions'); ?></th>
	    <th><?php echo $this->lang->line('elapsed_pct'); ?></th>
	    <th><?php echo $this->lang->line('elapsed_time_per_exec'); ?></th>
	    <th><?php echo $this->lang->line('gets_per_exec'); ?></th>
	    <th><?php echo $this->lang->line('reads_per_exec'); ?></th>
	    <th><?php echo $this->lang->line('physical_read_reqs'); ?></th>
	    <th><?php echo $this->lang->line('module'); ?></th>
	    <?php if($setval['show_type']=='first_time'){ ?>
	    <th><?php echo $this->lang->line('time'); ?></th>
	    <?php }else{ ?>
	    <th><?php echo $this->lang->line('stat_time'); ?></th>
	    <?php } ?>
	    <th></th>
	    <th></th>
       </tr>
      </thead>
      <tbody>
 
  <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist as $item):?>

    <tr>
	<?php if($setval['show_type']=='first_time'){ ?>
        <td><?php echo $item['host'] ?></td>
	<?php }else{ ?>
        <td><?php echo $item['host'].':'.$item['port'].'-'.$item['instance_num'] ?></td>
	<?php } ?>
        <td><?php echo $item['sql_id'] ?></td>
        <td><?php echo $item['executions'] ?></td>
        <td><?php echo $item['elapsed_pct'] ?></td>
        <td><?php echo $item['elapsed_time_per_exec'] ?></td>
        <td><?php echo $item['gets_per_exec'] ?></td>
        <td><?php echo $item['reads_per_exec'] ?></td>
        <td><?php echo $item['physical_read_reqs'] ?></td>
        <td><?php echo $item['module'] ?></td>
	<td><?php echo $item['stat_date'] ?></td>
	<td><a target="_blank" href="<?php echo '../../../awr/oracle/'.$item['stat_date'].'/'.$item['host'].'-'.$item['port'].'-'.$item['instance_num'].'.html#'.$item['sql_id'] ; ?>">SqlText</a></td>
	<td><a target="_blank" href="<?php echo '../../../awr/oracle/'.$item['stat_date'].'/'.$item['host'].'-'.$item['port'].'-'.$item['instance_num'].'.html' ; ?>">AwrReport</a></td>
	</tr>
 <?php endforeach;?>
<?php }else{  ?>
<tr>
<td colspan="10">
<font color="red"><?php echo $this->lang->line('no_record'); ?></font>
</td>
</tr>
<?php } ?>

</tbody>
    </table>
</div>

<div class="" style="margin-top: 8px;padding: 8px;">
<center><?php echo $this->pagination->create_links(); ?></center>
</div>
