<div>
	<p>
		What kind of stats do you want to see?
	</p>
	<ul>
		<li><?= $this->Html->link('All', array('controller' => 'scorecards', 'action' => 'pickCenter', 'all')); ?></li>
		<li><?= $this->Html->link('Social', array('controller' => 'scorecards', 'action' => 'pickCenter', 'social')); ?></li>
		<li><?= $this->Html->link('Competitions', array('controller' => 'scorecards', 'action' => 'pickLeague')); ?></li>
	</ul>
</div>
	