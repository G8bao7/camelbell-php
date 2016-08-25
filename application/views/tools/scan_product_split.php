<div class="header">            
  <h1 class="page-title"><?php echo $this->lang->line('_Mysql'); ?> <?php echo $this->lang->line('_Scan Product Split'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Tools'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Scan Product Split'); ?></li>
    <span class="right"><?php echo 'Total'; ?>:<?php if(!empty($row_count)){ echo $row_count;} else {echo '0';} ?></span>

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
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('tools/scan_product_split') ?>" >
  <input type="text" id="table_name"  name="table_name" value="<?php echo $setval['table_name']; ?>" placeholder="base table name" class="input-medium" > 
  <input type="text" id="str_where"  name="str_where" value="<?php echo $setval['str_where']; ?>" placeholder=" where condition" class="input-medium" >
  &nbsp;&nbsp;
  <?php echo $this->lang->line('parallels'); ?> 
  <input type="text" id="parallels"  name="parallels" value="<?php echo $setval['parallels']; ?>" placeholder="parallels" class="input-medium" > 
  <?php echo $this->lang->line('sleep');echo $this->lang->line('time'); ?> 
  <input type="text" id="sleepms"  name="sleepms" value="<?php echo $setval['sleepms']; ?>" placeholder="parallels" class="input-medium" > 
  ms
  &nbsp;&nbsp;&nbsp;
  <?php echo $this->lang->line('data_source'); ?>
  <select name="data_source" class="input-small" style="width: 130px;">
    <!--
    <option value="rw" <?php if($setval['data_source']== 'rw' ) echo "selected"; ?> > <?php echo $this->lang->line('rw'); ?> </option>
    <option value="ro" <?php if($setval['data_source']== 'ro' ) echo "selected"; ?> > <?php echo $this->lang->line('ro'); ?> </option>
    -->
    <option value="rs" <?php if($setval['data_source']== 'rs' ) echo "selected"; ?> > <?php echo $this->lang->line('rs'); ?> </option>
  </select> 
  <button type="submit" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('search'); ?></button>
  <a href="<?php echo site_url('tools/scan_product_split') ?>" class="btn btn-warning"><i class="icon-repeat"></i> <?php echo $this->lang->line('reset'); ?></a>

</form>
</div>
<div>
Example: table=<font color="blue">product_base</font>, condition=<font color="blue">where itemcode='254889213'</font>
</div>

<div class="well">
    <table class="table table-hover table-condensed  table-bordered">
      <thead>
       <tr>
        <th><?php echo $this->lang->line('productid'); ?></th>
        <th><?php echo $this->lang->line('itemcode'); ?></th>
        <th><?php echo $this->lang->line('supplierid'); ?></th>
        <th><?php echo $this->lang->line('table_name'); ?></th>
	</tr>
      </thead>

    <tbody>
    <?php if(!empty($datalist)) {?>
     <?php foreach ($datalist  as $item):?>
       <tr style="font-size: 12px;">
	    <td><?php echo $item['productid'] ?></td>
	    <td><?php echo $item['itemcode'] ?></td>
	    <td><?php echo $item['supplierid'] ?></td>
	    <td><?php echo $item['tb_name'] ?></td>
	    </tr>
     <?php endforeach;?>
    <?php }else{  ?>
	<tr>
	<td colspan="4">
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
