<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>

<script class="code" type="text/javascript">

function overallData(data) {
	var non_elim_wins = [["Non-Elim Wins", data['winlossdetail']['non_elim_wins_from_red']],["Non-Elim Wins",data['winlossdetail']['non_elim_wins_from_green']]];
	var elim_wins = [["Elim Wins", data['winlossdetail']['elim_wins_from_red']],["Elim Wins",data['winlossdetail']['elim_wins_from_green']]];

	$('#win_loss_chart').highcharts({
		chart: {
			type: 'bar',
			height: '200px'
		},
		title: {
			text: null
		},
		xAxis: {
			categories: ['Red','Green']
		},
		yAxis: {
			title: {
				text: 'Wins'
			}
		},
		tooltip: {
			pointFormat: "{point.y}"
		},
		legend: {
			enabled: false
		},
		plotOptions: {
			bar: {
				stacking: 'normal',
				groupPadding: 0,
				pointPadding: 0.1
			}
		},
		series: [
			{
				name: "Non-Elim Wins",
				data: non_elim_wins,
				colorByPoint: true,
				colors: ["#D7280B","#2A9351"]
			},
			{
				name: "Elim Wins",
				data: elim_wins,
				colorByPoint: true,
				colors: ["#F04124","#43ac6a"]
			}
		]
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

function renderBoxPlot(all, red, green) {
	var all_mvp = all['overall_mvp'];
	var red_mvp = red['overall_mvp'];
	var green_mvp = green['overall_mvp'];

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
		series: [
			{
				name: 'Red',
				data: [
					[red_mvp['commander_min'], red_mvp['commander_lower'], red_mvp['commander'], red_mvp['commander_upper'], red_mvp['commander_max']],
					[red_mvp['heavy_min'], red_mvp['heavy_lower'], red_mvp['heavy'], red_mvp['heavy_upper'], red_mvp['heavy_max']],
					[red_mvp['scout_min'], red_mvp['scout_lower'], red_mvp['scout'], red_mvp['scout_upper'], red_mvp['scout_max']],
					[red_mvp['ammo_min'], red_mvp['ammo_lower'], red_mvp['ammo'], red_mvp['ammo_upper'], red_mvp['ammo_max']],
					[red_mvp['medic_min'], red_mvp['medic_lower'], red_mvp['medic'], red_mvp['medic_upper'], red_mvp['medic_max']]
				],
				visible: false,
				color: '#F04124'
			},
			{
				name: 'All',
				data: [
					[all_mvp['commander_min'], all_mvp['commander_lower'], all_mvp['commander'], all_mvp['commander_upper'], all_mvp['commander_max']],
					[all_mvp['heavy_min'], all_mvp['heavy_lower'], all_mvp['heavy'], all_mvp['heavy_upper'], all_mvp['heavy_max']],
					[all_mvp['scout_min'], all_mvp['scout_lower'], all_mvp['scout'], all_mvp['scout_upper'], all_mvp['scout_max']],
					[all_mvp['ammo_min'], all_mvp['ammo_lower'], all_mvp['ammo'], all_mvp['ammo_upper'], all_mvp['ammo_max']],
					[all_mvp['medic_min'], all_mvp['medic_lower'], all_mvp['medic'], all_mvp['medic_upper'], all_mvp['medic_max']]
				],
				color: '#008CBA'
			},
			{
				name: 'Green',
				data: [
					[green_mvp['commander_min'], green_mvp['commander_lower'], green_mvp['commander'], green_mvp['commander_upper'], green_mvp['commander_max']],
					[green_mvp['heavy_min'], green_mvp['heavy_lower'], green_mvp['heavy'], green_mvp['heavy_upper'], green_mvp['heavy_max']],
					[green_mvp['scout_min'], green_mvp['scout_lower'], green_mvp['scout'], green_mvp['scout_upper'], green_mvp['scout_max']],
					[green_mvp['ammo_min'], green_mvp['ammo_lower'], green_mvp['ammo'], green_mvp['ammo_upper'], green_mvp['ammo_max']],
					[green_mvp['medic_min'], green_mvp['medic_lower'], green_mvp['medic'], green_mvp['medic_upper'], green_mvp['medic_max']]
				],
				visible: false,
				color: '#43AC6A'
			},
		]
	});
}

function updateBoxPlot() {
	$.when(
		$.ajax({
			url: '/players/allPlayersOverallMVP.json',
		}),
		$.ajax({
			url: '/players/allPlayersOverallMVP/red.json',
		}),
		$.ajax({
			url: '/players/allPlayersOverallMVP/green.json',
		})
	).done(function(all, red, green) {
		renderBoxPlot(all[0], red[0], green[0]);
	});
}



$(document).ready(function(){	
	$.ajax({
		url: '<?php echo html_entity_decode($this->Html->url(array('action' => 'overallWinLossDetail', 'ext' => 'json'))); ?>'
	}).done( function(response) {
		overallData(response);
	});

	updateBoxPlot();
});
</script>
<div class="panel panel-info">
	<div class="panel-heading">
		<h4 class="panel-title">
			Wins By Color
		</h4>
	</div>
	<div class="panel-body">
		<div id="win_loss_chart"></div>
	</div>
</div>
<div id="boxplot_panel" class="panel panel-info">
	<div class="panel-heading" id="boxplot_heading">
		<h4 class="panel-title">
			Median MVP
		</h4>
	</div>
	<div class="panel-body">
		<div id="mvp_box_plot" style="height: 500px;"></div>
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