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
		echo $this->Html->css('laserforce');
	?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/jq-3.2.1/dt-1.10.16/fc-3.2.3/fh-3.1.3/r-2.2.0/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootswatch/3.3.7/yeti/bootstrap.min.css"/>
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/jq-3.2.1/dt-1.10.16/fc-3.2.3/fh-3.1.3/r-2.2.0/datatables.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		<?php echo $title_for_layout; ?>
	</title>
</head>
<script>
	$(document).ready(function() {
        $.ajax({ 
            url:'https://api.twitch.tv/kraken/streams/laserforcetournaments?client_id=5shofd1neum3sel2bzbaskcvyohfgz',
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
							<?= $this->Html->image('/img/LF-logo1-shadow-small.png', array(
								'alt' => 'Lfstats Home',
								'url' => array('controller' => 'events', 'action' => 'landing', '?' => array('gametype' => 'all', 'leagueID' => 0, 'centerID' => 0))
								)
							); ?>
    					</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<?php if(isset($selected_event)): ?>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Event Stats<span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><?= $this->Html->link('Summary - '.$selected_event['Event']['name'], array('controller' => 'events', 'action' => 'view', $selected_event['Event']['id'])); ?></li>
											<li><?= $this->Html->link('Games Played', array('controller' => 'games', 'action' => 'index')); ?></li>
											<?php if($selected_event['Event']['type'] != 'social'): ?>
											<li><?= $this->Html->link('Player Stats', array('controller' => 'events', 'action' => 'playerStats', $selected_event['Event']['id'])); ?></li>
											<li><?= $this->Html->link('All Star Rankings', array('controller' => 'scorecards', 'action' => 'allstar')); ?></li>
											<li><?= $this->Html->link('Leader(Loser)boards', array('controller' => 'scorecards', 'action' => 'leaderboards')); ?></li>
											<li><?= $this->Html->link('Aggregate Stats', array('controller' => 'games', 'action' => 'overall')); ?></li>
											<li><?= $this->Html->link('Penalties', array('controller' => 'penalties', 'action' => 'index')); ?></li>
											<?php endif; ?>
											<li role="separator" class="divider"></li>
											<li><?= $this->Html->link('Event List', array('controller' => 'events', 'action' => 'index')); ?></li>
										</ul>
									</li>
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Overall Stats<span class="caret"></span></a>
										<ul class="dropdown-menu">
											<li><?= $this->Html->link('Player Details', array('controller' => 'scorecards', 'action' => 'overall')); ?></li>
											<li><?= $this->Html->link('All-Center Teams', array('controller' => 'scorecards', 'action' => 'allcenter')); ?></li>
											<li><?= $this->Html->link('Games Played', array('controller' => 'games', 'action' => 'index')); ?></li>
											<li><?= $this->Html->link('Leader(Loser)boards', array('controller' => 'scorecards', 'action' => 'leaderboards')); ?></li>
											<li><?= $this->Html->link('Aggregate Stats', array('controller' => 'games', 'action' => 'overall')); ?></li>
										</ul>
									</li>
									<li><?= $this->Html->link('Player Stats', array('controller' => 'players', 'action' => 'index')); ?></li>
								<?php endif; ?>
								<li><?= $this->Html->link('About SM5', array('controller' => 'pages', 'action' => 'aboutSM5')); ?></li>
                                <li><?= $this->Html->link('Twitch', array('controller' => 'pages', 'action' => 'twitch'), array('id' => 'twitch_status')); ?></li>
                                <li><?= $this->Html->link('Internats 2017', array('controller' => 'leagues', 'action' => 'standings', '?' => array('gametype' => 'league', 'leagueID' => 16, 'centerID' => 14))); ?></li>
								<?php if(AuthComponent::user('role') === 'admin' || AuthComponent::user('role') === 'center_admin'): ?>
									<li><?= $this->Html->link('Upload PDFs', array('controller' => 'uploads', 'action' => 'index')); ?></li>
								<?php endif; ?>
							</ul>
							<ul class="nav navbar-nav navbar-right">
								<li>
								<?php if (AuthComponent::user('id')): ?>
									<a href="/users/logout" role="button"><?= AuthComponent::user('username') ?> Logout</a>
								<?php else: ?>
									<a href="/users/login" role="button">Login</a>
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
				<script>
					$('#penaltyModal').on('show.bs.modal', function (event) {
						var button = $(event.relatedTarget);
						$(this).find(".modal-body").text("Loading...");
						$(this).find(".modal-body").load(button.attr("target"));
					});
					$('#hitModal').on('show.bs.modal', function (event) {
						var button = $(event.relatedTarget);
						$(this).find(".modal-body").text("Loading...");
						$(this).find(".modal-body").load(button.attr("target"));
					});
					$('#mvpModal').on('show.bs.modal', function (event) {
						var button = $(event.relatedTarget);
						$(this).find(".modal-body").text("Loading...");
						$(this).find(".modal-body").load(button.attr("target"));
					});
				</script>
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
</body>
</html>
