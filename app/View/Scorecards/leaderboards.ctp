<script type="text/javascript">
	$(document).ready(function() {
		var oTable = $('.display').DataTable( {
			"order": [[1, "desc"]],
			"searching": false,
			"lengthChange": false,
			"pageLength": 5,
			"pagingType": "simple"
		} );
	} );
</script>
<div id="games_points" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#games_points" data-target="#collapse_games_points"" role="tab" id="games_points_heading">
		<h4 class="panel-title">
			Games and Points
		</h4>
	</div>
	<div id="collapse_games_points" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="games_played_table">
						<thead>
							<th>Name</th>
							<th>Total Games</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['games_played'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['games_played']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="score_total_total">
						<thead>
							<th>Name</th>
							<th>Total Score</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['score_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['score_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="medic_tomfoolery" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#medic_tomfoolery" data-target="#collapse_medic_tomfoolery"" role="tab" id="medic_tomfoolery_heading">
		<h4 class="panel-title">
			Medic Tomfoolery
		</h4>
	</div>
	<div id="collapse_medic_tomfoolery" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="medic_hits_table">
						<thead>
							<th>Name</th>
							<th>Total Medic Hits</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['medic_hits_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['medic_hits_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="own_medic_hits_table">
						<thead>
							<th>Name</th>
							<th>Own Medic Hits</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['own_medic_hits_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['own_medic_hits_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="missile_malarkey" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#missile_malarkey" data-target="#collapse_missile_malarkey"" role="tab" id="missile_malarkey_heading">
		<h4 class="panel-title">
			Missile Malarkey
		</h4>
	</div>
	<div id="collapse_missile_malarkey" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-4">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="missiled_opponent_table">
						<thead>
							<th>Name</th>
							<th>Total Missiles</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['missiled_opponent_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['missiled_opponent_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="times_missiled_table">
						<thead>
							<th>Name</th>
							<th>Total Times Missiled</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['times_missiled_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['times_missiled_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="missiled_team_table">
						<thead>
							<th>Name</th>
							<th>Team Missiles (You Idiot)</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['missiled_team_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['missiled_team_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="nuke_nonsense" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#nuke_nonsense" data-target="#collapse_nuke_nonsense"" role="tab" id="nuke_nonsense_heading">
		<h4 class="panel-title">
			Nuke Nonsense
		</h4>
	</div>
	<div id="collapse_nuke_nonsense" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-4">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="nukes_detonated_total_table">
						<thead>
							<th>Name</th>
							<th>Total Nukes Detonated</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['nukes_detonated_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['nukes_detonated_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="nukes_canceled_total_table">
						<thead>
							<th>Name</th>
							<th>Total Nukes Canceled</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['nukes_canceled_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['nukes_canceled_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-4">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="own_nuke_cancels_total_table">
						<thead>
							<th>Name</th>
							<th>Own Nukes Canceled</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['own_nuke_cancels_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['own_nuke_cancels_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="elimination_frustration" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#elimination_frustration" data-target="#collapse_elimination_frustration"" role="tab" id="elimination_frustration_heading">
		<h4 class="panel-title">
			Elimination Frustration
		</h4>
	</div>
	<div id="collapse_elimination_frustration" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="elim_other_team_total_table">
						<thead>
							<th>Name</th>
							<th>Eliminated Opposing Team</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['elim_other_team_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['elim_other_team_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="team_elim_total_table">
						<thead>
							<th>Name</th>
							<th>Own Team Eliminated</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['team_elim_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['team_elim_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="streaky" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#streaky" data-target="#collapse_streaky"" role="tab" id="streaky_heading">
		<h4 class="panel-title">
			Streaky
		</h4>
	</div>
	<div id="collapse_streaky" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="elim_other_team_total_table">
						<thead>
							<th>Name</th>
							<th>Longest Win Streak</th>
						</thead>
						<tbody>
							<?php foreach ($winstreaks as $row): ?>
							<tr>
								<td><?php echo $this->Html->link($row['players']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['streakset']['player_id'])); ?></td>
								<td><?php echo $row[0]['maxstreak']; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="elim_other_team_total_table">
						<thead>
							<th>Name</th>
							<th>Longest Losing Streak</th>
						</thead>
						<tbody>
							<?php foreach ($lossstreaks as $row): ?>
							<tr>
								<td><?php echo $this->Html->link($row['players']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['streakset']['player_id'])); ?></td>
								<td><?php echo $row[0]['maxstreak']; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="misc_mischief" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#misc_mischief" data-target="#collapse_misc_mischief"" role="tab" id="misc_mischief_heading">
		<h4 class="panel-title">
			Miscellaneous Mischief
		</h4>
	</div>
	<div id="collapse_misc_mischief" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<table class="display table table-striped table-bordered table-hover table-condensed" id="elim_other_team_total_table">
						<thead>
							<th>Name</th>
							<th>Shots Fired</th>
						</thead>
						<tbody>
							<?php foreach ($leaderboards as $row): ?>
							<?php if ($row[0]['shots_fired_total'] > 0): ?>
							<tr>
								<td><?php echo $this->Html->link($row['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $row['Player']['id'])); ?></td>
								<td><?php echo $row[0]['shots_fired_total']; ?></td>
							</tr>
							<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>