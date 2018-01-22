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
				colors: ["#FF0000","#CC0000","#00CC00","#00FF00"],
				groupPadding: 0,
				pointPadding: 0.1
			}
		},
		series: [
			{
				name: "Non-Elim Wins",
				data: non_elim_wins,
				colorByPoint: true,
				colors: ["#CC0000","#00CC00"]
			},
			{
				name: "Elim Wins",
				data: elim_wins,
				colorByPoint: true,
				colors: ["#FF0000","#00FF00"]
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

function renderBoxPlot(data) {
	var mvp = data['overall_mvp'];

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
}

function updateBoxPlot(color) {

	var data_url = '/players/allPlayersOverallMVP.json';
	if(color === 'red') {
		data_url = '/players/allPlayersOverallMVP/red.json';
	} else if(color === 'green') {
		data_url = '/players/allPlayersOverallMVP/green.json';
	}
	
	$.ajax({
		type: 'get',
		url: data_url,
		dataType: 'json',
		success: function(data) {
			renderBoxPlot(data);
		},
		error: function() {
			alert('Failed to retrieve box plot data');
		}
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
			alert('Failed to retrieve data');
		}
	});

	updateBoxPlot('all');
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