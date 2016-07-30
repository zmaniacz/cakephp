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
		echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js');
		echo $this->Html->script('//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js');
		echo $this->Html->script('//cdn.datatables.net/plug-ins/1.10.10/integration/bootstrap/3/dataTables.bootstrap.js');
		echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootswatch/3.3.5/yeti/bootstrap.min.css', array('title' => 'main'));
		echo $this->Html->css('//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css');
		echo $this->Html->css('laserforce');
	?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<script>
	$(document).ready(function() {
		var theme = localStorage.theme;
		if (theme) {
			set_theme(theme);
		}
        
        $.ajax({ 
            url:'https://api.twitch.tv/kraken/streams/beanz2d2',
            dataType:'jsonp',
            success:function(channel) { 
                if(channel["stream"] == null) {
                    $("#twitch_status").append(" <span class='label label-default'>Offline</span>");
                } else {
                    $("#twitch_status").append(" <span class='label label-danger'>LIVE</span>");
                }
            },
            error:function() {
                //request failed
            }
        });
	});
</script>
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
								<li><?php
									if($this->Session->read('state.gametype') == 'league') {
										echo $this->Html->link('Standings', array('controller' => 'leagues', 'action' => 'standings'));
									} else {
										echo $this->Html->link('Nightly Stats', array('controller' => 'scorecards', 'action' => 'nightly'));
									}
								?></li>
								<li><?= $this->Html->link('Top Players', array('controller' => 'scorecards', 'action' => 'overall')); ?></li>
								<li><?= $this->Html->link('Game List', array('controller' => 'games', 'action' => 'index')); ?></li>
								<li><?= $this->Html->link('Leader(Loser)boards', array('controller' => 'scorecards', 'action' => 'leaderboards')); ?></li>
								<li><?= $this->Html->link('Center Stats', array('controller' => 'games', 'action' => 'overall')); ?></li>
								<li><?php
									if($this->Session->read('state.gametype') == 'league') {
										echo $this->Html->link('All Star Rankings', array('controller' => 'scorecards', 'action' => 'allstar'));
									} else {
										echo $this->Html->link('All-Center Teams', array('controller' => 'scorecards', 'action' => 'allcenter'));
									}
								?></li>
								<li><?= $this->Html->link('Penalties', array('controller' => 'penalties', 'action' => 'index')); ?></li>
								<li><?= $this->Html->link('About SM5', array('controller' => 'pages', 'action' => 'aboutSM5')); ?></li>
                                <li><?= $this->Html->link('Twitch', array('controller' => 'pages', 'action' => 'twitch'), array('id' => 'twitch_status')); ?></li>
                                <li><?= $this->Html->link('Nationals 2016', array('controller' => 'leagues', 'action' => 'standings', '?' => array('gametype' => 'league', 'leagueID' => 10, 'centerID' => 8))); ?></li>
								<?php if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin') && AuthComponent::user('center') == $this->Session->read('state.centerID')): ?>
									<li><?= $this->Html->link('Upload PDFs', array('controller' => 'uploads', 'action' => 'index')); ?></li>
								<?php endif; ?>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li>
								<?php if (AuthComponent::user('id')): ?>
									<a class="btn btn-info btn-sm" href="/users/logout" role="button"><?= AuthComponent::user('username') ?> Logout</a>
								<?php else: ?>
									<a class="btn btn-info btn-sm" href="/users/login" role="button">Login</a>
								<?php endif; ?>
								</li>
								<li class="dropdown" id="theme-dropdown">
									<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">Theme <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="#" class="change-style-menu-item" rel="slate">Dark</a></li>
										<li><a href="#" class="change-style-menu-item" rel="yeti">Light</a></li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</nav>
				<ul class="breadcrumb">
					<li class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
						<?=	Inflector::camelize(($this->Session->read('state.gametype') == 'league') ? 'competitive' : $this->Session->read('state.gametype')); ?> 
						 <span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li><?= $this->Html->link('All', array(
														'controller' => $this->request->params['controller'], 
														'action' => $this->request->params['action'],
														implode(",", $this->request->pass),
														'?' => array(
															'gametype' => 'all',
															'centerID' => 0,
															'leagueID' => 0
														)
								)); ?>
							</li>
							<li><?= $this->Html->link('Social', array(
														'controller' => $this->request->params['controller'], 
														'action' => $this->request->params['action'],
														implode(",", $this->request->pass),
														'?' => array(
															'gametype' => 'social',
															'centerID' => $this->Session->read('state.centerID'),
															'leagueID' => 0
														)
								)); ?>
							</li>
							<li><?= $this->Html->link('Competitive', array(
														'controller' => $this->request->params['controller'], 
														'action' => $this->request->params['action'],
														implode(",", $this->request->pass),
														'?' => array(
															'gametype' => 'league',
															'centerID' => $this->Session->read('state.centerID'),
															'leagueID' => $this->Session->read('state.leagueID')
														)
								)); ?>
							</li>
						</ul>
					</li>
					<li class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown">
							<?php
								if($this->Session->read('state.leagueID') > 0) {
									echo $leagues[$this->Session->read('state.leagueID')];
								} elseif($this->Session->read('state.centerID') > 0) {
									echo $centers[$this->Session->read('state.centerID')];
								} else {
									echo 'All Games';
								}
							?>
						 <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><?= $this->Html->link('All Games', array(
									'controller' => $this->request->params['controller'], 
									'action' => $this->request->params['action'],
									implode(",", $this->request->pass),
									'?' => array(
										'gametype' => $this->Session->read('state.gametype'),
										'centerID' => 0,
										'leagueID' => 0
									)
								));
								?>
							</li>
							<li class="divider"></li>
							<?php
								if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'social') {
									echo "<li class=\"dropdown-header\">Centers</li>";
									foreach($centers as $key => $value) {
										echo "<li>".$this->Html->link($value, array(
											'controller' => $this->request->params['controller'], 
											'action' => $this->request->params['action'],
											implode(",", $this->request->pass),
											'?' => array(
												'gametype' => $this->Session->read('state.gametype'),
												'centerID' => $key,
												'leagueID' => 0
											)
										));
									}
								}
								if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'league') {
									echo "<li class=\"dropdown-header\">Competitions</li>";
									foreach($league_details as $league) {
										echo "<li>".$this->Html->link($league['League']['name'], array(
											'controller' => $this->request->params['controller'], 
											'action' => $this->request->params['action'],
											implode(",", $this->request->pass),
											'?' => array(
												'gametype' => 'league',
												'centerID' => $league['League']['center_id'],
												'leagueID' => $league['League']['id']
											)
										));
									}
								}
							?>
						</ul>
					</li>
				</ul>
			</div>
			<hr>
			<div id="content">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
				<div class="modal fade" id="mvpModal" tabindex="-1">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="mvpModalLabel">MVP Details</h4>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<script>
					$('#mvpModal').on('show.bs.modal', function (event) {
						var button = $(event.relatedTarget);
						$(this).find(".modal-body").text("Loading...");
						$(this).find(".modal-body").load(button.attr("target"));
					});
					
					jQuery(function($) {
					    $('body').on('click', '.change-style-menu-item', function() {
					      var theme_name = $(this).attr('rel');
					      var theme = "//netdna.bootstrapcdn.com/bootswatch/3.3.5/" + theme_name + "/bootstrap.min.css";
					      set_theme(theme);
					    });
					});
					
					function set_theme(theme) {
						$('link[title="main"]').attr('href', theme);
						localStorage.theme = theme;
					}
				</script>
			</div>
			<div id="footer">
			</div>
		</div>
	</div>
</body>
</html>
