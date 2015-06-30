<script type="text/javascript">
	$(document).ready(function() {
		var gTable = $('.gamelist').DataTable( {
			"autoWidth": false,
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"scrollX": true,
		} );
	} );
</script>
<p>Numbers in parentheses are score adjustments due to penalties and elimination bonuses</p>
<?php
	if(AuthComponent::user('role') === 'admin') {
		echo $this->Form->create('Game');
		echo $this->Form->input('id');
		if(isset($game['Game']['league_id'])) {
			echo $this->Form->input('league_round', array('class' => 'form-control', 'div' => array('class' => 'form-group')));
			echo $this->Form->input('league_match', array('class' => 'form-control', 'div' => array('class' => 'form-group')));
			echo $this->Form->input('league_game', array('class' => 'form-control', 'div' => array('class' => 'form-group')));
		}
	} else {
		if(isset($game['Game']['league_id']))
			echo '<h3>R'.$game['Game']['league_round'].' M'.$game['Game']['league_match'].' G'.$game['Game']['league_game']."</h3>";
	}
?>
<h3>
	<?php
		if(AuthComponent::user('role') === 'admin') {
			echo $this->Form->input('game_name', array('class' => 'form-control', 'div' => array('class' => 'form-group')));
		} else {
			if(!empty($game['Game']['game_name'])) {
				echo $game['Game']['game_name'];
			}
		}
	?>	
</h3>
<h3>
	<?php
		if($game['Game']['red_team_id'] != null)
			echo $teams[$game['Game']['red_team_id']];
		else
			echo "Red Team";

		echo " vs ";

		if($game['Game']['green_team_id'] != null)
			echo $teams[$game['Game']['green_team_id']];
		else
			echo "Green Team";

		if (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) {
			echo $this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf");
		} elseif (file_exists(WWW_ROOT."/pdf/".$game['Game']['pdf_id'].".pdf")) {
			echo $this->Html->link("PDF", "/pdf/".$game['Game']['pdf_id'].".pdf");
		}
		if(AuthComponent::user('role') === 'admin') {
			
			
		}
	?>
