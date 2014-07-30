<?php

class PlayersController extends AppController {
	public $components = array('RequestHandler');

	public function beforeFilter() {
		$this->Auth->allow();
		parent::beforeFilter();
	}
	
	public function index() {
		$this->redirect(array('controller' => 'scorecards', 'action' => 'overall'));
	}
	
	public function view($id = null) {
		if($id == null) {
			$this->redirect(array('controller' => 'Players', 'action' => 'index'));
		} else {
			$this->set('id', $id);
			$this->set('overall', $this->Player->getPlayerStats($id));
			$this->set('commander', $this->Player->getPlayerStats($id, 'Commander'));
			$this->set('heavy', $this->Player->getPlayerStats($id, 'Heavy Weapons'));
			$this->set('scout', $this->Player->getPlayerStats($id, 'Scout'));
			$this->set('ammo', $this->Player->getPlayerStats($id, 'Ammo Carrier'));
			$this->set('medic', $this->Player->getPlayerStats($id, 'Medic'));
			$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id));
			$this->set('teammates',$this->Player->getMyTeammates($id));
		}
	}
	
	public function playerWinLossDetail($id, $filter = null) {
		$this->request->onlyAllow('ajax');
		
		if($this->request->is('post')) {
			$filter = $this->request->data;
		}
		
		$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id, $filter));
	}
	
	public function playerPositionSpider($id, $filter = null) {
		$this->request->onlyAllow('ajax');
		
		if($this->request->is('post')) {
			$filter = $this->request->data;
		}
		
		$this->set('player_mdn_scores', $this->Player->getMedianScoreByPosition($id, $filter));
		$this->set('center_mdn_scores', $this->Player->getMedianScoreByPosition());
		$this->set('player_mdn_mvp', $this->Player->getMedianMVPByPosition($id, $filter));
		$this->set('center_mdn_mvp', $this->Player->getMedianMVPByPosition());
	}
}