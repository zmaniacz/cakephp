<h2 class="text-warning"><?= $details['League']['name']; ?> - <?= $team['Team']['name']; ?></h2>
<div id="accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_rounds" role="tab" id="rounds_heading">
		<h4 class="panel-title">
			Rounds
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