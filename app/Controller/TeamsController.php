<?php
App::uses('AppController', 'Controller');
/**
 * Leagues Controller
 *
 * @property League $League
 */
class TeamsController extends AppController {
	public $uses = array('Team');
	
	public function beforeFilter() {
		$this->Auth->allow('view');
		parent::beforeFilter();
	}
	
	public function view($id = null) {
		if (!$this->Team->exists($id)) {
			throw new NotFoundException(__('Invalid team'));
		}

		$team = $this->Team->find('first', array(
			'contain' => array(
				'Red_Game' => array(
					'Red_Scorecard'
				),
				'Green_Game'=> array(
					'Green_Scorecard'
				)
			),
			'conditions' => array(
				'Team.id' => $id
			)
		));

		$this->set('team', $team);
		$this->set('teams',  $this->League->Team->find('list', array('fields' => array('Team.name'), 'conditions' => array('league_id' => $this->Session->read('state.leagueID')))));
		$this->set('details', $this->Team->getTeamMatches($id, $this->Session->read('state')));
	}
}