<?php
	echo $this->Form->create('nightly');
	echo $this->Form->input('selectDate', array('label' => 'Select Date', 'options' => $game_dates));
	echo $this->Form->end();
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"autoWidth": false,
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyGames', $current_date, 'ext' => 'json')); ?>",
				"dataSrc" : "games"
			},
			"columns" : [
				{
					"data" : "Game.game_name",
					"render" : function(data, type, row, meta) {
						if(row.Game.type == 'league') {
							return '<a href="/games/view/'+row.Game.id+'">'+row.League.name+' - R'+row.Game.league_round+' M'+row.Game.league_match+' G'+row.Game.league_game+'</a>';
						} else {
							return '<a href="/games/view/'+row.Game.id+'">'+data+'</a>';
						}
					}
				},
				{ "data" : "Game.game_datetime" },
				{ 
					"data" : "Game", 
					"render" : function(data, type, row, meta) {
						if(row.Game.winner == 'Red') {
							return (row.Game.red_team_id == null ? 'Red Team' : row.Red_Team.name)+': '+(row.Game.red_score+row.Game.red_adj);
						} else {
							return (row.Game.green_team_id == null ? 'Green Team' : row.Green_Team.name)+': '+(row.Game.green_score+row.Game.green_adj);
						}
					}
				},
				{ 
					"data" : "Game", 
					"render" : function(data, type, row, meta) {
						if(row.Game.winner == 'Red') {
							return (row.Game.green_team_id == null ? 'Green Team' : row.Green_Team.name)+': '+(row.Game.green_score+row.Game.green_adj);
						} else {
							return (row.Game.red_team_id == null ? 'Red Team' : row.Red_Team.name)+': '+(row.Game.red_score+row.Game.red_adj);
						}
					}
				},
				{
					"data" : "Game.pdf_id",
					"render" : function(data, type, row, meta) {
						if(data == null)
							return 'N/A';
						else
							return '<a href="/pdf/'+data+'.pdf">PDF</a>';
					}
				}
			],
			"rowCallback" : function(row, data) {
				if(data.Game.winner == "Red")
					$(row).addClass('gameRowRed');
				else
					$(row).addClass('gameRowGreen');
			}
		});
		
		$('#overall').DataTable( {
			"scrollX" : true,
			"deferRender" : true,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyScorecards', $current_date, 'ext' => 'json')); ?>",
				"dataSrc" : "scorecards"
			},
			"columns" : [
				{
					"data" : "Scorecard.player_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/players/view/'+row.Scorecard.player_id+'">'+data+'</a>';
					}
				},
				{ "data" : "Game.game_name", "render" : function(data, type,row, meta) {return '<a href="/games/view/'+row.Game.id+'">'+data+'</a>'}},
				{ "data" : "Scorecard.position" },
				{ "data" : "Scorecard.score" },
				{ "data" : "Scorecard.mvp_points" },
				{ "data" : "Scorecard.accuracy", "render" : function(data, type, row, meta) {return parseFloat(data*100).toFixed(2)+'%';} },
				{ "data" : "Scorecard.shot_opponent", "render" : function(data, type, row, meta) {var diff = (data/row.Scorecard.times_zapped); return diff.toFixed(2);} },
				{ "data" : "Scorecard.medic_hits" },
				{ "data" : "Scorecard.shot_team" },
			],
			"order": [[ 3, "desc" ]]
		});
		
		$('#medic_hits').DataTable( {
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyMedicHits', $current_date, 'ext' => 'json')); ?>",
				"dataSrc" : "medic_hits"
			},
			"columns" : [
				{
					"data" : "Scorecard.player_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/players/view/'+row.Scorecard.player_id+'">'+data+'</a>';
					}
				},
				{ "data" : "0.total_medic_hits" },
				{ "data" : "0.medic_hits_per_game" },
				{ "data" : "0.games_played" },
				{ "data" : "ScorecardNoResup.total_medic_hits" },
				{ "data" : "ScorecardNoResup.medic_hits_per_game" },
				{ "data" : "ScorecardNoResup.games_played" }
			],
			"order": [[ 1, "desc" ]]
		});
	} );
</script>
<h3>Games Played</h3>
<div style="width: 1000px;">
	<table class="display" id="game_list">
		<thead>
			<th>Game</th>
			<th>Time</th>
			<th>Winner Score</th>
			<th>Loser Score</th>
			<th>Scorecard PDF</th>
		</thead>
	</table>
</div>
<div id="accordion">
	<h3>Overall</h3>
	<div>
		<table class="display" id="overall">
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
<script>
$('#nightlySelectDate').change(function() {
	var new_game_url = $('#game_list').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	var new_overall_url = $('#overall').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	var new_medic_url = $('#medic_hits').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	$('#game_list').DataTable().ajax.url(new_game_url).load();
	$('#overall').DataTable().ajax.url(new_overall_url).load();
	$('#medic_hits').DataTable().ajax.url(new_medic_url).load();
});

$( "#accordion" ).accordion( {
	collapsible: true,
	heightStyle: "content",
	activate: function(event, ui) {
		var oTable = $('div.dataTables_scrollBody>table.display', ui.panel).dataTable();
		if(oTable.length > 0) {
			oTable.fnAdjustColumnSizing();
		}
	}
} );
</script>