<?php
App::uses('AppModel', 'Model');
/**
 * EventTeam Model
 *
 * @property Event $Event
 * @property Team $Team
 */
class EventTeam extends AppModel {

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
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
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
			'foreignKey' => 'event_team_id',
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

	public function getTeamMatches($team_id, $event_id) {
		$rounds = $this->Event->find('first',array(
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
				'Event.id' => $event_id
			)
		));
		
		return $rounds;
	}

}
