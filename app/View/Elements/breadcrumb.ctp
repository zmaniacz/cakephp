<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="bcDropDown1" data-toggle="dropdown">
                <?=	Inflector::camelize(($this->Session->read('state.gametype') == 'league') ? 'competitive' : $this->Session->read('state.gametype')); ?>
            </button>
            <div class="dropdown-menu">
                <?= $this->Html->link('All', array(
                                        'controller' => $this->request->params['controller'], 
                                        'action' => $this->request->params['action'],
                                        implode(",", $this->request->pass),
                                        '?' => array(
                                            'gametype' => 'all',
                                            'centerID' => 0,
                                            'leagueID' => 0
                                        )), array('class' => 'dropdown-item')); ?>
                <?= $this->Html->link('Social', array(
                                            'controller' => $this->request->params['controller'], 
                                            'action' => $this->request->params['action'],
                                            implode(",", $this->request->pass),
                                            '?' => array(
                                                'gametype' => 'social',
                                                'centerID' => $this->Session->read('state.centerID'),
                                                'leagueID' => 0
                                            )), array('class' => 'dropdown-item')); ?>
                <?= $this->Html->link('Competitive', array(
                                            'controller' => $this->request->params['controller'], 
                                            'action' => $this->request->params['action'],
                                            implode(",", $this->request->pass),
                                            '?' => array(
                                                'gametype' => 'league',
                                                'centerID' => $this->Session->read('state.centerID'),
                                                'leagueID' => $this->Session->read('state.leagueID')
                                            )), array('class' => 'dropdown-item')); ?>
            </div>
        </div>
    </li>
    <li class="breadcrumb-item">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="bcDropDown2" data-toggle="dropdown">
            <?php
                if($this->Session->read('state.leagueID') > 0) {
                    echo $leagues[$this->Session->read('state.leagueID')];
                } elseif($this->Session->read('state.centerID') > 0) {
                    echo $selected_center['Center']['name'];
                } else {
                    echo 'All Games';
                }
            ?>
        </button>
        <div class="dropdown-menu">
            <?= $this->Html->link('All Games', array(
                    'controller' => $this->request->params['controller'], 
                    'action' => $this->request->params['action'],
                    implode(",", $this->request->pass),
                    '?' => array(
                        'gametype' => $this->Session->read('state.gametype'),
                        'centerID' => 0,
                        'leagueID' => 0
                    )
                ), array('class' => 'dropdown-item')); ?>
            <div class="dropdown-divider"></div>
            <?php
                if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'social') {
                    echo "<div class=\"dropdown-header\">Centers</div>";
                    foreach($centers as $center) {
                        echo $this->Html->link($center['name'], array(
                            'controller' => $this->request->params['controller'], 
                            'action' => $this->request->params['action'],
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => $this->Session->read('state.gametype'),
                                'centerID' => $center['id'],
                                'leagueID' => 0
                            )
                        ), array('class' => 'dropdown-item'));
                    }
                }
                if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'league') {
                    echo "<div class=\"dropdown-header\">Competitions</div>";
                    foreach($league_details as $league) {
                        echo $this->Html->link($league['League']['name'], array(
                            'controller' => $this->request->params['controller'], 
                            'action' => $this->request->params['action'],
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => 'league',
                                'centerID' => $league['League']['center_id'],
                                'leagueID' => $league['League']['id']
                            )
                        ), array('class' => 'dropdown-item'));
                    }
                }
            ?>
        </div>
    </li>
</ol>
<hr>