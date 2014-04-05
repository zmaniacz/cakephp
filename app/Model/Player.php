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
	
	public function getMyTeammates($id) {
		$results = $this->Scorecard->query("
			select player_name,
			(select count(myTeammates.game_datetime)
			from scorecards myGames
			inner join scorecards myTeammates
			on myGames.game_id = myTeammates.game_id
			and myGames.team = myTeammates.team
			where myTeammates.player_id = scorecards.player_id
			and myGames.player_id = $id
			and myTeammates.player_id != $id) as same_team_count,
			(select count(myTeammates.game_datetime)
			from scorecards myGames
			inner join scorecards myTeammates
			on myGames.game_id = myTeammates.game_id
			and myGames.team != myTeammates.team
			where myTeammates.player_id = scorecards.player_id
			and myGames.player_id = $id
			and myTeammates.player_id != $id) as other_team_count
			from scorecards
			where player_id != $id
		");
		
		$teammates = array();
		
		foreach($results as $line) {
			if($line[0]['same_team_count'] + $line[0]['other_team_count'] >= 10) {
				$teammates[$line['scorecards']['player_name']] = array(
					'player_name'		=> $line['scorecards']['player_name'],
					'same_team_count' 	=> $line[0]['same_team_count'], 
					'other_team_count' 	=> $line[0]['other_team_count'], 
					'same_team_percent' => ($line[0]['same_team_count'] / ($line[0]['same_team_count'] + $line[0]['other_team_count']))
				);
			}
		}
		
		return $teammates;
	}
}