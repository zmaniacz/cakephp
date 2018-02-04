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
	<meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
	<script defer src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/dataTables.bootstrap.min.js'></script>
	<script defer src='https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js'></script>
	<script defer src='http://code.highcharts.com/stock/highstock.js'></script>
	<script defer src='http://code.highcharts.com/stock/highcharts-more.js'></script>
	<script defer src='https://code.highcharts.com/stock/indicators/indicators.js'></script>
	<?php
		echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/paper/bootstrap.min.css');
		echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/dataTables.bootstrap.min.css');
		echo $this->Html->css('https://cdn.datatables.net/responsive/2.2.1/css/responsive.dataTables.min.css');
		echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css');
		echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.css');
		echo $this->Html->css('laserforce');
	?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<body>
	<div class="container">
		<div id="container">
			<div id="header">
				<nav class="navbar navbar-inverse navbar-fixed-top">
					<div class="container-fluid">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<?= $this->Html->image('/img/LF-logo1-shadow-small.png', array(
								'alt' => 'Lfstats Home',
								'url' => array('controller' => 'events', 'action' => 'index', '?' => array('gametype' => 'all', 'eventID' => 0, 'centerID' => 0, 'selectedEvent' => 0))
								)
							); ?>
    					</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<?php if($this->Session->read('state.selectedEvent') > 0): ?>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Event Stats<span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li>
											<?= $this->Html->link('Summary - '.$selected_event['Event']['name'], array(
												'controller' => 'events',
												'action' => 'view',
												$selected_event['Event']['id'],
												'?' => array(
													'gametype' => $selected_event['Event']['type'],
													'eventID' => $selected_event['Event']['id'],
													'centerID' => $selected_event['Event']['center_id'],
													'selectedEvent' => $selected_event['Event']['id']
												)
											)); ?>
											</li>
											<li><?= $this->Html->link('Games Played', array('controller' => 'games', 'action' => 'index')); ?></li>
											<?php if($selected_event['Event']['type'] != 'social'): ?>
											<li><?= $this->Html->link('Player Stats', array('controller' => 'events', 'action' => 'playerStats', $selected_event['Event']['id'])); ?></li>
											<li><?= "todo";//$this->Html->link('All Star Rankings', array('controller' => 'scorecards', 'action' => 'allstar')); ?></li>
											<li><?= "todo";//$this->Html->link('Leader(Loser)boards', array('controller' => 'scorecards', 'action' => 'leaderboards')); ?></li>
											<li><?= "todo";//$this->Html->link('Aggregate Stats', array('controller' => 'games', 'action' => 'overall')); ?></li>
											<li><?= "todo";//$this->Html->link('Penalties', array('controller' => 'penalties', 'action' => 'index')); ?></li>
											<?php endif; ?>
											<li role="separator" class="divider"></li>
											<li><?= $this->Html->link('Event List', array('controller' => 'events', 'action' => 'index')); ?></li>
										</ul>
									</li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Overall Stats<span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><?= $this->Html->link('Top Players', array('controller' => 'players', 'action' => 'index', '?' => array('gametype' => $this->Session->read('state.gametype'), 'eventID' => 0, 'centerID' => $this->Session->read('state.centerID')))); ?></li>
											<li><?= $this->Html->link('All-Center Teams', array('controller' => 'scorecards', 'action' => 'allcenter', '?' => array('gametype' => $this->Session->read('state.gametype'), 'eventID' => 0, 'centerID' => $this->Session->read('state.centerID')))); ?></li>
											<li><?= $this->Html->link('Games Played', array('controller' => 'games', 'action' => 'index', '?' => array('gametype' => $this->Session->read('state.gametype'), 'eventID' => 0, 'centerID' => $this->Session->read('state.centerID')))); ?></li>
											<li><?= $this->Html->link('Leader(Loser)boards', array('controller' => 'scorecards', 'action' => 'leaderboards', '?' => array('gametype' => $this->Session->read('state.gametype'), 'eventID' => 0, 'centerID' => $this->Session->read('state.centerID')))); ?></li>
											<li><?= $this->Html->link('Center Stats', array('controller' => 'games', 'action' => 'overall', '?' => array('gametype' => $this->Session->read('state.gametype'), 'eventID' => 0, 'centerID' => $this->Session->read('state.centerID')))); ?></li>
										</ul>
									</li>
								<?php endif; ?>
								<li><?= $this->Html->link('About SM5', array('controller' => 'pages', 'action' => 'aboutSM5')); ?></li>
                                <li><?= $this->Html->link('Twitch', array('controller' => 'pages', 'action' => 'twitch'), array('id' => 'twitch_status')); ?></li>
                                <li><?= $this->Html->link('WCT 2018', array('controller' => 'leagues', 'action' => 'standings', '?' => array('gametype' => 'league', 'leagueID' => 18, 'centerID' => 10))); ?></li>
								<?php if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID'))): ?>
									<li><?= $this->Html->link('Upload PDFs', array('controller' => 'uploads', 'action' => 'index')); ?></li>
								<?php endif; ?>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li>
								<?php if (AuthComponent::user('id')): ?>
									<a class="btn btn-sm" href="/users/logout" role="button"><?= AuthComponent::user('username') ?> Logout</a>
								<?php else: ?>
									<a class="btn btn-sm" href="/users/login" role="button">Login</a>
								<?php endif; ?>
								</li>
							</ul>
						</div>
					</div>
				</nav>
			</div>
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
				<div class="modal fade" id="penaltyModal" tabindex="-1">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="penaltyModalLabel">Penalty Details</h4>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="teamPenaltyModal" tabindex="-1">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="penaltyModalLabel">Team Penalty Details</h4>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="hitModal" tabindex="-1">
					<div class="modal-dialog modal-md">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="hitModalLabel">Hit Details</h4>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="matchModal" tabindex="-1">
					<div class="modal-dialog modal-md">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="matchModalLabel">Match Details</h4>
							</div>
							<div class="modal-body">
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>

			</div>
			<div id="footer">
				<h6 class="text-center">
					<small>
						Players have shot each other <?=$scorecard_stats[0]['total_hits']; ?> times in <?=$game_stats[0]['total_games']; ?> games with <?=$scorecard_stats[0]['total_scorecards']; ?> individual scorecards.
					</small>
				</h6>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			//Set a global error handler for datatables
			$.fn.dataTable.ext.errMode = function(settings, techNote, message) {
				toastr.options = {
					"closeButton": true,
					"debug": false,
					"newestOnTop": true,
					"progressBar": false,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "300",
					"hideDuration": "1000",
					"timeOut": "0",
					"extendedTimeOut": "0",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				}
				toastr.error(message);
			}

			$(document).ready(function() {
				Highcharts.setOptions({
					chart: {
						style: {
							fontFamily: '"Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif'
						}
					}
				});
			});
			
			$.ajax({ 
				url:'https://api.twitch.tv/kraken/streams/laserforcetournaments?client_id=5shofd1neum3sel2bzbaskcvyohfgz',
				dataType:'jsonp',
			}).done(function(channel) { 
				if(channel["stream"] == null) {
					$("#twitch_status").append(" <span class='label label-default'>Offline</span>");
				} else {
					$("#twitch_status").append(" <span class='label label-danger'>LIVE</span>");
				}
			});

			//global handlers for the various modals
			$('#penaltyModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				$(this).find(".modal-body").text("Loading...");
				$(this).find(".modal-body").load(button.attr("target"));
			});
			$('#teamPenaltyModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				$(this).find(".modal-body").text("Loading...");
				$(this).find(".modal-body").load(button.attr("target"));
			});
			$('#hitModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				$(this).find(".modal-body").text("Loading...");
				$(this).find(".modal-body").load(button.attr("target"));
			});
			$('#matchModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				$(this).find(".modal-body").text("Loading...");
				$(this).find(".modal-body").load(button.attr("target"));
			});
			$('#mvpModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				$(this).find(".modal-body").text("Loading...");
				$(this).find(".modal-body").load(button.attr("target"));
			});
		});
	</script>
</body>
</html>
