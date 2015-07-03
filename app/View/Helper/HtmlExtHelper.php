<?php
	App::uses('HtmlHelper', 'View/Helper');
	
	class HtmlExtHelper extends HtmlHelper {
		var $helpers = array('Session');
		
		public function link($title, $url = null, $options = array(), $confirmMessage = false) {
			if(is_array($url) && empty($url['?'])) {
				$querystring = array('?' => array('gametype' => $this->Session->read('state.gametype'), 'leagueID' => $this->Session->read('state.leagueID'), 'centerID' => $this->Session->read('state.centerID')));
				$url = $url + $querystring;
			}
			
			return parent::link($title, $url, $options, $confirmMessage);
		}
		
		public function url($url = null, $full = false) {
			if(is_array($url) && empty($url['?'])) {
				$querystring = array('?' => array('gametype' => $this->Session->read('state.gametype'), 'leagueID' => $this->Session->read('state.leagueID'), 'centerID' => $this->Session->read('state.centerID')));
				$url = $url + $querystring;
			}

			return parent::url($url, $full);
		}
	}