<?php
	$data = array();
	foreach($games as &$game) {
		if(!empty($game['Match']['id'])) {
			$game_name = 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'];
			
			if($game['Match']['Round']['is_finals'])
				$game_name .= '(Finals)';
			
			$game['Game']['game_name'] = $game_name;
		}

		$game['Game']['pdf_link'] = "http://scorecards.lfstats.com/".$game['Game']['pdf_id'].".pdf";
		$game['Game']['link'] = $this->Html->url(array('controller' => 'games', 'action' => 'view', $game['Game']['id']));

		if(!empty($game['Red_Team']['EventTeam']))
			$game['Red_Team']['link'] = $this->Html->url(array('controller' => 'eventTeams', 'action' => 'view', $game['Red_Team']['EventTeam']['id']));

		if(!empty($game['Green_Team']['EventTeam']))
			$game['Green_Team']['link'] = $this->Html->url(array('controller' => 'eventTeams', 'action' => 'view', $game['Green_Team']['EventTeam']['id']));
	}
	$data = $games;
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>