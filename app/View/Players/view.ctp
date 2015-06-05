<?php
	echo $this->Html->script('highcharts.js');
	echo $this->Html->script('highcharts-more.js');
	echo $this->Html->script('https://rawgithub.com/laff/technical-indicators/master/technical-indicators.src.js');
	
	$overall_acc_plot = array();
	$overall_score_plot = array();
	$overall_mvp_plot = array();
	foreach($overall[0]['Scorecard'] as $key => $val) {
		$overall_acc_plot[] = ((float)$val['shots_hit']/(float)$val['shots_fired'])*100;
		$overall_score_plot[] = (float)$val['score'];
		$overall_mvp_plot[] = (float)$val['mvp_points'];
	}
	$overall_acc_json = json_encode($overall_acc_plot);
	$overall_score_json = json_encode($overall_score_plot);
	$overall_mvp_json = json_encode($overall_mvp_plot);

	$commander_acc_plot = array();
	$commander_score_plot = array();
	$commander_mvp_plot = array();
	foreach($commander[0]['Scorecard'] as $key => $val) {
		$commander_acc_plot[] = ((float)$val['shots_hit']/(float)$val['shots_fired'])*100;
		$commander_score_plot[] = (float)$val['score'];
		$commander_mvp_plot[] = (float)$val['mvp_points'];
	}
	$commander_acc_json = json_encode($commander_acc_plot);
	$commander_score_json = json_encode($commander_score_plot);
	$commander_mvp_json = json_encode($commander_mvp_plot);

	$heavy_acc_plot = array();
	$heavy_score_plot = array();
	$heavy_mvp_plot = array();
	foreach($heavy[0]['Scorecard'] as $key => $val) {
		$heavy_acc_plot[] = ((float)$val['shots_hit']/(float)$val['shots_fired'])*100;
		$heavy_score_plot[] = (float)$val['score'];
		$heavy_mvp_plot[] = (float)$val['mvp_points'];
	}
	$heavy_acc_json = json_encode($heavy_acc_plot);
	$heavy_score_json = json_encode($heavy_score_plot);
	$heavy_mvp_json = json_encode($heavy_mvp_plot);

	$scout_acc_plot = array();
	$scout_score_plot = array();
	$scout_mvp_plot = array();
	foreach($scout[0]['Scorecard'] as $key => $val) {
		$scout_acc_plot[] = ((float)$val['shots_hit']/(float)$val['shots_fired'])*100;
		$scout_score_plot[] = (float)$val['score'];
		$scout_mvp_plot[] = (float)$val['mvp_points'];
	}
	$scout_acc_json = json_encode($scout_acc_plot);
	$scout_score_json = json_encode($scout_score_plot);
	$scout_mvp_json = json_encode($scout_mvp_plot);

	$ammo_acc_plot = array();
	$ammo_score_plot = array();
	$ammo_mvp_plot = array();
	foreach($ammo[0]['Scorecard'] as $key => $val) {
		$ammo_acc_plot[] = ((float)$val['shots_hit']/(float)$val['shots_fired'])*100;
		$ammo_score_plot[] = (float)$val['score'];
		$ammo_mvp_plot[] = (float)$val['mvp_points'];
	}
	$ammo_acc_json = json_encode($ammo_acc_plot);
	$ammo_score_json = json_encode($ammo_score_plot);
	$ammo_mvp_json = json_encode($ammo_mvp_plot);

	$medic_acc_plot = array();
	$medic_score_plot = array();
	$medic_mvp_plot = array();
	foreach($medic[0]['Scorecard'] as $key => $val) {
		$medic_acc_plot[] = ((float)$val['shots_hit']/(float)$val['shots_fired'])*100;
		$medic_score_plot[] = (float)$val['score'];
		$medic_mvp_plot[] = (float)$val['mvp_points'];
	}
	$medic_acc_json = json_encode($medic_acc_plot);
	$medic_score_json = json_encode($medic_score_plot);
	$medic_mvp_json = json_encode($medic_mvp_plot);
?>

