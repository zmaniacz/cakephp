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
	
	public function updateGames($game) {
		//this is all shit
		//why am i doing all this validation on other models in the league model?
		//stupid
		//break it out, dispatch it out
		//use the existing model chain
		//you fucking dummy
		//also...stop setting league round match what the fuck ever form the gme page
		//set it form the competition page
		//dropdowns like a motherfucker
		$this->unbindModel(array(
			'hasMany' => array('Round')
		));
		
		$this->bindModel(array(
			'hasOne' => array(
				'Round' => array(
					'className' => 'Round',
					'foreignkey' => 'league_id'
				)
			)
		));
		
		$this->Round->unbindModel(array(
			'hasMany' => array('Match')
		));
		
		$this->Round->bindModel(array(
			'hasOne' => array(
				'Match' => array(
					'className' => 'Match',
					'foreignkey' => 'round_id'
				)
			)
		));
		
		$league = $this->find('first', array(
			'contain' => array(
				'Round' => array(
					'Match' => array(
						'conditions' => array(
							'Match.match' => $game['league_match']
						)
					),
					'conditions' => array(
						'Round.round' => $game['league_round']
					)
				)
			),
			'conditions' => array(
				'League.id' => $game['league_id']
			)
		));
		
		if(empty($league['Round'])) {
			$league['Round']['league_id'] = $game['league_id'];
			$league['Round']['round'] = $game['league_round'];
			$this->Round->save($league);
			$round_id = $this->Round->id;
		} else {
			$round_id = $league['Round']['id'];
		}
		
		if(empty($league['Match'])) {
			$league['Match']['match'] = $game['league_match'];
			$league['Match']['round_id'] = $round_id;
			$this->Round->Match->save($league);
			//create the match
			//use the round id
			//set the teams
			//set the game id
		} else {
			//verify the teams are correct
		}
	}
}
