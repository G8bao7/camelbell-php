<div class="header">            
  <h1 class="page-title"><?php echo $this->lang->line('_Mysql'); ?> <?php echo $this->lang->line('_Scan App DBConfig'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Tools'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Scan App DBConfig'); ?></li>
    <span class="right"><?php echo 'Total'; ?>:<?php if(!empty($conf_count)){ echo $conf_count;} else {echo '0';} ?></span>

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
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('tools/scan_alto_dbconfig') ?>" >
  Key &nbsp;
  <input type="text" id="find_key"  name="find_key" value="<?php echo $setval['find_key']; ?>" placeholder="find key" class="input-medium" > 
  
  <?php echo $this->lang->line('env'); ?>
  <select name="env" class="input-small" style="width: 130px;">
      <option value="ALL" <?php if($setval['env']== 'ALL' ) echo "selected"; ?> > All </option>
      <option value="protest" <?php if($setval['env']== 'protest' ) echo "selected"; ?> > 沙箱 </option>
      <option value="progray" <?php if($setval['env']== 'progray' ) echo "selected"; ?> > 灰度 </option>
      <option value="lgproduction" <?php if($setval['env']== 'lgproduction' ) echo "selected"; ?> > 鲁谷读写 </option>
      <option value="lgreadonlyproduction" <?php if($setval['env']== 'lgreadonlyproduction' ) echo "selected"; ?> > 鲁谷只读 </option>
      <option value="hkreadonlyproduction" <?php if($setval['env']== 'hkreadonlyproduction' ) echo "selected"; ?> > 香港只读 </option>
      <option value="multi-language" <?php if($setval['env']== 'multi-language' ) echo "selected"; ?> > 多语言 </option>
      <option value="dzmreadonlyproduction" <?php if($setval['env']== 'dzmreadonlyproduction' ) echo "selected"; ?> > 东直门只读 </option>
      <option value="infoproduction" <?php if($setval['env']== 'infoproduction' ) echo "selected"; ?> > 信息站 </option>
  </select>
  
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('tools/scan_alto_dbconfig') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

</form>                 
</div>

<div class="well">
    <table class="table table-hover table-condensed  table-bordered">
      <thead>
       <tr>
        <th><?php echo $this->lang->line('env'); ?><?php echo $this->lang->line('name'); ?></th>
        <th><?php echo $this->lang->line('app'); ?><?php echo $this->lang->line('name'); ?></th>
        <th><?php echo $this->lang->line('file'); ?><?php echo $this->lang->line('counts'); ?></th>
        <th><?php echo $this->lang->line('remark'); ?></th>
	</tr>
      </thead>

      <tbody>
 
  <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
   <tr style="font-size: 12px;">
	<td><?php echo $item['env_name'] ?></td>
	<td><?php echo $item['app_name'] ?></td>
	<td><?php echo $item['fnum'] ?></td>

	<?php if($item['fnum'] == 1) {?>
	<td><?php echo $item['remark'] ?></td>
	<?php }else{  ?>
         <td>
         <div class="message_head"><cite><?php echo substring("+--".$item['remark'],0,60); ?></cite></div>
	    <div class="message_body" style="width: 600px;">
	    <pre><span style="color: blue;"><?php echo $item['remark'] ?></span></pre>
	    </div>
	</td>
	<?php } ?>
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
