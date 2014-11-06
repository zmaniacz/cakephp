<?php

class Game extends AppModel {
	public $hasMany = array(
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'game_id'
		)
	);

	public $belongsTo = array(
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id'
		),
		'Red_Team' => array(
			'className' => 'Team',
			'foreignKey' => 'red_team_id'
		),
		'Green_Team' => array(
			'className' => 'Team',
			'foreignKey' => 'green_team_id'
		),
	);
	
	public function getOverallStats($filter_type = null, $games_limit = null, $center_id = null, $filter = null) {
		$conditions = array();
		
		if(!is_null($center_id))
			$conditions[] = array('center_id' => $center_id);

		if(!is_null($filter)) {
			if($filter['type'] != 'all') {
				$conditions[] = array('type' => $filter['type']);
			}
		}
	
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
				'conditions' => $conditions,
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

	public function getGameList($center_id = null, $filter = null) {
		$conditions = array();

		if(!is_null($center_id))
			$conditions[] = array('Game.center_id' => $center_id);

		if(!is_null($filter)) {
			if($filter['type'] != 'all')
				$conditions[] = array('Game.type' => $filter['type']);

			if($filter['type'] == 'league' && $filter['value'] > 0)
				$conditions[] = array('Game.league_id' => $filter['value']);
		}

		$games = $this->find('all', array(
			'contain' => array('Red_Team', 'Green_Team'),
			'conditions' => $conditions,
			'order' => 'Game.game_datetime ASC'
		));
		return $games;
	}
}