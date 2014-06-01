<?php
	echo $this->Form->create('Scorecard', array('type' => 'file'));
	echo $this->Form->input('file', array('type' => 'file', 'label' => 'Scorecard CSV'));
	echo $this->Form->end('Upload');
?>