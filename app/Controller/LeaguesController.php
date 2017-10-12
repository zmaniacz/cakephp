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
		$this->Auth->allow('index','standings','ajax_getLeagues','ajax_getTeams','ajax_getMatchDetails','ajax_getTeamStandings');
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
	}

	public function standings() {
		if($this->Session->read('state.gametype') != 'league' || !$this->Session->check('state.eventID') || $this->Session->read('state.eventID') <= 0)
			$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
		
		$this->set('teams',  $this->Event->Team->find('list', array('fields' => array('Team.name'), 'conditions' => array('event_id' => $this->Session->read('state.eventID')))));
		$this->set('standings', $this->Event->getTeamStandings($this->Session->read('state')));
		$this->set('details', $this->Event->getLeagueDetails($this->Session->read('state')));
	}

	public function ajax_getTeamStandings($round = null) {
		$this->set('data', $this->League->getTeamStandings($this->Session->read('state'), $round));
	}

	public function ajax_getLeagues() {
		$this->request->onlyAllow('ajax');
		$this->set('leagues', $this->Event->getLeagues());
	}

	public function ajax_getTeams() {
		$this->request->onlyAllow('ajax');
		$this->set('teams', $this->Event->getTeamStandings($this->Session->read('state')));
	}
	
	public function ajax_getStandings() {
		$this->request->onlyAllow('ajax');
		$this->set('standings', $this->Event->getTeamStandings($this->Session->read('state')));
	}

	public function ajax_assignTeam($match_id, $team_number, $team_id) {
		$this->request->onlyAllow('ajax');

		$match = $this->League->Round->Match->read(null, $match_id);
		
		if($team_number == 1)
			$this->League->Round->Match->set('team_1_id', $team_id);
		else
			$this->League->Round->Match->set('team_2_id', $team_id);
		
		if($this->League->Round->Match->save()) {
			return new CakeResponse(array('body' => json_encode(array('match_id' => $match_id, 'team_number' => $team_number, 'team_id' => $team_id))));
		}
	}

	public function ajax_getMatchDetails($match_id) {
		$this->set('match', $this->League->Round->Match->find('first', array(
			'contain' => array(
				'Game_1',
				'Game_2',
				'Team_1',
				'Team_2',
				'Round'
			),
			'conditions' => array(
				'Match.id' => $match_id
			)
		)));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Event->create();
			if ($this->Event->save($this->request->data)) {
				return $this->flash(__('The league has been saved.'), array('controller' => 'leagues', 'action' => 'standings'));
			}
		}
		$centers = $this->Event->Center->find('list');
		$this->set(compact('centers'));
	}

	public function addTeam() {
		if ($this->request->is('post')) {
			$this->Event->Team->create();
			if ($this->Event->Team->save($this->request->data)) {
				$this->Session->setFlash(__('The team has been saved.'));
				$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
			}
		}

		$leagues = $this->Event->find('list', array('conditions' => array('id' => $this->Session->read('state.eventID'))));
		$this->set(compact('leagues'));
		//$captains = $this->Event->Team->Player->find('list');
		//$this->set(compact('captains'));
	}
	
	public function addRound() {
		if ($this->request->is('post')) {
			$this->Event->Round->create();
			if ($this->Event->Round->save($this->request->data)) {
				$this->Session->setFlash(__('The round has been created.'));
				$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
			}
		}
	}
	
	public function addMatch($event_id, $round_id) {
		if ($this->request->is('post')) {
			$match = $this->Event->Round->Match->find('first', array(
				'conditions' => array(
					'round_id' => $this->request->data['League']['round_id']
				),
				'order' => 'match DESC'
			));
			$match_start = ($match) ? $match['Match']['match'] : 0;
			
			for($i = 0; $i < $this->request->data['League']['matches']; $i++) {
				$match_start++;
				$this->Event->Round->Match->create();
				$this->Event->Round->Match->set('match', $match_start);
				$this->Event->Round->Match->set('round_id', $this->request->data['League']['round_id']);
				$this->Event->Round->Match->save();
			}
			
			$this->redirect(array('controller' => 'leagues', 'action' => 'standings'));
		} else {
			$this->set('league', $this->Event->findById($event_id));
			$this->set('round', $this->Event->Round->findById($round_id));
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
		if (!$this->Event->exists($id)) {
			throw new NotFoundException(__('Invalid league'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Event->save($this->request->data)) {
				return $this->flash(__('The league has been saved.'), array('action' => 'index'));
			}
		} else {
			$options = array('conditions' => array('League.' . $this->Event->primaryKey => $id));
			$this->request->data = $this->Event->find('first', $options);
		}
		$centers = $this->Event->Center->find('list');
		$this->set(compact('centers'));
	}
	
	public function editMatch($id = null) {
		if (!$this->Event->Round->Match->exists($id)) {
			throw new NotFoundException(__('Invalid match'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if($this->Event->Round->Match->save($this->request->data)) {
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
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid league'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Event->delete()) {
			return $this->flash(__('The league has been deleted.'), array('action' => 'index'));
		} else {
			return $this->flash(__('The league could not be deleted. Please, try again.'), array('action' => 'index'));
		}
	}
}
