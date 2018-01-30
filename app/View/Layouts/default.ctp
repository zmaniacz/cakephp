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
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script defer src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/jquery.dataTables.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/js/dataTables.bootstrap4.min.js'></script>
	<script defer src='https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/highcharts.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/highcharts-more.js'></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0/cerulean/bootstrap.min.css">
	<?php
		echo $this->Html->css('https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/dataTables.bootstrap4.min.css');
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
	<script type="text/javascript">
		$(document).ready(function() {
			Highcharts.setOptions({
				chart: {
					style: {
						fontFamily: '"Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif'
					}
				}
			});
		});
	</script>
	<div class="container">
		<?= $this->element('navbar'); ?>
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php if(empty($landing))
				echo $this->element('breadcrumb'); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer">
			<?= $this->element('modals'); ?>
			<?= $this->element('stat_footer'); ?>
		</div>
	</div>
	<script>
		$(document).ready(function() {
			$.ajax({ 
				url:'https://api.twitch.tv/kraken/streams/laserforcetournaments?client_id=5shofd1neum3sel2bzbaskcvyohfgz',
				dataType:'jsonp',
			}).done(function(channel) { 
				if(channel["stream"] == null) {
					$("#twitch_status").append('<i class="fab fa-twitch"></i>');
				} else {
					$("#twitch_status").append('<span class="text-danger"><i class="fab fa-twitch"></i></span>');
				}
			});
		});
	</script>
</body>
</html>
