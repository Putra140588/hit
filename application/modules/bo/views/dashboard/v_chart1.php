<div class="panel panel-info">
	<div class="panel-heading">
		<h4><i class="icon-bar-chart"></i> <?php echo $title?></h4>
	</div>
	<div class="panel-body">
		<div id="chart1"></div>
	</div>
</div>

<script type="text/javascript">
$(function () {
    $('#chart1').highcharts({
        title: {
            text: 'Complete Vs Outstanding Opname'
        },
        subtitle: {
            text: 'Source: <?php echo base_url(); ?>'
        },
        xAxis: {
        	categories: [<?php echo $month?>],
        	                   	         
        	         crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Presentase Values'
            }
        },        
        series: [{
            type: 'column',
            name: 'Complete',
            data: [<?php echo $complete?>]
        }, {
            type: 'column',
            name: 'Outstanding',
            data: [<?php echo $outstanding?>]
        },{
            type: 'column',
            name: 'Main Asset',
            data: [<?php echo $mainasset?>]
        }]
        
    });
});
		</script>