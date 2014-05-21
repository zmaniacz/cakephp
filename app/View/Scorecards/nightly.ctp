<h1>Stats for <?php echo $current_date;?></h1>
<?php
	echo $this->Form->create('nightly');
	echo $this->Form->input('selectDate', array('label' => 'Select Date', 'options' => $game_dates));
	echo $this->Form->end();
?>
<script type="text/javascript">
	$(document).ready(function() {	
		var oTable = $('.display').dataTable( {
			"bFilter": false,
			"bInfo": false,
			"bPaginate": false,
			"bJQueryUI": true,
			"bRetrieve": true
		} );
		var table;
		for (var i=0; i < oTable.length; i++) {
			table = $(oTable[i]).dataTable();
			table.fnSort( [ [2,'desc'] ] );
		}
		
		var gTable = $('#game_list').DataTable( {
			"autoWidth": false,
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"jQueryUI": true,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyGameList', $current_date, 'ext' => 'json')); ?>",
				"dataSrc" : "games"
			},
			"columns" : [
				{
					"data" : "Game.game_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/<?php echo $this->params->center; ?>/games/view/'+row.Game.id+'">'+data+'</a>';
					}
				},
				{ "data" : "Game.game_datetime" },
				{ "data" : "Game.red_score" },
				{ "data" : "Game.green_score" },
				{
					"data" : "Game.pdf_id",
					"render" : function(data, type, row, meta) {
						return '<a href="/pdf/'+data+'.pdf">PDF</a>';
					}
				}
			],
			"rowCallback" : function(row, data) {
				if(data.Game.winner == "Red")
					$(row).addClass('gameRowRed');
				else
					$(row).addClass('gameRowGreen');
			}
		});
	} );
</script>
<h3>Games Played</h3>
<div style="width: 500px;">
	<table id="game_list">
		<thead>
			<th>Game</th>
			<th>Time</th>
			<th>Red Score</th>
			<th>Green Score</th>
			<th>Scorecard PDF</th>
		</thead>
	</table>
</div>
<div id="accordion">
	<h3>Overall</h3>
	<div>
		<table class="display" id="overall">
			<thead>
				<tr>
					<th>Name</th>
					<th>Minimum Score</th>
					<th>Average Score</th>
					<th>Max Score</th>
					<th>Minimum Accuracy</th>
					<th>Average Accuracy</th>
					<th>Max Accuracy</th>
					<th>Games Played</th>	
				</tr>
			</thead>
			<?php foreach ($avg_score as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['min_score']; ?></td>
				<td><?php echo $score[0]['avg_score']; ?></td>
				<td><?php echo $score[0]['max_score']; ?></td>
				<td><?php echo round($score[0]['min_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['max_acc']*100,2); ?>%</td>
				<td><?php echo $score[0]['games_played']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
	<h3>Ammo Carrier</h3>
	<div>
		<table class="display" id="ammo">
			<thead>
				<tr>
					<th>Name</th>
					<th>Minimum Score</th>
					<th>Average Score</th>
					<th>Max Score</th>
					<th>Minimum Accuracy</th>
					<th>Average Accuracy</th>
					<th>Max Accuracy</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<?php foreach ($ammo_score as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['min_score']; ?></td>
				<td><?php echo $score[0]['avg_score']; ?></td>
				<td><?php echo $score[0]['max_score']; ?></td>
				<td><?php echo round($score[0]['min_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['max_acc']*100,2); ?>%</td>
				<td><?php echo $score[0]['games_played']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
	<h3>Commander</h3>
	<div>	
		<table class="display" id="commander">
			<thead>
				<tr>
					<th>Name</th>
					<th>Minimum Score</th>
					<th>Average Score</th>
					<th>Max Score</th>
					<th>Minimum Accuracy</th>
					<th>Average Accuracy</th>
					<th>Max Accuracy</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<?php foreach ($commander_score as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['min_score']; ?></td>
				<td><?php echo $score[0]['avg_score']; ?></td>
				<td><?php echo $score[0]['max_score']; ?></td>
				<td><?php echo round($score[0]['min_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['max_acc']*100,2); ?>%</td>
				<td><?php echo $score[0]['games_played']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
	<h3>Heavy Weapons</h3>
	<div>
		<table class="display" id="heavy">
			<thead>
				<tr>
					<th>Name</th>
					<th>Minimum Score</th>
					<th>Average Score</th>
					<th>Max Score</th>
					<th>Minimum Accuracy</th>
					<th>Average Accuracy</th>
					<th>Max Accuracy</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<?php foreach ($heavy_score as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['min_score']; ?></td>
				<td><?php echo $score[0]['avg_score']; ?></td>
				<td><?php echo $score[0]['max_score']; ?></td>
				<td><?php echo round($score[0]['min_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['max_acc']*100,2); ?>%</td>
				<td><?php echo $score[0]['games_played']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
	<h3>Medic</h3>
	<div>
		<table class="display" id="medic">
			<thead>
				<tr>
					<th>Name</th>
					<th>Minimum Score</th>
					<th>Average Score</th>
					<th>Max Score</th>
					<th>Minimum Accuracy</th>
					<th>Average Accuracy</th>
					<th>Max Accuracy</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<?php foreach ($medic_score as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['min_score']; ?></td>
				<td><?php echo $score[0]['avg_score']; ?></td>
				<td><?php echo $score[0]['max_score']; ?></td>
				<td><?php echo round($score[0]['min_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['max_acc']*100,2); ?>%</td>
				<td><?php echo $score[0]['games_played']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
	<h3>Scout</h3>
	<div>
		<table class="display" id="scout">
			<thead>
				<tr>
					<th>Name</th>
					<th>Minimum Score</th>
					<th>Average Score</th>
					<th>Max Score</th>
					<th>Minimum Accuracy</th>
					<th>Average Accuracy</th>
					<th>Max Accuracy</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<?php foreach ($scout_score as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['min_score']; ?></td>
				<td><?php echo $score[0]['avg_score']; ?></td>
				<td><?php echo $score[0]['max_score']; ?></td>
				<td><?php echo round($score[0]['min_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
				<td><?php echo round($score[0]['max_acc']*100,2); ?>%</td>
				<td><?php echo $score[0]['games_played']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
	<h3>Medic Hits</h3>
	<div>
		<table class="display" id="medic_hits">
			<thead>
				<tr>
					<th>Name</th>
					<th>Total</th>
					<th>Average</th>
				</tr>
			</thead>
			<?php foreach ($medic_hits as $score): ?>
			<tr>
				<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
				<td><?php echo $score[0]['medic_hits']; ?></td>
				<td><?php echo $score[0]['medic_hits_per_game']; ?></td>
			</tr>
			<?php endforeach; ?>
			<?php unset($score); ?>
		</table>
	</div>
</div>
<script>
$('#nightlySelectDate').change(function() {
	var table = $('#game_list').DataTable();
	var old_url = table.ajax.url();
	table.ajax.url(old_url.replace(/\d{4}-\d{2}-\d{2}/, $(this).val())).load();
});

$( "#accordion" ).accordion( {
	collapsible: true,
	heightStyle: "content",
	activate: function(event, ui) {
		var oTable = $('div.dataTables_scrollBody>table.display', ui.panel).dataTable();
		if(oTable.length > 0) {
			oTable.fnAdjustColumnSizing();
		}
	}
} );
</script>