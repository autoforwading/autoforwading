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

    <section class="no-padding-top no-padding-bottom">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="block">

              <div class="title">
                <strong>All Remarks</strong>
                <!-- add vassel modal and btn -->
                <button class="btn btn-success btn-sm" style="float: right;" data-toggle="modal" data-target="#addRemarks">+ ADD</button>
                

                <!-- Modal -->
                <div class="modal fade" id="addRemarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Insert Remarks Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="post" action="remarks.php">
                        <div class="modal-body">
                          
                          <div class="form-group row">
                            <div class="col-sm-12">
                              
                              <label for="exampleInputPassword1">Insert / Write Remarks</label>
                              <!-- <input type="text" name="bank_name" class="form-control" required placeholder="BANK NAME"> -->
                              <textarea name="remarks" class="form-control" rows="3"></textarea>
                              
                            </div>
                          </div>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" name="addRemarks" class="btn btn-success">+ADD</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <div class="table-responsive"> 
                <table id="example" class="table table-dark table-striped table-sm">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Remarks</th>
                      <th>ACTIONS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php allRemarks(); ?>
                  </tbody>
                </table>
              </div>


            </div>
          </div>
        </div>
      </div>
    </section>

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


<?php
  $run3 = mysqli_query($db, "SELECT * FROM remarks");
  while ($row3 = mysqli_fetch_assoc($run3)) {
    $id = $row3['id']; $name = $row3['name'];
?>
<div class="modal fade" id="<?php echo"editRemarks".$id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Insert Remarks Info</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="remarks.php">
        <div class="modal-body">
          
          <div class="form-group row">
            <div class="col-sm-12">
                <label for="exampleInputPassword1">Remarks</label>
                <input type="hidden" name="remarksId" value="<?php echo $id; ?>">
                <!-- <input type="text" name="remarks" value="<?php echo $name; ?>" class="form-control" required placeholder="Remarks"> -->
                <textarea name="remarks" class="form-control" rows="3"><?php echo $name; ?></textarea>
                
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" name="editRemarks" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>
<?php include('inc/footer.php'); ?>