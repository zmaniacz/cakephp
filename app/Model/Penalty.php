<?php
App::uses('AppModel', 'Model');
/**
 * Penalty Model
 *
 * @property Scorecard $Scorecard
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
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignKey' => 'scorecard_id'
		)
	);
}