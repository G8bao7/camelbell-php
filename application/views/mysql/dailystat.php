<div class="header">            
            <h1 class="page-title"><?php echo $this->lang->line('_MySQL'); ?> <?php echo $this->lang->line('_Daily Statistic'); ?></h1>
</div>
        
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_MySQL Monitor'); ?></li><span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_Daily Statistic'); ?></li>
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
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('lp_mysql/dailystat') ?>" >
  <input type="hidden" name="search" value="submit" />
  
  <select name="server_id" class="input-large" style="width:230px"  >
  <option value=""><?php echo $this->lang->line('host'); ?></option>
  <?php foreach ($server as $item):?>
  <option value="<?php echo $item['id'];?>" <?php if($setval['server_id']==$item['id']) echo "selected"; ?> ><?php echo $item['host'];?>:<?php echo $item['port'];?>(<?php echo $item['tags'];?>)</option>
   <?php endforeach;?>
  </select>
  
  <?php echo $this->lang->line('time'); ?>
  <input class="Wdate" style="width:130px;" type="text" name="stime" id="start_time>" value="<?php echo $setval['stime'] ?>" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,dateFmt:'yyyy-MM-dd'})"/> -
  <input class="Wdate" style="width:130px;" type="text" name="etime" id="end_time>" value="<?php echo $setval['etime'] ?>" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,startDate:'1980-05-01',dateFmt:'yyyy-MM-dd'})"/>
 <!-- 
  <select name="days" class="input-small" style="width: 130px;">
  <option value="1" <?php if($setval['days']=='1') echo "selected"; ?> >1<?php echo $this->lang->line('date_day'); ?></option>
  <option value="3" <?php if($setval['days']=='3') echo "selected"; ?> >3<?php echo $this->lang->line('date_day'); ?></option>
  </select>
-->
  <?php echo $this->lang->line('sort'); ?>
  <select name="order" class="input-small" style="width: 130px;">
  <option value="tps_avg" <?php if($setval['order']=='tps_avg') echo "selected"; ?> ><?php echo $this->lang->line('avg'); ?><?php echo $this->lang->line('tps'); ?></option>
  <option value="qps_avg" <?php if($setval['order']=='qps_avg') echo "selected"; ?> ><?php echo $this->lang->line('avg'); ?><?php echo $this->lang->line('qps'); ?></option>
  <option value="tps_max" <?php if($setval['order']=='tps_max') echo "selected"; ?> ><?php echo $this->lang->line('max'); ?><?php echo $this->lang->line('tps'); ?></option>
  <option value="qps_max" <?php if($setval['order']=='qps_max') echo "selected"; ?> ><?php echo $this->lang->line('max'); ?><?php echo $this->lang->line('qps'); ?></option>
  <option value="disk_size_m" <?php if($setval['order']=='disk_size_m') echo "selected"; ?> ><?php echo $this->lang->line('disk_size'); ?></option>
  </select>
  <select name="order_type" class="input-small" style="width:80px;">
  <option value="desc" <?php if($setval['order_type']=='desc') echo "selected"; ?> ><?php echo $this->lang->line('desc'); ?></option>
  <option value="asc" <?php if($setval['order_type']=='asc') echo "selected"; ?> ><?php echo $this->lang->line('asc'); ?></option>
  </select>
  
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('lp_mysql/dailystat') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

  <!--添加一个空的表单变量让URL中order_type=desc?noparam=0变成order_type=desc&noparam=0?，使order by生效  -->
  <input type="hidden" name="noparam" value="0" />
</form>                 
</div>

<div class="well">
    <table class="table table-hover table-condensed  table-bordered">
      <thead>

       <tr style="font-size: 12px;">
	<th colspan="2"></th>
	<th colspan="2"><center><?php echo $this->lang->line('qps'); ?></center></th>
	<th colspan="2"><center><?php echo $this->lang->line('tps'); ?></center></th>
        <th colspan="2"><center></center></th>
       </tr>
       <tr>
        <th><?php echo $this->lang->line('host'); ?></th>
        <th><?php echo $this->lang->line('tags'); ?></th>
        <th><?php echo $this->lang->line('avg'); ?></th>
        <th><?php echo $this->lang->line('max'); ?></th>
        <th><?php echo $this->lang->line('avg'); ?></th>
        <th><?php echo $this->lang->line('max'); ?></th>
	<!--
        <th><?php echo $this->lang->line('disk_size'); ?>(M)</th>
	-->
        <th><?php echo $this->lang->line('stat_time'); ?></th>
        <th><?php echo $this->lang->line('trend'); ?> <?php echo $this->lang->line('chart'); ?></th>
	</tr>
      </thead>

      <tbody>
 
  <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
   <tr style="font-size: 12px;">
        <td><?php echo $item['host'] ?>:<?php echo $item['port'] ?></td>
	<td><?php echo $item['tags'] ?></td>
        <td><?php echo $item['qps_avg'] ?></td>
        <td><?php echo $item['qps_max'] ?></td>
        <td><?php echo $item['tps_avg'] ?></td>
        <td><?php echo $item['tps_max'] ?></td>
	<!--
        <td><?php echo $item['disk_size_m'] ?></td>
	-->
	<td><?php echo $item['stat_date'] ?></td>
	<td><a href="<?php echo site_url('lp_'.$item['db_type'].'/dailystat_chart/'.$item['server_id']); ?>"><img src="./images/chart.gif"/></a></td>
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
