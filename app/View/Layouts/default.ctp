<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->script('http://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.0.3.min.js');
		echo $this->Html->script('http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js');
		echo $this->Html->script('jquery-ui-1.10.3.custom.min.js');
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('laserforce','cake.generic','ui-lightness/jquery-ui-1.10.3.custom.min','http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css','http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables_themeroller.css'));
	?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>Laserforce - LTC</h1>
			<h1><?php echo $this->Html->link("Player List", array('controller' => 'players', 'action' => 'index')); ?></h1>
			<h1><?php echo $this->Html->link("Top Players", array('controller' => 'scorecards', 'action' => 'overall')); ?></h1>
			<h1><?php echo $this->Html->link("Nightly Stats", array('controller' => 'scorecards', 'action' => 'nightly')); ?></h1>
		</div>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div>
	</div>
	<?php
		//echo $this->element('sql_dump');
		echo $this->Js->writeBuffer();
	?>
</body>
</html>
