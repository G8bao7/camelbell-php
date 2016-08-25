<script src="lib/bootstrap/js/jquery.pin.js"></script>

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="header">
    <div class="stats">
    
    <p class="tools"><span class="number"></span>Tools</p>
    
    </div>
<h1 class="page-title"><?php echo $this->lang->line('dashboard'); ?></h1>
</div>
        
<ul class="breadcrumb">
    <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
    <li class="active"><?php echo $this->lang->line('dashboard'); ?></li><span class="divider">/</span></li>
</ul>

 

<div class="container-fluid">
<div class="row-fluid">
 
<script src="lib/bootstrap/js/bootstrap-switch.js"></script>
<link href="lib/bootstrap/css/bootstrap-switch.css" rel="stylesheet"/>
                    
<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search"></span>                 
<form name="form" class="form-inline" method="get" action="<?php echo site_url('tools/index') ?>" >
  

</form>                
</div>


<div id='dbstatus' class="well monitor " style=" <?php if($this->input->cookie('lang_current')=='zh-hans') echo  'font-family: 微软雅黑;' ?>  ;">
<p><span style="float: left; margin-right: .3em;" class="ui-icon ui-icon-search">aa</span> 
</div>

<script type="text/javascript">
     $(function(){
		// tooltip demo
    	$('.tooltip-lepus').tooltip({
      		selector: "a[data-toggle=tooltip]"
    	})
		
	 })
	

$(".thead").pin()
 </script>




