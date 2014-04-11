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
			$this->set('id', $id);
			$this->set('overall', $this->Player->getPlayerStats($id));
			$this->set('commander', $this->Player->getPlayerStats($id, 'Commander'));
			$this->set('heavy', $this->Player->getPlayerStats($id, 'Heavy Weapons'));
			$this->set('scout', $this->Player->getPlayerStats($id, 'Scout'));
			$this->set('ammo', $this->Player->getPlayerStats($id, 'Ammo Carrier'));
			$this->set('medic', $this->Player->getPlayerStats($id, 'Medic'));
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
	
	public function playerWinLossDetail($id, $games_limit = null, $filter_type = null) {
		$this->request->onlyAllow('ajax');
		$this->set('games', $this->Player->Scorecard->getPlayerGamesScorecardsById($id, $games_limit, $filter_type));
	}
	
	public function playerPositionSpider($id, $games_limit = null, $filter_type = null) {
		$this->request->onlyAllow('ajax');
		$this->set('mdn_commander_score', $this->Player->getMedianScoreByPosition('Commander', $id, $games_limit, $filter_type));
		$this->set('mdn_heavy_score', $this->Player->getMedianScoreByPosition('Heavy Weapons', $id, $games_limit, $filter_type));
		$this->set('mdn_scout_score', $this->Player->getMedianScoreByPosition('Scout', $id, $games_limit, $filter_type));
		$this->set('mdn_ammo_score', $this->Player->getMedianScoreByPosition('Ammo Carrier', $id, $games_limit, $filter_type));
		$this->set('mdn_medic_score', $this->Player->getMedianScoreByPosition('Medic', $id, $games_limit, $filter_type));
		$this->set('center_mdn_commander_score', $this->Player->getMedianScoreByPosition('Commander', null, $games_limit, $filter_type));
		$this->set('center_mdn_heavy_score', $this->Player->getMedianScoreByPosition('Heavy Weapons', null, $games_limit, $filter_type));
		$this->set('center_mdn_scout_score', $this->Player->getMedianScoreByPosition('Scout', null, $games_limit, $filter_type));
		$this->set('center_mdn_ammo_score', $this->Player->getMedianScoreByPosition('Ammo Carrier', null, $games_limit, $filter_type));
		$this->set('center_mdn_medic_score', $this->Player->getMedianScoreByPosition('Medic', null,$games_limit, $filter_type));
		$this->set('mdn_commander_mvp', $this->Player->getMedianMVPByPosition('Commander', $id, $games_limit, $filter_type));
		$this->set('mdn_heavy_mvp', $this->Player->getMedianMVPByPosition('Heavy Weapons', $id, $games_limit, $filter_type));
		$this->set('mdn_scout_mvp', $this->Player->getMedianMVPByPosition('Scout', $id, $games_limit, $filter_type));
		$this->set('mdn_ammo_mvp', $this->Player->getMedianMVPByPosition('Ammo Carrier', $id, $games_limit, $filter_type));
		$this->set('mdn_medic_mvp', $this->Player->getMedianMVPByPosition('Medic', $id, $games_limit, $filter_type));
		$this->set('center_mdn_commander_mvp', $this->Player->getMedianMVPByPosition('Commander', null, $games_limit, $filter_type));
		$this->set('center_mdn_heavy_mvp', $this->Player->getMedianMVPByPosition('Heavy Weapons', null, $games_limit, $filter_type));
		$this->set('center_mdn_scout_mvp', $this->Player->getMedianMVPByPosition('Scout', null, $games_limit, $filter_type));
		$this->set('center_mdn_ammo_mvp', $this->Player->getMedianMVPByPosition('Ammo Carrier', null, $games_limit, $filter_type));
		$this->set('center_mdn_medic_mvp', $this->Player->getMedianMVPByPosition('Medic', null, $games_limit, $filter_type));
	}
}