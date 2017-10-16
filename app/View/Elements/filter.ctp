
<?php
    $typeSelect = array(
        array(
            "text" => 'All',
            "selected" => (($this->Session->read('state.gametype') == 'all') ? true : false),
            "id" => 'gametype=all&centerID='.$this->Session->read('state.centerID').'&eventID=0'
        ),
        array(
            "text" => 'Social',
            "selected" => (($this->Session->read('state.gametype') == 'social') ? true : false),
            "id" => 'gametype=social&centerID='.$this->Session->read('state.centerID').'&eventID=0'
        ),
        array(
            "text" => 'Competitive',
            "selected" => (($this->Session->read('state.gametype') == 'comp') ? true : false),
            "id" => 'gametype=comp&centerID='.$this->Session->read('state.centerID').'&eventID='.$this->Session->read('state.eventID')
        )
    );

    $eventSelect = array(
        array(
            "text" => 'All Events',
            "id" => 'gametype='.$this->Session->read('state.gametype').'&centerID=0&eventID=0'
        )
    );

    if($this->Session->read('state.gametype') == 'all' || $this->Session->read('state.gametype') == 'social') {
        $sorted_centers = $centers;
        asort($sorted_centers);
        $centerOptions = array();
        foreach($sorted_centers as $key => $value) {
            $centerOptions[] = array(
                "text" => $value,
                "id" => 'gametype='.$this->Session->read('state.gametype').'&centerID='.$key.'&eventID=0',
                "selected" => (($this->Session->read('state.centerID') == $key) ? true : false)
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
                    "id" => 'gametype=comp&centerID='.$event['Event']['center_id'].'&eventID='.$event['Event']['id'],
                    "selected" => (($this->Session->read('state.eventID') == $event['Event']['id']) ? true : false)
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
                window.location.search = url;
            }
            return false;
        });
    });
</script>
<select id="typeSelect" class="lfstatsFilter"></select>
<span> / </span>
<select id="eventSelect" class="lfstatsFilter"></select>
<hr>