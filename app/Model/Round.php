<?php

class Round extends AppModel {
	public $hasMany = array(
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'round_id'
		)
	);

	public $belongsTo = array(
		'League' => array(
			'className' => 'League',
			'foreignKey' => 'league_id'
		)
	);
}