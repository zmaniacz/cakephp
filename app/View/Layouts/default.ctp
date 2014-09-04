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
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->script('jquery-2.1.1.min.js');
		echo $this->Html->script('jquery-ui.min.js');
		echo $this->Html->script('//cdn.datatables.net/1.10.1/js/jquery.dataTables.js');
		echo $this->Html->script('//cdn.datatables.net/plug-ins/725b2a2115b/integration/jqueryui/dataTables.jqueryui.js');
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('laserforce','cake.generic','ui-lightness/jquery-ui.min.css'));
		echo $this->Html->css('//cdn.datatables.net/plug-ins/725b2a2115b/integration/jqueryui/dataTables.jqueryui.css');
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#topmenu").menu();
		});
	</script>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<body>
	<div id="container">
		<div id="header">
			<div style="float:right;">
				<?php if (AuthComponent::user('id')): ?>
					Logged in as <?= AuthComponent::user('username') ?> - <button><?php echo $this->Html->link("Logout", array('controller' => 'users', 'action' => 'logout')); ?></button>
				<?php else: ?>
					<button><?php echo $this->Html->link("Login", array('controller' => 'users', 'action' => 'login')); ?></button>
				<?php endif; ?>
			</div>
			<h1>Laserforce - <?php echo strtoupper($this->params->center); ?></h1>
			<ul id="topmenu">
				<li><?php echo $this->Html->link("Top Players", array('controller' => 'scorecards', 'action' => 'overall')); ?></li>
				<li><?php echo $this->Html->link("Game List", array('controller' => 'games', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link("Nightly Stats", array('controller' => 'scorecards', 'action' => 'nightly')); ?></li>
				<li><?php echo $this->Html->link("Center Stats", array('controller' => 'games', 'action' => 'overall')); ?></li>
				<li><?php echo $this->Html->link("All-Center Teams", array('controller' => 'scorecards', 'action' => 'allcenter')); ?></li>
				<li><?php echo $this->Html->link("Penalties", array('controller' => 'penalties', 'action' => 'index')); ?></li>
				<li><?php echo $this->Html->link("About SM5", array('controller' => 'pages', 'action' => 'aboutSM5')); ?></li>
				<?php
					if(AuthComponent::user('role') === 'admin') {
						echo "<li>".$this->Html->link("Upload PDF", array('controller' => 'scorecards', 'action' => 'uploadpdf'))."</li>";
						echo "<li>".$this->Html->link("Upload CSV", array('controller' => 'scorecards', 'action' => 'uploadcsv'))."</li>";
					}
				?>
			</ul>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false, 'id' => 'cake-powered')
				);
			?>
			<p>
				<?php echo $cakeVersion; ?>
			</p>
		</div>
	</div>
	<?php
		//echo $this->element('sql_dump');
		echo $this->Js->writeBuffer();
	?>
</body>
</html>
