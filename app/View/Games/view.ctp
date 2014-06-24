<script type="text/javascript">
	$(document).ready(function() {
		var gTable = $('.gamelist').DataTable( {
			"autoWidth": false,
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false,
			"scrollX": true,
			"jQueryUI": true
		} );
	} );
</script>
<p>Numbers in parentheses are score adjustments due to penalties and elimination bonuses</p>
<h3><?php 
		echo $game['Game']['game_name']." ".$game['Game']['game_datetime']." "; 
		if (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) {
			echo $this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf");
		} elseif (file_exists(WWW_ROOT."/pdf/".$game['Game']['pdf_id'].".pdf")) {
			echo $this->Html->link("PDF", "/pdf/".$game['Game']['pdf_id'].".pdf");
		}
		if(AuthComponent::user('role') === 'admin') {
			echo "<button>".$this->Html->link("Edit", array('controller' => 'Games', 'action' => 'edit', $game['Game']['id']))."</button>";
		}
	?>
</h3>
<div>
	<h1>Score:
		<?php 
		if($game['Game']['winner'] == 'Green') {
			echo $game['Game']['green_score']+$game['Game']['green_adj'];
			if($game['Game']['green_adj'] != 0)
				echo " (".$game['Game']['green_adj'].")";
		} else {
			echo $game['Game']['red_score']+$game['Game']['red_adj'];
			if($game['Game']['red_adj'] != 0)
				echo " (".$game['Game']['red_adj'].")"; 
		}
		?>
	</h1>
	<table class="gamelist">
		<thead>
			<th>Rank</th>
			<th>Name</th>
			<th>Position</th>
			<th>Score</th>
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
			<th>Nukes</th>
			<th>Nuke Cancels</th>
			<th>Boosts</th>
			<th>Resupplies</th>
			<th>MVP Points</th>
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
				
				if($score['lives_left'] == 0)
					echo "<tr class='gameRowDead'>";
				else
					echo "<tr class='gameRow".$score['team']."'>";
					
				echo "<td>".$score['rank']."</td>";
				echo "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
				echo "<td>".$score['position']."</td>";
				echo "<td>".($score['score']+$penalty_score).(($penalty_score != 0) ? " ($penalty_score)" : "")."</td>";
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
				echo "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
				echo "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
				echo "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
				echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
				echo "<td>".$score['mvp_points']."</td>";
				echo "<td>$penalty_string";
				if(AuthComponent::user('role') === 'admin') {
					echo "<button>".$this->Html->link("Add", array('controller' => 'Penalties', 'action' => 'add', $score['id']))."</button>";
				}
				echo "</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>
<br />
<br />
<div>
	<h1>Score: 
		<?php 
		if($game['Game']['winner'] == 'Green') {
			echo $game['Game']['red_score']+$game['Game']['red_adj'];
			if($game['Game']['red_adj'] != 0)
				echo " (".$game['Game']['red_adj'].")";
		} else {
			echo $game['Game']['green_score']+$game['Game']['green_adj'];
			if($game['Game']['green_adj'] != 0)
				echo " (".$game['Game']['green_adj'].")"; 
		}
		?>
	</h1>
	<table class="gamelist">
		<thead>
			<th>Rank</th>
			<th>Name</th>
			<th>Position</th>
			<th>Score</th>
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
			<th>Nukes</th>
			<th>Nuke Cancels</th>
			<th>Boosts</th>
			<th>Resupplies</th>
			<th>MVP Points</th>
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
					
				if($score['lives_left'] == 0)
					echo "<tr class='gameRowDead'>";
				else
					echo "<tr class='gameRow".$score['team']."'>";

				echo "<td>".$score['rank']."</td>";
				echo "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
				echo "<td>".$score['position']."</td>";
				echo "<td>".($score['score']+$penalty_score).(($penalty_score != 0) ? " ($penalty_score)" : "")."</td>";
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
				echo "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
				echo "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
				echo "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
				echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
				echo "<td>".$score['mvp_points']."</td>";
				echo "<td>$penalty_string";
				if(AuthComponent::user('role') === 'admin') {
					echo "<button>".$this->Html->link("Add", array('controller' => 'Penalties', 'action' => 'add', $score['id']))."</button>";
				}
				echo "</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>