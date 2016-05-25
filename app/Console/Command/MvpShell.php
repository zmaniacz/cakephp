<?php
class MvpShell extends AppShell {
    public $uses = array('Scorecard');
    public function main() {
        $this->Scorecard->generateMVP(0,500);
        $this->Scorecard->generateMVP(500,500);
        $this->Scorecard->generateMVP(1000,500);
        $this->Scorecard->generateMVP(1500,500);
    }
}