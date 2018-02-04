
<?php if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID'))): ?>
<div class="well well-lg">
	<h3 class="text-danger">IMPORTANT</h3>
	<p class="lead">
		Matches MUST be configured on the standings page before they can be applied here.
		In the dropdown, teams are listed in order of RED v GREEN.  Be sure to choose the 
		appropriate game number based on that.
	</p>	
</div>
<?php endif; ?>
<?php
	if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID'))) {
		echo $this->Form->create('Game', array(
			'class' => 'form-horizontal', 
			'role' => 'form',
			'inputDefaults' => array(
			    'format' => array('before', 'label', 'between', 'input', 'error', 'after'),
			    'div' => array('class' => 'form-group'),
			    'class' => array('form-control'),
			    'label' => array('class' => 'col-lg-2 control-label'),
			    'between' => '<div class="col-lg-3">',
			    'after' => '</div>',
			    'error' => array('attributes' => array('wrap' => 'span', 'class' => 'help-inline')),
		)));
		echo $this->Form->input('id');
		if(isset($game['Game']['league_id'])) {
			$match_list = array();
			foreach($available_matches['Round'] as $round) {
				foreach($round['Match'] as $match) {
					if(empty($match['Game_1']) || $match['Game_1']['id'] == $game['Game']['id'])
						$match_list[$match['id']."|1"] = "R{$round['round']} M{$match['match']} G1 - {$match['Team_1']['name']} v {$match['Team_2']['name']}";
					
					if(empty($match['Game_2']) || $match['Game_2']['id'] == $game['Game']['id'])
						$match_list[$match['id']."|2"] = "R{$round['round']} M{$match['match']} G2 - {$match['Team_2']['name']} v {$match['Team_1']['name']}";
				}
			}
			echo $this->Form->input('league_id', array('type' => 'hidden'));
			echo $this->Form->input('match', array(
				'type' => 'select', 
				'options' => array($match_list),
				'empty' => 'Select a match/game',
				'selected' => $game['Game']['match_id']."|".$game['Game']['league_game'],
			));
		} else {
			echo $this->Form->input('game_name');
		}
		
		echo $this->Form->end(array('value' => 'Update', 'class' => 'btn btn-warning'));
		echo $this->Html->link("Delete Game", array('controller' => 'Games', 'action' => 'delete', $game['Game']['id']), array('class' => 'btn btn-danger'), __('ARE YOU VERY SURE YOU WANT TO DELETE # %s?  THIS WILL DELETE ALL ASSOCIATED SCORECARDS!!!', $game['Game']['id']));
	}
?>
<h3 class="row">
	<div class="col-6 col-md-4 text-center text-md-left order-2 order-md-1">
	<?php if(!empty($neighbors['prev'])): ?>
		<?= $this->Html->link("<i class=\"fas fa-step-backward\"></i> Previous Game", array('controller' => 'games', 'action' => 'view', $neighbors['prev']['Game']['game_id']), array('class' => 'btn btn-primary', 'escape' => false)); ?>
	<?php endif; ?>
	</div>
	<div class="col-12 col-md-4 text-center order-1 order-md-2">
	<h3><?= $game['Game']['game_name'];?> <small><?= $game['Game']['game_datetime']; ?></small></h3>
	<a class="text-danger" href="<?= $game['Game']['red_team_link']; ?>"><?= $game['Game']['red_team_name']; ?></a> vs <a class="text-success" href="<?= $game['Game']['green_team_link']; ?>"><?= $game['Game']['green_team_name']; ?></a>
	<a href="<?= $game['Game']['pdf_link'];?>"><i class="far fa-file-pdf"></i></a>
	</div>
	<div class="col-6 col-md-4 text-right text-md-right order-3 order-md-3">
	<?php if(!empty($neighbors['next'])): ?>
		<?= $this->Html->link("Next Game <i class=\"fas fa-step-forward\"></i> ", array('controller' => 'games', 'action' => 'view', $neighbors['next']['Game']['game_id']), array('class' => 'btn btn-primary', 'escape' => false)); ?></span>
	<?php endif; ?>
	</div>
