<form class="form-inline" action="/scorecards/nightly" id="nightlyNightlyForm" method="post" accept-charset="utf-8">
	<div class="form-group">
		<label for="nightlySelectDate">Select Date</label>
		<select class="custom-select" name="data[nightly][selectDate]" id="nightlySelectDate">
			<?php foreach($game_dates as $game_date): ?>
				<option value="<?= $game_date ?>" <?= ($game_date == $current_date) ? "selected" : ""; ?>><?= $game_date ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="d-none">
		<input type="hidden" name="_method" value="POST"/>
	</div>
</form>
</br>
<h4>Games Played</h4>
<div class="col col-md-6">
	<table class="table table-sm" id="game_list">
		<tbody>
		</tbody>
	</table>
</div>
<h4>Overall</h4>
<div class="col col-md-12">
	<table class="table table-sm table-bordered table-hover dt-responsive nowrap" id="overall">
		<thead>
			<th data-priority="1">#</th>
			<th data-priority="2">Name</th>
			<th>Game</th>
			<th data-priority="3">Position</th>
			<th>Score</th>
			<th data-priority="4">MVP</th>
			<th>Hit Diff</th>
			<th>Medic Hits</th>
			<th>Accuracy</th>
			<th class="none">Shot Team</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<h4>Summary Stats</h4>
<div class="col col-md-12">
	<table class="table table-sm table-bordered table-hover dt-responsive nowrap" id="summary_stats">
		<thead>
			<th data-priority="1">#</th>
			<th data-priority="2">Name</th>
			<th data-priority="3">Avg Score</th>
			<th class="none">Score (Min/Avg/Max)</th>
			<th data-priority="4">Avg MVP</th>
			<th class="none">MVP (Min/Avg/Max)</th>
			<th>Avg Acc</th>
			<th>Hit Diff</th>
			<th>Medic Hits</th>
			<th class="none">Elim Rate</th>
			<th class="none">Won/Played</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<h4>Medic Hits</h4>
