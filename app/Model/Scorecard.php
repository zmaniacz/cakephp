<?php

class Scorecard extends AppModel {
	public $belongsTo = array('Game','Player');
	
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
					$mvp += max(ceil(($score['Scorecard']['score']-7999)/1000),0);
					break;
			}
			
			//accuracy bonus
			$mvp += round($score['Scorecard']['accuracy'] * 10,0);
			
			//don't get missiled dummy
			$mvp += $score['Scorecard']['times_missiled'] * -1;
			
			//missile other people instead
			switch($score['Scorecard']['position']) {
				case "Commander":
					$mvp += $score['Scorecard']['missile_hits'];
					break;
				case "Heavy Weapons":
					$mvp += $score['Scorecard']['missile_hits'] * 2;
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
			$mvp += floor(($score['Scorecard']['shot_3hit']/8)*100) / 100;
			
			//No.  Stahp.
			$mvp += $score['Scorecard']['own_nuke_cancels'] * -3;
			
			//more venom points
			$mvp += $score['Scorecard']['missiled_team'] * -3;
			
			//WINNER
			$mvp += $score['Scorecard']['elim_other_team'] * 2;
			
			$score['Scorecard']['mvp_points'] = max($mvp,0);
			$this->save($score);
			$counter++;
		}
		return $counter;
	}
	
	public function generateGames() {
	
		App::uses('Sanitize', 'Utility');
		$counter = 0;
		
		$scores = $this->query("SELECT green.game_datetime, green.score, red.score, green.team_elim, red.team_elim
			FROM (
				SELECT game_datetime, SUM(score) AS score, SUM(team_elim) AS team_elim
				FROM scorecards 
				WHERE team = 'Green' AND game_id IS NULL
				GROUP BY game_datetime
			) AS green,
			(
				SELECT game_datetime, SUM(score) AS score, SUM(team_elim) AS team_elim
				FROM scorecards
				WHERE team = 'Red' AND game_id IS NULL
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
			
			$winner = 'Green';
			if($score['red']['score'] > $score['green']['score'])
				$winner = 'Red';
			
			
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
				$red_adj = 10000;
			}

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
				'winner' => $winner
			));
			$this->Game->save();
			
			$this->updateAll(
				array('Scorecard.game_id' =>  '"' . $this->Game->id . '"'),
				array('Scorecard.game_datetime' => $score['green']['game_datetime'])
			);
			
			$counter++;
		}
		return $counter;
	}
	
	public function generatePlayers() {
		$scores = $this->find('all', array('conditions' => array('Scorecard.player_id' => NULL)));
		$players = $this->Player->find('all');
		$results = array('new' => 0, 'existing' => 0);
		
		foreach($scores as $score) {
			$found = false;
			foreach($players as $key => $val) {
				if(strcmp($score['Scorecard']['player_name'], $val['Player']['player_name']) == 0 ) {
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
					'player_name' => $score['Scorecard']['player_name']
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
	
	public function getGameDates() {
		$game_dates = $this->find('all', array(
			'fields' => array('DISTINCT DATE(Scorecard.game_datetime) as game_date'),
			'order' => 'Scorecard.game_datetime DESC'
		));
		$game_dates = Set::combine($game_dates, '{n}.0.game_date', '{n}.0.game_date');
		return $game_dates;
	}
	
	public function getGamesByDate($date) {
		$games = $this->Game->find('all', array(
			'conditions' => array("DATE(Game.game_datetime)" => $date),
			'order' => 'Game.game_datetime ASC'
		));
		return $games;
	}
	
	public function getPositionStats($role = null, $date = null, $min_games = 0) {
		$conditions = array();
		
		if(!is_null($role))
			$conditions[] = array('position' => $role);
		
		if(!is_null($date))
			$conditions[] = array('DATE(Scorecard.game_datetime)' => $date);
		
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
				'AVG(missile_hits) as avg_missiles',
				'AVG(medic_hits) as avg_medic_hits',
				'AVG(shot_3hit) as avg_3hit',
				'AVG(ammo_boost) as avg_ammo_boost',
				'AVG(Scorecard.life_boost) as avg_life_boost',
				'AVG(resupplies) as avg_resup',
				'AVG(Scorecard.lives_left) as avg_lives',
				'(SUM(Scorecard.team_elim)/COUNT(Scorecard.game_datetime)) as elim_rate'
			),				
			'conditions' => $conditions,
			'group' => "player_name HAVING games_played >= $min_games",
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
	
	public function getMedicHitStats() {
		$scores = $this->find('all', array(
			'fields' => array(
				'player_name',
				'player_id',
				'SUM(Scorecard.medic_hits) as total_medic_hits',
				'(SUM(Scorecard.medic_hits)/COUNT(Scorecard.game_datetime)) as medic_hits_per_game'
			),
			'conditions' => array(
				"NOT" => array("position" => array("Medic", "Ammo Carrier"))
			),
			'group' => 'player_name HAVING total_medic_hits > 0',
			'order' => 'total_medic_hits DESC'
		));
		return $scores;
	}
	
	public function getPlayerGamesScorecardsById($player_id, $games_limit = null, $filter_type = null) {
		if(is_null($games_limit)) {
			$games = $this->find('all', array(
				'conditions' => array('player_id' => $player_id),
				'order' => 'Scorecard.game_datetime DESC',
				'contain' => array(
					'Game' => array()
				)
			));
		} elseif ($filter_type == 'numeric') {
			$games = $this->find('all', array(
				'conditions' => array('player_id' => $player_id),
				'order' => 'Scorecard.game_datetime DESC',
				'limit' => $games_limit,
				'contain' => array(
					'Game' => array()
				)
			));
		} elseif ($filter_type == 'date') {
			$games = $this->find('all', array(
				'conditions' => array(
					'player_id' => $player_id,
					'DATEDIFF(DATE(NOW()),DATE(game_datetime)) <=' => $games_limit
				),
				'order' => 'Scorecard.game_datetime DESC',
				'contain' => array(
					'Game' => array()
				)
			));
		}
		
		return $games;
	}
	
	public function getPlayerTopGamesScorecardsById($player_id, $position = "") {
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
}