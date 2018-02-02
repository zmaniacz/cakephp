<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script defer src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
	<script defer type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.js"></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/highcharts.js'></script>
	<script defer src='https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.4/highcharts-more.js'></script>

	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.css"/>
	<?php
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
