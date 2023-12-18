<?php
if ($_SESSION['userlevel'] > 9 ) {
  
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $ignoredColumns = ['deleted_at','published_at'];
    $tooltips = [];
    $popups = [];
    $jsonarrays = [];
    $urlPaths = ['link' => 'link'];
    //$gsc = ['indexed' => 's_alias'];
    $fieldTitles = ['LastCrawlTime' => 'Last Crawl Time','IndexingState'=>'Indexing State'];
    $dateColumns = ['created_at', 'updated_at','speed_date','google_date','yandex_date','bing_date','LastCrawlTime']; // replace with your actual date columns
    $custom_buttons = [
      // (object)[
      //     'id' => 'google_sitemap',
      //     'type' => 'button',
      //     'kind' => 'URL',
      //     'action' => 'check_google_sitemap',
      //     'name' => 'resubmit google sitemap',
      // ],
      // (object)[
      //   'id' => 'yandex_sitemap',
      //   'type' => 'button',
      //   'kind' => 'URL',
      //   'action' => 'check_yandex_sitemap',
      //   'name' => 'resubmit yandex sitemap',
      // ],
      // (object)[
      //   'id' => 'bing_sitemap',
      //   'type' => 'button',
      //   'kind' => 'URL',
      //   'action' => 'check_bing_sitemap',
      //   'name' => 'resubmit bing sitemap',
      // ],
      (object)[
        'id' => 'cron_index',
        'type' => 'button',
        'kind' => 'URL',
        'action' => 'google_cron',
        'name' => 'Check Google Index',
        'class' => '',
      ],
      (object)[
        'id' => 'cron_speed',
        'type' => 'button',
        'kind' => 'URL',
        'action' => 'speed_cron',
        'name' => 'Check Page Speed',
        'class' => '',
      ],
      // Add more objects as needed
    ];
    $dataArrays = [       
      // 'google_index' => ['0'=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-danger">not indexed</span></div>','1'=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-success">indexed</span></div>',''=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-secondary">not checked</span></div>'],
      // 'yandex_index' => ['0'=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-danger">not indexed</span></div>','1'=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-success">indexed</span></div>',''=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-secondary">not checked</span></div>'],
      // 'bing_index' => ['0'=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-danger">not indexed</span></div>','1'=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-success">indexed</span></div>',''=>'<div class="td-container d-flex justify-content-between align-items-center"><span class="badge bg-secondary">not checked</span></div>'],
        // Add more column mappings here
    ];
    $imagePaths = [];
    $urlslug = '';
    $custom_button_title = 'Update SEO Data';
    $no_edits = true;
    $pagelength = 25;
    $ajaxview= false;
//     $costumeQuery = "SELECT *,CONCAT(
//       '<div class=\"td-container d-flex justify-content-between align-items-center\">',
//       '<span class=\"badge ',
//       IF(speed_test IS NOT NULL AND speed_test < 0.90, 'bg-danger',
//         IF(speed_test >= 0.90 AND speed_test < 0.95, 'bg-orange',
//             IF(speed_test IS NULL, 'bg-secondary', 'bg-success')
//         )
//       ),
//       '\">',
//       IF(speed_test IS NOT NULL AND speed_test != 'checked', CONCAT(CAST(speed_test * 100 AS DECIMAL(10, 2)), '%'), 'not checked'),
//       '</span>',
//       '</div>'
//   ) AS speed_test 
// FROM seo";


if(isset($_SESSION['success'])){
  if($_SESSION['success'] == true){
    $success = 'success';
    $icon = 'bi bi-check-circle-fill';
  }else{
    $success = 'danger';
    $icon = 'bi bi-exclamation-triangle-fill';
  }
  ?>
  <div class="alert border-0 bg-light-<?php echo $success; ?> alert-dismissible fade show py-2">
      <div class="d-flex align-items-center">
          <div class="fs-3 text-<?php echo $success; ?>"><i class="<?php echo $icon; ?>"></i>
          </div>
          <div class="ms-3">
          <div class="text-<?php echo $success; ?>"><?php echo $_SESSION['msg']; ?></div>
          </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php
  unset($_SESSION['success']); // Clear session variable
  unset($_SESSION['msg']);     // Clear session variable
}
    include 'include/view.php';
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

      $(document).on('click', '#example2 button', function () {
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
  <?php

}

?>