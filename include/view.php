<meta charset="UTF-8"><?php 
$adminajaxview = true;

$start = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 10;
if(!isset($ajaxview))         {       $ajaxview = false; }
if(!isset($tablename))        {      $tablename = '';    }
if(!isset($tabletitle))       {     $tabletitle = '';    }
if(!isset($folderName))       {     $folderName = '';    }
if(!isset($urlslug))          {        $urlslug = '';    }
if(!isset($maxlenginfield))   { $maxlenginfield = '';    }
if(!isset($theconnection))    {  $theconnection = '';    }
if(!isset($thedbname))        {      $thedbname = '';    }
if(!isset($no_edits))         {       $no_edits = 0;     }
if(!isset($no_link))          {       $no_link = 0;      }
if(!isset($pagelength))       {     $pagelength = 100;   }
if(!isset($imagePaths))       {     $imagePaths = [''];  }
if(!isset($urlPath))          {     $urlPath = null;     }
if(!isset($withEventCid))     {   $withEventCid = false; }
if(!isset($searchsColumns))   { $searchsColumns = false; }
if(!isset($pageend))          {       $pageend = '';     }
if(!isset($_GET['startpage'])){       $startpage = 1;    }else{ $startpage = $_GET['startpage']; }
if(!isset($dashedname))       {   $dashedname = false;   }
if(!isset($editPath))         {   $editPath = false;     }

// Check if the 'category' parameter exists in the GET request
$allowedParameters = ['category', 'city', 'upcomming','monday'];
$searchColumns = [];

foreach ($allowedParameters as $param) {
    if (isset($_GET[$param]) && $_GET[$param] !== '0') {
        $searchColumns[$param] = $_GET[$param];
    }
}
// ... add more conditions for other table names if needed

// Check if $searchColumns is empty (no parameters were provided)
if (empty($searchColumns)) {
    $searchColumns = false;
}
$ignoredColumnsDB = $ignoredColumnsDB ?? []; 
$additionalColumns = $additionalColumns ?? [];
$result = checkTableColumns($tablename, $theconnection);
echo $result;
$columnQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tablename' AND TABLE_SCHEMA = '$thedbname' AND COLUMN_COMMENT != '0' ORDER BY ORDINAL_POSITION ASC";
$columnResult = mysqli_query($theconnection, $columnQuery);
if ($columnResult) {
    $columnNames = mysqli_fetch_all($columnResult, MYSQLI_ASSOC);
    $columnNames = array_column($columnNames, 'COLUMN_NAME');
    // Remove ignored columns for DB fetch from the columnNames array
    $columnNames = array_diff($columnNames, $ignoredColumnsDB);
    // Add additional columns to the columnNames array
    $columnNames = array_merge($columnNames, $additionalColumns);
} else {
    echo "Error: " . mysqli_error($theconnection);
}
if (isset($costumeQuery) && !empty($costumeQuery)) {
    $contentQuery = $costumeQuery;
} else {
    $contentQuery = "SELECT * FROM $tablename";
};

if(!$ajaxview){
    $contentResult = mysqli_query($theconnection, $contentQuery);
    if ($contentResult) {
        $contents = mysqli_fetch_all($contentResult, MYSQLI_ASSOC);
    } else {
        echo "Error: " . mysqli_error($theconnection);
    }
}
$columnNames = array_diff($columnNames, $ignoredColumns);  // Remove ignored columns for table generation
$columnNameDisplay = str_replace('_', ' ', array_map('ucwords', $columnNames));
$columnNamesdisplay = array_map('ucwords', $columnNameDisplay); 
 
if(isset($gsc)){
    $columnNames[] = $gsc['indexed'];
    $columnNamesdisplay['Indexed']='indexed';  
}else{
    $gsc['indexed'] = '';
}

if (isset($_POST['message'])) {
    $response = json_decode($_POST['message'], true);
    if ($response) {
        if ($response['success']) {
            echo '<div class="alert alert-success">' . $response['message'] . '</div>';
        } else {
            echo '<div class="alert alert-danger">' . $response['message'] . '</div>';
        }
    }
}

