<?php
	$scorecards = array();

	foreach($team['Red_Game'] as $game) {
		foreach($game['Red_Scorecard'] as $scorecard) {
			$scorecards[] = $scorecard;
		}
	}

	foreach($team['Green_Game'] as $game) {
		foreach($game['Green_Scorecard'] as $scorecard) {
			$scorecards[] = $scorecard;
		}
	}
	
	$player_positions = array();
	foreach($scorecards as $scorecard) {
		if(!$scorecard['is_sub']) {
			if(isset($player_positions[$scorecard['player_name']])) {
				$player_positions[$scorecard['player_name']][$scorecard['position']] += 1;
			} else {
				$player_positions[$scorecard['player_name']] = array(
					'Commander' => 0,
					'Heavy Weapons' => 0,
					'Scout' => 0,
					'Ammo Carrier' => 0,
					'Medic' =>0
				);

				$player_positions[$scorecard['player_name']][$scorecard['position']] += 1;
			}
		}
	}
?>
<h2 class="text-warning"><?= $details['League']['name']; ?> - <?= $team['Team']['name']; ?></h2>
<div id="positions_panel" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#positions_panel" data-target="#collapse_positions" role="tab" id="positions_heading">
		<h4 class="panel-title">
			Positions Detail
		</h4>
	</div>
	<div id="collapse_positions" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<table class="table table-striped table-bordered table-hover table-condensed" id="positions_table">
				<thead>
					<tr>
						<th></th>
						<th colspan="5" class="text-center">Games Played</th>
					</tr>
					<tr>
						<th class="col-xs-2">Player</th>
						<th class="col-xs-2">Commander</th>
						<th class="col-xs-2">Heavy Weapons</th>
						<th class="col-xs-2">Scout</th>
						<th class="col-xs-2">Ammo Carrier</th>
						<th class="col-xs-2">Medic</th>
					</tr>
				</thead>
				<tbody class="text-center">
					<?php foreach($player_positions as $player => $position): ?>
						<tr>
							<td><?= $player; ?></td>
							<td><?= $position['Commander']; ?></td>
							<td><?= $position['Heavy Weapons']; ?></td>
							<td><?= $position['Scout']; ?></td>
							<td><?= $position['Ammo Carrier']; ?></td>
							<td><?= $position['Medic']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div>
	<input class="pull-right" type="text" id="search-criteria" placeholder="Search Matches..." />
	<?php foreach($details['Round'] as $round): ?>
		<div class="page-header">
			<h3><?= (($round['is_finals']) ? "Finals" : "Round ".$round['round']); ?></h3>
		</div>
		<div class="row">
		<?php foreach($round['Match'] as $match): ?>
			<div class="col-md-4 match-panel">
				<div class="panel panel-info">
					<div class="panel-heading">
						<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#matchModal" target="<?= $this->Html->url(array('controller' => 'leagues', 'action' => 'ajax_getMatchDetails', $match['id'], 'ext' => 'json')); ?>">More...</button>
						<h5><?= (($round['is_finals']) ? "Finals" : "R".$round['round'])." M".$match['match']; ?></h5>
					</div>
					<div class="panel-body">
						<table class="table table-condensed">
							<thead>
								<tr>
									<th>Team</th>
									<th>Game 1</th>
									<th>Game 2</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
											if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID'))) {
												echo "<select id=\"Match{$match['match']}Team1\" 
														class=\"match-select form-control\" 
														data-match-id={$match['id']}
														data-match-number={$match['match']}
														data-round-id={$match['round_id']}
														data-team=1
														>";
												echo "<option value=\"\">Select a team</option>";
												foreach($teams as $key => $value) {
													if($key == $match['team_1_id'])
														echo "<option value=\"$key\" selected>$value</option>";
													else
														echo "<option value=\"$key\">$value</option>";
												}
												echo "</select>";
											} else {
												echo (is_null($match['team_1_id'])) ? "TBD" : $this->Html->link($teams[$match['team_1_id']], array('controller' => 'teams', 'action' => 'view', $match['team_1_id']));
											}

											if(!empty($match['Game_1']) && !empty($match['Game_2']) && $match['team_1_points'] > $match['team_2_points'])
												echo " <span class=\"glyphicon glyphicon-star text-warning\"></span>";
										?>
									</td>
									<td class="text-center">
										<?php 
											if(!empty($match['Game_1'])) {
												if( ($match['Game_1']['winner'] == 'red' && $match['team_1_id'] == $match['Game_1']['red_team_id']) || ($match['Game_1']['winner'] == 'green' && $match['team_1_id'] == $match['Game_1']['green_team_id']))
													echo "<span class=\"glyphicon glyphicon-ok text-success\"></span>";
												else
													echo "<span class=\"glyphicon glyphicon-remove text-danger\"></span>";
											}
										?>
									</td>
									<td class="text-center">
										<?php 
											if(!empty($match['Game_2'])) {
												if( ($match['Game_2']['winner'] == 'red' && $match['team_1_id'] == $match['Game_2']['red_team_id']) || ($match['Game_2']['winner'] == 'green' && $match['team_1_id'] == $match['Game_2']['green_team_id']))
													echo "<span class=\"glyphicon glyphicon-ok text-success\"></span>";
												else
													echo "<span class=\"glyphicon glyphicon-remove text-danger\"></span>";
											}
										?>
									</td>
								</tr>
								<tr>
									<td>
										<?php
											if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID'))) {
												echo "<select id=\"Match{$match['match']}Team2\" 
														class=\"match-select form-control\" 
														data-match-id={$match['id']}
														data-match-number={$match['match']}
														data-round-id={$match['round_id']}
														data-team=2
														>";
												echo "<option value=\"\">Select a team</option>";
												foreach($teams as $key => $value) {
													if($key == $match['team_2_id'])
														echo "<option value=\"$key\" selected>$value</option>";
													else
														echo "<option value=\"$key\">$value</option>";
												}
												echo "</select>";
											} else {
												echo (is_null($match['team_2_id'])) ? "TBD" : $this->Html->link($teams[$match['team_2_id']], array('controller' => 'teams', 'action' => 'view', $match['team_2_id']));
											}

											if(!empty($match['Game_1']) && !empty($match['Game_2']) && $match['team_2_points'] > $match['team_1_points'])
												echo " <span class=\"glyphicon glyphicon-star text-warning\"></span>";
										?>
									</td>
									<td class="text-center">
										<?php 
											if(!empty($match['Game_1'])) {
												if( ($match['Game_1']['winner'] == 'red' && $match['team_2_id'] == $match['Game_1']['red_team_id']) || ($match['Game_1']['winner'] == 'green' && $match['team_2_id'] == $match['Game_1']['green_team_id']))
													echo "<span class=\"glyphicon glyphicon-ok text-success\"></span>";
												else
													echo "<span class=\"glyphicon glyphicon-remove text-danger\"></span>";
											}
										?>
									</td>
									<td class="text-center">
										<?php 
											if(!empty($match['Game_1'])) {
												if( ($match['Game_2']['winner'] == 'red' && $match['team_2_id'] == $match['Game_2']['red_team_id']) || ($match['Game_2']['winner'] == 'green' && $match['team_2_id'] == $match['Game_2']['green_team_id']))
													echo "<span class=\"glyphicon glyphicon-ok text-success\"></span>";
												else
													echo "<span class=\"glyphicon glyphicon-remove text-danger\"></span>";
											}
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	<?php endforeach; ?>
</div>
<script>
	$('#search-criteria').keyup(function(){
		$('.match-panel').hide();
		var txt = $('#search-criteria').val();
		$('.match-panel').each(function(){
		if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
			$(this).show();
		}
		});
	});
</script>