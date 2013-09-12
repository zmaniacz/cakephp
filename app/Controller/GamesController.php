<?php
class GamesController extends AppController {
	public function view($id = null) {
		$this->Game->contain('Scorecard');
		$game = $this->Game->findById($id);
		
		foreach ($game['Scorecard'] as $key => $row) {
			$team[$key] = $row['team'];
			$rank[$key] = $row['rank'];
		}
		
		if($game['Game']['winner'] == 'red')
			array_multisort($team, SORT_DESC, $rank, SORT_ASC, $game['Scorecard']);
		else
			array_multisort($team, SORT_ASC, $rank, SORT_ASC, $game['Scorecard']);
		
		$this->set('game', $game);
	}
}