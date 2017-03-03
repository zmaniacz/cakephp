<?php
App::uses('AppModel', 'Model');
/**
 * Event Model
 *
 * @property Center $Center
 * @property Game $Game
 */
class Event extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'event_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public function getEventList($type = null, $limit = null) {
		$conditions[] = array();
		
		if(isset($type))
			$conditions[] = array('Event.type' => $type);
		
		$this->virtualFields['last_gametime'] = 0;
		$events = $this->find('all', array(
			'fields' => array(
				'Event.id',
				'Event.name',
				'Event.description',
				'Event.type',
				'Event.is_comp',
				'Event.center_id',
				'Center.id',
				'Center.name',
				'Center.short_name',
				'MAX(Game.game_datetime) as Event__last_gametime'
			),
			'joins' => array(
				array(
					'table' => 'games',
					'alias' => 'Game',
					'type' => 'LEFT',
					'conditions' => array(
						'Event.id = Game.event_id'
					)
				),
				array(
					'table' => 'centers',
					'alias' => 'Center',
					'type' => 'LEFT',
					'conditions' => array(
						'Event.center_id = Center.id'
					)
				)
			),
			'conditions' => $conditions,
			'group' => 'Event.id',
			'order' => 'Event__last_gametime DESC',
			'limit' => $limit
		));

		return $events;
	}

	public function getGameList($event_id) {
		$games = $this->find('first', array(
			'contain' => array(
				'Game' => array(
					'Red_Team' => array(
						'EventTeam'
					),
					'Green_Team' => array(
						'EventTeam'
					)
				)
			),
			'conditions' => array('id' => $event_id)
		));

		return $games;
	}

	public function getScorecards($event_id) {
		$scorecards = $this->find('first', array(
			'contain' => array(
				'Game' => array(
					'Red_Team' => array(
						'Scorecard'
					),
					'Green_Team' => array(
						'Scorecard'
					)
				)
			),
			'conditions' => array('id' => $event_id)
		));

		return $scorecards;
	}

	public function getSummaryStats($event_id) {
		$scorecard_ids = $this->_getScorecardIds($event_id);

		$scorecard = ClassRegistry::init('Scorecard');

		$stats = $scorecard->find('all', array(
			'fields' => array(
				'player_id',
				'MIN(Scorecard.score) as min_score',
				'ROUND(AVG(Scorecard.score)) as avg_score',
				'MAX(Scorecard.score) as max_score',
				'MIN(Scorecard.mvp_points) as min_mvp',
				'AVG(Scorecard.mvp_points) as avg_mvp',
				'MAX(Scorecard.mvp_points) as max_mvp',	
				'AVG(Scorecard.accuracy) as avg_acc',
				'(SUM(Scorecard.shot_opponent)/SUM(Scorecard.times_zapped)) as hit_diff',
				'SUM(Scorecard.medic_hits) as medic_hits',
				'(SUM(Scorecard.team_elim)/COUNT(Scorecard.game_datetime)) as elim_rate',
				'COUNT(Scorecard.game_datetime) as games_played',
				'SUM(GameResult.won) as games_won'
			),
			'contain' => array(
				'Player' => array(
					'fields' => array('id', 'player_name')
				)
			),
			'joins' => array(
				array(
					'table' => 'game_results',
					'alias' => 'GameResult',
					'type' => 'LEFT',
					'conditions' => array(
						'GameResult.scorecard_id = Scorecard.id'
					)
				)
			),
			'conditions' => array(
				'Scorecard.id' => $scorecard_ids
			),
			'group' => "Scorecard.player_id",
			'order' => 'avg_mvp DESC'
		));

		return $stats;
	}

	public function getMedicHitStats($event_id) {
		$conditions = array();
	
		$scorecard_ids = $this->_getScorecardIds($event_id);

		$scorecard = ClassRegistry::init('Scorecard');
		$scorecards = $scorecard->find('all', array(
			'fields' => array(
				'player_id',
				'position',
				'SUM(Scorecard.medic_hits) as total_medic_hits',
				'COUNT(Scorecard.game_datetime) as games_played',
			),
			'contain' => array(
				'Player' => array(
					'fields' => array('id', 'player_name')
				)
			),
			'conditions' => array(
				'Scorecard.id' => $scorecard_ids
			),
			'group' => 'Scorecard.player_id, Scorecard.position HAVING total_medic_hits > 0',
			'order' => 'total_medic_hits DESC'
		));

		$response = array();

		foreach($scorecards as $scorecard) {
			if(!isset($response[$scorecard['Scorecard']['player_id']])) {
				$response[$scorecard['Scorecard']['player_id']] = array(
					'player_id' => $scorecard['Scorecard']['player_id'],
					'player_name' => $scorecard['Player']['player_name'],
					'total_medic_hits' => 0,
					'total_games_played' => 0
				);
			}
			$response[$scorecard['Scorecard']['player_id']][$scorecard['Scorecard']['position']] = array(
				'medic_hits' => $scorecard[0]['total_medic_hits'],
				'games_played' => $scorecard[0]['games_played']
			);
			$response[$scorecard['Scorecard']['player_id']]['total_medic_hits'] += $scorecard[0]['total_medic_hits'];
			$response[$scorecard['Scorecard']['player_id']]['total_games_played'] += $scorecard[0]['games_played'];
		}

		return $response;
	}

	protected function _getGameIds($event_id, $show_rounds = true, $show_finals = true) {
		$events = $this->find('first', array(
			'fields' => array('id'),
			'contain' => array(
				'Game' => array(
					'fields' => array('id')
				)
			),
			'conditions' => array('id' => $event_id)
		));

		$game_ids = array();

		foreach($events['Game'] as $game) {
			$game_ids[] = $game['id'];
		}

		return $game_ids;
	}

	protected function _getScorecardIds($event_id, $show_rounds = true, $show_finals = true) {
		$events = $this->find('first', array(
			'fields' => array('id'),
			'contain' => array(
				'Game' => array(
					'fields' => array('id'),
					'Scorecard' => array(
						'fields' => array('id')
					)
				)
			),
			'conditions' => array('id' => $event_id)
		));

		$scorecard_ids = array();

		foreach($events['Game'] as $game) {
			foreach($game['Scorecard'] as $scorecard) {
				$scorecard_ids[] = $scorecard['id'];
			}
		}

		return $scorecard_ids;
	}



	/////NONE OF THE BELOW WORKS
	public function getLeagues($state) {
		$leagues = $this->find('all', array(
			'contain' => array(
				'Center'
			),
			'order' => 'League.name ASC'
		));

		return $leagues;
	}

	public function getTeamStandings($state) {
		$conditions = array();
		
		$conditions[] = array('Team.event_id' => $state['eventID']);
		
		$rounds = $this->find('first', array(
			'contain' => array(
				'Round' => array(
					'Match' => array(
						'Game_1',
						'Game_2'
					),
					'conditions' => array('Round.is_finals' => '0')
				)
			),
			'conditions' => array('id' => $state['eventID'])
		));
		
		$teams = $this->Team->find('list', array('conditions' => array('Team.event_id' => $state['eventID'])));
		
		$standings = array();
		
		foreach($teams as $id => $name) {
			$standings[$id] = array('id' => $id, 'name' => $name, 'points' => 0, 'played' => 0, 'won' => 0, 'lost' => 0, 'matches_played' => 0, 'matches_won' => 0, 'elims' => 0, 'for' => 0, 'against' => 0, 'ratio' => 0);
		}
		
		
		foreach($rounds['Round'] as $round) {
			foreach($round['Match'] as $match) {
				//Overall match points
				if(!is_null($match['team_1_id']) || !is_null($match['team_2_id'])) {
					$standings[$match['team_1_id']]['points'] += $match['team_1_points'];
					$standings[$match['team_2_id']]['points'] += $match['team_2_points'];
					
					//Matches Won
					if(!is_null($match['team_1_points']) && !is_null($match['team_2_points'])) {
						if($match['team_1_points'] + $match['team_2_points'] == 6) {
							if($match['team_1_points'] > $match['team_2_points'])
								$standings[$match['team_1_id']]['matches_won'] += 1;
							elseif($match['team_1_points'] < $match['team_2_points'])
								$standings[$match['team_2_id']]['matches_won'] += 1;
						}
					}
					
					if(!empty($match['Game_1'])) {
						if($match['Game_1']['winner'] == 'red') {
							$standings[$match['team_1_id']]['won'] += 1;
							$standings[$match['team_2_id']]['lost'] += 1;
						} else {
							$standings[$match['team_1_id']]['lost'] += 1;
							$standings[$match['team_2_id']]['won'] += 1;
						}
						
						$standings[$match['team_1_id']]['for'] += $match['Game_1']['red_score'] + $match['Game_1']['red_adj'];
						$standings[$match['team_2_id']]['against'] += $match['Game_1']['red_score'] + $match['Game_1']['red_adj'];
						$standings[$match['team_2_id']]['for'] += $match['Game_1']['green_score'] + $match['Game_1']['green_adj'];
						$standings[$match['team_1_id']]['against'] += $match['Game_1']['green_score'] + $match['Game_1']['green_adj'];
						
						$standings[$match['team_1_id']]['played'] += 1;
						$standings[$match['team_2_id']]['played'] += 1;
						
						if($match['Game_1']['red_eliminated'])
							$standings[$match['team_2_id']]['elims'] += 1;
						
						if($match['Game_1']['green_eliminated'])
							$standings[$match['team_1_id']]['elims'] += 1;
					}
					
					if(!empty($match['Game_2'])) {
						if($match['Game_2']['winner'] == 'red') {
							$standings[$match['team_2_id']]['won'] += 1;
							$standings[$match['team_1_id']]['lost'] += 1;
						} else {
							$standings[$match['team_2_id']]['lost'] += 1;
							$standings[$match['team_1_id']]['won'] += 1;
						}
						
						$standings[$match['team_2_id']]['for'] += $match['Game_2']['red_score'] + $match['Game_2']['red_adj'];
						$standings[$match['team_1_id']]['against'] += $match['Game_2']['red_score'] + $match['Game_2']['red_adj'];
						$standings[$match['team_1_id']]['for'] += $match['Game_2']['green_score'] + $match['Game_2']['green_adj'];
						$standings[$match['team_2_id']]['against'] += $match['Game_2']['green_score'] + $match['Game_2']['green_adj'];
						
						$standings[$match['team_1_id']]['played'] += 1;
						$standings[$match['team_2_id']]['played'] += 1;
						
						if($match['Game_2']['red_eliminated'])
							$standings[$match['team_1_id']]['elims'] += 1;
						
						if($match['Game_2']['green_eliminated'])
							$standings[$match['team_2_id']]['elims'] += 1;
					}
				}
			}
		}
		
		foreach($standings as &$standing) {
			if($standing['against'] > 0)
				$standing['ratio'] = $standing['for']/$standing['against'];
			
			$standing['matches_played'] = $standing['played']/2;
		}
		
		if(!empty($standings)) {
			foreach ($standings as $key => $row) {
			    $arr_points[$key]  = $row['points'];
				$arr_ratio[$key]  = $row['ratio'];
			}
			
			array_multisort($arr_points, SORT_DESC, $arr_ratio, SORT_DESC, $standings);
		}
		
		return $standings;
	}

	public function getTeams($event_id) {
		$teams = $this->Team->find('list', array(
			'conditions' => array(
				'event_id' => $event_id
			),
			'order' => 'name ASC'
		));

		return $teams;
	}
	
	public function getLeagueDetails($state) {
		$event_id = $state['eventID'];
		
		$rounds = $this->find('first',array(
			'contain' => array(
				'Round' => array(
					'Match' =>array(
						'Game_1',
						'Game_2'
					)
				)
			),
			'conditions' => array(
				'League.id' => $event_id
			)
		));
		
		return $rounds;
	}

	public function getAvailableMatches($game = null) {
		$conditions = array();
		$match_list = array();
		
		if(!is_null($game)) {
			if(!is_null($game['Game']['red_team_id'])) {
				$conditions[] = array('OR' => array(
					array(
						'Game_1.id' => null,
						'Match.team_1_id' => $game['Game']['red_team_id']
					),
					array(
						'Game_2.id' => null,
						'Match.team_2_id' => $game['Game']['red_team_id']
					)
				));
			} else {
				$conditions = array(
					'OR' => array(
						'Game_1.id' => null,
						'Game_2.id' => null
					),
					'AND' => array(
						'Match.team_1_id NOT' => null,
						'Match.team_2_id NOT' => null,
					)
				);
			}
		}
		
		$league = $this->find('first', array(
			'contain' => array(
				'Round' => array(
					'Match' => array(
						'Game_1' => array('fields' => array('id')),
						'Game_2' => array('fields' => array('id')),
						'Team_1' => array('fields' => array('id', 'name')),
						'Team_2' => array('fields' => array('id', 'name')),
					)
				)
			),
			'conditions' => array('League.id' => $game['Game']['event_id'])
		));

		foreach($league['Round'] as &$round) {
			foreach($round['Match'] as $key => $match) {
				if(empty($match['Team_1']) || empty($match['Team_2']) || (!empty($match['Game_1']) && !empty($match['Game_2']))) {
					if($match['id'] != $game['Game']['match_id'])
						unset($round['Match'][$key]);
				}
			}
		}

		return $league;
	}

}