</h3>
<?php
	$red_team = array(
		'scorecards' => $game['Red_Scorecard'],
		'bg-class' => 'bg-danger',
		'score' => $game['Game']['red_score']+$game['Game']['red_adj'],
		'adj' => ($game['Game']['red_adj'] > 0) ? "({$game['Game']['red_adj']})" : "",
		'team_name' => $game['Game']['red_team_name'],
		'team_link' => $game['Game']['red_team_link']
	);
	$green_team = array(
		'scorecards' => $game['Green_Scorecard'],
		'bg-class' => 'bg-success',
		'score' => $game['Game']['green_score']+$game['Game']['green_adj'],
		'adj' => ($game['Game']['green_adj'] > 0) ? "({$game['Game']['green_adj']})" : "",
		'team_name' => $game['Game']['green_team_name'],
		'team_link' => $game['Game']['green_team_link']
	);
	if($game['Game']['winner'] == 'red') {
		$winner = $red_team;
		$loser = $green_team;
	} else {
		$winner = $green_team;
		$loser = $red_team;
	}
?>
	<div class="h5 text-white <?= $winner['bg-class']; ?>"><?= $winner['team_name']; ?></div>
		<div class="h5 text-primary"><?= "Score: ".$winner['score'].$winner['adj']; ?></div>
	<table class="table table-bordered table-hover table-sm dt-responsive" id="winner_table">
		<thead>
			<th>Name</th>
			<th>Position</th>
			<th>Score</th>
			<th>MVP Points</th>
			<th>Lives Left</th>
			<th>Shots Left</th>
			<th>Hit Diff</th>
			<th>Missiled</th>
			<th>Got Missiled</th>
			<th>Medic Hits</th>
			<th>Shot Team</th>
			<th>Missiled Team</th>
			<th>Accuracy</th>
			<th>SP Spent/Earned</th>
			<th>Nukes</th>
			<th>Nuke Cancels</th>
			<th>Boosts</th>
			<th>Resupplies</th>
			<th>Penalties</th>
		</thead>
		<tbody>
		</tbody>
	</table>

<div class="card">
	<div class="h5 card-header text-white <?= $loser['bg-class']; ?>"><?= $loser['team_name']; ?></div>
	<div class="card-body">
		<div class="h5 text-primary card-title"><?= "Score: ".$loser['score'].$loser['adj']; ?></div>
	</div>
	<table class="table table-bordered table-hover table-sm dt-responsive" id="loser_table">
		<thead>
			<th>Alive</th>
			<th>Name</th>
			<th>Position</th>
			<th>Score</th>
			<th>MVP Points</th>
			<th>Lives Left</th>
			<th>Shots Left</th>
			<th>Hit Diff</th>
			<th>Missiled</th>
			<th>Got Missiled</th>
			<th>Medic Hits</th>
			<th>Shot Team</th>
			<th>Missiled Team</th>
			<th>Accuracy</th>
			<th>SP Spent/Earned</th>
			<th>Nukes</th>
			<th>Nuke Cancels</th>
			<th>Boosts</th>
			<th>Resupplies</th>
			<th>Penalties</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var gameData = <?= json_encode($game, JSON_NUMERIC_CHECK,JSON_FORCE_OBJECT); ?>;
		console.log(gameData);
		var winnerData = (gameData.winner === 'red') ? gameData.Red_Scorecard : gameData.Green_Scorecard;
		var loserData = (gameData.winner === 'red') ? gameData.Green_Scorecard : gameData.Red_Scorecard;

		var winnerTable = $('#winner_table').DataTable({
			ordering: false,
			paging: false,
			searching: false,
			info: false,
			responsive: {
				details: {
					renderer: $.fn.dataTable.Responsive.renderer.tableAll( {
						tableClass: 'table'
					} )
				}
			},
			data: winnerData,
			createdRow: function(row, data, dataIndex) {
				if(row.lives_left <= 0) {
					$(row).addClass('bg-secondary');
				}
			},
			columns: [
				{
					data: "player_name"
				},
				{
					data: "position"
				},
				{
					data: "score"
				}
			]
		});
		
		$('.switch_sub_cbox').change(function() {
			$.ajax({
				url: "/scorecards/ajax_switchSub/" + $(this).prop('id') + ".json"
			}).done(function(data) {
				toastr.success('Successfully set Merc status');
			}).fail(function(data) {
				toastr.error('Failed to set Merc status')
			});
		});
	});
