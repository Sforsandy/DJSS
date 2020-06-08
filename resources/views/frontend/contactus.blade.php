@extends('frontend.main')
@section('content')
   <div role="main" class="main">
				<section class="page-header page-header-modern overlay overlay-color-dark overlay-show overlay-op-7">
					<div class="container">
						<div class="row mt-0">
							<div class="col-md-12 align-self-center p-static order-2 text-center">
								<h1 class="text-9 font-weight-bold">Contact Us</h1>
								<!--<span class="sub-title">...</span>-->
							</div>
						</div>
					</div>
				</section>
				
				<div class="container">
				
				
				
					<div class="row py-4">
						<div class="col-lg-6">
							<!-- Google Maps - Go to the bottom of the page to change settings and map location. -->
							<div id="googlemaps" class="google-map mt-0" style="height: 500px;"></div>

						</div>
						<div class="col-lg-6">

							<div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="800">
								<h4 class="mt-2 mb-1">Our <strong>Office</strong></h4>
								<ul class="list list-icons list-icons-style-2 mt-2">
									<li><i class="fas fa-map-marker-alt top-6"></i> <strong class="text-dark">Address: </strong>601-A/2, Shubham Centre, Cardinal Gracious Road, Andheri East</li> 
									<li><i class="fas fa-globe top-6"></i> <strong class="text-dark">City/State/Country: </strong>Mumbai - 400099, Maharashtra, India</i>
									<li><i class="fas fa-phone top-6"></i> <strong class="text-dark">Phone: </strong> +91 (022) 2088-9990, (022) 3511-3849</li>
									<li><i class="fas fa-envelope top-6"></i> <strong class="text-dark">Email: </strong>support[at]gamersbyte.in</a></li>
								</ul>
							</div>

							<div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="950">
								<h4 class="pt-5">Business <strong>Hours</strong></h4>
								<ul class="list list-icons list-dark mt-2">
									<li><i class="far fa-clock top-6"></i> Monday - Friday - 10am to 7pm</li>
									<li><i class="far fa-clock top-6"></i> Saturday - 10am to 5pm</li>
									<li><i class="far fa-clock top-6"></i> Sunday - Closed</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
@endsection