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

	public $uses = array('Center', 'Event', 'Scorecard', 'Game');

	public function isAuthorized($user) {
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		} elseif (isset($user['role']) && $user['role'] === 'center_admin' && $user['center'] === $this->Session->read('state.centerID')) {
			return true;
		}
		return false;
	}
	
	public function beforeFilter() {
		//gametype, centerID and eventID and compID should be defined in the session state at all times
		//values of all, 0 and 0 and 0 respectively indicate no filtering to occur on those items
		//gametype can be all, social, league or tournament
		//matchtype can be rounds, finals or all
		//default to all, 0, 0, 0
		if($this->Session->check('state')) {
			$state = $this->Session->read('state');
		} else {
			$state = array(
				'gametype' => 'all',
				'centerID' => 0,
				'eventID' => 0,
				'selectedEvent' => 0,
				'matchtype' => 'rounds',
				'show_subs' => false
			);
		}

		if(!is_null($this->request->query('gametype')))
			$state['gametype'] = $this->request->query('gametype');

		if(!is_null($this->request->query('centerID')))
			$state['centerID'] = $this->request->query('centerID');

		if(!is_null($this->request->query('eventID')))
			$state['eventID'] = $this->request->query('eventID');
		
		if(!is_null($this->request->query('selectedEvent')))
			$state['selectedEvent'] = $this->request->query('selectedEvent');

		if($state['eventID'] > 0) {
			$state['selectedEvent'] = $state['eventID'];
			$selected_event = $this->Event->findById($state['selectedEvent']);
			$state['centerID'] = $selected_event['Event']['center_id'];
			$state['gametype'] = ($selected_event['Event']['is_comp']) ? 'comp' : 'social';
		}

		$this->Session->write('state', $state);

		if($state['selectedEvent'] > 0 && !isset($selected_event))
			$selected_event = $this->Event->findById($state['selectedEvent']);
		
		if(isset($selected_event))
			$this->set('selected_event', $selected_event);

		if($state['centerID'] > 0)
			$this->set('selected_center', $this->Center->findById($state['centerID']));
		
		$this->set('centers', $this->Center->find('list'));
		$this->set('events', $this->Event->find('list'));
		$this->set('event_details', $this->Event->getEventList());
		$this->set('scorecard_stats', $this->Scorecard->getDatabaseStats());
		$this->set('game_stats', $this->Game->getDatabaseStats());
	}
}
