<?php
	$response = array();

	foreach ($data as $score) {
		$response[$score['Scorecard']['player_id']] = array(
            'player_id' => $score['Player']['id'],
			'player_name' => $score['Player']['player_name'],
            'min_score' => $score[0]['min_score'],
            'avg_score' => $score[0]['avg_score'],
            'max_score' => $score[0]['max_score'],
            'min_mvp' => $score[0]['min_mvp'],
			'avg_mvp' =>  $score[0]['avg_mvp'],
            'max_mvp' =>  $score[0]['max_mvp'],
			'avg_acc' => $score[0]['avg_acc'],
			'hit_diff' => $score[0]['hit_diff'],
			'medic_hits' => $score[0]['medic_hits'],
			'elim_rate' => $score[0]['elim_rate'],
			'games_played' => $score[0]['games_played'],
			'games_won' => $score[0]['games_won'],
			'link' => html_entity_decode($this->Html->url(array(
				'controller' => 'players',
				'action' => 'view',
				$score['Player']['id']
			)))
		);
	}

	foreach ($overall as $score) {
		if(isset($response[$score['Scorecard']['player_id']])) {
			$response[$score['Scorecard']['player_id']]['overall_avg_mvp'] = $score[0]['avg_mvp'];
			$response[$score['Scorecard']['player_id']]['overall_avg_acc'] = $score[0]['avg_acc'];
		}
	}

    $data = array_values($response);
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>