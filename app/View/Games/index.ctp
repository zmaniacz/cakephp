<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"pageLength": 25,
			"order": [1,'desc'],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'games', 'action' => 'getGameList', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : "game_name", },
				{ "data" : "game_datetime" },
				{ "data" : "winner" },
				{ "data" : "loser" },
				{ "data" : "pdf" }
			]
		});
	});
</script>
<div id="game_list_panel" class="panel panel-info">
	<div class="panel-heading" role="tab" id="game_list_heading">
		<h4 class="panel-title">
			Game List
		</h4>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-bordered table-hover table-condensed" id="game_list">
			<thead>
				<th>Game</th>
				<th>Time</th>
				<th>Winner Score</th>
				<th>Loser Score</th>
				<th>PDF</th>
			</thead>
		</table>
	</div>
</div>