<?php
App::uses('AppController', 'Controller');

class UploadsController extends AppController {
	public $uses = array('Scorecard');

	public function index() {
		if ($this->request->is('post')) {
			App::import('Vendor','UploadHandler',array('file' => 'UploadHandler/UploadHandler.php'));

			$options = array
			(
				'script_url' => FULL_BASE_URL.DS.'uploads/index/',
				'upload_dir' => APP.WEBROOT_DIR.DS.'parser'.DS.'incoming'.DS.$this->Session->read('center.Center.id').DS,
				'upload_url' => FULL_BASE_URL.DS.'parser'.DS.'incoming'.DS.$this->Session->read('center.Center.id').DS,
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
		if (!$this->Session->check('center.Center.id')) {
			throw new NotFoundException(__('No center defined'));
		}
	}

	public function parse() {
		$center_id = $this->Session->read('center.Center.id');
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
		$center_id = $this->Session->read('center.Center.id');
		$type = $this->Session->read('filter.type');

		$league_id = null;
		if($type == 'league' || $type == 'tournament')
			$league_id = $this->Session->read('filter.value');

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
		$handle = fopen($path.DS.$latest_filename,"r");
		fgetcsv($handle);

		$red_pens = 0;
		$green_pens = 0;

		while (($csvline = fgetcsv($handle)) !== FALSE) {
			$this->Scorecard->create();
			$this->Scorecard->set(array(
				'player_name' => $csvline[0], 
				'game_datetime' => date("Y-m-d H-i-s",strtotime($csvline[1])), 
				'team' => $csvline[2], 
				'position' => $csvline[3], 
				'score' => ($csvline[4]+(1000*$csvline[22])),
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
				'penalties' => $csvline[22],
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
				'sp_earned' => ($csvline[27] + ($csvline[29]*2) + ($csvline[33]*5)),
				'sp_spent' => (($csvline[12]*20) + ($csvline[18]*10) + ($csvline[19]*15)),
				'pdf_id' => (isset($csvline[34]) ? $csvline[34] : null),
				'center_id' => $center_id,
				'type' => $type,
				'league_id' => $league_id
			));


			if($this->Scorecard->save()) {
				$row++;

				for($i=1; $i<=$csvline[22]; $i++) {
					$this->Scorecard->Penalty->create();
					$this->Scorecard->Penalty->set(array(
						'type' => 'Unknown',
						'value' => -1000,
						'scorecard_id' => $this->Scorecard->getLastInsertId()
					));
					$this->Scorecard->Penalty->save();
				}
			}
		}
		fclose($handle);
		
		$this->Scorecard->generateMVP();
		$this->Scorecard->generateGames($this->Session->read('center.Center.id'), $this->Session->read('filter'));
		$this->Scorecard->generatePlayers($this->Session->read('center.Center.id'), $this->Session->read('filter'));
		
		$this->Session->setFlash("Added $row scorecards");
		$this->redirect(array('controller' => 'scorecards', 'action' => 'nightly'));
	}
}
