<?php
	$data = array();
	$commander = array();
	$heavy = array();
	$scout = array();
	$ammo = array();
	$medic = array();

	foreach ($response as $score) {
		$stats = array(	
			'name' => $this->Html->link($score['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Player']['id']), array('class' => 'btn btn-block btn-info')),
			'player_id' => $score['Player']['id'],
			'player_name' => $score['Player']['player_name'],
			'avg_score' => $score[0]['avg_score'],
			'total_score' => $score[0]['total_score'],
			'avg_mvp' =>  round($score[0]['avg_mvp'],2),
			'total_mvp' => round($score[0]['total_mvp'],2),
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
			'games_played' => $score[0]['games_played'],
			'games_won' => $score[0]['games_won']
		);

		switch($score['Scorecard']['position']) {
			case 'Commander':
				$commander[] = $stats;
				break;
			case 'Heavy Weapons':
				$heavy[] = $stats;
				break;
			case 'Scout':
				$scout[] = $stats;
				break;
			case 'Ammo Carrier':
				$ammo[] = $stats;
				break;
			case 'Medic':
				$medic[] = $stats;
				break;
		}
	}

	$data = compact('commander','heavy','scout','ammo','medic');
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>