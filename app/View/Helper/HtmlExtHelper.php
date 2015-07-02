<?php
	App::uses('HtmlHelper', 'View/Helper');
	
	class HtmlExtHelper extends HtmlHelper {
		public function link($title, $url = null, $options = array(), $confirmMessage = false) {
			if(!is_array($url))
				$url = array($url);
			
			$new_url = $url + array('?' => array('gametype' => 'all'));
			
			return parent::link($title, $new_url, $options, $confirmMessage);
		}
	}