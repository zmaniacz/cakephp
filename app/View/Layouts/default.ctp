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
<html>
<head>
	<?php
		echo $this->Html->charset();
		echo $this->Html->script('//code.jquery.com/jquery-2.1.3.min.js');
		echo $this->Html->script('//code.jquery.com/ui/1.11.2/jquery-ui.min.js');
		echo $this->Html->script('//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js');
		echo $this->Html->script('//cdn.datatables.net/plug-ins/3cfcc339e89/integration/jqueryui/dataTables.jqueryui.js');
		echo $this->Html->meta('icon');
		echo $this->Html->css(array('laserforce','cake.generic','//code.jquery.com/ui/1.11.2/themes/ui-lightness/jquery-ui.css'));
		echo $this->Html->css('//cdn.datatables.net/plug-ins/3cfcc339e89/integration/jqueryui/dataTables.jqueryui.css');
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#topmenu").menu();

			if($('#game_filterSelectFilter').val() == 'league' || $('#game_filterSelectFilter').val() == 'tournament') {
				populateLeagues($('#game_filterSelectFilter').val());
				$.getJSON("/scorecards/ajax_getFilter.json", function (data) {
					$("#game_filter_detailsSelect").val(data.filter.value);
				});
			}
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
			<h1>Laserforce - 
			<?php
				echo $this->Form->create('center_filter', array('model' => false, 'url' => array('controller' => 'scorecards', 'action' => 'setFilter'), 'inputDefaults' => array('div' => false, 'label' => false), 'style' => 'display: inline;', 'id' => 'center_filterForm'));
				echo $this->Form->input('selectFilter', array('options' => $centers, 'selected' => $this->Session->read('center.Center.id'), 'style' => 'display: inline;'));
				echo $this->Form->end();
			?>
			</h1>
			<h1>Viewing: 
			<?php
				echo $this->Form->create('game_filter', array('model' => false, 'url' => array('controller' => 'scorecards', 'action' => 'setFilter'), 'inputDefaults' => array('div' => false, 'label' => false), 'style' => 'display: inline;', 'id' => 'game_filterForm'));
				echo $this->Form->input('selectFilter', array('options' => array('all' => 'All','social' => 'Social','league' => 'League','tournament' => 'Tournament'), 'selected' => $this->Session->read('filter.type'), 'style' => 'display: inline;'));
				echo $this->Form->input('select_detailsFilter', array('options' => array(0 => '-- --'), 'style' => 'display: none;', 'id' => 'game_filter_detailsSelect'));
				echo $this->Form->end();
			?>
			</h1>
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
						echo "<li>".$this->Html->link("Upload PDF", array('controller' => 'uploads'))."</li>";
					}
				?>
			</ul>
		</div>
		<div id="content">

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
</body>
</html>