?>
<div id="messageDiv"></div>  
<div class="row">
    <div class="col-10">
    <h1><?php echo $tabletitle; ?></h1>
    </div>
    
    
</div>
<div class="row">
    <div class="col-10 d-inline-flex">

        <?php if(isset($custom_buttons) && is_array($custom_buttons) && count($custom_buttons) > 0){ 
            $values = [0, 3, 6, 12]; ?>
            <div class="custom_buttons col-<?php echo $values[count($custom_buttons)]; ?> ">
            <?php foreach($custom_buttons as $custom_button){ ?>
                <?php if($custom_button->type == 'accordion'){ ?>
                    <div class="accordion accordion-flush" id="<?php echo $custom_button->containerid ?>">
                      <div class="accordion-item">
                        <?php echo isset($custom_button->heading) ? "<h$custom_button->heading class='accordion-header' id='".$custom_button->id."'>" : "<h1 class='accordion-header' id='".$custom_button->id."'>" ?>
                          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $custom_button->acid ?>" aria-expanded="true" aria-controls="<?php echo $custom_button->acid ?>">
                            <?php echo $custom_button->lable ?>
                          </button>
                        <?php echo isset($custom_button->heading) ? "</h$custom_button->heading>" : "</h1>" ?>
                        <div id="<?php echo $custom_button->acid ?>" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#<?php echo $custom_button->containerid ?>" style="">
                          <div class="accordion-body">

                <?php }elseif($custom_button->type == 'accordionend'){ ?>
                                </div>
                        </div>
                      </div>
                      
                    </div>
                            
                <?php }elseif($custom_button->type == 'h' ){ ?>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        <div class="d-inline-flex col-<?php echo isset($custom_button->size) ? $custom_button->size : 6 ?>">
                    <?php }} ?>
                    <div class="input-group mb-6">
                        <?php echo isset($custom_button->heading) ? "<h$custom_button->heading>" : "<h1>" ?> <?php echo $custom_button->lable; ?> <?php echo isset($custom_button->heading) ? "</h$custom_button->heading>" : "</h1>" ?>
                    </div>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        </div>
                    <?php }} ?>
                <?php }else if($custom_button->type == 'upload' ){ ?>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        <div class="d-inline-flex col-<?php echo isset($custom_button->size) ? $custom_button->size : 6 ?>">
                    <?php }} ?>
                    <div class="input-group mb-6">
                        <button class="btn btn-primary" type="button" data-action="<?php echo $custom_button->action; ?>">Upload <?php echo $custom_button->name; ?> </button>
                        <input class="form-control" type="file" id="<?php echo $custom_button->id; ?>">
                    </div>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        </div>
                    <?php }} ?>
                <?php }else if($custom_button->type == 'input' ){ ?>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        <div class="d-inline-flex col-<?php echo isset($custom_button->size) ? $custom_button->size : 6 ?>">
                    <?php }} ?>
                    <div class="input-group mb-6">
                        <button class="btn btn-primary" type="button" data-action="<?php echo $custom_button->action; ?>"><?php echo $custom_button->name; ?> </button>
                        <?php if(isset($custom_button->kind) && $custom_button->kind !== ''){ ?><span class="input-group-text" id="basic-addon1"><?php echo $custom_button->kind; ?></span><?php } ?>
                        <input class="form-control" type="text" id="<?php echo $custom_button->id; ?>">
                    </div>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        </div>
                    <?php }} ?>
                <?php }else if($custom_button->type == 'a' ){ ?>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        <div class="d-inline-flex col-<?php echo isset($custom_button->size) ? $custom_button->size : 6 ?>">
                    <?php }} ?>
                    <div class="input-group mb-6">
                    <a id="<?php echo $custom_button->id; ?>" href="<?php echo $custom_button->action ?>" class="btn btn-primary <?php $custom_button->class ?>"><?php echo $custom_button->name ?></a>
                    </div>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                    </div>
                    <?php }} ?>
                <?php } else if ($custom_button->type == 'select') { ?>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        <div class="d-inline-flex col-<?php echo isset($custom_button->size) ? $custom_button->size : 6 ?>">
                    <?php }} ?>
                <!-- New code for handling select type -->
        <div class="input-group mb-6 m-1 ">
            <label for="<?php echo $custom_button->id; ?>" class="input-group-text d-none d-lg-block"><?php echo $custom_button->lable; ?></label>
            <!-- Assuming this is inside your loop for generating select elements -->
            <select class="single-select form-select float-right justify-content-end table-select" id="<?php echo $custom_button->id; ?>" data-table="<?php echo $custom_button->id; ?>">
    <!-- Default option -->
    <option value="0" <?php if($custom_button->selected == ''){ echo 'selected'; } ?>>Select <?php echo $custom_button->name; ?></option>
    
    <!-- Options for <?php echo $custom_button->name; ?> -->
    <?php foreach ($custom_button->options as $key => $value) { ?>
        <option value="<?php echo $key; ?>" <?php if($custom_button->selected == $key){ echo 'selected'; } ?>><?php echo $value; ?></option>
    <?php } ?>
