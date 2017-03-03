<?php
App::uses('AppModel', 'Model');
/**
 * Team Model
 *
 * @property Game $Game
 * @property LeagueTeam $LeagueTeam
 * @property Scorecard $Scorecard
 */
class Team extends AppModel {


	// The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EventTeam' => array(
			'className' => 'EventTeam',
			'foreignKey' => 'event_team_id',
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
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignKey' => 'team_id',
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

	public function getSummaryStats($id) {
		$conditions[] = array('Team.id' => $id);

		$result = $this->find('first', array(
			'contain' => array(
				'Scorecard' => array(
					'fields' => array(
						'SUM(medic_hits) as medic_hits',
						'SUM(missile_hits) as missile_hits',
						'SUM(nukes_detonated) as nukes_detonated',
						'SUM(lives_left) as lives_left',
						'SUM(shots_left) as shots_left',
						'( SUM(shot_opponent) / SUM(times_zapped) ) as hit_diff',
						'SUM(resupplies) as resupplies',
						'SUM(bases_destroyed) as bases_destroyed',
						'AVG(accuracy) as accuracy',
						'SUM(mvp_points) as mvp_points'
					)
				)
			),
			'conditions' => $conditions
		));

		return $result;
	}

}
