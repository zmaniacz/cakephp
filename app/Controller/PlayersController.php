<?php

class PlayersController extends AppController {
	public $components = array('RequestHandler');
	
	public function index() {
		$this->set('players', $this->Player->find('all', array('order' => 'player_name')));
	}
	
	public function view($id = null) {
		if($id == null) {
			$this->redirect(array('controller' => 'Players', 'action' => 'index'));
		} else {
			$this->set('game_list', $this->Player->getPlayerGames($id));
			$this->set('overall', $this->Player->getPlayerStats($id));
			$this->set('commander', $this->Player->getPlayerStats($id, 'Commander'));
			$this->set('heavy', $this->Player->getPlayerStats($id, 'Heavy Weapons'));
			$this->set('scout', $this->Player->getPlayerStats($id, 'Scout'));
			$this->set('ammo', $this->Player->getPlayerStats($id, 'Ammo Carrier'));
			$this->set('medic', $this->Player->getPlayerStats($id, 'Medic'));
			$this->set('average_score', $this->Player->getAverageScoreByPosition($id));
			$this->set('average_mvp', $this->Player->getAverageMVPByPosition($id));
			$this->set('center_average_score', $this->Player->getAverageScoreByPosition());
			$this->set('center_average_mvp', $this->Player->getAverageMVPByPosition());
			$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id));
		}
	}
}