<!DOCTYPE html>
<html>
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->script('jquery-2.1.1.min.js');
		echo $this->Html->script('jquery.dataTables.min.js');
		echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('laserforce','cake.generic','ui-lightness/jquery-ui-1.10.3.custom.min','jquery.dataTables.css','jquery.dataTables_themeroller.css'));
	?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Laserforce - <?php echo strtoupper($this->params->center); ?></h1>
			<h1><?php echo $this->Html->link("Player List", array('controller' => 'players', 'action' => 'index')); ?></h1>
			<h1><?php echo $this->Html->link("Top Players", array('controller' => 'scorecards', 'action' => 'overall')); ?></h1>
			<h1><?php echo $this->Html->link("Nightly Stats", array('controller' => 'scorecards', 'action' => 'nightly')); ?></h1>
			<h1><?php echo $this->Html->link("Center Stats", array('controller' => 'games', 'action' => 'overall')); ?></h1>
			<h1><?php echo $this->Html->link("All-Center Teams", array('controller' => 'scorecards', 'action' => 'allcenter')); ?></h1>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<?php
		//echo $this->element('sql_dump');
		echo $this->Js->writeBuffer();
	?>
</body>
</html>
