<?php if ($_SESSION['userlevel'] > 2 ) { 
  $adminajaxview = true;
  $folderName =  basename(__DIR__); 
    $path = dirname(__FILE__);
include $path.'/conf.php'; ?>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <div class="row">
    <div class="col-10">
        <h1>Fix Price</h1>
    </div>
    
</div>
<span id="fsd" class="btn btn-primary float-right justify-content-end" rel="noopener noreferrer">Fetch Sitemap Data</span>
<div class="row">
    <div class="col-10 d-inline-flex"></div>
    <div class="col-2 ">
        <!-- <a href="<?php //echo $url ; ?>event/fixdurations/auto" class="btn btn-primary float-right justify-content-end">Automatic <i class="lni lni-reload"></i></a> -->
    </div>
</div>

<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <div >
                <!-- Table -->
                <table id='empTable' class='table table-striped table-bordered dataTable no-footer display dataTable'>
                    <thead>
                        <tr>
                            <th style="width:50%">Id</th>
                            <th style="width:3%">Link</th>
                            <th style="width:13%">Updated At</th>
                            <th style="width:13%">Speed Test</th>
                            <th style="width:3%">Speed Date</th>
                            <th style="width:3%">Google Index</th>
                            <th style="width:3%">Indexing State</th>
                            <th style="width:3%">Verdict</th>
                            <th style="width:3%">Coverage State</th>
                            <th style="width:3%">Robots Txt State</th>
                            <th style="width:3%">Last Crawl Time</th>
                            <th style="width:3%">Page Fetch State</th>
                            <th style="width:3%">Google Canonical</th>
                            <th style="width:3%">Google Date</th>
                            <th style="width:3%">Created At</th>
                            <th style="width:3%"></th>
                        </tr>
                    </thead>
                <!-- Your table content will be filled by DataTables -->
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var dataTable = $('#empTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'POST',
                'headers': {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-Requested-Session': '<?php echo session_id(); ?>'
                },
                'ajax': {
                    'url':'/tables/seo/get_seo.php',
                    'type': 'POST',
                    'data': function (data) {
                        data.start = data.start;
                        data.length = data.length;
                    }
                    
                },
            columns: [
                { data: 'Id' },
                { data: 'Link' },
                { data: 'UpdatedAt' },
                { data: 'SpeedTest' },
                { data: 'SpeedDate' },
                { data: 'GoogleIndex' },
                { data: 'IndexingState' },
                { data: 'Verdict' },
                { data: 'CoverageState' },
                { data: 'RobotsTxtState' },
                { data: 'LastCrawlTime' },
                { data: 'PageFetchState' },
                { data: 'GoogleCanonical' },
                { data: 'GoogleDate' },
                { data: 'CreatedAt' },
                { data: 'Delete'},
            ],
            "columnDefs": [
                // { "visible": false, "targets": [8,9,10 ] },
                { "orderable": false, "targets": [15 ] }
            ],
            // Add paging options
            lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500,'All']],
            pageLength: 10,
            dom: 'lBfrtip',
            buttons: [
                'copy','excel' //{
                    // extend: 'excel',
                    // exportOptions: {
                    //     columns: [<?php //foreach ($columnNamesdisplay as $columnName){ ?>'<?php //echo $columnName ?>', <?php //} ?>] 
                    // }
                //}
                , 'pdf', 'print'
            ]

            // Add any additional DataTable options as needed
        });


        $(document).on('click', '.delete-link', function(event) {
        event.preventDefault();

        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this items?')) {
          $.ajax({
            type: 'GET',
            data: {
                id: id
            },
            url: '<?php echo $url . 'seo/delete' ?>/'+id,
            contentType: 'application/x-www-form-urlencoded',
            success: function(response) {
                if (response.success == true) {
                    success_noti("successully deleted.")
                    dataTable.draw(false);
                } else {
                    error_noti(response.message);
                }
            },
            error: function() {
                error_noti("Failed to delete the row.");
                success = false;
            }
          });
            
        }
    });
      });
        // setInterval(function() {
        //     dataTable.draw(false);
        // }, 10000); // 10000 milliseconds = 10 seconds
        

    // Initialize end date picker
