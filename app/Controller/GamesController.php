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
	
	public function overall() {
		$this->set('overall', $this->Game->getOverallStats());
	}
	
	public function overaller() {
		$games_limit = null;

		if(isset($this->request->data['Post']['games_limit'])) {
			$games_limit = $this->request->data['Post']['games_limit'];
			$this->layout = 'ajax';
		}
		$this->set('overall', $this->Game->getOverallStats($games_limit));
	}
}