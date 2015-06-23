<script type="text/javascript">
	$(document).ready(function() {
		$('#team_standings').DataTable( {
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('controller' => 'leagues', 'action' => 'ajax_getTeams', 'ext' => 'json')); ?>",
				"dataSrc" : "teams"
			},
			"columns" : [
				{ "data" : "Team.name" },
				{ "data" : "Team.points" }

			]
		});
	});
</script>
<?php if(AuthComponent::user('role') === 'admin'): ?>
	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link('New Team', array('controller' => 'leagues', 'action' => 'addTeam')); ?></li>
		</ul>
	</div>
<?php endif; ?>
<h3>Team Standings</h3>
<table class="table table-striped table-bordered table-hover table-condensed" id="team_standings">
	<thead>
		<th>Team</th>
		<th>Points</th>
	</thead>
</table>