<?php
echo $this->Form->create('Scorecard', array('type' => 'file'));
echo $this->Form->input('files.', array('type' => 'file', 'multiple', 'label' => 'Scorecard PDFs'));
echo $this->Form->end('Upload');
?>