<?php

class Game extends AppModel {
	public $hasMany = array(
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'game_id'
		),
		'Penalty' => array(
			'className' => 'Penalty',
			'foreignKey' => 'game_id'
		)
	);

	public $belongsTo = array(
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id'
		)
	);
	
	public function getOverallStats($filter_type, $games_limit = null, $center_id = null) {
		if(is_null($games_limit)) {
			$overall = $this->find('all', array(
				'fields' => array(
					'winner',
					'red_eliminated',
					'green_eliminated',
					'COUNT(game_datetime) as Total',
					'AVG(red_score) as red_avg_score',
					'AVG(green_score) as green_avg_score'
				),
				'conditions' => array('center_id' => $center_id),
				'group' => array(
					'winner',
					'red_eliminated',
					'green_eliminated'
				)
			));
		} elseif($filter_type == 'numeric') {
			$overall = $this->query("
				SELECT winner,
					red_eliminated,
					green_eliminated,
					COUNT(game_datetime) as Total,
					AVG(red_score) as red_avg_score,
					AVG(green_score) as green_avg_score
				FROM (
					SELECT winner,
						red_eliminated,
						green_eliminated,
						game_datetime,
						red_score,
						green_score
					FROM games
					WHERE center_id = $center_id
					ORDER BY game_datetime DESC
					LIMIT $games_limit
				) as Game
				GROUP BY winner,
					red_eliminated,
					green_eliminated
				ORDER BY game_datetime DESC
			");
		} elseif($filter_type == 'date') {
			$overall = $this->query("
				SELECT winner,
					red_eliminated,
					green_eliminated,
					COUNT(game_datetime) as Total,
					AVG(red_score) as red_avg_score,
					AVG(green_score) as green_avg_score
				FROM (
					SELECT winner,
						red_eliminated,
						green_eliminated,
						game_datetime,
						red_Score,
						green_score
					FROM games
					WHERE DATEDIFF(DATE(NOW()),DATE(game_datetime)) <= $games_limit AND center_id = $center_id
					ORDER BY game_datetime DESC
				) as Game
				GROUP BY winner,
					red_eliminated,
					green_eliminated
				ORDER BY game_datetime DESC
			");
		}

		return $overall;
	}
}