<div>
	<p>
		Choose a center:
	</p>
	<ul>
		<li><?= $this->Form->postLink('All', array('controller' => 'scorecards', 'action' => 'pickCenter'), array('data' => array('center_id' => 0))); ?></li>
		<?php
			foreach($centers as $center) {
				echo "<li>".$this->Form->postLink($center['Center']['name'], array('controller' => 'scorecards', 'action' => 'pickCenter'), array('data' => array('center_id' => $center['Center']['id'])))."</li>";
			}
		?>
	</ul>
</div>