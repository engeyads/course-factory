    <!--start top header-->
	<header class="top-header">        
        <nav class="navbar navbar-expand gap-3">
        <div class="toggle-icon d-block d-lg-none">
          <i class="bi bi-list"></i>
        </div>
        <form class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <?php
              $selectHTML = generateTableSelect($conn);
              echo $selectHTML;
            ?>
        </form>
        <div class="top-navbar-right ms-auto">
              <ul class="navbar-nav align-items-center">
              <!DOCTYPE html>


  <div class="pin btn-primary" onclick="toggleDiv()"><i class="lni lni-alarm-clock"></i></div>
  <div class="hidden-pin">
    <div style="padding: 20px;">
      <p><?php echo date('d-m-Y H:i:s'); ?></p>
    </div>
  </div>

  <script>
    // JavaScript to handle the opening and closing of the hidden div
    let isOpen = false;
    const hiddenDiv = document.querySelector('.hidden-pin');

    function toggleDiv() {
      if (isOpen) {
        hiddenDiv.style.right = '-300px'; // Hide the div
      } else {
        hiddenDiv.style.right = '0'; // Show the div
      }
      isOpen = !isOpen;
    }

    document.addEventListener('click', function (event) {
      if (isOpen && event.target !== hiddenDiv && event.target !== document.querySelector('.pin')) {
        toggleDiv(); // Close the div if it's open and the user clicks outside of it
      }
    });
  </script>

                
              
              <!--<li class="nav-item dropdown dropdown-large">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                  <div class="projects">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                   <div class="row row-cols-3 gx-2">
                    <?php //if($_SESSION['userlevel'] > 7) { ?>
                      <div class="col">
                        <a href="<?php //echo $url; ?>categories/view">
                         <div class="apps p-2 radius-10 text-center">
                            <div class="apps-icon-box mb-1 text-white bg-gradient-purple">
                              <i class="bi bi-book-half"></i>
                            </div>
                            <p class="mb-0 apps-name">Categories</p>
                         </div>
                        </a>
                      </div>
                      
                    <div class="col">
                      <a href="<?php //echo $url; ?>cities/view">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-danger">
                          <i class="lni lni-map-marker"></i>
                         </div>
                         <p class="mb-0 apps-name">Cities</p>
                      </div>
                      </a>
                    </div>
                    
                    <div class="col">
                      <a href="<?php //echo $url; ?>courses/view">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-voilet">
                          <i class="lni lni-certificate"></i>
                         </div>
                         <p class="mb-0 apps-name">Courses</p>
                      </div>
                      </a>
                    </div>
                    <div class="col">
                      <a href="<?php //echo $url; ?>tags/view">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-branding">
                          <i class="lni lni-tag"></i>
                         </div>
                         <p class="mb-0 apps-name">Tags</p>
                      </div>
                      </a>
                    </div>
                    <div class="col">
                      <a href="<?php //echo $url; ?>keyword/view">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-desert">
                          <i class="lni lni-keyword-research"></i>
                         </div>
                         <p class="mb-0 apps-name">keyword</p>
                      </div>
                    </a>
                    </div>
                    <div class="col">
                      <a href="<?php //echo $url; ?>seo/view">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-amour">
                          <i class="lni lni-seo"></i>
                         </div>
                         <p class="mb-0 apps-name">SEO</p>
                        </div>
                      </a>
                    </div>
                    <?php //} ?>
                    <?php //if($_SESSION['userlevel'] > 9) { ?>
                      <div class="col">
                        <a href="<?php //echo $url; ?>users/view">
                        <div class="apps p-2 radius-10 text-center">
                           <div class="apps-icon-box mb-1 text-white bg-gradient-info">
                            <i class="bi bi-people-fill"></i>
                           </div>
                           <p class="mb-0 apps-name">Users</p>
                        </div>
                      </a>
                     </div>
                     <div class="col">
                      <a href="<?php //echo $url; ?>event/fixold">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-success">
                          <i class="lni lni-calendar"></i>
                         </div>
                         <p class="mb-0 apps-name">Fix Old Events</p>
                      </div>
                      </a>
                    </div>
                    <?php //} ?>
                    <div class="col">
                      <a href="<?php //echo $url; ?>profile/edit">
                      <div class="apps p-2 radius-10 text-center">
                         <div class="apps-icon-box mb-1 text-white bg-gradient-warning">
                          <i class="bi bi-person-circle"></i>
                         </div>
                         <p class="mb-0 apps-name">Password</p>
                       </div>
                      </a>
                    </div>
                   </div>
                   <!--end row
                </div>
              </li>-->
              <!--<li class="nav-item dropdown dropdown-large">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                  <div class="messages">
                     <span class="notify-badge">5</span> 
                    <i class="bi bi-chat-right-fill"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                  <div class="p-2 border-bottom m-2">
                      <h5 class="h5 mb-0">Messages</h5>
                  </div>
                 <!-- <div class="header-message-list p-2">
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                          <img src="<?php //echo $url; ?>assets/images/avatars/avatar-1.png" alt="" class="rounded-circle" width="50" height="50">
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">Amelio Joly <span class="msg-time float-end text-secondary">1 m</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">The standard chunk of lorem...</small>
                          </div>
                       </div>
                     </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                         <img src="<?php //echo $url; ?>assets/images/avatars/avatar-2.png" alt="" class="rounded-circle" width="50" height="50">
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Althea Cabardo <span class="msg-time float-end text-secondary">7 m</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Many desktop publishing</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                         <img src="<?php //echo $url; ?>assets/images/avatars/avatar-3.png" alt="" class="rounded-circle" width="50" height="50">
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Katherine Pechon <span class="msg-time float-end text-secondary">2 h</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Making this the first true</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                         <img src="<?php //echo $url; ?>assets/images/avatars/avatar-4.png" alt="" class="rounded-circle" width="50" height="50">
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Peter Costanzo <span class="msg-time float-end text-secondary">3 h</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">It was popularised in the 1960</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                         <img src="<?php //echo $url; ?>assets/images/avatars/avatar-5.png" alt="" class="rounded-circle" width="50" height="50">
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Thomas Wheeler <span class="msg-time float-end text-secondary">1 d</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">If you are going to use a passage</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                         <img src="<?php //echo $url; ?>assets/images/avatars/avatar-6.png" alt="" class="rounded-circle" width="50" height="50">
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Johnny Seitz <span class="msg-time float-end text-secondary">2 w</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">All the Lorem Ipsum generators</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                         <img src="<?php //echo $url; ?>assets/images/avatars/avatar-1.png" alt="" class="rounded-circle" width="50" height="50">
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Amelio Joly <span class="msg-time float-end text-secondary">1 m</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">The standard chunk of lorem...</small>
                         </div>
                      </div>
                    </a>
                   <a class="dropdown-item" href="#">
                     <div class="d-flex align-items-center">
                        <img src="<?php //echo $url; ?>assets/images/avatars/avatar-2.png" alt="" class="rounded-circle" width="50" height="50">
                        <div class="ms-3 flex-grow-1">
                          <h6 class="mb-0 dropdown-msg-user">Althea Cabardo <span class="msg-time float-end text-secondary">7 m</span></h6>
                          <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Many desktop publishing</small>
                        </div>
                     </div>
                   </a>
                   <a class="dropdown-item" href="#">
                     <div class="d-flex align-items-center">
                        <img src="<?php //echo $url; ?>assets/images/avatars/avatar-3.png" alt="" class="rounded-circle" width="50" height="50">
                        <div class="ms-3 flex-grow-1">
                          <h6 class="mb-0 dropdown-msg-user">Katherine Pechon <span class="msg-time float-end text-secondary">2 h</span></h6>
                          <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Making this the first true</small>
                        </div>
                     </div>
                   </a>
                </div> 
                <div class="p-2">
                  <div><hr class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">
                      <div class="text-center">View All Messages</div>
                    </a>
                </div>
               </div>
              </li>-->
              <!--<li class="nav-item dropdown dropdown-large">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                  <div class="notifications">
                     <span class="notify-badge">0</span>
                    <i class="bi bi-bell-fill"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                  <div class="p-2 border-bottom m-2">
                      <h5 class="h5 mb-0">Notifications</h5>
                  </div>
                  <!-- <div class="header-notifications-list p-2">
                      <a class="dropdown-item" href="#">
                        <div class="d-flex align-items-center">
                           <div class="notification-box bg-light-primary text-primary"><i class="bi bi-basket2-fill"></i></div>
                           <div class="ms-3 flex-grow-1">
                             <h6 class="mb-0 dropdown-msg-user">New Orders <span class="msg-time float-end text-secondary">1 m</span></h6>
                             <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">You have recived new orders</small>
                           </div>
                        </div>
                      </a>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-purple text-purple"><i class="bi bi-people-fill"></i></div>
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">New Customers <span class="msg-time float-end text-secondary">7 m</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">5 new user registered</small>
                          </div>
                       </div>
                     </a>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-success text-success"><i class="bi bi-file-earmark-bar-graph-fill"></i></div>
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">24 PDF File <span class="msg-time float-end text-secondary">2 h</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">The pdf files generated</small>
                          </div>
                       </div>
                     </a>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-orange text-orange"><i class="bi bi-collection-play-fill"></i></div>
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">Time Response  <span class="msg-time float-end text-secondary">3 h</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">5.1 min avarage time response</small>
                          </div>
                       </div>
                     </a>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-info text-info"><i class="bi bi-cursor-fill"></i></div>
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">New Product Approved  <span class="msg-time float-end text-secondary">1 d</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Your new product has approved</small>
                          </div>
                       </div>
                     </a>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-pink text-pink"><i class="bi bi-gift-fill"></i></div>
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">New Comments <span class="msg-time float-end text-secondary">2 w</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">New customer comments recived</small>
                          </div>
                       </div>
                     </a>
                     <a class="dropdown-item" href="#">
                       <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-warning text-warning"><i class="bi bi-droplet-fill"></i></div>
                          <div class="ms-3 flex-grow-1">
                            <h6 class="mb-0 dropdown-msg-user">New 24 authors<span class="msg-time float-end text-secondary">1 m</span></h6>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">24 new authors joined last week</small>
                          </div>
                       </div>
                     </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-primary text-primary"><i class="bi bi-mic-fill"></i></div>
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Your item is shipped <span class="msg-time float-end text-secondary">7 m</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Successfully shipped your item</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-success text-success"><i class="bi bi-lightbulb-fill"></i></div>
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">Defense Alerts <span class="msg-time float-end text-secondary">2 h</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">45% less alerts last 4 weeks</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-info text-info"><i class="bi bi-bookmark-heart-fill"></i></div>
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">4 New Sign Up <span class="msg-time float-end text-secondary">2 w</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">New 4 user registartions</small>
                         </div>
                      </div>
                    </a>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                        <div class="notification-box bg-light-bronze text-bronze"><i class="bi bi-briefcase-fill"></i></div>
                         <div class="ms-3 flex-grow-1">
                           <h6 class="mb-0 dropdown-msg-user">All Documents Uploaded <span class="msg-time float-end text-secondary">1 mo</span></h6>
                           <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">Sussessfully uploaded all files</small>
                         </div>
                      </div>
                    </a>
                 </div> 
                 <div class="p-2">
                   <div><hr class="dropdown-divider"></div>
                     <a class="dropdown-item" href="#">
                       <div class="text-center">View All Notifications</div>
                     </a>
                 </div>
                </div>
              </li>-->
              <?php

