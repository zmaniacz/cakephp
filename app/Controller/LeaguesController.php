<?php
App::uses('AppController', 'Controller');
/**
 * Leagues Controller
 *
 * @property League $League
 */
class LeaguesController extends AppController {
	public $uses = array('League','Scorecard','Game');

	public function beforeFilter() {
		$this->Auth->allow('index','standings','ajax_getLeagues','ajax_getTeams');
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->redirect(array('controller' => 'scorecards', 'action' => 'pickLeague'));
	}

	public function standings($league_id = null) {
		if(is_null($league_id))
			$this->redirect(array('controller' => 'scorecards', 'action' => 'pickLeague'));
		
		$this->set('teams', $this->League->getTeamStandings($this->Session->read('state')));
	}

	public function ajax_getLeagues($type) {
		$this->request->onlyAllow('ajax');
		$this->set('leagues', $this->League->getLeagues($this->Session->read('state')));
	}

	public function ajax_getTeams() {
		$this->request->onlyAllow('ajax');
		$this->set('teams', $this->League->getTeamStandings($this->Session->read('state')));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->League->create();
			if ($this->League->save($this->request->data)) {
				return $this->flash(__('The league has been saved.'), array('controller' => 'leagues', 'action' => 'index'));
			}
		}
		$centers = $this->League->Center->find('list');
		$this->set(compact('centers'));
	}

	public function addTeam() {
		if ($this->request->is('post')) {
			$this->League->Team->create();
			if ($this->League->Team->save($this->request->data)) {
				return $this->flash(__('The team has been saved.'), array('controller' => 'leagues', 'action' => 'standings'));
			}
		}

		$leagues = $this->League->find('list', array('conditions' => array('id' => $this->Session->read('filter.value'))));
		$this->set(compact('leagues'));
		$captains = $this->League->Team->Player->find('list');
		$this->set(compact('captains'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->League->exists($id)) {
			throw new NotFoundException(__('Invalid league'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->League->save($this->request->data)) {
				return $this->flash(__('The league has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('League.' . $this->League->primaryKey => $id));
			$this->request->data = $this->League->find('first', $options);
		}
		$centers = $this->League->Center->find('list');
		$this->set(compact('centers'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->League->id = $id;
		if (!$this->League->exists()) {
			throw new NotFoundException(__('Invalid league'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->League->delete()) {
			return $this->flash(__('The league has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The league could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}
