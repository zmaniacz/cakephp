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
	
	public function getTeamStandings($league_id) {
		$red_teams = $this->find('all', array(
			'contain' => array('Red_Game', 'Green_Game'),
			'conditions' => array(
				'Team.league_id' => $league_id
			)
		));
		var_dump($red_teams);
		return $red_teams;
	}

}
