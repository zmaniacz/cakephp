<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"pageLength": 25,
			"order": [1,'desc']
		});
	});
</script>
<div id="game_list_panel" class="panel panel-info">
	<div class="panel-heading" role="tab" id="game_list_heading">
		<h4 class="panel-title">
			Game List
		</h4>
	</div>
	<div class="panel-body">
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
					
					if(!empty($game['Match']['id'])) {
						$game_name = 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'];
						if($game['Match']['Round']['is_finals'])
							$game_name .= '(Finals)';
					} else {
						$game_name = $game['Game']['game_name'];
					}
				?>
				<?php if($game['Game']['winner'] == 'Red'): ?>
					<tr class="danger">
				<?php else: ?>
					<tr class="success">
				<?php endif; ?>
					<td><?= $this->Html->link($game_name, array('action' => 'view', $game['Game']['id'])); ?>
					</td>
					<td><?= $game['Game']['game_datetime']; ?></td>
					<td><?= (($game['Game']['winner'] == 'Red') ? $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj']) : $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj'])); ?></td>
					<td><?= (($game['Game']['winner'] == 'Red') ? $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj']) : $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj'])); ?></td>
					<?php if($game['Game']['pdf_id'] == null): ?>
						<td></td>
					<?php else: ?>
						<td><a href="/pdf/<?php echo $game['Game']['pdf_id']; ?>.pdf">PDF</a></td>
					<?php endif; ?>
					<?php if(AuthComponent::user('role') === 'admin'): ?>
						<td class="actions">
							<?= $this->Html->link(__('Edit'), array('action' => 'edit', $game['Game']['id'])); ?>
							<?= $this->Form->postLink(__('Delete'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?>
						</td>
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<?= debug($games); ?>