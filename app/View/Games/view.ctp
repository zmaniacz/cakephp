<script type="text/javascript">
	$(document).ready(function() {
		$('.gamelist').DataTable( {
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false
		} );
	} );
</script>
<?php if(AuthComponent::user('role') === 'admin'): ?>
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
	if(AuthComponent::user('role') === 'admin') {
		echo $this->Form->create('Game');
		echo $this->Form->input('id');
		/*echo $this->Form->input('type', array('type' => 'select', 
			'options' => array(
				'social' => 'Social',
				'league' => 'League',
				'tournament' => 'Tournament'
			), 
			'class' => 'form-control', 
			'div' => array('class' => 'form-group'
		)));*/
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
				'class' => 'form-control', 
				'div' => array('class' => 'form-group')
			));
		}
	}
?>
<h3 class="text-center">
	<?php
		if(isset($game['Game']['league_id']) && !is_null($game['Match']['id'])) {
			echo 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'];
		} elseif(AuthComponent::user('role') === 'admin') {
			echo $this->Form->input('game_name', array('class' => 'form-control', 'div' => array('class' => 'form-group')));
		} else {
			echo $game['Game']['game_name'];
		}
		
		if(AuthComponent::user('role') === 'admin') {
			echo $this->Form->end(array('value' => 'Submit', 'class' => 'btn btn-warning'));
			echo $this->Html->link("Delete Game", array('controller' => 'Games', 'action' => 'delete', $game['Game']['id']), array('class' => 'btn btn-danger'), __('ARE YOU VERY SURE YOU WANT TO DELETE # %s?  THIS WILL DELETE ALL ASSOCIATED SCORECARDS!!!', $game['Game']['id']));
		}
	?>
</h3>
<h3 class="row">
	<span class="col-md-4">
	<?php if(!empty($neighbors['prev'])): ?>
		<?= $this->Html->link("<span class=\"glyphicon glyphicon-backward\"></span> Previous Game", array('controller' => 'games', 'action' => 'view', $neighbors['prev']['LeagueGame']['game_id']), array('class' => 'btn btn-info', 'escape' => false)); ?>
	<?php endif; ?>
	</span>
	<span class="col-md-4 text-center">
	<?php
		if($game['Game']['red_team_id'] != null)
			echo $this->Html->link($teams[$game['Game']['red_team_id']], array('controller' => 'teams', 'action' => 'view', $game['Game']['red_team_id']), array('class' => 'btn btn-danger'));
		else
			echo "Red Team";

		echo " vs ";

		if($game['Game']['green_team_id'] != null)
			echo $this->Html->link($teams[$game['Game']['green_team_id']], array('controller' => 'teams', 'action' => 'view', $game['Game']['green_team_id']), array('class' => 'btn btn-success'));
		else
			echo "Green Team";

		if (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) {
			echo " - ".$this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf");
		} elseif (file_exists(WWW_ROOT."/pdf/".$game['Game']['pdf_id'].".pdf")) {
			echo " - ".$this->Html->link("PDF", "/pdf/".$game['Game']['pdf_id'].".pdf");
		}
	?>
	</span>
	<span class="col-md-4 text-right">
	<?php if(!empty($neighbors['next'])): ?>
		<?= $this->Html->link("Next Game <span class=\"glyphicon glyphicon-forward\"></span> ", array('controller' => 'games', 'action' => 'view', $neighbors['next']['LeagueGame']['game_id']), array('class' => 'btn btn-info', 'escape' => false)); ?>
	<?php endif; ?>
	</span>
