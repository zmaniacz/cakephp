<?php

class Center extends AppModel {
	public $hasMany = array(
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'center_id'
		),
		'Player' => array(
			'className' => 'Player',
			'foreignKey' => 'center_id'
		),
		'Game' => array(
			'className' => 'Game',
			'foreignKey' => 'center_id'
		)
	);
	
	public function getCenterDetails($short_name) {
		$results = $this->find('first', array(
			'fields' => array('id','type'),
			'conditions' => array('short_name' => $short_name)
		));
		
		return $results;
	}

	public function findWithLastPlayedDate() {
		$results = $this->query("
			SELECT centers.id,
				centers.name,
				centers.short_name,
				MAX(games.game_datetime) as last_played
			FROM
				centers inner join games on games.center_id = centers.id
			GROUP BY centers.id
			ORDER BY centers.name ASC
		");

		foreach($results as $result) {
			$output[] = array_merge($result['centers'],$result[0]);
		}

		return $output;
	}
}
?>