<script class="code" type="text/javascript">
function displayWinLossPie(data) {
	var winloss = [
		['Wins', data['winloss']['wins']],
		['Losses', data['winloss']['losses']]
	];
	var winlossdetail = [
		['Elim Wins from Red', data['winlossdetail']['elim_wins_from_red']],
		['Non-Elim Wins from Red', data['winlossdetail']['non_elim_wins_from_red']],
		['Elim Wins from Green', data['winlossdetail']['elim_wins_from_green']],
		['Non-Elim Wins from Green', data['winlossdetail']['non_elim_wins_from_green']],
		['Elim Losses from Red', data['winlossdetail']['elim_losses_from_red']],
		['Non-Elim Losses from Red', data['winlossdetail']['non_elim_losses_from_red']],
		['Elim Losses from Green', data['winlossdetail']['elim_losses_from_green']],
		['Non-Elim Losses from Green', data['winlossdetail']['non_elim_losses_from_green']]
	];
	
	$('#win_loss_pie').highcharts({
		chart: {
			type: 'pie'
		},
		title: {
			text: 'Wins & Losses'
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
			size: '40%',
			dataLabels: {
				formatter: function() {
					return this.point.name;
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
			size: '60%',
			innerSize: '40%',
			dataLabels: {
				formatter: function() {
					return '<b>'+ this.point.name +':</b> '+ this.y;
				}
			}
		}]
	});
}

function displayPositionSpider(data) {
	var mdn_mvp = [data['player_mdn_mvp']['ammo'],data['player_mdn_mvp']['commander'],data['player_mdn_mvp']['heavy'],data['player_mdn_mvp']['medic'],data['player_mdn_mvp']['scout']];
	var mdn_score = [data['player_mdn_scores']['ammo'],data['player_mdn_scores']['commander'],data['player_mdn_scores']['heavy'],data['player_mdn_scores']['medic'],data['player_mdn_scores']['scout']];
	var ctr_mdn_mvp = [data['center_mdn_mvp']['ammo'],data['center_mdn_mvp']['commander'],data['center_mdn_mvp']['heavy'],data['center_mdn_mvp']['medic'],data['center_mdn_mvp']['scout']];
	var ctr_mdn_score = [data['center_mdn_scores']['ammo'],data['center_mdn_scores']['commander'],data['center_mdn_scores']['heavy'],data['center_mdn_scores']['medic'],data['center_mdn_scores']['scout']];
	
	$('#position_spider').highcharts({
		chart: {
			polar: true,
			type: 'line'
		},
		title: {
			text: 'Position Score vs MVP'
		},
		xAxis: {
			categories: ['Ammo Carrier','Commander','Heavy Weapons','Medic','Scout'],
			tickmarkPlacement: 'on',
			lineWidth: 0
		},
		yAxis: [{
			min: 0,
			max: 25,
			labels: {
				enabled: false
			}
		}, {
			min: 0,
			max: 10000,
			labels: {
				enabled: false
			}
		}],
		series: [{
			name: 'Median MVP',
			data: mdn_mvp,
			marker: {
                symbol: 'circle'
            },
			pointPlacement: 'on',
			yAxis: 0
		}, {
			name: 'Median Score',
			data: mdn_score,
			marker: {
                symbol: 'square'
            },
			pointPlacement: 'on',
			yAxis: 1
		}, {
			name: 'Center Median MVP',
			data: ctr_mdn_mvp,
			marker: {
                symbol: 'circle'
            },
			pointPlacement: 'on',
			dashStyle: 'dash',
			yAxis: 0
		}, {
			name: 'Center Median Score',
			data: ctr_mdn_score,
			marker: {
                symbol: 'square'
            },
			pointPlacement: 'on',
			dashStyle: 'dash',
			yAxis: 1
		}]
	});
}


$(document).ready(function(){
	$.ajax({
		type: 'get',
		url: '<?php echo $this->Html->url(array('action' => 'playerWinLossDetail', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		success: function(data) {
			displayWinLossPie(data);
		},
		error: function() {
			alert('fail');
		}
	});
	
	$.ajax({
		type: 'get',
		url: '<?php echo $this->Html->url(array('action' => 'playerPositionSpider', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		success: function(data) {
			displayPositionSpider(data);
		},
		error: function() {
			alert('fail');
		}
	});
	
	var overall_mvp_data = <?php echo $overall_mvp_json; ?>;
	var commander_mvp_data = <?php echo $commander_mvp_json; ?>;
	var heavy_mvp_data = <?php echo $heavy_mvp_json; ?>;
	var scout_mvp_data = <?php echo $scout_mvp_json; ?>;
	var ammo_mvp_data = <?php echo $ammo_mvp_json; ?>;
	var medic_mvp_data = <?php echo $medic_mvp_json; ?>;
	
	var overall_score_data = <?php echo $overall_score_json; ?>;
	var commander_score_data = <?php echo $commander_score_json; ?>;
	var heavy_score_data = <?php echo $heavy_score_json; ?>;
	var scout_score_data = <?php echo $scout_score_json; ?>;
	var ammo_score_data = <?php echo $ammo_score_json; ?>;
	var medic_score_data = <?php echo $medic_score_json; ?>;

	var overall_acc_data = <?php echo $overall_acc_json; ?>;
	var commander_acc_data = <?php echo $commander_acc_json; ?>;
	var heavy_acc_data = <?php echo $heavy_acc_json; ?>;
	var scout_acc_data = <?php echo $scout_acc_json; ?>;
	var ammo_acc_data = <?php echo $ammo_acc_json; ?>;
	var medic_acc_data = <?php echo $medic_acc_json; ?>;
	
	(overall_mvp_data.length < 1) ? line1 = [null] : "";
	(commander_mvp_data.length < 1) ? line2 = [null] : "";
	(heavy_mvp_data.length < 1) ? line3 = [null] : "";
	(scout_mvp_data.length < 1) ? line4 = [null] : "";
	(ammo_mvp_data.length < 1) ? line5 = [null] : "";
	(medic_mvp_data.length < 1) ? line6 = [null] : "";
	(overall_score_data.length < 1) ? line7 = [null] : "";
	(commander_score_data.length < 1) ? line8 = [null] : "";
	(heavy_score_data.length < 1) ? line9 = [null] : "";
	(scout_score_data.length < 1) ? line10 = [null] : "";
	(ammo_score_data.length < 1) ? line11 = [null] : "";
	(medic_score_data.length < 1) ? line12 = [null] : "";
	
	$('#acc_plot').highcharts({
		chart: {
			alignTicks: false,

		},
		title: {text: 'Accuracy'},
		legend: {
			enabled: true,
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		yAxis: {
			title: {text: 'Accuracy'},
			max: 100,
			tickInterval: 5
		},
		xAxis: [{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: overall_acc_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: commander_acc_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: heavy_acc_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: scout_acc_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: ammo_acc_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: medic_acc_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		}],
		series: [{
			id: 'overall',
			name: 'All Positions (Scatter)',
			type: 'scatter',
			data: overall_acc_data,
			visible: false,
			xAxis: 0
		},
		{
			id: 'commander',
			name: 'Commander (Scatter)',
			type: 'scatter',
			data: commander_acc_data,
			visible: false,
			xAxis: 1
		},
		{
			id: 'heavy',
			name: 'Heavy Weapons (Scatter)',
			type: 'scatter',
			data: heavy_acc_data,
			visible: false,
			xAxis: 2
		},
		{
			id: 'scout',
			name: 'Scout (Scatter)',
			type: 'scatter',
			data: scout_acc_data,
			visible: false,
			xAxis: 3
		},
		{
			id: 'ammo',
			name: 'Ammo Carrier (Scatter)',
			type: 'scatter',
			data: ammo_acc_data,
			visible: false,
			xAxis: 4
		},
		{
			id: 'medic',
			name: 'Medic (Scatter)',
			type: 'scatter',
			data: medic_acc_data,
			visible: false,
			xAxis: 5
		},
		{
			name: 'All Positions',
			type: 'trendline',
			linkedTo: 'overall',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(overall_acc_data.length/10),10),
			xAxis: 0
		},
		{
			name: 'Commander',
			type: 'trendline',
			linkedTo: 'commander',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(commander_acc_data.length/10),10),
			xAxis: 1
		},
		{
			name: 'Heavy Weapons',
			type: 'trendline',
			linkedTo: 'heavy',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(heavy_acc_data.length/10),10),
			xAxis: 2
		},
		{
			name: 'Scout',
			type: 'trendline',
			linkedTo: 'scout',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(scout_acc_data.length/10),10),
			xAxis: 3
		},
		{
			name: 'Ammo Carrier',
			type: 'trendline',
			linkedTo: 'ammo',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(medic_acc_data.length/10),10),
			xAxis: 4
		},
		{
			name: 'Medic',
			type: 'trendline',
			linkedTo: 'medic',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(overall_acc_data.length/10),10),
			xAxis: 5
		}
		]
	});


	$('#mvp_plot').highcharts({
		chart: {
			alignTicks: false
		},
		title: {text: 'MVP Points'},
		legend: {
			enabled: true,
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		yAxis: {
			title: {text: 'Score'},
			max: 25,
			tickInterval: 1
		},
		xAxis: [{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: overall_mvp_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: commander_mvp_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: heavy_mvp_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: scout_mvp_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: ammo_mvp_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: medic_mvp_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		}],
		series: [{
			id: 'overall',
			name: 'All Positions (Scatter)',
			type: 'scatter',
			data: overall_mvp_data,
			visible: false,
			xAxis: 0
		},
		{
			id: 'commander',
			name: 'Commander (Scatter)',
			type: 'scatter',
			data: commander_mvp_data,
			visible: false,
			xAxis: 1
		},
		{
			id: 'heavy',
			name: 'Heavy Weapons (Scatter)',
			type: 'scatter',
			data: heavy_mvp_data,
			visible: false,
			xAxis: 2
		},
		{
			id: 'scout',
			name: 'Scout (Scatter)',
			type: 'scatter',
			data: scout_mvp_data,
			visible: false,
			xAxis: 3
		},
		{
			id: 'ammo',
			name: 'Ammo Carrier (Scatter)',
			type: 'scatter',
			data: ammo_mvp_data,
			visible: false,
			xAxis: 4
		},
		{
			id: 'medic',
			name: 'Medic (Scatter)',
			type: 'scatter',
			data: medic_mvp_data,
			visible: false,
			xAxis: 5
		},
		{
			name: 'All Positions',
			type: 'trendline',
			linkedTo: 'overall',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(overall_mvp_data.length/10),10),
			xAxis: 0
		},
		{
			name: 'Commander',
			type: 'trendline',
			linkedTo: 'commander',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(commander_mvp_data.length/10),10),
			xAxis: 1
		},
		{
			name: 'Heavy Weapons',
			type: 'trendline',
			linkedTo: 'heavy',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(heavy_mvp_data.length/10),10),
			xAxis: 2
		},
		{
			name: 'Scout',
			type: 'trendline',
			linkedTo: 'scout',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(scout_mvp_data.length/10),10),
			xAxis: 3
		},
		{
			name: 'Ammo Carrier',
			type: 'trendline',
			linkedTo: 'ammo',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(ammo_mvp_data.length/10),10),
			xAxis: 4
		},
		{
			name: 'Medic',
			type: 'trendline',
			linkedTo: 'medic',
			algorithm: 'SMA',
			showInLegend: true,
			periods: Math.max(Math.round(medic_mvp_data.length/10),10),
			xAxis: 5
		}
		]
	});


	$('#score_plot').highcharts({
		chart: {
			alignTicks: false
		},
		title: {text: 'Score'},
		legend: {
			enabled: true,
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: [{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: overall_score_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: commander_score_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: heavy_score_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: scout_score_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: ammo_score_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		},
		{
			maxPadding: 0.1,
			minPadding: 0.1,
			min: 5,
			max: medic_score_data.length + 5,
			labels: {
				enabled: false
			},
			tickWidth: 0
		}],
		series: [{
			id: 'overall',
			name: 'All Positions (Scatter)',
			type: 'scatter',
			data: overall_score_data,
			visible: false,
			xAxis: 0
		},
		{
			id: 'commander',
			name: 'Commander (Scatter)',
			type: 'scatter',
			data: commander_score_data,
			visible: false,
			xAxis: 1
		},
		{
			id: 'heavy',
			name: 'Heavy Weapons (Scatter)',
			type: 'scatter',
			data: heavy_score_data,
			visible: false,
			xAxis: 2
		},
		{
			id: 'scout',
			name: 'Scout (Scatter)',
			type: 'scatter',
			data: scout_score_data,
			visible: false,
			xAxis: 3
		},
		{
			id: 'ammo',
			name: 'Ammo Carrier (Scatter)',
			type: 'scatter',
			data: ammo_score_data,
			visible: false,
			xAxis: 4
		},
		{
			id: 'medic',
			name: 'Medic (Scatter)',
			type: 'scatter',
			data: medic_score_data,
			visible: false,
			xAxis: 5
		},
		{
			name: 'All Positions',
			type: 'trendline',
			linkedTo: 'overall',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(overall_score_data.length/10),10),
			xAxis: 0
		},
		{
			name: 'Commander',
			type: 'trendline',
			linkedTo: 'commander',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(commander_score_data.length/10),10),
			xAxis: 1
		},
		{
			name: 'Heavy Weapons',
			type: 'trendline',
			linkedTo: 'heavy',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(heavy_score_data.length/10),10),
			xAxis: 2
		},
		{
			name: 'Scout',
			type: 'trendline',
			linkedTo: 'scout',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(scout_score_data.length/10),10),
			xAxis: 3
		},
		{
			name: 'Ammo Carrier',
			type: 'trendline',
			linkedTo: 'ammo',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(ammo_score_data.length/10),10),
			xAxis: 4
		},
		{
			name: 'Medic',
			type: 'trendline',
			linkedTo: 'medic',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(medic_score_data.length/10),10),
			xAxis: 5
		}
		]
	});

	$('#game_list thead tr th.searchable').each( function () {
		var title = $('#game_list thead th').eq( $(this).index() ).text();
		$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	});

	$("#game_list thead tr th input").on( 'keyup change', function () {
		gameListTable
			.column( $(this).parent().index()+':visible' )
			.search( this.value )
			.draw();
	});

	var gameListTable = $('#game_list').DataTable( {
		"scrollX" : true,
		"deferRender" : true,
		"orderCellsTop" : true,
		"dom": '<"H"lr>t<"F"ip>',
		"ajax" : {
			"url" : "<?php echo $this->Html->url(array('controller' => 'Scorecards', 'action' => 'playerScorecards', $id, 'ext' => 'json')); ?>",
			"dataSrc" : "scorecards"
		},
		"columns" : [
			{ "data" : "Game.game_name", "render" : function(data, type,row, meta) {return '<a href="/games/view/'+row.Game.id+'">'+data+'</a>'}},
			{ "data" : "Game.game_datetime"},
			{"data" : "W", "render" : function(data, typw, row, meta) {if(row.Scorecard.team == row.Game.winner) {return "W";} else {return "L";}} },
			{ "data" : "Scorecard.team"},
			{ "data" : "Scorecard.position" },
			{ "data" : "Scorecard.score" },
			{ "data" : "Scorecard.accuracy", "render" : function(data, type, row, meta) {return parseFloat(data*100).toFixed(2)+'%';} },
			{ "data" : "Scorecard.mvp_points" },
			{ "data" : "Scorecard.lives_left" },
			{ "data" : "Scorecard.shots_left" },
			{ "data" : "Scorecard.shot_opponent" },
			{ "data" : "Scorecard.times_zapped" },
			{ "data" : "Scorecard.shot_opponent", "render" : function(data, type, row, meta) {var diff = (data/row.Scorecard.times_zapped); return diff.toFixed(2);} },
			{ "data" : "Scorecard.missile_hits", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Commander" || row.Scorecard.position == "Heavy Weapons") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.times_missiled" },
			{ "data" : "Scorecard.medic_hits" },
			{ "data" : "Scorecard.medic_nukes", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Commander") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.shot_3hit", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Scout") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.shot_team" },
			{ "data" : "Scorecard.missiled_team", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Commander" || row.Scorecard.position == "Heavy Weapons") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.own_medic_hits" },
			{ "data" : "Scorecard.nukes_activated", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Commander") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.nukes_detonated", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Commander") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.nukes_canceled" },
			{ "data" : "Scorecard.own_nuke_cancels" },
			{ "data" : "Scorecard.scout_rapid", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Scout") {return data;} else {return "-";}} },
			{ "data" : "Scorecard.ammo_boost", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Ammo Carrier") {return data;} else if(row.Scorecard.position == "Medic") {return row.Scorecard.life_boost;} else {return "-";}} },
			{ "data" : "Scorecard.resupplies", "render" : function(data, typw, row, meta) {if(row.Scorecard.position == "Ammo Carrier" || row.Scorecard.position == "Medic") {return data;} else {return "-";}} },
			{ "data" : "Game.pdf_id", "render" : function(data, type, row, meta) { if(data == null)	return 'N/A'; else return '<a href="/pdf/'+data+'.pdf">PDF</a>'; } }
		],
		"order": [[ 1, "desc" ]]
	});
});
</script>
<h1 class="text-info"><?= $overall[0]['Player']['player_name']; ?></h1>
<?php if(sizeof($aliases) > 1): ?>
<p>
	Aliases:
	<ul>
		<?php foreach($aliases as $alias): ?>
		<?php if($alias['PlayersName']['player_name'] != $overall[0]['Player']['player_name']): ?>
		<li><?php echo $alias['PlayersName']['player_name']; ?></li>
		<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</p>
