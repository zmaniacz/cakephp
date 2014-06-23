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
		<dt><?php echo __('Scorecard'); ?></dt>
		<dd>
			<?php echo $this->Html->link($penalty['Scorecard']['id'], array('controller' => 'scorecards', 'action' => 'view', $penalty['Scorecard']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('List Scorecards'), array('controller' => 'scorecards', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scorecard'), array('controller' => 'scorecards', 'action' => 'add')); ?> </li>
	</ul>
</div>
