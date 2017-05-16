<?php
App::uses('AppModel', 'Model');
/**
 * TeamPenalty Model
 *
 * @property Scorecard $Scorecard
 */
class TeamPenalty extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'type';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id'
		)
	);
}
