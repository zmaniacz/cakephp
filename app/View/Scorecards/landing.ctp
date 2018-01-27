<div class="row">
    <div class="col-md-6">
        <div class="dropdown pull-right">
            <button class="btn btn-default dropdown-toggle" type="button" id="socialDropDown" data-toggle="dropdown">
                Jump to social games <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Centers</li>
                <?php
                    $sorted_centers = $centers;
                    asort($sorted_centers);
                    foreach($sorted_centers as $key => $value) {
                        echo "<li>".$this->Html->link($value, array(
                            'controller' => 'scorecards', 
                            'action' => 'nightly',
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => $this->Session->read('state.gametype'),
                                'centerID' => $key,
                                'leagueID' => 0
                            )
                        ))."</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="dropdown pull-left">
            <button class="btn btn-default dropdown-toggle" type="button" id="socialDropDown" data-toggle="dropdown">
                Jump to competition <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Competitions</li>
                <?php
                    foreach($league_details as $league) {
                        echo "<li>".$this->Html->link($league['League']['name'], array(
                            'controller' => 'leagues', 
                            'action' => 'standings',
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => 'league',
                                'centerID' => $league['League']['center_id'],
                                'leagueID' => $league['League']['id']
                            )
                        ))."</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2">
        <table class="table table-striped table-condensed table-bordered table-hover">
            <thead>
                <tr>
                    <th class="col-xs-6">Center</th>
                    <th class="col-xs-3">Date</th>
                    <th class="col-xs-3">Games Played</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($events as $event): ?>
                <tr>
                    <td><?= $this->Html->link($event['Center']['name']." - ".ucfirst($event['Game']['type']), array(
                                                'controller' => 'scorecards', 
                                                'action' => 'nightly',
                                                $event[0]['games_date'],
                                                implode(",", $this->request->pass),
                                                '?' => array(
                                                    'gametype' => $event['Game']['type'],
                                                    'centerID' => $event['Center']['id'],
                                                    'leagueID' => (is_null($event['Game']['league_id']) ? 0 : $event['Game']['league_id'])
                                                )
                                            ),
                                            array('class' => 'btn btn-block btn-primary')
								); ?>
                    </td>
                    <td class="text-center"><strong><?=$event[0]['games_date']; ?></strong></td>
                    <td class="text-center"><strong><?=$event[0]['games_played']; ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>