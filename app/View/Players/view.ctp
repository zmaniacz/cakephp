<?php
	echo $this->Html->script('highcharts.js');
	echo $this->Html->script('highcharts-more.js');
	echo $this->Html->script('https://rawgithub.com/laff/technical-indicators/master/technical-indicators.src.js');
?>
<script class="code" type="text/javascript">
function displayWinLossPie(data) {
	var wins = 0;
	var losses = 0;
	var elim_wins_from_red = 0;
	var non_elim_wins_from_red = 0;
	var elim_wins_from_green = 0;
	var non_elim_wins_from_green = 0;
	var elim_losses_from_red = 0;
	var non_elim_losses_from_red = 0;
	var elim_losses_from_green = 0;
	var non_elim_losses_from_green = 0;

	data.scorecards.forEach(function(scorecard) {
		if(scorecard.Team.color == 'red') {
			if(scorecard.Team.winner) {
				wins++;
				if(scorecard.Team.eliminated_opponent) {
					elim_wins_from_red++;
				} else {
					non_elim_wins_from_red++;
				}
			} else {
				losses++;
				if(scorecard.Team.eliminated) {
					elim_losses_from_red++;
				} else {
					non_elim_losses_from_red++;
				}
			}
		} else {
			if(scorecard.Team.winner) {
				wins++;
				if(scorecard.Team.eliminated_opponent) {
					elim_wins_from_green++;
				} else {
					non_elim_wins_from_green++;
				}
			} else {
				losses++;
				if(scorecard.Team.eliminated) {
					elim_losses_from_green++;
				} else {
					non_elim_losses_from_green++;
				}
			}
		}
	});

	var winloss = [
		['Wins', wins],
		['Losses', losses]
	];
	var winlossdetail = [
		['Elim Wins from Red', elim_wins_from_red],
		['Non-Elim Wins from Red', non_elim_wins_from_red],
		['Elim Wins from Green', elim_wins_from_green],
		['Non-Elim Wins from Green', non_elim_wins_from_green],
		['Elim Losses from Red', elim_losses_from_red],
		['Non-Elim Losses from Red', non_elim_losses_from_red],
		['Elim Losses from Green', elim_losses_from_green],
		['Non-Elim Losses from Green', non_elim_losses_from_green]
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
			size: '60%',
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
			size: '80%',
			innerSize: '60%',
			dataLabels: {
				formatter: function() {
					return '<b>'+ this.point.name +':</b> '+ this.y;
				}
			}
		}]
	});
}

function displayPositionScoreSpider(data) {
	var mdn_score = [data['player_mdn_scores']['ammo'],data['player_mdn_scores']['commander'],data['player_mdn_scores']['heavy'],data['player_mdn_scores']['medic'],data['player_mdn_scores']['scout']];
	var ctr_mdn_score = [data['center_mdn_scores']['ammo'],data['center_mdn_scores']['commander'],data['center_mdn_scores']['heavy'],data['center_mdn_scores']['medic'],data['center_mdn_scores']['scout']];
	
	$('#position_score_spider').highcharts({
		chart: {
			polar: true,
			type: 'line'
		},
		title: {
			text: 'Position Score vs Median'
		},
		xAxis: {
			categories: ['Ammo Carrier','Commander','Heavy Weapons','Medic','Scout'],
			tickmarkPlacement: 'on',
			lineWidth: 0
		},
		yAxis: [{
			min: 0,
			max: 10000,
			labels: {
				enabled: false
			}
		}],
		series: [ {
			name: 'Median Score',
			data: mdn_score,
			marker: {
                symbol: 'square'
            },
			pointPlacement: 'on',
			yAxis: 0
		},{
			name: 'Center Median Score',
			data: ctr_mdn_score,
			marker: {
                symbol: 'square'
            },
			pointPlacement: 'on',
			dashStyle: 'dash',
			yAxis: 0
		}]
	});
}