<div class="col col-md-12">
	<table class="table table-sm table-bordered table-hover dt-responsive nowrap" id="medic_hits">
		<thead>
			<th data-priority="1">#</th>
			<th data-priority="2">Name</th>
			<th data-priority="3">Total</th>
			<th data-priority="4">Average</th>
			<th>Games Played (All)</th>
			<th>Total (Non-Resup)</th>
			<th data-priority="5">Average (Non-Resup)</th>
			<th>Games Played (Non-Resup)</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		const params = new URLSearchParams(location.search);
		params.set('date', '<?= $current_date; ?>');

		function updateGameList(params) {
			$.ajax({
				url: `/games/getGameList.json?${params.toString()}`,
			}).done(function(response) {
				$('#game_list').empty();

				response.data.forEach(function(element) {
					let game_time = new Date(Date.parse(element.Game.game_datetime)).toLocaleTimeString("en-US");
					let game = `<a href="/games/view/${element.Game.id}?${params.toString()}"><strong>${element.Game.game_name} - ${game_time}</strong></a>`;
					let pdf = `<a href="http://scorecards.lfstats.com/${element.Game.pdf_id}.pdf"><i class="far fa-file-pdf" data-fa-transform="grow-10"></i></a>`;
					
					let red_team = `<span class="text-danger">Red Team: ${element.Game.red_score+element.Game.red_adj}</span>`;
					let green_team = `<span class="text-success">Green Team: ${element.Game.green_score+element.Game.green_adj}</span>`;
					
					let teams = '';
					if(element.Game.winner === 'red') {
						teams = `<strong>${red_team}</strong><br>${green_team}`;
					} else {
						teams = `<strong>${green_team}</strong><br>${red_team}`;
					}

					$('#game_list').append(`<tr><td>${game}</td><td>${teams}</td><td>${pdf}</td></tr>`);
				});
			});
		}

		updateGameList(params);

		var overall = $('#overall').DataTable( {
			orderCellsTop : true,
			responsive: {
				details: {
					renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
						tableClass: 'table'
					} )
				}
			},
			ajax: {
				url: `/scorecards/nightlyScorecards.json?${params.toString()}`,
				dataSrc: function(response) {
					var result = response.data.map(function(element) {
						let positionClass = (element.Scorecard.team === 'red') ? 'text-danger' : 'text-success';
						let gameClass = (element.Game.winner === 'red') ? 'text-danger' : 'text-success';
						let hitDiff = Math.round(element.Scorecard.shot_opponent/Math.max(element.Scorecard.times_zapped,1) * 100) / 100;

						let playerLink = `<a href="/players/view/${element.Scorecard.player_id}?${params.toString()}">${element.Scorecard.player_name}</a>`;
						let gameLink = `<a href="/games/view/${element.Game.id}?${params.toString()}" class="${gameClass}">${element.Game.game_name}</a>`;
						let mvpLink = `<a href="#" data-toggle="modal" data-target="#mvpModal" target="/scorecards/getMVPBreakdown/${element.Scorecard.id}.json?${params.toString()}">${element.Scorecard.mvp_points}</a>`;
						let hitDiffLink = `<a href="#" data-toggle="modal" data-target="#hitModal" target="/scorecards/getHitBreakdown/${element.Scorecard.player_id}/${element.Scorecard.game_id}.json?${params.toString()}">${hitDiff} (${element.Scorecard.shot_opponent}/${element.Scorecard.times_zapped})</a>`;
						let positionElement = `<span class="${positionClass}">${element.Scorecard.position}</span>`;

						return {
							player_name: element.Scorecard.player_name,
							player_link: playerLink,
							game_name: element.Game.game_name,
							game_link: gameLink,
							position: element.Scorecard.position,
							position_element: positionElement,
							score: element.Scorecard.score,
							mvp_points: element.Scorecard.mvp_points,
							mvp_points_link: mvpLink,
							accuracy: (Math.round(element.Scorecard.accuracy * 100 * 100) / 100),
							hit_diff: hitDiff,
							hit_diff_link: hitDiffLink,
							medic_hits: element.Scorecard.medic_hits,
							shot_team: element.Scorecard.shot_team
						};
					});
					return result;
				}
			},
			columns: [
				{
					defaultContent : '',
					orderable: false
				},
				{
					data : null,
					render: {
						_: "player_name",
						display: "player_link"
					}
				},
				{
					data : null,
					render: {
						_: "game_name",
						display: "game_link"
					}
				},
				{
					data : null,
					render: {
						_: "position",
						display: "position_element"
					}
				},
				{ data: "score", orderSequence: [ "desc", "asc"], className: "text-right" },
				{
					data : null,
					render: {
						_: "mvp_points",
						display: "mvp_points_link"
					},
					className: "text-right"
				},
				{
					data : null,
					render: {
						_: "hit_diff",
						display: "hit_diff_link"
					},
					className: "text-right"
				},
				{ data: "medic_hits", orderSequence: [ "desc", "asc"], className: "text-right"  },
				{ data: "accuracy", orderSequence: [ "desc", "asc"], className: "text-right"  },
				{ data: "shot_team", orderSequence: [ "desc", "asc"], className: "text-right"  },
			],
			order: [[ 5, "desc" ]]
		});

		overall.on( 'order.dt', function () {
			overall.column(0, {order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		var summary_stats = $('#summary_stats').DataTable( {
			orderCellsTop : true,
			responsive: {
				details: {
					renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
						tableClass: 'table'
					} )
				}
			},
			ajax : {
				url : `/scorecards/nightlySummaryStats.json?${params.toString()}`
			},
			columns : [
				{
					defaultContent: '',
					orderable: false
				},
				{ 
					data: function ( row, type, val, meta) {
						if (type === 'display') {
							return `<a href="/players/view/${row.player_id}?${params.toString()}">${row.player_name}</a>`;
						}
						return row.player_name;
					},
					responsivePriority: 1
				},
				{ data: "avg_score", orderSequence: [ "desc", "asc"], className: "text-right" },
				{
					data: function(row, type, val, meta) {
						if(type === 'display') {
							return `${row.min_score}/${row.avg_score}/${row.max_score}`;
						}
						return row.avg_score;
					}, orderSequence: [ "desc", "asc"], className: "text-right"
				},
				{ 
					data: function ( row, type, val, meta) {
						if (type === 'display') {
							avg_mvp = Math.round(row.avg_mvp * 100) / 100;
							overall_avg_mvp = Math.round(row.overall_avg_mvp * 100) / 100;

							if (row.overall_avg_mvp >= row.avg_mvp) {
								return avg_mvp+'<span class="glyphicon glyphicon-arrow-down text-danger" title="'+overall_avg_mvp+'"></span>'
							} else {
								return avg_mvp+'<span class="glyphicon glyphicon-arrow-up text-success" title="'+overall_avg_mvp+'"></span>'
							}
						}

						return row.avg_mvp;
					},
					orderSequence: [ "desc", "asc"],
					className: "text-right",
					responsivePriority: 2
				},
				{
					data: function(row, type, val, meta) {
						if(type === 'display') {
							avg_mvp = Math.round(row.avg_mvp * 100) / 100;
							return `${row.min_mvp}/${avg_mvp}/${row.max_mvp}`;
						}
						return row.avg_mvp;
					}, orderSequence: [ "desc", "asc"], className: "text-right"
				},
				{
					data: function ( row, type, val, meta) {
						if (type === 'display') {
							avg_acc = Math.round(row.avg_acc * 100 * 100) / 100;
							overall_avg_acc = Math.round(row.overall_avg_acc * 100) / 100;

							if (row.overall_avg_acc >= row.avg_acc) {
								return avg_acc+'%<span class="glyphicon glyphicon-arrow-down text-danger" title="'+overall_avg_acc+'"></span>'
							} else {
								return avg_acc+'%<span class="glyphicon glyphicon-arrow-up text-success" title="'+overall_avg_acc+'"></span>'
							}
						}

						return row.avg_acc;
					},
					orderSequence: [ "desc", "asc"],
					className: "text-right",
					responsivePriority: 3
				},
				{
					data: function ( row, type, val, meta) {
						if (type === 'display') {
							hit_diff = Math.round(row.hit_diff * 100) / 100;

							return hit_diff;
						}

						return row.hit_diff;
					},
					orderSequence: [ "desc", "asc"],
					className: "text-right"
				},
				{ data: "medic_hits", orderSequence: [ "desc", "asc"], className: "text-right" },
				{ 
					data: function ( row, type, val, meta ) {
						var rate = row.elim_rate;
						if (type === 'display') {
							return rate+'%';
						}
							
						return rate;
					},
					orderSequence: [ "desc", "asc"],
					className: "text-right"
				},
				{
					data: function ( row, type, val, meta ) {
						var ratio = Math.round((row.games_won/row.games_played) * 100);
						if (type === 'display') {
							return ratio+'% ('+row.games_won+'/'+row.games_played+')';
						}
							
						return ratio;
					},
					orderSequence: [ "desc", "asc"],
					className: "text-right"
				}
			],
			"order": [[ 6, "desc" ]]
		});

		summary_stats.on( 'order.dt', function () {
			summary_stats.column(0, {order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		var medicHitsTable = $('#medic_hits').DataTable( {
			orderCellsTop: true,
			responsive: {
				details: {
					renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
						tableClass: 'table'
					} )
				}
			},
			ajax: {
				url: `/scorecards/nightlyMedicHits.json?${params.toString()}`
			},
			columns: [
				{
					defaultContent: '',
					orderable: false
				},
				{ 
					data: function ( row, type, val, meta) {
						if (type === 'display') {
							return `<a href="/players/view/${row.player_id}?${params.toString()}">${row.player_name}</a>`;
						}
						return row.player_name;
					},
					responsivePriority: 1
				},
				{ data: "total_medic_hits", orderSequence: [ "desc", "asc"], className: "text-right", responsivePriority: 2 },
				{ data: "medic_hits_per_game", orderSequence: [ "desc", "asc"], className: "text-right", responsivePriority: 3 },
				{ data: "games_played", orderSequence: [ "desc", "asc"], className: "text-right" },
				{ data: "non_resup_total_medic_hits", orderSequence: [ "desc", "asc"], className: "text-right" },
				{ data: "non_resup_medic_hits_per_game", orderSequence: [ "desc", "asc"], className: "text-right" },
				{ data: "non_resup_games_played", orderSequence: [ "desc", "asc"], className: "text-right" }
			],
			"order": [[ 2, "desc" ]]
		});

		medicHitsTable.on( 'order.dt', function () {
			medicHitsTable.column(0, {order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();

		$('#nightlySelectDate').change(function() {
			const params = new URLSearchParams(location.search);
			params.set('date', $(this).val());
			history.pushState(history.state, '', `?${params.toString()}`);

			updateGameList(params);
			$('#overall').DataTable().ajax.url(`/scorecards/nightlyScorecards.json?${params.toString()}`).load();
			$('#summary_stats').DataTable().ajax.url(`/scorecards/nightlySummaryStats.json?${params.toString()}`).load();
			$('#medic_hits').DataTable().ajax.url(`/scorecards/nightlyMedicHits.json?${params.toString()}`).load();
		});
	} );
</script>