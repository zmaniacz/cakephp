<script type="text/javascript">
	$(document).ready(function() {
		$('.event_table').DataTable( {
			"deferRender" : true,
            "ordering" : false,
            "paging" : false,
            "pageLength" : 5,
            "searching" : false,
            "info" : false,
			"columns" : [
                { "data" : "name" },
                { "data" : "type" },
                { "data" : function ( row, type, val, meta ) {
						if (type === 'display') {
                            return row.center_short_name.toUpperCase();
						}
						
						return row.center_short_name;
					}
				},
                { "data" : function ( row, type, val, meta ) {
						if (type === 'display') {
							let date = new Date(row.last_gametime);
                            return date.toLocaleDateString();
						}
						
						return row.last_gametime;
					}
				}
			]
		});
    });
</script>
<div id="event_list" class="panel panel-primary">
	<div class="panel-heading clearfix" id="event_list_heading">
        <h3 class="panel-title pull-left">Events</h3>
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="event_list_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'social', 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th class="col-xs-3">Event</th>
                <th class="col-xs-3">Type</th>
                <th class="col-xs-3">Center</th>
                <th class="col-xs-3">Last Played</th>
            </tr>
        </thead>
    </table>
</div>