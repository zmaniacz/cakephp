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
	} );
</script>
<div>
	<p>All stats below require a minimum 3 games at each position.</p>
</div>
<div id="accordion">
	<h3>Commander</h3>
	<div>
		<table class="display" id="commander_overall">
			<thead>
				<tr>
					<th>Name</th>
					<th>Average Score</th>
					<th>Average MVP Points</th>
					<th>Average Accuracy</th>
					<th>Nuke Success Ratio</th>
					<th>Hit Differential</th>
					<th>Average Missiles</th>
					<th>Average Medic Hits</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($commander as $score): ?>
				<tr>
					<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
					<td><?php echo $score[0]['avg_score']; ?></td>
					<td><?php echo round($score[0]['avg_mvp'],2); ?></td>
					<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
					<td><?php echo $score[0]['nuke_ratio']; ?></td>
					<td><?php echo $score[0]['hit_diff']; ?></td>
					<td><?php echo $score[0]['avg_missiles']; ?></td>
					<td><?php echo $score[0]['avg_medic_hits']; ?></td>
					<td><?php echo $score[0]['games_played']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php unset($score); ?>
			</tbody>
		</table>
	</div>
	<h3>Heavy Weapons</h3>
	<div>
		<table class="display">
			<thead>
				<tr>
					<th>Name</th>
					<th>Average Score</th>
					<th>Average MVP Points</th>
					<th>Average Accuracy</th>
					<th>Hit Differential</th>
					<th>Average Missiles</th>
					<th>Average Medic Hits</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($heavy as $score): ?>
				<tr>
					<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
					<td><?php echo $score[0]['avg_score']; ?></td>
					<td><?php echo round($score[0]['avg_mvp'],2); ?></td>
					<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
					<td><?php echo $score[0]['hit_diff']; ?></td>
					<td><?php echo $score[0]['avg_missiles']; ?></td>
					<td><?php echo $score[0]['avg_medic_hits']; ?></td>
					<td><?php echo $score[0]['games_played']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php unset($score); ?>
			</tbody>
		</table>
	</div>
	<h3>Scout</h3>
	<div>
		<table class="display">
			<thead>
				<tr>
					<th>Name</th>
					<th>Average Score</th>
					<th>Average MVP Points</th>
					<th>Average Accuracy</th>
					<th>Hit Differential</th>
					<th>Average 3Hit Hits</th>
					<th>Average Medic Hits</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($scout as $score): ?>
				<tr>
					<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
					<td><?php echo $score[0]['avg_score']; ?></td>
					<td><?php echo round($score[0]['avg_mvp'],2); ?></td>
					<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
					<td><?php echo $score[0]['hit_diff']; ?></td>
					<td><?php echo $score[0]['avg_3hit']; ?></td>
					<td><?php echo $score[0]['avg_medic_hits']; ?></td>
					<td><?php echo $score[0]['games_played']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php unset($score); ?>
			</tbody>
		</table>
	</div>
	<h3>Ammo Carrier</h3>
	<div>
		<table class="display">
			<thead>
				<tr>
					<th>Name</th>
					<th>Average Score</th>
					<th>Average MVP Points</th>
					<th>Average Accuracy</th>
					<th>Hit Differential</th>
					<th>Average Boosts</th>
					<th>Average Resupplies</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($ammo as $score): ?>
				<tr>
					<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
					<td><?php echo $score[0]['avg_score']; ?></td>
					<td><?php echo round($score[0]['avg_mvp'],2); ?></td>
					<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
					<td><?php echo $score[0]['hit_diff']; ?></td>
					<td><?php echo $score[0]['avg_ammo_boost']; ?></td>
					<td><?php echo $score[0]['avg_resup']; ?></td>
					<td><?php echo $score[0]['games_played']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php unset($score); ?>
			</tbody>
		</table>
	</div>
	<h3>Medic</h3>
	<div>
		<table class="display">
			<thead>
				<tr>
					<th>Name</th>
					<th>Average Score</th>
					<th>Average MVP Points</th>
					<th>Average Accuracy</th>
					<th>Hit Differential</th>
					<th>Average Boosts</th>
					<th>Average Resupplies</th>
					<th>Average Lives Left</th>
					<th>Team Elimination Rate</th>
					<th>Games Played</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($medic as $score): ?>
				<tr>
					<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
					<td><?php echo $score[0]['avg_score']; ?></td>
					<td><?php echo round($score[0]['avg_mvp'],2); ?></td>
					<td><?php echo round($score[0]['avg_acc']*100,2); ?>%</td>
					<td><?php echo $score[0]['hit_diff']; ?></td>
					<td><?php echo $score[0]['avg_life_boost']; ?></td>
					<td><?php echo $score[0]['avg_resup']; ?></td>
					<td><?php echo $score[0]['avg_lives']; ?></td>
					<td><?php echo round($score[0]['elim_rate']*100,2); ?>%</td>
					<td><?php echo $score[0]['games_played']; ?></td>
				</tr>
				<?php endforeach; ?>
				<?php unset($score); ?>
			</tbody>
		</table>
	</div>
	<h3>Medic Hits</h3>
	<div>
		<table class="display">
			<thead>
				<tr>
					<th>Name</th>
					<th>Total Medic Hits</th>
					<th>Average Medics Hits</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($medic_hits as $score): ?>
				<tr>
					<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
					<td><?php echo $score[0]['total_medic_hits']; ?></td>
					<td><?php echo round($score[0]['medic_hits_per_game'],2); ?></td>
				<?php endforeach; ?>
				<?php unset($score); ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
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