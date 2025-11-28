<?php

use App\Helpers\ViewHelper;
//TODO: set the page title dynamically based on the view being rendered in the controller.
$page_title = 'Home';
ViewHelper::loadHeader($page_title);
?>

<section class ="heroWrap">
    <div class = "bookingWrap">
    
    <ul class="bookingTabs">
        <li><button class ="bookingTab active">Transfer</button></li>
        <li><button class ="bookingTab">Hourly</button></li>
</ul> 

<form class="bookingForm">
    <div class ="bookingRow">
        <div class="bookingCol">
            <label>Fist Name</label>
          <input type="text" class="form-control" placeholder="First Name">
         </div>

<div class="bookCol">
    <label>Email</label>
    <div class="inputIcon"><i class="bi bi-envelope"></i></div>
    <input type = "email" cclass = "form-control" placeholder="Email">
</div>


<div class= "bookCol">
    <label>Dropoff</label>
    <div class="inputIcon"><i class="bi bi-telephone"></i></div>
         <input type="text" class="form-control" placeholder="Phone">
    </div>
    </div>
<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
