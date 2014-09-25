<?php
App::uses('AppController', 'Controller');
/**
 * Leagues Controller
 *
 * @property League $League
 */
class LeaguesController extends AppController {
	public $uses = array('League','Scorecard','Game');
/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');


	public function beforeFilter() {
		$this->Auth->allow('index','standings','ajax_getteams','ajax_getScorecards','ajax_getMedicHits','players','gameList');
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
* player standings methods
*/
	public function players() {
		$filter = array();
		$filter['league'] = $this->request->params['league_id'];

		$this->set('commander', $this->Scorecard->getPositionStats('Commander',$filter,$this->center_id));
		$this->set('heavy', $this->Scorecard->getPositionStats('Heavy Weapons',$filter,$this->center_id));
		$this->set('scout', $this->Scorecard->getPositionStats('Scout',$filter,$this->center_id));
		$this->set('ammo', $this->Scorecard->getPositionStats('Ammo Carrier',$filter,$this->center_id));
		$this->set('medic', $this->Scorecard->getPositionStats('Medic',$filter,$this->center_id));
		$this->set('medic_hits', $this->Scorecard->getMedicHitStats(true,$filter,$this->center_id));
		$this->set('medic_hits_all', $this->Scorecard->getMedicHitStats(false,$filter,$this->center_id));
		$this->set('averages', $this->Scorecard->getAllAvgMVP($filter,$this->center_id));
	}

/**
* Game list methods
*/
	public function gameList() {
		$this->set('games', $this->Game->getLeagueGameList($this->request->params['league_id']));
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

	public function editGame($game_id = null) {
		if (!$this->Game->exists($game_id)) {
			throw new NotFoundException(__('Invalid game'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Game->save($this->request->data)) {
				$this->Session->setFlash(__('The game has been saved.'));
				return $this->redirect(array('controller' => 'leagues/'.$this->request->params['league_id'], 'action' => 'gameList'));
			} else {
				$this->Session->setFlash(__('The game could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Game.' . $this->Game->primaryKey => $game_id));
			$this->request->data = $this->Game->find('first', $options);
			$this->set('teams', $this->League->getTeams($this->request->params['league_id']));
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
