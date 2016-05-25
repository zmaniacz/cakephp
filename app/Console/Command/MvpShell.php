class MvpShell extends AppShell {
    public $uses = array('Scorecard');
    public function main() {
        Scorecard::generateMVP();
    }
}