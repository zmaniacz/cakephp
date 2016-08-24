<?php
	$data = array();
	foreach ($nightly as $score) {
		$response[$score['Player']['id']] = array(	
			'player_name' => $this->Html->link($score['Player']['player_name'], array('controller' => 'Players', 'action' => 'view', $score['Player']['id']), array('class' => 'btn btn-block btn-info')),
            'min_score' => $score[0]['min_score'],
            'avg_score' => $score[0]['avg_score'],
            'max_score' => $score[0]['max_score'],
            'min_mvp' =>  round($score[0]['min_mvp'],2),
			'avg_mvp' =>  round($score[0]['avg_mvp'],2),
            'max_mvp' =>  round($score[0]['max_mvp'],2),
			'avg_acc' => round($score[0]['avg_acc']*100,2),
			'hit_diff' => $score[0]['hit_diff'],
			'medic_hits' => $score[0]['medic_hits'],
			'elim_rate' => round($score[0]['elim_rate']*100,2),
			'games_played' => $score[0]['games_played'],
			'games_won' => $score[0]['games_won']
		);
	}

	foreach ($overall as $key => $value) {
		if(isset($response[$key])) {
			$response[$key]['overall_avg_mvp'] = round($value['avg_avg_mvp'],2);
			$response[$key]['overall_avg_acc'] = round($value['avg_avg_acc']*100,2);
		}
	}

	$data = array_values($response);
	
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>