<div class="modal fade" id="mvpModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mvpModalLabel">MVP Details</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="penaltyModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="penaltyModalLabel">Penalty Details</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="teamPenaltyModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="penaltyModalLabel">Team Penalty Details</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="hitModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="hitModalLabel">Hit Details</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="matchModal" tabindex="-1">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="matchModalLabel">Match Details</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //global handlers for the various modals
        $('#penaltyModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $(this).find(".modal-body").text("Loading...");
            $(this).find(".modal-body").load(button.attr("target"));
        });
        $('#teamPenaltyModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $(this).find(".modal-body").text("Loading...");
            $(this).find(".modal-body").load(button.attr("target"));
        });
        $('#hitModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $(this).find(".modal-body").text("Loading...");
            $(this).find(".modal-body").load(button.attr("target"));
        });
        $('#matchModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $(this).find(".modal-body").text("Loading...");
            $(this).find(".modal-body").load(button.attr("target"));
        });
        $('#mvpModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $(this).find(".modal-body").text("Loading...");
            $(this).find(".modal-body").load(button.attr("target"));
        });
    });
</script>