</h3>
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
?>
<div id="winner_panel" class="<?= $winner_panel; ?>">
	<div class="panel-heading" data-toggle="collapse" data-parent="#winner_panel" data-target="#collapse_winner_panel" role="tab" id="winner_panel_heading">
		<h4 class="panel-title">
			<?php
				if(AuthComponent::user('role') === 'admin' && isset($game['Game']['league_id'])) {
					if($game['Game']['winner'] == 'Green')
						echo $this->Form->input('green_team_id', array('type' => 'select', 'options' => $teams, 'selected' => $game['Game']['green_team_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
					else
						echo $this->Form->input('red_team_id', array('type' => 'select', 'options' => $teams, 'selected' => $game['Game']['red_team_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
				} else {echo $winner;}
			?>
		</h4>
	</div>
	<div id="collapse_winner_panel" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<h4><?= "Score: ".$winner_score.$winner_adj; ?></h4>
			<table class="gamelist table table-striped table-bordered table-hover table-condensed">
				<thead>
					<th>Merc</th>
					<th>Rank</th>
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
					<?php foreach ($game['Scorecard'] as $score) {
						if($score['team'] != $game['Game']['winner'])
							continue;
		
						$penalty_score = 0;
						$penalty_string = "";
		
						if(isset($score['Penalty'])) {
							foreach ($score['Penalty'] as $penalty) {
								$penalty_score += $penalty['value'];
								$penalty_string .= "<p>".$this->Html->link($penalty['type'], array('controller' => 'Penalties', 'action' => 'view', $penalty['id']))."</p>";
							}
						}
						
						if($score['lives_left'] > 0) {
							if($score['team'] == 'Red')
								echo "<tr class='danger'>";
							else
								echo "<tr class='success'>";
						}
							
						echo "<td><form><input type=\"checkbox\" class=\"switch_sub_cbox\" id=".$score['id']." ".(($score['is_sub']) ? "checked" : "")." ".((!(AuthComponent::user('role') === 'admin')) ? "disabled" : "")."></form></td>";
						echo "<td>".$score['rank']."</td>";
						echo "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
						echo "<td>".$score['position']."</td>";
						echo "<td>".($score['score']+$penalty_score).(($penalty_score != 0) ? " ($penalty_score)" : "")."</td>";
						echo "<td>".$score['mvp_points']."</td>";
						echo "<td>".$score['lives_left']."</td>";
						echo "<td>".$score['shots_left']."</td>";
						echo "<td>".$score['shot_opponent']."</td>";
						echo "<td>".$score['times_zapped']."</td>";
						echo "<td>".$score['missiled_opponent']."</td>";
						echo "<td>".$score['times_missiled']."</td>";
						echo "<td>".$score['medic_hits'].($score['position'] == 'Commander' ? "/".$score['medic_nukes'] : "")."</td>";
						echo "<td>".$score['shot_team']."</td>";
						echo "<td>".$score['missiled_team']."</td>";
						echo "<td>".round($score['accuracy']*100,2)."%</td>";
						echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' || $score['position'] == 'Commander' ? $score['sp_spent']."/".$score['sp_earned'] : "-")."</td>";
						echo "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
						echo "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
						echo "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
						echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
						echo "<td>$penalty_string";
						if(AuthComponent::user('role') === 'admin') {
							echo $this->Html->link("Add", array('controller' => 'Penalties', 'action' => 'add', $score['id']), array('class' => 'btn btn-warning'));
						}
						echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="loser_panel" class="<?= $loser_panel; ?>">
	<div class="panel-heading" data-toggle="collapse" data-parent="#loser_panel" data-target="#collapse_loser_panel" role="tab" id="loser_panel_heading">
		<h4 class="panel-title">
			<?php
				if(AuthComponent::user('role') === 'admin' && isset($game['Game']['league_id'])) {
					if($game['Game']['winner'] == 'Green')
						echo $this->Form->input('red_team_id', array('type' => 'select', 'options' => $teams, 'selected' => $game['Game']['red_team_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
					else
						echo $this->Form->input('green_team_id', array('type' => 'select', 'options' => $teams, 'selected' => $game['Game']['green_team_id'], 'class' => 'form-control', 'div' => array('class' => 'form-group')));
				} else {echo $loser;}
			?>
		</h4>
	</div>
	<div id="collapse_loser_panel" class="panel-collapse collapse in" role="tabpanel">
		<div class="panel-body">
			<h4><?= "Score: ".$loser_score.$loser_adj; ?></h4>
			<table class="gamelist table table-striped table-bordered table-hover table-condensed">
				<thead>
					<th>Merc</th>
					<th>Rank</th>
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
					<?php foreach ($game['Scorecard'] as $score) {
						if($score['team'] == $game['Game']['winner'])
							continue;
		
						$penalty_score = 0;
						$penalty_string = "";
		
						if(isset($score['Penalty'])) {
							foreach ($score['Penalty'] as $penalty) {
								$penalty_score += $penalty['value'];
								$penalty_string .= "<p>".$this->Html->link($penalty['type'], array('controller' => 'Penalties', 'action' => 'view', $penalty['id']))."</p>";
							}
						}
							
						if($score['lives_left'] > 0) {
							if($score['team'] == 'Red')
								echo "<tr class='danger'>";
							else
								echo "<tr class='success'>";
						}
		
						echo "<td><form><input type=\"checkbox\" class=\"switch_sub_cbox\" id=".$score['id']." ".(($score['is_sub']) ? "checked" : "")." ".((!(AuthComponent::user('role') === 'admin')) ? "disabled" : "")."></form></td>";
						echo "<td>".$score['rank']."</td>";
						echo "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
						echo "<td>".$score['position']."</td>";
						echo "<td>".($score['score']+$penalty_score).(($penalty_score != 0) ? " ($penalty_score)" : "")."</td>";
						echo "<td>".$score['mvp_points']."</td>";
						echo "<td>".$score['lives_left']."</td>";
						echo "<td>".$score['shots_left']."</td>";
						echo "<td>".$score['shot_opponent']."</td>";
						echo "<td>".$score['times_zapped']."</td>";
						echo "<td>".$score['missiled_opponent']."</td>";
						echo "<td>".$score['times_missiled']."</td>";
						echo "<td>".$score['medic_hits'].($score['position'] == 'Commander' ? "/".$score['medic_nukes'] : "")."</td>";
						echo "<td>".$score['shot_team']."</td>";
						echo "<td>".$score['missiled_team']."</td>";
						echo "<td>".round($score['accuracy']*100,2)."%</td>";
						echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' || $score['position'] == 'Commander' ? $score['sp_spent']."/".$score['sp_earned'] : "-")."</td>";
						echo "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
						echo "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
						echo "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
						echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
						echo "<td>$penalty_string";
						if(AuthComponent::user('role') === 'admin') {
							echo $this->Html->link("Add", array('controller' => 'Penalties', 'action' => 'add', $score['id']), array('class' => 'btn btn-warning'));
						}
						echo "</td>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
	if(AuthComponent::user('role') === 'admin') {
		echo $this->Form->end(array('value' => 'Submit', 'class' => 'btn btn-warning'));
		echo $this->Html->link("Delete", array('controller' => 'Games', 'action' => 'delete', $game['Game']['id']), array('class' => 'btn btn-danger'), __('ARE YOU VERY SURE YOU WANT TO DELETE # %s?  THIS WILL DELETE ALL ASSOCIATED SCORECARDS!!!', $game['Game']['id']));
	}
?>

<script>
$('.switch_sub_cbox').change(function() {
	$(this).closest('tr').toggleClass("sub", this.checked);
}).change();

$('.switch_sub_cbox').change(function() {
	$.ajax({
		url: "/Scorecards/ajax_switchSub/" + $(this).prop('id') + ".json"
	});
});
</script>