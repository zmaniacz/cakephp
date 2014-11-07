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
			$this->set('overall', $this->Player->getPlayerStats($id, null, $this->Session->read('filter')));
			$this->set('commander', $this->Player->getPlayerStats($id, 'Commander', $this->Session->read('filter')));
			$this->set('heavy', $this->Player->getPlayerStats($id, 'Heavy Weapons', $this->Session->read('filter')));
			$this->set('scout', $this->Player->getPlayerStats($id, 'Scout', $this->Session->read('filter')));
			$this->set('ammo', $this->Player->getPlayerStats($id, 'Ammo Carrier', $this->Session->read('filter')));
			$this->set('medic', $this->Player->getPlayerStats($id, 'Medic', $this->Session->read('filter')));
			$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id, $this->Session->read('filter')));
			$this->set('teammates',$this->Player->getMyTeammates($id));
		}
	}

	public function link($id) {
		if($this->request->is('Post')) {
			$target_player = $this->Player->findById($id);
			$master_player = $this->Player->findById($this->request->data['Player']['linked_id']);

			$this->Player->linkPlayers($this->request->data['Player']['linked_id'], $id);

			$this->Session->setFlash(__($target_player['Player']['player_name']." has been set as an alias of ".$master_player['Player']['player_name']));
			return $this->redirect(array('action' => 'view', $this->request->data['Player']['linked_id']));
		} else {
			$this->set('players', $this->Player->find('list'));
			$this->set('target_player', $this->Player->findById($id));
		}
	}
	
	public function playerWinLossDetail($id) {
		$this->request->onlyAllow('ajax');
		
		$filter = $this->Session->read('filter');

		if($this->request->is('post')) {
			$filter = array_merge($filter, $this->request->data);
		}

		$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id, $filter));
	}
	
	public function playerPositionSpider($id) {
		$this->request->onlyAllow('ajax');
		
		$filter = $this->Session->read('filter');

		if($this->request->is('post')) {
			$filter = array_merge($filter, $this->request->data);
		}

		$this->set('player_mdn_scores', $this->Player->getMedianScoreByPosition($id, $filter));
		$this->set('center_mdn_scores', $this->Player->getMedianScoreByPosition(null, $filter));
		$this->set('player_mdn_mvp', $this->Player->getMedianMVPByPosition($id, $filter));
		$this->set('center_mdn_mvp', $this->Player->getMedianMVPByPosition(null, $filter));
	}
}