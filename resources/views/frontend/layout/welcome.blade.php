<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Template Mo">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">

    <title>MAPRO</title>
<!--

ART FACTORY

https://templatemo.com/tm-537-art-factory

-->
    <!-- Additional CSS Files -->
    <link rel="stylesheet" type="text/css" href="public/frontend/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="public/frontend/assets/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="public/frontend/assets/css/templatemo-art-factory.css">
    <link rel="stylesheet" type="text/css" href="public/frontend/assets/css/owl-carousel.css">

    </head>
    
    <body>
    
    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->
    
    
    <!-- ***** Header Area Start ***** -->
        @include('frontend.layout.navbar')
    <!-- ***** Header Area End ***** -->


    <!-- ***** Welcome Area Start ***** -->
        @include('frontend.layout.header')
    <!-- ***** Welcome Area End ***** -->


    <!-- ***** Features Big Item Start ***** -->
        @include('frontend.layout.about')
    <!-- ***** Features Big Item End ***** -->


    <!-- ***** Features Small Start ***** -->
       @include('frontend.layout.slide') 
    <!-- ***** Features Small End ***** -->


    <!-- ***** Frequently Question Start ***** -->
{{--         @include('frontend.layout.question')  --}}
    <!-- ***** Frequently Question End ***** -->


    <!-- ***** Contact Us Start ***** -->
    <br>
        @include('frontend.layout.contact')
    <!-- ***** Contact Us End ***** -->

    
    <!-- ***** Footer Start ***** -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 col-sm-12">
                    <p class="copyright">Copyright &copy; 2020 Art Factory Company 
                
                . Design: <a rel="nofollow" href="https://templatemo.com">TemplateMo</a></p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="social">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                        <li><a href="#"><i class="fa fa-rss"></i></a></li>
                        <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- jQuery -->
    <script src="public/frontend/assets/js/jquery-2.1.0.min.js"></script>

    <!-- Bootstrap -->
    <script src="public/frontend/assets/js/popper.js"></script>
    <script src="public/frontend/assets/js/bootstrap.min.js"></script>

    <!-- Plugins -->
    <script src="public/frontend/assets/js/owl-carousel.js"></script>
    <script src="public/frontend/assets/js/scrollreveal.min.js"></script>
    <script src="public/frontend/assets/js/waypoints.min.js"></script>
    <script src="public/frontend/assets/js/jquery.counterup.min.js"></script>
    <script src="public/frontend/assets/js/imgfix.min.js"></script> 
    
    <!-- Global Init -->
    <script src="public/frontend/assets/js/custom.js"></script>

  </body>
</html>
