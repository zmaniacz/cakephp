<script type="text/javascript">
	$(document).ready(function() {
		$('#game_list').DataTable( {
			"pageLength": 25,
			"order": [1,'desc'],
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'games', 'action' => 'getGameList', 'ext' => 'json'))); ?>"
			},
			"columns" : [
				{ "data" : function ( row, type, val, meta) {
						if(row.Game.winner == 'red') {
							var btnClass = 'btn-danger';
						} else {
							var btnClass = 'btn-success';
						}			
						if (type === 'display') {
							return '<a href="'+row.Game.link+'" class="btn '+btnClass+' btn-block">'+row.Game.game_name+'</a>';
						}
						return row.Game.game_name;
					}
				},
				{ "data" : "Game.game_datetime" },
				{ "data" : function ( row, type, val, meta) {			
						if(row.Game.winner == 'red') {
							var score = row.Red_Team.raw_score + row.Red_Team.bonus_score + row.Red_Team.penalty_score;
							var winner = 'Red Team: '+score;
							if(row.Red_Team.link) {
								winner = '<a href="'+row.Red_Team.link+'" class="btn btn-danger btn-block">'+row.Red_Team.EventTeam.name+': '+score+'</a>';
							}
						} else {
							var score = row.Green_Team.raw_score + row.Green_Team.bonus_score + row.Green_Team.penalty_score;
							var winner = 'Green Team: '+score;
							if(row.Green_Team.link) {
								winner = '<a href="'+row.Green_Team.link+'" class="btn btn-danger btn-block">'+row.Green_Team.EventTeam.name+': '+score+'</a>';
							}
						}
						
						if (type === 'display') {
							return winner;
						}
						return score;
					}
				},
				{ "data" : function ( row, type, val, meta) {			
						if(row.Game.winner == 'green') {
							var score = row.Red_Team.raw_score + row.Red_Team.bonus_score + row.Red_Team.penalty_score;
							var loser = 'Red Team: '+score;
							if(row.Red_Team.link) {
								loser = '<a href="'+row.Red_Team.link+'" class="btn btn-danger btn-block">'+row.Red_Team.EventTeam.name+': '+score+'</a>';
							}
						} else {
							var score = row.Green_Team.raw_score + row.Green_Team.bonus_score + row.Green_Team.penalty_score;
							var loser = 'Green Team: '+score;
							if(row.Green_Team.link) {
								loser = '<a href="'+row.Green_Team.link+'" class="btn btn-danger btn-block">'+row.Green_Team.EventTeam.name+': '+score+'</a>';
							}
						}
						
						if (type === 'display') {
							return loser;
						}
						return score;
					}
				},
				{ "data" : function ( row, type, val, meta) {		
						if (type === 'display') {
							return '<a href="'+row.Game.pdf_link+'" class="btn btn-info btn-block" target="_blank">PDF</a>';
						}
						return row.Game.pdf_id;
					}
				}
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