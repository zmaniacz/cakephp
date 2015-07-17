<?php
App::uses('AppModel', 'Model');
/**
 * League Model
 *
 * @property Center $Center
 * @property Game $Game
 * @property Team $Team
 */
class League extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
			'foreignKey' => 'league_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'league_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Round' => array(
			'className' => 'Round',
			'foreignkey' => 'league_id'
		)
	);

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
		
		$conditions[] = array('Team.league_id' => $state['leagueID']);
		
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
			'conditions' => array('id' => $state['leagueID'])
		));
		
		$teams = $this->Team->find('list', array('conditions' => array('Team.league_id' => $state['leagueID'])));
		
		$standings = array();
		
		foreach($teams as $id => $name) {
			$standings[$id] = array('id' => $id, 'name' => $name, 'points' => 0, 'played' => 0, 'won' => 0, 'lost' => 0, 'matches_won' => 0, 'elims' => 0, 'for' => 0, 'against' => 0, 'ratio' => 0);
		}
		
		$this->log($rounds, 'debug');
		
		foreach($rounds['Round'] as $round) {
			foreach($round['Match'] as $match) {
				//Overall match points
				$standings[$match['team_1_id']]['points'] += $match['team_1_points'];
				$standings[$match['team_2_id']]['points'] += $match['team_2_points'];
				
				//Matches Won
				if($standings[$match['team_1_id']]['points'] > $standings[$match['team_2_id']]['points'])
					$standings[$match['team_1_id']]['matches_won'] += 1;
				else
					$standings[$match['team_2_id']]['matches_won'] += 1;
				
				if(!empty($match['Game_1'])) {
					if($match['Game_1']['winner'] == 'Red') {
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
					if($match['Game_2']['winner'] == 'Red') {
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
		
		/*$teams = $this->Team->find('all', array(
			'contain' => array(
				'Match_Team1' => array(
					'Game_1',
					'Game_2',
					'Round' => array(
						'conditions' => array('Round.is_finals' => '0')
					)
				),
				'Match_Team2' => array(
					'Game_1',
					'Game_2',
					'Round' => array(
						'conditions' => array('Round.is_finals' => '0')
					)
				)
			),
			'conditions' => $conditions,
			'order' => 'points DESC'
		));
		
		$standings = array();
		
		foreach($teams as $team) {
			$match_points = 0;
			$played = 0;
			$won = 0;
			$match_won = 0;
			$elims = 0;
			$total_points_for = 0;
			$total_points_against = 0;
			
			foreach($team['Match_Team1'] as $match_1) {
				$match_points += $match_1['team_1_points'];
				
				if(!empty($match_1['Game_1'])) {
					$played++;
					
					if($match_1['Game_1']['winner'] == 'Red')
						$won++;
						
					if($match_1['Game_1']['green_eliminated'] == 1)
						$elims++;
						
					$total_points_for += $match_1['Game_1']['red_score'] + $match_1['Game_1']['red_adj'];
					$total_points_against += $match_1['Game_1']['green_score'] + $match_1['Game_1']['green_adj'];
				}
				
				if(!empty($match_1['Game_2'])) {
					$played++;
					
					if($match_1['Game_2']['winner'] == 'Green')
						$won++;
						
					if($match_1['Game_2']['red_eliminated'] == 1)
						$elims++;
						
					$total_points_for += $match_1['Game_2']['green_score'] + $match_1['Game_2']['green_adj'];
					$total_points_against += $match_1['Game_2']['red_score'] + $match_1['Game_2']['red_adj'];
				}
			}
			
			foreach($team['Match_Team2'] as $match_2) {
				$match_points += $match_2['team_2_points'];
				
				if(!empty($match_2['Game_1'])) {
					$played++;
					
					if($match_2['Game_1']['winner'] == 'Green')
						$won++;
						
					if($match_2['Game_1']['red_eliminated'] == 1)
						$elims++;
						
					$total_points_for += $match_2['Game_1']['green_score'] + $match_2['Game_1']['green_adj'];
					$total_points_against += $match_2['Game_1']['red_score'] + $match_2['Game_1']['red_adj'];
				}
				
				if(!empty($match_2['Game_2'])) {
					$played++;
					
					if($match_2['Game_2']['winner'] == 'Red')
						$won++;
						
					if($match_2['Game_2']['green_eliminated'] == 1)
						$elims++;
						
					$total_points_for += $match_2['Game_2']['red_score'] + $match_2['Game_2']['red_adj'];
					$total_points_against += $match_2['Game_2']['green_score'] + $match_2['Game_2']['green_adj'];
				}
			}
			
			$standings[] = array('id' => $team['Team']['id'], 'name' => $team['Team']['name'], 'points' => $match_points, 'played' => $played, 'won' => $won, 'lost' => $played-$won, 'matches_won' => 0, 'elims' => $elims, 'for' => $total_points_for, 'against' => $total_points_against, 'ratio' => (($total_points_for > 0) ? $total_points_for/$total_points_against : 0));
		}
		
		
		if(!empty($standings)) {
			foreach ($standings as $key => $row) {
			    $arr_points[$key]  = $row['points'];
				$arr_ratio[$key]  = $row['ratio'];
			}
			
			array_multisort($arr_points, SORT_DESC, $arr_ratio, SORT_DESC, $standings);
		}*/
		
		return $standings;
	}

	public function getTeams($league_id) {
		$teams = $this->Team->find('list', array(
			'conditions' => array(
				'league_id' => $league_id
			),
			'order' => 'name ASC'
		));

		return $teams;
	}
	
	public function getLeagueDetails($state) {
		$league_id = $state['leagueID'];
		
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
				'League.id' => $league_id
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
			'conditions' => array('League.id' => $game['Game']['league_id'])
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