function displayPositionMVPSpider(data) {
	var mdn_mvp = [data['player_mdn_mvp']['ammo'],data['player_mdn_mvp']['commander'],data['player_mdn_mvp']['heavy'],data['player_mdn_mvp']['medic'],data['player_mdn_mvp']['scout']];
	var ctr_mdn_mvp = [data['center_mdn_mvp']['ammo'],data['center_mdn_mvp']['commander'],data['center_mdn_mvp']['heavy'],data['center_mdn_mvp']['medic'],data['center_mdn_mvp']['scout']];
	
	$('#position_mvp_spider').highcharts({
		chart: {
			polar: true,
			type: 'line'
		},
		title: {
			text: 'Position MVP vs Median'
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
		}],
		series: [
		{
			name: 'Median MVP',
			data: mdn_mvp,
			marker: {
                symbol: 'circle'
            },
			pointPlacement: 'on',
			yAxis: 0
		},
		{
			name: 'Center Median MVP',
			data: ctr_mdn_mvp,
			marker: {
                symbol: 'circle'
            },
			pointPlacement: 'on',
			dashStyle: 'dash',
			yAxis: 0
		}]
	});
}

function displayPositionBoxPlot(data) {
	var mvp = data['player_mdn_mvp'];

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

function displayGameList(data) {
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
		"deferRender" : true,
		"orderCellsTop" : true,
		"dom": '<"H"lr>t<"F"ip>',
		"data" : data.scorecards,
		"columns" : [
			{ 
				"data" : function ( row, type, val, meta) {
					if (type === 'display') {
						return '<a href="/games/view/'+row.Game.id+location.search+'" class="btn btn-info btn-block">'+row.Game.game_name+'</a>';
					}
					return row.Game.game_name;
				}
			},
			{ "data" : "Game.game_datetime" },
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Team.winner) ? "W" : "L";
					}
					return row.Team.winner;
				}
			},
			{ "data" : "Scorecard.color" },
			{ "data" : "Scorecard.position" },
			{ "data" : "Scorecard.score" },
			{ 
				"data" : function ( row, type, val, meta) {
					if (type === 'display') {
						return parseFloat(row.Scorecard.accuracy*100).toFixed(2)+"%";
					}
					return row.Scorecard.accuracy;
				}
			},
			{ 
				"data" : function ( row, type, val, meta) {
					if (type === 'display') {
						return '<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#mvpModal" target="/scorecards/getMVPBreakdown/'+row.Scorecard.id+'.json">'+row.Scorecard.mvp_points+'</button>';
					}
					return row.Scorecard.mvp_points;
				}
			},
			{ 
				"data" : function ( row, type, val, meta) {
					var hit_diff = (row.Scorecard.times_zapped) ? parseFloat(row.Scorecard.shot_opponent/row.Scorecard.times_zapped) : row.Scorecard.shot_opponent; 
					if (type === 'display') {
						return '<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#hitModal" target="/scorecards/getHitBreakdown/'+row.Scorecard.player_id+'/'+row.Game.id+'.json">'+parseFloat(hit_diff).toFixed(2)+' ('+row.Scorecard.shot_opponent+'/'+row.Scorecard.times_zapped+')</button>';
					}
					return hit_diff;
				}
			},
			{ "data" : "Scorecard.lives_left" },
			{ "data" : "Scorecard.shots_left" },
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Commander' || row.Scorecard.position == 'Heavy Weapons') ? row.Scorecard.missile_hits : '-';
					}
					return row.Scorecard.missile_hits;
				}
			},
			{ "data" : "Scorecard.times_missiled" },
			{ "data" : "Scorecard.medic_hits" },
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Commander') ? row.Scorecard.medic_nukes : '-';
					}
					return row.Scorecard.medic_nukes;
				}
			},
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Scout') ? row.Scorecard.shot_3hit : '-';
					}
					return row.Scorecard.shot_3hit;
				}
			},
			{ "data" : "Scorecard.shot_team" },
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Commander' || row.Scorecard.position == 'Heavy Weapons') ? row.Scorecard.missiled_team : '-';
					}
					return row.Scorecard.missiled_team;
				}
			},
			{ "data" : "Scorecard.own_medic_hits" },
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Commander') ? row.Scorecard.nukes_activated+'/'+row.Scorecard.nukes_detonated : '-';
					}
					return row.Scorecard.nukes_activated;
				}
			},
			{ "data" : "Scorecard.nukes_canceled" },
			{ "data" : "Scorecard.own_nuke_cancels" },
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Scout') ? row.Scorecard.scout_rapid : '-';
					}
					return row.Scorecard.scout_rapid;
				}
			},
			{ 
				"data" : function ( row, type, val, meta ) {
					var boost = 0;
					if(row.Scorecard.position == 'Ammo Carrier') {
						boost = row.Scorecard.ammo_boost;
					} else if(row.Scorecard.position == 'Medic') {
						boost = row.Scorecard.life_boost;
					}

					if (type === 'display') {
						return (boost) ? boost : '-';
					}

					return boost;
				}
			},
			{ 
				"data" : function ( row, type, val, meta ) {
					if (type === 'display') {
						return (row.Scorecard.position == 'Ammo Carrier' || row.Scorecard.position == 'Medic') ? row.Scorecard.resupplies : '-';
					}
					return row.Scorecard.resupplies;
				}
			},
			{ 
				"data" : function ( row, type, val, meta) {
					if (type === 'display') {
						if(row.Game.pdf_id !== null) {
							return '<a href="http://scorecards.lfstats.com/'+row.Game.pdf_id+'.pdf" class="btn btn-info btn-block" target="_blank">PDF</a>';
						}
					}
					return row.Game.pdf_id;
				}
			},
		],
		"order": [[ 1, "desc" ]]
	});
}

