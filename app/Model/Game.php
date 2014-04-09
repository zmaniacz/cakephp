<?php

class Game extends AppModel {
	public $hasMany = 'Scorecard';
	
	public function getOverallStats($games_limit = null) {
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
		} else {
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
					LIMIT $games_limit
				) as Game
				GROUP BY winner,
					red_eliminated,
					green_eliminated
			");
		}
		
		return $overall;
	}
}