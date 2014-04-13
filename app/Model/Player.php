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
	
	public function getMedianScoreByPosition($id = null, $filter = null) {
		$fields = array('position','score');
		$conditions = array();
		$limit = null;
		
		if(!is_null($id)) {
			$fields[] = 'player_id';
			$conditions['player_id'] = $id;
		}
		
		if(!is_null($filter)) {
			if(isset($filter['numeric'])) {
				if($filter['numeric'] > 0) {
					$limit = $filter['numeric'];
				}
			}
			if(isset($filter['date'])) {
				if($filter['date'] > 0) {
					$conditions['DATEDIFF(DATE(NOW()),DATE(game_datetime)) <='] = $filter['date'];
				}
			}
			if(isset($filter['team'])) {
				if($filter['team'] != 0) {
					$conditions['team'] = $filter['team'];
				}
			}
		}

		$scores = $this->Scorecard->find('all', array(
			'fields' => $fields,
			'conditions' => $conditions,
			'order' => 'score ASC'
		));
		
		$commander = array();
		$heavy = array();
		$scout = array();
		$ammo = array();
		$medic = array();
		
		foreach($scores as $score) {
			switch($score['Scorecard']['position']) {
				case 'Commander':
					//echo count($commander);
					if( (is_null($limit)) || (!is_null($limit) && count($commander) <= $limit)) {
						$commander[] = $score['Scorecard']['score'];
					}
					break;
				case 'Heavy Weapons':
					if( (is_null($limit)) || (!is_null($limit) && count($heavy) <= $limit)) {
						$heavy[] = $score['Scorecard']['score'];
					}
					break;
				case 'Scout':
					if( (is_null($limit)) || (!is_null($limit) && count($scout) <= $limit)) {
						$scout[] = $score['Scorecard']['score'];
					}
					break;
				case 'Ammo Carrier':
					if( (is_null($limit)) || (!is_null($limit) && count($ammo) <= $limit)) {
						$ammo[] = $score['Scorecard']['score'];
					}
					break;
				case 'Medic':
					if( (is_null($limit)) || (!is_null($limit) && count($medic) <= $limit)) {
						$medic[] = $score['Scorecard']['score'];
					}
					break;
			}
		}
		
		$results = array(
			'commander' => (count($commander) > 0 ? $commander[floor((count($commander)-1)/2)] : 0),
			'heavy' => (count($heavy) > 0 ? $heavy[floor((count($heavy)-1)/2)] : 0),
			'scout' => (count($scout) > 0 ? $scout[floor((count($scout)-1)/2)] : 0),
			'ammo' => (count($ammo) > 0 ? $ammo[floor((count($ammo)-1)/2)] : 0),
			'medic' => (count($medic) > 0 ? $medic[floor((count($medic)-1)/2)] : 0)
		);
		
		return $results;
	}
	
	public function getMedianMVPByPosition($id = null, $filter = null) {
		$fields = array('position','mvp_points');
		$conditions = array();
		$limit = null;
		
		if(!is_null($id)) {
			$fields[] = 'player_id';
			$conditions['player_id'] = $id;
		}
		
		if(!is_null($filter)) {
			if(isset($filter['numeric'])) {
				if($filter['numeric'] > 0) {
					$limit = $filter['numeric'];
				}
			}
			if(isset($filter['date'])) {
				if($filter['date'] > 0) {
					$conditions['DATEDIFF(DATE(NOW()),DATE(game_datetime)) <='] = $filter['date'];
				}
			}
			if(isset($filter['team'])) {
				if($filter['team'] != 0) {
					$conditions['team'] = $filter['team'];
				}
			}
		}
		
		$scores = $this->Scorecard->find('all', array(
			'fields' => $fields,
			'conditions' => $conditions,
			'order' => 'mvp_points ASC'
		));
		
		$commander = array();
		$heavy = array();
		$scout = array();
		$ammo = array();
		$medic = array();
		
		foreach($scores as $score) {
			switch($score['Scorecard']['position']) {
				case 'Commander':
					if( (is_null($limit)) || (!is_null($limit) && count($commander) <= $limit)) {
						$commander[] = $score['Scorecard']['mvp_points'];
					}
					break;
				case 'Heavy Weapons':
					if( (is_null($limit)) || (!is_null($limit) && count($heavy) <= $limit)) {
						$heavy[] = $score['Scorecard']['mvp_points'];
					}
					break;
				case 'Scout':
					if( (is_null($limit)) || (!is_null($limit) && count($scout) <= $limit)) {
						$scout[] = $score['Scorecard']['mvp_points'];
					}
					break;
				case 'Ammo Carrier':
					if( (is_null($limit)) || (!is_null($limit) && count($ammo) <= $limit)) {
						$ammo[] = $score['Scorecard']['mvp_points'];
					}
					break;
				case 'Medic':
					if( (is_null($limit)) || (!is_null($limit) && count($medic) <= $limit)) {
						$medic[] = $score['Scorecard']['mvp_points'];
					}
					break;
			}
		}
		
		$results = array(
			'commander' => (count($commander) > 0 ? $commander[floor((count($commander)-1)/2)] : 0),
			'heavy' => (count($heavy) > 0 ? $heavy[floor((count($heavy)-1)/2)] : 0),
			'scout' => (count($scout) > 0 ? $scout[floor((count($scout)-1)/2)] : 0),
			'ammo' => (count($ammo) > 0 ? $ammo[floor((count($ammo)-1)/2)] : 0),
			'medic' => (count($medic) > 0 ? $medic[floor((count($medic)-1)/2)] : 0)
		);
		
		return $results;
	}
	
	public function getMyTeammates($id) {
		$results = $this->Scorecard->query("
			select player_name,
			(select count(myTeammates.game_datetime)
			from scorecards myGames
			inner join scorecards myTeammates
			on myGames.game_id = myTeammates.game_id
			and myGames.team = myTeammates.team
			where myTeammates.player_id = players.id
			and myGames.player_id = $id
			and myTeammates.player_id != $id) as same_team_count,
			(select count(myTeammates.game_datetime)
			from scorecards myGames
			inner join scorecards myTeammates
			on myGames.game_id = myTeammates.game_id
			and myGames.team != myTeammates.team
			where myTeammates.player_id = players.id
			and myGames.player_id = $id
			and myTeammates.player_id != $id) as other_team_count
			from players
		");
		
		$teammates = array();
		
		foreach($results as $line) {
			if($line[0]['same_team_count'] + $line[0]['other_team_count'] >= 10) {
				$teammates[$line['players']['player_name']] = array(
					'player_name'		=> $line['players']['player_name'],
					'same_team_count' 	=> $line[0]['same_team_count'], 
					'other_team_count' 	=> $line[0]['other_team_count'], 
					'same_team_percent' => ($line[0]['same_team_count'] / ($line[0]['same_team_count'] + $line[0]['other_team_count']))
				);
			}
		}
		
		return $teammates;
	}
}