<div class="penalties form">
<?php echo $this->Form->create('Penalty'); ?>
	<fieldset>
		<legend><?php echo __('Add Penalty'); ?></legend>
	<?php
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
				'Unsportsmanlike Conduct' => 'Unsportsmanlike Conduct'
			)
		));
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
		<li><?php echo $this->Html->link(__('List Penalties'), array('action' => 'index')); ?></li>
	</ul>
</div>
