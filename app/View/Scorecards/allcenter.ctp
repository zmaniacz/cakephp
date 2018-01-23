<script type="text/javascript">
	function updateAllCenter(min_games, min_days) {
		const params = new URLSearchParams(location.search);
		params.set('min_games',min_games);
		params.set('min_days',min_days);

		$.ajax({
			"url" : "/scorecards/getAllCenter.json?"+params.toString()
		}).done(function(response) {
			$('#all_center_a').DataTable( {
				searching: false,
				info: false,
				paging: false,
				ordering: false,
				data: response.all_center.team_a,
				columns: [
					{ data: "position" },
					{ data: "player_name" },
					{ data: "avg_mvp" }
				]
			});

			$('#all_center_b').DataTable( {
				searching: false,
				info: false,
				paging: false,
				ordering: false,
				data: response.all_center.team_b,
				columns: [
					{ data: "position" },
					{ data: "player_name" },
					{ data: "avg_mvp" }
				]
			});
		})
	}

	$(document).ready(function() {
		let min_games = 15;
		let min_days = 365;

		updateAllCenter(min_games, min_days);
	});
</script>
<div id="all_center_teams" class="panel panel-info">
	<div class="panel-heading" role="tab" id="all_center_teams_heading">
		<h4 class="panel-title">
			All-Center Teams
		</h4>
	</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<h3><span class="label label-success">1st Team</span></h3>
					<table class="allcenter table table-striped table-bordered table-hover" id="all_center_a">
						<thead>
							<th>Position</th>
							<th>Player</th>
							<th>Average MVP</th>
						</thead>
					</table>
				</div>
				<div class="col-sm-6">
					<h3><span class="label label-danger">2nd Team</span></h3>
					<table class="allcenter table table-striped table-bordered table-hover" id="all_center_b">
						<thead>
							<th>Position</th>
							<th>Player</th>
							<th>Average MVP</th>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
