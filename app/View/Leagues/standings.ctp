<div id="top_accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#top_accordion" data-target="#collapse_standings" role="tab" id="standings_heading">
		<h4 class="panel-title">
			Team Standings
		</h4>
	</div>
	<div id="collapse_standings" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<?php 
				if(AuthComponent::user('role') === 'admin')
					echo $this->Html->link('New Team', array('controller' => 'leagues', 'action' => 'addTeam'), array('class' => 'btn btn-success'));
			?>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-condensed" id="team_standings">
					<thead>
						<th class="col-xs-2">Team</th>
						<th class="col-xs-1">Points</th>
						<th class="col-xs-1">Played</th>
						<th class="col-xs-1">Won</th>
						<th class="col-xs-1">Lost</th>
						<th class="col-xs-1">Match</th>
						<th class="col-xs-1">Eliminations</th>
						<th class="col-xs-1">For</th>
						<th class="col-xs-1">Against</th>
						<th class="col-xs-1">Ratio</th>
					</thead>
					<tbody>
						<?php foreach($standings as $team): ?>
						<tr>
							<td><?= $this->Html->link($team['name'], array('controller' => 'teams', 'action' => 'view', $team['id']), array('class' => 'btn btn-block btn-info')); ?></td>
							<td><?= $team['points']; ?></td>
							<td><?= $team['played']; ?></td>
							<td><?= $team['won']; ?></td>
							<td><?= $team['lost']; ?></td>
							<td><?= $team['matches_won']; ?></td>
							<td><?= $team['elims']; ?></td>
							<td><?= $team['for']; ?></td>
							<td><?= $team['against']; ?></td>
							<td><?= round($team['ratio'], 2); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_rounds" role="tab" id="rounds_heading">
		<h4 class="panel-title">
			Rounds
		</h4>
	</div>
	<div id="collapse_rounds" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<?php 
				if(AuthComponent::user('role') === 'admin')
					echo $this->Html->link('Add Round', array('controller' => 'leagues', 'action' => 'addRound'), array('class' => 'btn btn-success'));
			?>
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
						<?php 
							if(AuthComponent::user('role') === 'admin')
								echo $this->Html->link('Add Match', array('controller' => 'leagues', 'action' => 'addMatch', $details['League']['id'], $round['id']), array('class' => 'btn btn-success'));
						?>
						<?php foreach($round['Match'] as $match) { ?>
							<div class="panel panel-info">
								<div class="panel-heading">
									<h4 class="panel-title">
										Match <?= $match['match']; ?>
									</h4>
								</div>
								<div class="panel-body">
									<?php
										if(AuthComponent::user('role') === 'admin') {
											echo $this->Form->create('Match', array('url' => array('controller' => 'leagues', 'action' => 'editMatch', $match['id'])));
											echo $this->Form->hidden('Match.id', array('value' => $match['id']));
											echo $this->Form->hidden('Match.round_id', array('value' => $match['round_id']));
										}
									?>
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
														if(AuthComponent::user('role') === 'admin') {
															echo $this->Form->input('team_1_id', array('type' => 'select', 'options' => $teams, 'empty' => 'Select a team', 'selected' => $match['team_1_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
														} else {
															echo (is_null($match['team_1_id'])) ? "TBD" : $this->Html->link($teams[$match['team_1_id']], array('controller' => 'teams', 'action' => 'view', $match['team_1_id']), array('class' => 'btn btn-block btn-info'));
														}
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
														if(AuthComponent::user('role') === 'admin') {
															echo $this->Form->input('team_2_id', array('type' => 'select', 'options' => $teams, 'empty' => 'Select a team', 'selected' => $match['team_2_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
														} else {
															echo (is_null($match['team_2_id'])) ? "TBD" : $this->Html->link($teams[$match['team_2_id']], array('controller' => 'teams', 'action' => 'view', $match['team_2_id']), array('class' => 'btn btn-block btn-info'));
														}
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
									<?php
										if(AuthComponent::user('role') === 'admin') {
											echo $this->Form->end(array('value' => 'Submit', 'class' => 'btn btn-warning'));
										}
									?>
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