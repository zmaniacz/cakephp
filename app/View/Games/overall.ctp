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
	$('#mvp_box_plot').highcharts({
		chart: {
			type: 'boxplot'
		},
		title: {
			text: null
		},
		tooltip: {
			pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}<br />' +
						'Maximum: {point.high}<br />' +
						'Upper quartile: {point.q1}<br />' +
						'Median: {point.median}<br />' +
						'Mean: {point.mean}<br />' +
						'Lower quartile: {point.q3}<br />' +
						'Minimum: {point.low}<br />'
		},
		yAxis: {
			title: {
				text: 'MVP'
			}
		},
		xAxis: {
			categories: ['Commander', 'Heavy', 'Scout', 'Ammo', 'Medic'],
			title: {
				text: null
			}	
		},
		plotOptions: {
			boxplot: {
                fillColor: '#eee',
                lineWidth: 2,
                medianColor: '#008cba',
                medianWidth: 3,
                stemColor: '#E99002',
                stemDashStyle: 'dot',
                stemWidth: 1,
                whiskerColor: '#008cba',
                whiskerLength: '20%',
                whiskerWidth: 3
			}
		},
		series: [
			{
				name: 'Red',
				data: red,
				visible: false,
				color: '#F04124'
			},
			{
				name: 'All',
				data: all,
				color: '#5bc0de'
			},
			{
				name: 'Green',
				data: green,
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
		rawData = [all, red, green];

		allData = rawData.map(function(item) {
			item = item[0]['overall_mvp'];
			return [
				{
					low: item['commander_min'],
					q1: item['commander_lower'],
					median: item['commander'],
					q3: item['commander_upper'],
					high: item['commander_max'],
					mean: Math.round(item['commander_avg'] * 100)/100
				},
				{
					low: item['heavy_min'],
					q1: item['heavy_lower'],
					median: item['heavy'],
					q3: item['heavy_upper'],
					high: item['heavy_max'],
					mean: Math.round(item['heavy_avg'] * 100)/100
				},
				{
					low: item['scout_min'],
					q1: item['scout_lower'],
					median: item['scout'],
					q3: item['scout_upper'],
					high: item['scout_max'],
					mean: Math.round(item['scout_avg'] * 100)/100
				},
				{
					low: item['ammo_min'],
					q1: item['ammo_lower'],
					median: item['ammo'],
					q3: item['ammo_upper'],
					high: item['ammo_max'],
					mean: Math.round(item['ammo_avg'] * 100)/100
				},
				{
					low: item['medic_min'],
					q1: item['medic_lower'],
					median: item['medic'],
					q3: item['medic_upper'],
					high: item['medic_max'],
					mean: Math.round(item['medic_avg'] * 100)/100
				}
			];
		});

		renderBoxPlot(allData[0], allData[1], allData[2]);
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