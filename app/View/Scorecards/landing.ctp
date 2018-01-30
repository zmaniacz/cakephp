<div class="jumbotron">
    <h1 class="display-5">WCT 2018</h3>
    <p class="lead">The 5th West Coast Tournament held January 16th - 18th, 2017 at Loveland LaserTag in Loveland, CO.</p>
    <p class="lead"><a class="btn btn-primary btn-lg" href="/leagues/standings?gametype=league&amp;leagueID=18&amp;centerID=10">Details <i class="fas fa-caret-right"></i></a></p>
</div>
<div class="row justify-content-center">
    <div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="socialDropDown" data-toggle="dropdown">Jump to social games</button>
            <div class="dropdown-menu">
                <h6 class="dropdown-header">Centers</h6>
                <?php
                    foreach($centers as $center) {
                        if(strtotime($center['last_played']) > strtotime('1 year ago')) {
                            echo $this->Html->link($center['name'], array(
                                'controller' => 'scorecards', 
                                'action' => 'nightly',
                                implode(",", $this->request->pass),
                                '?' => array(
                                    'gametype' => $this->Session->read('state.gametype'),
                                    'centerID' => $center['id'],
                                    'leagueID' => 0
                                )
                            ), array('class' => "dropdown-item"));
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    <div>
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Jump to Competition
            </button>
            <div class="dropdown-menu">
                <h6 class="dropdown-header">Competitions</h6>
                <?php
                    $i = 0;
                    foreach($league_details as $league) {
                        echo $this->Html->link($league['League']['name'], array(
                            'controller' => 'leagues', 
                            'action' => 'standings',
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => 'league',
                                'centerID' => $league['League']['center_id'],
                                'leagueID' => $league['League']['id']
                            )
                        ), array('class' => "dropdown-item"));
                        if ($i++ > 4) break;
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row justify-content-center">
    <h4 class="text-info d-block">Recent Events</h4>
</div>
<div class="row justify-content-center">
    <div class="col-8">
    <table class="table table-striped table-sm table-bordered table-hover" id="events_list">
        <thead>
            <tr>
                <th>Center</th>
                <th class="text-right">Date</th>
                <th class="text-right">Games Played</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        let params = new URLSearchParams();
        let events = <?= json_encode($events, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT); ?>;
        let table = $('#events_list tbody');

        events.forEach( function(item) {
            params.set('gametype', item.Game.type);
            params.set('centerID', item.Center.id);
            params.set('leagueID', (!item.Game.league_id) ? 0 : item.Game.league_id);

            let eventLink = `<a href="/scorecards/nightly/${item[0].games_date}?${params.toString()}">
                                ${item.Center.name} - <span class="text-capitalize">${item.Game.type}</span></a>`;
            
            let row = `<tr>
                        <td>${eventLink}</td>
                        <td class="text-right">${item[0].games_date}</td>
                        <td class="text-right">${item[0].games_played}</td>
                    </tr>`;
            table.append(row);
        });
    });
</script>