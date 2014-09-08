<script type="text/javascript">
	$(document).ready(function() {
		$('#team_standings').DataTable( {
			"autoWidth": false,
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'ajax_getTeams', 'ext' => 'json')); ?>",
				"dataSrc" : "teams"
			},
			"columns" : [
				{ "data" : "Team.name" },
				{ "data" : "Team.points" }

			]
		});

		$('#scorecards').DataTable( {
			"scrollX" : true,
			"deferRender" : true,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'ajax_getScorecards', 'ext' => 'json')); ?>",
				"dataSrc" : "scorecards"
			},
			"columns" : [
				{
					"data" : "Scorecard.player_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/<?php echo $this->params->center; ?>/players/view/'+row.Scorecard.player_id+'">'+data+'</a>';
					}
				},
				{ "data" : "Game.game_name", "render" : function(data, type,row, meta) {return '<a href="/<?php echo $this->params->center; ?>/games/view/'+row.Game.id+'">'+data+'</a>'}},
				{ "data" : "Scorecard.position" },
				{ "data" : "Scorecard.score" },
				{ "data" : "Scorecard.mvp_points" },
				{ "data" : "Scorecard.accuracy", "render" : function(data, type, row, meta) {return parseFloat(data*100).toFixed(2)+'%';} },
				{ "data" : "Scorecard.shot_opponent", "render" : function(data, type, row, meta) {var diff = (data/row.Scorecard.times_zapped); return diff.toFixed(2);} },
				{ "data" : "Scorecard.medic_hits" },
				{ "data" : "Scorecard.shot_team" },
			],
			"order": [[ 4, "desc" ]]
		});

		$('#medic_hits').DataTable( {
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'ajax_getMedicHits', 'ext' => 'json')); ?>",
				"dataSrc" : "medic_hits"
			},
			"columns" : [
				{
					"data" : "Scorecard.player_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/<?php echo $this->params->center; ?>/players/view/'+row.Scorecard.player_id+'">'+data+'</a>';
					}
				},
				{ "data" : "0.total_medic_hits" },
				{ "data" : "0.medic_hits_per_game" }
			],
			"order": [[ 1, "desc" ]]
		});
	} );
</script>
<?php if(AuthComponent::user('role') === 'admin'): ?>
	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link('New Team', array('controller' => 'leagues/'.$league['League']['id'], 'action' => 'addTeam')); ?></li>
		</ul>
	</div>
<?php endif; ?>
<h3>Team Standings</h3>
<div style="width: 700px;">
	<table class="display" id="team_standings">
		<thead>
			<th>Team</th>
			<th>Points</th>
			<th>Wins</th>
			<th>Losses</th>
		</thead>
	</table>
</div>
<div id="accordion">
	<h3>Scores</h3>
	<div>
		<table class="display" id="scorecards">
			<thead>
				<tr>
					<th>Name</th>
					<th>Game</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP</th>
					<th>Accuracy</th>
					<th>Hit Diff</th>
					<th>Medic Hits</th>
					<th>Shot Team</th>
				</tr>
			</thead>
		</table>
	</div>
	<h3>Medic Hits</h3>
	<div>
		<table class="display" id="medic_hits">
			<thead>
				<tr>
					<th>Name</th>
					<th>Total</th>
					<th>Average</th>
				</tr>
			</thead>
		</table>
	</div>
</div>