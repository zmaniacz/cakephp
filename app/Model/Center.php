<?php

class Center extends AppModel {
	public $hasMany = 'Scorecard';
	
	public function getCenterID($short_name) {
		$results = $this->find('first', array(
			'fields' => array('id'),
			'conditions' => array('short_name' => $short_name)
		));
		
		return $results['Center']['id'];
	}
}
?>