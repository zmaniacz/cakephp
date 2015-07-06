<form class="form-inline" action="/scorecards/nightly" id="nightlyNightlyForm" method="post" accept-charset="utf-8">
	<div style="display:none;">
		<input type="hidden" name="_method" value="POST"/>
	</div>
	<div class="form-group">
		<label for="nightlySelectDate">Select Date</label>
		<select class="form-control" name="data[nightly][selectDate]" id="nightlySelectDate">
			<?php foreach($game_dates as $game_date): ?>
				<option value="<?= $game_date ?>"><?= $game_date ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</form>
</br>
<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('action' => 'nightlyGames', $current_date, 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "game_name", },
				{ "data" : "game_datetime" },
				{ "data" : "winner" },
				{ "data" : "loser" },
				{ "data" : "pdf" }
			]
		});

		$("#overall thead th input").on( 'keyup change', function () {
			overall
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		});

		var overall = $('#overall').DataTable( {
			"deferRender" : true,
			"orderCellsTop" : true,
			"dom": '<lr>t<ip>',
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('action' => 'nightlyScorecards', $current_date, 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "player_name" },
				{ "data" : "game_name" },
				{ "data" : "position" },
				{ "data" : "score" },
				{ "data" : "mvp_points" },
				{ "data" : "accuracy" },
				{ "data" : "hit_diff" },
				{ "data" : "medic_hits" },
				{ "data" : "shot_team" },
			],
			"order": [[ 4, "desc" ]]
		});

		$("#medic_hits thead tr th input").on( 'keyup change', function () {
			medicHitsTable
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		});

		var medicHitsTable = $('#medic_hits').DataTable( {
			"deferRender" : true,
			"orderCellsTop" : true,
			"dom": '<"H"lr>t<"F"ip>',
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('action' => 'nightlyMedicHits', $current_date, 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "player_name" },
				{ "data" : "total_medic_hits" },
				{ "data" : "medic_hits_per_game" },
				{ "data" : "games_played" },
				{ "data" : "non_resup_total_medic_hits" },
				{ "data" : "non_resup_medic_hits_per_game" },
				{ "data" : "non_resup_games_played" }
			],
			"order": [[ 1, "desc" ]]
		});
	} );
</script>
<div id="top_accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#top_accordion" data-target="#collapse_game_list" role="tab" id="game_list_heading">
		<h4 class="panel-title">
			Games Played
		</h4>
	</div>
	<div id="collapse_game_list" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed" id="game_list">
					<thead>
						<th>Game</th>
						<th>Time</th>
						<th>Winner Score</th>
						<th>Loser Score</th>
						<th>Scorecard PDF</th>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="panel-group" id="accordion" role="tablist">
	<div class="panel panel-info">
		<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_overall" role="tab" id="overall_heading">
			<h4 class="panel-title">
				Overall
			</h4>
		</div>
		<div id="collapse_overall" class="panel-collapse collapse in" role="tabpanel">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="overall">
						<thead>
							<th class="searchable col-xs-2"><input type="text" class="form-control" placeholder="Name" /></th>
							<th class="searchable col-xs-2"><input type="text" class="form-control" placeholder="Game" /></th>
							<th class="searchable col-xs-2"><input type="text" class="form-control" placeholder="Position" /></th>
							<th class="col-xs-1">Score</th>
							<th class="col-xs-1">MVP</th>
							<th class="col-xs-1">Accuracy</th>
							<th class="col-xs-1">Hit Diff</th>
							<th class="col-xs-1">Medic Hits</th>
							<th class="col-xs-1">Shot Team</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_medic_hits" role="tab" id="#medic_hits_heading">
			<h4 class="panel-title">
				Medic Hits
			</h4>
		</div>
		<div id="collapse_medic_hits" class="panel-collapse collapse" role="tabpanel">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="medic_hits">
						<thead>
							<th class="searchable col-xs-2"><input type="text" class="form-control" placeholder="Name" /></th>
							<th class="col-xs-1">Total Medic Hits (All)</th>
							<th class="col-xs-1">Average Medic Hits (All)</th>
							<th class="col-xs-1">Games Played (All)</th>
							<th class="col-xs-1">Total Medic Hits (Non-Resupply)</th>
							<th class="col-xs-1">Average Medic Hits (Non-Resupply)</th>
							<th class="col-xs-1">Games Played (Non-Resupply)</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$('#nightlySelectDate').change(function() {
	var new_game_url = $('#game_list').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	var new_overall_url = $('#overall').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	var new_medic_url = $('#medic_hits').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	$('#game_list').DataTable().ajax.url(new_game_url).load();
	$('#overall').DataTable().ajax.url(new_overall_url).load();
	$('#medic_hits').DataTable().ajax.url(new_medic_url).load();
});
</script>