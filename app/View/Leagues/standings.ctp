<div id="top_accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#top_accordion" data-target="#collapse_standings" role="tab" id="standings_heading">
		<h4 class="panel-title">
			Team Standings
		</h4>
	</div>
	<div id="collapse_standings" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<?php 
				if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID')))
					echo $this->Html->link('New Team', array('controller' => 'leagues', 'action' => 'addTeam'), array('class' => 'btn btn-success'));
			?>
		</div>
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover table-condensed" id="team_standings">
				<thead>
					<th class="col-xs-2">Team</th>
					<th class="col-xs-1">Points</th>
					<th class="col-xs-1">Matches Won/Played</th>
					<th class="col-xs-1">Games Won/Played</th>
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
						<td><?= $team['matches_won']; ?> / <?= $team['matches_played']; ?></td>
						<td><?= $team['won']; ?> / <?= $team['played']; ?></td>
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
<ul class="nav nav-tabs" id="round_tabs">
	<?php foreach($details['Round'] as $round): ?>
		<li>
			<a href="#round<?= $round['id']; ?>" data-toggle="tab">
				<?= ($round['is_finals']) ? "Finals" : "Round ".$round['round']; ?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
<div id="accordion" class="panel panel-info">
	<div class="panel-heading" data-toggle="collapse" data-parent="#accordion" data-target="#collapse_rounds" role="tab" id="rounds_heading">
		<h4 class="panel-title">
			Rounds
		</h4>
	</div>
	<div id="collapse_rounds" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<?php 
				if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID')))
					echo $this->Html->link('Add Round', array('controller' => 'leagues', 'action' => 'addRound'), array('class' => 'btn btn-success'));
			?>
			<div class="tab-content">
				<?php foreach($details['Round'] as $round) { ?>
					<div class="tab-pane" id="round<?= $round['id']; ?>">
						<?php 
							if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID')))
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
								</div>
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
							</div>
						<?php } ?>
					</div>
				<?php } ?>	
			</div>
		</div>
	</div>
</div>
<script>
	$('.match-select').change(function() {
		toastr.options = {
			"closeButton": false,
			"debug": false,
			"newestOnTop": false,
			"progressBar": false,
			"positionClass": "toast-top-full-width",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "3000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "slideDown",
			"hideMethod": "slideUp"
		}
		$.ajax({
			url: "/leagues/ajax_assignTeam/"+$(this).data('matchId')+"/"+$(this).data('team')+"/"+$(this).val()+".json",
			success: function(data) {
				toastr.success('Assigned Team')
			},
			error: function(data) {
				toastr.error('Assignment Failed')
			}
		});
	});
	$('#round_tabs a:first').tab('show')
</script>