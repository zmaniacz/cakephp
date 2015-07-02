<?php
	$response = array();
	$this->log($games, 'debug');
	
	foreach($games as $game) {
		if(!empty($game['Match']['id'])) {
			$game_name = 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'];
			if($game['Match']['Round']['is_finals'])
				$game_name .= '(Finals)';
		} else {
			$game_name = $game['Game']['game_name'];
		}
		
		$red_team = ($game['Game']['red_team_id'] == null) ? 'Red Team' : $game['Red_Team']['name'];
		$green_team = ($game['Game']['green_team_id'] == null) ? 'Green Team' : $game['Green_Team']['name'];
		
		$winner = (($game['Game']['winner'] == 'Red') ? $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj']) : $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj']));
		$loser = (($game['Game']['winner'] == 'Red') ? $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj']) : $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj']));
		
		if($game['Game']['pdf_id'] == null) {
			$pdf = "";
		} else {
			$pdf = "<a href=\"/pdf/".$game['Game']['pdf_id'].".pdf\">PDF</a>";
		}
		
		$response[] = array(
			'game_name' => $this->Html->link($game_name, array('controller' => 'games', 'action' => 'view', $game['Game']['id'])),
			'game_datetime' => $game['Game']['game_datetime'],
			'winner' => $winner,
			'loser' => $loser,
			'winner_color' => $game['Game']['winner'],
			'pdf' => $pdf
		);
	}
	echo json_encode(compact('response'), JSON_NUMERIC_CHECK);
?>