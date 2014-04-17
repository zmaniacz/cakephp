<h2>1st Team</h2>
<table>
	<thead>
		<th>Position</th>
		<th>Player</th>
		<th>Average MVP</th>
	</thead>
	<tbody>
		<tr>
			<td>Commander</td>
			<td><?php echo $top['team_a']['Commander']['player_name']; ?></td>
			<td><?php echo round($top['team_a']['Commander']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Heavy Weapons</td>
			<td><?php echo $top['team_a']['Heavy Weapons']['player_name']; ?></td>
			<td><?php echo round($top['team_a']['Heavy Weapons']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Scout 1</td>
			<td><?php echo $top['team_a']['Scout']['player_name']; ?></td>
			<td><?php echo round($top['team_a']['Scout']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Scout 2</td>
			<td><?php echo $top['team_a']['Scout2']['player_name']; ?></td>
			<td><?php echo round($top['team_a']['Scout2']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Ammo Carrier</td>
			<td><?php echo $top['team_a']['Ammo Carrier']['player_name']; ?></td>
			<td><?php echo round($top['team_a']['Ammo Carrier']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Medic</td>
			<td><?php echo $top['team_a']['Medic']['player_name']; ?></td>
			<td><?php echo round($top['team_a']['Medic']['avg_mvp'],2); ?></td>
		</tr>
	</tbody>
</table>
<h2>2nd Team</h2>
<table>
	<thead>
		<th>Position</th>
		<th>Player</th>
		<th>Average MVP</th>
	</thead>
	<tbody>
		<tr>
			<td>Commander</td>
			<td><?php echo $top['team_b']['Commander']['player_name']; ?></td>
			<td><?php echo round($top['team_b']['Commander']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Heavy Weapons</td>
			<td><?php echo $top['team_b']['Heavy Weapons']['player_name']; ?></td>
			<td><?php echo round($top['team_b']['Heavy Weapons']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Scout 1</td>
			<td><?php echo $top['team_b']['Scout']['player_name']; ?></td>
			<td><?php echo round($top['team_b']['Scout']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Scout 2</td>
			<td><?php echo $top['team_b']['Scout2']['player_name']; ?></td>
			<td><?php echo round($top['team_b']['Scout2']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Ammo Carrier</td>
			<td><?php echo $top['team_b']['Ammo Carrier']['player_name']; ?></td>
			<td><?php echo round($top['team_b']['Ammo Carrier']['avg_mvp'],2); ?></td>
		</tr>
		<tr>
			<td>Medic</td>
			<td><?php echo $top['team_b']['Medic']['player_name']; ?></td>
			<td><?php echo round($top['team_b']['Medic']['avg_mvp'],2); ?></td>
		</tr>
	</tbody>
</table>
