<div class="penalties view">
<h2><?php echo __('Penalty'); ?></h2>
	<dl>
		<dt><?php echo __('Player'); ?></dt>
		<dd>
			<?php echo $this->Html->link(h($penalty['Scorecard']['Player']['player_name']), array('controller' => 'Players', 'action' => 'view', $penalty['Scorecard']['Player']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Type'); ?></dt>
		<dd>
			<?php echo h($penalty['Penalty']['type']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($penalty['Penalty']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Value'); ?></dt>
		<dd>
			<?php echo h($penalty['Penalty']['value']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Game'); ?></dt>
		<dd>
			<?php echo $this->Html->link(h($penalty['Scorecard']['Game']['game_name'])." ".h($penalty['Scorecard']['Game']['game_datetime']), array('controller' => 'Games', 'action' => 'view', $penalty['Scorecard']['Game']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<?php if(AuthComponent::user('role') === 'admin'): ?>
		<li><?php echo $this->Html->link(__('Edit Penalty'), array('action' => 'edit', $penalty['Penalty']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Penalty'), array('action' => 'delete', $penalty['Penalty']['id']), null, __('Are you sure you want to delete # %s?', $penalty['Penalty']['id'])); ?> </li>
		<?php endif; ?>
		<li><?php echo $this->Html->link(__('List Penalties'), array('action' => 'index')); ?> </li>
	</ul>
</div>
