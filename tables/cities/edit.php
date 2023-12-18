<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';
    $newdbstype = $db_name == "agile4training" || $db_name == "blackbird-training.co.uk";

    $row = GetRow($id,'id',$tablename , $theconnection);
    $Columns = GetTableColumns($tablename,$theconnection);

    FormsStart();
    FormsInput('name','Name', 'text', true, 'col-6',true,5,70,'published_at' );
    FormsInput('ar_name','Arabic Name', 'text', true, 'col-6',false,5,70,'published_at' );
    
    FormsInput('countryname','Country Name', 'text', true, 'col-6',false,40,70,'published_at' );
    if ($id) {
        FormsInput('s_alias','Slug (Do Not Change if you dont know what you do!)', 'text', true, 'col-6',false,8,70,'' );  
    }

    FormsInput('title','Title', 'text', false, 'col-6',true,25,60,'published_at' );
    FormsInput('code','Code', 'text', false , 'col-4 ',true,1,3,'');
    FormsInput('hotel_name','Hotel Name', 'text', false, 'col-6',false,40,70,'published_at' );
    FormsInput('hotel_link','Hotel Link', 'text', false, 'col-6',false,40,70,'published_at' );
    FormsInput('hotel_photo','Hotel Photo', 'text', false, 'col-6',false,40,70,'published_at' );

    $options = array(
        "01_Europe & USA" => "Europe and America", 
        "03_Asia" => "Asia",
        "02_Middle East & africa" => "Middle East and Africa",
    );
    FormsSelect('continental','Continental', $options , true, 'col-4');
    $options = array(
        "01_أوربا وأمريكا" => "أوربا وأمريكا", 
        "03_اسيا" => "اسيا",
        "02_الشرق الاوسط و افريقيا" => "الشرق الاوسط و افريقيا",
    );
    FormsSelect('ar_continental','AR Continental', $options , true, 'col-4');

    $options = array(
        "A" => "A", 
        "B" => "B",
        "C" => "C",
    );
    FormsSelect('class','Class', $options , true, 'col-4');
    FormsCheck('monday', 'Monday', 'checkbox',false,'1', 'col-1' );
    ?><div class="row"><hr><?php
if($newdbstype){
    FormsImg('city_photo','City Image', 'col-4 row float-start',$citiesimgurl,'s_alias',$tablename,$remoteDirectory,'cities','1.35',100,20);
    FormsImg('slider_photo','Slider Image', 'col-4 row float-start',$citiessliderimgurl,'s_alias',$tablename,$remoteDirectory,'bg','2.45',1500,70);
    ?><hr></div><?php
}
    if ($id){
        if(isset($row['keyword'])){

            $row['keyword'] = JsonArrayAsText($row['keyword']);
        }
    }
    FormsText('keyword','Keywords', 'keyword','', 'true', 'col-6', 20, false,0,0);
    FormsText('description','description', 'text','published_at', false, 'col-6', 10, true,110,160);
    FormsEditor('about','About', 'text', 'true', 'col-12  ');

    ?><div class="row"><hr><?php
    FormsInput('x','Ratio for Class A', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w1_p','Price for 1 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w2_p','Price for 2 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w3_p','Price for 3 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w4_p','Price for 4 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    ?><div class="row"><hr><?php
    FormsInput('x_b','Ratio for Class B', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w1_p_b','Price for 1 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w2_p_b','Price for 2 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w3_p_b','Price for 3 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w4_p_b','Price for 4 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    ?><div class="row"><hr><?php
    FormsInput('x_c','Ratio for Class c', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w1_p_c','Price for 1 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w2_p_c','Price for 2 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w3_p_c','Price for 3 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w4_p_c','Price for 4 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    ?><hr></div><?php
    ?><br><br><?php
    FormsDateTimeNew('published_at','Publish Date And Time', false, 'col-6');
    FormsEnd(); 
    
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