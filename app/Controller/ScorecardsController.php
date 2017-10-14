<?php

class ScorecardsController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow(
			'index',
			'landing',
			'setState',
			'pickCenter',
			'pickLeague',
			'overall',
			'getOverallStats',
			'getOverallAverages',
			'getOverallMedicHits',
			'nightly',
			'tournament',
			'nightlySummaryStats',
			'nightlyScorecards',
			'nightlyGames',
			'nightlyMedicHits',
			'allcenter',
			'playerScorecards',
			'leaderboards',
			'getMVPBreakdown',
            'getHitBreakdown',
			'filterSub',
			'filterFinals',
			'filterRounds',
			'allstar',
			'getAllStarStats',
			'getComparison',
			'getPlayerHitBreakdown',
			'getIds'
		);
		parent::beforeFilter();
	}

	public function index() {
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly', '?' => array('gametype' => $this->Session->read('state.gametype'), 'centerID' => $this->Session->read('state.centerID'), 'eventID' => $this->Session->read('state.eventID'))));
	}

	public function landing() {
		$this->layout = 'landing';

		$events = $this->Game->find('all', array(
			'fields' => array(
				'COUNT(Game.id) as games_played',
				'Game.center_id',
				'Game.league_id',
				'DATE(Game.game_datetime) as games_date',
				'Game.type'
			),
			'group' => array(
				'center_id',
				'league_id',
				'games_date',
				'type'
			),
			'order' => array(
				'games_date DESC'
			),
			'limit' => 10,
			'contain' => array(
				'Center' => array(
					'fields' => array(
						'name',
						'short_name'
					)
				)
			)
		));

		$this->set('events', $events);
	}
	
	public function setState($gametype, $event_id, $center_id) {
		$this->Session->delete('state');
		
		$this->Session->write('state.gametype', $gametype);
		
		if(!is_null($event_id))
			$this->Session->write('state.eventID', $event_id);
			
		if(!is_null($center_id))
			$this->Session->write('state.centerID', $center_id);
		
		if($gametype == 'all' || $gametype == 'social')
			$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
			
		if($gametype == 'league')
			$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
	}
	
	public function phpview() {
	}
	
	public function getOverallStats($position = null) {
		$this->set('response', $this->Scorecard->getPositionStats($position,$this->Session->read('state')));
	}

	public function getAllStarStats() {
		$this->set('response', $this->Scorecard->getAllAvgMVP($this->Session->read('state')));
	}
	
	public function getOverallAverages() {
		$this->set('response', $this->Scorecard->getAllAvgMVP($this->Session->read('state')));
	}
	
	public function getOverallMedicHits() {
		$this->set('response', $this->Scorecard->getMedicHitStats($this->Session->read('state')));
	}

	public function nightlyMedicHits($date = null) {
		$this->set('medic_hits', $this->Scorecard->getMedicHitStatsByDate($date, $this->Session->read('state')));
	}

	public function playerScorecards($id) {
		$this->set('scorecards', $this->Scorecard->getPlayerGamesScorecardsById($id, $this->Session->read('state')));
	}
	
	public function rebuild() {
		//$mvps = $this->Scorecard->generateMVP();
		//$games = $this->Scorecard->generateGames(1);
		//$players = $this->Scorecard->generatePlayers($this->Session->read('center.Center.id'), $this->Session->read('filter'));
		//$existing = $players['existing'];
		//$new = $players['new'];
		
		//$this->Session->setFlash("Added $mvps MVP entries"); //, $games game entries, games for $existing players and $new new players");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
	
	public function allcenter() {
		$this->set('top', $this->Scorecard->getTopTeams($this->Session->read('state')));
	}

	public function allstar() {

	}

	public function leaderboards() {
		$this->set('leaderboards', $this->Scorecard->getLeaderboards($this->Session->read('state')));
		$this->set('commander', $this->Scorecard->getPositionLeaderboards('Commander', $this->Session->read('state')));
		$this->set('heavy', $this->Scorecard->getPositionLeaderboards('Heavy Weapons', $this->Session->read('state')));
		$this->set('scout', $this->Scorecard->getPositionLeaderboards('Scout', $this->Session->read('state')));
		$this->set('ammo', $this->Scorecard->getPositionLeaderboards('Ammo Carrier', $this->Session->read('state')));
		$this->set('medic', $this->Scorecard->getPositionLeaderboards('Medic', $this->Session->read('state')));
		$this->set('penalties', $this->Scorecard->getPenaltyCount($this->Session->read('state')));
		$this->set('winstreaks', $this->Scorecard->getWinStreaks($this->Session->read('state')));
		$this->set('lossstreaks', $this->Scorecard->getLossStreaks($this->Session->read('state')));
		$this->set('current_streaks', $this->Scorecard->getCurrentStreaks($this->Session->read('state')));
		$this->set('medic_on_medic', $this->Scorecard->getMedicOnMedicHits($this->Session->read('state')));
	}
	
	public function getMVPBreakdown($id) {
		$this->request->allowMethod('ajax');
		$scorecard = $this->Scorecard->find('first', 
			array(
				'contain' => array(
					'Penalty'
				),
				'conditions' => array(
					'id' => $id
				)
			)
		);
		
		$this->set('score', $scorecard);
	}
    
    public function getHitBreakdown($player_id, $game_id) {
        $this->set('hits', $this->Scorecard->getHitDetails($player_id, $game_id));
        $this->set('player_id', $player_id);
    }

	public function getPlayerHitBreakdown($player_id) {
        $this->set('data', $this->Scorecard->getPlayerHitDetails($player_id, $this->Session->read('state')));
		$this->set('players', $this->Scorecard->Player->find('list'));
    }
	
	public function ajax_switchSub($id) {
		$this->request->onlyAllow('ajax');

		$scorecard = $this->Scorecard->read(null, $id);

		$is_sub = ($scorecard['Scorecard']['is_sub']) ? 0 : 1;
		
		$this->Scorecard->set('is_sub', $is_sub);
		
		if($this->Scorecard->save()) {
			return new CakeResponse(array('body' => json_encode(array('id' => $id, 'is_sub' => $is_sub))));
		}
	}
	
	public function filterSub($showSubs = false) {
		$this->Session->write('state.show_subs', $showSubs);
		$this->redirect($this->request->referer());
	}
	
	public function filterFinals($showFinals = false) {
		$this->Session->write('state.show_finals', $showFinals);
		$this->redirect($this->request->referer());
	}
	
	public function filterRounds($showRounds = false) {
		$this->Session->write('state.show_rounds', $showRounds);
		$this->redirect($this->request->referer());
	}
}