<form class="form-inline" action="/scorecards/nightly" id="nightlyNightlyForm" method="post" accept-charset="utf-8">
	<div style="display:none;">
		<input type="hidden" name="_method" value="POST"/>
	</div>
	<div class="form-group">
		<label for="nightlySelectDate">Select Date</label>
		<select class="form-control" name="data[nightly][selectDate]" id="nightlySelectDate">
			<?php foreach($game_dates as $game_date): ?>
				<option value="<?= $game_date ?>" <?= ($game_date == $current_date) ? "selected" : ""; ?>><?= $game_date ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</form>
</br>
<script type="text/javascript">
	$(document).ready(function() {
		const params = new URLSearchParams(location.search);

		$.ajax({
			url: "<?= html_entity_decode($this->Html->url(array('controller' => 'games', 'action' => 'getGameList', $current_date, 'ext' => 'json'))); ?>",
		}).done(function(response) {
			response.data.forEach(function(element) {
				var $li = $('<li>', {class: 'list-group-item'});
				var $link = $('<a>', {href: '/games/view/'+element.Game.id+'?'+params.toString()});
				var $pdfLink = $('<a>', {href: 'http://scorecards.lfstats.com/'+element.Game.pdf_id+'.pdf'}).text('PDF');

				$link.html('<strong>'+element.Game.game_name+' - '+element.Game.game_datetime+'</strong>');

				var red_team = '<span class="text-danger">Red Team: '+(element.Game.red_score+element.Game.red_adj)+'</span>';
				var green_team = '<span class="text-success">Green Team: '+(element.Game.green_score+element.Game.green_adj)+'</span>';

				if(element.Game.winner === 'red') {
					$li.append($link);
					$li.append(' - <strong>'+red_team+'</strong> | '+green_team+' - ');
				} else {
					$li.append($link);
					$li.append(' - <strong>'+green_team+'</strong> | '+red_team+' - ');
				}
				$li.append($pdfLink);
				$('#game_list_group').append($li);
			});
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
				{
					"defaultContent" : '',
					"orderable": false
				},
				{ "data" : "player_name", "width" : "200px" },
				{ "data" : "game_name" },
				{ "data" : "position" },
				{
					"data" : "score",
					"orderSequence": [ "desc", "asc"]
				},
				{ "data" : "mvp_points", "orderSequence": [ "desc", "asc"] },
				{ "data" : "hit_diff", "orderSequence": [ "desc", "asc"] },
				{ "data" : "medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : "accuracy", "orderSequence": [ "desc", "asc"] },
				{ "data" : "shot_team", "orderSequence": [ "desc", "asc"] },
			],
			"order": [[ 5, "desc" ]]
		});

		overall.on( 'order.dt', function () {
			overall.column(0, {order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$("#summary_stats thead th input").on( 'keyup change', function () {
			summary_stats
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		});

		var summary_stats = $('#summary_stats').DataTable( {
			"deferRender" : true,
			"orderCellsTop" : true,
			"dom": '<lr>t<ip>',
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('action' => 'nightlySummaryStats', $current_date, 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{
					"defaultContent" : '',
					"orderable": false
				},
				{ "data" : "player_name" },
				{ "data" : "min_score", "orderSequence": [ "desc", "asc"] },
				{ "data" : "avg_score", "orderSequence": [ "desc", "asc"] },
				{ "data" : "max_score", "orderSequence": [ "desc", "asc"] },
				{ "data" : "min_mvp", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta) {
						if (type === 'display') {
							if (row.overall_avg_mvp >= row.avg_mvp) {
								return row.avg_mvp+'<span class="glyphicon glyphicon-arrow-down text-danger" title="'+row.overall_avg_mvp+'"></span>'
							} else {
								return row.avg_mvp+'<span class="glyphicon glyphicon-arrow-up text-success" title="'+row.overall_avg_mvp+'"></span>'
							}
						}

						return row.avg_mvp;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : "max_mvp", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta) {
						if (type === 'display') {
							if (row.overall_avg_acc >= row.avg_acc) {
								return row.avg_acc+'<span class="glyphicon glyphicon-arrow-down text-danger" title="'+row.overall_avg_acc+'"></span>'
							} else {
								return row.avg_acc+'<span class="glyphicon glyphicon-arrow-up text-success" title="'+row.overall_avg_acc+'"></span>'
							}
						}

						return row.avg_acc;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : "hit_diff", "orderSequence": [ "desc", "asc"] },
				{ "data" : "medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta ) {
						var rate = row.elim_rate;
						if (type === 'display') {
							return rate+'%';
						}
							
						return rate;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : function ( row, type, val, meta ) {
						var ratio = Math.round((row.games_won/row.games_played) * 100);
						if (type === 'display') {
							return ratio+'% ('+row.games_won+'/'+row.games_played+')';
						}
							
						return ratio;
					}, "orderSequence": [ "desc", "asc"]
				}
			],
			"order": [[ 6, "desc" ]]
		});

		summary_stats.on( 'order.dt', function () {
			summary_stats.column(0, {order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

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
				{
					"defaultContent" : '',
					"orderable": false
				},
				{ "data" : "player_name" },
				{ "data" : "total_medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : "medic_hits_per_game", "orderSequence": [ "desc", "asc"] },
				{ "data" : "games_played", "orderSequence": [ "desc", "asc"] },
				{ "data" : "non_resup_total_medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : "non_resup_medic_hits_per_game", "orderSequence": [ "desc", "asc"] },
				{ "data" : "non_resup_games_played", "orderSequence": [ "desc", "asc"] }
			],
			"order": [[ 2, "desc" ]]
		});

		medicHitsTable.on( 'order.dt', function () {
			medicHitsTable.column(0, {order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
	} );
</script>
<h4>Games Played</h4>
<div id="game_list_group" class="list-group"></div>
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
					<table class="table table-striped table-bordered table-hover table-condensed" id="overall">
						<thead>
							<th>#</th>
							<th class="searchable col-xs-2"><input type="text" placeholder="Name" /></th>
							<th class="searchable col-xs-2"><input type="text" placeholder="Game" /></th>
							<th class="searchable col-xs-2"><input type="text" placeholder="Position" /></th>
							<th class="col-xs-1">Score</th>
							<th class="col-xs-1">MVP</th>
							<th class="col-xs-1">Hit Diff</th>
							<th class="col-xs-1">Medic Hits</th>
							<th class="col-xs-1">Accuracy</th>
							<th class="col-xs-1">Shot Team</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-info">
		<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_summary_stats" role="tab" id="#summary_stats_heading">
			<h4 class="panel-title">
				Summary Stats
			</h4>
		</div>
		<div id="collapse_summary_stats" class="panel-collapse collapse" role="tabpanel">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover table-condensed" id="summary_stats">
						<thead>
							<th>#</th>
							<th><input type="text" placeholder="Name" /></th>
							<th>Min Score</th>
							<th>Avg Score</th>
							<th>Max Score</th>
							<th>Min MVP</th>
							<th>Avg MVP</th>
							<th>Max MVP</th>
							<th>Avg Acc</th>
							<th>Hit Diff</th>
							<th>Medic Hits</th>
							<th>Elim Rate</th>
							<th>Won/Played</th>
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
					<table class="table table-striped table-bordered table-hover table-condensed" id="medic_hits">
						<thead>
							<th>#</th>
							<th class="searchable col-xs-2"><input type="text" placeholder="Name" /></th>
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
	var new_summary_url = $('#summary_stats').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	var new_medic_url = $('#medic_hits').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	$('#game_list').DataTable().ajax.url(new_game_url).load();
	$('#overall').DataTable().ajax.url(new_overall_url).load();
	$('#summary_stats').DataTable().ajax.url(new_summary_url).load();
	$('#medic_hits').DataTable().ajax.url(new_medic_url).load();
});
</script>