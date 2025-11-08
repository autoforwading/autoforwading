<?php include('inc/header.php'); ?>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">

        <!-- page start -->
        <?php if ($my['companyid'] != 0) { ?>
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <span>Dashboard </span> <button type="button" class="btn btn-primary btn-sm" style="float: right;" data-bs-toggle="modal" data-bs-target="#filterModal">Search</button>
            </h2>
          </div>
        </div>
        <?php// echo $msg; ?>
        <!-- Modal Filter -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <form method="post" action="index.php?view=query">
              <div class="modal-header">
                <!-- <h5 class="modal-title" id="exampleModalLabel">Search Vessels</h5> -->
                <button type="submit" name="filtervsl" class="btn btn-primary">Search</button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <!-- <form method="post" action="index.php?view=query"> -->
              <div class="modal-body">
                
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="inputEmail4">From</label>
                      <input id="fromDate" type="date" class="form-control" name="frm_date" >
                    </div>

                    <div class="form-group col-md-4">
                      <label for="inputEmail4">To</label>
                      <input id="toDate" type="date" class="form-control" name="to_date"  disabled required>
                    </div>

                    <!-- HERE REP -->
                    <div class="form-group col-md-4">
                      <label for="inputState">Cargo</label>
                      <select name="cargo[]" class="form-control selectpicker" multiple style="background: transparent;" data-live-search="true">
                        <?php selectOptions("cargokeys","name"); ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="inputState">Impoter</label>
                      <select name="importer[]" class="form-control selectpicker" multiple style="background: transparent;" data-live-search="true">
                        <?php
                          $run = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
                          while ($row = mysqli_fetch_assoc($run)) {
                            $id = $row['id']; $value = $row['name'];
                            echo"<option value=\"$id\">$value</option>";
                          }
                        ?>
                      </select>
                    </div>

                    <div class="form-group col-md-4">
                      <label for="inputState">Stevedore</label>
                      <select id="inputState" class="form-control search" name="stevedore">
                        <option value="">--SELECT--</option>
                        <?php selectOptions("stevedore","name"); ?>
                      </select>
                    </div>

                    <div class="form-group col-md-4">
                      <label for="inputState">Loadport</label>
                      <select name="loadport[]" class="form-control selectpicker" multiple style="background: transparent;" data-live-search="true">
                        <?php selectOptions("loadport","port_name"); ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-row">

                    <div class="form-group col-md-4">
                      <label for="inputState">Representative</label>
                      <select id="inputState" class="form-control search" name="representative">
                        <option value="">--SELECT--</option>
                        <?php
                          $run1 = mysqli_query($db, "SELECT * FROM users WHERE office_position = '6' OR office_position = '7' ");
                          while ($row1 = mysqli_fetch_assoc($run1)) {
                            $id = $row1['id']; $rep_name = $row1['name'];
                            echo"<option value=\"$id\">$rep_name</option>";
                          }
                        ?>
                      </select>
                    </div>

                    <div class="form-group col-md-4">
                      <label for="inputState">Cnf</label>
                      <select class="form-control search" name="cnf">
                        <option value="">--SELECT--</option>
                        <?php selectOptions("cnf","name"); ?>
                      </select>
                    </div>
                    <div class="form-group col-md-4">
                      <label for="inputState">Surveyor</label>
                      <select class="form-control search" name="surveyors">
                        <option value="">--SELECT--</option>
                        <?php selectOptions("surveyors","surveyor_name"); ?>
                      </select>
                    </div>

                  </div> 

                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="inputState">Surveycompany</label>
                      <select class="form-control search" name="surveycompanies">
                        <option value="">--SELECT--</option>
                        <?php selectOptions("surveycompany","company_name"); ?>
                      </select>
                    </div>

                    <div class="form-group col-md-4">
                      <label for="inputState">Vessels Opa</label>
                      <select class="form-control search" name="vsl_opa">
                        <option value="">--SELECT--</option>
                        <?php selectOptions("agent","company_name"); ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="days" value="1" disabled="">
                        <label class="form-check-label" for="flexCheckDefault">
                          Multiple Lightdues
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="has_grab" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Has Grab
                        </label>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="qurentine_visited" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Qurentine visited
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="custom_visited" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Custom Visited
                        </label>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="psc_visited" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Psc Visited
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="crew_change" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Crew Change
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kutubdia" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Anchor Kutubdia
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="outer" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Anchor Outer
                        </label>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="fender" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Fender Supply
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="fresh_water" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Fresh Water Supply
                        </label>
                      </div>
                    </div>

                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="piloting" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          Piloting
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="seventyeight" value="1" >
                        <label class="form-check-label" for="flexCheckDefault">
                          78 PERMISSION GRANTED
                        </label>
                      </div>
                    </div>
                  </div>

                  <div class="form-row">

                    <div class="form-group col-md-4">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="sscec" value="1">
                        <label class="form-check-label" for="flexCheckDefault">
                          SSCEC
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="egm" value="1" >
                        <label class="form-check-label" for="flexCheckDefault">
                          No EGM
                        </label>
                      </div>
                    </div>
                  </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="filtervsl" class="btn btn-primary">Search</button>
              </div>
              </form>
            </div>
          </div>
        </div>



        



        <!-- Modal switch -->
        <div class="modal fade" id="switchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Switch Vessels</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="post" action="index.php">
              <div class="modal-body">
                
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="inputState">Vessel One</label>
                    <select class="form-control search" name="firstvsl">
                      <option value="">--SELECT--</option>
                      <?php selectOptions("vessels","vessel_name"); ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputState">Vessel Two</label>
                    <select class="form-control search" name="secondvsl">
                      <option value="">--SELECT--</option>
                      <?php selectOptions("vessels","vessel_name"); ?>
                    </select>
                  </div>
                </div> 

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="switchvsl" class="btn btn-primary">Swap</button>
              </div>
              </form>
            </div>
          </div>
        </div>


        

        <!-- Modal -->
        <div class="modal fade" id="addVassel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Vessel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php 
                $companyid = $my['companyid'];
                $lastvsl = mysqli_query($db, "SELECT * FROM vessels WHERE companyid = '$companyid' ORDER BY id DESC LIMIT 1 "); 
                if (mysqli_num_rows($lastvsl) > 0) {
                  $rowlastvsl = mysqli_fetch_assoc($lastvsl); $lastmsl = $rowlastvsl['msl_num'];
                }else{$lastmsl = 0;}
                
              ?>
              <form method="post" action="index.php">
              <div class="modal-body">
                <div class="form-row">
                  <div class="form-group col-md-3">
                    <label for="inputState">Voyage</label>
                    <input type="text" class="form-control" name="msl_num" value="<?php echo $lastmsl+1; ?>">
                  </div>
                  <div class="form-group col-md-9">
                    <label for="inputState">Vessel Name</label>
                    <input type="text" class="form-control" name="vessel_name">
                  </div>
                </div> 
              </div>
              <div class="modal-footer">
                <button type="submit" name="addVassel" class="btn btn-primary">+ Add</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-writing-whiteboard"></i></div><strong><a href="index.php?view=upcoming">Upcoming</a></strong>
                    </div>
                    <div class="number dashtext-4">
                      <?php 
                        $mycompanyid = $my['companyid'];
                        echo rawcount("vessels", "(rcv_date IS NULL OR rcv_date = '') AND (sailing_date IS NULL OR sailing_date = '') AND companyid = '$mycompanyid' "); 
                        if (exist("vessels", "(rcv_date IS NULL OR rcv_date = '') AND (sailing_date IS NULL OR sailing_date = '') AND companyid = '$mycompanyid' ")) {
                          $q = round(rawcount("vessels", "(rcv_date IS NULL OR rcv_date = '') AND (sailing_date IS NULL OR sailing_date = '') AND companyid = '$mycompanyid' ")/(rawcount("vessels")/100));
                        }else{$q = 0;}
                      ?>
                    </div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: <?php echo $q; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-4"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-contract"></i></div><strong><a href="index.php?view=online">Online</a></strong>
                    </div>
                    <div class="number dashtext-2">
                      <?php 
                        echo rawcount("vessels", "rcv_date IS NOT NULL AND rcv_date <> '' AND (sailing_date IS NULL OR sailing_date = '') AND companyid = '$mycompanyid' "); 
                        if (exist("vessels", "rcv_date IS NOT NULL AND rcv_date <> '' AND (sailing_date IS NULL OR sailing_date = '') AND companyid = '$mycompanyid' ")) {
                          $q = round(rawcount("vessels", "rcv_date IS NOT NULL AND rcv_date <> '' AND (sailing_date IS NULL OR sailing_date = '') AND companyid = '$mycompanyid' ")/(rawcount("vessels")/100));
                          if($q > -1 && $q < 1){$q=1;}
                        }else{$q = 0;} 
                      ?>
                    </div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: <?php echo $q; ?>%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-2"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-paper-and-pencil"></i></div><strong><a href="index.php?view=completed">Completed</a></strong>
                    </div>
                    <div class="number dashtext-3">
                      <?php 
                        echo rawcount("vessels", "rcv_date IS NOT NULL AND rcv_date <> '' AND sailing_date IS NOT NULL AND sailing_date <> '' AND companyid = '$mycompanyid' "); 
                        if (exist("vessels", "rcv_date IS NOT NULL AND rcv_date <> '' AND sailing_date IS NOT NULL AND sailing_date <> '' AND companyid = '$mycompanyid' ")) {
                          $q = round(rawcount("vessels", "rcv_date IS NOT NULL AND rcv_date <> '' AND sailing_date IS NOT NULL AND sailing_date <> '' AND companyid = '$mycompanyid' ")/(rawcount("vessels")/100));
                        }else{$q = 0;}
                      ?>
                    </div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: <?php echo $q; ?>%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                  </div>
                </div>
              </div>

              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-user-1"></i></div><strong><a href="index.php?view=all">ALL</a></strong>
                    </div>
                    <div class="number dashtext-1">
                      <?php echo rawcount("vessels", "companyid = '$mycompanyid' "); ?>
                    </div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>



        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php //echo $msg; //include('inc/errors.php'); ?>
                  <div class="title">
                    <?php echo $msg; //include('inc/errors.php'); ?>
                    <strong>Vassel List</strong>

                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none;">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>

                    <!-- add vassel modal and btn -->
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addVassel">+ ADD VASSEL</button>
                    <!-- <a class="btn btn-success btn-sm" style="float: right;" href="add_vessel.php">+ ADD VASSEL</a> -->
                  </div>

                  <div class="table-responsive"> 
                    <table 
                      id="example" 
                      class="table table-dark table-striped table-sm"
                      data-show-export="false"
                      data-show-columns="false"
                    >
                    

                    <!-- <div id="bar" class="select" style="border: 1px solid white; display: none;">
                      <select></select>
                    </div> -->

                      <thead>
                        <tr role="row">
                          <th>Voy</th>
                          <th>VASSEL NAME</th>
                          <th>CARGO</th>
                          <th>QTY</th>
                          <th>REP</th>
                          <th>%</th>
                          <th>ARRIVAL</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          // $key = "all";
                          if (isset($_GET['view'])) {$key = $_GET['view']; }
                          else{$key = "default";}
                          if (isset($_GET['year'])) {$key = $_GET['year']; }
                          allVessels($key, $query); 
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>




        <!-- first two block line chart -->
        <section class="no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="title" style="text-align: center; padding-bottom: 20px;">
                <strong>Vassel List From 2018 To <?php echo date('Y') ?></strong>
              </div>

              <div class="col-lg-6">
                <div class="stats-2-block block d-flex">
                  <canvas id="yearwise" style="width:100%;max-width:700px"></canvas>
                </div>
              </div>
              <div class="col-lg-6">
                <canvas id="lineyearwise" style="width:100%;max-width:700px"></canvas>
              </div>
            </div>
          </div>
        </section>


        <!-- from 2018 to current year line chart -->
        <div class="title" style="text-align: center; padding-bottom: 20px;">
          <strong>Vassel List Year wise</strong>
        </div>


        <?php
          // This is to get statistics from 2018 to current year

          // $mesure to put 2 statics in one div.
          $mesure = 0;

          // this loop runs from current year to 2018
          for ($i=date('Y'); $i >= 2018; $i--) { 

            $start = ""; $end = ""; $mesure ++;
            if ($mesure == 1) {
              $start = "
                <section class=\"no-padding-bottom\">
                  <div class=\"container-fluid\">
                    <div class=\"row\">
              "; 
              $end = "";
            }
            if ($mesure == 2) {
              $mesure = 0; $start = ""; 
              $end = "
                    </div>
                  </div>
                </section>
              ";
            }
            echo $start."
              <div class=\"col-lg-6\">
                Total vessel: ".vslcountyr($i)." ON Year: <a href=\"index.php?view=$i\">$i</a>
                <div class=\"stats-2-block block d-flex\">
                  <canvas id=\"$i\" style=\"width:100%;max-width:700px\"></canvas>
                </div>
              </div>".$end;
          }
        ?>

        <?php }else{ ?>
        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <span>Dashboard </span>
            </h2>
          </div>
        </div>

        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">

            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <div class="title">
                    <?php echo $msg; ?>
                    <!-- <strong>Vassel List</strong> -->
                  </div>

                  <h3 style="text-align: center;">You Don't Have a Company, Create a New Company.</h3>
                  <form method="post" action="index.php" style="text-align: center;">
                  
                    <div class="form-row">
                      <div class="form-group col-md-3">
                        <label for="inputEmail4">Company Name.</label>
                        <input id="fromDate" type="text" class="form-control" name="companyname" placeholder="Company name" required>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="inputEmail4">Company Port.</label>
                        <select id="inputState" name="port" class="form-control search" required>
                          <option value="">--SELECT PORT--</option>
                          <?php selectOptions('loadport', 'port_name', 'all'); ?>
                        </select>
                      </div>
                      <div class="form-group col-md-3">
                        <label for="inputEmail4">Telephone</label>
                        <input id="fromDate" type="text" class="form-control" name="telephone" placeholder="Office Contact Number" >
                      </div>
                      <div class="form-group col-md-3">
                        <label for="inputEmail4">Package</label>
                        <select name="package" class="form-control">
                          <option value="monthly">Monthly</option>
                          <option value="vesselwise">Vessel Wise</option>
                        </select>
                      </div> 
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label for="inputEmail4">Address</label>
                        <textarea type="text" class="form-control" name="address" value="" rows="5" ></textarea>
                      </div>
                      
                    </div>
                    <button type="submit" name="createcompany" class="btn btn-success">Create</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php } ?>





















        <!-- page end -->
        <?php include('inc/footercredit.php'); ?>
      </div>
    </div>
    <?php
      $companyid = $my['companyid'];
      // get all vessels
      $runlinegraph = mysqli_query($db, "SELECT * FROM vessels WHERE companyid = '$companyid' ");
      // set months
      $months = array('01' => 'jan','02' => 'feb','03' => 'mar','04' => 'apr','05' => 'may','06' => 'jun','07' => 'jul','08' => 'aug','09' => 'sep','10' => 'oct','11' => 'nov','12' => 'dec');

      /*replicate part one*/
      // dynamic variable name
      $year = 2018; 
      while ($year <= date('Y')) {
        $totalyear = "total".$year; // means $totalyear = total2018
        $totalvsl = "vsl".$year; // means $totalvsl = vsl2018
        // Setting the values to zero
        $$totalyear = 0; $$totalvsl = array();
        foreach ($months as $key => $value) {
          $yrmonth = $value.$year; //means $yrmonth = jan2018
          // Setting the values to zero
          $$yrmonth = 0; //means $jan2018 = $total2018 = 0
        }
        $year++;
      }
      

      /*replicate part two*/
      while ($rowlinegraph = mysqli_fetch_assoc($runlinegraph)) { 
        $rcv_dateLinegraph = $rowlinegraph['rcv_date']; 
          $year = 2018;
        while ($year <= date('Y')) {
          $totalyear = "total".$year; // means $totalyear = total2018
        $totalvsl = "vsl".$year; // means $totalvsl = vsl2018

          if (date("Y", strtotime($rcv_dateLinegraph)) == $year){ // filter from 2022
            $$totalyear++; //total number of vessels in 2018 means $total2022++;
            // sort vessels month wise
            foreach ($months as $key => $value) {
                $yrmonth = $value.$year; //means $yrmonth = jan2018
                // Setting the values to zero
                  if(date("m",strtotime($rcv_dateLinegraph))==$key){$$yrmonth++;}
                  /*replicate part three*/
                  $$totalvsl[$value] = $$yrmonth;
              }
          }
          $year++;
        }  
      }

    /*replicate part four*/
    // json encode
    $year = 2018;
    while ($year <= date('Y')) {
      $totalvsl = "vsl".$year; // means $totalvsl = vsl2018
      $$totalvsl = json_encode($$totalvsl);
      $year++;
    }

    ?>
    <script type="text/javascript">

      // function for linechart in index.php
      function linechartCustom(convertVals, giverYear){
        var y2022 = JSON.parse(convertVals); var year = giverYear;
        const xValsLine = ["0","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        const yValsLine = [0,y2022.jan,y2022.feb,y2022.mar,y2022.apr,y2022.may,y2022.jun,y2022.jul,y2022.aug,y2022.sep,y2022.oct,y2022.nov,y2022.dec];

        new Chart(year, {
          type: "line",
          data: {
            labels: xValsLine,
            datasets: [{
              fill: false,
              lineTension: 0,
              backgroundColor: "#22252A",
              borderColor: "#8A8D93",
              data: yValsLine
            }]
          },
          options: {
            legend: {display: false},
            scales: {
              yAxes: [{ticks: {min: 0, max:10}}],
            }
          }
        });
      }
      // charts on {index.php}


      // bar, yarwise from 2018 to 2023
      // var xValues = ["2018", "2019", "2020", "2021", "2022", "2023"];
      var xValues = []; var barColors = [];
      const d = new Date();
      for (let year = 2018; year <= d.getFullYear(); year++) {
        xValues.push(year); 
        barColors.push("#8A8D93");
      }

      // needs manual input
      var yValues = [<?php echo vslcountyr(2018); ?>, <?php echo vslcountyr(2019); ?>, <?php echo vslcountyr(2020); ?>, <?php echo vslcountyr(2021); ?>, <?php echo vslcountyr(2022); ?>, <?php echo vslcountyr(2023); ?>, <?php echo vslcountyr(2024); ?>, <?php echo vslcountyr(2025); ?>];
      // var barColors = ["#8A8D93", "#8A8D93","#8A8D93","#8A8D93","#8A8D93","#8A8D93","#8A8D93"];

      new Chart("yearwise", {
        type: "bar",
        data: {
          labels: xValues,
          datasets: [{
            backgroundColor: barColors,
            data: yValues
          }]
        },
        options: {
          legend: {display: false},
          title: {
            display: true,
            text: "Multiport Vessels Since 2018"
          }
        }
      });

      // line yearwise from 2018 to 2023
      var xVals = []; var barColors = [];
      const dline = new Date();
      for (let yearline = 2018; yearline <= dline.getFullYear(); yearline++) {
        xVals.push(yearline); 
        // barColors.push("#8A8D93");
      }

      // needs manual input
      // const xVals = ["2018","2019","2020","2021","2022","2023"];
      const yVals = [<?php echo vslcountyr(2018) ?>, <?php echo vslcountyr(2019) ?>, <?php echo vslcountyr(2020) ?>, <?php echo vslcountyr(2021) ?>, <?php echo vslcountyr(2022) ?>, <?php echo vslcountyr(2023) ?>, <?php echo vslcountyr(2024) ?>, <?php echo vslcountyr(2025) ?>];

      new Chart("lineyearwise", {
        type: "line",
        data: {
          labels: xVals,
          datasets: [{
            fill: false,
            lineTension: 0,
            backgroundColor: "#22252A",
            borderColor: "#8A8D93",
            data: yVals
          }]
        },
        options: {
          legend: {display: false},
          scales: {
            yAxes: [{ticks: {min: 0, max:60}}],
          }
        }
      });




      // needs manual input
      var cnvrt2025 = <?php echo json_encode($vsl2025); ?>;
      var cnvrt2024 = <?php echo json_encode($vsl2024); ?>;
      var cnvrt2023 = <?php echo json_encode($vsl2023); ?>;
      var cnvrt2022 = <?php echo json_encode($vsl2022); ?>;
      var cnvrt2021 = <?php echo json_encode($vsl2021); ?>;
      var cnvrt2020 = <?php echo json_encode($vsl2020); ?>;
      var cnvrt2019 = <?php echo json_encode($vsl2019); ?>;
      // Access the PHP value and store it in a JavaScript variable
      var cnvrt2018 = <?php echo json_encode($vsl2018); ?>;



      /*replicate part five*/
      // Parse the JSON string into a JavaScript object
      // Get the current year
      const currentYear = new Date().getFullYear();
      // Create variables dynamically for each year from 2018 to current year
      for (let year = 2018; year <= currentYear; year++) {
          // Use template literals to create variable names
          let variableName = `cnvrt${year}`; // like: cnvrt2018, cnvrt2019 etc
          // Create variables using the let keyword
          linechartCustom(window[variableName],year.toString());
          // linechartCustom(cnvrt2022,"2022");
      }
    </script>
    <?php include('inc/footer.php'); ?>