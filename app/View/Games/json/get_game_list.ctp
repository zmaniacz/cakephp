<?php
	$data = array();
	
	foreach($games as $game) {
		if(!empty($game['Match']['id'])) {
			$game_name = 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'];
			if($game['Match']['Round']['is_finals'])
				$game_name .= '(Finals)';
		} else {
			$game_name = $game['Game']['game_name'];
		}
		
		$red_score = $game['Red_Team']['raw_score']+$game['Red_Team']['bonus_score']+$game['Red_Team']['penalty_score'];
		$green_score = $game['Green_Team']['raw_score']+$game['Green_Team']['bonus_score']+$game['Green_Team']['penalty_score'];
		
		if(!empty($game['Red_Team']['LeagueTeam'])) {
			$red_team = $this->Html->link("{$game['Red_Team']['LeagueTeam']['name']} : $red_score", 
							array('controller' => 'teams', 'action' => 'view', $game['Red_Team']['id']), 
							array('class' => 'btn btn-block btn-danger')
						);
		} else {
			$red_team = "Red Team : $red_score";
		}
		
		if(!empty($game['Green_Team']['LeagueTeam'])) {
			$green_team = $this->Html->link("{$game['Green_Team']['LeagueTeam']['name']} : $green_score", 
							array('controller' => 'teams', 'action' => 'view', $game['Green_Team']['id']), 
							array('class' => 'btn btn-block btn-success')
						);
		} else {
			$green_team = "Green Team : $green_score";
		}
		
		if($game['Game']['winner'] == 'red') {
			$winner =  $red_team;
			$loser = $green_team;
			$options = array('class' => 'btn btn-danger btn-block');
		} else {
			$winner = $green_team;
			$loser = $red_team;
			$options = array('class' => 'btn btn-success btn-block');
		}
		
		if($game['Game']['pdf_id'] == null) {
			$pdf = "";
		} else {
			$pdf = "<a href=\"http://scorecards.lfstats.com/".$game['Game']['pdf_id'].".pdf\" class=\"btn btn-info btn-block\" target=\"_blank\">PDF</a>";
		}
		
		$data[] = array(
			'id' => $game['Game']['id'],
			'game_name' => $this->Html->link($game_name, array('controller' => 'games', 'action' => 'view', $game['Game']['id']), $options),
			'game_datetime' => $game['Game']['game_datetime'],
			'winner' => $winner,
			'loser' => $loser,
			'pdf' => $pdf
		);
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>