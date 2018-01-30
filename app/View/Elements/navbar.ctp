<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="/scorecards/landing"><i class="fas fa-home"></i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent">
        <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarContent">
    <?php if(empty($landing)): ?>
        <ul class="navbar-nav">
            <li class="nav-item">
            <?php if($this->Session->read('state.gametype') == 'league'): ?>
                <a href="<?= $this->Html->url(array('controller' => 'leagues', 'action' => 'standings')); ?>" class="nav-link">Standings</a>
            <?php else: ?>
                <a href="<?= $this->Html->url(array('controller' => 'scorecards', 'action' => 'nightly')); ?>" class="nav-link">Nightly Stats</a>
            <?php endif; ?>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'scorecards', 'action' => 'overall')); ?>">Top Players</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'games', 'action' => 'index')); ?>">Game List</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'scorecards', 'action' => 'leaderboards')); ?>">Leader(Loser)boards</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'games', 'action' => 'overall')); ?>">Center Stats</a></li>
            <li class="nav-item">
            <?php if($this->Session->read('state.gametype') == 'league'): ?>
                <a class="nav-link" href="<?= $this->Html->url(array('controller' => 'scorecards', 'action' => 'allstar')); ?>">All Star Rankings</a></li>
            <?php else: ?>
                <a class="nav-link" href="<?= $this->Html->url(array('controller' => 'scorecards', 'action' => 'allcenter')); ?>">All-Center Teams</a></li>
            <?php endif; ?>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'penalties', 'action' => 'index')); ?>">Penalties</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'pages', 'action' => 'aboutSM5')); ?>">About SM5</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'leagues', 'action' => 'standings', '?' => array('gametype' => 'league', 'leagueID' => 18, 'centerID' => 10))); ?>">WCT 2018</a></li>
            <?php if(AuthComponent::user('role') === 'admin' || (AuthComponent::user('role') === 'center_admin' && AuthComponent::user('center') == $this->Session->read('state.centerID'))): ?>
                <li class="nav-item"><a class="nav-link" href="<?= $this->Html->url(array('controller' => 'uploads', 'action' => 'index')); ?>">Upload PDFs</a></li>
            <?php endif; ?>
        </ul>
        <?php endif; ?> 
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/pages/twitch" id='twitch_status'>Twitch </a>
            </li>
            <li class="nav-item">
                <?php if (AuthComponent::user('id')): ?>
                    <a class="nav-link" href="/users/logout"><?= AuthComponent::user('username') ?> Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="/users/login">Login</a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</nav>