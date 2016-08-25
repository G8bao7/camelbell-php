<div class="header">            
            <h1 class="page-title"><?php echo $this->lang->line('_MySQL'); ?> <?php echo $this->lang->line('_Slowquery Analysis'); ?></h1>
</div>
        
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('_MySQL Monitor'); ?></li><span class="divider">/</span></li>
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
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('lp_mysql/slowquery') ?>" >
  <input type="hidden" name="search" value="submit" />
  <!--
  <select name="server_id" class="input-large" style="width:230px"  >
  <option value=""><?php echo $this->lang->line('host'); ?></option>
  <?php foreach ($server as $item):?>
  <option value="<?php echo $item['id'];?>" <?php if($setval['server_id']==$item['id']) echo "selected"; ?> ><?php echo $item['host'];?>:<?php echo $item['port'];?>(<?php echo $item['tags'];?>)</option>
   <?php endforeach;?>
  </select>
  -->
  <input type="text" id="dbname"  name="dbname" value="<?php echo $setval['dbname'] ?>" placeholder="<?php echo $this->lang->line('please_input_dbname'); ?>" class="input-medium" >
  <input type="text" id="checksum"  name="checksum" value="<?php echo $setval['checksum'] ?>" placeholder="<?php echo $this->lang->line('please_input_checksum'); ?>" class="input-medium" >
  &nbsp;&nbsp;
 
  <?php echo $this->lang->line('time'); ?>
  <input class="Wdate" style="width:100px;" type="text" name="stime" id="start_time>" value="<?php echo $setval['stime'] ?>" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,dateFmt:'yyyy-MM-dd '})"/> -
  <input class="Wdate" style="width:100px;" type="text" name="etime" id="end_time>" value="<?php echo $setval['etime'] ?>" onFocus="WdatePicker({doubleCalendar:false,isShowClear:false,readOnly:false,startDate:'1980-05-01',dateFmt:'yyyy-MM-dd'})"/>
 
  &nbsp;&nbsp;
  <?php echo $this->lang->line('type'); ?>
  <select name="show_type" class="input-small" style="width: 100px;">
  <option value="run_time" <?php if($setval['show_type']=='run_time') echo "selected"; ?> >Run Time</option>
  <option value="first_time" <?php if($setval['show_type']=='first_time') echo "selected"; ?> >First Time</option>
  </select>

  &nbsp;&nbsp;
  <?php echo $this->lang->line('status'); ?>
  <select name="cur_status" class="input-small" style="width: 100px;">
  <option value="0" <?php if($setval['cur_status']=='0') echo "selected"; ?>><?php echo $this->lang->line('slowquery_none'); ?></option>
  <option value="5" <?php if($setval['cur_status']=='5') echo "selected"; ?>><?php echo $this->lang->line('slowquery_index'); ?></option>
  <option value="100" <?php if($setval['cur_status']=='100') echo "selected"; ?>><?php echo $this->lang->line('ignore'); ?></option>
  <option value="200" <?php if($setval['cur_status']=='200') echo "selected"; ?>><?php echo $this->lang->line('optimize_dev'); ?></option>
  <option value="all" <?php if($setval['cur_status']=='all') echo "selected"; ?>>All</option>
  </select>
 
  &nbsp;&nbsp;
  <?php echo $this->lang->line('sort'); ?>
  <select name="order" class="input-small" style="width: 100px;">
  <option value="ts_min" <?php if($setval['order']=='ts_min') echo "selected"; ?> ><?php echo $this->lang->line('time'); ?></option>
  <option value="ts_cnt" <?php if($setval['order']=='ts_cnt') echo "selected"; ?> ><?php echo $this->lang->line('ts_cnt'); ?></option>
  <option value="query_time_pct_95" <?php if($setval['order']=='query_time_pct_95') echo "selected"; ?> ><?php echo $this->lang->line('query_time'); ?></option>
  <option value="lock_time_pct_95" <?php if($setval['order']=='lock_time_pct_95') echo "selected"; ?> ><?php echo $this->lang->line('lock_time'); ?></option>
  <option value="Rows_examined_pct_95" <?php if($setval['order']=='Rows_examined_pct_95') echo "selected"; ?> ><?php echo $this->lang->line('Rows_examined'); ?></option>
  </select>
  <select name="order_type" class="input-small" style="width:80px;">
  <option value="desc" <?php if($setval['order_type']=='desc') echo "selected"; ?> ><?php echo $this->lang->line('desc'); ?></option>
  <option value="asc" <?php if($setval['order_type']=='asc') echo "selected"; ?> ><?php echo $this->lang->line('asc'); ?></option>
  </select>
  
  &nbsp;&nbsp;
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('lp_mysql/slowquery') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

</form>                 
</div>

<div class="well">
    <table class="table table-hover table-condensed " style="font-size: 12px;">
      <thead>
        <tr>
	    <th><?php echo $this->lang->line('checksum'); ?></th>
	    <th><?php echo $this->lang->line('status'); ?></th>
	    <th><?php echo $this->lang->line('database'); ?></th>
	    <th><?php echo $this->lang->line('user'); ?></th>
	    <th><?php echo $this->lang->line('ts_cnt'); ?></th>
	    <th><?php echo $this->lang->line('query_time'); ?></th>
	    <th><?php echo $this->lang->line('lock_time'); ?></th>
	    <th><?php echo $this->lang->line('Rows_examined'); ?></th>
	    <th><?php echo $this->lang->line('time'); ?></th>
       </tr>
      </thead>
      <tbody>
 
  <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr>
        <td><a href="<?php echo site_url('lp_mysql/slowquery_detail/'.$item['checksum']) ?>" target="_blank"  title="<?php echo $this->lang->line('view_detail'); ?>"><?php  echo $item['checksum'] ?></a></td>
        <td>
	<?php if($item['cur_status']==5){echo $this->lang->line('slowquery_index');}elseif($item['cur_status']==100){echo $this->lang->line('ignore');}elseif($item['cur_status']==200){echo $this->lang->line('optimize_dev');}else{echo $this->lang->line('slowquery_none');} ?>
	</td>
         
         <td>
         <div class="message_head"><cite><?php echo substring("+".$item['db_max'],0,35); ?></cite></div>
	    <div class="message_body" style="width: 200px;">
	    <pre><span style="color: blue;"><?php echo $item['hostname_max'] ?>/<?php echo $item['db_max'] ?></span></pre>
	    </div>
	</td>
        <td><?php echo $item['user_max'] ?></td>
        <td><?php echo $item['ts_cnt'] ?></td>
        <td><?php echo $item['Query_time_pct_95'] ?></td>
        <td><?php echo $item['Lock_time_pct_95'] ?></td>
	<td><?php echo $item['Rows_examined_pct_95'] ?></td>
        <td><?php echo $item['ts_min'] ?></td>
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
