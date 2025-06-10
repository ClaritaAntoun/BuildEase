<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BuildEase - Premium Home Construction</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Add this to your Google Fonts link -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
   
    body {
        font-family: 'Playfair Display', serif;
        background: #f8f9fa;
        color: #495057;
    }

    /* Header Top Bar */
    .header-area {
        background: #1A2A3A; /* Deep navy blue */
        padding: 12px 0;
        color:orangered;
        font-family: 'Poppins', sans-serif;
        border-bottom: 1px solid rgba(212, 175, 55, 0.1);
    }

    .header-left a, .header-right ul li a {
        color:orangered;
        font-weight: 500;
        transition: 0.3s;
        font-size: 15px;
    }
    .header-left a:hover, .header-right ul li a:hover {
        color: #FFFFFF;
        text-decoration: none;
    }

    /* Navigation Bar */
    .navigation {
        background: #FFFFFF;
        padding: 20px 0;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        border-bottom: 2px solid orangered ;
    }

    .logo {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #1A2A3A;
        letter-spacing: 0.5px;
    }
    .logo span {
        color:orangered;
        font-weight: 400;
    }

    .navbar-nav .nav-link {
        color: #1A2A3A !important;
        font-weight: 500;
        padding: 15px 20px !important;
        transition: 0.3s;
        font-size: 16px;
        position: relative;
    }

    .navbar-nav .nav-link:hover, 
    .navbar-nav .nav-item.active .nav-link {
        color:orangered;
    }

    .navbar-nav .nav-link:hover::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background:orangered;
    }

    /* Dropdown Menu */
    .dropdown-menu {
        background: #FFFFFF;
        border: 1px solid rgba(26, 42, 58, 0.1);
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 10px !important;
    }
    .dropdown-menu .dropdown-item {
        color: #1A2A3A !important;
        padding: 12px 25px;
        font-size: 15px;
        transition: 0.3s;
    }
    .dropdown-menu .dropdown-item:hover {
        background: #F8F9FA;
        color:orangered!important;
    }

    /* Call to Action Button */
    .appoint-btn a {
        background: orangered;
        color: #1A2A3A !important;
        padding: 14px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
        letter-spacing: 0.5px;
        border: 2px solid transparent;
    }
    .appoint-btn a:hover {
        background: #1A2A3A;
        color: #FFFFFF !important;
        border-color:orangered;
        transform: translateY(-2px);
    }

    /* Responsive Adjustments */
    @media (max-width: 991px) {
        .navbar-collapse {
            background: #FFFFFF;
            padding: 20px;
            margin-top: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
    }
</style>



  </head>
  <body>
    <div class="header" id="header">
      <header class="header-area">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-3 text-left">
              <div class="header-left">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
            <div class="col-md-9 text-right">
              <div class="header-right">
                <ul class="list-inline mb-0">
                  <li class="list-inline-item"><i class="fas fa-map-marker-alt"></i> Lebanon</li>
                  <li class="list-inline-item"><i class="fas fa-mobile-alt"></i> <a href="#">+961 81 111 000</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </header>

      <nav class="navigation">
        <div class="container">
          <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index.php">
              <div class="logo">Build<span>Ease</span></div>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">About Us</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Profile</a>
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Services</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="landSurveying.php">Land Surveying</a>
                    <a class="dropdown-item" href="civilEngineering.php">Civil Engineering</a>
                    <a class="dropdown-item" href="excavation.php">Excavation</a>
                    <a class="dropdown-item" href="construction.php">Construction</a>
                    <a class="dropdown-item" href="plumbing.php">Plumbing</a>
                    <a class="dropdown-item" href="electricitySystem.php">Electricity System</a>
                    <a class="dropdown-item" href="carpentry.php">Carpentry</a>
                    <a class="dropdown-item" href="tiling.php">Tiling</a>
                    <a class="dropdown-item" href="interiorDesign.php">Interior Design</a>
                  </div>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">Projects</a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Complete Projects</a>
                    <a class="dropdown-item" href="#">Uncomplete Projects</a>
                  </div>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Clients</a></li>
                <li class="nav-item"><a class="nav-link" href="logInPage.php">Login</a></li>
              </ul>
            </div>
          </nav>
        </div>
      </nav>
    </div>
  </body>
</html>
