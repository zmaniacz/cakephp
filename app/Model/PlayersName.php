<?php
App::uses('AppModel', 'Model');
/**
 * PlayersName Model
 *
 * @property Player $Player
 */
class PlayersName extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'player_name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'player_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
