<?php
	$data = array();
	foreach ($response as $score) {
		$data[] = array(
			'name' => $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id']), array('class' => 'btn btn-block btn-info')),
			'total_medic_hits' => $score[0]['total_medic_hits'],
			'medic_hits_per_game' => round($score[0]['medic_hits_per_game'],2),
			'games_played' => round($score[0]['games_played'],2),
			'non_resup_total_medic_hits' => $score['ScorecardNoResup']['total_medic_hits'],
			'non_resup_medic_hits_per_game' => round($score['ScorecardNoResup']['medic_hits_per_game'],2),
			'non_resup_games_played' => round($score['ScorecardNoResup']['games_played'],2)
		);
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>