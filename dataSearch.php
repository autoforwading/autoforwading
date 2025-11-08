<?php include('inc/header.php'); ?>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">

        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <?php
                $pg_title = $_GET['page'];
                $page = "Blank";
                if($pg_title == "representative"){$page = "Representative Wise";}
              ?>
              <span><?php echo $page; ?></span>
            </h2>
          </div>
        </div>

        <?php if(isset($_GET['page']) && $_GET['page'] == 'representative'){ ?>
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php //echo $msg; //include('inc/errors.php'); ?>
                  <div class="title">
                    <?php echo $msg; //include('inc/errors.php'); ?>
                    <strong>Representative Wise Vessels</strong>

                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none;">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>

                    <!-- add vassel modal and btn -->
                    <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addVassel">+ ADD VASSEL</button>
                  </div>

                  <!-- Modal -->
                  <div class="modal fade" id="addVassel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLongTitle">Insert Vassen Info</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form method="post" action="index.php">
                          <div class="modal-body">

                                <div class="form-row">
                                  <div class="form-group col-md-2">
                                    <label for="exampleInputPassword1">Msl</label>
                                    <input type="text" name="msl_num" class="form-control" required placeholder="MSL NUM">
                                  </div>

                                  <div class="form-group col-md-6">
                                    <label for="exampleInputPassword1">Vessel</label>
                                    <input type="text" name="vessel_name" class="form-control" required placeholder="VASSEL NAME">
                                  </div>

                                  <div class="form-group col-md-4">
                                    <label for="exampleInputPassword1">Cargo Qty</label>
                                    <input type="text" name="cargo_quantity" class="form-control" required placeholder="QTY">
                                  </div>
                                </div><br/>

                                <div class="form-row">
                                  <div class="form-group col-md-3">
                                    <label for="inputState">Select Cargo</label>
                                    <select id="inputState" name="cargokey" class="form-control search" required>
                                      <option value="">--Select--</option>
                                      <?php selectOptions('cargokeys', 'name'); ?>
                                    </select>
                                  </div>
                                  <!-- <div class="form-group col-md-3">
                                    <label for="inputState">Select Vessel</label>
                                    <select id="inputState" name="vesselId" class="form-control search" required>
                                      <option value="">--Select--</option>
                                      <?php selectOptions('vessels', 'vessel_name'); ?>
                                    </select>
                                  </div> -->
                                  <div class="form-group col-md-9">
                                    <label for="inputState">Select Loadport</label>
                                    <select id="inputState" name="loadport" class="form-control search" required>
                                      <option value="">--Select--</option>
                                      <?php selectOptions('loadport', 'port_name'); ?>
                                    </select>
                                  </div>
                                  <!-- <div class="form-group col-md-3">
                                    <label for="inputState">Quantity</label>
                                    <input type="number" step="any" class="form-control" name="quantity" required>
                                  </div> -->
                                </div>
                                
                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <label for="inputState">Cargo Bl Name</label>
                                    <input type="text" class="form-control" name="cargo_bl_name" required>
                                  </div>
                                </div>
                            
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="addVassel" class="btn btn-primary">+ADD</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table 
                      id="example" 
                      class="table table-dark table-striped table-sm"
                      data-show-export="true"
                      data-show-columns="false"
                    >

                      <thead>
                        <tr role="row">
                          <th>id</th>
                          <th>Img</th>
                          <th>Name</th>
                          <th>Qty</th>
                          <th>From</th>
                          <th>To</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php cargoAndConsigneeWise(); ?>
                        <!-- <tr>
                          <th scope="raw">Shaikh brothers and poultry feed and something more</th>
                          <td>1</td>
                          <td>0</td>
                          <td>3</td>
                          <td>5</td>
                          <td>2</td>
                          <td>3</td>
                          <td>7</td>
                          <td>21</td>
                        </tr> -->
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'stevedore'){ ?>


        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">
                  <?php //echo $msg; //include('inc/errors.php'); ?>
                  <div class="title">
                    <?php echo $msg; //include('inc/errors.php'); ?>
                    <strong>Stevedore wise Vassel List of 2024</strong>

                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none; ">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>

                    <!-- add vassel modal and btn -->
                    <!-- <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addVassel">+ ADD VASSEL</button> -->
                    <!-- <a class="btn btn-success btn-sm" style="float: right;" href="add_vessel.php">+ ADD VASSEL</a> -->
                  </div>

                  <div class="table-responsive"> 
                    <table 
                      id="example" 
                      class="table table-dark table-border table-sm"
                      data-show-export="false"
                      data-show-columns="false"
                    >
                    

                    <!-- <div id="bar" class="select" style="border: 1px solid white; display: none;">
                      <select></select>
                    </div> -->

                      <thead>
                        <tr role="row">
                          <th>SL</th>
                          <th>Stevedore</th>
                          <th>MSL</th>
                          <th>VASSEL NAME</th>
                          <th>QTY</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          stevedorewise();
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>


        <?php } else{ ?>
        <?php } ?>
        
        <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
        <!-- <footer class="footer">
          <div class="footer__block block no-margin-bottom">
            <div class="container-fluid text-center">
              <p class="no-margin-bottom">2020 &copy; Multiport. Design by <a href="https://bootstrapious.com/p/bootstrap-4-dark-admin">Tafsin Sanjid Turan</a>.</p>
            </div>
          </div>
        </footer> -->
        <?php include('inc/footercredit.php'); ?>
      </div>
    </div>
    <?php include('inc/footer.php'); ?>