<?php

class Scorecard extends AppModel {
	public $belongsTo = array(
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'player_id'
		),
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id'
		)
	);

	public $hasMany = array(
		'Penalty' => array(
			'className' => 'Penalty',
			'foreignkey' => 'scorecard_id'
		)
	);

	public $validate = array(
		'player_name' => array(
			'on' => 'create',
			'rule' => array('uniqueScorecard'),
			'message' => "Non-Unique player/game combination"
		)
	);

	public function uniqueScorecard ($player_name) {
		$count = $this->find('count', array(
			'conditions' => array(
				'player_name' => $player_name, 
				'game_datetime' => $this->data[$this->alias]['game_datetime']
			)
		));
		return $count == 0;
	}
	
	public function generateMVP() {
		$counter = 0;
		$scores = $this->find('all', array('conditions' => array('Scorecard.mvp_points' => NULL)));
		foreach ($scores as $score) {
			$mvp = 0;

			//Position based point bonus
			switch($score['Scorecard']['position']) {
				case "Ammo Carrier":
					$mvp += max(ceil(($score['Scorecard']['score']-3999)/1000),0);
					break;
				case "Commander":
					$mvp += max(ceil(($score['Scorecard']['score']-10999)/1000),0);
					break;
				case "Heavy Weapons":
					$mvp += max(ceil(($score['Scorecard']['score']-7999)/1000),0);
					break;
				case "Medic":
					$mvp += max(ceil(($score['Scorecard']['score']-2999)/1000),0);
					break;
				case "Scout":
					$mvp += max(ceil(($score['Scorecard']['score']-6999)/1000),0);
					break;
			}

			//medic bonus point
			if($score['Scorecard']['position'] == 'Medic' && $score['Scorecard']['score'] >= 3000) {
				$mvp += 1;
			}
			
			//accuracy bonus
			$mvp += round($score['Scorecard']['accuracy'] * 10,0);
			
			//don't get missiled dummy
			$mvp += $score['Scorecard']['times_missiled'] * -1;
			
			//missile other people instead
			switch($score['Scorecard']['position']) {
				case "Commander":
					$mvp += $score['Scorecard']['missiled_opponent'];
					break;
				case "Heavy Weapons":
					$mvp += $score['Scorecard']['missiled_opponent'] * 2;
					break;
			}
			
			//get dat 5-chain
			$mvp += $score['Scorecard']['nukes_detonated'];
			
			//maybe hide better
			$mvp += ($score['Scorecard']['nukes_activated'] - $score['Scorecard']['nukes_detonated']) * -3;

			//make commanders cry
			$mvp += $score['Scorecard']['nukes_canceled'] *3;
			
			//medic tears are scrumptious
			$mvp += $score['Scorecard']['medic_hits'];
			
			//dont be a venom
			$mvp += $score['Scorecard']['own_medic_hits'] * -1;
			
			//push the little button
			$mvp += $score['Scorecard']['scout_rapid'] * .5;
			$mvp += $score['Scorecard']['life_boost'] * 2;
			$mvp += $score['Scorecard']['ammo_boost'] * 3;
			
			//survival bonuses/penalties
			if($score['Scorecard']['lives_left'] > 0 && $score['Scorecard']['position'] == "Medic")
				$mvp += 2;
			
			if($score['Scorecard']['lives_left'] <= 0 && $score['Scorecard']['position'] != "Medic")
				$mvp += -1;
			
			
			$mvp += $score['Scorecard']['penalties'] * -5;
			
			//raping 3hits.  the math looks weird, but it works and gets the desired result
			$mvp += floor(($score['Scorecard']['shot_3hit']/6)*100) / 100;
			
			//No.  Stahp.
			$mvp += $score['Scorecard']['own_nuke_cancels'] * -3;
			
			//more venom points
			$mvp += $score['Scorecard']['missiled_team'] * -3;
			
			//WINNER
			$mvp += $score['Scorecard']['elim_other_team'] * 2;
			
			$score['Scorecard']['mvp_points'] = max($mvp,0);

			if($this->save($score)) {
				$counter++;
			} else {
				debug($this->validationErrors); die();
			}
		}
		return $counter;
	}
	
	public function generateGames($center_id, $green_pens = null, $red_pens = null) {
	
		App::uses('Sanitize', 'Utility');
		$counter = 0;
		
		$scores = $this->query("SELECT green.game_datetime, green.score, red.score, green.team_elim, red.team_elim, green.pdf_id
			FROM (
				SELECT game_datetime, pdf_id, league_id, SUM(score) AS score, SUM(team_elim) AS team_elim
				FROM scorecards 
				WHERE team = 'Green' AND game_id IS NULL AND center_id=$center_id
				GROUP BY game_datetime
			) AS green,
			(
				SELECT game_datetime, SUM(score) AS score, SUM(team_elim) AS team_elim
				FROM scorecards
				WHERE team = 'Red' AND game_id IS NULL AND center_id=$center_id
				GROUP BY game_datetime
			) AS red
			WHERE green.game_datetime = red.game_datetime 
			ORDER BY green.game_datetime"
		);
		
		$current_date = 0;
		$date = 0;
		$game_counter = 0;
		
		foreach($scores as $score) {
			$date = date("Y-m-d", strtotime($score['green']['game_datetime']));
			if($current_date == $date) {
				$game_counter++;
			} else {
				$game_counter = 1;
				$current_date = $date;
			}

			//calculate elim bonus
			$red_elim = 0;
			$green_elim = 0;
			$red_adj = 0;
			$green_adj = 0;
			
			if($score['red']['team_elim'] > 0) {
				$red_elim = 1;
				$green_adj += 10000;
			}
	
			if($score['green']['team_elim'] > 0) {
				$green_elim = 1;
				$red_adj += 10000;
			}

			if(!is_null($green_pens)) {
				$green_adj += $green_pens;
			}
			
			if(!is_null($red_pens)) {
				$red_adj += $red_pens;
			}

			$winner = 'Green';
			if(($score['red']['score'] + $red_adj) > ($score['green']['score'] + $green_adj))
				$winner = 'Red';

			$this->Game->create();
			$this->Game->set(array(
				'game_name' => "G{$game_counter}",
				'game_description' => "",
				'game_datetime' => $score['green']['game_datetime'],
				'green_score' => $score['green']['score'],
				'red_score' => $score['red']['score'],
				'red_adj' => $red_adj,
				'green_adj' => $green_adj,
				'red_eliminated' => $red_elim,
				'green_eliminated' => $green_elim,
				'winner' => $winner,
				'pdf_id' => $score['green']['pdf_id'],
				'league_id' => $score['green']['league_id'],
				'center_id' => $center_id
			));
			$this->Game->save();
			
			$this->updateAll(
				array('Scorecard.game_id' =>  '"' . $this->Game->id . '"'),
				array('Scorecard.game_datetime' => $score['green']['game_datetime'])
			);

			$penalties = $this->
			
			$counter++;
		}
		return $counter;
	}
	
	public function generatePlayers($center_id) {
		$scores = $this->find('all', array('conditions' => array('Scorecard.player_id' => NULL)));
		$players = $this->Player->find('all');
		$results = array('new' => 0, 'existing' => 0);
		
		foreach($scores as $score) {
			$found = false;
			foreach($players as $key => $val) {
				if(strcasecmp($score['Scorecard']['player_name'], $val['Player']['player_name']) == 0 ) {
					$score['Scorecard']['player_id'] = $val['Player']['id'];
					$this->save($score);
					$results['existing']++;
					$found = true;
					break;
				}
			}
				
			if(!$found) {
				$this->Player->Create();
				$this->Player->set(array(
					'player_name' => $score['Scorecard']['player_name'],
					'center_id' => $center_id
				));
				$this->Player->save();
				$score['Scorecard']['player_id'] = $this->Player->id;
				$this->save($score);
				$results['new']++;
				$players = $this->Player->find('all');
			}
		}
		
		return $results;
	}
	
	public function getGameDates($center_id = null) {
		if(!is_null($center_id))
			$conditions[] = array('center_id' => $center_id);

		$game_dates = $this->find('all', array(
			'fields' => array('DISTINCT DATE(Scorecard.game_datetime) as game_date'),
			'order' => 'Scorecard.game_datetime DESC',
			'conditions' => $conditions
		));
		$game_dates = Set::combine($game_dates, '{n}.0.game_date', '{n}.0.game_date');
		return $game_dates;
	}
	
	public function getGamesByDate($date, $center_id) {
		$conditions = array();
		
		if(!is_null($date))
			$conditions[] = array('DATE(Game.game_datetime)' => $date);
			
		$conditions[] = array('Game.center_id' => $center_id);
	
		$games = $this->Game->find('all', array(
			'conditions' => $conditions,
			'order' => 'Game.game_datetime ASC'
		));
		return $games;
	}
	
	public function getPositionStats($role = null, $filter = null, $center_id = null) {
		$conditions = array();
		$min_games = null;

		if(!is_null($center_id))
			$conditions[] = array('center_id' => $center_id);

		if(!is_null($role))
			$conditions[] = array('position' => $role);

		if(!is_null($filter)) {
			if(isset($filter['numeric'])) {
				if($filter['numeric'] > 0) {
					$min_games = $filter['numeric'];
				}
			}
			if(isset($filter['date'])) {
				if($filter['date'] > 0) {
					$conditions['DATEDIFF(DATE(NOW()),DATE(Scorecard.game_datetime)) <='] = $filter['date'];
				}
			}
			if(isset($filter['league'])) {
				if($filter['league'] > 0) {
					$conditions[] = array('league_id' => $filter['league']);
				}
			}
		}
		
		$scores = $this->find('all', array(
			'fields' => array(
				'player_name',
				'player_id',
				'MIN(Scorecard.score) as min_score',
				'ROUND(AVG(Scorecard.score)) as avg_score',
				'MAX(Scorecard.score) as max_score',
				'AVG(Scorecard.mvp_points) as avg_mvp',
				'COUNT(Scorecard.game_datetime) as games_played',
				'MIN(Scorecard.accuracy) as min_acc',
				'AVG(Scorecard.accuracy) as avg_acc',
				'MAX(Scorecard.accuracy) as max_acc',
				'(SUM(nukes_detonated)/SUM(nukes_activated)) as nuke_ratio',
				'(SUM(shot_opponent)/SUM(times_zapped)) as hit_diff',
				'AVG(missiled_opponent) as avg_missiles',
				'AVG(medic_hits) as avg_medic_hits',
				'AVG(shot_3hit) as avg_3hit',
				'AVG(ammo_boost) as avg_ammo_boost',
				'AVG(Scorecard.life_boost) as avg_life_boost',
				'AVG(resupplies) as avg_resup',
				'AVG(Scorecard.lives_left) as avg_lives',
				'(SUM(Scorecard.team_elim)/COUNT(Scorecard.game_datetime)) as elim_rate'
			),				
			'conditions' => $conditions,
			'group' => "player_name".(($min_games > 0) ? " HAVING games_played >= $min_games" : ""),
			'order' => 'avg_mvp DESC'
		));
		return $scores;
	}
	
	public function getMedicExtraStats($min_games = 0) {
		$players = $this->find('all', array(
			'fields' => array(
				'player_name',
				'player_id',
				'COUNT(game_datetime) as games_played'
			),
			'conditions' => array('position' => 'Medic'),
			'group' => "player_id HAVING games_played >= $min_games"
		));
		
		/*foreach($player as $players) {
			$options = array();
			$options['joins'] = array(
				array(
					'table' => Games
			$games = $this->Game->find('all', array(
				'conditions*/
	}
	
	public function getMedicHitStats($resup_only, $filter = null, $center_id = null) {
		if(!is_null($center_id))
			$conditions[] = array('center_id' => $center_id);

		if($resup_only)
			$conditions[] = array("NOT" => array("position" => array("Medic", "Ammo Carrier")));

		if(!is_null($filter)) {
			if(isset($filter['league'])) {
				if($filter['league'] > 0) {
					$conditions[] = array('league_id' => $filter['league']);
				}
			}
		}

		$scores = $this->find('all', array(
			'fields' => array(
				'player_name',
				'player_id',
				'SUM(Scorecard.medic_hits) as total_medic_hits',
				'(SUM(Scorecard.medic_hits)/COUNT(Scorecard.game_datetime)) as medic_hits_per_game'
			),
			'conditions' => $conditions,
			'group' => 'player_name HAVING total_medic_hits > 0',
			'order' => 'total_medic_hits DESC'
		));
		return $scores;
	}
	
	public function getMedicHitStatsByDate($date, $center_id) {
		$conditions = array();
		
		if(!is_null($date))
			$conditions[] = array('DATE(Scorecard.game_datetime)' => $date);
			
		$conditions[] = array('Scorecard.center_id' => $center_id);
	
	
		$scores = $this->find('all', array(
			'fields' => array(
				'player_name',
				'player_id',
				'SUM(Scorecard.medic_hits) as total_medic_hits',
				'(SUM(Scorecard.medic_hits)/COUNT(Scorecard.game_datetime)) as medic_hits_per_game'
			),
			'conditions' => $conditions,
			'group' => 'player_name',
			'order' => 'total_medic_hits DESC'
		));
		return $scores;
	}

	public function getMedicHitStatsByRound($round, $league_id) {
		//need to do round shit here
		$conditions = array();
			
		$conditions[] = array('Scorecard.league_id' => $league_id);
	
		$scores = $this->find('all', array(
			'fields' => array(
				'player_name',
				'player_id',
				'SUM(Scorecard.medic_hits) as total_medic_hits',
				'(SUM(Scorecard.medic_hits)/COUNT(Scorecard.game_datetime)) as medic_hits_per_game'
			),
			'conditions' => $conditions,
			'group' => 'player_name',
			'order' => 'total_medic_hits DESC'
		));
		return $scores;
	}
	
	public function getPlayerGamesScorecardsById($player_id, $filter = null) {
		$conditions = array();
		$limit = null;
		
		$conditions['player_id'] = $player_id;
		
		if(!is_null($filter)) {
			if(isset($filter['numeric'])) {
				if($filter['numeric'] > 0) {
					$limit = $filter['numeric'];
				}
			}
			if(isset($filter['date'])) {
				if($filter['date'] > 0) {
					$conditions['DATEDIFF(DATE(NOW()),DATE(Scorecard.game_datetime)) <='] = $filter['date'];
				}
			}
		}

		$games = $this->find('all', array(
			'conditions' => $conditions,
			'order' => 'Scorecard.game_datetime DESC',
			'limit' => $limit,
			'contain' => array(
				'Game' => array()
			)
		));
		
		return $games;
	}
	
	public function getPlayerTopScorecardsMVPById($player_id, $position = "") {
		$conditions = array('player_id' => $player_id);
		if($position != "" ) {
			$conditions['position'] = $position;
		}
	
		$games = $this->find('all', array(
			'conditions' => $conditions,
			'order' => 'Scorecard.mvp_points DESC',
			'limit' => 5,
			'contain' => array(
				'Game' => array()
			)
		));
		
		return $games;
	}

	public function getPlayerTopScorecardsScoreById($player_id, $position = "") {
		$conditions = array('player_id' => $player_id);
		if($position != "" ) {
			$conditions['position'] = $position;
		}
	
		$games = $this->find('all', array(
			'conditions' => $conditions,
			'order' => 'Scorecard.score DESC',
			'limit' => 5,
			'contain' => array(
				'Game' => array()
			)
		));
		
		return $games;
	}

	public function getOverallAverages($filter_type, $games_limit = null, $center_id = null) {
		if(is_null($games_limit)) {
			$overall = $this->find('all', array(
				'fields' => array(
					'position',
					'AVG(score) as avg_score',
					'AVG(mvp_points) as avg_mvp'
				),
				'conditions' => array('center_id' => $center_id),
				'group' => array(
					'position'
				)
			));
		} elseif($filter_type == 'numeric') {
			$overall = $this->query("
				SELECT position,
					AVG(score) as avg_score,
					AVG(mvp_points) as avg_mvp
				FROM (
					SELECT position,
						score,
						mvp_points
					FROM scorecards
					WHERE center_id = $center_id
					ORDER BY game_datetime DESC
					LIMIT $games_limit
				) as Scorecard
				GROUP BY position
			");
		} elseif($filter_type == 'date') {
			$overall = $this->query("
				SELECT position,
					AVG(score) as avg_score,
					AVG(mvp_points) as avg_mvp
				FROM (
					SELECT position,
						score,
						mvp_points
					FROM scorecards
					WHERE DATEDIFF(DATE(NOW()),DATE(game_datetime)) <= $games_limit AND center_id = $center_id
					ORDER BY game_datetime DESC
				) as Scorecard
				GROUP BY position
			");
		}

		return $overall;
	}
	
	public function getAllAvgMVP($filter = null, $center_id = null) {
		if(!is_null($center_id))
			$conditions[] = array('center_id' => $center_id);

		if(!is_null($filter)) {
			if(isset($filter['league'])) {
				if($filter['league'] > 0) {
					$conditions[] = array('league_id' => $filter['league']);
				}
			}
		}

		$players = $this->find('all', array(
			'fields' => array(
				'player_id',
				'player_name',
				'position',
				'AVG(mvp_points) as avg_mvp',
				'AVG(accuracy) as avg_acc',
				'COUNT(game_datetime) as games_played' 
			),
			'conditions' => $conditions,
			'group' => 'player_name, position'
		));
		
		$results = array();
		foreach($players as $player) {
			if(!isset($results[$player['Scorecard']['player_id']])) {
				$results[$player['Scorecard']['player_id']] = array();
				$results[$player['Scorecard']['player_id']]['player_name'] = $player['Scorecard']['player_name'];
			}
			$results[$player['Scorecard']['player_id']][$player['Scorecard']['position']]['avg_mvp'] = $player[0]['avg_mvp'];
			$results[$player['Scorecard']['player_id']][$player['Scorecard']['position']]['avg_acc'] = $player[0]['avg_acc'];
			$results[$player['Scorecard']['player_id']][$player['Scorecard']['position']]['games_played'] = $player[0]['games_played'];
		}
		
		foreach($results as &$result) {
			$total_mvp = 0;
			$total_acc = 0;
			$total_games_played = 0;
			$positions = 0;

			if(isset($result['Ammo Carrier'])) {
				$total_mvp += $result['Ammo Carrier']['avg_mvp'];
				$total_acc += $result['Ammo Carrier']['avg_acc'];
				$total_games_played += $result['Ammo Carrier']['games_played'];
				$positions++;
			} else {
				$result['Ammo Carrier']['avg_mvp'] = 0;
				$result['Ammo Carrier']['avg_acc'] = 0;
				$result['Ammo Carrier']['games_played'] = 0;
			}

			if(isset($result['Commander'])) {
				$total_mvp += $result['Commander']['avg_mvp'];
				$total_acc += $result['Commander']['avg_acc'];
				$total_games_played += $result['Commander']['games_played'];
				$positions++;
			} else {
				$result['Commander']['avg_mvp'] = 0;
				$result['Commander']['avg_acc'] = 0;
				$result['Commander']['games_played'] = 0;
			}


			if(isset($result['Heavy Weapons'])) {
				$total_mvp += $result['Heavy Weapons']['avg_mvp'];
				$total_acc += $result['Heavy Weapons']['avg_acc'];
				$total_games_played += $result['Heavy Weapons']['games_played'];
				$positions++;
			} else {
				$result['Heavy Weapons']['avg_mvp'] = 0;
				$result['Heavy Weapons']['avg_acc'] = 0;
				$result['Heavy Weapons']['games_played'] = 0;
			}

			if(isset($result['Scout'])) {
				$total_mvp += $result['Scout']['avg_mvp'];
				$total_acc += $result['Scout']['avg_acc'];
				$total_games_played += $result['Scout']['games_played'];
				$positions++;
			} else {
				$result['Scout']['avg_mvp'] = 0;
				$result['Scout']['avg_acc'] = 0;
				$result['Scout']['games_played'] = 0;
			}

			if(isset($result['Medic'])) {
				$total_mvp += $result['Medic']['avg_mvp'];
				$total_acc += $result['Medic']['avg_acc'];
				$total_games_played += $result['Medic']['games_played'];
				$positions++;
			} else {
				$result['Medic']['avg_mvp'] = 0;
				$result['Medic']['avg_acc'] = 0;
				$result['Medic']['games_played'] = 0;
			}
			
			$result['avg_avg_mvp'] = $total_mvp/$positions;
			$result['avg_avg_acc'] = $total_acc/$positions;
			$result['total_games'] = $total_games_played;
		}
		
		return $results;
	}
	
	public function getScorecardsByDate($date, $center_id) {
		$conditions = array();
		
		if(!is_null($date))
			$conditions[] = array('DATE(Scorecard.game_datetime)' => $date);
			
		$conditions[] = array('Scorecard.center_id' => $center_id);
	
		$scorecards = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Game' => array()
			)
		));
		
		return $scorecards;
	}

	public function getLeagueScorecardsByRound($round, $league_id) {
		//doesnt do shit with rounds yet, just pulls everything
		$conditions = array();
			
		$conditions[] = array('Scorecard.league_id' => $league_id);
	
		$scorecards = $this->find('all', array(
			'conditions' => $conditions,
			'contain' => array(
				'Game' => array()
			)
		));
		
		return $scorecards;
	}
	
	public function getTopTeams($center_id) {
		$matrix = $this->_loadMatrix($center_id);

		//reverse the matrix to make it a cost matrix
		$max = 0;
		foreach($matrix as $row) {
			foreach($row as $column) {
				if($column > $max) {
					$max = $column;
				}
			}
		}

		foreach($matrix as &$row) {
			foreach($row as &$column) {
				$column = $max - $column;
			}
		}

		//run the algorithm
		$M = $this->_munkres($matrix);

		//build the results
		$team_a = array();
		$r = 0;
		foreach($matrix as $key => $value) {
			for($c = 0; $c < count($M[$r]); $c++) {
				if($M[$r][$c] == 1) {
					switch($c) {
						case 0:
							$team_a['Ammo Carrier'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Ammo Carrier']));
							break;
						case 1:
							$team_a['Commander'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Commander']));
							break;
						case 2:
							$team_a['Heavy Weapons'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Heavy Weapons']));
							break;
						case 3:
							$team_a['Medic'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Medic']));
							break;
						case 4:
							$team_a['Scout'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Scout']));
							break;
						case 5:
							$team_a['Scout2'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Scout']));
							break;
					}
					break;
				}
			}
			$r++;
		}
		
		foreach($team_a as $player) {
			unset($matrix[$player['player_name']]);
		}
		
		$M = $this->_munkres($matrix);
		$team_b = array();
		$r = 0;
		foreach($matrix as $key => $value) {
			for($c = 0; $c < count($M[$r]); $c++) {
				if($M[$r][$c] == 1) {
					switch($c) {
						case 0:
							$team_b['Ammo Carrier'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Ammo Carrier']));
							break;
						case 1:
							$team_b['Commander'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Commander']));
							break;
						case 2:
							$team_b['Heavy Weapons'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Heavy Weapons']));
							break;
						case 3:
							$team_b['Medic'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Medic']));
							break;
						case 4:
							$team_b['Scout'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Scout']));
							break;
						case 5:
							$team_b['Scout2'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Scout']));
							break;
					}
					break;
				}
			}
			$r++;
		}
/*
		foreach($team_b as $player) {
			unset($matrix[$player['player_name']]);
		}
		
		$M = $this->_munkres($matrix);
		$team_c = array();
		$r = 0;
		foreach($matrix as $key => $value) {
			for($c = 0; $c < count($M[$r]); $c++) {
				if($M[$r][$c] == 1) {
					switch($c) {
						case 0:
							$team_c['Ammo Carrier'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Ammo Carrier']));
							break;
						case 1:
							$team_c['Commander'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Commander']));
							break;
						case 2:
							$team_c['Heavy Weapons'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Heavy Weapons']));
							break;
						case 3:
							$team_c['Medic'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Medic']));
							break;
						case 4:
							$team_c['Scout'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Scout']));
							break;
						case 5:
							$team_c['Scout2'] = array('player_name' => $key, 'avg_mvp' => ($max - $value['Scout']));
							break;
					}
					break;
				}
			}
			$r++;
		}
*/		
		if(!isset($team_a['Ammo Carrier']))
			$team_a['Ammo Carrier'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_a['Commander']))
			$team_a['Commander'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_a['Heavy Weapons']))
			$team_a['Heavy Weapons'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_a['Medic']))
			$team_a['Medic'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_a['Scout']))
			$team_a['Scout'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_a['Scout2']))
			$team_a['Scout2'] = array('player_name' => 'N/A', 'avg_mvp' => 0);

		if(!isset($team_b['Ammo Carrier']))
			$team_b['Ammo Carrier'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_b['Commander']))
			$team_b['Commander'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_b['Heavy Weapons']))
			$team_b['Heavy Weapons'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_b['Medic']))
			$team_b['Medic'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_b['Scout']))
			$team_b['Scout'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_b['Scout2']))
			$team_b['Scout2'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
/*
		if(!isset($team_c['Ammo Carrier']))
			$team_c['Ammo Carrier'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_c['Commander']))
			$team_c['Commander'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_c['Heavy Weapons']))
			$team_c['Heavy Weapons'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_c['Medic']))
			$team_c['Medic'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_c['Scout']))
			$team_c['Scout'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
		if(!isset($team_c['Scout2']))
			$team_c['Scout2'] = array('player_name' => 'N/A', 'avg_mvp' => 0);
*/
		$results = array('team_a' => $team_a, 'team_b' => $team_b/*, 'team_c' => $team_c*/);
	
		return $results;
	}
	
	protected function _loadMatrix($center_id) {
		$results = $this->find('all', array(
			'fields' => array(
				'player_id',
				'player_name',
				'position',
				'AVG(mvp_points) as avg_mvp',
				'COUNT(game_datetime) as games_played'
			),
			'conditions' => array(
				'center_id =' => $center_id,
				'DATEDIFF(DATE(NOW()),DATE(game_datetime)) <=' => 365
			),
			'group' => 'player_id, player_name, position HAVING games_played >= 15'
		));
		
		$matrix = array();

		foreach($results as $key => $result) {
			$matrix[$result['Scorecard']['player_name']]['Ammo Carrier'] = 0.0;
			$matrix[$result['Scorecard']['player_name']]['Commander'] = 0.0;
			$matrix[$result['Scorecard']['player_name']]['Heavy Weapons'] = 0.0;
			$matrix[$result['Scorecard']['player_name']]['Medic'] = 0.0;
			$matrix[$result['Scorecard']['player_name']]['Scout'] = 0.0;
			$matrix[$result['Scorecard']['player_name']]['Scout2'] = 0.0;
		}
		
		foreach($results as $key => $result) {
			$matrix[$result['Scorecard']['player_name']][$result['Scorecard']['position']] = (float)$result[0]['avg_mvp'];
			if($result['Scorecard']['position'] == 'Scout') {
				$matrix[$result['Scorecard']['player_name']]['Scout2'] = (float)$result[0]['avg_mvp'];
			}
		}

		return $matrix;
	}
	
	protected function _munkres($matrix) {
		//Munkres implementation
		$C = array();
		$C_orig = array();
		$M = array();
		$path = array();
		$RowCover = array();
		$colCover = array();
		$nrow = 0;
		$ncol = 0;
		$path_count = 0;
		$path_row_0 = 0;
		$path_col_0 = 0;
		$asgn = 0;
		$step = 1;

		foreach($matrix as $row) {
			$ncol = 0;
			foreach($row as $column) {
				$C[$nrow][$ncol] = $column;
				$ncol++;
			}
			$nrow++;
		}
		
		while($ncol < $nrow) {
			for($r = 0; $r < $nrow; $r++) {
				$C[$r][$ncol] = 100;
			}
			$ncol++;
		}
		
		for($r = 0; $r < $nrow; $r++) {
			$RowCover[$r] = 0;
			for($c = 0; $c < $ncol; $c++) {
				$M[$r][$c] = 0;
			}
		}
		for($c = 0; $c < $ncol; $c++) {
			$ColCover[$c] = 0;
		}
		
		$ovl_done = false;
		
		while(!$ovl_done) {
			switch($step) {
				case 1:
					$min_in_row = 0;
					
					for($r = 0; $r < $nrow; $r++) {
						$min_in_row = $C[$r][0];
						for($c = 0; $c < $ncol; $c++) {
							if($C[$r][$c] < $min_in_row) {
								$min_in_row = $C[$r][$c];
							}
						}
						for($c = 0; $c < $ncol; $c++) {
							$C[$r][$c] -= $min_in_row;
						}
					}
					$step = 2;
					break;
				case 2:
					for($r = 0; $r < $nrow; $r++) {
						for($c = 0; $c < $ncol; $c++) {
							if($C[$r][$c] == 0 && $RowCover[$r] == 0 && $ColCover[$c] == 0) {
								$M[$r][$c] = 1;
								$RowCover[$r] = 1;
								$ColCover[$c] = 1;
							}
						}
					}
					for($r = 0; $r < $nrow; $r++) {
						$RowCover[$r] = 0;
					}
					for($c = 0; $c < $ncol; $c++) {
						$ColCover[$c] = 0;
					}
					$step = 3;
					break;
				case 3:
					$colcount = 0;
					for($r = 0; $r < $nrow; $r++) {
						for($c = 0; $c < $ncol; $c++) {
							if($M[$r][$c] == 1) {
								$ColCover[$c] = 1;
							}
						}
					}

					$colcount = 0;
					for($c = 0; $c < $ncol; $c++) {
						if($ColCover[$c] == 1) {
							$colcount += 1;
						}
					}
					if($colcount >= $ncol || $colcount >= $nrow) {
						$step = 7;
					} else {
						$step = 4;
					}
					break;
				case 4:
					$row = -1;
					$col = -1;
					$done = false;
					
					while (!$done) {
						$r = 0;
						$c = 0;
						$done2 = false;
						$row = -1;
						$col = -1;
						
						//find_a_zero
						while (!$done2) {
							$c = 0;
							while (true) {
								if ($C[$r][$c] == 0 && $RowCover[$r] == 0 && $ColCover[$c] == 0) {
									$row = $r;
									$col = $c;
									$done2 = true;
								}
								$c += 1;
								if ($c >= $ncol || $done2)
									break;
							}
							$r += 1;
							if ($r >= $nrow)
								$done2 = true;
						}
						
						if ($row == -1) {
							$done = true;
							$step = 6;
						} else {
							$M[$row][$col] = 2;
							
							//star_in_row
							$tmp = false;
							for($tmp_c = 0; $tmp_c < $ncol; $tmp_c++) {
								if($M[$row][$tmp_c] == 1) {
									$tmp = true;
								}
							}
							
							if ($tmp) {
								//find_star_in_row
								$col = -1;
								for($tmp_c = 0; $tmp_c < $ncol; $tmp_c++) {
									if ($M[$row][$tmp_c] == 1) {
										$col = $tmp_c;
									}
								}
			
								$RowCover[$row] = 1;
								$ColCover[$col] = 0;
							} else {
								$done = true;
								$step = 5;
								$path_row_0 = $row;
								$path_col_0 = $col;
							}
						}
					}
					break;
				case 5:
					$done = false;
					$r = -1;
					$c = -1;

					$path_count = 1;
					$path[$path_count - 1][0] = $path_row_0;
					$path[$path_count - 1][1] = $path_col_0;

					while (!$done) {
						//find_star_in_col
						$tmp_c = $path[$path_count - 1][1];
						$r = -1;
						for ($i = 0; $i < $nrow; $i++) {
							if ($M[$i][$tmp_c] == 1) {
								$r = $i;
							}
						}
						
						if ($r > -1) {
							$path_count += 1;
							$path[$path_count - 1][0] = $r;
							$path[$path_count - 1][1] = $path[$path_count - 2][1];
						} else {
							$done = true;
						}
						if (!$done)	{
							//find_prime_in_row
							$tmp_r = $path[$path_count - 1][0];
							 for ($j = 0; $j < $ncol; $j++) {
								if ($M[$tmp_r][$j] == 2) {
									$c = $j;
								}
							}
						
							$path_count += 1;
							$path[$path_count - 1][0] = $path[$path_count - 2][0];
							$path[$path_count - 1][1] = $c;
						}
					}
					//augment_path();
					for ($p = 0; $p < $path_count; $p++)
						if ($M[$path[$p][0]][$path[$p][1]] == 1)
							$M[$path[$p][0]][$path[$p][1]] = 0;
						else
							$M[$path[$p][0]][$path[$p][1]] = 1;
					
					//clear_covers();
					for ($r = 0; $r < $nrow; $r++)
						$RowCover[$r] = 0;
					for ($c = 0; $c < $ncol; $c++)
						$ColCover[$c] = 0;
					
					//erase_primes();
					for ($r = 0; $r < $nrow; $r++)
						for ($c = 0; $c < $ncol; $c++)
							if ($M[$r][$c] == 2)
								$M[$r][$c] = 0;
					
					$step = 3;
					break;
				case 6:
					$minval = 100;
					
					for ($r = 0; $r < $nrow; $r++) {
						for ($c = 0; $c < $ncol; $c++) {
							if ($RowCover[$r] == 0 && $ColCover[$c] == 0) {
								if ($minval > $C[$r][$c]) {
									$minval = $C[$r][$c];
								}
							}
						}
					}

					for ($r = 0; $r < $nrow; $r++) {
						for ($c = 0; $c < $ncol; $c++) {
							if ($RowCover[$r] == 1) {
								$C[$r][$c] += $minval;
							}
							if ($ColCover[$c] == 0) {
								$C[$r][$c] -= $minval;
							}
						}
					}
					$step = 4;
					break;
				case 7:
					$ovl_done = true;
					break;
			}
		}
		
		return $M;
	}
}