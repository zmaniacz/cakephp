<div class="games index">
	<h2><?php echo __('Games'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('game_name'); ?></th>
			<th><?php echo $this->Paginator->sort('game_description'); ?></th>
			<th><?php echo $this->Paginator->sort('game_datetime'); ?></th>
			<th><?php echo $this->Paginator->sort('red_score'); ?></th>
			<th><?php echo $this->Paginator->sort('green_score'); ?></th>
			<th><?php echo $this->Paginator->sort('red_adj'); ?></th>
			<th><?php echo $this->Paginator->sort('green_adj'); ?></th>
			<th><?php echo $this->Paginator->sort('winner'); ?></th>
			<th><?php echo $this->Paginator->sort('red_eliminated'); ?></th>
			<th><?php echo $this->Paginator->sort('green_eliminated'); ?></th>
			<th><?php echo $this->Paginator->sort('pdf_id'); ?></th>
			<th><?php echo $this->Paginator->sort('center_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($games as $game): ?>
	<tr>
		<td><?php echo h($game['Game']['id']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['game_name']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['game_description']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['game_datetime']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['red_score']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['green_score']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['red_adj']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['green_adj']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['winner']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['red_eliminated']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['green_eliminated']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['pdf_id']); ?>&nbsp;</td>
		<td><?php echo h($game['Game']['center_id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $game['Game']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $game['Game']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $game['Game']['id']), null, __('Are you sure you want to delete # %s?', $game['Game']['id'])); ?>
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
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Game'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Scorecards'), array('controller' => 'scorecards', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scorecard'), array('controller' => 'scorecards', 'action' => 'add')); ?> </li>
	</ul>
</div>
