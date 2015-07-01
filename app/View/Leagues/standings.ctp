<script type="text/javascript">
	$(document).ready(function() {
		$('#team_standings').DataTable( {
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
		});
	});
</script>
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
			<table class="table table-striped table-bordered table-hover table-condensed" id="team_standings">
				<thead>
					<th>Team</th>
					<th>Points</th>
					<th>Played</th>
					<th>Won</th>
					<th>Lost</th>
					<th>Match</th>
					<th>Eliminations</th>
					<th>For</th>
					<th>Against</th>
					<th>Ratio</th>
				</thead>
				<tbody>
					<?php foreach($standings as $team): ?>
					<tr>
						<td><?= $team['name']; ?></td>
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
									<table class="table table-striped table-bordered table-hover table-condensed" id="match<?= $match['id']; ?>">
										<thead>
											<th>Team</th>
											<th>Points</th>
											<th>Game 1 Score</th>
											<th>Game 2 Score</th>
											<th>Total</th>
										</thead>
										<tbody>
											<tr>
												<td>
												<?php
													if(AuthComponent::user('role') === 'admin') {
														echo $this->Form->input('team_1_id', array('type' => 'select', 'options' => $teams, 'empty' => 'Select a team', 'selected' => $match['team_1_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
													} else {
														echo (is_null($match['team_1_id'])) ? "TBD" : $teams[$match['team_1_id']];;
													}
												?>
												</td>
												<td class="text-center"><?= $match['team_1_points']; ?></td>
												<td class="danger text-center"><?= (!empty($match['Game_1'])) ? $this->Html->link($match['Game_1']['red_score'] + $match['Game_1']['red_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_1']['id'])) : ""; ?></td>
												<td class="success text-center"><?= (!empty($match['Game_2'])) ? $this->Html->link($match['Game_2']['green_score'] + $match['Game_2']['green_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_2']['id'])) : ""; ?></td>
												<td><?= (!empty($match['Game_1']) && !empty($match['Game_2'])) ? $match['Game_1']['red_score'] + $match['Game_1']['red_adj'] + $match['Game_2']['green_score'] + $match['Game_2']['green_adj'] : ""; ?></td>
											</tr>
											<tr>
												<td>
												<?php
													if(AuthComponent::user('role') === 'admin') {
														echo $this->Form->input('team_2_id', array('type' => 'select', 'options' => $teams, 'empty' => 'Select a team', 'selected' => $match['team_2_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
													} else {
														echo (is_null($match['team_2_id'])) ? "TBD" : $teams[$match['team_2_id']];;
													}
												?>
												</td>
												<td class="text-center"><?= $match['team_2_points']; ?></td>
												<td class="success text-center"><?= (!empty($match['Game_1'])) ? $this->Html->link($match['Game_1']['green_score'] + $match['Game_1']['green_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_1']['id'])) : ""; ?></td>
												<td class="danger text-center"><?= (!empty($match['Game_2'])) ? $this->Html->link($match['Game_2']['red_score'] + $match['Game_2']['red_adj'], array('controller' => 'Games', 'action' => 'view', $match['Game_2']['id'])) : ""; ?></td>
												<td class="text-center"><?= (!empty($match['Game_1']) && !empty($match['Game_2'])) ? $match['Game_1']['green_score'] + $match['Game_1']['green_adj'] + $match['Game_2']['red_score'] + $match['Game_2']['red_adj'] : ""; ?></td>
											</tr>
										</tbody>
									</table>
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