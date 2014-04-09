<?php

class PlayersController extends AppController {
	public $components = array('RequestHandler');
	
	public function index() {
		$this->set('players', $this->Player->find('all', array('order' => 'player_name')));
	}
	
	public function view($id = null) {
		if($id == null) {
			$this->redirect(array('controller' => 'Players', 'action' => 'index'));
		} else {
			$this->set('overall', $this->Player->getPlayerStats($id));
			$this->set('commander', $this->Player->getPlayerStats($id, 'Commander'));
			$this->set('heavy', $this->Player->getPlayerStats($id, 'Heavy Weapons'));
			$this->set('scout', $this->Player->getPlayerStats($id, 'Scout'));
			$this->set('ammo', $this->Player->getPlayerStats($id, 'Ammo Carrier'));
			$this->set('medic', $this->Player->getPlayerStats($id, 'Medic'));
			$this->set('mdn_commander_score', $this->Player->getMedianScoreByPosition('Commander', $id));
			$this->set('mdn_heavy_score', $this->Player->getMedianScoreByPosition('Heavy Weapons', $id));
			$this->set('mdn_scout_score', $this->Player->getMedianScoreByPosition('Scout', $id));
			$this->set('mdn_ammo_score', $this->Player->getMedianScoreByPosition('Ammo Carrier', $id));
			$this->set('mdn_medic_score', $this->Player->getMedianScoreByPosition('Medic', $id));
			$this->set('center_mdn_commander_score', $this->Player->getMedianScoreByPosition('Commander'));
			$this->set('center_mdn_heavy_score', $this->Player->getMedianScoreByPosition('Heavy Weapons'));
			$this->set('center_mdn_scout_score', $this->Player->getMedianScoreByPosition('Scout'));
			$this->set('center_mdn_ammo_score', $this->Player->getMedianScoreByPosition('Ammo Carrier'));
			$this->set('center_mdn_medic_score', $this->Player->getMedianScoreByPosition('Medic'));
			$this->set('mdn_commander_mvp', $this->Player->getMedianMVPByPosition('Commander', $id));
			$this->set('mdn_heavy_mvp', $this->Player->getMedianMVPByPosition('Heavy Weapons', $id));
			$this->set('mdn_scout_mvp', $this->Player->getMedianMVPByPosition('Scout', $id));
			$this->set('mdn_ammo_mvp', $this->Player->getMedianMVPByPosition('Ammo Carrier', $id));
			$this->set('mdn_medic_mvp', $this->Player->getMedianMVPByPosition('Medic', $id));
			$this->set('center_mdn_commander_mvp', $this->Player->getMedianMVPByPosition('Commander'));
			$this->set('center_mdn_heavy_mvp', $this->Player->getMedianMVPByPosition('Heavy Weapons'));
			$this->set('center_mdn_scout_mvp', $this->Player->getMedianMVPByPosition('Scout'));
			$this->set('center_mdn_ammo_mvp', $this->Player->getMedianMVPByPosition('Ammo Carrier'));
			$this->set('center_mdn_medic_mvp', $this->Player->getMedianMVPByPosition('Medic'));
			//$this->set('center_average_mvp', $this->Player->getMedianMVPByPosition());
			//$this->set('average_score', $this->Player->getAverageScoreByPosition($id));
			//$this->set('average_mvp', $this->Player->getAverageMVPByPosition($id));
			//$this->set('center_average_score', $this->Player->getAverageScoreByPosition());
			//$this->set('center_average_mvp', $this->Player->getAverageMVPByPosition());
			$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id));
			$this->set('games_top5_overall', $this->Player->Scorecard->getPlayerTopGamesScorecardsById($id));
			$this->set('games_top5_commander', $this->Player->Scorecard->getPlayerTopGamesScorecardsById($id,'Commander'));
			$this->set('games_top5_heavy', $this->Player->Scorecard->getPlayerTopGamesScorecardsById($id,'Heavy Weapons'));
			$this->set('games_top5_scout', $this->Player->Scorecard->getPlayerTopGamesScorecardsById($id,'Scout'));
			$this->set('games_top5_ammo', $this->Player->Scorecard->getPlayerTopGamesScorecardsById($id,'Ammo Carrier'));
			$this->set('games_top5_medic', $this->Player->Scorecard->getPlayerTopGamesScorecardsById($id,'Medic'));
			$this->set('teammates',$this->Player->getMyTeammates($id));
		}
	}
}