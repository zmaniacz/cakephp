<?php
	echo $this->Html->script('jquery.jqplot.min.js');
	echo $this->Html->script('plugins/jqplot.trendline.min.js');
	echo $this->Html->script('highcharts.js');
	echo $this->Html->script('highcharts-more.js');
	echo $this->Html->css('jquery.jqplot.min');
	
	$wins = 0;
	$losses = 0;
	$red_wins = 0;
	$red_losses = 0;
	$red_wins_elim = 0;
	$green_wins_elim = 0;
	$red_losses_elim = 0;
	$green_losses_elim = 0;

	foreach($games as $game) {
		if($game['Scorecard']['team'] == 'Red') {
			if($game['Game']['winner'] == 'Red') {
				$wins++;
				$red_wins++;
				if($game['Game']['green_eliminated'] > 0) {
					$red_wins_elim++;
				}
			} else {
				$losses++;
				$red_losses++;
				if($game['Game']['red_eliminated'] > 0) {
					$red_losses_elim++;
				}
			}
		} else {
			if($game['Game']['winner'] == 'Green') {
				$wins++;
				if($game['Game']['red_eliminated'] > 0) {
					$green_wins_elim++;
				}
			} else {
				$losses++;
				if($game['Game']['green_eliminated'] > 0) {
					$green_losses_elim++;
				}
			}
		}
	}
	
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
?>

