<div class="penalties view">
<h2><?php echo __('Penalty'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($penalty['Penalty']['id']); ?>
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
		<dt><?php echo __('Player'); ?></dt>
		<dd>
			<?php echo $this->Html->link($penalty['Player']['id'], array('controller' => 'players', 'action' => 'view', $penalty['Player']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Game'); ?></dt>
		<dd>
			<?php echo $this->Html->link($penalty['Game']['id'], array('controller' => 'games', 'action' => 'view', $penalty['Game']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Penalty'), array('action' => 'edit', $penalty['Penalty']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Penalty'), array('action' => 'delete', $penalty['Penalty']['id']), null, __('Are you sure you want to delete # %s?', $penalty['Penalty']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Penalties'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Penalty'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Players'), array('controller' => 'players', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Player'), array('controller' => 'players', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Games'), array('controller' => 'games', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Game'), array('controller' => 'games', 'action' => 'add')); ?> </li>
	</ul>
</div>
