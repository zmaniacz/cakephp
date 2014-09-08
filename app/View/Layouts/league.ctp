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
			<?php if(isset($league)): ?>
			<h1>Laserforce - <?php echo $league['League']['name']; ?></h1>
			<ul id="topmenu">
				<li><?php echo $this->Html->link("Team Standings", array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'standings')); ?></li>
				<li><?php echo $this->Html->link("Player Standings", array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'players')); ?></li>
				<li><?php echo $this->Html->link("Game List", array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'gamelist')); ?></li>
				<li><?php echo $this->Html->link("Penalties", array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'penalties')); ?></li>
				<li><?php echo $this->Html->link("About SM5", array('controller' => 'pages', 'action' => 'aboutSM5')); ?></li>
				<?php
					if(AuthComponent::user('role') === 'admin') {
						echo "<li>".$this->Html->link("Upload PDF", array('controller' => 'uploads', 'league_id' => $league['League']['id'], 'center_id' => $league['League']['center_id']))."</li>";
					}
				?>
			</ul>
			<?php endif; ?>
		</div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
		</div>
	</div>
	<?php
		echo $this->element('sql_dump');
		echo $this->Js->writeBuffer();
	?>
</body>
</html>
