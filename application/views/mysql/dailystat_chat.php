<div class="header">
            
            <h1 class="page-title"><?php echo $this->lang->line('_MySQL'); ?> <?php echo $this->lang->line('_Daily Statistic'); ?> <?php echo $this->lang->line('chart'); ?></h1>
</div>
        
<ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><?php echo $this->lang->line('home'); ?></a> <span class="divider">/</span></li>
            <li><a href="<?php echo site_url('lp_mysql/dailystat'); ?>"><?php echo $this->lang->line('_Daily Statistic'); ?></a></li><span class="divider">/</span></li>
            <li class="active"><?php echo $this->lang->line('chart'); ?></li>
</ul>

<div class="container-fluid">
<div class="row-fluid">

<div class="btn-toolbar">
    <div class="btn-group">
      <a class="btn btn-default <?php if($begin_time=='7') echo 'active'; ?>" href="<?php echo site_url('lp_mysql/dailystat_chart/'.$cur_server_id.'/7/day') ?>"><i class="fui-calendar-16"></i>&nbsp;7 <?php echo $this->lang->line('date_days'); ?></a>
      <a class="btn btn-default <?php if($begin_time=='14') echo 'active'; ?>" href="<?php echo site_url('lp_mysql/dailystat_chart/'.$cur_server_id.'/14/day') ?>"><i class="fui-calendar-16"></i>&nbsp;14 <?php echo $this->lang->line('date_days'); ?></a>
      <a class="btn btn-default <?php if($begin_time=='30') echo 'active'; ?>" href="<?php echo site_url('lp_mysql/dailystat_chart/'.$cur_server_id.'/30/day') ?>"><i class="fui-calendar-16"></i>&nbsp;30 <?php echo $this->lang->line('date_days'); ?></a>
	</div>
</div> <!-- /toolbar -->             
<hr/>

<div id="daily_stat" style="margin-top:10px; margin-left:0px;  width:100%;  height:300px;"></div>


<script src="lib/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./lib/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.pieRenderer.min.js"></script>
<script type="text/javascript" src="./lib/jqplot/plugins/jqplot.donutRenderer.min.js"></script>
<link href="./lib/jqplot/jquery.jqplot.min.css"  rel="stylesheet">


<script type="text/javascript">

//========================= daily stat=========================================//
$(document).ready(function(){  
  var data1=[
    <?php if(!empty($chart_reslut_daily)) { foreach($chart_reslut_daily as $item){ ?>
    ["<?php echo $item['time']?>", <?php echo $item['QPS_MAX']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data2=[
    <?php if(!empty($chart_reslut_daily)) { foreach($chart_reslut_daily as $item){ ?>
    ["<?php echo $item['time']?>", <?php echo $item['QPS_AVG']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data3=[
    <?php if(!empty($chart_reslut_daily)) { foreach($chart_reslut_daily as $item){ ?>
    ["<?php echo $item['time']?>", <?php echo $item['TPS_MAX']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  var data4=[
    <?php if(!empty($chart_reslut_daily)) { foreach($chart_reslut_daily as $item){ ?>
    ["<?php echo $item['time']?>", <?php echo $item['TPS_AVG']?> ],
    <?php }}else{ ?>
    []    
    <?php } ?>
  ];
  // alert(data4);
  
  var plot1 = $.jqplot('daily_stat', [data1,data2,data3,data4], {
    axes:{
        xaxis:{
            renderer:$.jqplot.DateAxisRenderer,
            label: "",
            pad:1.1,
  	    tickInterval: "<?php echo $tickInterval; ?> day",
            tickOptions: {  
                    mark: 'cross',    // 设置横（纵）坐标刻度在坐标轴上显示方式，分为坐标轴内，外，穿过坐标轴显示  
                                // 值也分为：'outside', 'inside' 和 'cross',  
                    showMark: false,     //设置是否显示刻度  
                    showGridLine: true, // 是否在图表区域显示刻度值方向的网格线  
                    markSize:0,        // 每个刻度线顶点距刻度线在坐标轴上点距离（像素为单位）  
                                //如果mark值为 'cross', 那么每个刻度线都有上顶点和下顶点，刻度线与坐标轴  
                                //在刻度线中间交叉，那么这时这个距离×2,  
                    show: true,         // 是否显示刻度线，与刻度线同方向的网格线，以及坐标轴上的刻度值  
                    showLabel: true,    // 是否显示刻度线以及坐标轴上的刻度值  
                    formatString:"%m/%d",   // 梃置坐标轴上刻度值显示格式，eg:'%b %#d, %Y'表示格式"月 日，年"，"AUG 30,2008"  
                    fontSize:'',    //刻度值的字体大小  
                    fontFamily:'Tahoma', //刻度值上字体  
                    angle:40,           //刻度值与坐标轴夹角，角度为坐标轴正向顺时针方向  
                    fontWeight:'normal', //字体的粗细  
                    fontStretch:0,//刻度值在所在方向（坐标轴外）上的伸展(拉伸)度,
            }
        },  
    },
    title: {  
        text: "<?php echo $cur_server; ?> QPS-TPS daily <?php echo $this->lang->line('chart'); ?>",  //        设置当前图的标题  
        show: true,//设置当前标题是否显示 
        fontSize:'13px',    //刻度值的字体大小  
    },
    seriesDefaults: {
              show: true,     // 设置是否渲染整个图表区域（即显示图表中内容）  
              xaxis: 'xaxis', // either 'xaxis' or 'x2axis'.  
              yaxis: 'yaxis', // either 'yaxis' or 'y2axis'.  
              label: '',      // 用于显示在分类名称框中的分类名称  
              color: '',      // 分类在图标中表示（折现，柱状图等）的颜色  
              lineWidth: 1.5, // 分类图（特别是折线图）宽度  
              shadow: true,   // 各图在图表中是否显示阴影区域   
              showLine: true,     //是否显示图表中的折线（折线图中的折线）  
              showMarker: true,   // 是否强调显示图中的数据节点  
              fill: false,        // 是否填充图表中折线下面的区域（填充颜色同折线颜色）以及legend 
              rendererOptions: {
                  smooth: true,
              },
              
    },
    series:[//如果有多个分类需要显示，这在此处设置各个分类的相关配置属性  
           //eg.设置各个分类在分类名称框中的分类名称  
           {label: 'QPS_MAX'},{label: 'QPS_AVG'},{label: 'TPS_MAX'},{label: 'TPS_AVG'}
           //配置参数设置同seriesDefaults  
    ],  
    legend: {  
        show: true, //设置是否出现分类名称框（即所有分类的名称出现在图的某个位置） 
        label:'', 
        location: 'ne',     // 分类名称框出现位置, nw, n, ne, e, se, s, sw, w.  
        xoffset: 2,        // 分类名称框距图表区域上边框的距离（单位px）  
        yoffset: 2,        // 分类名称框距图表区域左边框的距离(单位px)  
        background:'',        //分类名称框距图表区域背景色  
        textColor:''          //分类名称框距图表区域内字体颜色  
    },    
    seriesColors: ["#EAA228", "#4bb2c5", "#953579", "#ff5800", "#0085cc"],  // 默认显示的分类颜色
    highlighter: {
            show: true, 
            showLabel: true, 
            tooltipAxes: '',
            sizeAdjust: 0.5, 
            tooltipLocation : 'ne',
    },
    cursor:{
            show: true, 
            zoom: true
    }  
    
  });
});
</script>
