<?php include('inc/header.php'); ?>
    <div class="d-flex align-items-stretch">
      <!-- Sidebar Navigation-->
      <?php include('inc/sidebar.php'); ?>
      <!-- Sidebar Navigation end-->
      <div class="page-content">

        <div class="page-header">
          <div class="container-fluid">
            <h2 class="h5 no-margin-bottom">
              <span>Dashboard</span>
            </h2>
          </div>
        </div>
        <?php echo $msg; //include('inc/errors.php'); ?>
        <?php if(isset($_GET['page']) && $_GET['page'] == 'consignee'){ ?>

        <!-- add consignee & CNF -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Consignee</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addConsignee">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addConsignee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Consignee info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=consignee">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Consignee</label>
                                  <input type="text" name="consigneeName" class="form-control" placeholder="Consignee Name">
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Bin Number</label>
                                  <input type="text" name="binnumber" class="form-control" placeholder="Bin Number">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addConsignee" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Bin</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allConsignee('editBtn'); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>


          <?php
            $run3 = mysqli_query($db, "SELECT * FROM bins WHERE type = 'IMPORTER' ");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id']; $name = $row3['name'];  $binnumber = $row3['bin'];
          ?>
          <!-- Consignee Edit Modal -->
          <div class="modal fade" id="<?php echo"editConsignee".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Consignee info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=consignee">
                  <input type="hidden" name="consigneeId" value="<?php echo $id; ?>">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Consignee</label>
                        <input type="text" name="consigneeName" value="<?php echo $name; ?>" class="form-control" placeholder="Consignee Name">
                      </div>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Bin Number</label>
                        <input type="text" name="binnumber" value="<?php echo $binnumber; ?>" class="form-control" placeholder="Bin Number">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editConsignee" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>




        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'stevedore'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Stevedore</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addStevedore">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addStevedore" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Stevedore info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=stevedore">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Name</label>
                                  <input type="text" name="stevedoreName" class="form-control" placeholder="Stevedore Name">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Email</label>
                                  <input type="text" name="email" class="form-control" placeholder="Stevedore Email">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addStevedore" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Email</th>
                          <th scope="col">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allStevedore(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM stevedore");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $name = $row3['name'];
              $email = $row3['email'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editStevedore".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Stevedore info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=stevedore">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="hidden" name="stevedoreId" value="<?php echo $id; ?>">
                        <input type="text" name="stevedoreName" value="<?php echo $name; ?>" class="form-control" placeholder="Stevedore Name">
                      </div>
                  </div>
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="email" value="<?php echo $email; ?>" class="form-control" placeholder="Stevedore Email">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editStevedore" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>























        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'agents'){ ?>
        <!-- agent -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Agents</strong>
                    
                    <div id="toolbar" class="select" style="width: 30%; margin-left: 120px; margin-top: -35px; display: none;">
                      <select class="form-control">
                        <option value="">Export Basic</option>
                        <option value="all">Export All</option>
                        <option value="selected">Export Selected</option>
                      </select>
                    </div>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addAgent">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addAgent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Agent info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=agents">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Company</label>
                                  <input type="text" name="company_name" class="form-control" placeholder="Company Name">
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Agent</label>
                                  <input type="text" name="contact_person" class="form-control" placeholder="Agent Name">
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact-1</label>
                                  <input type="text" name="contact_1" class="form-control" placeholder="Contact-1">
                                </div>

                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact-2</label>
                                  <input type="text" name="contact_2" class="form-control" placeholder="Contact-2">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addAgent" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark" data-show-export="true" data-show-columns="true">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Company</th>
                          <th scope="col">Name</th>
                          <th scope="col">Contact-1</th>
                          <th scope="col">Contact-2</th>
                          <th scope="col">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allAgent(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM agent");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $company_name = $row3['company_name']; 
              $contact_person = $row3['contact_person'];
              $contact_1 = $row3['contact_1'];
              $contact_2 = $row3['contact_2'];
          ?>
          <!-- Agent Edit Modal -->
          <div class="modal fade" id="<?php echo"editAgent".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Agent info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=agents">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Company</label>
                        <input type="hidden" name="agentId" value="<?php echo $id; ?>">
                        <input type="text" name="company_name" value="<?php echo $company_name; ?>" class="form-control" placeholder="Company Name">
                      </div>


                      <div class="form-group">
                        <label for="exampleInputEmail1">Agent</label>
                        <input type="text" name="contact_person" value="<?php echo $contact_person; ?>" class="form-control" placeholder="Agent Name">
                      </div>


                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact-1</label>
                        <input type="text" name="contact_1" value="<?php echo $contact_1; ?>" class="form-control" placeholder="Contact-1">
                      </div>


                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact-2</label>
                        <input type="text" name="contact_2" value="<?php echo $contact_2; ?>" class="form-control" placeholder="Contact-2">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editAgent" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>

          
        </section>
















      <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'cnf'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All CNF</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addCnf">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addCnf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add CNF info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=cnf">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">CNF</label>
                                  <input type="text" name="cnfName" class="form-control" placeholder="CNF Name">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Email</label>
                                  <input type="email" name="cnfEmail" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addCnf" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Email</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allCnf(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM cnf");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $name = $row3['name'];
              $email = $row3['email'];
          ?>
          <!-- CNF Edit Modal -->
          <div class="modal fade" id="<?php echo"editCnf".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit CNF info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=cnf">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">CNF</label>
                        <input type="hidden" name="cnfId" value="<?php echo $id; ?>">
                        <input type="text" name="cnfName" value="<?php echo $name; ?>" class="form-control" placeholder="CNF Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="cnfEmail" value="<?php echo $email; ?>" class="form-control" placeholder="Email">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editCnf" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>



        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'surveyors'){ ?>
        <!-- stevedore -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>Surveyors</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addSurveyor">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addSurveyor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Surveyor info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=surveyors">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Name</label>
                                  <input type="text" name="surveyor_name" class="form-control" >
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact 1</label>
                                  <input type="text" name="contact_1" class="form-control" >
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Contact 2</label>
                                  <input type="text" name="contact_2" class="form-control" >
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addSurveyor" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Surveyor Name</th>
                          <th scope="col">Contact 1</th>
                          <th scope="col">Contact 2</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php allSurveyors(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run = mysqli_query($db, "SELECT * FROM surveyors");
            while ($row = mysqli_fetch_assoc($run)) {
              $id = $row['id'];
              $surveyor_name = $row['surveyor_name'];
              $contact_1 = $row['contact_1'];
              $contact_2 = $row['contact_2'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editSurveyor".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Stevedore info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=surveyors">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Surveyor Name</label>
                        <input type="hidden" name="surveyorId" value="<?php echo $id; ?>">
                        <input type="text" name="surveyor_name" value="<?php echo $surveyor_name; ?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact 1</label>
                        <input type="text" name="contact_1" value="<?php echo $contact_1; ?>" class="form-control" >
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact 2</label>
                        <input type="text" name="contact_2" value="<?php echo $contact_2; ?>" class="form-control" >
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editSurveyor" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>




        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'others'){ ?>
        <!-- others -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-6">
                <div class="block">

                  <div class="title">
                    <strong>OTHERS</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addConsignee">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addConsignee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add X Representatie info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=consignee">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">X Representatie</label>
                                  <input type="text" name="consigneeName" class="form-control" placeholder="Consignee Name">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addConsignee" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allConsignee('editBtn'); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>


              <!-- add cnf -->
              <div class="col-lg-6">
                <div class="block">

                  <div class="title">
                    <strong>All Captain</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addCnf">
                      +ADD
                    </a>
                    <?php// include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addCnf" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add CNF info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=consignee">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">CNF</label>
                                  <input type="text" name="cnfName" class="form-control" placeholder="CNF Name">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addCnf" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col" style="text-align: center;">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allCnf("editBtn"); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>
        </section>



        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'loadport'){ ?>
        <!-- loadport -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Load Ports</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addLoadport">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addLoadport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Loadport info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=loadport">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Loadport</label>
                                  <input type="text" name="port_name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Country Code</label>
                                  <input type="text" name="port_code" class="form-control" required="">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addLoadport" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Port Name</th>
                          <th scope="col">Port Code</th>
                          <th scope="col">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allLoadport(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM loadport");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $port_name = $row3['port_name'];
              $port_code = $row3['port_code'];
          ?>
          <!-- Loadport Edit Modal -->
          <div class="modal fade" id="<?php echo"editLoadport".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Loadport info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=loadport">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Name</label>
                        <input type="hidden" name="loadportId" value="<?php echo $id; ?>">
                        <input type="text" name="port_name" value="<?php echo $port_name; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Code</label>
                        <input type="text" name="port_code" value="<?php echo $port_code; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editLoadport" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>






        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'nationality'){ ?>
        <!-- nationality -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Nationalities</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addNationality">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addNationality" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Nationality info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=nationality">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Nationality</label>
                                  <input type="text" name="port_name" class="form-control" required="">
                                </div>
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Country Code</label>
                                  <input type="text" name="port_code" class="form-control" required="">
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addNationality" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Port Name</th>
                          <th scope="col">Port Code</th>
                          <th scope="col">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allNationality(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM nationality");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id'];
              $port_name = $row3['port_name'];
              $port_code = $row3['port_code'];
          ?>
          <!-- Loadport Edit Modal -->
          <div class="modal fade" id="<?php echo"editNationality".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Nationality info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=nationality">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Name</label>
                        <input type="hidden" name="nationalityId" value="<?php echo $id; ?>">
                        <input type="text" name="port_name" value="<?php echo $port_name; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Port Code</label>
                        <input type="text" name="port_code" value="<?php echo $port_code; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editNationality" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>



        <?php } elseif(isset($_GET['page']) && $_GET['page'] == 'surveycompany'){ ?>
        <!-- surveycompany -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong>All Load Ports</strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addSurveycompany">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addSurveycompany" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Survey Company Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?page=surveycompany">
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="exampleInputEmail1">Company Name</label>
                                  <input type="text" name="company_name" class="form-control" required="">
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="text" name="contact_person" class="form-control">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Office Num</label>
                                    <input type="text" name="contact_number" class="form-control">
                                  </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addSurveycompany" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark table-responsive-sm">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Company Name</th>
                          <th scope="col">Email</th>
                          <th scope="col" style="width: 20%">Office Number</th>
                          <th scope="col" style="width: 20%">Actions</th>
                          <!-- <th scope="col">Handle</th> -->
                        </tr>
                      </thead>
                      <tbody>
                        <?php allSurveycompany(); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run3 = mysqli_query($db, "SELECT * FROM surveycompany");
            while ($row3 = mysqli_fetch_assoc($run3)) {
              $id = $row3['id']; $company_name = $row3['company_name']; 
              $contact_person = $row3['email']; $contact_number = $row3['officenum'];
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editSurveycompany".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Company info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?page=surveycompany">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Company Name</label>
                        <input type="hidden" name="surveycompanyId" value="<?php echo $id; ?>">
                        <input type="text" name="company_name" value="<?php echo $company_name; ?>" class="form-control" placeholder="Company Name">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="contact_person" value="<?php echo $contact_person; ?>" class="form-control" placeholder="Contact Person">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Office Number</label>
                        <input type="text" name="contact_number" value="<?php echo $contact_number; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editSurveycompany" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>


      <?php } elseif(isset($_GET['cnfview'])){ $cnfId = $_GET['cnfview']; ?>
        <!-- surveycompany -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong><?php echo allData('cnf', $cnfId, 'name'); ?></strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addCnfContacts">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addCnfContacts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Cnf Contact Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?cnfview=<?php echo $cnfId; ?>">
                            <input type="hidden" name="cnfcompanyId" value="<?php echo $cnfId; ?>">
                            <div class="modal-body">
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" required="">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" required="">
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">Contact Two</label>
                                    <input type="text" name="contact_2" class="form-control">
                                  </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addCnfContacts" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Contact Person</th>
                          <th scope="col">Contact</th>
                          <th scope="col">Contact 2</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php cnfContacts($cnfId); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run = mysqli_query($db, "SELECT * FROM cnf_contacts");
            while ($row = mysqli_fetch_assoc($run)) {
              $id = $row['id']; $name = $row['name']; $contact_2 = $row['contact_2'];
              $contact = $row['contact'];
              $company = $row['company']; $company_name = allData('cnf', $company, 'name');
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editCnfContact".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Contact info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?cnfview=<?php echo $_GET['cnfview']; ?>">
                  <input type="hidden" name="rowId" value="<?php echo $id; ?>">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Person</label>
                        <input type="text" name="contact_person" value="<?php echo $name; ?>" class="form-control" placeholder="Contact Person">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="text" name="contact_2" value="<?php echo $contact_2; ?>" class="form-control" placeholder="Email">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Number</label>
                        <input type="text" name="contact_number" value="<?php echo $contact; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editCnfContact" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>




        <?php } elseif(isset($_GET['consigneeview'])){ $consigneeId = $_GET['consigneeview']; ?>
        <!-- consignee contacts -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong><?php echo allData('consignee', $consigneeId, 'name'); ?></strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addConsigneeContacts">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addConsigneeContacts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Consignee Contact Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?consigneeview=<?php echo $consigneeId; ?>">
                            <input type="hidden" name="consigneecompanyId" value="<?php echo $consigneeId; ?>">
                            <div class="modal-body">
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" required="">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" required="">
                                  </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addConsigneeContacts" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Contact Person</th>
                          <th scope="col">Contact Number</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php consigneeContacts($consigneeId); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run = mysqli_query($db, "SELECT * FROM consignee_contacts"); $num = 0;
            while ($row = mysqli_fetch_assoc($run)) {
              $id = $row['id']; $name = $row['name']; $contact = $row['contact']; $num++;
              $company = $row['company']; $company_name = allData('consignee', $company, 'name');
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editConsigneeContact".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Contact info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?consigneeview=<?php echo $consigneeId; ?>">
                  <input type="hidden" name="rowId" value="<?php echo $id; ?>">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Person</label>
                        <input type="text" name="contact_person" value="<?php echo $name; ?>" class="form-control" placeholder="Contact Person">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Number</label>
                        <input type="text" name="contact_number" value="<?php echo $contact; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editConsigneeContact" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>



        <?php } elseif(isset($_GET['stevedoreview'])){ $stevedoreId = $_GET['stevedoreview']; ?>
        <!-- consignee contacts -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">

              <div class="col-lg-12">
                <div class="block">

                  <div class="title">
                    <strong><?php echo allData('stevedore', $stevedoreId, 'name'); ?></strong>
                    <!-- modal add -->
                    <a href="#" class="btn btn-success" style="float: right;" data-toggle="modal" data-target="#addStevedoreContacts">
                      +ADD
                    </a>
                    <?php include('inc/errors.php'); ?>

                    <!-- Modal -->
                    <div class="modal fade" id="addStevedoreContacts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Stevedore Contact Info</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form method="post" action="3rd_parties.php?stevedoreview=<?php echo $stevedoreId; ?>">
                            <input type="hidden" name="stevedorecompanyId" value="<?php echo $stevedoreId; ?>">
                            <div class="modal-body">
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control" required="">
                                  </div>
                                  <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Contact Number</label>
                                    <input type="text" name="contact_number" class="form-control" required="">
                                  </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="submit" name="addStevedoreContacts" class="btn btn-primary">Submit</button>
                            </div>
                          </form>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Contact Person</th>
                          <th scope="col">Contact Number</th>
                          <th scope="col">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php stevedoreContacts($stevedoreId); ?>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>

            </div>
          </div>

          <?php
            $run = mysqli_query($db, "SELECT * FROM stevedore_contacts"); $num = 0;
            while ($row = mysqli_fetch_assoc($run)) {
              $id = $row['id']; $name = $row['name']; $contact = $row['contact']; $num++;
              $company = $row['company']; $company_name = allData('stevedore', $company, 'name');
          ?>
          <!-- Stevedore Edit Modal -->
          <div class="modal fade" id="<?php echo"editStevedoreContact".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Contact info</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>

                <form method="post" action="3rd_parties.php?stevedoreview=<?php echo $stevedoreId; ?>">
                  <input type="hidden" name="rowId" value="<?php echo $id; ?>">
                  <div class="modal-body">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Person</label>
                        <input type="text" name="contact_person" value="<?php echo $name; ?>" class="form-control" placeholder="Contact Person">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contact Number</label>
                        <input type="text" name="contact_number" value="<?php echo $contact; ?>" class="form-control" placeholder="Loadport Name">
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="editStevedoreContact" class="btn btn-primary">Submit</button>
                  </div>
                </form>

              </div>
            </div>
          </div>
          <?php } ?>
        </section>
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