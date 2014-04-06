<script type="text/javascript">
	$(document).ready(function() {
		var gTable = $('.gamelist').dataTable( {
			"bAutoWidth": false,
			"bFilter": false,
			"bInfo": false,
			"bPaginate": false,
			"bSort": false,
			"bJQueryUI": true,
			"bRetrieve": true
		} );
	} );
</script>
<h3><?php echo $game['Game']['game_name']." ".$game['Game']['game_datetime']." "; echo (file_exists(WWW_ROOT."/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf")) ? $this->Html->link("PDF", "/pdf/LTC_SM5".$game['Game']['game_name']."_".date("Y-m-d_Hi",strtotime($game['Game']['game_datetime'])).".pdf") : ""; ?></h3>
<div>
	<h1>Score: <?php echo ($game['Game']['winner'] == 'Green') ? $game['Game']['green_score'] : $game['Game']['red_score']; ?></h1>
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
			<th>Accuracy</th>
			<th>MVP Points</th>
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
				echo "<td>".$score['lives_left']."</td>";
				echo "<td>".$score['shots_left']."</td>";
				echo "<td>".$score['shot_opponent']."</td>";
				echo "<td>".$score['times_zapped']."</td>";
				echo "<td>".$score['missile_hits']."</td>";
				echo "<td>".$score['times_missiled']."</td>";
				echo "<td>".$score['medic_hits']."</td>";
				echo "<td>".$score['shot_team']."</td>";
				echo "<td>".round($score['accuracy']*100,2)."%</td>";
				echo "<td>".$score['mvp_points']."</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>
<br />
<br />
<div>
	<h1>Score: <?php echo ($game['Game']['winner'] == 'Green') ? $game['Game']['red_score'] : $game['Game']['green_score']; ?></h1>
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
			<th>Accuracy</th>
			<th>MVP Points</th>
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
				echo "<td>".$score['lives_left']."</td>";
				echo "<td>".$score['shots_left']."</td>";
				echo "<td>".$score['shot_opponent']."</td>";
				echo "<td>".$score['times_zapped']."</td>";
				echo "<td>".$score['missile_hits']."</td>";
				echo "<td>".$score['times_missiled']."</td>";
				echo "<td>".$score['medic_hits']."</td>";
				echo "<td>".$score['shot_team']."</td>";
				echo "<td>".round($score['accuracy']*100,2)."%</td>";
				echo "<td>".$score['mvp_points']."</td>";
				echo "</tr>";
			}
			?>
		</tbody>
	</table>
</div>