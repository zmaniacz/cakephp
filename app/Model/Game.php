<?php

class Game extends AppModel {
	public $hasMany = array(
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'game_id'
		),
		'Team' => array(
			'className' => 'Team',
			'foreignkey' => 'game_id'
		),
		'GameResult' => array(
			'className' => 'GameResult',
			'foreignKey' => 'game_id'
		)
	);

	public $hasOne = array(
		'Red_Team' => array(
			'className' => 'Team',
			'foreignkey' => 'game_id',
			'conditions' => array('Red_Team.color' => 'red')
		),
		'Green_Team' => array(
			'className' => 'Team',
			'foreignkey' => 'game_id',
			'conditions' => array('Green_Team.color' => 'green')
		)
	);

	public $belongsTo = array(
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id'
		),
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id'
		),
		'League' => array(
			'className' => 'League',
			'foreignKey' => 'league_id'
		)
	);
	
	public function getOverallStats($state) {
		$conditions = array();
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Game.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Game.type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Game.league_id' => $state['leagueID']);
	
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

		return $overall;
	}

	public function getGameDetails($id) {
		$conditions[] = array('Game.id' => $id);

		$result = $this->find('first', array(
			'contain' => array(
				'Red_Team' => array(
					'Scorecard' => array(
						'Penalty',
						'Hit'
					)
				),
				'Green_Team' => array(
					'Scorecard' => array(
						'Penalty',
						'Hit'
					)
				),
				'Match' => array(
					'Round'
				)
			),
			'conditions' => $conditions
		));

		return $result;
	}

	public function getGameList($date = null, $state) {
		$conditions = array();
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Game.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Game.type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Game.league_id' => $state['leagueID']);
			
		if(!is_null($date))
			$conditions[] = array('DATE(Game.game_datetime)' => $date);

		$games = $this->find('all', array(
			'contain' => array(
				'Red_Team' => array(
					'LeagueTeam'
				),
				'Green_Team' => array(
					'LeagueTeam'
				),
				'Match' => array(
					'Round'
				)
			),
			'conditions' => $conditions,
			'order' => 'Game.game_datetime ASC'
		));
		return $games;
	}
	
	public function updateGameWinner($id) {
		$game = $this->find('first', array(
			'contain' => array(
				'Red_Team' =>array(
					'Scorecard' => array(
						'fields' => array('id'),
						'Penalty'
					)
				),
				'Green_Team' =>array(
					'Scorecard' => array(
						'fields' => array('id'),
						'Penalty'
					)
				),
			),
			'conditions' => array(
				'Game.id' => $id
			)
			
		));
		
		$elim_bonus = 10000;
		$game['Red_Team']['bonus_score'] = 0;
		$game['Red_Team']['penalty_score'] = 0;
		$game['Red_Team']['winner'] = 0;
		$game['Green_Team']['bonus_score'] = 0;
		$game['Green_Team']['penalty_score'] = 0;
		$game['Green_Team']['winner'] = 0;

		//apply penalties
		foreach($game['Red_Team']['Scorecard'] as $scorecard) {
			if(!empty($scorecard['Penalty'])) {
				foreach($scorecard['Penalty'] as $penalty) {
						$game['Red_Team']['penalty_score'] += $penalty['value'];
				}
			}
		}

		foreach($game['Green_Team']['Scorecard'] as $scorecard) {
			if(!empty($scorecard['Penalty'])) {
				foreach($scorecard['Penalty'] as $penalty) {
						$game['Green_Team']['penalty_score'] += $penalty['value'];
				}
			}
		}
		
		//Apply the elim bonus if the ooposing team was eliminated...both teams can get the bonus
		//in the case that the elim bonus does not produce a win by score, the bonus is increased until it does
		if($game['Red_Team']['eliminated_opponent'])
			$game['Red_Team']['bonus_score'] += $elim_bonus;

		if($game['Green_Team']['eliminated_opponent'])
			$game['Green_Team']['bonus_score'] += $elim_bonus;

		if($game['Red_Team']['eliminated_opponent'] xor $game['Green_Team']['eliminated_opponent']) {
			if($game['Red_Team']['eliminated_opponent']) {
				if($game['Red_Team']['raw_score'] + $game['Red_Team']['bonus_score'] + $game['Red_Team']['penalty_score'] < $game['Green_Team']['raw_score'] + $game['Green_Team']['bonus_score'] + $game['Green_Team']['penalty_score']) {
					$game['Red_Team']['bonus_score'] += ($game['Green_Team']['raw_score'] + $game['Green_Team']['bonus_score'] + $game['Green_Team']['penalty_score']) - ($game['Red_Team']['raw_score'] + $game['Red_Team']['bonus_score'] + $game['Red_Team']['penalty_score']) + 1;
				}
			}

			if($game['Green_Team']['eliminated_opponent']) {
				if($game['Green_Team']['raw_score'] + $game['Green_Team']['bonus_score'] + $game['Green_Team']['penalty_score'] < $game['Red_Team']['raw_score'] + $game['Red_Team']['bonus_score'] + $game['Red_Team']['penalty_score']) {
					$game['Green_Team']['bonus_score'] += ($game['Red_Team']['raw_score'] + $game['Red_Team']['bonus_score'] + $game['Red_Team']['penalty_score']) - ($game['Green_Team']['raw_score'] + $game['Green_Team']['bonus_score'] + $game['Green_Team']['penalty_score']) + 1;
				}
			}
		}
		
		if($game['Red_Team']['raw_score'] + $game['Red_Team']['bonus_score'] + $game['Red_Team']['penalty_score'] > $game['Green_Team']['raw_score'] + $game['Green_Team']['bonus_score'] + $game['Green_Team']['penalty_score']) {
			$game['Red_Team']['winner'] = 1;
			$game['Game']['winner'] = 'red';
		} else {
			$game['Green_Team']['winner'] = 1;
			$game['Game']['winner'] = 'green';
		}
		
		$this->saveAll($game);
	}
}