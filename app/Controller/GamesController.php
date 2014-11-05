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
		$this->set('games', $this->Game->getGameList($this->Session->read('center.Center.id'), $this->Session->read('filter')));
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
		$this->Game->contain(array(
			'Scorecard' => array(
				'Penalty'
			)
		));
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
			$this->loadModel('League');
			
			$options = array('conditions' => array('Game.' . $this->Game->primaryKey => $id));
			$this->request->data = $this->Game->find('first', $options);
			if($this->request->data['Game']['type'] == 'league') {
				$this->set('teams', $this->League->getTeams($this->request->data['Game']['league_id']));
			}
		}
	}

	public function overall() {
	}
	
	public function overallWinLossDetail($filter_type, $games_limit = null) {
		//$this->request->onlyAllow('ajax');
		
		if($games_limit == 0) {
			$games_limit = null;
		}
		
		$filter = $this->Session->read('filter');

		$this->set('overall', $this->Game->getOverallStats($filter_type, $games_limit, $this->Session->read('center.Center.id'), $filter));
		$this->set('overall_averages', $this->Game->Scorecard->getOverallAverages($filter_type, $games_limit, $this->Session->read('center.Center.id'), $filter));
	}
}
