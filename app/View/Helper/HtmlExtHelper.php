<?php
	App::uses('HtmlHelper', 'View/Helper');
	
	class HtmlExtHelper extends HtmlHelper {
		var $helpers = array('Session');
		
		public function link($title, $url = null, $options = array(), $confirmMessage = false) {
			return parent::link($title, $url, $options, $confirmMessage);
		}
		
		public function url($url = null, $full = false) {
			if(is_array($url)) {
				$default = array(
					'gametype' => $this->Session->read('state.gametype'),
					'eventID' => $this->Session->read('state.eventID'), 
					'centerID' => $this->Session->read('state.centerID'),
					'selectedEvent' => $this->Session->read('state.selectedEvent')
				);

				$url['?'] = array_merge($default,(empty($url['?'])) ? array() : $url['?']);
			}

			return parent::url($url, $full);
		}
	}