<h1>Stats for <?php echo $current_date;?></h1>
<?php
	echo $this->Form->create();
	echo $this->Form->input('date', array('options' => $game_dates));
	echo $this->Form->submit();
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
		
		var gTable = $('.gamelist').dataTable( {
			"bAutoWidth": false,
			"bFilter": false,
			"bInfo": false,
			"bPaginate": false,
			"bSort": false,
			"bJQueryUI": true,
			"bRetrieve": true
		} );
	} );
</script>
<h3>Games Played</h3>
<div style="width: 500px;">
	<table class="gamelist">
		<thead>
			<th>Game</th>
			<th>Time</th>
			<th>Red Score</th>
			<th>Green Score</th>
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
					<td><?php echo (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) ? $this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf") : ""; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
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