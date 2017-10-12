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
		if($id == null || $id <= 0) {
			$this->redirect(array('controller' => 'Players', 'action' => 'index'));
		} else {
			$this->set('player', $this->Player->findById($id));
			$this->set('aliases', $this->Player->PlayersName->findAllByPlayerId($id));
		}
	}

	public function playerScorecards($id) {
		$this->set('scorecards', $this->Player->getPlayerScorecards($id, null, $this->Session->read('state')));
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
	
	public function playerPositionSpider($id) {
		$this->set('player_mdn_scores', $this->Player->getMedianScoreByPosition($id, $this->Session->read('state')));
		$this->set('center_mdn_scores', $this->Player->getMedianScoreByPosition(null, $this->Session->read('state')));
		$this->set('player_mdn_mvp', $this->Player->getMedianMVPByPosition($id, $this->Session->read('state')));
		$this->set('center_mdn_mvp', $this->Player->getMedianMVPByPosition(null, $this->Session->read('state')));
	}
}