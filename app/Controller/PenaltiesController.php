<?php
App::uses('AppController', 'Controller');
/**
 * Penalties Controller
 *
 * @property Penalty $Penalty
 * @property PaginatorComponent $Paginator
 * @property SessionComponent $Session
 */
class PenaltiesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator', 'Session');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Penalty->recursive = 0;
		$this->set('penalties', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Penalty->exists($id)) {
			throw new NotFoundException(__('Invalid penalty'));
		}
		$options = array('conditions' => array('Penalty.' . $this->Penalty->primaryKey => $id));
		$this->set('penalty', $this->Penalty->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Penalty->create();
			if ($this->Penalty->save($this->request->data)) {
				$this->Session->setFlash(__('The penalty has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The penalty could not be saved. Please, try again.'));
			}
		}
		$players = $this->Penalty->Player->find('list');
		$games = $this->Penalty->Game->find('list');
		$this->set(compact('players', 'games'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Penalty->exists($id)) {
			throw new NotFoundException(__('Invalid penalty'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Penalty->save($this->request->data)) {
				$this->Session->setFlash(__('The penalty has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The penalty could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Penalty.' . $this->Penalty->primaryKey => $id));
			$this->request->data = $this->Penalty->find('first', $options);
		}
		$players = $this->Penalty->Player->find('list');
		$games = $this->Penalty->Game->find('list');
		$this->set(compact('players', 'games'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Penalty->id = $id;
		if (!$this->Penalty->exists()) {
			throw new NotFoundException(__('Invalid penalty'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Penalty->delete()) {
			$this->Session->setFlash(__('The penalty has been deleted.'));
		} else {
			$this->Session->setFlash(__('The penalty could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
