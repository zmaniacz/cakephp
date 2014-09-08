<div class="teams form">
<?php echo $this->Form->create('Team'); ?>
	<fieldset>
		<legend><?php echo 'Add Team'; ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('league_id');
		echo $this->Form->input('captain_id');
	?>
	</fieldset>
<?php echo $this->Form->end('Submit'); ?>
</div>
