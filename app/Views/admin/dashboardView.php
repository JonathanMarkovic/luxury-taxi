<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Admin Dashboard';

//TODO: We need to load an admin-specific header.
ViewHelper::loadAdminHeader($page_title);
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div><br>

    <div id='calendar'>


    </div>


</main>

<script>
    $(document).ready(function(){
        var events =  <?php echo json_encode($events ?? []); ?>;

        $('#calendar').fullCalendar({
            header: {
                left: 'prev, next, today',
                center: 'title',
                right: 'month, agendaWeek, agendaDay'
            },
            events: events
        })

    });
</script>

<?php

ViewHelper::loadJsScripts();

//TODO: We need to load an admin-specific footer.
ViewHelper::loadAdminFooter();
?>
