    <!--start top header-->
	<header class="top-header">        
        <nav class="navbar navbar-expand gap-3">
        <div class="toggle-icon d-block d-lg-none">
          <i class="bi bi-list"></i>
        </div>
        <form class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
            <?php
            if(isset($isLocal)){
              ?><script>console.log("Full URL: <?php echo $_SERVER['REQUEST_URI']; ?>");</script><?php
            }
              $selectHTML = generateTableSelect($conn);
              echo $selectHTML;
            ?>
        </form>


        <script>
           $(document).ready(function() {
            var notificationsList = $('#header-notifications-list');
            var totalUnreadBadge = $('#notify-badge');
            var modal = $('#notificationModal');
            var modalBody = modal.find('.modal-body');
            var loadingIndicator = $('#loading-indicator');
            // Store the timestamp of the latest notification
            var latestTimestamp = '';
            var markAllReadButton = $('#mark-all-read');
            
            // Function to fetch and display notifications
            function fetchNotifications(isInitialLoad) {
                var data = {};
                if (isInitialLoad) {
                    // If initial load, no need to send a timestamp
                    data.offset = notificationsList.find('.notification-item').length;
                } else {
                    // Fetch new notifications only
                    var mostRecentNotification = notificationsList.find('.notification-item:first-child');
                    latestTimestamp = mostRecentNotification.data('created-at') || '';
                    data.latest_timestamp = latestTimestamp;
                }

                $.ajax({
                    url: '<?php echo $url;?>tables/fetch_notifications.php',
                    method: 'GET',
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        // Update the UI with the fetched notifications
                        updateNotificationUI(data, isInitialLoad);
                    }
                });
            }
            // Function to update the notification UI
        function updateNotificationUI(data) {
            // Update the badge count for total unread notifications
            if (totalUnreadBadge) {
                totalUnreadBadge.text(data.totalUnreadCount);
            }

            if (notificationsList) {
              loadingIndicator.hide();
                if (data.notifications.length > 0) {
                    // Get the timestamp of the latest notification in the list
                    var currentLatestTimestamp = notificationsList.find('.notification-item:first-child').data('created-at');

                    // Loop through new notifications and append them to the list
                    $.each(data.notifications, function(index, notification) {
                        var existingNotification = notificationsList.find('.notification-item[data-notification-id="' + notification.notification_id + '"]');
                        if (existingNotification.length === 0) {
                            // Use the notification properties to build the HTML structure
                            // var iconClass = notification.unread ? 'bi-info-circle-fill' : 'bi-info-circle';
                            var unreadClass = notification.unread ? 'fw-bold text-primary' : '';
                            var markasread = notification.unread ? 'Mark as read' : '';
                            let msg = notification.message;
                            let img = notification.img;
                            let notified = notification.notified;
                            if(notified == 0){
                              info_noti(msg,"<?php echo $url;?>assets/images/avatars/" + img);
                              markNotificationAsNotified(notification.notification_id)
                            }
                            if (msg.length > 35) {
                                msg = msg.substr(0, 35) + '...';
                            }

                            var notificationItem = $('<span class="dropdown-item notification-item cursor-pointer" data-notification-id="' + notification.notification_id + '" data-created-at="' + notification.created_at + '">' +
                                '<div class="d-flex align-items-center">' +
                                '<img style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;" class="notiimg" src="<?php echo $url;?>assets/images/avatars/' + img + '">' +
                                '<div class="ms-3 flex-grow-1">' +
                                '<h6 class="mb-0 dropdown-msg-user ' + unreadClass + '">' + msg + '</h6>' +
                                '<div class="d-flex justify-content-between">' +
                                '<small class="text-secondary">' + notification.created_at + '</small>' +
                                '<small class="text-secondary">' + markasread + '</small>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</span>');

                            // Attach a click event to mark the notification as read
                            notificationItem.on('click', function() {
                                showNotificationModal(notification.message, notification.created_at);
                                markNotificationAsRead(notification.notification_id);
                                notificationItem.attr('data-notification-id', notification.notification_id);
                            });

                            // Append or prepend the new notification item based on the timestamp
                            if (currentLatestTimestamp && notification.created_at > currentLatestTimestamp) {
                                // Push the new notifications to the top
                                notificationsList.find('.notification-item:first-child').before(notificationItem);
                            } else {
                                // Append the existing notifications
                                notificationsList.append(notificationItem);
                            }
                        }
                    });
                }
            }
        }

            // Function to mark a notification as read
            function markNotificationAsRead(notificationId) {
                $.ajax({
                    url: '<?php echo $url;?>tables/mark_notification_as_read.php',
                    method: 'POST',
                    data: { notification_id: notificationId },
                    success: function(response) {
                        // Mark the clicked notification as read locally
                        var clickedNotification = notificationsList.find('.notification-item[data-notification-id="' + notificationId + '"]');
                        clickedNotification.find('.dropdown-msg-user').removeClass('fw-bold text-primary');
                        clickedNotification.find('.text-secondary:last-child').text('Marked as read');
                    }
                });
            }
        // Function to mark a notification as read
        function markNotificationAsNotified(notificationId) {
                $.ajax({
                    url: '<?php echo $url;?>tables/mark_notification_as_notified.php',
                    method: 'POST',
                    data: { notification_id: notificationId },
                    success: function(response) {
                        // Mark the clicked notification as read locally
                        
                    }
                });
            }
            // Function to fetch more notifications when scrolling down
        function fetchMoreNotifications() {
            // Check if the user has manually scrolled to the bottom
            if (notificationsList.length && notificationsList[0].scrollHeight - notificationsList.scrollTop() <= notificationsList.outerHeight()) {
                // Calculate the new offset based on the current number of notifications
                loadingIndicator.show();
                var offset = notificationsList.find('.notification-item').length;
                fetchNotifications(offset);
            }
        }

            // Function to show the full notification in a modal
            function showNotificationModal(message, createdAt) {
                modalBody.html('<p><strong>Message:</strong> ' + message + '</p>' +
                                '<p><strong>Created At:</strong> ' + createdAt + '</p>');
                modal.modal('show');
            }

            // Function to fetch more notifications when scrolling down
            function fetchMoreNotifications() {
                if (notificationsList.length && notificationsList[0].scrollHeight - notificationsList.scrollTop() <= notificationsList.outerHeight()) {
                    // Show loading spinner
                    loadingIndicator.show();

                    // User has scrolled to the bottom, fetch more notifications
                    // Calculate the new offset based on the current number of notifications
                    var offset = notificationsList.find('.notification-item').length;
                    fetchNotifications(offset);
                }
            }

            // Function to mark all notifications as read
            function markAllNotificationsAsRead() {
                $.ajax({
                    url: '<?php echo $url;?>tables/mark_all_notifications_as_read.php',
                    method: 'POST',
                    success: function(response) {
                        // Fetch updated notifications after marking all as read
                        fetchNotifications(true);
                    }
                });
            }

            // Attach click event to "Mark All as Read" button
            markAllReadButton.on('click', function() {
                markAllNotificationsAsRead();
            });

            // Attach the fetchMoreNotifications function to the scroll event
            notificationsList.on('scroll', function() {
                // Trigger pagination only if we're at the bottom and not currently fetching new notifications
                if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
                    loadingIndicator.show();
                    fetchNotifications(true);
                }
            });

            // Fetch notifications initially
            fetchNotifications(true);

            // Set interval to periodically fetch new notifications (e.g., every 10 seconds)
            setInterval(function() { fetchNotifications(false) }, 10000); // 10000 milliseconds = 10 seconds
        });
        </script>
        <div class="top-navbar-right ms-auto">
              <ul class="navbar-nav align-items-center">
              <!DOCTYPE html>


  <div class="pin btn-primary" onclick="toggleDiv()"><i class="lni lni-alarm-clock"></i></div>
  <div class="hidden-pin">
    <div style="padding: 20px;">
      <p id="liveTime"><?php echo date('d-m-Y H:i:s'); ?></p>
    </div>
  </div>

  <script>
    // JavaScript to handle the opening and closing of the hidden div
    let isOpen = false;
    const hiddenDiv = document.querySelector('.hidden-pin');

    function toggleDiv() {
      if (isOpen) {
        updateServerTime();
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
             <li class="nav-item dropdown dropdown-large">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                  <div class="notifications">
                     <span id="notify-badge" class="notify-badge">0</span>
                    <i class="bi bi-bell-fill"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end p-0">
                  <div class="p-2 border-bottom m-2 d-flex justify-content-between">
                      <h5 class="h5 mb-0">Notifications</h5>
                      <span id="mark-all-read" class="cursor-pointer text-right" title="Mark All as Read"><i class="fadeIn animated bx bx-envelope-open"></i></span>
                  </div>
                  <div id="header-notifications-list" class="header-notifications-list p-2">
                    </div> 
                    <div id="loading-indicator" style=""></div>
                 <!-- <div class="p-2">
                   <div><hr class="dropdown-divider"></div>
                     <a class="dropdown-item" href="#">
                       <div class="text-center">View All Notifications</div>
                     </a>
                 </div> -->
                </div>
              </li>
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
							<small class="mb-0 dropdown-user-designation text-secondary"><?php echo isset($isLocal) ? $_SESSION['userlevel'] : '' ; ?> </small>
                            <small class="mb-0 dropdown-user-designation text-secondary"><?php echo isset($isLocal) ? $_SESSION['db'] : ''; ?> </small>
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
      <h6 class="mb-0">Theme Background</h6>
      <hr>
      <div class="switcher">

      <div class="image-container">
        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="image1">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/1.png" alt="theme background 1">
        </label>

        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="image2">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/2.png" alt="theme background 2">
        </label>

        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="image3">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/3.png" alt="theme background 3">
        </label>

        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="image4">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/4.png" alt="theme background 4">
        </label>

        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="image5">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/5.png" alt="theme background 5">
        </label>

        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="image6">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/6.png" alt="theme background 6">
        </label>
        <label class="image-label form-check form-check-inline">
          <input type="radio" name="BGOptions" value="none">
          <img style="max-width: 140px;border-radius: 20px;cursor:pointer" src="<?php echo $url; ?>assets/bg-themes/7.png" alt="theme background 7">
        </label>
        <!-- Add more image labels as needed -->
      </div>
      </div>
    </div>
  </div>
</div>
<!-- End switcher -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notification Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be dynamically added here -->
            </div>
        </div>
    </div>
</div>
