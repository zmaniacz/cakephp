<?php
	$data = array();
	foreach($medic_hits as $medic) {
		$data[] = array(
			'player_name' => $this->Html->link($medic['Scorecard']['player_name'], array('controller' => 'players', 'action' => 'view', $medic['Scorecard']['player_id']), array('class' => 'btn btn-info btn-block')),
			'total_medic_hits' => $medic[0]['total_medic_hits'],
			'medic_hits_per_game' => $medic[0]['medic_hits_per_game'],
			'games_played' => $medic[0]['games_played'],
			'non_resup_total_medic_hits' => $medic['ScorecardNoResup']['total_medic_hits'],
			'non_resup_medic_hits_per_game' => $medic['ScorecardNoResup']['medic_hits_per_game'],
			'non_resup_games_played' => $medic['ScorecardNoResup']['games_played'],
		);
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>