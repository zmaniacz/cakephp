<div>
	<p>
		What kind of stats do you want to see?
	</p>
	<ul>
		<li><?= $this->Form->postLink('All', array('controller' => 'scorecards', 'action' => 'index'), array('data' => array('gametype' => 'all'))); ?></li>
		<li><?= $this->Form->postLink('Social', array('controller' => 'scorecards', 'action' => 'index'), array('data' => array('gametype' => 'social'))); ?></li>
		<li><?= $this->Form->postLink('Competitions', array('controller' => 'scorecards', 'action' => 'index'), array('data' => array('gametype' => 'league'))); ?></li>
	</ul>
</div>
	