</select>
            <?php if (isset($custom_button->kind) && $custom_button->kind !== '') { ?>
                <span class="input-group-text" id="basic-addon1"><?php echo $custom_button->kind; ?></span>
            <?php } ?>
            <!-- <button class="btn btn-primary " type="button" data-action="<?php //echo $custom_button->action; ?>" data-table="<?php //echo $custom_button->id; ?>"><?php //echo $custom_button->name; ?></button> -->
        </div>
                <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                    </div>
                <?php }} ?>
                <?php }else if($custom_button->type == 'button' ){ ?>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                        <div class="d-inline-flex col-<?php echo isset($custom_button->size) ? $custom_button->size : 6 ?>">
                    <?php }} ?>
                    <button class="btn btn-primary" data-action="<?php echo $custom_button->action ?>"><?php echo $custom_button->name ?></button>
                    <?php if(isset($custom_button->subdiv) ){ if($custom_button->subdiv == 'true' ){ ?>
                    </div>
                    <?php }} ?>
                <?php } ?>
            <?php } ?>
            </div>
        <?php }?>
    
    </div>
        <?php
        if(!isset($disableAddBtn)){
            if(isset($customLink) && !empty($customLink)){
                $goToLink = $customLink;
            }else{
                $goToLink = $url . $folderName . '/'. $editslug ;
            }
        ?>
    <div class="col-2 ">
        <a href="<?php echo $goToLink ; ?>" class="btn btn-primary float-right justify-content-end"><?php echo isset($custom_button_title) ? $custom_button_title : 'Add New '.$tabletitle; ?></a>
    </div>
    <?php } ?>
</div>

