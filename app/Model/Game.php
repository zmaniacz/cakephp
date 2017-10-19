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
		),
		'TeamPenalties' => array(
			'className' => 'TeamPenalties',
			'foreignKey' => 'game_id'
		),
		'Red_TeamPenalties' => array(
			'className' => 'TeamPenalties',
			'foreignkey' => 'game_id',
			'conditions' => array('Red_TeamPenalties.team_color' => 'red')
		),
		'Green_TeamPenalties' => array(
			'className' => 'TeamPenalties',
			'foreignkey' => 'game_id',
			'conditions' => array('Green_TeamPenalties.team_color' => 'green')
		),
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
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id'
		)
	);

	public $validate = array(
		'game_datetime' => array(
			'rule' => array('isUnique', array('game_datetime', 'center_id'), false),
			'message' => "Non-Unique center/game combination"
		)
	);
	
	public function getOverallStats($state) {
		$conditions = array();
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Game.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Game.type' => $state['gametype']);
		
		if(isset($state['eventID']) && $state['eventID'] > 0)
			$conditions[] = array('Game.event_id' => $state['eventID']);
	
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
		return $this->find('first', array(
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
				),
				'Event',
				'Red_TeamPenalties',
				'Green_TeamPenalties'
			),
			'conditions' => array('Game.id' => $id)
		));
	}

	public function getMatchups($id) {
		$conditions[] = array('Game.id' => $id);

		$red_team = $this->find('first', array(
			'fields' => array('id'),
			'contain' => array(
				'Red_Scorecard' => array(
					'fields' => array(
						'player_id',
						'player_name',
						'position',
						'mvp_points'
					),
					'order' => 'position ASC, mvp_points DESC'
				)
			),
			'conditions' => $conditions
		));
		
		$green_team = $this->find('first', array(
			'fields' => array('id'),
			'contain' => array(
				'Green_Scorecard' => array(
					'fields' => array(
						'player_id',
						'player_name',
						'position',
						'mvp_points'
					),
					'order' => 'position ASC, mvp_points DESC'
				)
			),
			'conditions' => $conditions
		));

		$scout_counter = 1;
		foreach($red_team['Red_Scorecard'] as &$score) {
			if($score['position'] == 'Scout') {
				$score['position'] = 'Scout'.$scout_counter;
				$scout_counter++;
			}
		}

		$scout_counter = 1;
		foreach($green_team['Green_Scorecard'] as &$score) {
			if($score['position'] == 'Scout') {
				$score['position'] = 'Scout'.$scout_counter;
				$scout_counter++;
			}
		}
		$data = array();
		foreach($red_team['Red_Scorecard'] as $red_score) {
			foreach($green_team['Green_Scorecard'] as $green_score) {
				if($red_score['position'] == $green_score['position']) {
					$data[] = array(
						'position' => $red_score['position'],
						'red_player_id' => $red_score['player_id'],
						'red_player_name' => $red_score['player_name'],
						'green_player_id' => $green_score['player_id'],
						'green_player_name' => $green_score['player_name'],
						'matchup' => $this->Scorecard->getComparison($red_score['player_id'], $green_score['player_id'])
					);
				}
			}
		}
		return $data;
	}

	public function getGameList($state) {
		$conditions = array();
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Event.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Event.type' => $state['gametype']);
		
		if(isset($state['eventID']) && $state['eventID'] > 0)
			$conditions[] = array('Event.id' => $state['eventID']);

		$games = $this->find('all', array(
			'contain' => array(
				'Red_Team' => array(
					'EventTeam'
				),
				'Green_Team' => array(
					'EventTeam'
				),
				'Match' => array(
					'Round'
				),
				'Event'
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
				'Red_TeamPenalties',
				'Green_TeamPenalties'
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

		//load team penalties in
		foreach($game['Red_TeamPenalties'] as $team_penalty) {
			$game['Red_Team']['penalty_score'] += $team_penalty['value'];
		}

		foreach($game['Green_TeamPenalties'] as $team_penalty) {
			$game['Green_Team']['penalty_score'] += $team_penalty['value'];
		}
		
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

		//if you elimed, you win
		if($game['Red_Team']['eliminated_opponent'])
			$game['Game']['winner'] = 'red';
		elseif($game['Green_Team']['eliminated_opponent'])
			$game['Game']['winner'] = 'green';
		
		$this->saveAll($game);

		$game = $this->find('first', array(
			'contain' => array(
				'Match'
			),
			'conditions' => array(
				'Game.id' => $id
			)
		));
		
		if(isset($game['Match']['id'])) {
			$this->Match->updatePoints($game['Match']['id']);
		}
	}

	public function getPrevNextGame($game_id) {
		$game = $this->find('first', array(
			'contain' => array(
				'Event'
			),
			'conditions' => array(
				'Game.id' => $game_id
			)
		));

		if($game['Event']['is_comp']) {
			App::import('Model', 'LeagueGame');
			$leagueGame = new LeagueGame();
			$results = $leagueGame->find('neighbors', array(
				'field' => 'game_id',
				'value' => $game_id,
				'conditions' => array(
					'event_id' => $game['Game']['event_id']
				)
			));

			$results = array_map(function($position) {
				if(isset($position['LeagueGame']))
					return array(
						'Game' => $position['LeagueGame']
					);
			}, $results);
		} else {
			$results = $this->find('neighbors', array(
				'field' => 'id',
				'value' => $game_id,
				'conditions' => array(
					'Game.event_id' => $game['Event']['id']
				),
				'order' => 'game_datetime DESC'
			));

			$results = array_map(function($position) {
				if(isset($position['Game'])) {
					$position['Game']['game_id'] = $position['Game']['id'];
					return $position;
				}
			}, $results);
		}

		return $results;
	}

	public function getDatabaseStats() {
		$stats = $this->find('first', array(
			'fields' => array(
				'COUNT(id) as total_games'
			)
		));

		return $stats;
	}

	public function fixSocialGameNames($date, $center_id) {
		//christ
		$games = $this->find('all', array(
			'conditions' => array(
				'center_id' => $center_id,
				'DATE(game_datetime)' => $date
			),
			'order' => 'game_datetime ASC'
		));

		$game_counter=1;
		foreach($games as $game) {
			$game['Game']['game_name'] = "G{$game_counter}";
			$this->save($game);
			$game_counter++;
		}
	}
}