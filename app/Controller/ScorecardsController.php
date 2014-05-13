<?php

class ScorecardsController extends AppController {
	public $components = array('RequestHandler');
	
	public function index() {
		$this->redirect(array('controller' => 'Scorecards', 'action' => 'nightly'));
	}
	
	public function overall() {
		$this->set('commander', $this->Scorecard->getPositionStats('Commander',null,3));
		$this->set('heavy', $this->Scorecard->getPositionStats('Heavy Weapons',null,3));
		$this->set('scout', $this->Scorecard->getPositionStats('Scout',null,3));
		$this->set('ammo', $this->Scorecard->getPositionStats('Ammo Carrier',null,3));
		$this->set('medic', $this->Scorecard->getPositionStats('Medic',null,3));
		$this->set('medic_hits', $this->Scorecard->getMedicHitStats());
		$this->set('averages', $this->Scorecard->getAllAvgMVP());
    }
	
	public function nightly() {
		$game_dates = $this->Scorecard->getGameDates();
		$this->set('game_dates', $game_dates);
		
		if($this->request->isPost())
			$date = $this->request->data['Scorecard']['date'];
		else
			$date = reset($game_dates);
			
		$this->set('current_date', $date);
		
		$this->set('games', $this->Scorecard->getGamesByDate($date));
		
        $this->set('avg_score', $this->Scorecard->getPositionStats(null, $date));
        $this->set('ammo_score', $this->Scorecard->getPositionStats('Ammo Carrier', $date));
        $this->set('commander_score', $this->Scorecard->getPositionStats('Commander', $date));
		$this->set('heavy_score', $this->Scorecard->getPositionStats('Heavy Weapons', $date));
		$this->set('medic_score', $this->Scorecard->getPositionStats('Medic', $date));
		$this->set('scout_score', $this->Scorecard->getPositionStats('Scout', $date));
		
		
		$options = array(
			'conditions' => array("DATE(Scorecard.game_datetime)" => $date),
		    'fields' => array(
				'player_name',
				'player_id',
				'SUM(Scorecard.medic_hits) as medic_hits',
				'(SUM(Scorecard.medic_hits)/COUNT(Scorecard.game_datetime)) as medic_hits_per_game'
			),
			'group' => 'player_name',
			'order' => 'medic_hits DESC'
		);
        $this->set('medic_hits', $this->Scorecard->find('all', $options));
	}
	
	public function upload() {
		if ($this->request->is('post')) {
			$row=0;
			$handle = fopen($this->data['Scorecard']['file']['tmp_name'],"r");
			fgetcsv($handle);
			while (($csvline = fgetcsv($handle)) !== FALSE) {
				$this->Scorecard->create();
				$this->Scorecard->set(array(
					'player_name' => $csvline[0], 
					'game_datetime' => date("Y-m-d H-i-s",strtotime($csvline[1])), 
					'team' => $csvline[2], 
					'position' => $csvline[3], 
					'score' => $csvline[4],
					'game_type' => $csvline[5],
					'shots_hit' => $csvline[6],
					'shots_fired' => $csvline[7],
					'accuracy' => ($csvline[6]/$csvline[7]),
					'times_zapped' => $csvline[8],
					'times_missiled' => $csvline[9],
					'missile_hits' => $csvline[10],
					'nukes_detonated' => $csvline[11],
					'nukes_activated' => $csvline[12],
					'nukes_canceled' => $csvline[13],
					'medic_hits' => $csvline[14],
					'own_medic_hits' => $csvline[15],
					'medic_nukes' => $csvline[16],
					'scout_rapid' => $csvline[17],
					'life_boost' => $csvline[18],
					'ammo_boost' => $csvline[19],
					'lives_left' => $csvline[20],
					'shots_left' => $csvline[21],
					'penalties' => $csvline[22],
					'shot_3hit' => $csvline[23],
					'elim_other_team' => $csvline[24],
					'team_elim' => $csvline[25],
					'own_nuke_cancels' => $csvline[26],
					'shot_opponent' => $csvline[27],
					'shot_team' => $csvline[28],
					'missiled_opponent' => $csvline[29],
					'missiled_team' => $csvline[30],
					'resupplies' => $csvline[31],
					'rank' => $csvline[32],
					'bases_destroyed' => $csvline[33]));
				$this->Scorecard->save();
				$row++;
			}
			fclose($handle);
			
			$this->Scorecard->generateMVP();
			$this->Scorecard->generateGames();
			$this->Scorecard->generatePlayers();
			
			$this->Session->setFlash("Added $row scorecards");
			$this->redirect('/scorecards/nightly');
		}
	}
	
	public function rebuild() {
		$mvps = $this->Scorecard->generateMVP();
		$games = $this->Scorecard->generateGames();
		$players = $this->Scorecard->generatePlayers();
		$existing = $players['existing'];
		$new = $players['new'];
		
		$this->Session->setFlash("Added $mvps MVP entries, $games game entries, games for $existing players and $new new players");
		$this->redirect('/scorecards/nightly');
		
		
	}
	
	public function allcenter() {
		$this->set('top', $this->Scorecard->getTopTeams());
	}
}