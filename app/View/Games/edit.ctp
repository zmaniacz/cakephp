<div class="games form">
<?php echo $this->Form->create('Game'); ?>
	<fieldset>
		<legend><?php echo __('Edit Game'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('game_name');
		echo $this->Form->input('game_description');
		echo $this->Form->input('red_team_name');
		echo $this->Form->input('red_score');
		echo $this->Form->input('green_team_name');
		echo $this->Form->input('green_score');
		echo $this->Form->input('red_adj');
		echo $this->Form->input('green_adj');
		echo $this->Form->input('winner');
		echo $this->Form->input('red_eliminated');
		echo $this->Form->input('green_eliminated');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('List Games'), array('action' => 'index')); ?></li>
	</ul>
</div>
