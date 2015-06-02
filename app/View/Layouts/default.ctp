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
								<li><a href="/scorecards/nightly">Nightly Stats</a></li>
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
				<form class="form-inline" action="/scorecards/setFilter" id="center_filterForm" method="post" accept-charset="utf-8">
					<div style="display:none;">
						<input type="hidden" name="_method" value="POST"/>
					</div>
					<div class="form-group">
						<label for="center_filterSelectFilter">Center</label>
						<select class="form-control" name="data[center_filter][selectFilter]" id="center_filterSelectFilter">
							<?php foreach($centers as $center): ?>
								<option value="<?= $center['Center']['id'] ?>"<?= ($center['Center']['id'] == $this->Session->read('center.Center.id')) ? " selected" : ""; ?>><?= $center['Center']['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</form>
				<form class="form-inline" action="/scorecards/setFilter" id="game_filterForm" method="post" accept-charset="utf-8">
					<div style="display:none;">
						<input type="hidden" name="_method" value="POST"/>
					</div>
					<label for="game_filterSelectFilter">Viewing</label>
					<select class="form-control" name="data[game_filter][selectFilter]" id="game_filterSelectFilter">
						<option value="all"<?= ('all' == $this->Session->read('filter.type')) ? " selected" : ""; ?>>All</option>
						<option value="social"<?= ('social' == $this->Session->read('filter.type')) ? " selected" : ""; ?>>Social</option>
						<option value="league"<?= ('league' == $this->Session->read('filter.type')) ? " selected" : ""; ?>>League</option>
						<option value="tournament"<?= ('tournament' == $this->Session->read('filter.type')) ? " selected" : ""; ?>>Tournament</option>
					</select>
					<select class="form-control" name="data[game_filter][select_detailsFilter]" id="game_filter_detailsSelect" style="display:none;">
						<option value="0">-- --</option>
					</select>
				</form>
			</div>
			<hr>
			<div id="content">
				<?php //print_r($this->Session->read()); ?>
				<?php echo $this->Session->flash(); ?>
	
				<?php echo $this->fetch('content'); ?>
			</div>
			<div id="footer">
			</div>
		</div>
		<script>
		$('#game_filterSelectFilter').change(function() {
			if($('#game_filterSelectFilter').val() == 'social' || $('#game_filterSelectFilter').val() == 'all') {
				$('#game_filter_detailsSelect').hide();
				$('#game_filterForm').submit();
			} else {
				populateLeagues($('#game_filterSelectFilter').val());
			}
		});
	
		$('#center_filterSelectFilter').change(function() {
			$('#center_filterForm').submit();
		});
	
		$('#game_filter_detailsSelect').change(function(){
			if($('#game_filter_detailsSelect').val() >= 0) {
				$('#game_filterForm').submit();
			}
		});
	
		function populateLeagues(type) {
			$('#game_filter_detailsSelect').find('option').remove().end();
			$('#game_filter_detailsSelect').append('<option value="-1">-- Choose '+type+' --</option>').val(-1);
			$('#game_filter_detailsSelect').append('<option value="0">All</option>').val(0);
			$.getJSON("/leagues/ajax_getLeagues/"+type+".json", function( data ) {
				$.each(data['leagues'], function() {
					$("#game_filter_detailsSelect").append($("<option />").val(this.League.id).text(this.League.name));
				})
				$('#game_filter_detailsSelect').show();
			});
			$('#game_filter_detailsSelect').val(-1);
		}
		</script>
		<?php
			//echo $this->element('sql_dump');
			echo $this->Js->writeBuffer();
		?>
	</div>
</body>
</html>
