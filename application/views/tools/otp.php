<div class="header">
  <h1 class="page-title"><?php echo $this->lang->line('tools'); ?> <?php echo $this->lang->line('_Otp'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Tools'); ?></li><span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('_Otp'); ?></li>
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
                    
<form name="form" class="form-horizontal" method="post" action="<?php site_url('tools/otp') ?>" >

<div style="height: 20px;">
<button name="submit" type="submit" value="query" class="btn btn-success"><i class="icon-search"></i> <?php echo $this->lang->line('query'); ?></button>
</div>
 
</form>                
</div>


<div class="well">
    <table class="table table-hover table-condensed  table-bordered">
      <thead>
       <tr>
        <th colspan="2"><?php echo $this->lang->line('otp'); ?></th>
        <th colspan="3"><?php echo $otp; ?></th>
	</tr>
      </thead>

</table>
</div>