</script>
<?php

    include 'include/logview.php';
?>
<script>
  
    
    // Attach a click event handler to the button
    $('#example2 tbody tr').each(function() {
    var speedTestCell = $(this).find('td:eq(3)');
    
    var pageFetchState = $(this).find('td:eq(11)');
    var robotsTxtState = $(this).find('td:eq(9)');
    var coverageState = $(this).find('td:eq(8)');
    var vedict = $(this).find('td:eq(7)');
    var indexingstate = $(this).find('td:eq(6)');
    if(indexingstate.text().includes("INDEXING_ALLOWED")){
      indexingstate.css('color','lightgreen');
    }else {
      indexingstate.css('color','red');
    }

    if(vedict.text().includes("PASS")){
      vedict.css('color','lightgreen');
    }else if(vedict.text().includes("NEUTRAL")){
      vedict.css('color','gold');
    }else {
      vedict.css('color','red');
    }

    if(coverageState.text().includes("Alternate page with proper canonical tag")){
      coverageState.css('color','gold');
    }else if(coverageState.text().includes("Submitted and indexed")){
      coverageState.css('color','lightgreen');
    }else{
      coverageState.css('color','red');
    }

    if(robotsTxtState.text().includes("ALLOWED") && !robotsTxtState.text().includes("NOT ALLOWED")){
      robotsTxtState.css('color','lightgreen');
    }else {
      robotsTxtState.css('color','gold');
    }
    
    if(pageFetchState.text().includes("SUCCESSFUL")){
      pageFetchState.css('color','lightgreen');
    }else {
      pageFetchState.css('color','red');
    }
      var speedTestValue = parseFloat(speedTestCell.text());
      var checked='Check',
      gchecked='reCheck',
      ychecked='reCheck',
      bchecked='reCheck';
      var badgeClass = '';
      if (speedTestValue !== null && !isNaN(speedTestValue)) {
        checked='reCheck';
          if (speedTestValue < 0.90) {
              badgeClass = 'bg-danger';
          } else if (speedTestValue >= 0.90 && speedTestValue < 0.95) {
              badgeClass = 'bg-warning';
          } else {
              badgeClass = 'bg-success';
          }

          var badgeHtml = '<div class="td-container"><span class="badge ' + badgeClass + '">' + (speedTestValue * 100).toFixed(2) + '%</span></div>';
          speedTestCell.html(badgeHtml);
      } else {
          speedTestCell.html('<div class="td-container"><span class="badge bg-secondary">not checked</span></div>');
      }

      var google = $(this).find('td:nth-child(6)');
      var gbadgeClass = '';
      var gbadgeText = '';
      
      if (google.text().trim() == '1') {
        gbadgeClass = 'bg-success';
        gbadgeText = 'Indexed';
      } else if (google.text().trim() == '0') {
        gbadgeClass = 'bg-danger';
        gbadgeText = 'Not Indexed';
      } else {
        gchecked='Check';
        gbadgeClass = 'bg-secondary';
        gbadgeText = 'Not Checked';
      }
  
      google.html('<div class="td-container"><span class="badge ' + gbadgeClass + '">' + gbadgeText + '</span></div>');

    var yandex = $(this).find('td:nth-child(15)');
      var ybadgeClass = '';
      var ybadgeText = '';
      
      if (yandex.text().trim() === '1') {
        ybadgeClass = 'bg-success';
        ybadgeText = 'Indexed';
      } else if (yandex.text().trim() === '0') {
        ybadgeClass = 'bg-danger';
        ybadgeText = 'Not Indexed';
      } else {
        ychecked='Check';
        ybadgeClass = 'bg-secondary';
        ybadgeText = 'Not Checked';
      }
      yandex.html('<div class="td-container"><span class="badge ' + ybadgeClass + '">' + ybadgeText + '</span></div>');
    var bing = $(this).find('td:nth-child(17)');
      var bbadgeClass = '';
      var bbadgeText = '';
      if (bing.text().trim() === '1') {
        bbadgeClass = 'bg-success';
        bbadgeText = 'Indexed';
      } else if (bing.text().trim() === '0') {
        bbadgeClass = 'bg-danger';
        bbadgeText = 'Not Indexed';
      } else {
        bchecked='Check';
        bbadgeClass = 'bg-secondary';
        bbadgeText = 'Not Checked';
      }
    bing.html('<div class="td-container"><span class="badge ' + bbadgeClass + '">' + bbadgeText + '</span></div>');
    var buttonCell1 = $('<button class="btn btn-primary" data-action="check_speed"><i class="lni lni-rocket"></i> '+checked+'</button>');
    var buttonCell2 = $('<button class="btn btn-primary" data-action="check_google_index"><i class="lni lni-reload"></i> '+gchecked+'</button>');
    var buttonCell3 = $('<button class="btn btn-primary" data-action="check_yandex_index"><i class="lni lni-reload"></i> '+ychecked+'</button>');
    var buttonCell4 = $('<button class="btn btn-primary" data-action="check_bing_index"><i class="lni lni-reload"></i> '+bchecked+'</button>');
    speedTestCell.find('.td-container').append(buttonCell1);
    google.find('.td-container').append(buttonCell2);
    yandex.find('.td-container').append(buttonCell3);
    bing.find('.td-container').append(buttonCell4);
  });
    $(document).ready(function() {

      $(document).on('click', '#fsd', function () {
        $.ajax({
          url: "<?php echo $url;?>tables/seo/update.php",
          type: "GET",
          success: function(response) {
            if(response.success == true){
              success_noti(response.message)
            }else{
              error_noti(response.message);
            }
          },
          error: function(response) {
            error_noti(response.message);
          }
        });
      })
      

      $(document).on('click', '.custom_buttons button', function () {
        var action = $(this).data("action");
        var input = $(this).parent().find("input").val();
        if(action == 'google_cron'){
          $.ajax({
            url: "/google<?php echo $_SESSION['db'] ?>.php",
            type: "GET",
            success: function(response) {},
            error: function() {}
          });
        }else if(action == 'speed_cron'){
          $.ajax({
            url: "/speed<?php echo $_SESSION['db'] ?>.php",
            type: "GET",
            success: function(response) {},
            error: function() {}
          });
        }else{
        $.ajax({
          url: window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'))+"/functions",
          type: "POST",
          data: { action: action, url: input },
          success: function(response) {
            
            // Handle the response from the PHP function
              switch(action){
                case 'google_cron':
                  
                  break;
                case 'check_google_index':
                  
                break;
                case 'check_yandex_index':
                  
                break;
                case 'check_bing_index':
                break;
              }
          },
          error: function() {

          }
        });
      }
      });

      $(document).on('click', '#empTable span', function () {
        // Make an AJAX request to the PHP script
        var beforeElement = this.previousElementSibling;
        var nextElement = this.parentNode.parentNode.nextElementSibling;
        var action = $(this).data("action");
        var tr = $(this).closest("tr");
        var url = tr.find("td:nth(1)").text().trim();
        var id = tr.find("td:nth(0)").text().trim();
        var update = tr.find("td:nth(2)").text().trim();
        var badge = $(this).closest("td").find(".badge");
        var icon = $(this).find("i");

        // Add spinning class to the icon to start animation
        icon.addClass("spinning-icon");
        $.ajax({
          url: window.location.pathname.substring(0, window.location.pathname.lastIndexOf('/'))+"/functions",
          type: "POST",
          data: { action: action, url: url, id: id },
          success: function(response) {
            icon.removeClass("spinning-icon");
            // Handle the response from the PHP function
              switch(action){
                case 'check_google_index':
                  if(response.indexed == 1 ){
                    badge.text('indexed');
                    badge[0].className = 'badge bg-success';
                  }else{
                    badge.text('not indexed');
                    badge[0].className = 'badge bg-danger';
                  }
                  tr.find("td:nth(6)").text(response.inspectionResult.indexStatusResult.indexingState != 'undefined' ? response.inspectionResult.indexStatusResult.indexingState : '' );
                  tr.find("td:nth(7)").text(response.inspectionResult.indexStatusResult.verdict != 'undefined' ? response.inspectionResult.indexStatusResult.verdict : '');
                  tr.find("td:nth(8)").text(response.inspectionResult.indexStatusResult.coverageState != 'undefined' ? response.inspectionResult.indexStatusResult.coverageState : '');
                  tr.find("td:nth(9)").text(response.inspectionResult.indexStatusResult.robotsTxtState != 'undefined' ? response.inspectionResult.indexStatusResult.robotsTxtState : '');
                  tr.find("td:nth(10)").text(response.inspectionResult.indexStatusResult.lastCrawlTime != 'undefined' ? response.inspectionResult.indexStatusResult.lastCrawlTime : '');
                  tr.find("td:nth(11)").text(response.inspectionResult.indexStatusResult.pageFetchState != 'undefined' ? response.inspectionResult.indexStatusResult.pageFetchState : '');
                  tr.find("td:nth(12)").text(response.inspectionResult.indexStatusResult.googleCanonical != 'undefined' ? response.inspectionResult.indexStatusResult.googleCanonical : '');
                  tr.find("td:nth(13)").text('Just now');

                  if(tr.find("td:nth(6)").text().includes("INDEXING_ALLOWED")){
                    tr.find("td:nth(6)").css('color','lightgreen');
                  }else {
                    tr.find("td:nth(6)").css('color','red');
                  }

                  if(tr.find("td:nth(7)").text().includes("PASS")){
                    tr.find("td:nth(7)").css('color','lightgreen');
                  }else if(tr.find("td:nth(7)").text().includes("NEUTRAL")){
                    tr.find("td:nth(7)").css('color','gold');
                  }else {
                    tr.find("td:nth(7)").css('color','red');
                  }

                  if(tr.find("td:nth(8)").text().includes("Alternate page with proper canonical tag")){
                    tr.find("td:nth(8)").css('color','gold');
                  }else if(tr.find("td:nth(8)").text().includes("Submitted and indexed")){
                    tr.find("td:nth(8)").css('color','lightgreen');
                  }else {
                    tr.find("td:nth(8)").css('color','red');
                  }

                  if(tr.find("td:nth(9)").text().includes("ALLOWED") && !tr.find("td:nth(9)").text().includes("NOT ALLOWED")){
                    tr.find("td:nth(9)").css('color','lightgreen');
                  }else {
                    tr.find("td:nth(9)").css('color','gold');
                  }
                  
                  if(tr.find("td:nth(11)").text().includes("SUCCESSFUL")){
                    tr.find("td:nth(11)").css('color','lightgreen');
                  }else {
                    tr.find("td:nth(11)").css('color','red');
                  }
    
                  
                break;
                case 'check_yandex_index':
                  if(response == 1 ){
                    badge.text('indexed');
                    badge[0].className = 'badge bg-success';
                    $(nextElement).text('Just now');
                  }else{
                    badge.text('not indexed');
                    badge[0].className = 'badge bg-danger';
                    $(nextElement).text('Just now');
                  }
                  break;
                case 'check_bing_index':
                break;
                case 'check_speed':
                  $(beforeElement).text(response*100+'.00%');
                  if(response < 0.90){
                    badge[0].className = 'badge bg-danger';
                  }else if(response >= 0.90 && response < 0.95){
                    badge[0].className = 'badge bg-orange';
                  }else{
                    badge[0].className = 'badge bg-success';
                  }
                  $(nextElement).text('Just now');
                break;
              }
          },
          error: function() {
            icon.removeClass("spinning-icon");
            console.log("Error calling PHP function.");
          }
        });
      });
    });

    


</script>
<?php
}else{

  ?>
  <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
      <div class="d-flex align-items-center">
          <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div class="ms-3">
          <div class="text-warning">You are not allowed to access this page!</div>
          </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
  <?php

}

?>