<script type="text/javascript">
	$(document).ready(function() {
		var gTable = $('.gamelist').dataTable( {
			"bAutoWidth": false,
			"bFilter": false,
			"bInfo": false,
			"bPaginate": false,
			"bSort": false,
			"bJQueryUI": true,
			"bRetrieve": true,
			"sScrollX": "100%"
		} );
	} );
</script>
<h3><?php echo $game['Game']['game_name']." ".$game['Game']['game_datetime']." "; echo (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) ? $this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf") : ""; ?></h3>
<div>
	<div style="font-size: 150%">Total Score: <?php echo ($game['Game']['winner'] == 'Green') ? $game['Game']['green_total_score'] : $game['Game']['red_total_score']; ?></div>
	<div style="font-size: 120%">Raw Score: <?php echo ($game['Game']['winner'] == 'Green') ? $game['Game']['green_score'] : $game['Game']['red_score']; ?></div>
	<div style="font-size: 120%">Elim Bonus: +<?php echo ($game['Game']['winner'] == 'Green') ? $game['Game']['green_elim_bonus'] : $game['Game']['red_elim_bonus']; ?></div>
	<div style="font-size: 120%">Penalties: -<?php echo ($game['Game']['winner'] == 'Green') ? $game['Game']['green_penalties'] : $game['Game']['red_penalties']; ?></div>
	<table class="gamelist">
		<thead>
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
			<th>Accuracy</th>
			<th>Nukes</th>
			<th>Nuke Cancels</th>
			<th>Boosts</th>
			<th>Resupplies</th>
		</thead>
		<tbody>
			<?php foreach ($game['Scorecard'] as $score) {
				if($score['team'] != $game['Game']['winner'])
					continue;
				
				if($score['lives_left'] == 0)
					echo "<tr class='gameRowDead'>";
				else
					echo "<tr class='gameRow".$score['team']."'>";
					
				echo "<td>".$score['rank']."</td>";
				echo "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
				echo "<td>".$score['position']."</td>";
				echo "<td>".$score['score']."</td>";
				echo "<td>".$score['mvp_points']."</td>";
				echo "<td>".$score['lives_left']."</td>";
				echo "<td>".$score['shots_left']."</td>";
				echo "<td>".$score['shot_opponent']."</td>";
				echo "<td>".$score['times_zapped']."</td>";
				echo "<td>".$score['missile_hits']."</td>";
				echo "<td>".$score['times_missiled']."</td>";
				echo "<td>".$score['medic_hits'].($score['position'] == 'Commander' ? "/".$score['medic_nukes'] : "")."</td>";
				echo "<td>".$score['shot_team']."</td>";
				echo "<td>".round($score['accuracy']*100,2)."%</td>";
				echo "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
				echo "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
				echo "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
				echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>
<br />
<br />
<div>
	<div style="font-size: 150%">Total Score: <?php echo ($game['Game']['winner'] == 'Red') ? $game['Game']['green_total_score'] : $game['Game']['red_total_score']; ?></div>
	<div style="font-size: 120%">Raw Score: <?php echo ($game['Game']['winner'] == 'Red') ? $game['Game']['green_score'] : $game['Game']['red_score']; ?></div>
	<div style="font-size: 120%">Elim Bonus: +<?php echo ($game['Game']['winner'] == 'Red') ? $game['Game']['green_elim_bonus'] : $game['Game']['red_elim_bonus']; ?></div>
	<div style="font-size: 120%">Penalties: -<?php echo ($game['Game']['winner'] == 'Red') ? $game['Game']['green_penalties'] : $game['Game']['red_penalties']; ?></div>
	<table class="gamelist">
		<thead>
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
			<th>Accuracy</th>
			<th>Nukes</th>
			<th>Nuke Cancels</th>
			<th>Boosts</th>
			<th>Resupplies</th>
		</thead>
		<tbody>
			<?php foreach ($game['Scorecard'] as $score) {
				if($score['team'] == $game['Game']['winner'])
					continue;
					
				if($score['lives_left'] == 0)
					echo "<tr class='gameRowDead'>";
				else
					echo "<tr class='gameRow".$score['team']."'>";

				echo "<td>".$score['rank']."</td>";
				echo "<td>".$this->Html->link($score['player_name'], array('controller' => 'Players', 'action' => 'view', $score['player_id']))."</td>";
				echo "<td>".$score['position']."</td>";
				echo "<td>".$score['score']."</td>";
				echo "<td>".$score['mvp_points']."</td>";
				echo "<td>".$score['lives_left']."</td>";
				echo "<td>".$score['shots_left']."</td>";
				echo "<td>".$score['shot_opponent']."</td>";
				echo "<td>".$score['times_zapped']."</td>";
				echo "<td>".$score['missile_hits']."</td>";
				echo "<td>".$score['times_missiled']."</td>";
				echo "<td>".$score['medic_hits'].($score['position'] == 'Commander' ? "/".$score['medic_nukes'] : "")."</td>";
				echo "<td>".$score['shot_team']."</td>";
				echo "<td>".round($score['accuracy']*100,2)."%</td>";
				echo "<td>".($score['position'] == 'Commander' ? $score['nukes_detonated']."/".$score['nukes_activated'] : "-")."</td>";
				echo "<td>".($score['nukes_canceled'] > 0 ? $score['nukes_canceled'] : "-")."</td>";
				echo "<td>".($score['position'] == 'Medic' ? $score['life_boost'] : ($score['position'] == 'Ammo Carrier' ? $score['ammo_boost'] : "-"))."</td>";
				echo "<td>".($score['position'] == 'Medic' || $score['position'] == 'Ammo Carrier' ? $score['resupplies'] : "-")."</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>