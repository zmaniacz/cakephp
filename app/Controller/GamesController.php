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
		$this->Auth->allow('view','overall','overallWinLossDetail');
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
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
		$this->Game->contain('Scorecard');
		$game = $this->Game->findById($id);
		
		foreach ($game['Scorecard'] as $key => $row) {
			$team[$key] = $row['team'];
			$rank[$key] = $row['rank'];
		}
		
		if($game['Game']['winner'] == 'red')
			array_multisort($team, SORT_DESC, $rank, SORT_ASC, $game['Scorecard']);
		else
			array_multisort($team, SORT_ASC, $rank, SORT_ASC, $game['Scorecard']);
		
		$this->set('game', $game);
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
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The game could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Game.' . $this->Game->primaryKey => $id));
			$this->request->data = $this->Game->find('first', $options);
		}
	}

	public function overall() {
	}
	
	public function overallWinLossDetail($filter_type, $games_limit = null) {
		$this->request->onlyAllow('ajax');
		if($games_limit == 0) {
			$games_limit = null;
		}
		$this->set('overall', $this->Game->getOverallStats($filter_type, $games_limit));
	}
}
