<?php
//we define team 1 to be the team that plays red in game 1 of the match
class Match extends AppModel {
	public $hasMany = array(
		'Game' => array(
			'className' => 'Game',
			'foreignkey' => 'match_id'
		)
	);

	public $belongsTo = array(
		'Round' => array(
			'className' => 'Round',
			'foreignKey' => 'round_id'
		),
		'Team_1' => array(
			'className' => 'Team',
			'foreignKey' => 'team_1_id'
		),
		'Team_2' => array(
			'className' => 'Team',
			'foreignKey' => 'team_2_id'
		)
	);
}