// Function to check if an image exists

?>
			  <li class="nav-item dropdown dropdown-user-setting">
				<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
				  <div class="user-setting d-flex align-items-center">
					<img src="<?php echo $userphoto; ?>" class="user-img" alt="">
				  </div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
				  <li>
					 <a class="dropdown-item" href="<?php echo $url; ?>profile/edit">
					   <div class="d-flex align-items-center">
						  <img src="<?php echo $userphoto; ?>" alt="" class="rounded-circle" width="54" height="54">
						  <div class="ms-3">
							<h6 class="mb-0 dropdown-user-name"><?php echo $_SESSION['username'] ; ?></h6>
							<small class="mb-0 dropdown-user-designation text-secondary"><?php echo $_SESSION['userlevel'] ; ?> </small>
                            <small class="mb-0 dropdown-user-designation text-secondary"><?php echo $_SESSION['db']; ?> </small>
						  </div>
					   </div>
					 </a>
				   </li>
				   <li><hr class="dropdown-divider"></li>
				   <li>
					  <a class="dropdown-item" href="<?php echo $url; ?>profile/edit">
						 <div class="d-flex align-items-center">
						   <div class=""><i class="bi bi-person-fill"></i></div>
						   <div class="ms-3"><span>Change Password</span></div>
						 </div>
					   </a>
					</li>
					<!-- <li>
					  <a class="dropdown-item" href="#">
						 <div class="d-flex align-items-center">
						   <div class=""><i class="bi bi-gear-fill"></i></div>
						   <div class="ms-3"><span>Setting</span></div>
						 </div>
					   </a>
					</li>
					<li>
					  <a class="dropdown-item" href="index2.html">
						 <div class="d-flex align-items-center">
						   <div class=""><i class="bi bi-speedometer"></i></div>
						   <div class="ms-3"><span>Dashboard</span></div>
						 </div>
					   </a>
					</li>
					<li>
					  <a class="dropdown-item" href="#">
						 <div class="d-flex align-items-center">
						   <div class=""><i class="bi bi-piggy-bank-fill"></i></div>
						   <div class="ms-3"><span>Earnings</span></div>
						 </div>
					   </a>
					</li>
					<li>
					  <a class="dropdown-item" href="#">
						 <div class="d-flex align-items-center">
						   <div class=""><i class="bi bi-cloud-arrow-down-fill"></i></div>
						   <div class="ms-3"><span>Downloads</span></div>
						 </div>
					   </a>
					</li> -->
					<li><hr class="dropdown-divider"></li>
					<li>
					  <a class="dropdown-item" href="<?php echo $url; ?>logout.php">
						 <div class="d-flex align-items-center">
						   <div class=""><i class="bi bi-lock-fill"></i></div>
						   <div class="ms-3"><span>Logout</span></div>
						 </div>
					   </a>
					</li>
				</ul>
			  </li>
              </ul>
              </div>
        </nav>
      </header>
       <!--end top header-->
       <!-- Start switcher -->
<div class="switcher-body">
  <button class="btn btn-primary btn-switcher shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
    <i class="bi bi-paint-bucket me-0"></i>
  </button>
  <div class="offcanvas offcanvas-end shadow border-start-0 p-2" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Theme Customizer</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
      <h6 class="mb-0">Theme Variation</h6>
      <hr>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="themeOptions" id="LightTheme" value="light-theme">
        <label class="form-check-label" for="LightTheme">Light</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="themeOptions" id="DarkTheme" value="dark-theme">
        <label class="form-check-label" for="DarkTheme">Dark</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="themeOptions" id="SemiDarkTheme" value="semi-dark">
        <label class="form-check-label" for="SemiDarkTheme">Semi Dark</label>
      </div>
      <hr>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="themeOptions" id="MinimalTheme" value="minimal-theme">
        <label class="form-check-label" for="MinimalTheme">Minimal Theme</label>
      </div>
      <hr/>
    </div>
  </div>
</div>
<!-- End switcher -->
