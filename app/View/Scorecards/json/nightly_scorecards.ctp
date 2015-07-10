<?php
	$data = array();
	
	foreach($scorecards as $score) {
		if($score['Game']['winner'] == 'Red')
			$options = array('class' => 'btn btn-danger btn-block');
		else
			$options = array('class' => 'btn btn-success btn-block');
		
		$data[] = array(
			'player_name' => $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'players', 'action' => 'view', $score['Scorecard']['player_id']), array('class' => 'btn btn-info btn-block')),
			'game_name' => $this->Html->link($score['Game']['game_name'], array('controller' => 'games', 'action' => 'view', $score['Game']['id']), $options),
			'position' => $score['Scorecard']['position'],
			'score' => $score['Scorecard']['score'],
			'mvp_points' => "<button type=\"button\" class=\"btn btn-info btn-block\" data-toggle=\"modal\" data-target=\"#mvpModal\" target=\"".$this->Html->url(array('controller' => 'scorecards', 'action' => 'getMVPBreakdown', $score['Scorecard']['id'], 'ext' => 'json'))."\">".$score['Scorecard']['mvp_points']."</button>",
			'accuracy' => round($score['Scorecard']['accuracy']*100,2),
			'hit_diff' => ($score['Scorecard']['times_zapped'] > 0) ? round($score['Scorecard']['shot_opponent']/$score['Scorecard']['times_zapped'],2) : $score['Scorecard']['shot_opponent'],
			'medic_hits' => $score['Scorecard']['medic_hits'],
			'shot_team' => $score['Scorecard']['shot_team']
		);
	}
	
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>