<h6 class="text-center">
    <small id="db_stats_text"></small>
</h6>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: `/scorecards/getDBStats.json`
        }).done(function (response) {
            $('#db_stats_text').text(`Players have shot each other ${response.scorecard_stats[0].total_hits} times in ${response.game_stats[0].total_games} games with ${response.scorecard_stats[0].total_scorecards} individual scorecards.`);
        });
    });
</script>