<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS --> 
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css"> 
 
    <title> @isset($title)    
            {{ $title }}
        @endisset</title>
  </head>
  <body>
    <!-- -------------------- Top bar Start -------------------- -->
    <section class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <ul class="accessibility-icons">
                      <li class="skip-content">
                        <a href="#">Skip to main content</a>
                    </li>
                    <li class="skip-content">
                      <a href="#">Sitemap</a>
                    </li>
                    </ul>
                    <ul class="accessibility-icons float-end">
                      <li>Theme</li>
                      <li class="screen-reader">
                        <span>Screen Reader Access</span>
                      </li>
                        <li class="screen-reader">
                            <a href="#">A-</a>
                            <a href="#">A</a>
                            <a href="#">A+</a>
                        </li> 
                    </ul>
                </div>
            </div>
        </div>
    </section>

     <!-- header bar start -->

    <section class="wrapper header-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <a href="#" class="brand">
                        <img src="img/logo.png">
                    </a>
                </div>
                <div class="col-lg-6 col-md-6 text-right">
                    
                </div>
            </div>
        </div>
    </section>
 <!-- -------------------- Navbar end -------------------- -->

  <section class="navigation-bar">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="{{ url('cases') }}">Case Status </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url('judge') }}">Judgments/Daily Orders</a>
            </li>
             
          </ul>
        </div>
        </div>
      </nav>
    </section>
    <!-- innerpage banner section -->
    <div class="inner-banner-sec">
      <div class="container">
        <div class="row">
            <h4><span>@isset($title)    
            {{ $title }}
        @endisset</span></h4>
            <ol>
            <li><a href="/">Home</a></li>
            <li>@isset($title)    
            {{ $title }}
        @endisset</li>
            </ol>
          </div>
        </div>
      </div>
