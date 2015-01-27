<?php

class ScorecardsController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('index','overall','nightly','tournament','nightlyStats','nightlyScorecards','nightlyGames','nightlyMedicHits','allcenter','setFilter','ajax_getFilter');
		parent::beforeFilter();
	}

	public function index() {
		$this->redirect(array('controller' => 'Scorecards', 'action' => 'nightly'));
	}
	
	public function phpview() {
	}
	
	public function overall() {
		$center_id = $this->Session->read('center.Center.id');
		$filter = $this->Session->read('filter');
		
		$this->set('commander', $this->Scorecard->getPositionStats('Commander',$filter,$center_id));
		$this->set('heavy', $this->Scorecard->getPositionStats('Heavy Weapons',$filter,$center_id));
		$this->set('scout', $this->Scorecard->getPositionStats('Scout',$filter,$center_id));
		$this->set('ammo', $this->Scorecard->getPositionStats('Ammo Carrier',$filter,$center_id));
		$this->set('medic', $this->Scorecard->getPositionStats('Medic',$filter,$center_id));
		$this->set('medic_hits', $this->Scorecard->getMedicHitStats(true,$filter,$center_id));
		//$this->set('medic_hits_all', $this->Scorecard->getMedicHitStats(false,$filter,$center_id));
		$this->set('averages', $this->Scorecard->getAllAvgMVP($filter,$center_id));
    }
	
	public function nightly() {
		$game_dates = $this->Scorecard->getGameDates($this->Session->read('center.Center.id'), $this->Session->read('filter'));
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
		$this->set('scorecards', $this->Scorecard->getScorecardsByDate($date, $this->Session->read('center.Center.id'), $this->Session->read('filter')));
	}

	public function nightlyGames($date = null) {
		$this->request->onlyAllow('ajax');
		$this->set('games', $this->Scorecard->Game->getGamesByDate($date, $this->Session->read('center.Center.id'), $this->Session->read('filter')));
	}

	public function nightlyMedicHits($date = null) {
		$this->request->onlyAllow('ajax');
		$this->set('medic_hits', $this->Scorecard->getMedicHitStatsByDate($date, $this->Session->read('center.Center.id'), $this->Session->read('filter')));
	}
	
	public function rebuild() {
		//$mvps = $this->Scorecard->generateMVP();
		//$games = $this->Scorecard->generateGames(1);
		$players = $this->Scorecard->generatePlayers($this->Session->read('center.Center.id'), $this->Session->read('filter'));
		$existing = $players['existing'];
		$new = $players['new'];
		
		$this->Session->setFlash("Added $mvps MVP entries, $games game entries, games for $existing players and $new new players");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
	
	public function allcenter() {
		$this->set('top', $this->Scorecard->getTopTeams($this->Session->read('center.Center.id'), $this->Session->read('filter')));
	}
	
	public function setFilter () {
		if($this->request->is('post')) {
			if(isset($this->request->data['game_filter'])) {
				$type = $this->request->data['game_filter']['selectFilter'];
				
				$value = -1;
				if(isset($this->request->data['game_filter']['select_detailsFilter']))
					$value = $this->request->data['game_filter']['select_detailsFilter'];

				$this->Session->write('filter',array('type' => $type, 'value' => $value));

			} elseif(isset($this->request->data['center_filter'])) {
				$center = $this->Center->findById($this->request->data['center_filter']['selectFilter']);
				$this->Session->write('center',$center);
			}
		}
		$this->redirect($this->referer());
	}

	public function ajax_getFilter() {
		$this->request->onlyAllow('ajax');
		$this->set('filter', $this->Session->read('filter'));
	}
}