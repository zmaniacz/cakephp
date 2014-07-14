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

	$('#avg_scores').DataTable( {
		"destroy": true,
		"autoWidth": false,
		"searching": false,
		"info": false,
		"paging": false,
		"ordering": false,
		"jQueryUI": true,
		"data" : data['scoredetail'],
		"columns" : [
			{ "data" : "Game"},
			{ "data" : "green_score" },
			{ "data" : "red_score"}
		],
	});
}

$(document).ready(function(){	
	$.ajax({
		type: 'get',
		url: '<?php echo $this->Html->url(array('action' => 'overallWinLossDetail', 'numeric', 'ext' => 'json')); ?>',
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
	echo $this->Form->end();
?>
<div id="win_loss_pie" style="height: 500px; width: 800px"></div>
<br />
<br />
<h2>Average Scores</h2>
<table id="avg_scores">
	<thead>
		<th>Win Type</th>
		<th>Green Score</th>
		<th>Red Score</th>
	<thead>
</table>
<script>
$('#gamesLimitSelectNumeric').change(function() {
	var selectedValue = $(this).val();
	
	$('#gamesLimitSelectDate').val(0);

	$.ajax({
		type: 'get',
		url: 'overallWinLossDetail/numeric/' + selectedValue + '.json',
		dataType: 'json',
		success: function(data) {
			overallData(data);
		},
		error: function() {
			alert('fail');
		}
	});
});

$('#gamesLimitSelectDate').change(function() {
	var selectedValue = $(this).val();
	
	$('#gamesLimitSelectNumeric').val(0);

	$.ajax({
		type: 'get',
		url: 'overallWinLossDetail/date/' + selectedValue + '.json',
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