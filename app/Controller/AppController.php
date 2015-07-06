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

	public $uses = array('Center', 'League');

	public function isAuthorized($user) {
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		}
		return false;
	}
	
	public function beforeFilter() {
		//read state from the querystring; default to social games at LTC if no state passed
		if(!is_null($this->request->query('gametype'))) {
			$this->Session->write('state.gametype', $this->request->query('gametype'));
		} else {
			$this->Session->write('state.gametype', 'social');
		}
		
		if(!is_null($this->request->query('centerID'))) {
			$this->Session->write('state.centerID', $this->request->query('centerID'));
		} else {
			$this->Session->write('state.centerID', 1);
		}
		
		if(!is_null($this->request->query('leagueID'))) {
			$this->Session->write('state.leagueID', $this->request->query('leagueID'));
		} else {
			$this->Session->write('state.leagueID', 0);
		}
		
		//get a center and league object for use throughout the app
		if(($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'social') && $this->Session->read('state.centerID') > 0) {
			$this->set('selected_center', $this->Center->findById($this->Session->read('state.centerID')));
		} elseif($this->Session->read('state.gametype') == 'league' && $this->Session->read('state.leagueID') > 0) {
			$league = $this->League->find('first', array(
				'contain' => array(
					'Center'
				),
				'conditions' => array(
					'League.id' => $this->Session->read('state.leagueID')
				)
			));
			$this->set('selected_league', $league);
			$this->set('selected_center', $this->Center->findById($league['Center']['id']));
		}
		
		$this->set('centers', $this->Center->find('list'));
		$this->set('leagues', $this->League->find('list'));
	}
}
