<?php
App::uses('AppModel', 'Model');
/**
 * LeagueTeam Model
 *
 * @property League $League
 * @property Team $Team
 */
class LeagueTeam extends AppModel {

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
		'League' => array(
			'className' => 'League',
			'foreignKey' => 'league_id',
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
		'Team' => array(
			'className' => 'Team',
			'foreignKey' => 'league_team_id',
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
