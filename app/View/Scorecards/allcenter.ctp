<script type="text/javascript">
	$(document).ready(function() {
		$('.allcenter').DataTable( {
			"searching": false,
			"info": false,
			"paging": false,
			"ordering": false
		})
	});
</script>
<div class="well">
All/Social: MVP points calculated based on games played within the last 365 days.  Player must have at least 15 games at a position over that time period to be eligible.
League: MVP points calculated based on all league games played.  Player must have at least 3 games at a position to be eligible.
</div>
<div id="all_center_teams" class="panel panel-info">
	<div class="panel-heading" role="tab" id="all_center_teams_heading">
		<h4 class="panel-title">
			All-Center Teams
		</h4>
	</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-6">
					<h3><span class="label label-success">1st Team</span></h3>
					<table class="allcenter table table-striped table-bordered table-hover">
						<thead>
							<th>Position</th>
							<th>Player</th>
							<th>Average MVP</th>
						</thead>
						<tbody>
							<tr>
								<td>Commander</td>
								<td><?php echo $this->Html->link($top['team_a']['Commander']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_a']['Commander']['player_id'])); ?></td>
								<td><?php echo round($top['team_a']['Commander']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Heavy Weapons</td>
								<td><?php echo $this->Html->link($top['team_a']['Heavy Weapons']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_a']['Heavy Weapons']['player_id'])); ?></td>
								<td><?php echo round($top['team_a']['Heavy Weapons']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Scout 1</td>
								<td><?php echo $this->Html->link($top['team_a']['Scout']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_a']['Scout']['player_id'])); ?></td>
								<td><?php echo round($top['team_a']['Scout']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Scout 2</td>
								<td><?php echo $this->Html->link($top['team_a']['Scout2']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_a']['Scout2']['player_id'])); ?></td>
								<td><?php echo round($top['team_a']['Scout2']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Ammo Carrier</td>
								<td><?php echo $this->Html->link($top['team_a']['Ammo Carrier']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_a']['Ammo Carrier']['player_id'])); ?></td>
								<td><?php echo round($top['team_a']['Ammo Carrier']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Medic</td>
								<td><?php echo $this->Html->link($top['team_a']['Medic']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_a']['Medic']['player_id'])); ?></td>
								<td><?php echo round($top['team_a']['Medic']['avg_mvp'],2); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-sm-6">
					<h3><span class="label label-danger">2nd Team</span></h3>
					<table class="allcenter table table-striped table-bordered table-hover">
						<thead>
							<th>Position</th>
							<th>Player</th>
							<th>Average MVP</th>
						</thead>
						<tbody>
							<tr>
								<td>Commander</td>
								<td><?php echo $this->Html->link($top['team_b']['Commander']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_b']['Commander']['player_id'])); ?></td>
								<td><?php echo round($top['team_b']['Commander']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Heavy Weapons</td>
								<td><?php echo $this->Html->link($top['team_b']['Heavy Weapons']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_b']['Heavy Weapons']['player_id'])); ?></td>
								<td><?php echo round($top['team_b']['Heavy Weapons']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Scout 1</td>
								<td><?php echo $this->Html->link($top['team_b']['Scout']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_b']['Scout']['player_id'])); ?></td>
								<td><?php echo round($top['team_b']['Scout']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Scout 2</td>
								<td><?php echo $this->Html->link($top['team_b']['Scout2']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_b']['Scout2']['player_id'])); ?></td>
								<td><?php echo round($top['team_b']['Scout2']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Ammo Carrier</td>
								<td><?php echo $this->Html->link($top['team_b']['Ammo Carrier']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_b']['Ammo Carrier']['player_id'])); ?></td>
								<td><?php echo round($top['team_b']['Ammo Carrier']['avg_mvp'],2); ?></td>
							</tr>
							<tr>
								<td>Medic</td>
								<td><?php echo $this->Html->link($top['team_b']['Medic']['player_name'], array('controller' => 'Players', 'action' => 'view', $top['team_b']['Medic']['player_id'])); ?></td>
								<td><?php echo round($top['team_b']['Medic']['avg_mvp'],2); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
