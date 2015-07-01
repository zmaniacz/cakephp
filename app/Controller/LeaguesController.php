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

	public function standings() {
		if(!$this->Session->check('state.leagueID') && $this->Session->read('state.leagueID') > 0)
			$this->redirect(array('controller' => 'scorecards', 'action' => 'pickLeague'));
		
		$this->set('teams',  $this->League->Team->find('list', array('fields' => array('Team.name'), 'conditions' => array('league_id' => $this->Session->read('state.leagueID')))));
		$this->set('standings', $this->League->getTeamStandings($this->Session->read('state')));
		$this->set('details', $this->League->getLeagueDetails($this->Session->read('state')));
	}

	public function ajax_getLeagues() {
		$this->request->onlyAllow('ajax');
		$this->set('leagues', $this->League->getLeagues());
	}

	public function ajax_getTeams() {
		$this->request->onlyAllow('ajax');
		$this->set('teams', $this->League->getTeamStandings($this->Session->read('state')));
	}
	
	public function ajax_getStandings() {
		$this->request->onlyAllow('ajax');
		$this->set('standings', $this->League->getTeamStandings($this->Session->read('state')));
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
				return $this->flash(__('The league has been saved.'), array('controller' => 'leagues', 'action' => 'standings'));
			}
		}
		$centers = $this->League->Center->find('list');
		$this->set(compact('centers'));
	}

	public function addTeam() {
		if ($this->request->is('post')) {
			$this->League->Team->create();
			if ($this->League->Team->save($this->request->data)) {
				$this->Session->setFlash(__('The team has been saved.'));
				$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
			}
		}

		$leagues = $this->League->find('list', array('conditions' => array('id' => $this->Session->read('state.leagueID'))));
		$this->set(compact('leagues'));
		//$captains = $this->League->Team->Player->find('list');
		//$this->set(compact('captains'));
	}
	
	public function addRound() {
		if ($this->request->is('post')) {
			$this->League->Round->create();
			if ($this->League->Round->save($this->request->data)) {
				$this->Session->setFlash(__('The round has been created.'));
				$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
			}
		}
	}
	
	public function addMatch($league_id, $round_id) {
		if ($this->request->is('post')) {
			$match = $this->League->Round->Match->find('first', array(
				'conditions' => array(
					'round_id' => $this->request->data['League']['round_id']
				),
				'order' => 'match DESC'
			));
			$match_start = ($match) ? $match['Match']['match'] : 0;
			
			for($i = 0; $i < $this->request->data['League']['matches']; $i++) {
				$match_start++;
				$this->League->Round->Match->create();
				$this->League->Round->Match->set('match', $match_start);
				$this->League->Round->Match->set('round_id', $this->request->data['League']['round_id']);
				$this->League->Round->Match->save();
			}
			
			$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
		} else {
			$this->set('league', $this->League->findById($league_id));
			$this->set('round', $this->League->Round->findById($round_id));
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
	
	public function editMatch($id = null) {
		if (!$this->League->Round->Match->exists($id)) {
			throw new NotFoundException(__('Invalid match'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if($this->League->Round->Match->save($this->request->data)) {
				$this->Session->setFlash(__('Match saved'));
				$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
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
