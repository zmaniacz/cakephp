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
	function stopPropagation(evt) {
		if (evt.stopPropagation !== undefined) {
			evt.stopPropagation();
		} else {
			evt.cancelBubble = true;
		}
	}

	$(document).ready(function() {
		const params = new URLSearchParams(location.search);

		$.ajax({
			url: "<?= html_entity_decode($this->Html->url(array('controller' => 'games', 'action' => 'getGameList', $current_date, 'ext' => 'json'))); ?>",
		}).done(function(response) {
			response.data.forEach(function(element) {
				var $wrapper = $('<div>', {class: 'list-group-item'});
				var $heading = $('<div>', {class: 'list-group-item-heading'});
				var $body = $('<div>', {class: 'list-group-item-text'});
				var $gameLink = $('<a>', {href: '/games/view/'+element.Game.id+'?'+params.toString()});
				var $pdfLink = $('<a>', {href: 'http://scorecards.lfstats.com/'+element.Game.pdf_id+'.pdf'}).text('PDF');

				$gameLink.html('<strong>'+element.Game.game_name+' - '+element.Game.game_datetime+'</strong>');
				$heading.append($gameLink);
				
				var red_team = '<span class="text-danger">Red Team: '+(element.Game.red_score+element.Game.red_adj)+'</span>';
				var green_team = '<span class="text-success">Green Team: '+(element.Game.green_score+element.Game.green_adj)+'</span>';

				$wrapper.append($heading);
				if(element.Game.winner === 'red') {
					$body.append('<strong>'+red_team+'</strong> | '+green_team+' - ');
				} else {
					$body.append('<strong>'+green_team+'</strong> | '+red_team+' - ');
				}
				$body.append($pdfLink);
				$wrapper.append($body);
				$('#game_list_group').append($wrapper);
			});
		});

		$("#overall thead th input").on( 'keyup change', function () {
			overall
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		});

		var overall = $('#overall').DataTable( {
			orderCellsTop : true,
			responsive: true,
			ajax : {
				url : "<?= html_entity_decode($this->Html->url(array('action' => 'nightlyScorecards', $current_date, 'ext' => 'json'))); ?>",
				dataSrc: function(response) {
					var result = response.data.map(function(element) {
						let positionClass = (element.Scorecard.team === 'red') ? 'text-danger' : 'text-success';
						let gameClass = (element.Game.winner === 'red') ? 'text-danger' : 'text-success';
						let hitDiff = Math.round(element.Scorecard.shot_opponent/Math.max(element.Scorecard.times_zapped,1) * 100) / 100;

						let playerLink = `<a href="/players/view/${element.Scorecard.player_id}?${params.toString()}">${element.Scorecard.player_name}</a>`;
						let gameLink = `<a href="/games/view/${element.Game.id}?${params.toString()}" class="${gameClass}">${element.Game.game_name}</a>`;
						let mvpLink = `<a href="#" data-toggle="modal" data-target="#mvpModal" target="/scorecards/getMVPBreakdown/${element.Scorecard.id}.json?${params.toString()}">${element.Scorecard.mvp_points} <span class="glyphicon glyphicon-stats"></span></a>`;
						let hitDiffLink = `<a href="#" data-toggle="modal" data-target="#hitModal" target="/scorecards/getHitBreakdown/${element.Scorecard.player_id}/${element.Scorecard.id}/${element.Scorecard.game_id}.json?${params.toString()}">${hitDiff} (${element.Scorecard.shot_opponent}/${element.Scorecard.times_zapped}) <span class="glyphicon glyphicon-stats"></span></a>`;
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
					console.log(result);
					return result;
				}
			},
			columns : [
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

		$("#summary_stats thead th input").on( 'keyup change', function () {
			summary_stats
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		});

		var summary_stats = $('#summary_stats').DataTable( {
			orderCellsTop : true,
			dom: '<r>t<ip>',
			scrollY: '500px',
			ajax : {
				url : "<?= html_entity_decode($this->Html->url(array('action' => 'nightlySummaryStats', $current_date, 'ext' => 'json'))); ?>"
			},
			columns : [
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
			"orderCellsTop" : true,
			"dom": '<r>t<ip>',
			scrollY: '500px',
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
<h4>Overall</h4>
<div>
	<table class="table table-striped table-bordered table-hover table-condensed" id="overall">
		<thead>
			<th>#</th>
			<th>Name</th>
			<th>Game</th>
			<th>Position</th>
			<th>Score</th>
			<th>MVP</th>
			<th>Hit Diff</th>
			<th>Medic Hits</th>
			<th>Accuracy</th>
			<th>Shot Team</th>
		</thead>
	</table>
</div>
<h4>Summary Stats</h4>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover table-condensed" id="summary_stats">
		<thead>
			<th>#</th>
			<th><input type="text" placeholder="Name" onClick="stopPropagation(event);" /></th>
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
<h4>Medic Hits</h4>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover table-condensed" id="medic_hits">
		<thead>
			<th>#</th>
			<th><input type="text" placeholder="Name" onClick="stopPropagation(event);" /></th>
			<th>Total Medic Hits (All)</th>
			<th>Average Medic Hits (All)</th>
			<th>Games Played (All)</th>
			<th>Total Medic Hits (Non-Resupply)</th>
			<th>Average Medic Hits (Non-Resupply)</th>
			<th>Games Played (Non-Resupply)</th>
		</thead>
	</table>
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