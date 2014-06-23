<?php
App::uses('AppModel', 'Model');
/**
 * Penalty Model
 *
 * @property Player $Player
 * @property Game $Game
 */
class Penalty extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'type';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'player_id'
		),
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'game_id'
		)
	);
}
