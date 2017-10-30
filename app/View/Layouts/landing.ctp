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
		echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
		echo $this->Html->script('//cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js');
		echo $this->Html->script('//cdn.datatables.net/plug-ins/1.10.10/integration/bootstrap/3/dataTables.bootstrap.js');
		echo $this->Html->css('bootstrap.min.css');
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
      						<a class="navbar-brand" href="/scorecards/landing"><img src="/img/LF-logo1-shadow-small.png"></a>
    					</div>
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav navbar-right">
								<li>
								<?php if (AuthComponent::user('id')): ?>
									<a class="btn btn-info btn-sm" href="/users/logout" role="button"><?= AuthComponent::user('username') ?> Logout</a>
								<?php else: ?>
									<a class="btn btn-info btn-sm" href="/users/login" role="button">Login</a>
								<?php endif; ?>
								</li>
							</ul>
						</div>
					</div>
				</nav>
			<div id="content">
				<div class="row"><div class="col-xs-4 col-xs-offset-4"><?= $this->Html->link('ECT 7', array('controller' => 'leagues', 'action' => 'standings', '?' => array('gametype' => 'league', 'leagueID' => 17, 'centerID' => 7)), array('class' => 'btn btn-block btn-primary')); ?></div></div>
				<br />
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
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
