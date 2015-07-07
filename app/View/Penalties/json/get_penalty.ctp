<?php $this->log('modal', 'debug'); ?>
<dl class="dl-horizontal">
	<dt><?php echo __('Player'); ?></dt>
	<dd>
		<?php echo $this->Html->link(h($penalty['Scorecard']['Player']['player_name']), array('controller' => 'Players', 'action' => 'view', $penalty['Scorecard']['Player']['id'])); ?>
	</dd>
	<dt><?php echo __('Type'); ?></dt>
	<dd>
		<?php echo h($penalty['Penalty']['type']); ?>
	</dd>
	<dt><?php echo __('Description'); ?></dt>
	<dd>
		<?php echo h($penalty['Penalty']['description']); ?>
	</dd>
	<dt><?php echo __('Value'); ?></dt>
	<dd>
		<?php echo h($penalty['Penalty']['value']); ?>
	</dd>
	<dt><?php echo __('Game'); ?></dt>
	<dd>
		<?php echo $this->Html->link(h($penalty['Scorecard']['Game']['game_name'])." ".h($penalty['Scorecard']['Game']['game_datetime']), array('controller' => 'Games', 'action' => 'view', $penalty['Scorecard']['Game']['id'])); ?>
	</dd>
</dl>
<?php if(AuthComponent::user('role') === 'admin'): ?>
<a href=<?= $this->Html->url(array('controller' => 'penalties', 'action' => 'edit', $penalty['Penalty']['id'])); ?> class="btn btn-warning" role="button">Edit Penalty</a>
<?= $this->Form->postButton(__('Delete Penalty'), array('controller' => 'penalties', 'action' => 'delete', $penalty['Penalty']['id']), array('class' => 'btn btn-danger'), __('Are you sure you want to delete # %s?', $penalty['Penalty']['id'])); ?>
<?php endif; ?>