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
//debug($this->Session->read());
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->script('//code.jquery.com/jquery-2.1.4.min.js');
		echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js');
		echo $this->Html->script('//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js');
		echo $this->Html->script('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js');
		echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootswatch/3.3.4/slate/bootstrap.min.css');
		echo $this->Html->css('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css');
		echo $this->Html->css('laserforce');
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<body>
	<div class="container">
		<div id="container">
			<div id="header">
				<nav class="navbar navbar-default navbar-fixed-top">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
      						<a class="navbar-brand" href="#"><img src="/img/LF-logo1-shadow-small.png"></a>
    					</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li><a href="/scorecards/overall">Top Players</a></li>
								<li><a href="/games/index">Game List</a></li>
								<li>
								<?php
									if($this->Session->read('state.gametype') == 'league') {
										echo $this->Html->link('Standings', array('controller' => 'leagues', 'action' => 'standings'));
									} else {
										echo $this->Html->link('Nightly Stats', array('controller' => 'scorecards', 'action' => 'nightly'));
									}
								?>
								</li>
								<li><a href="/scorecards/leaderboards">Leader(Loser)boards</a></li>
								<li><a href="/games/overall">Center Stats</a></li>
								<li><a href="/scorecards/allcenter">All-Center Teams</a></li>
								<li><a href="/penalties/index">Penalties</a></li>
								<li><a href="/pages/aboutSM5">About SM5</a></li>
								<?php if(AuthComponent::user('role') === 'admin'): ?>
									<li><a href="/uploads">Upload PDFs</li>
								<?php endif; ?>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<?php if (AuthComponent::user('id')): ?>
									<p class="navbar-text"><?= AuthComponent::user('username') ?></p><a class="btn btn-info navbar-btn" href="/users/logout" role="button">Logout</a>
								<?php else: ?>
									<a class="btn btn-info navbar-btn" href="/users/login" role="button">Login</a>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</nav>
				<ul class="breadcrumb">
					<li><?= $this->Html->link('Home', array('controller' => 'scorecards', 'action' => 'index')); ?></li>
					<?php
						if($this->Session->check('state.gametype')) {
							if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'social') {
								echo "<li>".$this->Html->link((($this->Session->read('state.gametype') == 'all') ? 'All Games' : 'Social Games'), array('controller' => 'scorecards', 'action' => 'index'))."</li>";
								if($this->Session->check('state.centerID')) {
									if($this->Session->read('state.centerID') > 0){
										echo "<li>".$this->Html->link($selected_center['Center']['name'], array('controller' => 'scorecards', 'action' => 'pickCenter'))."</li>";
									} else {
										echo "<li>".$this->Html->link('All Centers', array('controller' => 'scorecards', 'action' => 'pickCenter'))."</li>";
									}
								}
							} elseif($this->Session->read('state.gametype') == 'league') {
								echo "<li>".$this->Html->link('League', array('controller' => 'scorecards', 'action' => 'pickLeague'))."</li>";
								if($this->Session->check('state.leagueID'))
									echo "<li>".$this->Html->link($selected_league['Center']['name']." - ".$selected_league['League']['name'], array('controller' => 'leagues', 'action' => 'standings'))."</li>";	
							}
								
						}
					?>
				</ul>
			</div>
			<hr>
			<div id="content">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
			<div id="footer">
			</div>
		</div>
		<?php
			//debug($this->Session->read('state'));
			echo $this->element('sql_dump');
			echo $this->Js->writeBuffer();
		?>
	</div>
</body>
</html>
