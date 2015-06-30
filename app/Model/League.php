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
		$league_id = $state['leagueID'];
		
		$teams = $this->Team->find('all', array(
			'conditions' => array(
				'Team.league_id' => $league_id
			),
			'order' => 'points DESC'
		));
		return $teams;
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

	//this sauce is bad
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
		
		$matches = $this->Round->Match->find('all', array(
			'contain' => array(
				'Game_1' => array('fields' => array('id')),
				'Game_2' => array('fields' => array('id')),
				'Round',
				'Team_1' => array('fields' => array('id', 'name')),
				'Team_2' => array('fields' => array('id', 'name'))
			),
			'conditions' => $conditions
		));

		foreach($matches as $match) {
			$match_list[$match['Match']['id']] = "R".$match['Round']['round']." M".$match['Match']['match']." - ".$match['Team_1']['name']." v ".$match['Team_2']['name'];
		}
		
		if(!empty($game['Game']['match_id'])) {
			$match = $this->Round->Match->find('first', array(
				'contain' => array(
					'Round',
					'Team_1' => array('fields' => array('id', 'name')),
					'Team_2' => array('fields' => array('id', 'name'))
				),
				'conditions' => array(
					'Match.id' => $game['Game']['match_id']
				)
			));
			$match_list[$match['Match']['id']] = "R".$match['Round']['round']." M".$match['Match']['match']." - ".$match['Team_1']['name']." v ".$match['Team_2']['name'];
		}
		
		return $match_list;
	}
}
