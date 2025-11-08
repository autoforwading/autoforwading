      <?php $id = $my['id']; ?>
      <nav id="sidebar">
        <!-- Sidebar Header-->
        <div class="sidebar-header d-flex align-items-center">
          <div class="avatar"><img src="img/userimg/<?php echo allData('users', $id, 'image'); ?>" alt="..." class="img-fluid rounded-circle"></div>
          <div class="title">
            <a href="profile.php?userid=<?php echo $id; ?>"><h1 class="h5"><?php echo allData('users', $id, 'name'); ?></h1></a>
            <p><?php echo allData('useraccess', $my['office_position'], 'designation'); ?></p>
            <p>TK: <?php echo formatIndianNumber($my['balance']); ?> /-</p>
          </div>
        </div>
        <?php
          $homecls = $thirdpartycls = $cnfcls = $consigneecls = $surveyorcls = $surveycompanycls = $stevedorecls = $agentcls = $otherscls = $cargokeycls = $loadportcls = $nationalitycls = $binnumberscls = $userscls = $taskcls = $userblnscls = $userctrlcls = $bincls = $crgokeyaprvcls = $ldprtaprovlcls = $natnltyaprovlcls = $access_ctrlcls = $surveyoraprvlcls = $cnfaprvlcls = $srvycmpnyaprvlcls = $stvdraprvlcls = $logchkcls = $settingcls = $profilecls = $comsettingcls = "";
          if(page() == "index"){$homecls = "active";}
          elseif(page()== "3rd_parties"){$thirdpartycls="active";
            if(isset($_GET['page'])&&$_GET['page']=="cnf"){$cnfcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="consignee"){$consigneecls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="surveyors"){$surveyorcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="surveycompany"){$surveycompanycls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="stevedore"){$stevedorecls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="loadport"){
              $otherscls=$loadportcls="active"; $thirdpartycls="";
            }elseif(isset($_GET['page'])&&$_GET['page']=="nationality"){
              $otherscls=$nationalitycls="active"; $thirdpartycls="";
            }elseif(isset($_GET['page'])&&$_GET['page']=="agents"){$agentcls="active";}
          }
          elseif (page()== "others_adds"){$otherscls="active";
            if(isset($_GET['page'])&&$_GET['page']=="cargoKeys"){$cargokeycls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="binNumbers"){$binnumberscls="active";$otherscls="";}
            elseif (isset($_GET['page'])&&$_GET['page']=="useraccess"){$access_ctrlcls="active";$otherscls="";}
          }
          elseif (page()== "users"){$userscls="active";}
          elseif (page()== "task"){$taskcls="active";}
          elseif (page()== "usercontrols"){
            $userctrlcls="active";
            if(isset($_GET['page'])&&$_GET['page']=="balance"){$userblnscls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="binapproval"){$bincls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="cargokeyapproval"){$crgokeyaprvcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="loadportapproval"){$ldprtaprovlcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="nationalityapproval"){$natnltyaprovlcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="cnfapproval"){$cnfaprvlcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="surveyorapproval"){$surveyoraprvlcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="surveycompanyapproval"){$srvycmpnyaprvlcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="stevedoreapproval"){$stvdraprvlcls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="exportlogs"){$logchkcls="active";}
          }
          elseif (page()== "profile"){
            $settingcls="active";
            if(isset($_GET['page'])&&$_GET['page']=="profile"||!isset($_GET['page'])){$profilecls="active";}
            elseif(isset($_GET['page'])&&$_GET['page']=="comsetting"){$comsettingcls="active";}
          }
        ?>
        <!-- Sidebar Navidation Menus-->
        <span class="heading">Main</span>

        <!-- show sidebar after creating company -->
        <?php if ($my['companyid'] != 0) { ?>
          <ul class="list-unstyled">
            <li class="<?php echo $homecls; ?>"><a href="index.php"> <i class="icon-home"></i>Home</a></li>

            <?php 
              if($my['email'] == "shukurs920@gmail.com" || $my['email'] == "skturan2405@gmail.com"){ 
                $comcount = $vslcount = $pending = 0; $sqlcom = "SELECT * FROM companies ";
                $runcom = mysqli_query($db, $sqlcom); while ($row = mysqli_fetch_assoc($runcom)) {
                  $companyid = $row['id']; 
                  $sqlvsl = "SELECT * FROM vessels WHERE workstatus = 'notdone' AND companyid = '$companyid' ";
                  $runvsl = mysqli_query($db, $sqlvsl); $pending = mysqli_num_rows($runvsl); if (!$pending) {continue;} $comcount++; $vslcount = $vslcount+$pending;
                }
            ?>
            <li class="<?php echo $taskcls; ?>"><a href="task.php?page=home"> <i class="icon-padnote"></i> Task (<?php echo $vslcount; ?>)</a></li>
            <?php } ?>

            <li class="<?php echo $thirdpartycls; ?>"><a href="#exampledropdownDropdown" aria-expanded="false" data-toggle="collapse"> 
              <i class="icon-windows"></i>3rd parties </a>
              <ul id="exampledropdownDropdown" class="collapse list-unstyled ">
                <li class="<?php echo $cnfcls; ?>">
                  <a href="3rd_parties.php?page=cnf">CNF</a>
                </li>
                <li class="<?php echo $consigneecls; ?>">
                  <a href="3rd_parties.php?page=consignee">CONSIGNEE</a>
                </li>
                <li class="<?php echo $surveyorcls; ?>">
                  <a href="3rd_parties.php?page=surveyors">SURVEYORS</a>
                </li>
                <li class="<?php echo $surveycompanycls; ?>">
                  <a href="3rd_parties.php?page=surveycompany">SURVEY COMPANY</a>
                </li>
                <li class="<?php echo $stevedorecls; ?>">
                  <a href="3rd_parties.php?page=stevedore">STEVEDORE</a>
                </li>
                <?php if($my['companyid'] == 1){ ?>
                <li class="<?php echo $agentcls; ?>">
                  <a href="3rd_parties.php?page=agents">AGENTS</a>
                </li>
                <?php } ?>
              </ul>
            </li>

            <li class="<?php echo $otherscls; ?>">
              <a href="#othersDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-padnote"></i>Others </a>
              <ul id="othersDropdown" class="collapse list-unstyled ">
                <li class="<?php echo $cargokeycls; ?>">
                  <a href="others_adds.php?page=cargoKeys">Cargo Keys</a>
                </li>
                <li class="<?php echo $loadportcls; ?>">
                  <a href="3rd_parties.php?page=loadport">Load Port</a>
                </li>
                <li class="<?php echo $nationalitycls; ?>">
                  <a href="3rd_parties.php?page=nationality">Nationality</a>
                </li>
                <!-- <li><a href="others_adds.php?page=test">Test Page</a></li> -->
              </ul>
            </li>
            <?php if(allData('useraccess',$my['office_position'],'bin_ctrl')){ ?>
            <li class="<?php echo $binnumberscls; ?>">
              <a href="others_adds.php?page=binNumbers"> <i class="icon-page"></i>Bin Numbers</a>
            </li>
            <?php } ?>
            <li class="<?php echo $userscls; ?>">
              <a href="users.php"> <i class="icon-user"></i>Users </a>
            </li>
            <?php if(allData('useraccess',$my['office_position'],'access_ctrl')){ ?>
            <li class="<?php echo $access_ctrlcls; ?>">
              <a href="others_adds.php?page=useraccess"> <i class="icon-settings-1"></i>Access Controls </a>
            </li>
            <?php } ?>


            <li class="<?php echo $profilecls; ?>">
              <a href="profile.php?page=profile&&userid=<?php echo $my['id']; ?>"> 
                <i class="icon-settings"></i>Settings 
              </a>
            </li>

            <!-- <li class="<?php echo $settingcls; ?>">
              <a href="#settingsDropdown" aria-expanded="false" data-toggle="collapse"> <i class="icon-settings"></i>Settings </a>
              <ul id="settingsDropdown" class="collapse list-unstyled ">
                <li class="<?php echo $profilecls; ?>">
                  <a href="profile.php?page=profile&&userid=<?php echo $my['id']; ?>">My Profile</a>
                </li>
                <li class="<?php echo $comsettingcls; ?>">
                  <a href="profile.php?page=comsetting">Company</a>
                </li>
              </ul>
            </li> -->






            <?php if($my['email'] == "skturan2405@gmail.com"){ ?>
            <?php
              $notify = 0;
              $countbin=mysqli_num_rows(mysqli_query($db,"SELECT * FROM bins WHERE status='unapproved' "));
              $cntcrgoky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM cargokeys WHERE status='unapproved' "));
              $ldprtky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM loadport WHERE status='unapproved' "));
              $ntltyky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM nationality WHERE status='unapproved' "));
              $cnfky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM cnf WHERE status='unapproved' ")); 
              $surky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM surveyors WHERE status='unapproved' ")); 
              $surcmky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM surveycompany WHERE status='unapproved' ")); 
              $stvdrky=mysqli_num_rows(mysqli_query($db,"SELECT * FROM stevedore WHERE status='unapproved' ")); 

              if($countbin){$notify++;} if($cntcrgoky){$notify++;} if($ldprtky){$notify++;} 
              if($ntltyky){$notify++;} if($cnfky){$notify++;} if($surky){$notify++;}
              if($surcmky){$notify++;} if($stvdrky){$notify++;}
            ?>
            <li class="<?php echo $userctrlcls; ?>">
              <a href="#usercontrols" aria-expanded="false" data-toggle="collapse"> <i class="icon-settings"></i>Controls <?php if($notify){echo " <span style=\"color: white;\">(".$notify.")</span>";} ?></a>
              <ul id="usercontrols" class="collapse list-unstyled ">
                <li class="<?php echo $userblnscls; ?>"><a href="usercontrols.php?page=balance">Balance</a></li>
                <li class="<?php echo $bincls; ?>">
                  <a href="usercontrols.php?page=binapproval">
                    Bin Approval
                    <?php if($countbin){echo " <span style=\"color: white;\">(".$countbin.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $crgokeyaprvcls; ?>">
                  <a href="usercontrols.php?page=cargokeyapproval">
                    Cargokey Approval
                    <?php if($cntcrgoky){echo " <span style=\"color: white;\">(".$cntcrgoky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $ldprtaprovlcls; ?>">
                  <a href="usercontrols.php?page=loadportapproval">
                    Loadport Approval
                    <?php if($ldprtky){echo " <span style=\"color: white;\">(".$ldprtky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $natnltyaprovlcls; ?>">
                  <a href="usercontrols.php?page=nationalityapproval">
                    Nationality Approval
                    <?php if($ntltyky){echo " <span style=\"color: white;\">(".$ntltyky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $cnfaprvlcls; ?>">
                  <a href="usercontrols.php?page=cnfapproval">
                    Cnf Approval
                    <?php if($cnfky){echo " <span style=\"color: white;\">(".$cnfky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $surveyoraprvlcls; ?>">
                  <a href="usercontrols.php?page=surveyorapproval">
                    Surveyor Approval
                    <?php if($surky){echo " <span style=\"color: white;\">(".$surky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $srvycmpnyaprvlcls; ?>">
                  <a href="usercontrols.php?page=surveycompanyapproval">
                    Surveycompany Approval
                    <?php if($surcmky){echo " <span style=\"color: white;\">(".$surcmky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $stvdraprvlcls; ?>">
                  <a href="usercontrols.php?page=stevedoreapproval">
                    Stevedore Approval
                    <?php if($stvdrky){echo " <span style=\"color: white;\">(".$stvdrky.")</span>";} ?>
                  </a>
                </li>
                <li class="<?php echo $logchkcls; ?>">
                  <a href="usercontrols.php?page=exportlogs">
                    Export Logs
                    <?php if($stvdrky){echo " <span style=\"color: white;\">(".$stvdrky.")</span>";} ?>
                  </a>
                </li>
              </ul>
            </li>
            <li><a href="databackups.php"> <i class="icon-paper-and-pencil"></i>Data Backups </a></li>
            <?php } ?>
          </ul>
        <?php } ?>
      </nav>