<?php
App::uses('AppModel', 'Model');
/**
 * Player Model
 *
 * @property Center $Center
 * @property Scorecard $Scorecard
 * @property Team $Team
 */
class Player extends AppModel {

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
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id'
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignKey' => 'player_id',
			'dependent' => false
		),
		'PlayersName' => array(
			'className' => 'PlayersName',
			'foreignKey' => 'player_id',
			'dependent' => false
		),
        'PlayerHits' => array(
            'className' => 'Hit',
            'foreignKey' => 'player_id',
        ),
        'HitPlayer' => array(
            'className' => 'Hit',
            'foreignKey' => 'target_id',
        )
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Team' => array(
			'className' => 'Team',
			'joinTable' => 'players_teams',
			'foreignKey' => 'player_id',
			'associationForeignKey' => 'team_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public function getPlayerStats($id, $role = null, $state = null) {
		$conditions = array();
		if(!is_null($role))
			$conditions[] = array('position' => $role);
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('league_id' => $state['leagueID']);

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

	public function getPlayerWinsLosses($id) {
		
	}
	
	public function getAverageScoreByPosition($id = null, $state = null) {
		$conditions = array();
		
		if(!is_null($id))
			$conditions[] = array('player_id' => $id);
			
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('league_id' => $state['leagueID']);
		
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
	
	public function getAverageMVPByPosition($id = null, $state = null) {
		$conditions = array();
		
		if(!is_null($id))
			$conditions[] = array('player_id' => $id);
			
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('league_id' => $state['leagueID']);
		
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
	
	public function getMedianScoreByPosition($id = null, $state = null) {
		$fields = array('position','score');
		$conditions = array();
		$limit = null;
		
		if(!is_null($id)) {
			$fields[] = 'player_id';
			$conditions[] = array('Scorecard.player_id' => $id);
		}
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Scorecard.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Scorecard.type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Scorecard.league_id' => $state['leagueID']);

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
	
	public function getMedianMVPByPosition($id = null, $state = null) {
		$fields = array('position','mvp_points');
		$conditions = array();
		$limit = null;
		
		if(!is_null($id)) {
			$fields[] = 'player_id';
			$conditions[] = array('Scorecard.player_id' => $id);
		}
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Scorecard.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Scorecard.type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Scorecard.league_id' => $state['leagueID']);
			
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Scorecard.league_id' => $state['leagueID']);

		
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
	
	public function getMyTeammates($id, $state = null) {
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

	public function linkPlayers($master_id, $target_id) {
		//update the player_names table
		$this->PlayersName->updateAll(
			array('PlayersName.player_id' => $master_id),
			array('PlayersName.player_id' => $target_id)
		);
		//update all scorecards with the new id
		$this->Scorecard->updateAll(
			array('Scorecard.player_id' => $master_id),
			array('Scorecard.player_id' => $target_id)
		);
        
      	$this->PlayerHits>updateAll(
			array('Hit.player_id' => $master_id),
			array('Hit.player_id' => $target_id)
		);
        
        $this->HitPlayer>updateAll(
			array('Hit.target_id' => $master_id),
			array('Hit.target_id' => $target_id)
		);  
		//delete the old player record
		$this->delete($target_id);
	}

}
