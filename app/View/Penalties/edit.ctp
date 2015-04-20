<div class="penalties form">
<?php echo $this->Form->create('Penalty'); ?>
	<fieldset>
		<legend><?php echo __('Edit Penalty'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('type', array(
			'options' => array(
				'Illegal Language' => 'Illegal Language',
				'Leaving Starting Area' => 'Leaving Starting Area',
				'Leaving Playing Arena' => 'Leaving Playing Arena',
				'Physical Abuse' => 'Physical Abuse',
				'Dangerous Play' => 'Dangerous Play',
				'Blocking' => 'Blocking',
				'Removing Equipment' => 'Removing Equipment',
				'Sitting or Lying' => 'Sitting or Lying',
				'Climbing' => 'Climbing',
				'Swapping Guns' => 'Swapping Guns',
				'Loitering' => 'Loitering',
				'Illegal Interaction' => 'Illegal Interaction',
				'Shielding' => 'Shielding',
				'Illegal Targeting' => 'Illegal Targeting',
				'Chasing' => 'Chasing',
				'Shoulder Tilting' => 'Shoulder Tilting',
				'Unsportsmanlike Conduct' => 'Unsportsmanlike Conduct',
				'Penalty Removed' => 'Penalty Removed'
			)
		));
		echo $this->Form->input('description');
		echo $this->Form->input('value');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Penalty.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Penalty.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Penalties'), array('action' => 'index')); ?></li>
	</ul>
</div>
