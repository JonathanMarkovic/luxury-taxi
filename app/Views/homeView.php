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
    <div class ="bookRow">
        <div class="bookCol">
            <label>First Name</label>
          <input type="text" class="form-control" placeholder="First Name">
         </div>
         <div class="bookCol">
                  <label>Last Name</label>
                 <input type="text" class="form-control" placeholder="Last Name">
                </div>
            </div>

<div class="bookRow">
    <div class="bookCol">
    <label>Email</label>
    <div class="inputIcon"><i class="bi bi-envelope"></i></div>
    <input type = "email" class = "form-control" placeholder="Email">
</div>


<div class= "bookCol">
    <label>Phone</label>
    <div class="inputIcon"><i class="bi bi-telephone"></i></div>
         <input type="text" class="form-control" placeholder="Phone">
    </div>
    </div>

<div class="bookRow">
      <div class="bookCol">
        <label>Pickup</label>
          <div class="inputIcon"><i class="bi bi-geo-alt"></i></div>
             <input type="text" class="form-control" placeholder="Pickup">
                </div>

   
       <div class="bookCol">
                 <label>Dropoff</label>
             <div class="inputIcon"><i class="bi bi-geo-alt"></i></div>
                    <input type="text" class="form-control" placeholder="Dropoff">
                </div>
            </div>
            <div class="bookRow">
         <div class="bookCol">
                     <label>Date</label>
                     <div class="inputIcon"><i class="bi bi-calendar-event"></i></div>
                    <input type="date" class="form-control">
                 </div>
    
        <div class="bookCol">
                    <label>Start Time</label>
                    <div class="inputIcon"><i class="bi bi-clock"></i></div>
                    <input type="time" class="form-control">
                </div>


             <div class="bookCol">
                    <label>End Time</label>
                    <div class="inputIcon"><i class="bi bi-clock"></i></div>
                    <input type="time" class="form-control">
                </div>
            </div>


            <div class="bookRow">
                <div class="bookFull">
                    <label>Comments</label>
                    <textarea class="form-control" rows="3" placeholder="Comments"></textarea>
                </div>
            </div>

            <button class="reserveBtn">Reserve</button>
         </form>
      </div>
  </section>

  <section class="aboutWrap">
    <div class="aboutImgLeft">
        <img src="/uploads/images/car_5_6924c126e86e0.jpg" alt="">
    </div>

    <div class="aboutText">
        <h2 class="aboutTitle">Solaf Performance</h2>
        <p>We combine luxury, reliability, and professionalism to deliver an exceptional travel experience.</p>
    </div>
</section>

<section class="stepsWrap">
    <h2 class="stepsTitle">How it works</h2>

    <div class="stepsBox">

        <div class="stepItem">
            <div class="stepIcon"><i class="bi bi-car-front-fill"></i></div>
            <h4 class="stepHead">Reserve Your Car</h4>
            <p>Choose your vehicle.</p>
        </div>

        <div class="stepItem">
            <div class="stepIcon"><i class="bi bi-check-circle-fill"></i></div>
            <h4 class="stepHead">Admin Confirmation</h4>
            <p>We confirm your booking.</p>
        </div>

        <div class="stepItem">
            <div class="stepIcon"><i class="bi bi-credit-card-2-front-fill"></i></div>
            <h4 class="stepHead">Secure Booking</h4>
            <p>Pay deposit to finalize.</p>
        </div>

        <div class="stepItem">
            <div class="stepIcon"><i class="bi bi-clock-history"></i></div>
            <h4 class="stepHead">Pickup & Go</h4>
            <p>Driver arrives on time.</p>
        </div>

    </div>
</section>
<section class="carsWrap">
    <h2 class="carsTitle">Our Cars</h2>

    <div class="carsRow">
        <div class="carBox">Empty</div>
        <div class="carBox">Empty</div>
        <div class="carBox">Empty</div>
    </div>
</section>



<?php

ViewHelper::loadJsScripts();
ViewHelper::loadFooter();
?>
