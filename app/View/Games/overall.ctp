<?php
	echo $this->Html->script('highcharts.js');
	echo $this->Html->script('highcharts-more.js');
?>

<script class="code" type="text/javascript">


function overallData(data) {
	var winloss = [['Red Wins', data['winloss']['red_wins']], ['Green Wins', data['winloss']['green_wins']]];
	var winlossdetail = [
		['Elim Wins from Red', data['winlossdetail']['elim_wins_from_red']],
		['Non-Elim Wins from Red', data['winlossdetail']['non_elim_wins_from_red']],
		['Elim Wins from Green', data['winlossdetail']['elim_wins_from_green']],
		['Non-Elim Wins from Green', data['winlossdetail']['non_elim_wins_from_green']],
	];
	var mvp = data['overall_mvp'];
	var commander_max = mvp['commander_max'];
	
	$('#win_loss_pie').highcharts({
		chart: {
			type: 'pie'
		},
		title: {
			text: ''
		},
		yAxis: {
			title: {
				text: 'Wins'
			}
		},
		plotOptions: {
			pie: {
				shadow: false,
				center: ['50%', '50%']
			}
		},
		series: [{
			data: winloss,
			size: '60%',
			dataLabels: {
				formatter: function() {
					return this.y > 0 ? this.point.name : null;
				},
				color: 'white',
				distance: -30
			}
		}, {
			data: winlossdetail,
			colors: [
				'#FF0000',
				'#CC0000',
				'#00FF00',
				'#00CC00'
			],
			size: '80%',
			innerSize: '60%',
			dataLabels: {
				formatter: function() {
					return this.y > 0 ? '<b>'+ this.point.name +':</b> '+ this.y  : null;
				}
			}
		}]
	});
	
	$('#mvp_box_plot').highcharts({
		chart: {
			type: 'boxplot'
		},
		title: {
			text: ''
		},
		yAxis: {
			title: {
				text: 'MVP'
			}
		},
		xAxis: {
			categories: ['Commander', 'Heavy', 'Scout', 'Ammo', 'Medic'],
			title: {
				text: 'Position'
			}	
		},
		plotOptions: {
			boxplot: {
                fillColor: '#F0F0E0',
                lineWidth: 2,
                medianColor: '#0C5DA5',
                medianWidth: 3,
                stemColor: '#A63400',
                stemDashStyle: 'dot',
                stemWidth: 1,
                whiskerColor: '#3D9200',
                whiskerLength: '20%',
                whiskerWidth: 3
			}
		},
		series: [{
			name: 'Positions',
			data: [
				[mvp['commander_min'], mvp['commander_lower'], mvp['commander'], mvp['commander_upper'], mvp['commander_max']],
				[mvp['heavy_min'], mvp['heavy_lower'], mvp['heavy'], mvp['heavy_upper'], mvp['heavy_max']],
				[mvp['scout_min'], mvp['scout_lower'], mvp['scout'], mvp['scout_upper'], mvp['scout_max']],
				[mvp['ammo_min'], mvp['ammo_lower'], mvp['ammo'], mvp['ammo_upper'], mvp['ammo_max']],
				[mvp['medic_min'], mvp['medic_lower'], mvp['medic'], mvp['medic_upper'], mvp['medic_max']]
			]
		}]
	});

	$('#avg_positions').DataTable( {
		"destroy": true,
		"autoWidth": false,
		"searching": false,
		"info": false,
		"paging": false,
		"ordering": false,
		"data" : data['averages'],
		"columns" : [
			{ "data" : "position"},
			{ "data" : "avg_score", "render" : function(data, type, row, meta) {return parseFloat(data).toFixed(2);}},
			{ "data" : "avg_mvp", "render" : function(data, type, row, meta) {return parseFloat(data).toFixed(2);}}
		],
	});


	$('#avg_scores').DataTable( {
		"destroy": true,
		"autoWidth": false,
		"searching": false,
		"info": false,
		"paging": false,
		"ordering": false,
		"data" : data['scoredetail'],
		"columns" : [
			{ "data" : "Game"},
			{ "data" : "green_score", "render" : function(data, type, row, meta) {return parseFloat(data).toFixed(2);} },
			{ "data" : "red_score", "render" : function(data, type, row, meta) {return parseFloat(data).toFixed(2);}}
		],
	});
}

$(document).ready(function(){	
	$.ajax({
		type: 'get',
		url: '<?php echo html_entity_decode($this->Html->url(array('action' => 'overallWinLossDetail', 'ext' => 'json'))); ?>',
		dataType: 'json',
		success: function(data) {
			overallData(data);
		},
		error: function() {
			alert('fail');
		}
	});
});
</script>
<div id="winloss_panel" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#winloss_panel" data-target="#collapse_winloss" role="tab" id="winloss_heading">
		<h4 class="panel-title">
			Wins & Losses
		</h4>
	</div>
	<div id="collapse_winloss" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div id="win_loss_pie" style="height: 500px; width: 800px"></div>
		</div>
	</div>
</div>
<div id="boxplot_panel" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#boxplot_panel" data-target="#collapse_boxplot" role="tab" id="boxplot_heading">
		<h4 class="panel-title">
			Median MVP
		</h4>
	</div>
	<div id="collapse_boxplot" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div id="mvp_box_plot" style="height: 500px; width: 800px"></div>
		</div>
	</div>
</div>
<div id="avg_pos_panel" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#avg_pos_panel" data-target="#collapse_avg_pos" role="tab" id="avg_pos_heading">
		<h4 class="panel-title">
			Averages By Position
		</h4>
	</div>
	<div id="collapse_avg_pos" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="table table-striped table-bordered table-hover" id="avg_positions">
				<thead>
					<th>Position</th>
					<th>Average Score</th>
					<th>Average MVP</th>
				<thead>
			</table>
		</div>
	</div>
</div>
<div id="avg_score_panel" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#avg_score_panel" data-target="#collapse_avg_score" role="tab" id="avg_score_heading">
		<h4 class="panel-title">
			Average Team Scores
		</h4>
	</div>
	<div id="collapse_avg_score" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="table table-striped table-bordered table-hover" id="avg_scores">
				<thead>
					<th>Win Type</th>
					<th>Green Score</th>
					<th>Red Score</th>
				<thead>
			</table>
		</div>
	</div>
</div>