<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'gameList', $selected_event['Event']['id'], 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : function ( row, type, val, meta) {
						if(row.winner === 'red') {
							var btn_class = 'btn btn-danger btn-block';
						} else {
							var btn_class = 'btn btn-success btn-block';
						}
						
						if (type === 'display') {
							return '<a href="/games/view/'+row.id+location.search+'" class="'+btn_class+'">'+row.game_name+'</a>';
						}

						return row.name;
					}
				},
				{ "data" : "game_datetime" },
				{ "data" : function ( row, type, val, meta) {
						if(row.winner === 'red') {
							var score = row.Red_Team.raw_score + row.Red_Team.bonus_score + row.Red_Team.penalty_score;
							if(row.Red_Team.EventTeam.length > 0) {
								var name = row.Red_Team.EventTeam.name;
							} else {
								var name = 'Red Team';
							}
						} else {
							var score = row.Green_Team.raw_score + row.Green_Team.bonus_score + row.Green_Team.penalty_score;
							if(row.Green_Team.EventTeam.length > 0) {
								var name = row.Green_Team.EventTeam.name;
							} else {
								var name = 'Green Team';
							}
						}
						
						if (type === 'display') {
							return name+' : '+score;
						}

						return score;
					}
				},
				{ "data" : function ( row, type, val, meta) {
						if(row.winner === 'red') {
							var score = row.Green_Team.raw_score + row.Green_Team.bonus_score + row.Green_Team.penalty_score;
							if(row.Green_Team.EventTeam.length > 0) {
								var name = row.Green_Team.EventTeam.name;
							} else {
								var name = 'Green Team';
							}
						} else {
							var score = row.Red_Team.raw_score + row.Red_Team.bonus_score + row.Red_Team.penalty_score;
							if(row.Red_Team.EventTeam.length > 0) {
								var name = row.Red_Team.EventTeam.name;
							} else {
								var name = 'Red Team';
							}
						}
						
						if (type === 'display') {
							return name+' : '+score;
						}

						return score;
					}
				},
				{ "data" : function ( row, type, val, meta) {
						if (type === 'display') {
							return '<a href="http://scorecards.lfstats.com/'+row.pdf_id+'.pdf" class="btn btn-info btn-block" target="_blank">PDF</a>';
						}

						return row.pdf_id;
					}
				}
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
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventScorecards', $selected_event['Event']['id'], 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{
					"defaultContent" : '',
					"orderable": false
				},
				{ "data" : function ( row, type, val, meta) {			
						if (type === 'display') {
							return '<a href="/players/view/'+row.player_id+location.search+'" class="btn btn-info btn-block">'+row.player_name+'</a>';
						}
						return row.player_name;
					},
					"width" : "200px" 
				},
				{ "data" : function ( row, type, val, meta) {
						if(row.winner === 'red') {
							var btn_class = 'btn btn-danger btn-block';
						} else {
							var btn_class = 'btn btn-success btn-block';
						}

						if (type === 'display') {
							return '<a href="/games/view/'+row.game_id+location.search+'" class="'+btn_class+'">'+row.game_name+'</a>';
						}
						return row.game_name;
					}
				},
				{ "data" : "position" },
				{
					"data" : "score",
					"orderSequence": [ "desc", "asc"]
				},
				{ "data" : function ( row, type, val, meta) {
						if (type === 'display') {
							return '<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#mvpModal" target="/scorecards/getMVPBreakdown/'+row.id+'.json">'+row.mvp_points+'</button>';
						}
						return row.mvp_points;
					},
					"orderSequence": [ "desc", "asc"]
				},
				{ "data" : function ( row, type, val, meta) {
						var hit_diff = row.shot_opponent/row.times_zapped;
						if (type === 'display') {
							return '<button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#hitModal" target="/scorecards/getHitBreakdown/'+row.player_id+'/'+row.game_id+'.json">'+parseFloat(hit_diff).toFixed(2)+' ('+row.shot_opponent+'/'+row.times_zapped+')</button>';
						}
						return row.mvp_points;
					},
					"orderSequence": [ "desc", "asc"]
				},
				{ "data" : "medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta) {
						var acc = row.accuracy * 100;
						if (type === 'display') {
							return parseFloat(acc).toFixed(2);
						}
						return row.accuracy;
					},
					"orderSequence": [ "desc", "asc"]
				},
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
				"url" : "<?= html_entity_decode($this->Html->url(array('action' => 'summaryStats', $selected_event['Event']['id'], 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{
					"defaultContent" : '',
					"orderable": false
				},
				{ "data" : function ( row, type, val, meta) {			
						if (type === 'display') {
							return '<a href="/players/view/'+row.player_id+location.search+'" class="btn btn-info btn-block">'+row.player_name+'</a>';
						}
						return row.player_name;
					},
					"width" : "200px" 
				},
				{ "data" : "min_score", "orderSequence": [ "desc", "asc"] },
				{ "data" : "avg_score", "orderSequence": [ "desc", "asc"] },
				{ "data" : "max_score", "orderSequence": [ "desc", "asc"] },
				{ "data" : "min_mvp", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta) {
						if (type === 'display') {
							var avg_mvp = parseFloat(row.avg_mvp).toFixed(2);
							if (row.overall_avg_mvp >= row.avg_mvp) {
								return avg_mvp+'<span class="glyphicon glyphicon-arrow-down text-danger" title="'+row.overall_avg_mvp+'"></span>'
							} else {
								return avg_mvp+'<span class="glyphicon glyphicon-arrow-up text-success" title="'+row.overall_avg_mvp+'"></span>'
							}
						}

						return row.avg_mvp;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : "max_mvp", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta) {
						if (type === 'display') {
							var avg_acc = parseFloat(row.avg_acc*100).toFixed(2);
							if (row.overall_avg_acc >= row.avg_acc) {
								return avg_acc+'<span class="glyphicon glyphicon-arrow-down text-danger" title="'+row.overall_avg_acc+'"></span>'
							} else {
								return avg_acc+'<span class="glyphicon glyphicon-arrow-up text-success" title="'+row.overall_avg_acc+'"></span>'
							}
						}

						return row.avg_acc;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : "hit_diff", "orderSequence": [ "desc", "asc"] },
				{ "data" : "medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta ) {
						var rate = parseFloat(row.elim_rate*100).toFixed(2);
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
				"url" : "<?= html_entity_decode($this->Html->url(array('action' => 'medicHits', $selected_event['Event']['id'], 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{
					"defaultContent" : '',
					"orderable": false
				},
				{ "data" : function ( row, type, val, meta) {			
						if (type === 'display') {
							return '<a href="/players/view/'+row.player_id+location.search+'" class="btn btn-info btn-block">'+row.player_name+'</a>';
						}
						return row.player_name;
					},
					"width" : "200px" 
				},
				{ "data" : "total_medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta ) {
						var medic_hits_per_game = row.total_medic_hits/row.total_games_played;
						if (type === 'display') {
							return parseFloat(medic_hits_per_game).toFixed(2);
						}
						return medic_hits_per_game;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : "total_games_played", "orderSequence": [ "desc", "asc"] },
				{ "data" : "non_resup_total_medic_hits", "orderSequence": [ "desc", "asc"] },
				{ "data" : function ( row, type, val, meta ) {
						var non_resup_medic_hits_per_game = row.non_resup_total_medic_hits/row.non_resup_total_games_played;
						if (type === 'display') {
							return parseFloat(non_resup_medic_hits_per_game).toFixed(2);
						}
						return non_resup_medic_hits_per_game;
					}, "orderSequence": [ "desc", "asc"]
				},
				{ "data" : "non_resup_total_games_played", "orderSequence": [ "desc", "asc"] }
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