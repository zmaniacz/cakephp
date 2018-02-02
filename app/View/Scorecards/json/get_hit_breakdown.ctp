<?php
    if(!empty($hits)) {
        $green_table = "";
        $red_table = "";
        foreach($hits as $hit) {
            if($hit['id'] != $player_id) {
                if($hit['team'] == 'green') {
                    $green_line = "<tr>";
                    
                    $green_line .= "<td>".$hit['name']."</td>";
                    $green_line .= "<td>".$hit['position']."</td>";
                    $green_line .= "<td>".$hit['hit']."</td>";
                    $green_line .= "<td>".$hit['hitBy']."</td>";
                    $green_line .= "<td>".$hit['missile']."</td>";
                    $green_line .= "<td>".$hit['missileBy']."</td>";
            
                    $green_line .= "</tr>";
                    
                    $green_table .= $green_line;
                } else {
                    $red_line = "<tr>";
                    
                    $red_line .= "<td>".$hit['name']."</td>";
                    $red_line .= "<td>".$hit['position']."</td>";
                    $red_line .= "<td>".$hit['hit']."</td>";
                    $red_line .= "<td>".$hit['hitBy']."</td>";
                    $red_line .= "<td>".$hit['missile']."</td>";
                    $red_line .= "<td>".$hit['missileBy']."</td>";
            
                    $red_line .= "</tr>";
                    
                    $red_table .= $red_line;
                }
            } else {
                $title_line = "<h3>".$hit['name']." - <span class=\"text-".(($hit['team'] == 'red') ? "danger" : "success")." text-capitalize\">".$hit['team']." ".$hit['position']."</span></h3>";
            }
        }
?>
    <?=$title_line; ?>
    <table class="table table-bordered table-sm">
        <thead class="table-success">
            <th>Name</th>
            <th>Position</th>
            <th class="text-right">Shot</th>
            <th class="text-right">Shot By</th>
            <th class="text-right">Missiled</th>
            <th class="text-right">Missiled By</th>
        </thead>
        <tbody>
            <?= $green_table; ?>
        </tbody>
    </table>
    <table class="table table-bordered table-sm">
        <thead class="table-danger">
            <th>Name</th>
            <th>Position</th>
            <th class="text-right">Shot</th>
            <th class="text-right">Shot By</th>
            <th class="text-right">Missiled</th>
            <th class="text-right">Missiled By</th>
        </thead>
        <tbody>
            <?= $red_table; ?>
        </tbody>
    </table>
<?php
    } else {
        echo "No data available";
    }
?>