<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <?php if($ajaxview){ ?>
                
                <!-- jQuery Library -->
        <script src="<?php echo $url ?>assets/makitweb/jquery-3.3.1.min.js"></script>
        
        <!-- Datatable JS -->
        <script src="<?php echo $url ?>assets/makitweb/DataTables/datatables.min.js"></script>
                <div >
            <!-- Table -->
            <table id='empTable' class='table table-striped table-bordered dataTable no-footer display dataTable'>
            <thead>
                    <tr>
                    <?php 
                    if (!empty($columnNames)) {
                        foreach (array_combine($columnNames, $columnNamesdisplay) as $columnName => $columnNameDisplay) {
                            echo "<th>";
                            if (isset($fieldTitles[$columnName])) {
                                echo $fieldTitles[$columnName];
                            } else {
                                echo $columnNameDisplay;
                            }
                            echo "</th>";
                        }
                    } 
                    ?>
                    <?php if($no_link != true){ ?>
                        <th></th>
                        <?php } if($no_edits != true){ ?>
                        <th></th>
                        <?php
                        if ($_SESSION['userlevel'] > 2 ) {
                            echo '<th></th>';
                        }
                        if ($_SESSION['userlevel'] > 9 ) { ?>
                        <th></th>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                </thead>
                
            </table>
        </div>
        
        <!-- Script -->
        <script>
        <?php if(isset($imagePaths)){ ?>
            var imagePaths = {
                <?php 
                foreach ($columnNames as $columnName){
                    if (array_key_exists($columnName, $imagePaths)) {
                        echo "'" . $columnName . "':'" . $imagePaths[$columnName] . "',";
                    }   
                    }
                ?>
        };
       <?php } ?>
       var empTable = $('#empTable').DataTable({});
            $(document).ready(function(){
                filltable();
                // let Link = empTable.column('Link:name').index();
                // let Edit = empTable.column('Edit:name').index();
                // let Trash = empTable.column('Trash:name').index();
                // let Delete = empTable.column('Delete:name').index();

                // empTable.DataTable({
                //     "columnDefs": [
                //         // { "visible": false, "targets": [empTable.column('Link:name').index(),empTable.column('Edit:name').index(), empTable.column('Trash:name').index(), empTable.column('Delete:name').index()] },
                //         { "orderable": false, "targets": [empTable.columns().count() - 4, empTable.columns().count() - 3, empTable.columns().count() - 2, empTable.columns().count() - 1] }
                //     ]
                // });

                var searchValue = '<?php echo isset($_GET['searchfor']) ? $_GET['searchfor'] : ''; ?>';
                if (searchValue) {
                    empTable.search(searchValue).draw();
                }
                setInterval(function() {
                    empTable.draw(false);
                }, 10000); // 10000 milliseconds = 10 seconds
            });
            function filltable(){
                empTable.destroy();
                empTable = $('#empTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'post',
                    'headers': {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Requested-Session': '<?php echo session_id(); ?>'
                    },
                    'ajax': {
                        'url':'<?php if(isset($isLocal)){ echo $url; }else {echo '/' ;} ?>include/ajaxfile.php',
                        'data': function (data) {
                            data.start = data.start;
                            data.length = data.length;
                            // Include the table name in the request data
                            data.tablename = '<?php echo $tablename; ?>';
                            data.columns = '<?php echo implode(",", $columnNames); ?>';
                            <?php if(isset($imagePaths)){ ?>data.imagePaths = imagePaths; <?php } ?>
                            <?php if(isset($popups)){ ?> 
                            data.popups = '<?php echo implode(",", $popups); ?>';<?php } ?>
                            <?php if(isset($jsonarrays)){ ?> 
                            data.jsonarrays = '<?php echo implode(",", $jsonarrays); ?>';
                            <?php } ?>data.maxlenginfield = <?php echo $maxlenginfield; ?>;
                            <?php if (isset($url)) { ?>data.urll = '<?php echo $url; ?>';<?php } ?>

                            <?php if (isset($folderName)) { ?>data.folderName = '<?php echo $folderName; ?>';<?php } ?>

                            <?php if (isset($editslug)) { ?>data.editslug = '<?php echo $editslug; ?>';<?php } ?>

                            <?php if (isset($editPath)) { ?>data.editPath = '<?php echo $editPath; ?>';<?php } ?>
                            
                            <?php if (isset($trashslug)) { ?>data.trashslug = '<?php echo $trashslug; ?>';<?php } ?>
                            
                            <?php if (isset($tooltips)) { ?>data.tooltips = <?php echo json_encode(implode(',', $tooltips)); ?>;<?php } ?>
                            
                            <?php if (isset($excludesearch)) { ?>data.excludesearch = "<?php echo implode(',', $excludesearch); ?>";<?php } ?>
                            
                            <?php if (isset($searchsColumns)) { if($searchsColumns != false){ ?>data.searchsColumns = <?php echo json_encode($searchsColumns); ?>;<?php }} ?>

                            <?php if (isset($urlPaths)) { ?>data.urlPaths = <?php echo json_encode($urlPaths); ?>;<?php } ?>

                            <?php if ($urlPath != null) { ?>data.urlPath = '<?php echo $urlPath; ?>';<?php } ?>
                            
                            <?php if (isset($urlslug)) { ?>data.urlslug = '<?php echo $urlslug; ?>';<?php } ?>

                            <?php if (isset($pageend)) { ?>data.pageend = '<?php echo $pageend; ?>';<?php } ?>

                            <?php if (isset($dashedname)) { ?>data.dashedname = '<?php echo $dashedname; ?>';<?php } ?>

                            <?php if (isset($costumeQuery)) { ?>data.costumeQuery = "<?php echo str_replace(array("\r", "\n", "\t"), ' ', $costumeQuery); ?>";<?php } ?>

                            <?php if (isset($custom_from)) { ?>data.custom_from = "<?php echo $custom_from; ?>";<?php } ?>

                            <?php if (isset($custom_select)) { ?>data.custom_select = "<?php echo $custom_select; ?>";<?php } ?>
                            
                            <?php if (isset($custom_where)) { ?>data.custom_where = "<?php echo $custom_where; ?>";<?php } ?>

                            <?php if (isset($ajaxDataArrays)) { ?>data.ajaxDataArrays = <?php echo json_encode($ajaxDataArrays); ?>;<?php } ?>
                            
                            <?php if (isset($dateColumns)) { ?>data.dateColumns = <?php echo json_encode($dateColumns); ?>;<?php } ?>

                            <?php if (isset($ignoredColumns)) { ?>data.ignoredColumns = <?php echo json_encode($ignoredColumns); ?>;<?php } ?>
                            
                            <?php if (isset($gsc)) { ?>data.gsc = <?php echo json_encode($gsc); ?>;<?php } ?>

                            <?php if ($no_edits == true) { ?>data.noedits = <?php echo $no_edits; ?>;<?php } ?>

                            <?php if ($no_link == true) { ?>data.nolink = <?php echo $no_link; ?>;<?php } ?>

                            <?php if (isset($withEventCid) && $withEventCid == true) { ?>data.withEventCid = <?php echo $withEventCid; ?>;<?php } ?>
                        }
                        
                    },
                    'columns': [
<?php foreach ($columnNames as $columnName){ ?>
                        { data: '<?php echo $columnName ?>'},
<?php } if($no_link !=true){ ?>
                        { data: 'Link'},<?php } ?>
                        { data: 'Edit'},
<?php if ($_SESSION['userlevel'] > 2 ) { ?>
                        { data: 'Trash'},
<?php } ?>
<?php if ($_SESSION['userlevel'] > 9 ) { ?>
                        { data: 'Delete'},
                        <?php } ?>
],
        // Add paging options
        lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500,'All']],
        pageLength: 25,
        dom: 
        "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-12 col-md-6'l>>"+
        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" ,
        buttons: [
            'copy','excel' //{
                // extend: 'excel',
                // exportOptions: {
                //     columns: [<?php //foreach ($columnNamesdisplay as $columnName){ ?>'<?php //echo $columnName ?>', <?php //} ?>] 
                // }
            //}
            , 'pdf', 'print'
        ],
        "columnDefs": [
            // { "visible": false, "targets": [empTable.column('Link:name').index(),empTable.column('Edit:name').index(), empTable.column('Trash:name').index(), empTable.column('Delete:name').index()] },
            { "orderable": false, "targets": [<?php if($no_link !=true && $_SESSION['userlevel'] > 9){ ?>empTable.columns().count() - 4,empTable.columns().count() - 3, empTable.columns().count() - 2, empTable.columns().count() - 1<?php }elseif($no_link !=true && $_SESSION['userlevel'] > 2){ ?>empTable.columns().count() - 3,empTable.columns().count() - 2, empTable.columns().count() - 1<?php } elseif($no_link !=true){ ?>empTable.columns().count() - 2,empTable.columns().count() - 1<?php } ?> ] }
        ],
        "initComplete": function () {
            // Use a timeout to delay setting the page
            setTimeout(function() {
                empTable.page(<?php echo $startpage - 1; ?>).draw('page');
            }, 100);
        }});
            }
            
        </script>
            <?php }else{ ?>
            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr >
                    <?php 
                    if (!empty($columnNames)) {
                        foreach (array_combine($columnNames, $columnNamesdisplay) as $columnName => $columnNameDisplay) {
                            echo "<th>";
                            if (isset($fieldTitles[$columnName])) {
                                echo $fieldTitles[$columnName];
                            } else {
                                echo $columnNameDisplay;
                            }
                            echo "</th>";
                        }
                    } 
                    ?>
                    <?php if($no_link != true ){ ?>
                        <th>Link</th>
                        <?php } if($no_edits != true ){ ?>
                        <th>Edit</th>
                        <?php
                        if ($_SESSION['userlevel'] > 2 ) {
                            echo '<th>Trash</th>';
                        }
                        if ($_SESSION['userlevel'] > 9 ) { ?>
                        <th>Delete</th>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($contents) && !empty($contents)) : ?>
                        <?php foreach ($contents as $content) : ?>
    <tr >
        <?php foreach ($columnNames as $columnName) : ?>
            <?php if (in_array($columnName, array_keys($content))) : ?>
                <?php $cellContent = $content[$columnName];
                // If the column is in the dateColumns array, convert its content to time ago format.
                if (!empty($cellContent) && in_array($columnName, $dateColumns)) {
                    $cellContent = time_ago($cellContent);
                }
                if ($content['deleted_at'] == NULL) {
                    $deleteclass = '';
                } else {
                    $deleteclass = 'text-decoration-line-through text-danger';
                }
                // Check if the column has a corresponding data array
                if (array_key_exists($columnName, $dataArrays)) {
                    $dataArray = $dataArrays[$columnName];
                    $dataValue = $content[$columnName];
                    $cellContent = $dataArray[$dataValue] ?? '';
                }
                        if (in_array($columnName, $tooltips)) : ?>
                        <?php $tooltipContent = $cellContent; ?>
                        <?php $cellContent = $cellContent !== null && strlen($cellContent) >  $maxlenginfield ? substr($cellContent, 0, $maxlenginfield) . '...' : $cellContent; ?>
                        <td class="<?php echo $deleteclass ; ?>" data-toggle="tooltip" data-placement="top"
                            title="<?php echo htmlspecialchars($tooltipContent); ?>"><?php echo $cellContent; ?></td>
                        <?php elseif (in_array($columnName, $popups) && in_array($columnName, $jsonarrays)) : ?>
                        <?php $popupContent = $content[$columnName] ?? ''; // Check if $content[$columnName] is set, otherwise set $popupContent to an empty string
                        if ($popupContent !== null) {
                            $popupContent = json_decode($popupContent, true);
                            if (is_array($popupContent)) {
                                $popupValues = array_column($popupContent, 'value');
                                $popupContent = implode("<br>", $popupValues);
                            } else {
                                $popupContent = $popupContent['value'] ?? '';
                            }
                        } ?> 
                        <?php $cellContent = $cellContent !== null && strlen($cellContent) >  $maxlenginfield ? substr($cellContent, 0, $maxlenginfield) . '...' : $cellContent; ?>
                        <?php $btnContent = strlen($popupContent) > $maxlenginfield ? substr($popupContent, 0, $maxlenginfield) . '...' : $popupContent;   
                            $btnContent = strip_tags($btnContent); 
                            $btnContent = htmlspecialchars($btnContent, ENT_QUOTES); ?>
                        <td class="<?php echo $deleteclass ; ?>">
                            <button type="button" class="btn btn-secondary m-0 p-2"
                                onclick="showPopup('<?php echo htmlspecialchars($popupContent, ENT_QUOTES); ?>','<?php echo $columnName; ?>')"><?php echo $btnContent; ?></button>
                        </td>
                        <?php elseif (in_array($columnName, $popups)) : ?>
                        <?php 
                            $popupContent = $content[$columnName] ?? ''; // Check if $content[$columnName] is set, otherwise set $popupContent to an empty string
                            if ($popupContent !== null) {
                                $popupContent = str_replace("\n", "<br>", $popupContent);
                            }
                            
                            if ($cellContent !== null) {
                                $cellContent = strlen((string)$cellContent) > $maxlenginfield ? substr($cellContent, 0, $maxlenginfield) . '...' : $cellContent;
                            }
                            
                            $btnContent = $cellContent !== null ? (strlen((string)$cellContent) > $maxlenginfield ? substr($cellContent, 0, $maxlenginfield) . '...' : $cellContent) : '';?>
                        <td class="<?php echo $deleteclass ; ?>">
                            <button type="button" class="btn btn-secondary m-0 p-2 popup-btn"
                                data-content="<?php echo htmlspecialchars($popupContent, ENT_QUOTES); ?>"
                                data-subject="<?php echo $columnName; ?>"><?php echo $btnContent; ?></button>
                        </td>
                        <?php elseif (array_key_exists($columnName, $imagePaths)) : ?>
                        <?php $imagePath = $imagePaths[$columnName]; ?>
                        <td class="<?php echo $deleteclass ; ?>">
                            <a href="#" data-toggle="modal" data-target="#imageModal-<?php echo $columnName.'-'.$content['id']; ?>">
                                <img src="<?php echo $imagePath . $cellContent; ?>" alt="<?php echo $columnName.'-'.$columnName; ?>"
                                    style="width:100px; height:auto;">
                            </a>
                        </td>
                        <div class="modal fade" id="imageModal-<?php echo $columnName.'-'.$content['id']; ?>" tabindex="-1"
                            role="dialog" aria-labelledby="imageModalLabel-<?php echo $content['id']; ?>"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel-<?php echo $content['id']; ?>"><?php echo $imagePath ?>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <img src="<?php echo $imagePath . $cellContent; ?>"
                                            alt="<?php echo $columnName; ?>" style="width:100%; height:auto;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php elseif (array_key_exists($columnName, $urlPaths)) : ?>
                        <?php $urlColumn = $urlPaths[$columnName]; ?>
                        <?php $urlValue = $content[$urlColumn]; ?>
                        <td class="<?php echo $deleteclass ; ?>">
                            <a target="_blank"
                                href="<?php echo $urlslug . $urlValue; ?>"><?php echo $cellContent; ?></a>
                        </td>
                        <?php elseif ($columnName === $gsc['indexed']) : ?>
                            <?php $indexingStatus = checkIndexingStatus($urlslug . $urlValue); ?>
                            <?php echo '<td class="' . $deleteclass . '">' . $indexingStatus . '</td>'; ?>
                        <?php else : ?>
                        <td class="<?php echo $deleteclass ; ?>"><?php echo $cellContent; ?></td>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if($no_edits != true){ ?>
                        <td>
                            <a target="_blank" href="<?php echo $url . $folderName . '/'. $editslug . ($withEventCid ? '/'.$content['c_id'] : '') .'/' . $content['id']; ?>">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                        <?php
                        if ($_SESSION['userlevel'] > 2 ) {
                            ?>
                        <td>
                            <a target="_blank" class="trash-link" href="#" data-id="<?php echo $content['id']; ?>"
                                class="confirmAction">
                                <?php 
                                    if($content['deleted_at'] == NULL){
                                        $trashclass = 'bi bi-trash';
                                    }else{
                                        $trashclass = 'bi bi-arrow-counterclockwise text-danger';
                                    }
                                ?>
                                <i class="<?php echo $trashclass;  ?>"></i>
                            </a>
                        </td>
                        <?php } ?>
                        <?php 
                                    if ($_SESSION['userlevel'] > 9 ) { ?>
                        <td>
                            <a target="_blank" href="#<?php //echo $url . $folderName . '/delete/' . $content['id']; ?>"
                                class="delete-link" data-id="<?php echo $content['id']; ?>">
                                <i class="bi bi-x"></i>
                            </a>
                        </td>
                        <?php } ?>
                        <?php } ?>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                    <?php 
                        if (!empty($columnNames)) {
                            foreach (array_combine($columnNames, $columnNamesdisplay) as $columnName => $columnNameDisplay) {
                                echo "<th>";
                                if (isset($fieldTitles[$columnName])) {
                                    echo $fieldTitles[$columnName];
                                } else {
                                    echo $columnNameDisplay;
                                }
                                echo "</th>";
                            }
                        }
                        ?>
                        <?php if($no_edits != true){ ?>
                        <th>Edit</th>
                        <?php 
                        if ($_SESSION['userlevel'] > 2 ) {
                            echo "<th>Trash</th>";
                        }
                     
                        if ($_SESSION['userlevel'] > 9 ) {
                            echo "<th>Delete</th>";
                        } 
                        ?>
                        <?php } ?>
                    </tr>
                </tfoot>

            </table>
            <?php } ?>
        </div>
    </div>
</div>
<div id="popupModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="popupModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="popupModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p id="popupContent"></p>
            </div>
        </div>
    </div>
</div>
<script>
    
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('click', '.popup-btn', function() {
        var content = $(this).data('content');
        var subject = $(this).data('subject');
        console.log(content);
        showPopup(content, subject);
    });
    $(document).on('click', '.delete-link', function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to delete this item?')) {
            let success = deleteItem($(this).data('id'));
            if(success){
                $(this).closest('tr').remove();
            }
        }
    });

    $(document).on('click', '.trash-link', function(event) {
    event.preventDefault();
    var $this = $(this); // Cache the jQuery object
    if (confirm('Are you sure you want to trash this item?')) {
         trashItem($(this).data('id'),$(this)) ;
    }
});

    $(document).on('click', '.confirmAction', function(e) {
        e.preventDefault(); // Prevent the default action
        var href = $(this).attr('href');
        var confirmation = confirm('Are you sure you want to proceed with this action?');
        if (confirmation) {
            window.location.href = href; // Redirect to the intended URL
        }
    });

    $('#example').DataTable({
        "order": [[6, 'desc']] // Sort by the seventh column (date) in descending order
    });

    var table1 = $('#example2').DataTable({ 
        buttons: ['copy', 'excel', 'pdf', 'print'],
        pageLength: <?php { echo $pagelength; } ?>,
        lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"]]
        
    });

    table1.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

    var table2 = $('#admin_log').DataTable({
        buttons: ['copy', 'excel', 'pdf', 'print'],
        pageLength: <?php { echo $pagelength; } ?>,
        lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"]]
    });

    table2.buttons().container().appendTo('#admin_log_wrapper .col-md-6:eq(0)');
    
    
});


