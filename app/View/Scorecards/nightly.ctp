<h1>Stats for <?php echo $current_date;?></h1>
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
			"jQueryUI": true,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyStats', $current_date, 'ext' => 'json')); ?>",
				"dataSrc" : "games"
			},
			"columns" : [
				{
					"data" : "Game.game_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/<?php echo $this->params->center; ?>/games/view/'+row.Game.id+'">'+data+'</a>';
					}
				},
				{ "data" : "Game.game_datetime" },
				{ "data" : "Game.red_score" },
				{ "data" : "Game.green_score" },
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
			"jQueryUI": true,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyStats', $current_date, 'ext' => 'json')); ?>",
				"dataSrc" : "scorecards"
			},
			"columns" : [
				{
					"data" : "Scorecard.player_name",
					"render" : function(data, type, row, meta) {
						return '<a href="/<?php echo $this->params->center; ?>/players/view/'+row.Scorecard.player_id+'">'+data+'</a>';
					}
				},
				{ "data" : "Scorecard.position" },
				{ "data" : "Scorecard.score" },
				{ "data" : "Scorecard.mvp_points" },
				{ "data" : "Scorecard.accuracy", "render" : function(data, type, row, meta) {return parseFloat(data*100).toFixed(2)+'%';} },
				{ "data" : "Scorecard.shot_opponent", "render" : function(data, type, row, meta) {var diff = (data/row.Scorecard.times_zapped); return diff.toFixed(2);} },
				{ "data" : "Scorecard.medic_hits" },
				{ "data" : "Scorecard.shot_team" },
			],
			"order": [[ 2, "desc" ]]
		});
		
		$('#medic_hits').DataTable( {
			"jQueryUI": true,
			"ajax" : {
				"url" : "<?php echo $this->Html->url(array('action' => 'nightlyStats', $current_date, 'ext' => 'json')); ?>",
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
<h3>Games Played</h3>
<div style="width: 500px;">
	<table id="game_list">
		<thead>
			<th>Game</th>
			<th>Time</th>
			<th>Red Score</th>
			<th>Green Score</th>
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
<script>
$('#nightlySelectDate').change(function() {
	var new_url = $('#game_list').DataTable().ajax.url().replace(/\d{4}-\d{2}-\d{2}/, $(this).val());
	$('#game_list').DataTable().ajax.url(new_url).load();
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