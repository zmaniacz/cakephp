
<?php
    $typeSelect = array(
        array(
            "text" => 'All',
            "selected" => (($this->Session->read('state.gametype') == 'all') ? true : false),
            "id" => html_entity_decode($this->Html->url(array('?' => array(
                'gametype' => 'all',
                'centerID' => $this->Session->read('state.centerID'),
                'eventID' => 0,
                'selectedEvent' => $this->Session->read('state.selectedEvent')
            ))))
        ),
        array(
            "text" => 'Social',
            "selected" => (($this->Session->read('state.gametype') == 'social') ? true : false),
            "id" => html_entity_decode($this->Html->url(array('?' => array(
                'gametype' => 'social',
                'centerID' => $this->Session->read('state.centerID'),
                'eventID' => 0,
                'selectedEvent' => $this->Session->read('state.selectedEvent')
            ))))
        ),
        array(
            "text" => 'Competitive',
            "selected" => (($this->Session->read('state.gametype') == 'comp') ? true : false),
            "id" => html_entity_decode($this->Html->url(array('?' => array(
                'gametype' => 'comp',
                'centerID' => $this->Session->read('state.centerID'),
                'eventID' => 0,
                'selectedEvent' => $this->Session->read('state.selectedEvent')
            ))))
        )
    );

    $eventSelect = array(
        array(
            "text" => 'All Events',
            "id" => html_entity_decode($this->Html->url(array('?' => array(
                'gametype' => $this->Session->read('state.gametype'),
                'centerID' => 0,
                'eventID' => 0,
                'selectedEvent' => $this->Session->read('state.selectedEvent')
            ))))
        )
    );

    if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'social') {
        $sorted_centers = $centers;
        asort($sorted_centers);
        $centerOptions = array();
        foreach($sorted_centers as $key => $value) {
            $centerOptions[] = array(
                "text" => $value,
                "selected" => (($this->Session->read('state.centerID') == $key) ? true : false),
                "id" => html_entity_decode($this->Html->url(array('?' => array(
                    'gametype' => $this->Session->read('state.gametype'),
                    'centerID' => $key,
                    'eventID' => 0,
                    'selectedEvent' => $this->Session->read('state.selectedEvent')
                ))))
            );
        }
    }

    if(!empty($centerOptions)) {
        $eventSelect[] = array(
            "text" => 'Centers',
            "children" => $centerOptions
        );
    }

    if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'comp') {
        $compOptions = array();
        foreach($event_details as $event) {
            if($event['Event']['is_comp']) {
                $compOptions[] = array(
                    "text" => $event['Event']['name'],
                    "selected" => (($this->Session->read('state.eventID') == $event['Event']['id']) ? true : false),
                    "id" => html_entity_decode($this->Html->url(array('?' => array(
                        'gametype' => 'comp',
                        'centerID' => $event['Event']['center_id'],
                        'eventID' => $event['Event']['id'],
                        'selectedEvent' => $event['Event']['id']
                    ))))
                );
            }
        }
    }

    if(!empty($compOptions)) {
        $eventSelect[] = array(
            "text" => 'Competitions',
            "children" => $compOptions
        );
    };
    
    $jsonTypeSelect = json_encode($typeSelect);
    $jsonEventSelect = json_encode($eventSelect);
?>
<script>
    $(document).ready(function() {
        $('#typeSelect').select2({
            minimumResultsForSearch: -1,
            data: <?= $jsonTypeSelect; ?>
        });
        $('#eventSelect').select2({
            data: <?= $jsonEventSelect; ?>
        });

        $('.lfstatsFilter').on('select2:select', function(e) {
            var url = $(this).val();
            if (url) {
                window.location = url;
            }
            return false;
        });
    });
</script>
<select id="typeSelect" class="lfstatsFilter"></select>
<span> / </span>
<select id="eventSelect" class="lfstatsFilter"></select>
<hr>