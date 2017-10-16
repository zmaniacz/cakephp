<?php
	echo $this->element('filter');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('.event_table').DataTable( {
			"deferRender" : true,
            "ordering" : false,
            "paging" : true,
            "pageLength" : 10,
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
    <div class= "col-xs-8 col-xs-offset-2">
        <table 
            class="event_table table table-striped table-bordered table-hover" 
            id="event_list_table" 
            data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'ext' => 'json'))); ?>"
        >
            <thead>
                <tr>
                    <th class="col-xs-3">Event</th>
                    <th class="col-xs-3">Date</th>
                    <th class="col-xs-3">Games Played</th>
                </tr>
            </thead>
        </table>
    </div>
</div>