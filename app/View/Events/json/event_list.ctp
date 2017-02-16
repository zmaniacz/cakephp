<?php
	$data = array();
	foreach ($response as $event) {
		$data[] = array(
            'id' => $event['Event']['id'],
			'name' => $this->Html->link($event['Event']['name'], array(
				'controller' => 'Events', 
				'action' => 'view', 
				$event['Event']['id'], 
				'?' => array(
				'gametype' => $event['Event']['type'],
				'centerID' => $event['Event']['center_id'],
				'eventID' => $event['Event']['id']
			)), array('class' => 'btn btn-block btn-info')),
			'description' => $event['Event']['description'],
            'is_comp' => $event['Event']['is_comp'],
			'type' => $event['Event']['type'],
			'last_gametime' => $event['Event']['last_gametime'],
			'center_id' => $event['Center']['id'],
            'center_short_name' => $event['Center']['short_name'],
			'center' => $event['Center']['name']
		);
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>