</script>




















<?php
	$winner_table = "";
	$loser_table = "";



	foreach ($game['Scorecard'] as $score) {
		$score_line = "";
		$penalty_score = 0;
		$penalty_string = "";
		
		if(isset($score['Penalty'])) {
			foreach ($score['Penalty'] as $penalty) {
				$penalty_score += $penalty['value'];
				$penalty_string .= "<button type=\"button\" class=\"btn btn-warning btn-block\" data-toggle=\"modal\" data-target=\"#penaltyModal\" target=\"".$this->Html->url(array('controller' => 'Penalties', 'action' => 'getPenalty', $penalty['id'], 'ext' => 'json'))."\">".$penalty['type']."</button>";
			}
		}
		
		$score_line .= "<tr class=\"text-center\">";
		
		if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $game['Game']['center_id'])) {
			$score_line .= "<td><form><input type=\"checkbox\" class=\"switch_sub_cbox\" id=".$score['id']." ".(($score['is_sub']) ? "checked" : "")."></form></td>";
		} else {
			$score_line .= (($score['is_sub']) ? "<td class=\"text-warning\"><i class=\"fas fa-certificate\"></i></td>" : "<td></td>");
		}
		
		$score_line .= (($score['lives_left'] > 0) ? "<td class=\"text-success\"><i data-toggle=\"tooltip\" title=\"Alive\" class=\"fas fa-check-square\"></i>" : "<td class=\"text-danger text-center\"><i data-toggle=\"tooltip\" title=\"Dead\" class=\"fas fa-times-circle\"></i>")."</td>";
		$score_line .= "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
		$score_line .= "<td>".$score['position']."</td>";
		$score_line .= "<td>".($score['score']+$penalty_score).(($penalty_score != 0) ? " ($penalty_score)" : "")."</td>";
		$score_line .= "<td><a href=\"#\" data-toggle=\"modal\" data-target=\"#mvpModal\" target=\"".$this->Html->url(array('controller' => 'scorecards', 'action' => 'getMVPBreakdown', $score['id'], 'ext' => 'json'))."\">".$score['mvp_points']."</a></td>";
		$score_line .= "<td>".$score['lives_left']."</td>";
		$score_line .= "<td>".$score['shots_left']."</td>";
		$score_line .= "<td><a href=\"#\" data-toggle=\"modal\" data-target=\"#hitModal\" target=\"".$this->Html->url(array('controller' => 'scorecards', 'action' => 'getHitBreakdown', $score['player_id'], $score['game_id'], 'ext' => 'json'))."\">".round($score['shot_opponent']/max($score['times_zapped'],1),2)." (".$score['shot_opponent']."/".$score['times_zapped'].")</a></td>";
		$score_line .= "<td>".$score['missiled_opponent']."</td>";
		$score_line .= "<td>".$score['times_missiled']."</td>";
		$score_line .= "<td>".$score['medic_hits'].($score['position'] == 'Commander' ? "/".$score['medic_nukes'] : "")."</td>";
		$score_line .= "<td>".$score['shot_team']."</td>";
		$score_line .= "<td>".$score['missiled_team']."</td>";
		$score_line .= "<td>".round($score['accuracy']*100,2)."%</td>";
		$score_line .= "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' || $score['position'] == 'Commander' ? $score['sp_spent']."/".$score['sp_earned'] : "-")."</td>";
		$score_line .= "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
		$score_line .= "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
		$score_line .= "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
		$score_line .= "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
		$score_line .= "<td>$penalty_string";
		if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $game['Game']['center_id'])) {
			$score_line.= $this->Html->link("Add", array('controller' => 'Penalties', 'action' => 'add', $score['id']), array('class' => 'btn btn-warning'));
		}
		$score_line .= "</td></tr>";
		
		if($score['team'] == $game['Game']['winner'])
			$winner_table .= $score_line;
		else
			$loser_table .= $score_line;
	}	
?>