<?php

class Player extends AppModel {
	public $hasMany = 'Scorecard';
	
	public function getPlayerStats($id, $role = null) {
		$conditions = array();
		if(!is_null($role))
			$conditions[] = array('position' => $role);
		
		return $this->find('all', array(
			'conditions' => array('id' => $id),
			'contain' => array(
				'Scorecard' => array(
					'conditions' => $conditions,
					'order' => 'Scorecard.game_datetime'
				)
			)
		));
	}
	
	public function getPlayerGames($id) {
		$games = $this->query("SELECT * 
			FROM  games,
			(
				SELECT game_id
				FROM  scorecards 
				WHERE player_id=$id
			) AS scores
			WHERE games.id = scores.game_id
			ORDER BY  games.game_datetime DESC"
		);
		
		return $games;
	}
	
	public function getAverageScoreByPosition($id = null) {
		$conditions = array();
		if(!is_null($id))
			$conditions[] = array('player_id' => $id);
		
		$raw = $this->Scorecard->find('all', array(
			'fields' => array(
				'position',
				'AVG(score) as avg_score'
			),
			'conditions' => $conditions,
			'group' => 'position',
			'order' => 'position'
		));
		
		$averages = array('Ammo Carrier' => 0, 'Commander' => 0, 'Heavy Weapons' => 0, 'Medic' => 0, 'Scout' => 0);
		foreach($raw as $item) {
			$averages[$item['Scorecard']['position']] = $item[0]['avg_score'];
		}
		
		return $averages;
	}
	
	public function getAverageMVPByPosition($id = null) {
		$conditions = array();
		if(!is_null($id))
			$conditions[] = array('player_id' => $id);
		
		$raw = $this->Scorecard->find('all', array(
			'fields' => array(
				'position',
				'AVG(mvp_points) as avg_mvp'
			),
			'conditions' => $conditions,
			'group' => 'position',
			'order' => 'position'
		));
		
		$averages = array('Ammo Carrier' => 0, 'Commander' => 0, 'Heavy Weapons' => 0, 'Medic' => 0, 'Scout' => 0);
		foreach($raw as $item) {
			$averages[$item['Scorecard']['position']] = $item[0]['avg_mvp'];
		}
		
		return $averages;
	}
}