<div class="leagues index">
	<h2><?php echo __('Leagues'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($leagues as $league): ?>
	<tr>
		<td><?php echo $this->Html->link(h($league['League']['name']), array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'standings')); ?>&nbsp;</td>
		<td><?php echo h($league['League']['description']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $league['League']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $league['League']['id']), array(), __('Are you sure you want to delete # %s?', $league['League']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
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
<?php if (AuthComponent::user('id')): ?>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New League'), array('action' => 'add')); ?></li>
	</ul>
</div>
<?php endif; ?>
