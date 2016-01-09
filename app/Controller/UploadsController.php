<?php
App::uses('AppController', 'Controller', 'Xml', 'Utility');

class UploadsController extends AppController {
	public $uses = array('Scorecard');

	public function index() {
		if ($this->request->is('post')) {
			App::import('Vendor','UploadHandler',array('file' => 'UploadHandler/UploadHandler.php'));

			$options = array
			(
				'script_url' => FULL_BASE_URL.DS.'uploads/index/',
				'upload_dir' => APP.WEBROOT_DIR.DS.'parser'.DS.'incoming'.DS.$this->Session->read('state.centerID').DS,
				'upload_url' => FULL_BASE_URL.DS.'parser'.DS.'incoming'.DS.$this->Session->read('state.centerID').DS,
				'image_versions' => array()
			);

			$upload_handler = new UploadHandler($options, $initialize = false);
			switch ($_SERVER['REQUEST_METHOD'])
			{
				case 'HEAD':
				case 'GET':
				$upload_handler->get();
				break;
				case 'POST':
				$upload_handler->post();
				break;
				case 'DELETE':
				$upload_handler->delete();
				break;
				default:
				header('HTTP/1.0 405 Method Not Allowed');
			}
			exit;
		}
		if (!$this->Session->check('state.centerID')) {
			throw new NotFoundException(__('No center defined'));
		}
	}

	public function parse() {
		$center_id = $this->Session->read('state.centerID');
		$command = "nohup sh -c '".APP.WEBROOT_DIR.DS."parser/pdfparse.sh $center_id' > /dev/null 2>&1 & echo $!";
		$this->set('pid', exec($command,$output));
	}

	public function checkPid($pid) {
		$this->request->onlyAllow('ajax');

		$cmd = "ps $pid";
		exec($cmd, $output, $result);
		if(count($output) >= 2) {
			$this->set('alive', true);
		} else {
			$this->set('alive', false);
		}
	}

	public function parseCSV() {
		//We're only going to process the most recent file
		$center_id = $this->Session->read('state.centerID');
		$type = $this->Session->read('state.gametype');

		$league_id = null;
		if($type == 'league' || $type == 'tournament')
			$league_id = $this->Session->read('state.leagueID');

		//make sure we default to social
		if($type == 'all')
			$type = 'social';

		$path = "parser/pending/$center_id";

		$latest_ctime = 0;
		$latest_filename = '';    

		$d = dir($path);
		while (false !== ($entry = $d->read())) {
			$filepath = "{$path}/{$entry}";
			if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
				$latest_ctime = filectime($filepath);
				$latest_filename = $entry;
			}
		}

		$row=0;
		$xmlString = file_get_contents($path.DS.$latest_filename);
        $xml = Xml::toArray(Xml::build($xmlString));

		$red_pens = 0;
		$green_pens = 0;
        $tmpIds = array();
        
        foreach($xml['games'] as $game) {
            //Start Syracuse hack
            //format sample:  9:03pm Jul-5-2015
            $datetime = null;
            $datetime = preg_replace('/(\d{1,2}:\d{2}(am|pm))\s(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)-(\d{1})-(\d{4})/', '$1 $3-0$4-$5', $game['date']);
            
            foreach($game['player'] as $player) {
                
                $this->Scorecard->create();
                $this->Scorecard->set(array(
                    'player_name' => "$player[name]", 
                    'game_datetime' => date("Y-m-d H-i-s",strtotime($datetime)), 
                    'team' => $player['team'], 
                    'position' => $player['position'], 
                    'score' => ($player['score']+(1000*$player['penalties'])),
                    'shots_hit' => $player['shotsHit'],
                    'shots_fired' => $player['shotsFired'],
                    'accuracy' => (($player['shotsFired'] > 0) ? ($player['shotsHit']/$player['shotsFired']) : 0),
                    'times_zapped' => $player['timesZapped'],
                    'times_missiled' => $player['timesMissled'],
                    'missile_hits' => $player['missleHits'],
                    'nukes_detonated' => $player['nukesDetonated'],
                    'nukes_activated' => $player['nukesActivated'],
                    'nukes_canceled' => $player['nukeCancels'],
                    'medic_hits' => $player['medicHits'],
                    'own_medic_hits' => $player['ownMedicHits'],
                    'medic_nukes' => $player['medicNukes'],
                    'scout_rapid' => $player['scoutRapid'],
                    'life_boost' => $player['lifeBoost'],
                    'ammo_boost' => $player['ammoBoost'],
                    'lives_left' => $player['livesLeft'],
                    'shots_left' => $player['shotsLeft'],
                    'penalties' => $player['penalties'],
                    'shot_3hit' => $player['shot3hit'],
                    'elim_other_team' => $player['elimOtherTeam'],
                    'team_elim' => $player['teamElim'],
                    'own_nuke_cancels' => $player['ownNukeCancels'],
                    'shot_opponent' => $player['shotOpponent'],
                    'shot_team' => $player['shotTeam'],
                    'missiled_opponent' => $player['missiledOpponent'],
                    'missiled_team' => $player['missiledTeam'],
                    'resupplies' => $player['resupplies'],
                    'rank' => $player['rank'],
                    'bases_destroyed' => $player['basesDestroyed'],
                    'sp_earned' => ($player['shotOpponent'] + ($player['missiledOpponent']*2) + ($player['basesDestroyed']*5)),
                    'sp_spent' => (($player['nukesActivated']*20) + ($player['lifeBoost']*10) + ($player['ammoBoost']*15)),
                    'pdf_id' => (isset($game['file']) ? $game['file'] : null),
                    'center_id' => $center_id,
                    'type' => $type,
                    'league_id' => $league_id
                ));

                if($this->Scorecard->save()) {
                    $row++;
                    
                    $scorecard_id = $this->Scorecard->getLastInsertId();

                    for($i=1; $i<=$player['penalties']; $i++) {
                        $this->Scorecard->Penalty->create();
                        $this->Scorecard->Penalty->set(array(
                            'type' => 'Unknown',
                            'value' => -1000,
                            'scorecard_id' => $scorecard_id
                        ));
                        $this->Scorecard->Penalty->save();
                    }                
                    $tmpIds[$player['name']] = $scorecard_id;    
                }
            }
            
            $this->Scorecard->generatePlayers();
            foreach($game['player'] as $player) {
                foreach($player['playerTarget'] as $hits) {
                    $this->Scorecard->Hit->storeHits($player['name'], $tmpIds[$player['name']], $hits);
                }
            }
        }
		
		$this->Scorecard->generateMVP();
		$this->Scorecard->generateGames();
		//$this->Scorecard->generatePlayers();
		
		$this->Session->setFlash("Added $row scorecards");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
}
