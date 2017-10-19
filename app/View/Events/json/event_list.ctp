<?php
	$data = array();
	foreach ($response as $event) {
		$event['Event']['link'] = html_entity_decode($this->Html->url(array(
				'controller' => 'events',
				'action' => 'view',
				$event['Event']['id'],
				'?' => array(
					'gametype' => $event['Event']['type'],
					'centerID' => $event['Center']['id'],
					'eventID' => $event['Event']['id'],
					'selectedEvent' => $event['Event']['id']
				)
			))
		);
		$data[] = $event;
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>