function displayPlots(data) {
	var overall_mvp_data = [];
	var commander_mvp_data = [];
	var heavy_mvp_data = [];
	var scout_mvp_data = [];
	var ammo_mvp_data = [];
	var medic_mvp_data = [];
	
	var overall_score_data = [];
	var commander_score_data = [];
	var heavy_score_data = [];
	var scout_score_data = [];
	var ammo_score_data = [];
	var medic_score_data = [];

	var overall_acc_data = [];
	var commander_acc_data = [];
	var heavy_acc_data = [];
	var scout_acc_data = [];
	var ammo_acc_data = [];
	var medic_acc_data = [];

	data.scorecards.forEach(function(scorecard) {
		overall_mvp_data.push(scorecard.Scorecard.mvp_points);
		overall_score_data.push(scorecard.Scorecard.score);
		overall_acc_data.push(parseFloat(scorecard.Scorecard.accuracy*100));

		if(scorecard.Scorecard.position == 'Commander') {
			commander_mvp_data.push(scorecard.Scorecard.mvp_points);
			commander_score_data.push(scorecard.Scorecard.score);
			commander_acc_data.push(parseFloat(scorecard.Scorecard.accuracy*100));
		}

		if(scorecard.Scorecard.position == 'Heavy Weapons') {
			heavy_mvp_data.push(scorecard.Scorecard.mvp_points);
			heavy_score_data.push(scorecard.Scorecard.score);
			heavy_acc_data.push(parseFloat(scorecard.Scorecard.accuracy*100));
		}

		if(scorecard.Scorecard.position == 'Scout') {
			scout_mvp_data.push(scorecard.Scorecard.mvp_points);
			scout_score_data.push(scorecard.Scorecard.score);
			scout_acc_data.push(parseFloat(scorecard.Scorecard.accuracy*100));
		}

		if(scorecard.Scorecard.position == 'Ammo Carrier') {
			ammo_mvp_data.push(scorecard.Scorecard.mvp_points);
			ammo_score_data.push(scorecard.Scorecard.score);
			ammo_acc_data.push(parseFloat(scorecard.Scorecard.accuracy*100));
		}

		if(scorecard.Scorecard.position == 'Medic') {
			medic_mvp_data.push(scorecard.Scorecard.mvp_points);
			medic_score_data.push(scorecard.Scorecard.score);
			medic_acc_data.push(parseFloat(scorecard.Scorecard.accuracy*100));
		}
	});
	
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
			visible: true,
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
			visible: true,
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
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(overall_mvp_data.length/10),10),
			xAxis: 0
		},
		{
			name: 'Commander',
			type: 'trendline',
			linkedTo: 'commander',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(commander_mvp_data.length/10),10),
			xAxis: 1
		},
		{
			name: 'Heavy Weapons',
			type: 'trendline',
			linkedTo: 'heavy',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(heavy_mvp_data.length/10),10),
			xAxis: 2
		},
		{
			name: 'Scout',
			type: 'trendline',
			linkedTo: 'scout',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(scout_mvp_data.length/10),10),
			xAxis: 3
		},
		{
			name: 'Ammo Carrier',
			type: 'trendline',
			linkedTo: 'ammo',
			algorithm: 'EMA',
			showInLegend: true,
			periods: Math.max(Math.round(ammo_mvp_data.length/10),10),
			xAxis: 4
		},
		{
			name: 'Medic',
			type: 'trendline',
			linkedTo: 'medic',
			algorithm: 'EMA',
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
			visible: true,
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
}

