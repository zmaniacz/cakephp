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
<div id="accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_rounds" role="tab" id="rounds_heading">
		<h4 class="panel-title">
			Match Detail
		</h4>
	</div>
	<div id="collapse_rounds" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<ul class="nav nav-tabs" id="round_tabs">
				<?php foreach($details['Round'] as $round): ?>
					<li>
						<a href="#round<?= $round['id']; ?>" data-toggle="tab">
							<?= ($round['is_finals']) ? "Finals" : "Round ".$round['round']; ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="tab-content">
				<?php foreach($details['Round'] as $round) { ?>
					<div class="tab-pane" id="round<?= $round['id']; ?>">
						<?php foreach($round['Match'] as $match) { ?>
							<div class="panel panel-info">
								<div class="panel-heading">
									<h4 class="panel-title">
										Match <?= $match['match']; ?>
									</h4>
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover table-condensed" id="match<?= $match['id']; ?>">
											<thead>
												<th class="col-xs-4">Team</th>
												<th class="col-xs-2">Points</th>
												<th class="col-xs-2">Game 1 Score</th>
												<th class="col-xs-2">Game 2 Score</th>
												<th class="col-xs-2">Total</th>
											</thead>
											<tbody>
												<tr>
													<td>
													<?php
														echo (is_null($match['team_1_id'])) ? "TBD" : $this->Html->link($teams[$match['team_1_id']], array('controller' => 'teams', 'action' => 'view', $match['team_1_id']), array('class' => 'btn btn-block btn-info'));
													?>
													</td>
													<td class="text-center"><?= $match['team_1_points']; ?></td>
													<td class="text-center"><?= (!empty($match['Game_1'])) ? $this->Html->link($match['Game_1']['red_score'] + $match['Game_1']['red_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_1']['id']), array('class' => 'btn btn-block btn-danger')) : ""; ?></td>
													<td class="text-center"><?= (!empty($match['Game_2'])) ? $this->Html->link($match['Game_2']['green_score'] + $match['Game_2']['green_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_2']['id']), array('class' => 'btn btn-block btn-success')) : ""; ?></td>
													<td class="text-center"><?= (!empty($match['Game_1']) && !empty($match['Game_2'])) ? $match['Game_1']['red_score'] + $match['Game_1']['red_adj'] + $match['Game_2']['green_score'] + $match['Game_2']['green_adj'] : ""; ?></td>
												</tr>
												<tr>
													<td>
													<?php
														echo (is_null($match['team_2_id'])) ? "TBD" : $this->Html->link($teams[$match['team_2_id']], array('controller' => 'teams', 'action' => 'view', $match['team_2_id']), array('class' => 'btn btn-block btn-info'));
													?>
													</td>
													<td class="text-center"><?= $match['team_2_points']; ?></td>
													<td class="text-center"><?= (!empty($match['Game_1'])) ? $this->Html->link($match['Game_1']['green_score'] + $match['Game_1']['green_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_1']['id']), array('class' => 'btn btn-block btn-success')) : ""; ?></td>
													<td class="text-center"><?= (!empty($match['Game_2'])) ? $this->Html->link($match['Game_2']['red_score'] + $match['Game_2']['red_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_2']['id']), array('class' => 'btn btn-block btn-danger')) : ""; ?></td>
													<td class="text-center"><?= (!empty($match['Game_1']) && !empty($match['Game_2'])) ? $match['Game_1']['green_score'] + $match['Game_1']['green_adj'] + $match['Game_2']['red_score'] + $match['Game_2']['red_adj'] : ""; ?></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
				<?php } ?>	
			</div>
		</div>
	</div>
</div>
<script>
	$('#round_tabs a:first').tab('show')
</script>