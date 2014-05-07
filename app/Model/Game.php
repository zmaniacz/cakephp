<?php

class Game extends AppModel {
	public $hasMany = 'Scorecard';
	
	public function getOverallStats($filter_type, $games_limit = null) {
		if(is_null($games_limit)) {
			$overall = $this->find('all', array(
				'fields' => array(
					'winner',
					'red_eliminated',
					'green_eliminated',
					'COUNT(game_datetime) as Total'
				),
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
					COUNT(game_datetime) as Total
				FROM (
					SELECT winner,
						red_eliminated,
						green_eliminated,
						game_datetime
					FROM games
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
					COUNT(game_datetime) as Total
				FROM (
					SELECT winner,
						red_eliminated,
						green_eliminated,
						game_datetime
					FROM games
					WHERE DATEDIFF(DATE(NOW()),DATE(game_datetime)) <= $games_limit
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