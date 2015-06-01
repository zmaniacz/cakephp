<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"pageLength": 25,
			"order": [1,'desc']
		});
	});
</script>
<h2><?php echo __('Game List'); ?></h2>
<table class="table table-striped table-bordered table-hover table-condensed" id="game_list">
	<thead>
		<th>Game</th>
		<th>Time</th>
		<th>Winner Score</th>
		<th>Loser Score</th>
		<th>PDF</th>
		<?php if(AuthComponent::user('role') === 'admin'): ?>
			<th class="actions">Actions</th>
		<?php endif; ?>
	</thead>
	<tbody>
		<?php foreach ($games as $game): ?>
		<?php
			$red_team = ($game['Game']['red_team_id'] == null) ? 'Red Team' : $game['Red_Team']['name'];
			$green_team = ($game['Game']['green_team_id'] == null) ? 'Green Team' : $game['Green_Team']['name'];
		?>
		<?php if($game['Game']['winner'] == 'Red'): ?>
			<tr class="danger">
		<?php else: ?>
			<tr class="success">
		<?php endif; ?>
			<td>
			<?php
				if($game['Game']['type'] == 'league' || $game['Game']['type'] == 'tournament') {
					echo $this->Html->link($game['League']['name'].' - R'.$game['Game']['league_round'].' M'.$game['Game']['league_match'].' G'.$game['Game']['league_game'], array('controller' => 'Games', 'action' => 'view', $game['Game']['id'])); 
				} else {
					echo $this->Html->link($game['Game']['game_name'], array('action' => 'view', $game['Game']['id']));
				}					
			?>
			</td>
			<td><?php echo $game['Game']['game_datetime']; ?></td>
			<td><?php echo (($game['Game']['winner'] == 'Red') ? $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj']) : $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj'])); ?></td>
			<td><?php echo (($game['Game']['winner'] == 'Red') ? $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj']) : $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj'])); ?></td>
			<?php if($game['Game']['pdf_id'] == null): ?>
				<td></td>
			<?php else: ?>
				<td><a href="/pdf/<?php echo $game['Game']['pdf_id']; ?>.pdf">PDF</a></td>
			<?php endif; ?>
			<?php if(AuthComponent::user('role') === 'admin'): ?>
				<td class="actions">
					<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $game['Game']['id'])); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?>
				</td>
			<?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>