$(document).ready(function(){
	$.ajax({
		type: 'get',
		url: '<?php echo html_entity_decode($this->Html->url(array('action' => 'playerScorecards', $player['Player']['id'], 'ext' => 'json'))); ?>',
		dataType: 'json',
		success: function(data) {
			displayGameList(data);
			displayPlots(data);
			displayWinLossPie(data);
		},
		error: function() {
			toastr.error('Failed to retrieve scorecards');
		}
	});
	
	$.ajax({
		type: 'get',
		url: '<?php echo html_entity_decode($this->Html->url(array('action' => 'playerPositionSpider', $player['Player']['id'], 'ext' => 'json'))); ?>',
		dataType: 'json',
		success: function(data) {
			displayPositionScoreSpider(data);
			displayPositionMVPSpider(data);
			displayPositionBoxPlot(data);
		},
		error: function() {
			toastr.error('Failed to retrieve position details');
		}
	});
	
	var headToHeadTable = $('#head_to_head').DataTable( {
		"deferRender" : true,
		"ajax" : {
			"url" : "<?php echo html_entity_decode($this->Html->url(array('controller' => 'Scorecards', 'action' => 'getPlayerHitBreakdown', $player['Player']['id'], 'ext' => 'json'))); ?>"
		},
		"columns" : [
			{ "data" : "name" },
			{ "data" : "hits" },
			{ "data" : "hit_by" },
			{ "data" : "hit_ratio" },
			{ "data" : "missiles" },
			{ "data" : "missile_by" },
			{ "data" : "missile_ratio" }
		],
		"order": [[ 1, "desc" ]]
	});
});
</script>
<h1 class="text-info"><?= $player['Player']['player_name']; ?></h1>
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
	<?php if (AuthComponent::user('role') === 'admin'): ?>
		<a href="<?= $this->Html->url(array('controller' => 'players', 'action' => 'link', $overall[0]['Player']['id'])); ?>" class="btn btn-success" role="button">Link</a>
	<?php endif; ?>
</div>
<ul class="nav nav-tabs" role="tablist" id="myTab">
	<li role="presentation" class="active"><a href="#game_list_tab" role="tab" data-toggle="tab">Game List</a></li>
	<li role="presentation"><a href="#overall_tab" role="tab" data-toggle="tab">Overall</a></li>
	<li role="presentation"><a href="#head_to_head_tab" role="tab" data-toggle="tab">Head To Head</a></li>
</ul>
<div class="tab-content" id="tabs">
	<div role="tabpanel" class="tab-pane active" id="game_list_tab">
		<div class="table-responsive">
			<table id="game_list" class="table table-striped table-hover table-border table-condensed">
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
						<th rowspan="2">Hit Diff</th>
						<th rowspan="2">Lives Left</th>
						<th rowspan="2">Shots Left</th>
						<th rowspan="2">Missiled</th>
						<th rowspan="2">Got Missiled</th>
						<th rowspan="2">Medic Hits</th>
						<th rowspan="2">Medic Nukes</th>
						<th rowspan="2">Shot 3-Hits</th>
						<th rowspan="2">Shot Team</th>
						<th rowspan="2">Missiled Team</th>
						<th rowspan="2">Shot Own Medic</th>
						<th rowspan="2">Nukes Activated/Detonated</th>
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
	</div>
	<div role="tabpanel" class="tab-pane" id="overall_tab">
		<div id="win_loss_pie" style="height: 500px; width: 800px"></div>
		<div id="position_score_spider" style="display: inline-block;height: 500px; width: 500px"></div>
		<div id="position_mvp_spider" style="display: inline-block;height: 500px; width: 500px"></div>
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
	<div role="tabpanel" class="tab-pane" id="head_to_head_tab">
		<div class="table-responsive">
			<table id="head_to_head" class="table table-striped table-hover table-border table-condensed">
				<thead>
					<tr>
						<th>Player</th>
						<th>Shot</th>
						<th>Shot By</th>
						<th>Shot Ratio</th>
						<th>Missiles</th>
						<th>Missiled By</th>
						<th>Missile Ratio</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>