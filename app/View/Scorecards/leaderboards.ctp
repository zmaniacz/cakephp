<script type="text/javascript">
	$(document).ready(function() {
		var oTable = $('.display').DataTable( {
			"jQueryUI": true,
			"order": [[1, "desc"]],
			"searching": false,
			"lengthChange": false,
			"pageLength": 5
		} );
	} );
</script>
<div>
	<h3>Games and Points</h3>
	<div style="width: 30%; float: left; margin-left: 15%;">
		<table class="display" id="games_played_table">
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
	<div style="width: 30%; float: right; margin-right: 15%;">
		<table class="display" id="score_total_total">
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
	<div style="clear: both;"></div>
</div>
<br />
<br />
<br />
<br />
<br />
<div>
	<h3>Medic Tomfoolery</h3>
	<div style="width: 30%; float: left; margin-left: 15%;">
		<table class="display" id="medic_hits_table">
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
	<div style="width: 30%; float: right; margin-right: 15%;">
		<table class="display" id="own_medic_hits_table">
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
	<div style="clear: both;"></div>
</div>
<br />
<br />
<br />
<br />
<br />
<div>
	<h3>Missile Malarkey</h3>
	<div style="width: 25%; float: left; margin-left: 5%;">
		<table class="display" id="missiled_opponent_table">
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
	<div style="width: 25%; float: left; margin-left: 7.5%;">
		<table class="display" id="times_missiled_table">
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
	<div style="width: 25%; float: right; margin-right: 5%;">
		<table class="display" id="missiled_team_table">
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
	<div style="clear: both;"></div>
</div>
<br />
<br />
<br />
<br />
<br />
<div>
	<h3>Nuke Nonsense</h3>
	<div style="width: 25%; float: left; margin-left: 5%;">
		<table class="display" id="nukes_detonated_total_table">
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
	<div style="width: 25%; float: left; margin-left: 7.5%;">
		<table class="display" id="nukes_canceled_total_table">
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
	<div style="width: 25%; float: right; margin-right: 5%;">
		<table class="display" id="own_nuke_cancels_total_table">
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
	<div style="clear: both;"></div>
</div>
<br />
<br />
<br />
<br />
<br />
<div>
	<h3>Elimination Frustration</h3>
	<div style="width: 30%; float: left; margin-left: 15%;">
		<table class="display" id="elim_other_team_total_table">
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
	<div style="width: 30%; float: right; margin-right: 15%;">
		<table class="display" id="team_elim_total_table">
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
	<div style="clear: both;"></div>
</div>
<br />
<br />
<br />
<br />
<br />
<div>
	<h3>Miscellaneous Mischief</h3>
	<div style="width: 30%; float: left; margin-left: 15%;">
		<table class="display" id="elim_other_team_total_table">
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
	<div style="clear: both;"></div>
</div>