function showPopup(content) {
    //console.log("showPopup called with content: ", content);
    $('#popupContent').html(content);
    $('#popupModal').modal('show');
}

function showPopup(content,subject) {
    //console.log("showPopup called with content: ", content);
    $('#popupModalLabel').html(subject.charAt(0).toUpperCase() + subject.slice(1));
    $('#popupContent').html(content);
    $('#popupModal').modal('show');
}
// var deleteLinks = document.getElementsByClassName('delete-link');
//   for (var i = 0; i < deleteLinks.length; i++) {
//     deleteLinks[i].addEventListener('click', function(event) {
//       event.preventDefault();
//       var id = this.dataset.id;
//       if (confirm('Are you sure you want to delete this item?')) {
//         // Perform delete action
//         deleteItem(id);
//       }
//     });
//   }

  // Function to handle delete action
  function deleteItem(id) {

    $.ajax({
            type: 'GET',
            url: '<?php echo $url . $folderName . '/' . $deleteslug . '/' ?>' + id,
            contentType: 'application/x-www-form-urlencoded',
            success: function(response) {
                if (response.message == "Delete successful." ) {
                    success_noti("successully deleted.")
                    empTable.draw(false);
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

  // Function to handle trash action
    function trashItem(id, $this) {
        // Make an AJAX request to the delete endpoint
        $.ajax({
            type: 'GET',
            url: '<?php echo $url . $folderName . '/' . $trashslug . '/' ?>' + id,
            contentType: 'application/x-www-form-urlencoded',
            success: function(response) {
                console.log( response.un);
                if (response.un ) {
                    success_noti("successully untrashed.")
                } else {
                    success_noti("successully trashed.")
                }
                empTable.draw(false);
                if (typeof adminTable !== 'undefined') {
                    adminTable.draw(false);
                }
            },
            error: function() {
                error_noti("Failed to trash the row.");
            }
        });
    }

    // Retrieve the message from localStorage after the page reloads
    window.addEventListener('load', function () {
        var message = localStorage.getItem('message');
        if (message) {
            var messageDiv = document.getElementById('messageDiv');
            messageDiv.innerHTML = '<div class="alert alert-success">' + message + '</div>';
            // Clear the message from localStorage
            localStorage.removeItem('message');
        }
    });
</script>
