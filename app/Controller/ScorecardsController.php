<?php

class ScorecardsController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow(
			'index',
			'setState',
			'pickCenter',
			'pickLeague',
			'overall',
			'nightly',
			'tournament',
			'nightlyStats',
			'nightlyScorecards',
			'nightlyGames',
			'nightlyMedicHits',
			'allcenter',
			'playerScorecards',
			'leaderboards'
		);
		parent::beforeFilter();
	}

	public function index() {
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly', '?' => array('gametype' => $this->Session->read('state.gametype'), 'centerID' => $this->Session->read('state.centerID'), 'leagueID' => $this->Session->read('state.leagueID'))));
	}
	
	public function setState($gametype, $league_id, $center_id) {
		$this->Session->write('state', '');
		
		$this->Session->write('state.gametype', $gametype);
		
		if(!is_null($league_id))
			$this->Session->write('state.leagueID', $league_id);
			
		if(!is_null($center_id))
			$this->Session->write('state.centerID', $center_id);
		
		if($gametype == 'all' || $gametype == 'social')
			$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
			
		if($gametype == 'league')
			$this->redirect(array('controller' => 'leagues', 'action' => 'standings', $league_id));
	}
	
	public function phpview() {
	}
	
	public function overall() {	
		$this->set('commander', $this->Scorecard->getPositionStats('Commander',$this->Session->read('state')));
		$this->set('heavy', $this->Scorecard->getPositionStats('Heavy Weapons',$this->Session->read('state')));
		$this->set('scout', $this->Scorecard->getPositionStats('Scout',$this->Session->read('state')));
		$this->set('ammo', $this->Scorecard->getPositionStats('Ammo Carrier',$this->Session->read('state')));
		$this->set('medic', $this->Scorecard->getPositionStats('Medic',$this->Session->read('state')));
		$this->set('medic_hits', $this->Scorecard->getMedicHitStats($this->Session->read('state')));
		$this->set('averages', $this->Scorecard->getAllAvgMVP($this->Session->read('state')));
    }
	
	public function nightly() {

		$game_dates = $this->Scorecard->getGameDates($this->Session->read('state'));
		$this->set('game_dates', $game_dates);
		
		if($this->request->isPost()) {
			$date = $this->request->data['Scorecard']['date'];
		} else {
			$date = reset($game_dates);
		}
		
		if(!$date)
			$date = 0;

		$this->set('current_date', $date);
	}
	
	public function nightlyScorecards($date = null) {
		$this->request->onlyAllow('ajax');
		$this->set('scorecards', $this->Scorecard->getScorecardsByDate($date, $this->Session->read('state')));
	}

	public function nightlyGames($date = null) {
		$this->request->onlyAllow('ajax');
		$this->set('games', $this->Scorecard->Game->getGameList($date, $this->Session->read('state')));
	}

	public function nightlyMedicHits($date = null) {
		$this->request->onlyAllow('ajax');
		$this->set('medic_hits', $this->Scorecard->getMedicHitStatsByDate($date, $this->Session->read('state')));
	}

	public function playerScorecards($id) {
		$this->request->onlyAllow('ajax');
		$this->set('scorecards', $this->Scorecard->getPlayerGamesScorecardsById($id, $this->Session->read('state')));
	}
	
	public function rebuild() {
		$mvps = $this->Scorecard->generateMVP();
		//$games = $this->Scorecard->generateGames(1);
		//$players = $this->Scorecard->generatePlayers($this->Session->read('center.Center.id'), $this->Session->read('filter'));
		//$existing = $players['existing'];
		//$new = $players['new'];
		
		$this->Session->setFlash("Added $mvps MVP entries"); //, $games game entries, games for $existing players and $new new players");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
	
	public function allcenter() {
		$this->set('top', $this->Scorecard->getTopTeams($this->Session->read('state')));
	}

	public function leaderboards() {
		$this->set('leaderboards', $this->Scorecard->getLeaderboards($this->Session->read('state')));
		$this->set('winstreaks', $this->Scorecard->getWinStreaks($this->Session->read('state')));
		$this->set('lossstreaks', $this->Scorecard->getLossStreaks($this->Session->read('state')));
	}
	
	public function ajax_switchSub($id) {
		$this->request->onlyAllow('ajax');
		$this->render(false);
		
		$scorecard = $this->Scorecard->read(null, $id);
		$this->Scorecard->set('is_sub', (($scorecard['Scorecard']['is_sub']) ? 0 : 1) );
		$this->Scorecard->save();
	}
}