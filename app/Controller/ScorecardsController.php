<?php

class ScorecardsController extends AppController {
	public $components = array('RequestHandler');

	public function beforeFilter() {
		$this->Auth->allow('index','overall','nightly','nightlyStats','allcenter');
		parent::beforeFilter();
	}

	public function index() {
		$this->redirect(array('controller' => 'Scorecards', 'action' => 'nightly'));
	}
	
	public function phpview() {
	}

	public function uploadpdf() {
	}

	public function upload() {
        $this->request->onlyAllow('ajax');

        App::import('Vendor','UploadHandler',array('file' => 'UploadHandler/UploadHandler.php'));

        $options = array
        (
            'script_url' => FULL_BASE_URL.DS.$this->request->params['center'].DS.'scorecards/upload/',
            'upload_dir' => APP.WEBROOT_DIR.DS.'parser'.DS.'incoming'.DS.$this->center_id.DS,
            'upload_url' => FULL_BASE_URL.DS.'parser'.DS.'incoming'.DS.$this->center_id.DS,
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

    public function parse() {
		$command = "nohup sh -c $'/home/laserforce/lfstats.redial.net/lfstats/app/webroot/parser/pdfparse.sh $this->center_id' > /dev/null 2>&1 & echo $!";
		$this->set('pid', exec($command,$output));
	}

	public function checkPid($pid) {
		//$this->request->onlyAllow('ajax');

		$cmd = "ps $pid";
		exec($cmd, $output, $result);
		if(count($output) >= 2) {
			$this->set('alive', true);
		} else {
			$this->set('alive', false);
		}
	}
	
	public function overall() {
		$this->set('commander', $this->Scorecard->getPositionStats('Commander',null,$this->center_id));
		$this->set('heavy', $this->Scorecard->getPositionStats('Heavy Weapons',null,$this->center_id));
		$this->set('scout', $this->Scorecard->getPositionStats('Scout',null,$this->center_id));
		$this->set('ammo', $this->Scorecard->getPositionStats('Ammo Carrier',null,$this->center_id));
		$this->set('medic', $this->Scorecard->getPositionStats('Medic',null,$this->center_id));
		$this->set('medic_hits', $this->Scorecard->getMedicHitStats(true, $this->center_id));
		$this->set('medic_hits_all', $this->Scorecard->getMedicHitStats(false, $this->center_id));
		$this->set('averages', $this->Scorecard->getAllAvgMVP($this->center_id));
    }
	
	public function nightly() {
		$game_dates = $this->Scorecard->getGameDates($this->center_id);
		$this->set('game_dates', $game_dates);
		
		if($this->request->isPost())
			$date = $this->request->data['Scorecard']['date'];
		else
			$date = reset($game_dates);
		
		if(!$date)
			$date = 0;

		$this->set('current_date', $date);
	}
	
	public function nightlyStats($date) {
		$this->request->onlyAllow('ajax');
		
		$this->set('scorecards', $this->Scorecard->getScorecardsByDate($date, $this->center_id));
		$this->set('games', $this->Scorecard->getGamesByDate($date, $this->center_id));
		$this->set('medic_hits', $this->Scorecard->getMedicHitStatsByDate($date, $this->center_id));
	}
	
	public function uploadCSV() {
		if ($this->request->is('post')) {
			$row=0;
			$handle = fopen($this->data['Scorecard']['file']['tmp_name'],"r");
			fgetcsv($handle);
			while (($csvline = fgetcsv($handle)) !== FALSE) {
				$this->Scorecard->create();
				$this->Scorecard->set(array(
					'player_name' => $csvline[0], 
					'game_datetime' => date("Y-m-d H-i-s",strtotime($csvline[1])), 
					'team' => $csvline[2], 
					'position' => $csvline[3], 
					'score' => $csvline[4],
					'game_type' => $csvline[5],
					'shots_hit' => $csvline[6],
					'shots_fired' => $csvline[7],
					'accuracy' => (($csvline[7] > 0) ? ($csvline[6]/$csvline[7]) : 0),
					'times_zapped' => $csvline[8],
					'times_missiled' => $csvline[9],
					'missile_hits' => $csvline[10],
					'nukes_detonated' => $csvline[11],
					'nukes_activated' => $csvline[12],
					'nukes_canceled' => $csvline[13],
					'medic_hits' => $csvline[14],
					'own_medic_hits' => $csvline[15],
					'medic_nukes' => $csvline[16],
					'scout_rapid' => $csvline[17],
					'life_boost' => $csvline[18],
					'ammo_boost' => $csvline[19],
					'lives_left' => $csvline[20],
					'shots_left' => $csvline[21],
					//Skipping penalties, aim is to enter them manually later
					//'penalties' => $csvline[22],
					'shot_3hit' => $csvline[23],
					'elim_other_team' => $csvline[24],
					'team_elim' => $csvline[25],
					'own_nuke_cancels' => $csvline[26],
					'shot_opponent' => $csvline[27],
					'shot_team' => $csvline[28],
					'missiled_opponent' => $csvline[29],
					'missiled_team' => $csvline[30],
					'resupplies' => $csvline[31],
					'rank' => $csvline[32],
					'bases_destroyed' => $csvline[33],
					'pdf_id' => (isset($csvline[34]) ? $csvline[34] : null),
					'center_id' => $this->center_id));
				$this->Scorecard->save();
				$row++;
			}
			fclose($handle);
			
			$this->Scorecard->generateMVP();
			$this->Scorecard->generateGames($this->center_id);
			$this->Scorecard->generatePlayers($this->center_id);
			
			$this->Session->setFlash("Added $row scorecards");
			$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
		}
	}

	public function parseCSV() {
		//We're only going to process the most recent file
		$path = "parser/pending/$this->center_id";

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
		$handle = fopen($path.DS.$latest_filename,"r");
		fgetcsv($handle);
		while (($csvline = fgetcsv($handle)) !== FALSE) {
			$this->Scorecard->create();
			$this->Scorecard->set(array(
				'player_name' => $csvline[0], 
				'game_datetime' => date("Y-m-d H-i-s",strtotime($csvline[1])), 
				'team' => $csvline[2], 
				'position' => $csvline[3], 
				'score' => $csvline[4],
				'game_type' => $csvline[5],
				'shots_hit' => $csvline[6],
				'shots_fired' => $csvline[7],
				'accuracy' => (($csvline[7] > 0) ? ($csvline[6]/$csvline[7]) : 0),
				'times_zapped' => $csvline[8],
				'times_missiled' => $csvline[9],
				'missile_hits' => $csvline[10],
				'nukes_detonated' => $csvline[11],
				'nukes_activated' => $csvline[12],
				'nukes_canceled' => $csvline[13],
				'medic_hits' => $csvline[14],
				'own_medic_hits' => $csvline[15],
				'medic_nukes' => $csvline[16],
				'scout_rapid' => $csvline[17],
				'life_boost' => $csvline[18],
				'ammo_boost' => $csvline[19],
				'lives_left' => $csvline[20],
				'shots_left' => $csvline[21],
				//Skipping penalties, aim is to enter them manually later
				//'penalties' => $csvline[22],
				'shot_3hit' => $csvline[23],
				'elim_other_team' => $csvline[24],
				'team_elim' => $csvline[25],
				'own_nuke_cancels' => $csvline[26],
				'shot_opponent' => $csvline[27],
				'shot_team' => $csvline[28],
				'missiled_opponent' => $csvline[29],
				'missiled_team' => $csvline[30],
				'resupplies' => $csvline[31],
				'rank' => $csvline[32],
				'bases_destroyed' => $csvline[33],
				'pdf_id' => (isset($csvline[34]) ? $csvline[34] : null),
				'center_id' => $this->center_id));
			$this->Scorecard->save();
			$row++;
		}
		fclose($handle);
		
		$this->Scorecard->generateMVP();
		$this->Scorecard->generateGames($this->center_id);
		$this->Scorecard->generatePlayers($this->center_id);
		
		$this->Session->setFlash("Added $row scorecards");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
	
	public function rebuild() {
		$mvps = $this->Scorecard->generateMVP();
		$games = $this->Scorecard->generateGames(3);
		$players = $this->Scorecard->generatePlayers(3);
		$existing = $players['existing'];
		$new = $players['new'];
		
		$this->Session->setFlash("Added $mvps MVP entries, $games game entries, games for $existing players and $new new players");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
	
	public function allcenter() {
		$this->set('top', $this->Scorecard->getTopTeams($this->center_id));
	}
}