<?php

class Game extends AppModel {
	public $hasMany = array(
		'Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'game_id'
		),
		'Team' => array(
			'className' => 'Team',
			'foreignkey' => 'game_id'
		),
		'Red_Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'game_id',
			'conditions' => array('Red_Scorecard.team' => 'red')
		),
		'Green_Scorecard' => array(
			'className' => 'Scorecard',
			'foreignkey' => 'game_id',
			'conditions' => array('Green_Scorecard.team' => 'green')
		),
		'GameResult' => array(
			'className' => 'GameResult',
			'foreignKey' => 'game_id'
		)
	);

	public $hasOne = array(
			'Red_Team' => array(
			'className' => 'Team',
			'foreignkey' => 'game_id',
			'conditions' => array('Red_Team.color' => 'red')
		),
		'Green_Team' => array(
			'className' => 'Team',
			'foreignkey' => 'game_id',
			'conditions' => array('Green_Team.color' => 'green')
		)
	);

	public $belongsTo = array(
		'Center' => array(
			'className' => 'Center',
			'foreignKey' => 'center_id'
		),
		'Match' => array(
			'className' => 'Match',
			'foreignKey' => 'match_id'
		),
		'League' => array(
			'className' => 'League',
			'foreignKey' => 'league_id'
		)
	);
	
	public function getOverallStats($state) {
		$conditions = array();
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Game.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Game.type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Game.league_id' => $state['leagueID']);
	
		$overall = $this->find('all', array(
			'fields' => array(
				'winner',
				'red_eliminated',
				'green_eliminated',
				'COUNT(game_datetime) as Total',
				'AVG(red_score) as red_avg_score',
				'AVG(green_score) as green_avg_score'
			),
			'conditions' => $conditions,
			'group' => array(
				'winner',
				'red_eliminated',
				'green_eliminated'
			)
		));

		return $overall;
	}

	public function getGameDetails($id) {
		$conditions[] = array('Game.id' => $id);

		$result = $this->find('first', array(
			'contain' => array(
				'Scorecard' => array(
					'Penalty',
                    'Hit'
				),
				'Match' => array(
					'Round'
				),
				'Red_Scorecard' => array(
					'fields' => array(
						'SUM(medic_hits) as medic_hits',
						'SUM(missile_hits) as missile_hits',
						'SUM(nukes_detonated) as nukes_detonated',
						'SUM(lives_left) as lives_left',
						'SUM(shots_left) as shots_left',
						'( SUM(shot_opponent) / SUM(times_zapped) ) as hit_diff',
						'SUM(resupplies) as resupplies',
						'SUM(bases_destroyed) as bases_destroyed',
						'AVG(accuracy) as accuracy',
						'SUM(mvp_points) as mvp_points'
					)
				),
				'Green_Scorecard' => array(
					'fields' => array(
						'SUM(medic_hits) as medic_hits',
						'SUM(missile_hits) as missile_hits',
						'SUM(nukes_detonated) as nukes_detonated',
						'SUM(lives_left) as lives_left',
						'SUM(shots_left) as shots_left',
						'( SUM(shot_opponent) / SUM(times_zapped) ) as hit_diff',
						'SUM(resupplies) as resupplies',
						'SUM(bases_destroyed) as bases_destroyed',
						'AVG(accuracy) as accuracy',
						'SUM(mvp_points) as mvp_points'
					)
				)
			),
			'conditions' => $conditions
		));

		return $result;
	}

	public function getGameList($date = null, $state) {
		$conditions = array();
		
		if(isset($state['centerID']) && $state['centerID'] > 0)
			$conditions[] = array('Game.center_id' => $state['centerID']);
		
		if(isset($state['gametype']) && $state['gametype'] != 'all')
			$conditions[] = array('Game.type' => $state['gametype']);
		
		if(isset($state['leagueID']) && $state['leagueID'] > 0)
			$conditions[] = array('Game.league_id' => $state['leagueID']);
			
		if(!is_null($date))
			$conditions[] = array('DATE(Game.game_datetime)' => $date);

		$games = $this->find('all', array(
			'contain' => array(
				'Red_Team' => array(
					'LeagueTeam'
				),
				'Green_Team' => array(
					'LeagueTeam'
				),
				'Match' => array(
					'Round'
				)
			),
			'conditions' => $conditions,
			'order' => 'Game.game_datetime ASC'
		));
		return $games;
	}
	
	public function updateGameWinner($id) {
		$scores = $this->find('first', array(
			'fields' => array(
				'Game.id'
			),
			'contain' => array(
				'Red_Scorecard' => array(
					'fields' => array(
						'SUM(Red_Scorecard.score) as team_score',
						'SUM(Red_Scorecard.team_elim) as total_elim'
					)
				),
				'Green_Scorecard' => array(
					'fields' => array(
						'SUM(Green_Scorecard.score) as team_score',
						'SUM(Green_Scorecard.team_elim) as total_elim'
					)
				)
			),
			'conditions' => array(
				'Game.id' => $id
			)
			
		));
		
		$penalties = $this->find('first', array(
			'fields' => array('id'),
			'contain' => array(
				'Scorecard' => array(
					'fields' => array('id','team'),
					'Penalty'
				)
			),
			'conditions' => array(
				'Game.id' => $id
			)
		));
		
		$elim_bonus = 10000;
		$red_bonus = 0;
		$red_pens = 0;
		$red_elim = 0;
		$green_bonus = 0;
		$green_pens = 0;
		$green_elim = 0;
		$winner = 'green';
		
		//Apply the elim bonus if the ooposing team was eliminated...both teams can get the bonus
		if($scores['Red_Scorecard'][0]['Red_Scorecard'][0]['total_elim'] > 0) {
			$green_bonus += $elim_bonus;
			$red_elim = 1;
		}
		
		if($scores['Green_Scorecard'][0]['Green_Scorecard'][0]['total_elim'] > 0) {
			$red_bonus += $elim_bonus;
			$green_elim = 1;
		}
		
		foreach($penalties['Scorecard'] as $penalty) {
			if(!empty($penalty['Penalty'])) {
				foreach($penalty['Penalty'] as $single_penalty) {
					if($penalty['team'] == 'red') {
						$red_pens += $single_penalty['value'];
					} else {
						$green_pens += $single_penalty['value'];
					}
				}
			}
		}
		
		if($scores['Red_Scorecard'][0]['Red_Scorecard'][0]['team_score'] + $red_bonus + $red_pens > $scores['Green_Scorecard'][0]['Green_Scorecard'][0]['team_score'] + $green_bonus + $green_pens)
			$winner = 'red';
			
		$data = array('id' => $id,
			'green_score' => $scores['Green_Scorecard'][0]['Green_Scorecard'][0]['team_score'],
			'red_score' => $scores['Red_Scorecard'][0]['Red_Scorecard'][0]['team_score'],
			'red_adj' => $red_bonus + $red_pens,
			'green_adj' => $green_bonus + $green_pens,
			'red_eliminated' => $red_elim,
			'green_eliminated' => $green_elim,
			'winner' => $winner
		);
		
		$this->save($data);
	}
}