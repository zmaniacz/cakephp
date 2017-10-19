<?php
App::uses('AppController', 'Controller');
/**
 * Events Controller
 *
 * @property Event $Event
 * @property PaginatorComponent $Paginator
 */
class EventsController extends AppController {
	public function beforeFilter() {
		$this->Auth->allow(
			'index',
			'landing',
			'view',
			'playerStats',
			'eventList',
			'gameList',
			'eventScorecards',
			'summaryStats',
			'medicHits'
		);
		parent::beforeFilter();
	}

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

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
		if (!$this->Event->exists($id)) {
			throw new NotFoundException(__('Invalid event'));
		}
		$event = $this->Event->findById($id);
		$this->set('event', $this->Event->findById($id));

		if($event['Event']['type'] == 'social') {
			$this->render('social_view');
		} else {
			$this->render('comp_view');
		}
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
				$this->Flash->success(__('The event has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The event could not be saved. Please, try again.'));
			}
		}
		$centers = $this->Event->Center->find('list');
		$this->set(compact('centers'));
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
			throw new NotFoundException(__('Invalid event'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Event->save($this->request->data)) {
				$this->Flash->success(__('The event has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('The event could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Event.' . $this->Event->primaryKey => $id));
			$this->request->data = $this->Event->find('first', $options);
		}
		$centers = $this->Event->Center->find('list');
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
		$this->Event->id = $id;
		if (!$this->Event->exists()) {
			throw new NotFoundException(__('Invalid event'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Event->delete()) {
			$this->Flash->success(__('The event has been deleted.'));
		} else {
			$this->Flash->error(__('The event could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}

	//API functions below
	public function eventList() {
		$limit = $this->request->query('limit');
		$type = $this->request->query('gametype');
		$center_id = $this->request->query('centerID');
		$this->set('response', $this->Event->getEventList($type, $limit, $center_id));
	}

	public function gameList() {
		$event_id = $this->request->query('eventID');
		$this->set('response', $this->Event->getGameList($event_id));
	}

	public function medicHits($event_id = null) {
		$this->request->allowMethod('ajax');

		if (!$this->Event->exists($event_id)) {
			throw new NotFoundException(__('Invalid event'));
		}

		$this->loadModel('Scorecard');
		$this->set('response', $this->Event->getMedicHitStats($event_id));
	}
}
