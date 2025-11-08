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

        <!-- add consignee & CNF -->
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="block">

                  <?php if(isset($_GET['page']) && $_GET['page'] == 'home'){ ?>
                  <div class="title">
                    <strong>All Companies</strong>
                  </div>
                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
                      <thead>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Company</th>
                          <th scope="col">Email</th>
                          <th scope="col">Pending</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php alltask(); ?>
                      </tbody>
                    </table>
                  </div>


                  <?php }elseif(isset($_GET['page']) && $_GET['page'] == 'company'){ ?>
                  <div class="title">
                    <strong>All Companies</strong>
                  </div>
                  <div class="table-responsive"> 
                    <table id="example" class="table table-striped table-dark">
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
                          $companyid = "";
                          if(isset($_GET['companyid']) && !empty($_GET['companyid'])) {
                            $companyid = $_GET['companyid'];
                          }
                          taskcompanywise($companyid); 
                        ?>
                      </tbody>
                    </table>
                  </div>
                  <?php } ?>
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
    <?php include('inc/footer.php'); ?>