<?php endif; ?>
<div>
	<?php if (AuthComponent::user('id')): ?>
		<a href=<?= $this->Html->url(array('controller' => 'players', 'action' => 'link', $overall[0]['Player']['id'])); ?> class="btn btn-success" role="button">Link</a>
	<?php endif; ?>
</div>
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<li role="presentation" class="active"><a href="#game_list_tab" role="tab" data-toggle="tab">Game List</a></li>
	<li role="presentation"><a href="#overall_tab" role="tab" data-toggle="tab">Overall</a></li>
	<li role="presentation"><a href="#teammates_tab" role="tab" data-toggle="tab">Teammates</a></li>
</ul>
<div class="tab-content" id="tabs">
	<div role="tabpanel" class="tab-pane active" id="game_list_tab">
		<table id="game_list" class="table table-striped table-hover table-border">
			<thead>
				<tr>
					<th>Game</th>
					<th>Time</th>
					<th>W/L</th>
					<th>Team</th>
					<th>Position</th>
					<th rowspan="2">Score</th>
					<th rowspan="2">Accuracy</th>
					<th rowspan="2">MVP Points</th>
					<th rowspan="2">Lives Left</th>
					<th rowspan="2">Shots Left</th>
					<th rowspan="2">Shot Opponent</th>
					<th rowspan="2">Got Shot</th>
					<th rowspan="2">Hit Diff</th>
					<th rowspan="2">Missiled</th>
					<th rowspan="2">Got Missiled</th>
					<th rowspan="2">Medic Hits</th>
					<th rowspan="2">Medic Nukes</th>
					<th rowspan="2">Shot 3-Hits</th>
					<th rowspan="2">Shot Team</th>
					<th rowspan="2">Missiled Team</th>
					<th rowspan="2">Shot Own Medic</th>
					<th rowspan="2">Nukes Activated</th>
					<th rowspan="2">Nukes Detonated</th>
					<th rowspan="2">Nuke Cancels</th>
					<th rowspan="2">Own Nuke Cancels</th>
					<th rowspan="2">Rapid Fires</th>
					<th rowspan="2">Boosts</th>
					<th rowspan="2">Resupplies</th>
					<th rowspan="2">PDF</th>
				</tr>
				<tr>
					<th class="searchable">Game</th>
					<th class="searchable">Time</th>
					<th class="searchable">W/L</th>
					<th class="searchable">Team</th>
					<th class="searchable">Position</th>
				</tr>
			</thead>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="overall_tab">
		<?php
			echo $this->Form->create('gamesLimit', array('class' => 'form-inline'));
			echo $this->Form->input('selectNumeric', array(
				'label' => 'Select # of games',
				'options' => array(
					0 => 'All Games',
					10 => 'Last 10',
					25 => 'Last 25',
					50 => 'Last 50',
					100 => 'Last 100'
				),
				'selected' => 0,
				'class' => 'form-control',
				'div' => array('class' => 'form-group')
			));
			echo $this->Form->input('selectDate', array(
				'label' => 'Select # of days',
				'options' => array(
					0 => 'All Dates',
					30 => 'Last 30',
					60 => 'Last 60',
					90 => 'Last 90',
					120 => 'Last 120'
				),
				'selected' => 0,
				'class' => 'form-control',
				'div' => array('class' => 'form-group')
			));
			echo $this->Form->input('selectTeam', array(
				'label' => 'Select team',
				'options' => array(
					0 => 'All Teams',
					'red' => 'Red',
					'green' => 'Green'
				),
				'selected' => 0,
				'class' => 'form-control',
				'div' => array('class' => 'form-group')
			));
			echo $this->Form->end();
		?>
		<br />
		<div id="win_loss_pie" style="height: 500px; width: 800px"></div>
		<div id="position_spider" style="height: 500px; width: 800px"></div>
		<br />
		<br />
		<br />
		<p>The following graphs represent a simple rolling average for accuracy, score and MVP points both overall and by position.<br />
		Individual lines can be turned on and off by clicking on the title in the legend.<br />
		Clicking the legend items marked scatter will show all the points on the graph for the data set, if you like that sort of thing.<br />
		These graphs are not affected by the filters above.</p>
		<div id="acc_plot" style="display: inline-block;height:400px;width:800px; "></div>
		<br />
		<div id="mvp_plot" style="display: inline-block;height:400px;width:800px; "></div>
		<br />
		<div id="score_plot" style="display: inline-block;height:400px;width:800px; "></div>
	</div>
	<div role="tabpanel" class="tab-pane" id="teammates_tab">
		<?php
			foreach($teammates as $key => $row) {
				$same_team[$key] = $row['same_team_percent'];
			}
			array_multisort($same_team, SORT_DESC, $teammates);
		?>
		<div class="row">
			<div class="col-sm-6">
				<table class="table table-striped table-border table-hover">
					<thead>
						<th>Player</th>
						<th>Teammate %</th>
						<th>Teammate Games (Total Games)</th>
					</thead>
					<tbody>
						<?php foreach (array_slice($teammates,0,10) as $tm): ?>
						<tr>
							<td><?php echo $tm['player_name']; ?></td>
							<td><?php echo round($tm['same_team_percent'] * 100,2); ?>%</td>
							<td><?php echo $tm['same_team_count']." (".($tm['same_team_count'] + $tm['other_team_count']).")"; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php
				foreach($teammates as $key => $row) {
					$same_team[$key] = 1 - $row['same_team_percent'];
				}
				array_multisort($same_team, SORT_DESC, $teammates);
			?>
			<div class="col-sm-6">
				<table class="table table-striped table-border table-hover">
					<thead>
						<th>Player</th>
						<th>Opponent %</th>
						<th>Opponent Games (Total Games)</th>
					</thead>
					<tbody>
						<?php foreach (array_slice($teammates,0,10) as $tm): ?>
						<tr>
							<td><?php echo $tm['player_name']; ?></td>
							<td><?php echo round((1-$tm['same_team_percent']) * 100,2); ?>%</td>
							<td><?php echo $tm['other_team_count']." (".($tm['same_team_count'] + $tm['other_team_count']).")"; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script>
