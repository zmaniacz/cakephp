<?php
//we define team 1 to be the team that plays red in game 1 of the match
class Match extends AppModel {
	public $hasMany = array(
		'Game' => array(
			'className' => 'Game',
			'foreignkey' => 'match_id'
		)
	);
	
	public $hasOne = array(
		'Game_1' => array(
			'className' => 'Game',
			'foreignKey' => 'match_id',
			'finderQuery' => 'SELECT Game_1.* 
								FROM games as Game_1 
								WHERE Game_1.match_id = {$__cakeID__$} 
								AND Game_1.red_team_id IN (SELECT team_1_id FROM matches WHERE match_id={$__cakeID__$})'
		),
		'Game_2' => array(
			'className' => 'Game',
			'foreignKey' => 'match_id',
			'finderQuery' => 'SELECT Game_2.* 
								FROM games as Game_2 
								WHERE Game_2.match_id = {$__cakeID__$} 
								AND Game_2.red_team_id IN (SELECT team_2_id FROM matches WHERE match_id={$__cakeID__$})'
		)
	);

	public $belongsTo = array(
		'Round' => array(
			'className' => 'Round',
			'foreignKey' => 'round_id'
		),
		'Team_1' => array(
			'className' => 'Team',
			'foreignKey' => 'team_1_id'
		),
		'Team_2' => array(
			'className' => 'Team',
			'foreignKey' => 'team_2_id'
		)
	);
	
	public function addGame($match_id, $game_id) {
		$this->log($match_id, 'debug');
		$this->log($game_id, 'debug');
		
		$match = $this->find('first', array(
			'contain' => array(
				'Game_1',
				'Game_2',
				'Team_1',
				'Team_2'
			),
			'conditions' => array(
				'Match.id' => $match_id
			)
		));
		
		$game = $this->Game->findById($game_id);
		
		//do we know who these teams are?
		if(!empty($game['Game']['red_team_id'])) {
			//is this game 1?
			if($match['Team_1']['id'] == $game['Game']['red_team_id']) {
				//yes it is
				$game['Game']['match_id'] = $match['Match']['id'];
				$game['Game']['red_team_id'] = $match['Team_1']['id'];
				$game['Game']['green_team_id'] = $match['Team_2']['id'];
			} elseif($match['Team_1']['id'] == $game['Game']['green_team_id']) {
				//nope it's game 2
				$game['Game']['match_id'] = $match['Match']['id'];
				$game['Game']['red_team_id'] = $match['Team_2']['id'];
				$game['Game']['green_team_id'] = $match['Team_1']['id'];
			}
		} else {
			//is game 1 already set?
			if(empty($match['Game_1']['id'])) {
				$game['Game']['match_id'] = $match['Match']['id'];
				$game['Game']['red_team_id'] = $match['Team_1']['id'];
				$game['Game']['green_team_id'] = $match['Team_2']['id'];
			} elseif(empty($match['Game_2']['id'])) {
				//yup, so this is game 2
				$game['Game']['match_id'] = $match['Match']['id'];
				$game['Game']['red_team_id'] = $match['Team_2']['id'];
				$game['Game']['green_team_id'] = $match['Team_1']['id'];
			}
		}
		$this->Game->save($game);
		$this->updatePoints($match_id);
	}
	
	public function updatePoints($match_id) {
		$match = $this->find('first', array(
			'contain' => array(
				'Game_1',
				'Game_2',
				'Team_1',
				'Team_2'
			),
			'conditions' => array(
				'Match.id' => $match_id
			)
		));
		
		//$this->log($this->getDataSource()->getLog(false, false), 'debug');
		//$this->log($match, 'debug');
		
		$team_1_points = 0;
		$team_2_points = 0;
		
		if(!empty($match['Game_1']['id'])) {
			if($match['Game_1']['winner'] == 'Red') {
				$team_1_points += 2;
			} elseif($match['Game_1']['winner'] == 'Green') {
				$team_2_points += 2;
			}
		}

		if(!empty($match['Game_2']['id'])) {
			if($match['Game_2']['winner'] == 'Red') {
				$team_2_points += 2;
			} elseif($match['Game_2']['winner'] == 'Green') {
				$team_1_points += 2;
			}
		}
		
		$this->log($team_1_points, 'debug');
		$this->log($team_2_points, 'debug');
			
		//both games are logged
		if(!empty($match['Game_1']['id']) && !empty($match['Game_2']['id'])) {
			if($team_1_points == $team_2_points) {
				//tie round, goes to score
				$team_1_total_score = $match['Game_1']['red_score'] + $match['Game_1']['red_adj'] + $match['Game_2']['green_score'] + $match['Game_2']['green_adj'];
				$this->log($team_1_total_score, 'debug');
				$team_2_total_score = $match['Game_1']['green_score'] + $match['Game_1']['green_adj'] + $match['Game_2']['red_score'] + $match['Game_2']['red_adj'];
				$this->log($team_2_total_score, 'debug');
				
				if($team_1_total_score > $team_2_total_score) {
					$team_1_points += 2;
				} elseif($team_1_total_score < $team_2_total_score) {
					$team_2_points += 2;
				} else {
					$team_1_points += 1;
					$team_2_points += 1;
				}
				
			} elseif($team_1_points > $team_2_points) {
				$team_1_points += 2;
			} else {
				$team_2_points += 2;
			}
		}
		
		$match['Match']['team_1_points'] = $team_1_points;
		$match['Match']['team_2_points'] = $team_2_points;
		$this->log($match, 'debug');
		if($this->save($match))
			$this->log('success', 'debug');
		else
			$this->log('fail', 'debug');
	}
}