<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

<!------ Include the above in your HEAD tag ---------->

    <div class="row">

        <div class="col-md-12">
      <a href="../tags/edit" class="btn btn-primary">Add New Tag</a>
      <a href="../subtags/addmultyple" class="btn btn-danger" >Add Multi Sub Tag</a> 

            <?php
            $querykeywordscount = "SELECT COUNT(*) FROM `keywords`";
            $querykeywordscount = mysqli_query($conn, $querykeywordscount);
            $querykeywordscount = mysqli_fetch_assoc($querykeywordscount);
            $querykeywordscount = $querykeywordscount['COUNT(*)'];
            ?>
            <h1>Total Keywords (<?php echo $querykeywordscount; ?> ) </h1>
            <ul id="tree2">
                <?php 
                    $Query = "SELECT tags.id as id, tags.tag as tag, COUNT(keyword_tag.keyword_id) as count 
                    FROM tags 
                    LEFT JOIN keyword_tag ON tags.id = keyword_tag.tag_id 
                    GROUP BY tags.id, tags.tag order by  id ASC ";
                    $Result = mysqli_query($conn, $Query);
                    if ($Result) {
                    while ($row = mysqli_fetch_assoc($Result)) {
                         $subtagquery = "SELECT subtags.id as id, subtags.subtag as subtag, COUNT(keyword_subtag.keyword_id) as count
                        FROM subtags
                        LEFT JOIN keyword_subtag ON subtags.id = keyword_subtag.subtag_id
                        WHERE subtags.tag_id = " . $row['id'] . " GROUP BY subtags.id, subtags.subtag";
                        $subtagresult = mysqli_query($conn, $subtagquery);

                        echo '<li>  <a href="view?filterby='.$row['id'].'"  target="_blank">#' .$row['id'].'</a> 
                        ' .$row['tag'].' (' .$row['count'] . ' Keywords) <a href="../subtags/edit?tagid='.$row['id'].'" >Add One</a/>Sub Tag |  ( ' .mysqli_num_rows($subtagresult) .' Sub Tags)' ;
                        if ($subtagresult && mysqli_num_rows($subtagresult) > 0) {
                            echo '<ul>';
                            //
                            while ($subtagrow = mysqli_fetch_assoc($subtagresult)) {
                                $course_main_query = "SELECT  * from `course_main` Where subtag_id=". $subtagrow['id'] ." order by  name ASC ";
                                $course_main_result = mysqli_query($conn, $course_main_query);
                                // get all used keyword and the count
                                $coursemainusedkeywords = array();
  //                              while ($course_main_row = mysqli_fetch_assoc($course_main_result)) {
                                    // get keyword json array count
                                   // $keyword = json_decode($course_main_row['keyword']);
                                    //echo '<pre>';
                                    
                                    //$regularArray = [];
                                    //foreach($keyword as $obj) {
                                      //  $regularArray[] = $obj->value;
                                    //}
                                    //print_r($regularArray);

                                    //$coursemainusedkeywords = array_merge($coursemainusedkeywords, $regularArray);

                                    //echo '</pre>';
//
                                    //$keywordcount = count($coursemainusedkeywords);
                                  
    //                            }
                                //$coursemainusedkeywords = array_unique($coursemainusedkeywords);
      //                          $keywordusedcount = count($coursemainusedkeywords);
                                
                                echo '<li> <a href="view?filterby='.$row['id'].'&filterby2='.$subtagrow['id'].'"  target="_blank"> #'.$subtagrow['id'].'</a>'  .' '. $subtagrow['subtag'] . ' (' . $subtagrow['count'] . ' Keywords)';
                                //echo '<span class="text-success"> there are '.$keywordusedcount.' keywords in use </span> ';
                                //echo '<span class="text-danger"> there are 10 keywords not use </span>' ;
                                echo ' (' . mysqli_num_rows($course_main_result) .'  Courses) <a href="../course_main/addmultyple/'.$subtagrow['id'].'"  > Add More Courses </a> ';
                                if ($_SESSION['userlevel'] > 9){
                                echo ' <a href="../subtags/delete/'.$subtagrow['id'].'"><i class="lni lni-trash text-danger"></i></a>';
                                }
                                if ($course_main_result && mysqli_num_rows($course_main_result) > 0) {
                                    echo '<ul>';
                                    while ($course_main_row = mysqli_fetch_assoc($course_main_result)) {
                                        // get keyword json array count
                                        $keyword = json_decode($course_main_row['keyword']);
                                        $keywordcount = count($keyword);
                                        
                                        if ($course_main_row['updated_at'] != NULL) {
                                            $color = 'text-info';
                                        } else {
                                            $color = 'text-secondary';
                                        }
                                        echo '<li> <a  class="'.$color.'" href="../course_main/edit/'.$course_main_row['id'].'"  target="_blank"> #'.$course_main_row['id'] .' '. $course_main_row['name'] . ' (' . $keywordcount . ' Keywords)  </a>';
                                        if ($_SESSION['userlevel'] > 9){
                                            echo '  <a href="../course_main/delete/'.$course_main_row['id'].'"><i class="lni lni-trash text-danger"></i></a>';
                                        }
                                        
                                    }
                                    echo '</ul>';
                                } else {
                                    error_log("Error: " . mysqli_error($conn));
                                }
                                echo  '</li>';

                            }
                            echo '</ul>';
                        } else {
                            error_log("Error: " . mysqli_error($conn));
                        } 
                        echo '</li>';

                    }
                    } else {
                        error_log("Error: " . mysqli_error($conn2));
                    }
                ?>
                <li><a href="#">TECH</a>


                </li>
                <li>XRP
                    <ul>
                        <li>Company Maintenance</li>
                        <li>Employees
                            <ul>
                                <li>Reports
                                    <ul>
                                        <li>Report1</li>
                                        <li>Report2</li>
                                        <li>Report3</li>
                                    </ul>
                                </li>
                                <li>Employee Maint.</li>
                            </ul>
                        </li>
                        <li>Human Resources</li>
                    </ul>
                </li>
            </ul>
        </div>
         </div>


<script>

$.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
      tree.find('.branch .indicator').each(function(){
        $(this).on('click', function () {
            $(this).closest('li').click();
        });
      });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({openedClass:'glyphicon-folder-open', closedClass:'glyphicon-folder-close'});

$('#tree3').treed({openedClass:'glyphicon-chevron-right', closedClass:'glyphicon-chevron-down'});


</script>
<style>
    .tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0 1em;
    line-height:2em;
    color:#fff;
    font-weight:700;
    position:relative
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#DDD;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color: #EEE;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>