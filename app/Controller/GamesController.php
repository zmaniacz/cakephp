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

	public $uses = array('Game','Player');

	public function beforeFilter() {
		$this->Auth->allow('index','view','overall','overallWinLossDetail','getGameList','getGameMatchups');
		parent::beforeFilter();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->set('games', $this->Game->getGameList(null, $this->Session->read('state')));
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
					$match = explode("|", $this->request->data['Game']['match']);
					$this->Match->addGame($match[0], $match[1], $this->request->data['Game']['id']);
				}
				
				$this->Session->setFlash(__('The game has been saved.'));
				return $this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('The game could not be saved. Please, try again.'));
			}
		} else {
			$this->loadModel('League');

			$game = $this->Game->getGameDetails($id);
			$this->request->data = $game;

			$game['Game']['pdf_link'] = "http://scorecards.lfstats.com/{$game['Game']['pdf_id']}.pdf";
			$game['Game']['green_team_name'] = "Green Team";
			$game['Game']['red_team_name'] = "Red Team";
			$game['Game']['green_team_link'] = "#";
			$game['Game']['red_team_link'] = "#";
			$game['Game']['game_name'] = (isset($game['Game']['league_id']) && !is_null($game['Match']['id'])) ? 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'] : $game['Game']['game_name'];

			$teams = array();
			$matches = array();

			if($game['Game']['type'] == 'league' || $game['Game']['type'] == 'tournament') {
				$this->loadModel('LeagueGame');
				
				$teams = $this->League->getTeams($game['Game']['league_id']);
				$matches = $this->League->getAvailableMatches($game);

				if($game['Game']['green_team_id'] != null) {
					$game['Game']['green_team_name'] = $teams[$game['Game']['green_team_id']];
					$game['Game']['green_team_link'] = Router::url(array('controller' => 'teams', 'action' => 'view', $game['Game']['green_team_id']));
				}

				if($game['Game']['red_team_id'] != null) {
					$game['Game']['red_team_name'] = $teams[$game['Game']['red_team_id']];
					$game['Game']['red_team_link'] = Router::url(array('controller' => 'teams', 'action' => 'view', $game['Game']['red_team_id']));
				}
			}
			
			array_multisort(array_column($game['Red_Scorecard'], 'rank'), SORT_ASC, $game['Red_Scorecard']);
			array_multisort(array_column($game['Green_Scorecard'], 'rank'), SORT_ASC, $game['Green_Scorecard']);

			$this->set('neighbors', $this->Game->getPrevNextGame($game['Game']['id']));
			$this->set('game', $game);
			$this->set('teams', $teams);
			$this->set('available_matches', $matches);
		}
	}

	public function getGameMatchups($game_id) {
		$this->set('data', $this->Game->getMatchups($game_id));
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
	
	public function getGameList() {
		$date = (empty($this->request->query('date'))) ? null : $this->request->query('date');
		$this->set('data', $this->Game->getGameList($date, $this->Session->read('state')));
	}
	
	public function overallWinLossDetail() {
		$this->set('overall', $this->Game->getOverallStats($this->Session->read('state')));
		$this->set('overall_averages', $this->Game->Scorecard->getOverallAverages($this->Session->read('state')));
	}
}
