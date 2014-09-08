<?php
App::uses('AppController', 'Controller');
/**
 * Leagues Controller
 *
 * @property League $League
 */
class LeaguesController extends AppController {
	public $uses = array('League','Scorecard');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


	public function beforeFilter() {
		$this->Auth->allow('index','standings','ajax_getteams','ajax_getScorecards','ajax_getMedicHits');
		$this->layout = 'league';
		if(isset($this->request->params['league_id'])) {
			$this->set('league', $this->League->findById($this->request->params['league_id']));
		}
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('leagues', $this->Paginator->paginate());
	}

	public function standings() {

	}

	public function ajax_getTeams() {
		$this->request->onlyAllow('ajax');
		$this->set('teams', $this->League->getTeamStandings($this->request->params['league_id']));
	}

	public function ajax_getScorecards($round = null) {
		$this->request->onlyAllow('ajax');
		$this->set('scorecards', $this->Scorecard->getLeagueScorecardsByRound($round, $this->request->params['league_id']));
	}

	public function ajax_getMedicHits($round = null) {
		$this->request->onlyAllow('ajax');
		$this->set('medic_hits', $this->Scorecard->getMedicHitStatsByRound($round, $this->request->params['league_id']));
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
				return $this->flash(__('The team has been saved.'), array('controller' => 'leagues/'.$this->request->params['league_id'], 'action' => 'standings'));
			}
		}

		$leagues = $this->League->find('list', array('conditions' => array('id' => $this->request->params['league_id'])));
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
