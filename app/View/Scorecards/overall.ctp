<script type="text/javascript">
	$(document).ready(function() {
		var oTable = $('.display').DataTable( {
			"scrollX": true,
			"order": [[1, "desc"]]
		} );
	} );
</script>
<div id="overall" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#overall" data-target="#collapse_overall" role="tab" id="overall_heading">
		<h4 class="panel-title">
			Average Averages
		</h4>
	</div>
	<div id="collapse_overall" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover" id="averages_table">
				<thead>
					<tr>
						<th rowspan="2">Name</th>
						<th colspan="3">Overall</th>
						<th colspan="3">Commander</th>
						<th colspan="3">Heavy Weapons</th>
						<th colspan="3">Scout</th>
						<th colspan="3">Ammo Carrier</th>
						<th colspan="3">Medic</th>
					</tr>
					<tr>
						<th>MVP</th>
						<th>Accuracy</th>
						<th>Games Played</th>
						<th>MVP</th>
						<th>Accuracy</th>
						<th>Games Played</th>
						<th>MVP</th>
						<th>Accuracy</th>
						<th>Games Played</th>
						<th>MVP</th>
						<th>Accuracy</th>
						<th>Games Played</th>
						<th>MVP</th>
						<th>Accuracy</th>
						<th>Games Played</th>
						<th>MVP</th>
						<th>Accuracy</th>
						<th>Games Played</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($averages as $key => $value): ?>
					<tr>
						<td><?php echo $this->Html->link($value['player_name'], array('controller' => 'Players', 'action' => 'view', $key)); ?></td>
						<td><?php echo round($value['avg_avg_mvp'],2); ?></td>
						<td><?php echo round($value['avg_avg_acc']*100,2); ?></td>
						<td><?php echo round($value['total_games'],2); ?></td>
						<td><?php echo round($value['Commander']['avg_mvp'],2); ?></td>
						<td><?php echo round($value['Commander']['avg_acc']*100,2); ?></td>
						<td><?php echo round($value['Commander']['games_played'],2); ?></td>
						<td><?php echo round($value['Heavy Weapons']['avg_mvp'],2); ?></td>
						<td><?php echo round($value['Heavy Weapons']['avg_acc']*100,2); ?></td>
						<td><?php echo round($value['Heavy Weapons']['games_played'],2); ?></td>
						<td><?php echo round($value['Scout']['avg_mvp'],2); ?></td>
						<td><?php echo round($value['Scout']['avg_acc']*100,2); ?></td>
						<td><?php echo round($value['Scout']['games_played'],2); ?></td>
						<td><?php echo round($value['Ammo Carrier']['avg_mvp'],2); ?></td>
						<td><?php echo round($value['Ammo Carrier']['avg_acc']*100,2); ?></td>
						<td><?php echo round($value['Ammo Carrier']['games_played'],2); ?></td>
						<td><?php echo round($value['Medic']['avg_mvp'],2); ?></td>
						<td><?php echo round($value['Medic']['avg_acc']*100,2); ?></td>
						<td><?php echo round($value['Medic']['games_played'],2); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="commander" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#commander" data-target="#collapse_commander" role="tab" id="commander_heading">
		<h4 class="panel-title">
			Commander
		</h4>
	</div>
	<div id="collapse_commander" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover" id="commander_overall">
				<thead>
					<tr>
						<th>Name</th>
						<th>Average Score</th>
						<th>Average MVP Points</th>
						<th class="accuracy">Average Accuracy</th>
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
						<td><?php echo round($score[0]['avg_acc']*100,2); ?></td>
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
	</div>
</div>
<div id="heavy" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#heavy" data-target="#collapse_heavy" role="tab" id="heavy_heading">
		<h4 class="panel-title">
			Heavy Weapons
		</h4>
	</div>
	<div id="collapse_heavy" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Average Score</th>
						<th>Average MVP Points</th>
						<th class="accuracy">Average Accuracy</th>
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
						<td><?php echo round($score[0]['avg_acc']*100,2); ?></td>
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
	</div>
</div>
<div id="scout" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#scout" data-target="#collapse_scout" role="tab" id="scout_heading">
		<h4 class="panel-title">
			Scout
		</h4>
	</div>
	<div id="collapse_scout" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Average Score</th>
						<th>Average MVP Points</th>
						<th class="accuracy">Average Accuracy</th>
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
						<td><?php echo round($score[0]['avg_acc']*100,2); ?></td>
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
	</div>
</div>
<div id="ammo" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#ammo" data-target="#collapse_ammo" role="tab" id="ammo_heading">
		<h4 class="panel-title">
			Ammo Carrier
		</h4>
	</div>
	<div id="collapse_ammo" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Average Score</th>
						<th>Average MVP Points</th>
						<th class="accuracy">Average Accuracy</th>
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
						<td><?php echo round($score[0]['avg_acc']*100,2); ?></td>
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
	</div>
</div>
<div id="medic" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#medic" data-target="#collapse_medic" role="tab" id="medic_heading">
		<h4 class="panel-title">
			Medic
		</h4>
	</div>
	<div id="collapse_medic" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Average Score</th>
						<th>Average MVP Points</th>
						<th class="accuracy">Average Accuracy</th>
						<th>Hit Differential</th>
						<th>Average Boosts</th>
						<th>Average Resupplies</th>
						<th>Average Lives Left</th>
						<th class="team_elim">Team Elimination Rate</th>
						<th>Games Played</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($medic as $score): ?>
					<tr>
						<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
						<td><?php echo $score[0]['avg_score']; ?></td>
						<td><?php echo round($score[0]['avg_mvp'],2); ?></td>
						<td><?php echo round($score[0]['avg_acc']*100,2); ?></td>
						<td><?php echo $score[0]['hit_diff']; ?></td>
						<td><?php echo $score[0]['avg_life_boost']; ?></td>
						<td><?php echo $score[0]['avg_resup']; ?></td>
						<td><?php echo $score[0]['avg_lives']; ?></td>
						<td><?php echo round($score[0]['elim_rate']*100,2); ?></td>
						<td><?php echo $score[0]['games_played']; ?></td>
					</tr>
					<?php endforeach; ?>
					<?php unset($score); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="medic_hits" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#medic_hits" data-target="#collapse_medic_hits" role="tab" id="medic_hits_heading">
		<h4 class="panel-title">
			Medic Hits
		</h4>
	</div>
	<div id="collapse_medic_hits" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>Name</th>
						<th>Total Medic Hits (All)</th>
						<th>Average Medic Hits (All)</th>
						<th>Games Played (All)</th>
						<th>Total Medic Hits (Non-Resupply)</th>
						<th>Average Medic Hits (Non-Resupply)</th>
						<th>Games Played (Non-Resupply)</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($medic_hits as $score): ?>
					<tr>
						<td><?php echo $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id'])); ?></td>
						<td><?php echo $score[0]['total_medic_hits']; ?></td>
						<td><?php echo round($score[0]['medic_hits_per_game'],2); ?></td>
						<td><?php echo round($score[0]['games_played'],2); ?></td>
						<td><?php echo $score['ScorecardNoResup']['total_medic_hits']; ?></td>
						<td><?php echo round($score['ScorecardNoResup']['medic_hits_per_game'],2); ?></td>
						<td><?php echo round($score['ScorecardNoResup']['games_played'],2); ?></td>
					<?php endforeach; ?>
					<?php unset($score); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>