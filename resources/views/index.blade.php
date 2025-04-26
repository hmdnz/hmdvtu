
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>ZaumaData - Home | Buy your Data, Airtime, Electricity bill and Subscription bill</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="Zauma Data, Data, Airtime, bulk sms, cable subscription" />
    <meta name="developer" content="Shahuci Global Resources" />
    <meta name="website" content="www.sgr.com.ng" />

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('main/img/logo.png') }}" />
    <link href="{{ asset('main/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('main/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('main/vendor/aos/aos.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('main/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('main/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('main/css/main.css') }}" rel="stylesheet">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('app/assets/css/toastr.css') }}">
    <!-- FontAwesome 5-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" type="text/css">
    
</head>

<body class="  ">
@php( $page = 'home')
<main class="main">
    @include('components.main.navbar-index')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1>#1 site for afordable VTU services.</h1>
            <p>Get Airtime, Data Cable Subscription easily on ZaumaData.com.ng</p>
            <div class="d-flex">
              <a href="{{route('user.login')}}" class="btn-get-started mx-2">Sign In</a>
              <a href="{{route('user.signup')}}" class="btn-get-started">Sign Up</a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
            <img src="{{asset('main/img/hero-img.png')}}" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>About Us</h2>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-8 mx-auto content" data-aos="fade-up" data-aos-delay="100">
            <p>
              At Zauma Data, we are committed to redefining convenience in the world of Virtual Top-Up (VTU) services. Our platform, Zaumadata.com.ng, specializes in providing affordable and seamless solutions for purchasing airtime, data bundles, cable subscriptions, and electricity bills. Whether you’re topping up your phone, staying connected with reliable data, or managing utility payments, Zauma Data ensures a smooth and secure experience for our customers.
            </p>
            <p>At Zauma Data, our mission is simple: to empower individuals and businesses with efficient digital solutions, ensuring they stay connected and in control of their essential services anytime, anywhere.</p>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Why Us Section -->
    <section id="why-us" class="section why-us light-background" data-builder="section">

      <div class="container-fluid">

        <div class="row gy-4">

          <div class="col-lg-7 d-flex flex-column justify-content-center order-2 order-lg-1">

            <div class="content px-xl-5" data-aos="fade-up" data-aos-delay="100">
              <h3><span>Why Choose </span><strong>ZaumaData ?</strong></h3>
              <p>
                The following qualities sets us apart from other players and they serve as our believe to providing good user experience.
              </p>
            </div>

            <div class="faq-container px-xl-5" data-aos="fade-up" data-aos-delay="200">

              <div class="faq-item faq-active">

                <h3><span>01</span> Quality Service</h3>
                <div class="faq-content">
                  <p>At ZaumaData, we pride ourselves on delivering top-notch solutions tailored to your specific needs.
                    Our services are built with precision, leveraging the latest technologies to ensure reliability and efficiency.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span>02</span> Affordability</h3>
                <div class="faq-content">
                  <p>We offer premium services at competitive prices, ensuring you get the best value for your investment.
                    Our flexible pricing plans cater to businesses of all sizes, making high-quality solutions accessible to everyone</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3><span>03</span> Customer Support</h3>
                <div class="faq-content">
                  <p>Our dedicated support team is always ready to assist you with any queries or concerns.
                    We believe in building lasting relationships, providing personalized care for every customer.
                    With 24/7 availability, we ensure your business runs smoothly without disruptions.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div>

          <div class="col-lg-5 order-1 order-lg-2 why-us-img">
            <img src="{{asset('main/img/why-us.png')}}" class="img-fluid" alt="" data-aos="zoom-in" data-aos-delay="100">
          </div>
        </div>

      </div>

    </section><!-- /Why Us Section -->

    <!-- Services Section -->
    <section id="services" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Services</h2>
        <p>The fallowing are our qulitative services we offer</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-activity icon"></i></div>
              <h4><a href="" class="stretched-link">Airtime</a></h4>
              <p>Easily recharge your mobile phone with airtime for any network in seconds.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-bounding-box-circles icon"></i></div>
              <h4><a href="" class="stretched-link">Data</a></h4>
              <p>Get affordable data plans for all networks, tailored to meet your needs with ease.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-calendar4-week icon"></i></div>
              <h4><a href="" class="stretched-link">Cable </a></h4>
              <p>Renew your cable TV subscriptions quickly and conveniently for top providers.</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon"><i class="bi bi-broadcast icon"></i></div>
              <h4><a href="" class="stretched-link">Electricity</a></h4>
              <p>Pay your electricity bills effortlessly and enjoy power with our reliable service.</p>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section dark-background">

      <img src="assets/img/cta-bg.jpg" alt="">

      <div class="container">

        <div class="row" data-aos="zoom-in" data-aos-delay="100">
          <div class="col-xl-9 text-center text-xl-start">
            <h3>Need Support?</h3>
            <p>We're here to help! Reach out to our dedicated support team for quick assistance with any issues or inquiries. Your satisfaction is our priority—contact us today and experience exceptional service!</p>
          </div>
          <div class="col-xl-3 cta-btn-container text-center">
            <a class="cta-btn align-middle" href="https://wa.me/+2348100268819/?text=Hi ZaumaData"> <i class="bi bi-whatsapp icon"></i> Contact Us</a>
          </div>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

    <!-- Pricing Section -->
    <section id="pricing" class="pricing section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pricing</h2>
        <p>Below are the pricing of our products and services</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-3 p-0" data-aos="zoom-in" data-aos-delay="100">
            <div class="pricing-item featured">
              <h3>Data</h3>
              <h4>MTN</h4>
              <ul class="mb-0">
                @foreach($MTNPackages as $package)
                <li><i class="bi bi-check"></i> <span>{{"{$package->title} - N{$package->price}"}}</span></li>
                @endforeach
              </ul>
              
              <a href="#" class="buy-btn">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3" data-aos="zoom-in" data-aos-delay="200">
            <div class="pricing-item featured">
              <h3>Data</h3>
              <h4>AIRTEL</h4>
              <ul class="mb-0">
                @foreach($AirtelPackages as $package)
                <li><i class="bi bi-check"></i> <span>{{"{$package->title} - N{$package->price}"}}</span></li>
                @endforeach
              </ul>
              <a href="#" class="buy-btn">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->
          
          <div class="col-lg-3" data-aos="zoom-in" data-aos-delay="200">
            <div class="pricing-item featured">
              <h3>Data</h3>
              <h4>GLO</h4>
              <ul class="mb-0">
                @foreach($GloPackages as $package)
                <li><i class="bi bi-check"></i> <span>{{"{$package->title} - N{$package->price}"}}</span></li>
                @endforeach
              </ul>
              <a href="#" class="buy-btn">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

          <div class="col-lg-3" data-aos="zoom-in" data-aos-delay="300">
            <div class="pricing-item featured">
              <h3>Data</h3>
              <h4>9MOBILE</h4>
              <ul class="mb-0">
                @foreach($MobilePackages as $package)
                <li><i class="bi bi-check"></i> <span>{{"{$package->title} - N{$package->price}"}}</span></li>
                @endforeach
              </ul>
              <a href="#" class="buy-btn">Buy Now</a>
            </div>
          </div><!-- End Pricing Item -->

        </div>

      </div>

    </section><!-- /Pricing Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <!-- <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p> -->
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Abdullahi Aminu</h3>
                <h4>Software Engineer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>This is the best VTU platform ever.Its affordable and reliable.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Abdullahi Aminu</h3>
                <h4>Software Engineer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>This is the best VTU platform ever.Its affordable and reliable.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Abdullahi Aminu</h3>
                <h4>Software Engineer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>This is the best VTU platform ever.Its affordable and reliable.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Abdullahi Aminu</h3>
                <h4>Software Engineer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>This is the best VTU platform ever.Its affordable and reliable.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Abdullahi Aminu</h3>
                <h4>Software Engineer</h4>
                <div class="stars">
                  <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                </div>
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>This is the best VTU platform ever.Its affordable and reliable.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-6 mx-auto">

            <div class="info-wrap">
              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="200">
                <i class="bi bi-geo-alt flex-shrink-0"></i>
                <div>
                  <h3>Address</h3>
                  <p>Shagaari Quarters, Kazaure, Jigawa State</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone flex-shrink-0"></i>
                <div>
                  <h3>Call Us</h3>
                  <p>+234-8100268819</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
                <i class="bi bi-telephone flex-shrink-0"></i>
                <div>
                  <h3>WhatsApp</h3>
                  <p>+234-8100268819</p>
                </div>
              </div><!-- End Info Item -->

              <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
                <i class="bi bi-envelope flex-shrink-0"></i>
                <div>
                  <h3>Email Us</h3>
                  <p>zaumadata@gmail.com</p>
                </div>
              </div><!-- End Info Item -->

            </div>
          </div>

        </div>

      </div>

    </section><!-- /Contact Section -->

</main>

@include('components.main.footer')

<!-- Vendor JS Files -->
<script src="{{ asset('main/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('main/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('main/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('main/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('main/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('main/vendor/waypoints/noframework.waypoints.js') }}"></script>
<script src="{{ asset('main/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('main/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('main/js/main.js') }}"></script>
<!-- Toastr js -->
<script src="{{ asset('app/assets/js/toastr.js') }}"></script>

@if (session()->has('message'))
<script>
        toastr.info("{{ session('message') }}");        
</script>
@endif

</body>

</html>