<?php
	echo $this->Html->script('highcharts.js');
	echo $this->Html->script('highcharts-more.js');
	echo $this->Html->script('regression.js');
	
	$overall_plot = array();
	foreach($overall[0]['Scorecard'] as $key => $val) {
		$overall_plot[] = (float)$val['mvp_points'];
	}
	$overall_json = json_encode($overall_plot);
	
	$commander_plot = array();
	foreach($commander[0]['Scorecard'] as $key => $val) {
		$commander_plot[] = (float)$val['mvp_points'];
	}
	$commander_json = json_encode($commander_plot);
	
	$heavy_plot = array();
	foreach($heavy[0]['Scorecard'] as $key => $val) {
		$heavy_plot[] = (float)$val['mvp_points'];
	}
	$heavy_json = json_encode($heavy_plot);
	
	$scout_plot = array();
	foreach($scout[0]['Scorecard'] as $key => $val) {
		$scout_plot[] = (float)$val['mvp_points'];
	}
	$scout_json = json_encode($scout_plot);
	
	$ammo_plot = array();
	foreach($ammo[0]['Scorecard'] as $key => $val) {
		$ammo_plot[] = (float)$val['mvp_points'];
	}
	$ammo_json = json_encode($ammo_plot);
	
	$medic_plot = array();
	foreach($medic[0]['Scorecard'] as $key => $val) {
		$medic_plot[] = (float)$val['mvp_points'];
	}
	$medic_json = json_encode($medic_plot);
	
	
	
	$overall_score_plot = array();
	foreach($overall[0]['Scorecard'] as $key => $val) {
		$overall_score_plot[] = (float)$val['score'];
	}
	$overall_score_json = json_encode($overall_score_plot);
	
	$commander_score_plot = array();
	foreach($commander[0]['Scorecard'] as $key => $val) {
		$commander_score_plot[] = (float)$val['score'];
	}
	$commander_score_json = json_encode($commander_score_plot);
	
	$heavy_score_plot = array();
	foreach($heavy[0]['Scorecard'] as $key => $val) {
		$heavy_score_plot[] = (float)$val['score'];
	}
	$heavy_score_json = json_encode($heavy_score_plot);
	
	$scout_score_plot = array();
	foreach($scout[0]['Scorecard'] as $key => $val) {
		$scout_score_plot[] = (float)$val['score'];
	}
	$scout_score_json = json_encode($scout_score_plot);
	
	$ammo_score_plot = array();
	foreach($ammo[0]['Scorecard'] as $key => $val) {
		$ammo_score_plot[] = (float)$val['score'];
	}
	$ammo_score_json = json_encode($ammo_score_plot);
	
	$medic_score_plot = array();
	foreach($medic[0]['Scorecard'] as $key => $val) {
		$medic_score_plot[] = (float)$val['score'];
	}
	$medic_score_json = json_encode($medic_score_plot);
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
	
	var line1 = <?php echo $overall_json; ?>;
	var line2 = <?php echo $commander_json; ?>;
	var line3 = <?php echo $heavy_json; ?>;
	var line4 = <?php echo $scout_json; ?>;
	var line5 = <?php echo $ammo_json; ?>;
	var line6 = <?php echo $medic_json; ?>;
	
	var line7 = <?php echo $overall_score_json; ?>;
	var line8 = <?php echo $commander_score_json; ?>;
	var line9 = <?php echo $heavy_score_json; ?>;
	var line10 = <?php echo $scout_score_json; ?>;
	var line11 = <?php echo $ammo_score_json; ?>;
	var line12 = <?php echo $medic_score_json; ?>;
	
	(line1.length < 1) ? line1 = [null] : "";
	(line2.length < 1) ? line2 = [null] : "";
	(line3.length < 1) ? line3 = [null] : "";
	(line4.length < 1) ? line4 = [null] : "";
	(line5.length < 1) ? line5 = [null] : "";
	(line6.length < 1) ? line6 = [null] : "";
	(line7.length < 1) ? line7 = [null] : "";
	(line8.length < 1) ? line8 = [null] : "";
	(line9.length < 1) ? line9 = [null] : "";
	(line10.length < 1) ? line10 = [null] : "";
	(line11.length < 1) ? line11 = [null] : "";
	(line12.length < 1) ? line12 = [null] : "";
	
	$('#overall_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Overall'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'MVP Points'},
			max: 40,
			tickInterval: 5
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Overall',
			tooltip: {pointFormat: 'MVP Points: {point.y}'},
			data: line1
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line1).data;
			})()
		}
		]
	});
	
	$('#commander_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Commander'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'MVP Points'},
			max: 40,
			tickInterval: 5
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Commander',
			tooltip: {pointFormat: 'MVP Points: {point.y}'},
			data: line2
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line2).data;
			})()
		}
		]
	});
	
	$('#heavy_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Heavy Weapons'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'MVP Points'},
			max: 40,
			tickInterval: 5
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Heavy Weapons',
			tooltip: {pointFormat: 'MVP Points: {point.y}'},
			data: line3
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line3).data;
			})()
		}
		]
	});
	
	$('#scout_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Scout'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'MVP Points'},
			max: 40,
			tickInterval: 5
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Scout',
			tooltip: {pointFormat: 'MVP Points: {point.y}'},
			data: line4
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line4).data;
			})()
		}
		]
	});
	
	$('#ammo_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Ammo Carrier'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'MVP Points'},
			max: 40,
			tickInterval: 5
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Ammo Carrier',
			tooltip: {pointFormat: 'MVP Points: {point.y}'},
			data: line5
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line5).data;
			})()
		}
		]
	});
	
	$('#medic_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Medic'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'MVP Points'},
			max: 40,
			tickInterval: 5
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Medic',
			tooltip: {pointFormat: 'MVP Points: {point.y}'},
			data: line6
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line6).data;
			})()
		}
		]
	});
	
	$('#overall_score_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Overall'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Overall',
			tooltip: {pointFormat: 'Score: {point.y}'},
			data: line7
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line7).data;
			})()
		}
		]
	});
	
	$('#commander_score_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Commander'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Commander',
			tooltip: {pointFormat: 'Score: {point.y}'},
			data: line8
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line8).data;
			})()
		}
		]
	});
	
	$('#heavy_score_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Heavy Weapons'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Heavy Weapons',
			tooltip: {pointFormat: 'Score: {point.y}'},
			data: line9
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line9).data;
			})()
		}
		]
	});
	
	$('#scout_score_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Scout'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Scout',
			tooltip: {pointFormat: 'Score: {point.y}'},
			data: line10
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line10).data;
			})()
		}
		]
	});
	
	$('#ammo_score_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Ammo Carrier'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Ammo Carrier',
			tooltip: {pointFormat: 'Score: {point.y}'},
			data: line11
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line11).data;
			})()
		}
		]
	});
	
	$('#medic_score_plot').highcharts({
		chart: {type: 'scatter'},
		title: {text: 'Medic'},
		legend: {enabled: false},
		yAxis: {
			title: {text: 'Score'},
			max: 15000,
			tickInterval: 1000
		},
		xAxis: {
			title: {text: 'Number of Games'},
			maxPadding: 0.1,
			minPadding: 0.1
		},
		series: [{
			type: 'scatter',
			marker: {radius: 2},
			name: 'Medic',
			tooltip: {pointFormat: 'Score: {point.y}'},
			data: line12
		},
		{
			type: 'line',
			marker: {enabled: false},
			enableMouseTracking: false,
			data: (function() {
				return fitData(line12).data;
			})()
		}
		]
	});
	
	var gTable = $('.gamelist').dataTable( {
		"bAutoWidth": false,
		"bFilter": true,
		"bInfo": false,
		"bPaginate": false,
		"bJQueryUI": true,
		"bRetrieve": true
	} );
	
	$("#tabs").tabs();
});
</script>
<h2><?php echo $overall[0]['Player']['player_name']; ?></h2>
<div id="tabs">
	<ul>
		<li><a href="#overall_tab">Overall</a></li>
		<li><a href="#teammates_tab">Teammates</a></li>
		<li><a href="#stat_plots_tab">Stat Plots MVP</a></li>
		<li><a href="#stat_plots_score_tab">Stat Plots Score</a></li>
		<li><a href="#game_list_tab">Game List</a></li>
	</ul>
	<div id="overall_tab">
		<?php
			echo $this->Form->create('gamesLimit');
			echo $this->Form->input('selectNumeric', array(
				'label' => 'Select # of games',
				'options' => array(
					0 => 'All Games',
					10 => 'Last 10',
					25 => 'Last 25',
					50 => 'Last 50',
					100 => 'Last 100'
				),
				'selected' => 0
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
				'selected' => 0
			));
			echo $this->Form->input('selectTeam', array(
				'label' => 'Select team',
				'options' => array(
					0 => 'All Teams',
					'red' => 'Red',
					'green' => 'Green'
				),
				'selected' => 0
			));
			echo $this->Form->end();
		?>
		<div id="win_loss_pie" style="height: 500px; width: 800px"></div>
		<div id="position_spider" style="height: 500px; width: 800px"></div>
	</div>
	<div id="teammates_tab">
		<?php
			foreach($teammates as $key => $row) {
				$same_team[$key] = $row['same_team_percent'];
			}
			array_multisort($same_team, SORT_DESC, $teammates);
		?>
		<div>
			<span id="teammates" style="display: inline-block;width:500px">
				<table>
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
			</span>
			<?php
				foreach($teammates as $key => $row) {
					$same_team[$key] = 1 - $row['same_team_percent'];
				}
				array_multisort($same_team, SORT_DESC, $teammates);
			?>
			<span id="teammates" style="display: inline-block;width:500px">
				<table>
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
			</span>
		</div>
	</div>
	<div id="stat_plots_tab">
		<span id="overall_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="overall_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_overall as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="commander_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="commander_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_commander as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="heavy_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="heavy_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_heavy as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="scout_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="scout_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_scout as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="ammo_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="ammo_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_ammo as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="medic_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="medic_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_medic as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
	</div>
	<div id="stat_plots_score_tab">
		<span id="overall_score_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="overall_score_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_overall as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<span id="commander_score_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="commander_score_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_commander as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="heavy_score_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="heavy_score_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_heavy as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="scout_score_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="scout_score_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_scout as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="ammo_score_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="ammo_score_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_ammo as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
		<br />
		<span id="medic_score_plot" style="display: inline-block;height:250px;width:500px; "></span>
		<span id="medic_score_topgames" style="display: inline-block;width:500px">
			<table>
				<thead>
					<th>Game</th>
					<th>Time</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
				</thead>
				<tbody>
					<?php foreach ($games_top5_medic as $game): ?>
						<tr>
							<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
							<td><?php echo $game['Game']['game_datetime']; ?></td>
							<td><?php echo $game['Scorecard']['position']; ?></td>
							<td><?php echo $game['Scorecard']['score']; ?></td>
							<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</span>
	</div>
	<div id="game_list_tab">
		<table class="gamelist">
			<thead>
				<th>Game</th>
				<th>Time</th>
				<th>Red Score</th>
				<th>Green Score</th>
				<th>Team</th>
				<th>Position</th>
				<th>Score</th>
				<th>MVP</th>
				<th>Scorecard PDF</th>
			</thead>
			<tbody>
				<?php foreach ($games as $game): ?>
					<?php
						if($game['Game']['red_score'] > $game['Game']['green_score'])
							$color = 'gameRowRed';
						else
							$color = 'gameRowGreen';
					?>
					<tr class="<?php echo $color; ?>">
						<td><?php echo $this->Html->link($game['Game']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); ?></td>
						<td><?php echo $game['Game']['game_datetime']; ?></td>
						<td><?php echo $game['Game']['red_score']; ?></td>
						<td><?php echo $game['Game']['green_score']; ?></td>
						<td><?php echo $game['Scorecard']['team']; ?></td>
						<td><?php echo $game['Scorecard']['position']; ?></td>
						<td><?php echo $game['Scorecard']['score']; ?></td>
						<td><?php echo $game['Scorecard']['mvp_points']; ?></td>
						<td><?php echo (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) ? $this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf") : ""; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
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