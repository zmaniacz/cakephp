<script type="text/javascript">
	$(document).ready(function() {
		$('#recent_events_table').DataTable( {
			"deferRender" : true,
            "ordering" : false,
            "paging" : false,
            "pageLength" : 5,
            "searching" : false,
            "info" : false,
			"columns" : [
				{ "data" : function ( row, type, val, meta) {			
						if (type === 'display') {
							return '<a href="/events/view/'+row.id+'?gametype='+row.type+'&centerID='+row.center_id+'&eventID='+row.id+'" class="btn btn-info btn-block">'+row.center_name+" - "+row.name+'</a>';
						}
						return row.name;
					},
					"width" : "200px" 
				},
                { "data" : function ( row, type, val, meta ) {
						if (type === 'display') {
							let date = new Date(row.last_gametime);
                            return date.toLocaleDateString();
						}
						
						return row.last_gametime;
					}
				},
                { "data" : "games_played" }
			]
		});
    });
</script>
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
                            'controller' => 'events', 
                            'action' => 'view',
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => 'social',
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
                    foreach($event_details as $event) {
                        if($event['Event']['type'] != 'social') {
                            echo "<li>".$this->Html->link($event['Event']['name'], array(
                                'controller' => 'leagues', 
                                'action' => 'standings',
                                implode(",", $this->request->pass),
                                '?' => array(
                                    'gametype' => $event['Event']['type'],
                                    'centerID' => $event['Center']['id'],
                                    'eventID' => $event['Event']['id']
                                )
                            ))."</li>";
                        }
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class= "col-xs-8 col-xs-offset-2">
        <table 
            class="event_table table table-striped table-bordered table-hover" 
            id="recent_events_table" 
            data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', '?' => array('limit' => 10), 'ext' => 'json'))); ?>"
        >
            <thead>
                <tr>
                    <th class="col-xs-6">Event</th>
                    <th class="col-xs-3">Date</th>
                    <th class="col-xs-3">Games Played</th>
                </tr>
            </thead>
        </table>
    </div>
</div>