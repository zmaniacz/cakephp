<?php
	$data = array();
	
	foreach ($response as $score) {
		$data[] = array(	
			'name' => $this->Html->link($score['Scorecard']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Scorecard']['player_id']), array('class' => 'btn btn-block btn-info')),
			'avg_score' => $score[0]['avg_score'],
			'avg_mvp' =>  round($score[0]['avg_mvp'],2),
			'avg_acc' => round($score[0]['avg_acc']*100,2),
			'nuke_ratio' => $score[0]['nuke_ratio'],
			'hit_diff' => $score[0]['hit_diff'],
			'avg_missiles' => $score[0]['avg_missiles'],
			'avg_medic_hits' => $score[0]['avg_medic_hits'],
			'avg_3hit' => $score[0]['avg_3hit'],
			'avg_life_boost' =>$score[0]['avg_life_boost'],
			'avg_ammo_boost' => $score[0]['avg_ammo_boost'],
			'avg_resup' => $score[0]['avg_resup'],
			'avg_lives' => $score[0]['avg_lives'],
			'elim_rate' => round($score[0]['elim_rate']*100,2),
			'games_played' => $score[0]['games_played']
		);
	}
	
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>