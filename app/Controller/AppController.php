<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $helpers = array(
		'Html' => array(
			'className' => 'HtmlExt'
		), 
		'Form', 
		'Js', 
		'Session'
	);
	
	public $components = array(
		'RequestHandler',
		'Session',
		'Auth' => array(
			'logoutRedirect' => array(
				'controller' => 'scorecards',
				'action' => 'index',
				'home'
        	),
        	'authorize' => array('Controller')
    	)
	);

	public $uses = array('Center', 'Event');

	public function isAuthorized($user) {
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		} elseif (isset($user['role']) && $user['role'] === 'center_admin' && $user['center'] === $this->Session->read('state.centerID')) {
			return true;
		}
		return false;
	}
	
	public function beforeFilter() {
		//If an event is defined, then that's all we want to see
		if(!is_null($this->request->query('eventID'))) {
			$event = $this->Event->findById($this->request->query('eventID'));
			$this->Session->write('state.eventID', $this->request->query('eventID'));
			$this->Session->write('state.gametype', $event['Event']['type']);
			$this->Session->write('state.centerID', $event['Event']['center_id']);

			$this->set('selected_event', $event);
			$this->set('selected_center', $this->Center->findById($this->Session->read('state.centerID')));
		} else {
			if(!is_null($this->request->query('gametype')))
				$this->Session->write('state.gametype', $this->request->query('gametype'));

			if(!is_null($this->request->query('centerID'))) {
				$this->Session->write('state.centerID', $this->request->query('centerID'));
				$this->set('selected_center', $this->Center->findById($this->Session->read('state.centerID')));
			}
				
		}
		
		if(!$this->Session->check('state.show_rounds')) {
			$this->Session->write('state.show_rounds', true);
		}
		
		$this->set('centers', $this->Center->find('list'));
		$this->set('events', $this->Event->find('list'));
		$this->set('event_details', $this->Event->getEventList());
	}
}
