<div class="penalties form">
<?php echo $this->Form->create('Penalty'); ?>
	<fieldset>
		<legend><?php echo __('Edit Penalty'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('type');
		echo $this->Form->input('description');
		echo $this->Form->input('value');
		echo $this->Form->input('scorecard_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Penalty.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Penalty.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Penalties'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Scorecards'), array('controller' => 'scorecards', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Scorecard'), array('controller' => 'scorecards', 'action' => 'add')); ?> </li>
	</ul>
</div>
