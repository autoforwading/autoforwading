<?php include('server.php'); checkLogin(); ?>
<!DOCTYPE html>
<html>
  <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dark Bootstrap Admin by Bootstrapious.com</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS for theme component-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">

    <!-- bootstrap for custom purpose -->
    <link href="css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
    <!-- BOOTSTRAP ICONS -->
    <link rel="stylesheet" href="icons-reference/ICONS/bootstrap-icons.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom Font Icons CSS-->
    <!-- <link rel="stylesheet" href="css/all.css"> -->
    <link rel="stylesheet" href="css/font.css">

    <!-- datepicker flatpickr -->
    <link href="css/flatpickr.min.css" rel="stylesheet">

    <!-- Multi select -->
    <link rel="stylesheet" href="css/bootstrap-select.css">

    <!-- select box search -->
    <link rel="stylesheet" href="css/selectize.bootstrap3.min.css" >


    <!-- datatable css -->
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">

    <!-- export table -->
    <link href="css/export/bootstrap-table.css" rel="stylesheet">

    <style>
      #toolbar {
        margin: 0;
      }
      body {
        overflow-x: hidden; /* Hide scrollbars vertically */
      }
    </style>

    <!-- fontawesome -->
    <script src="js/fontawesome5ff245294c.js" crossorigin="anonymous"></script>
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="css/cssfont.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

    <script src="js/Chart.min.js"></script>
  </head>
  <body>
    <header class="header">   
      <nav class="navbar navbar-expand-lg">
        <div class="search-panel">
          <div class="search-inner d-flex align-items-center justify-content-center">
            <div class="close-btn">Close <i class="fa fa-close"></i></div>
            <form id="searchForm" action="#">
              <div class="form-group">
                <input type="search" name="search" placeholder="What are you searching for...">
                <button type="submit" class="submit">Search</button>
              </div>
            </form>
          </div>
        </div>
        <div class="container-fluid d-flex align-items-center justify-content-between">

          <div class="navbar-header">
            <!-- Navbar Header-->
            

            <a href="index.php" class="navbar-brand">
              <div class="brand-text brand-big visible text-uppercase">
                <?php
                  $companyid = $my['companyid'];
                  // Step 1: Define the company name
                  $companyname = allData('companies', $companyid, 'companyname'); // This can be any name

                  // Step 2: Split the name into words using space as the delimiter
                  $words = explode(" ", $companyname);

                  // Step 3: Check if there is more than one word
                  if(count($words) > 1) {
                    // Step 4: Loop through each word and get the first letter
                    for($i = 0; $i < count($words); $i++) {
                        // Get the first letter of each word and store it in a variable
                        $letter_variable = "w_" . ($i + 1); // Dynamic variable name like $w_1, $w_2, etc.
                        $$letter_variable = strtoupper($words[$i]); // Store uppercase first letter
                    }
                    echo"<strong class=\"text-primary\">$w_1</strong><strong>&nbsp;$w_2</strong>";
                  } else {echo "The name consists of one word.";}

                ?>
                <!-- <strong class="text-primary">MULTIPORT</strong><strong>&nbsp;SHIPPING</strong> -->
              </div>
              <div class="brand-text brand-sm">
                <?php
                  $companyid = $my['companyid'];
                  // Step 1: Define the company name
                  $companyname = allData('companies', $companyid, 'companyname'); // This can be any name

                  // Step 2: Split the name into words using space as the delimiter
                  $words = explode(" ", $companyname);

                  // Step 3: Check if there is more than one word
                  if(count($words) > 1) {
                    // Step 4: Loop through each word and get the first letter
                    for($i = 0; $i < count($words); $i++) {
                        // Get the first letter of each word and store it in a variable
                        $letter_variable = "w_" . ($i + 1); // Dynamic variable name like $w_1, $w_2, etc.
                        $$letter_variable = strtoupper(substr($words[$i], 0, 1)); // Store uppercase first letter
                    }
                    echo"<strong class=\"text-primary\">$w_1</strong><strong>$w_2</strong>";
                  } else {
                    echo "The name consists of one word.";
                  }

                ?>
              </div>
            </a>

            <!-- Sidebar Toggle Btn-->
            <button class="sidebar-toggle"><i class="fa fa-long-arrow-left"></i></button>
          </div>

          <div class="list-inline no-margin-bottom">
            <?php if(isset($_SESSION['email']) && $_SESSION['email'] == 'skturan2405@gmail.com'){ ?>    
            <!-- <div class="list-inline-item">
              <a onClick="javascript: return confirm('Are you sure you want to destroy all data?');"  href="index.php?destroy=destroy" class="nav-link">
                Destroy <i class="bi bi-trash"></i>
              </a>
            </div>

            <div class="list-inline-item">
              <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#switchModal">
                Switch <i class="bi bi-arrow-clockwise"></i>
              </a>
            </div> -->
            <?php } ?>

        

            <!-- <div class="list-inline-item logout">
              <form method="post" action="index.php">
                <button type="submit" name="backup_database" class="btn btn-secondary btn-sm">
                  <i class="bi bi-cloud-arrow-up"></i>
                </button>

                <button type="submit" name="restore_database" class="btn btn-success btn-sm">
                  <i class="bi bi-arrow-clockwise"></i>
                </button>
              </form>
            </div> -->
            
            <!-- <div class="list-inline-item">
              <a href="#" class="search-open nav-link"><i class="icon-magnifying-glass-browser"></i></a>
            </div> -->

            <!-- <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link messages-toggle">
              <i class="icon-email"></i><span class="badge dashbg-1">5</span></a>
              <div aria-labelledby="navbarDropdownMenuLink1" class="dropdown-menu messages"><a href="#" class="dropdown-item message d-flex align-items-center">
                  <div class="profile"><img src="img/avatar-3.jpg" alt="..." class="img-fluid">
                    <div class="status online"></div>
                  </div>
                  <div class="content">   <strong class="d-block">Nadia Halsey</strong><span class="d-block">lorem ipsum dolor sit amit</span><small class="date d-block">9:30am</small></div></a><a href="#" class="dropdown-item message d-flex align-items-center">
                  <div class="profile"><img src="img/avatar-2.jpg" alt="..." class="img-fluid">
                    <div class="status away"></div>
                  </div>
                  <div class="content">   <strong class="d-block">Peter Ramsy</strong><span class="d-block">lorem ipsum dolor sit amit</span><small class="date d-block">7:40am</small></div></a><a href="#" class="dropdown-item message d-flex align-items-center">
                  <div class="profile"><img src="img/avatar-1.jpg" alt="..." class="img-fluid">
                    <div class="status busy"></div>
                  </div>
                  <div class="content">   <strong class="d-block">Sam Kaheil</strong><span class="d-block">lorem ipsum dolor sit amit</span><small class="date d-block">6:55am</small></div></a><a href="#" class="dropdown-item message d-flex align-items-center">
                  <div class="profile"><img src="img/avatar-5.jpg" alt="..." class="img-fluid">
                    <div class="status offline"></div>
                  </div>
                  <div class="content">   <strong class="d-block">Sara Wood</strong><span class="d-block">lorem ipsum dolor sit amit</span><small class="date d-block">10:30pm</small></div></a><a href="#" class="dropdown-item text-center message"> <strong>See All Messages <i class="fa fa-angle-right"></i></strong></a></div>
            </div> -->



            <!-- Tasks-->
            <!-- <div class="list-inline-item dropdown"><a id="navbarDropdownMenuLink2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link tasks-toggle"><i class="icon-new-file"></i><span class="badge dashbg-3">9</span></a>
              <div aria-labelledby="navbarDropdownMenuLink2" class="dropdown-menu tasks-list"><a href="#" class="dropdown-item">
                  <div class="text d-flex justify-content-between"><strong>Task 1</strong><span>40% complete</span></div>
                  <div class="progress">
                    <div role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-1"></div>
                  </div></a><a href="#" class="dropdown-item">
                  <div class="text d-flex justify-content-between"><strong>Task 2</strong><span>20% complete</span></div>
                  <div class="progress">
                    <div role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-3"></div>
                  </div></a><a href="#" class="dropdown-item">
                  <div class="text d-flex justify-content-between"><strong>Task 3</strong><span>70% complete</span></div>
                  <div class="progress">
                    <div role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-2"></div>
                  </div></a><a href="#" class="dropdown-item">
                  <div class="text d-flex justify-content-between"><strong>Task 4</strong><span>30% complete</span></div>
                  <div class="progress">
                    <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-4"></div>
                  </div></a><a href="#" class="dropdown-item">
                  <div class="text d-flex justify-content-between"><strong>Task 5</strong><span>65% complete</span></div>
                  <div class="progress">
                    <div role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100" class="progress-bar dashbg-1"></div>
                  </div></a><a href="#" class="dropdown-item text-center"> <strong>See All Tasks <i class="fa fa-angle-right"></i></strong></a>
              </div>
            </div> -->
            <!-- Tasks end-->



            <!-- Megamenu-->
            <!-- <div class="list-inline-item dropdown menu-large"><a href="#" data-toggle="dropdown" class="nav-link">Mega <i class="fa fa-ellipsis-v"></i></a>
              <div class="dropdown-menu megamenu">
                <div class="row">
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                  <div class="col-lg-3 col-md-6"><strong class="text-uppercase">Elements Heading</strong>
                    <ul class="list-unstyled mb-3">
                      <li><a href="#">Lorem ipsum dolor</a></li>
                      <li><a href="#">Sed ut perspiciatis</a></li>
                      <li><a href="#">Voluptatum deleniti</a></li>
                      <li><a href="#">At vero eos</a></li>
                      <li><a href="#">Consectetur adipiscing</a></li>
                      <li><a href="#">Duis aute irure</a></li>
                      <li><a href="#">Necessitatibus saepe</a></li>
                      <li><a href="#">Maiores alias</a></li>
                    </ul>
                  </div>
                </div>
                <div class="row megamenu-buttons text-center">
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-1"><i class="fa fa-clock-o"></i><strong>Demo 1</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-2"><i class="fa fa-clock-o"></i><strong>Demo 2</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-3"><i class="fa fa-clock-o"></i><strong>Demo 3</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link dashbg-4"><i class="fa fa-clock-o"></i><strong>Demo 4</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link bg-danger"><i class="fa fa-clock-o"></i><strong>Demo 5</strong></a></div>
                  <div class="col-lg-2 col-md-4"><a href="#" class="d-block megamenu-button-link bg-info"><i class="fa fa-clock-o"></i><strong>Demo 6</strong></a></div>
                </div>
              </div>
            </div> -->
            <!-- Megamenu end     -->



            <!-- Languages dropdown    -->
            <!-- <div class="list-inline-item dropdown"><a id="languages" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link language dropdown-toggle"><img src="img/flags/16/GB.png" alt="English"><span class="d-none d-sm-inline-block">English</span></a>
              <div aria-labelledby="languages" class="dropdown-menu"><a rel="nofollow" href="#" class="dropdown-item"> <img src="img/flags/16/DE.png" alt="English" class="mr-2"><span>German</span></a><a rel="nofollow" href="#" class="dropdown-item"> <img src="img/flags/16/FR.png" alt="English" class="mr-2"><span>French  </span></a></div>
            </div> -->
            <!-- LANGUAGE END -->


            <!-- Log out -->
            <div class="list-inline-item logout">
              <a id="logout" href="index.php?logout=logout" class="nav-link"> 
                <span class="d-none d-sm-inline">Logout </span><i class="icon-logout"></i>
              </a>
            </div>

          </div>
        </div>
      </nav>
    </header>