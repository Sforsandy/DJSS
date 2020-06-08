@extends('frontend.main')
@section('content')
<div class="slider-container rev_slider_wrapper" style="height: 100vh;">
					<div id="revolutionSlider" class="slider rev_slider " data-version="5.4.8" data-plugin-revolution-slider data-plugin-options="{'sliderLayout': 'fullscreen', 'delay': 9000, 'gridwidth': 1170, 'gridheight': 550, 'disableProgressBar': 'on', 'responsiveLevels': [4096,1200,992,500], 'navigation' : {'arrows': { 'enable': true, 'style': 'arrows-style-1 arrow-dark' }, 'bullets': {'enable': true, 'style': 'bullets-style-1', 'h_align': 'center', 'v_align': 'bottom', 'space': 7, 'v_offset': 70, 'h_offset': 0}}}">
						<ul>
							<li data-transition="fade" class="slide-overlay slide-overlay-level-0">
								<img src="{{ URL::asset('public/image/Banner4.jpg') }}" 
									alt=""
									data-bgposition="center center" 
									data-bgfit="cover" 
									data-bgrepeat="no-repeat" 
									class="rev-slidebg img-responsive">

								<div class="tp-caption font-weight-bold text-color-light negative-ls-2"
									data-frames='[{"delay":1000,"speed":2000,"frame":"0","from":"sX:1.5;opacity:0;fb:20px;","to":"o:1;fb:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
									data-x="center"
									data-y="bottom"  data-voffset="['120','120','120','140']"
									data-fontsize="['55','60','60','60']"
									data-lineheight="['55','55','55','95']" style="color: #f43636fa !important;">WE ARE DEEP JYOTI SEVA SANSTHA.</div>

								<div class="tp-caption font-weight-light text-center ws-normal"
									data-frames='[{"from":"opacity:0;","speed":300,"to":"o:1;","delay":2000,"split":"chars","splitdelay":0.04,"ease":"Power2.easeInOut"},{"delay":"wait","speed":1000,"to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]'
									data-x="center"
									data-y="bottom" data-voffset="['80','80','80','130']"
									data-width="['570','570','570','1000']"
									data-fontsize="['22','22','22','42']"
									data-lineheight="['29','29','29','10']"
									style="color: #f43636fa;">#NAJARIYA BADALIYE.<!--<strong class="text-color-light">30,000</strong>--></div>
				
								<!-- <a class="tp-caption btn btn-primary font-weight-semibold rounded-0"
									data-frames='[{"delay":3500,"speed":2000,"frame":"0","from":"opacity:0;y:50%;","to":"o:1;y:0;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"opacity:0;fb:0;","ease":"Power3.easeInOut"}]'
									href="https://events.gamerzbyte.com/public/uploads/app.apk"
									data-x="center" data-hoffset="0"
									data-y="bottom" data-voffset="['-20','-20','-20','-40']"
									data-whitespace="nowrap"	
									data-fontsize="['15','15','15','33']"	
									data-paddingtop="['20','20','20','45']"
									data-paddingright="['45','45','45','110']"
									data-paddingbottom="['20','20','20','45']"				 
									data-paddingleft="['45','45','45','110']">DOWNLOAD APK</a> -->
								
							</li>
						</ul>
					</div>
				</div>

				
				<div class="container">
					<div class="row justify-content-center pt-3 my-3">
						<div class="col-lg-12 text-center appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="100">
							<h1 class="font-weight-bold mb-3 text-color-primary">ABOUT US</h1>
							<!-- <p class="lead text-4">At GamerzByte, <u>eSports</u> or <u>Competitive Gaming</u> Tournaments, Ladders and Leagues are played across multiple platforms such as PC, Console, Mobile and VR.</p> -->
						</div>
					</div>
				</div>
				<div class="container pb-2">				
					<div class="row py-3 my-3">
						<div class="col">					
							<div class="owl-carousel owl-theme mb-0" data-plugin-options="{'responsive': {'0': {'items': 1}, '476': {'items': 1}, '768': {'items': 4}, '992': {'items': 4}, '1200': {'items': 4}}, 'autoplay': true, 'autoplayTimeout': 3000, 'dots': false}">
								<div>
									<img class="img-fluid" src="img/games/pubg-mobile.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/fortnite.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/fifa19.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/dota2.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/pubg.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/counter-strike.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/apex-legends.jpg" alt="">
								</div>
								<div>
									<img class="img-fluid" src="img/games/call-of-duty.jpg" alt="">
								</div>
							</div>							
						</div>
					</div>
				</div>
				
				
				<section class="section section-dark section-height-4 border-0 m-0">
					<div class="container appear-animation" data-appear-animation="fadeIn">

						<div class="row">
							<div class="col text-center">
								<h2 class="text-color-light text-8 line-height-1 ls-0 mb-4"><strong>SOCIAL WORK</strong> For <strong>Better Society</strong>!</h2>
							</div>
						</div>
						<div class="row pt-4 my-4">
							<div class="col-lg-4">
								<div class="featured-box featured-box-primary border-radius-0 featured-box-effect-1">
									<div class="box-content box-content-border-0 border-radius-0 p-5 ">
										<h4 class="font-weight-normal text-5 text-dark"><strong class="font-weight-extra-bold text-color-primary">EDUCATION</strong></h4>
										<p class="mb-0 pt-4 my-4 text-dark">Join the ever-growing community of passionate gamers. Enjoy gaming with like minded people.</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="featured-box featured-box-primary border-radius-0 featured-box-effect-1">
									<div class="box-content box-content-border-0 border-radius-0 p-5 ">
										<h4 class="font-weight-normal text-5 text-dark"><strong class="font-weight-extra-bold text-color-primary">BETTER ENVIRONMENT</strong></h4>
										<p class="mb-0 pt-4 my-4 text-dark">Learn the concepts of game economies, team building, coordination, focus and leadership skills.</p>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="featured-box featured-box-primary border-radius-0 featured-box-effect-1">
									<div class="box-content box-content-border-0 border-radius-0 p-5 ">
										<h4 class="font-weight-normal text-5 text-dark"><strong class="font-weight-extra-bold text-color-primary">BETTER JOB</strong></h4>
										<p class="mb-0 pt-4 my-4 text-dark">Earn by using your stupendous skills and be a leader. Let the community know who you are and get recognized.</p>
									</div>
								</div>
							</div>						
						</div>
					</div>
				</section>				
				<section class="section section-light section-height-4 border-0 m-0">
					<div class="container appear-animation" data-appear-animation="fadeIn">

						<div class="row">
							<div class="col text-center">
								<h2 class="text-8 line-height-1 text-color-secondary ls-0 mb-4">Want to organize any <strong>SOCIETY</strong> Fuction in your <strong>locality.</strong>?</h2>
									<div class="call-to-action-btn">
											<a href="contact-us.php" class="btn btn-quaternary  btn-small mb-2">CONNECT WITH US</a>
									</div>
							</div>
						</div>
					</div>
				</section>
@endsection