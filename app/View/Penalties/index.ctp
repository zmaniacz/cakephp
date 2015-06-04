<script type="text/javascript">
	$(document).ready(function() {
		$('.display').DataTable( {
			"order": [[1, "desc"]]
		} );
	} );
</script>
<div id="penalties_list" class="panel panel-info">
	<div class="panel-heading" role="tab" id="overall_heading">
		<h4 class="panel-title">
			Penalties
		</h4>
	</div>
	<div class="panel-body">
			<table class="display table table-striped table-bordered table-hover" id="penalties_table">
				<thead>
					<th>Game</th>
					<th>Date/Time</th>
					<th>Player</th>
					<th>Type</th>
					<th>Value</th>
					<th>Actions</th>
				</thead>
				<?php foreach ($penalties as $penalty): ?>
					<tr>
						<td><?php echo $this->Html->link($penalty['Scorecard']['Game']['game_name']." ".$penalty['Scorecard']['Game']['game_datetime'], array('controller' => 'games', 'action' => 'view', $penalty['Scorecard']['Game']['id'])); ?>&nbsp;</td>
						<td><?php echo $penalty['Scorecard']['Game']['game_datetime']; ?></td>
						<td>
							<?php echo $this->Html->link($penalty['Scorecard']['Player']['player_name'], array('controller' => 'players', 'action' => 'view', $penalty['Scorecard']['Player']['id'])); ?>
						</td>	
						<td><?php echo h($penalty['Penalty']['type']); ?>&nbsp;</td>
						<td><?php echo h($penalty['Penalty']['value']); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('View'), array('action' => 'view', $penalty['Penalty']['id'])); ?>
							<?php 
								if(AuthComponent::user('role') === 'admin') {
									echo $this->Html->link(__('Edit'), array('action' => 'edit', $penalty['Penalty']['id']));
									echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $penalty['Penalty']['id']), null, __('Are you sure you want to delete # %s?', $penalty['Penalty']['id']));
								}
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>
