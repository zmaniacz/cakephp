<div class="penalties index">
	<h2><?php echo __('Penalties'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $this->Paginator->sort('game'); ?></th>
		<th><?php echo $this->Paginator->sort('player'); ?></th>
		<th><?php echo $this->Paginator->sort('type'); ?></th>
		<th><?php echo $this->Paginator->sort('description'); ?></th>
		<th><?php echo $this->Paginator->sort('value'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($penalties as $penalty): ?>
	<tr>
		<td><?php echo $this->Html->link($penalty['Scorecard']['Game']['game_name']." ".$penalty['Scorecard']['Game']['game_datetime'], array('controller' => 'games', 'action' => 'view', $penalty['Scorecard']['Game']['id'])); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($penalty['Scorecard']['Player']['player_name'], array('controller' => 'players', 'action' => 'view', $penalty['Scorecard']['Player']['id'])); ?>
		</td>	
		<td><?php echo h($penalty['Penalty']['type']); ?>&nbsp;</td>
		<td><?php echo h($penalty['Penalty']['description']); ?>&nbsp;</td>
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
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