$('#gamesLimitSelectNumeric').change(function() {
	var numeric = $(this).val();
	$('#gamesLimitSelectDate').val(0);
	var team = $('#gamesLimitSelectTeam').val();
	
	$.ajax({
		type: 'post',
		url: '<?php echo $this->Html->url(array('action' => 'playerWinLossDetail', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		data: {'numeric' : numeric, 'date' : 0},
		success: function(data) {
			displayWinLossPie(data);
		},
		error: function() {
			alert('fail');
		}
	});
	
	$.ajax({
		type: 'post',
		url: '<?php echo $this->Html->url(array('action' => 'playerPositionSpider', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		data: {'numeric' : numeric, 'team' : team, 'date' : 0},
		success: function(data) {
			displayPositionSpider(data);
		},
		error: function() {
			alert('fail');
		}
	});
});

$('#gamesLimitSelectDate').change(function() {
	var date = $(this).val();
	$('#gamesLimitSelectNumeric').val(0);
	var team = $('#gamesLimitSelectTeam').val();
	
	$.ajax({
		type: 'post',
		url: '<?php echo $this->Html->url(array('action' => 'playerWinLossDetail', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		data: {'numeric' : 0, 'date' : date},
		success: function(data) {
			displayWinLossPie(data);
		},
		error: function() {
			alert('fail');
		}
	});
	
	$.ajax({
		type: 'post',
		url: '<?php echo $this->Html->url(array('action' => 'playerPositionSpider', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		data: {'numeric' : 0, 'team' : team, 'date' : date},
		success: function(data) {
			displayPositionSpider(data);
		},
		error: function() {
			alert('fail');
		}
	});
});

$('#gamesLimitSelectTeam').change(function() {
	var team = $(this).val();
	var date = $('#gamesLimitSelectDate').val();
	var numeric = $('#gamesLimitSelectNumeric').val();
	
	$.ajax({
		type: 'post',
		url: '<?php echo $this->Html->url(array('action' => 'playerPositionSpider', $id, 'ext' => 'json')); ?>',
		dataType: 'json',
		data: {'numeric' : numeric, 'team' : team, 'date' : date},
		success: function(data) {
			displayPositionSpider(data);
		},
		error: function() {
			alert('fail');
		}
	});
});
</script>