<script class="code" type="text/javascript">
$(document).ready(function(){
	$.jqplot.config.enablePlugins = true;
	
	var line1 = <?php echo $overall_json; ?>;
	var line2 = <?php echo $commander_json; ?>;
	var line3 = <?php echo $heavy_json; ?>;
	var line4 = <?php echo $scout_json; ?>;
	var line5 = <?php echo $ammo_json; ?>;
	var line6 = <?php echo $medic_json; ?>;
	var winloss = [['Wins', <?php echo $wins; ?>], ['Losses', <?php echo $losses; ?>]];
	var winlossdetail = [
		['Elim Wins from Red', <?php echo $red_wins_elim; ?>],
		['Non-Elim Wins from Red', <?php echo $red_wins - $red_wins_elim; ?>],
		['Elim Wins from Green', <?php echo $green_wins_elim; ?>],
		['Non-Elim Wins from Green', <?php echo $wins - $red_wins - $green_wins_elim; ?>],
		['Elim Losses from Red', <?php echo $red_losses_elim; ?>],
		['Non-Elim Losses from Red', <?php echo $red_losses - $red_losses_elim; ?>],
		['Elim Losses from Green', <?php echo $green_losses_elim; ?>],
		['Non-Elim Losses from Green', <?php echo $losses - $red_losses - $green_losses_elim; ?>]
	];
	var avg_mvp = [<?php echo $average_mvp['Ammo Carrier'].",".$average_mvp['Commander'].",".$average_mvp['Heavy Weapons'].",".$average_mvp['Medic'].",".$average_mvp['Scout']; ?>];
	var avg_score = [<?php echo $average_score['Ammo Carrier'].",".$average_score['Commander'].",".$average_score['Heavy Weapons'].",".$average_score['Medic'].",".$average_score['Scout']; ?>];
	var ctr_avg_mvp = [<?php echo $center_average_mvp['Ammo Carrier'].",".$center_average_mvp['Commander'].",".$center_average_mvp['Heavy Weapons'].",".$center_average_mvp['Medic'].",".$center_average_mvp['Scout']; ?>];
	var ctr_avg_score = [<?php echo $center_average_score['Ammo Carrier'].",".$center_average_score['Commander'].",".$center_average_score['Heavy Weapons'].",".$center_average_score['Medic'].",".$center_average_score['Scout']; ?>];
	
	(line1.length < 1) ? line1 = [null] : "";
	(line2.length < 1) ? line2 = [null] : "";
	(line3.length < 1) ? line3 = [null] : "";
	(line4.length < 1) ? line4 = [null] : "";
	(line5.length < 1) ? line5 = [null] : "";
	(line6.length < 1) ? line6 = [null] : "";
	
	var plot1 = $.jqplot('overall_plot', [line1],
		{
			axes: {yaxis: {min: 0,max: 40,numberTicks: 9}},
			series: {trendline: {show: true}},
			seriesDefaults: {showLine: false,markerOptions: {size: 3}}
		}
	);
	
	var plot2 = $.jqplot('commander_plot', [line2],
		{
			axes: {yaxis: {min: 0,max: 40,numberTicks: 9}},
			series: {trendline: {show: true}},
			seriesDefaults: {showLine: false,markerOptions: {size: 3}}
		}
	);
	
	var plot3 = $.jqplot('heavy_plot', [line3],
		{
			axes: {yaxis: {min: 0,max: 40,numberTicks: 9}},
			series: {trendline: {show: true}},
			seriesDefaults: {showLine: false,markerOptions: {size: 3}}
		}
	);
	
	var plot4 = $.jqplot('scout_plot', [line4],
		{
			axes: {yaxis: {min: 0,max: 40,numberTicks: 9}},
			series: {trendline: {show: true}},
			seriesDefaults: {showLine: false,markerOptions: {size: 3}}
		}
	);
	
	var plot5 = $.jqplot('ammo_plot', [line5],
		{
			axes: {yaxis: {min: 0,max: 40,numberTicks: 9}},
			series: {trendline: {show: true}},
			seriesDefaults: {showLine: false,markerOptions: {size: 3}}
		}
	);
	
	var plot6 = $.jqplot('medic_plot', [line6],
		{
			axes: {yaxis: {min: 0,max: 40,numberTicks: 9}},
			series: {trendline: {show: true}},
			seriesDefaults: {showLine: false,markerOptions: {size: 3}}
		}
	);
	
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
			size: '60%',
			innerSize: '40%',
			dataLabels: {
				formatter: function() {
					return this.y > 0 ? '<b>'+ this.point.name +':</b> '+ this.y  : null;
				}
			}
		}]
	});

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
			name: 'Average MVP',
			data: avg_mvp,
			marker: {
                symbol: 'circle'
            },
			pointPlacement: 'on',
			yAxis: 0
		}, {
			name: 'Average Score',
			data: avg_score,
			marker: {
                symbol: 'square'
            },
			pointPlacement: 'on',
			yAxis: 1
		}, {
			name: 'Center Average MVP',
			data: ctr_avg_mvp,
			marker: {
                symbol: 'circle'
            },
			pointPlacement: 'on',
			dashStyle: 'dash',
			yAxis: 0
		}, {
			name: 'Center Average Score',
			data: ctr_avg_score,
			marker: {
                symbol: 'square'
            },
			pointPlacement: 'on',
			dashStyle: 'dash',
			yAxis: 1
		}]
	});
	
	$("#tabs").tabs();
});
</script>
<h2><?php echo $overall[0]['Player']['player_name']; ?></h2>
<div id="tabs">
	<ul>
		<li><a href="#overall_tab">Overall</a></li>
		<li><a href="#stat_plots_tab">Stat Plots</a></li>
		<li><a href="#game_list_tab">Game List</a></li>
	</ul>
	<div id="overall_tab">
		<div id="win_loss_pie" style="height: 500px; width: 800px"></div>
		<div id="position_spider" style="height: 500px; width: 800px"></div>
	</div>
	<div id="stat_plots_tab">
		<h3>Overall</h3>
		<div id="overall_plot" style="height:200px;width:400px; "></div>
		<h3>Commander</h3>
		<div id="commander_plot" style="height:200px;width:400px; "></div>
		<h3>Heavy Weapons</h3>
		<div id="heavy_plot" style="height:200px;width:400px; "></div>
		<h3>Scout</h3>
		<div id="scout_plot" style="height:200px;width:400px; "></div>
		<h3>Ammo Carrier</h3>
		<div id="ammo_plot" style="height:200px;width:400px; "></div>
		<h3>Medic</h3>
		<div id="medic_plot" style="height:200px;width:400px; "></div>
	</div>
	<div id="game_list_tab">
		<table class="gamelist">
			<thead>
				<th>Game</th>
				<th>Time</th>
				<th>Red Score</th>
				<th>Green Score</th>
				<th>Scorecard PDF</th>
			</thead>
			<tbody>
				<?php foreach ($game_list as $game): ?>
					<?php
						if($game['games']['red_score'] > $game['games']['green_score'])
							$color = 'gameRowRed';
						else
							$color = 'gameRowGreen';
					?>
					<tr class="<?php echo $color; ?>">
						<td><?php echo $this->Html->link($game['games']['game_name'], array('controller' => 'Games', 'action' => 'view', $game['games']['id'])); ?></td>
						<td><?php echo $game['games']['game_datetime']; ?></td>
						<td><?php echo $game['games']['red_score']; ?></td>
						<td><?php echo $game['games']['green_score']; ?></td>
						<td><?php echo (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['games']['game_name']."_".date("Y-m-d_Hi",strtotime($game['games']['game_datetime'])).".pdf")) ? $this->Html->link("PDF", "/pdf/LTC_SM5".$game['games']['game_name']."_".date("Y-m-d_Hi",strtotime($game['games']['game_datetime'])).".pdf") : ""; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