</h3>
<hr>
<div class="well well-sm">Numbers in parentheses are score adjustments due to penalties and elimination bonuses</div>
<?php 
	if($game['Game']['winner'] == 'Green') {
		$winner = (($game['Game']['green_team_id'] != null) ? $teams[$game['Game']['green_team_id']] : "Green Team");
		$winner_panel = "panel panel-success";
		$winner_score = ($game['Game']['green_score']+$game['Game']['green_adj']);
		$winner_adj = "";
		if($game['Game']['green_adj'] != 0)
			$winner_adj = " (".$game['Game']['green_adj'].")";
			
		$loser = (($game['Game']['red_team_id'] != null) ? $teams[$game['Game']['red_team_id']] : "Red Team");
		$loser_panel = "panel panel-danger";
		$loser_score = ($game['Game']['red_score']+$game['Game']['red_adj']);
		$loser_adj = "";
		if($game['Game']['red_adj'] != 0)
			$loser_adj = " (".$game['Game']['red_adj'].")";
	} else {
		$winner = (($game['Game']['red_team_id'] != null) ? $teams[$game['Game']['red_team_id']] : "Red Team");
		$winner_panel = "panel panel-danger";
		$winner_score = ($game['Game']['red_score']+$game['Game']['red_adj']);
		$winner_adj = "";
		if($game['Game']['red_adj'] != 0)
			$winner_adj = " (".$game['Game']['red_adj'].")";
		
		$loser = (($game['Game']['green_team_id'] != null) ? $teams[$game['Game']['green_team_id']] : "Green Team");
		$loser_panel = "panel panel-success";
		$loser_score = ($game['Game']['green_score']+$game['Game']['green_adj']);
		$loser_adj = "";
		if($game['Game']['green_adj'] != 0)
			$loser_adj = " (".$game['Game']['green_adj'].")"; 
	}
	
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
		
		if(AuthComponent::user('role') === 'admin') {
			$score_line .= "<td><form><input type=\"checkbox\" class=\"switch_sub_cbox\" id=".$score['id']." ".(($score['is_sub']) ? "checked" : "")."></form></td>";
		} else {
			$score_line .= (($score['is_sub']) ? "<td class=\"text-warning\"><span class=\"glyphicon glyphicon-asterisk\"></span></td>" : "<td></td>");
		}
		
		$score_line .= (($score['lives_left'] > 0) ? "<td class=\"text-success\"><span class=\"glyphicon glyphicon-ok\"></span>" : "<td class=\"text-danger text-center\"><span class=\"glyphicon glyphicon-remove\"></span>")."</td>";
		$score_line .= "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']), array('class' => 'btn btn-info btn-block'))."</td>";
		$score_line .= "<td>".$score['position']."</td>";
		$score_line .= "<td>".($score['score']+$penalty_score).(($penalty_score != 0) ? " ($penalty_score)" : "")."</td>";
		$score_line .= "<td><button type=\"button\" class=\"btn btn-info btn-block\" data-toggle=\"modal\" data-target=\"#mvpModal\" target=\"".$this->Html->url(array('controller' => 'scorecards', 'action' => 'getMVPBreakdown', $score['id'], 'ext' => 'json'))."\">".$score['mvp_points']."</button></td>";
		$score_line .= "<td>".$score['lives_left']."</td>";
		$score_line .= "<td>".$score['shots_left']."</td>";
		$score_line .= "<td>".$score['shot_opponent']."</td>";
		$score_line .= "<td>".$score['times_zapped']."</td>";
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
		if(AuthComponent::user('role') === 'admin') {
			$score_line.= $this->Html->link("Add", array('controller' => 'Penalties', 'action' => 'add', $score['id']), array('class' => 'btn btn-warning'));
		}
		$score_line .= "</td></tr>";
		
		if($score['team'] == $game['Game']['winner'])
			$winner_table .= $score_line;
		else
			$loser_table .= $score_line;
	}	
?>
<div id="winner_panel" class="<?= $winner_panel; ?>">
	<div class="panel-heading" data-toggle="collapse" data-parent="#winner_panel" data-target="#collapse_winner_panel" role="tab" id="winner_panel_heading">
		<h3 class="panel-title">
			<?= $winner; ?>
		</h3>
	</div>
	<div id="collapse_winner_panel" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<h3 class="text-info"><?= "Score: ".$winner_score.$winner_adj; ?></h3>
		</div>
		<div class="table-responsive">
			<table class="gamelist table table-striped table-bordered table-hover table-condensed">
				<thead>
					<th>Merc</th>
					<th>Alive</th>
					<th>Name</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
					<th>Lives Left</th>
					<th>Shots Left</th>
					<th>Shot Opponent</th>
					<th>Got Shot</th>
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
					<?= $winner_table; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="loser_panel" class="<?= $loser_panel; ?>">
	<div class="panel-heading" data-toggle="collapse" data-parent="#loser_panel" data-target="#collapse_loser_panel" role="tab" id="loser_panel_heading">
		<h3 class="panel-title">
			<?= $loser; ?>
		</h3>
	</div>
	<div id="collapse_loser_panel" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<h3 class="text-info"><?= "Score: ".$loser_score.$loser_adj; ?></h3>
		</div>
		<div class="table-responsive">
			<table class="gamelist table table-striped table-bordered table-hover table-condensed">
				<thead>
					<th>Merc</th>
					<th>Alive</th>
					<th>Name</th>
					<th>Position</th>
					<th>Score</th>
					<th>MVP Points</th>
					<th>Lives Left</th>
					<th>Shots Left</th>
					<th>Shot Opponent</th>
					<th>Got Shot</th>
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
					<?= $loser_table; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal fade" id="mvpModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mvpModalLabel">MVP Details</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="penaltyModal" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="penaltyModalLabel">Penalty Details</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
	$('#mvpModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		$(this).find(".modal-body").text("Loading...");
		$(this).find(".modal-body").load(button.attr("target"));
	});
	
	$('#penaltyModal').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget);
		$(this).find(".modal-body").text("Loading...");
		$(this).find(".modal-body").load(button.attr("target"));
	});

$('.switch_sub_cbox').change(function() {
	$.ajax({
		url: "/scorecards/ajax_switchSub/" + $(this).prop('id') + ".json"
	});
});
</script>