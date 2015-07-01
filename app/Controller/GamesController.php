<?php
App::uses('AppController', 'Controller');
/**
 * Games Controller
 *
 * @property Game $Game
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class GamesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

	public function beforeFilter() {
		$this->Auth->allow('index','view','overall','overallWinLossDetail');
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('games', $this->Game->getGameList($this->Session->read('state')));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Game->exists($id)) {
			throw new NotFoundException(__('Invalid game'));
		}

		if ($this->request->is(array('post', 'put'))) {
			if ($this->Game->save($this->request->data)) {
				
				if(!empty($this->request->data['Game']['match'])) {
					$this->loadModel('Match');
					$this->Match->addGame($this->request->data['Game']['match'], $this->request->data['Game']['id']);
				}
				
				$this->Session->setFlash(__('The game has been saved.'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The game could not be saved. Please, try again.'));
			}
		} else {
			$this->loadModel('League');

			$this->Game->contain(array(
				'Scorecard' => array(
					'Penalty'
				)
			));
			$game = $this->Game->findById($id);
			$this->request->data = $game;
			
			foreach ($game['Scorecard'] as $key => $row) {
				$team[$key] = $row['team'];
				$rank[$key] = $row['rank'];
			}
			
			if($game['Game']['winner'] == 'red')
				array_multisort($team, SORT_DESC, $rank, SORT_ASC, $game['Scorecard']);
			else
				array_multisort($team, SORT_ASC, $rank, SORT_ASC, $game['Scorecard']);

			if($game['Game']['type'] == 'league' || $game['Game']['type'] == 'tournament') {
				$this->set('teams', $this->League->getTeams($game['Game']['league_id']));
				$this->set('available_matches', $this->League->getAvailableMatches($game));
			}
			
			$this->set('game', $game);
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Game->exists($id)) {
			throw new NotFoundException(__('Invalid game'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Game->save($this->request->data)) {
				$this->Session->setFlash(__('The game has been saved.'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The game could not be saved. Please, try again.'));
			}
		} else {
			$this->loadModel('League');
			
			$options = array('conditions' => array('Game.' . $this->Game->primaryKey => $id));
			$this->request->data = $this->Game->find('first', $options);
			if($this->request->data['Game']['type'] == 'league' || $this->request->data['Game']['type'] == 'tournament') {
				$this->set('teams', $this->League->getTeams($this->request->data['Game']['league_id']));
			}
		}
	}
	
/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Game->id = $id;
		if (!$this->Game->exists()) {
			throw new NotFoundException(__('Invalid game'));
		}

		if ($this->Game->delete()) {
			$this->Session->setFlash(__('The game has been deleted.'));
		} else {
			$this->Session->setFlash(__('The game could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('controller' => 'Games', 'action' => 'index'));
	}

	public function overall() {
	}
	
	public function overallWinLossDetail() {
		$this->request->onlyAllow('ajax');
		$this->set('overall', $this->Game->getOverallStats($this->Session->read('state')));
		$this->set('overall_averages', $this->Game->Scorecard->getOverallAverages($this->Session->read('state')));
	}
}
