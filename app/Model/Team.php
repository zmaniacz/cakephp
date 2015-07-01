<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 * @property League $League
 * @property Player $Player
 */
class Team extends AppModel {

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
		'League' => array(
			'className' => 'League',
			'foreignKey' => 'league_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'captain_id'
		)
	);
	
	public $hasMany = array(
		'Red_Game' => array(
			'className' => 'Game',
			'foreignkey' => 'red_team_id'
		),
		'Green_Game' => array(
			'className' => 'Game',
			'foreignkey' => 'green_team_id'
		),
		'Match_Team1' => array(
			'className' => 'Match',
			'foreignKey' => 'team_1_id'
		),
		'Match_Team2' => array(
			'className' => 'Match',
			'foreignKey' => 'team_2_id'
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Player' => array(
			'className' => 'Player',
			'joinTable' => 'players_teams',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'player_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);
	
	public function getTeamMatches($team_id, $state) {
		$league_id = $state['leagueID'];
		
		$rounds = $this->League->find('first',array(
			'contain' => array(
				'Round' => array(
					'Match' => array(
						'Game_1',
						'Game_2',
						'conditions' => array(
							'OR' => array(
								'Match.team_1_id' => $team_id,
								'Match.team_2_id' => $team_id
							)
						)
					)
				)
			),
			'conditions' => array(
				'League.id' => $league_id
			)
		));
		
		return $rounds;
	}

}
