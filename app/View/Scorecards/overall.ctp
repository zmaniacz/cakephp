<script type="text/javascript">
	$(document).ready(function() {
		$('#overall_averages_table').DataTable( {
			"deferRender" : true,
			"order": [[1, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallAverages', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name", },
				{ "data" : "avg_avg_mvp" },
				{ "data" : "avg_avg_acc" },
				{ "data" : "total_games" },
				{ "data" : "commander_avg_mvp" },
				{ "data" : "commander_avg_acc" },
				{ "data" : "commander_games_played" },
				{ "data" : "heavy_avg_mvp" },
				{ "data" : "heavy_avg_acc" },
				{ "data" : "heavy_games_played" },
				{ "data" : "scout_avg_mvp" },
				{ "data" : "scout_avg_acc" },
				{ "data" : "scout_games_played" },
				{ "data" : "ammo_avg_mvp" },
				{ "data" : "ammo_avg_acc" },
				{ "data" : "ammo_games_played" },
				{ "data" : "medic_avg_mvp" },
				{ "data" : "medic_avg_acc" },
				{ "data" : "medic_games_played" },
			]
		} );
		
		$('#commander_overall_table').DataTable( {
			"deferRender" : true,
			"order": [[3, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallStats', 'commander', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name" },
				{ "data" : "games_played" },
				{ "data" : "avg_score" },
				{ "data" : "avg_mvp" },
				{ "data" : "avg_acc" },
				{ "data" : "nuke_ratio" },
				{ "data" : "hit_diff" },
				{ "data" : "avg_missiles" },
				{ "data" : "avg_medic_hits" }
			]
		});
		
		$('#heavy_overall_table').DataTable( {
			"deferRender" : true,
			"order": [[3, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallStats', 'heavy', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name" },
				{ "data" : "games_played" },
				{ "data" : "avg_score" },
				{ "data" : "avg_mvp" },
				{ "data" : "avg_acc" },
				{ "data" : "hit_diff" },
				{ "data" : "avg_missiles" },
				{ "data" : "avg_medic_hits" }
			]
		});
		
		$('#scout_overall_table').DataTable( {
			"deferRender" : true,
			"order": [[3, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallStats', 'scout', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name" },
				{ "data" : "games_played" },
				{ "data" : "avg_score" },
				{ "data" : "avg_mvp" },
				{ "data" : "avg_acc" },
				{ "data" : "hit_diff" },
				{ "data" : "avg_3hit" },
				{ "data" : "avg_medic_hits" }
			]
		});
		
		$('#ammo_overall_table').DataTable( {
			"deferRender" : true,
			"order": [[3, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallStats', 'ammo', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name" },
				{ "data" : "games_played" },
				{ "data" : "avg_score" },
				{ "data" : "avg_mvp" },
				{ "data" : "avg_acc" },
				{ "data" : "hit_diff" },
				{ "data" : "avg_ammo_boost" },
				{ "data" : "avg_resup" }
			]
		});
		
		$('#medic_overall_table').DataTable( {
			"deferRender" : true,
			"order": [[3, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallStats', 'medic', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name" },
				{ "data" : "games_played" },
				{ "data" : "avg_score" },
				{ "data" : "avg_mvp" },
				{ "data" : "avg_acc" },
				{ "data" : "hit_diff" },
				{ "data" : "avg_life_boost" },
				{ "data" : "avg_resup" },
				{ "data" : "avg_lives" },
				{ "data" : "elim_rate" }	
			]
		});
		
		$('#overall_medic_hits_table').DataTable( {
			"deferRender" : true,
			"order": [[2, "desc"]],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'getOverallMedicHits', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "name", },
				{ "data" : "total_medic_hits" },
				{ "data" : "medic_hits_per_game" },
				{ "data" : "games_played" },
				{ "data" : "non_resup_total_medic_hits" },
				{ "data" : "non_resup_medic_hits_per_game" },
				{ "data" : "non_resup_games_played" }
			]
		});
	} );
</script>
<?php if($this->Session->read('state.gametype') == 'league'): ?>
	<form class="form-inline">
		<div class="checkbox">
			<label for="rounds_cbox">Show Rounds/label>
			<input type="checkbox" id="rounds_cbox" <?= (($this->Session->read('state.show_rounds') == 'true') ? "checked" : "")?>>
			<label for="finals_cbox">Show Finals</label>
			<input type="checkbox" id="finals_cbox" <?= (($this->Session->read('state.show_finals') == 'true') ? "checked" : "")?>>
			<label for="sub_cbox">Show Subs</label>
			<input type="checkbox" id="sub_cbox" <?= (($this->Session->read('state.show_subs') == 'true') ? "checked" : "")?>>
		</div>
	</form>
<?php endif; ?>
<div id="overall" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#overall" data-target="#collapse_overall" role="tab" id="overall_heading">
		<h4 class="panel-title">
			Average Averages
		</h4>
	</div>
	<div id="collapse_overall" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="overall_averages_table">
					<thead>
						<tr>
							<th rowspan="2">Name</th>
							<th colspan="3">Overall</th>
							<th colspan="3">Commander</th>
							<th colspan="3">Heavy Weapons</th>
							<th colspan="3">Scout</th>
							<th colspan="3">Ammo Carrier</th>
							<th colspan="3">Medic</th>
						</tr>
						<tr>
							<th>MVP</th>
							<th>Accuracy</th>
							<th>Games Played</th>
							<th>MVP</th>
							<th>Accuracy</th>
							<th>Games Played</th>
							<th>MVP</th>
							<th>Accuracy</th>
							<th>Games Played</th>
							<th>MVP</th>
							<th>Accuracy</th>
							<th>Games Played</th>
							<th>MVP</th>
							<th>Accuracy</th>
							<th>Games Played</th>
							<th>MVP</th>
							<th>Accuracy</th>
							<th>Games Played</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="commander" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#commander" data-target="#collapse_commander" role="tab" id="commander_heading">
		<h4 class="panel-title">
			Commander
		</h4>
	</div>
	<div id="collapse_commander" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="commander_overall_table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Games Played</th>
							<th>Average Score</th>
							<th>Average MVP Points</th>
							<th class="accuracy">Average Accuracy</th>
							<th>Nuke Success Ratio</th>
							<th>Hit Differential</th>
							<th>Average Missiles</th>
							<th>Average Medic Hits</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="heavy" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#heavy" data-target="#collapse_heavy" role="tab" id="heavy_heading">
		<h4 class="panel-title">
			Heavy Weapons
		</h4>
	</div>
	<div id="collapse_heavy" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="heavy_overall_table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Games Played</th>
							<th>Average Score</th>
							<th>Average MVP Points</th>
							<th class="accuracy">Average Accuracy</th>
							<th>Hit Differential</th>
							<th>Average Missiles</th>
							<th>Average Medic Hits</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="scout" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#scout" data-target="#collapse_scout" role="tab" id="scout_heading">
		<h4 class="panel-title">
			Scout
		</h4>
	</div>
	<div id="collapse_scout" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="scout_overall_table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Games Played</th>
							<th>Average Score</th>
							<th>Average MVP Points</th>
							<th class="accuracy">Average Accuracy</th>
							<th>Hit Differential</th>
							<th>Average 3Hit Hits</th>
							<th>Average Medic Hits</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="ammo" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#ammo" data-target="#collapse_ammo" role="tab" id="ammo_heading">
		<h4 class="panel-title">
			Ammo Carrier
		</h4>
	</div>
	<div id="collapse_ammo" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="ammo_overall_table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Games Played</th>
							<th>Average Score</th>
							<th>Average MVP Points</th>
							<th class="accuracy">Average Accuracy</th>
							<th>Hit Differential</th>
							<th>Average Boosts</th>
							<th>Average Resupplies</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="medic" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#medic" data-target="#collapse_medic" role="tab" id="medic_heading">
		<h4 class="panel-title">
			Medic
		</h4>
	</div>
	<div id="collapse_medic" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="medic_overall_table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Games Played</th>
							<th>Average Score</th>
							<th>Average MVP Points</th>
							<th class="accuracy">Average Accuracy</th>
							<th>Hit Differential</th>
							<th>Average Boosts</th>
							<th>Average Resupplies</th>
							<th>Average Lives Left</th>
							<th class="team_elim">Team Elimination Rate</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="medic_hits" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#medic_hits" data-target="#collapse_medic_hits" role="tab" id="medic_hits_heading">
		<h4 class="panel-title">
			Medic Hits
		</h4>
	</div>
	<div id="collapse_medic_hits" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="overall_medic_hits_table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Total Medic Hits (All)</th>
							<th>Average Medic Hits (All)</th>
							<th>Games Played (All)</th>
							<th>Total Medic Hits (Non-Resupply)</th>
							<th>Average Medic Hits (Non-Resupply)</th>
							<th>Games Played (Non-Resupply)</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$('#sub_cbox').change(function() {
		if($('#sub_cbox').is(':checked')) {
			window.location = "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'filterSub', 'true'))); ?>";
		} else {
			window.location = "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'filterSub', 'false'))); ?>";
		}
	});
	$('#finals_cbox').change(function() {
		if($('#finals_cbox').is(':checked')) {
			window.location = "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'filterFinals', 'true'))); ?>";
		} else {
			window.location = "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'filterFinals', 'false'))); ?>";
		}
	});
	$('#rounds_cbox').change(function() {
		if($('#rounds_cbox').is(':checked')) {
			window.location = "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'filterRounds', 'true'))); ?>";
		} else {
			window.location = "<?= html_entity_decode($this->Html->url(array('controller' => 'scorecards', 'action' => 'filterRounds', 'false'))); ?>";
		}
	});
</script>