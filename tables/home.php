<?php 
echo "<h3>Welcomback ";echo $username = $_SESSION['username']; echo "</h3>";
if(isset($_SESSION['db'])){
    if(isset($conn2)){
        createAdminLogTable($conn2);
        checkTableColumns('seo', $conn2);
    }
}

?>

<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
    <div class="col">
        <a href="event/view">
            <div class="card card-special rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Events</p>
                            <h4 id="totalevents" class="mb-0">0</h4>
                            <input type="hidden" id="totalevents2" value="0">
                            <p class="mb-0 mt-2 font-13"><i class="bi bi-arrow-up"></i><span><span id="totaleventslast">0</span><!-- %--> from last week </span></p>
                            <input type="hidden" id="totaleventslast2" value="0">

                        </div>
                        <div class="ms-auto widget-icon bg-primary text-white">
                            <i class="lni lni-calendar"></i>
                        </div>
                    </div>
            
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="courses/view">
            <div class="card card-special rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Courses</p>
                            <h4 id="totalcourses" class="mb-0">0</h4>
                            <input type="hidden" id="totalcourses2" value="0">
                            <p class="mb-0 mt-2 font-13"><i class="bi bi-arrow-up"></i><span><span id="totalcourseslast">0</span><!-- %--> from last week</span></p>
                            <input type="hidden" id="totalcourseslast2" value="0">

                        </div>
                        <div class="ms-auto widget-icon bg-success text-white">
                            <i class="lni lni-certificate"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="categories/view">
            <div class="card card-special rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Categories</p>
                            <h4 id="totalcategories" class="mb-0">0</h4>
                            <input type="hidden" id="totalcategories2" value="0">
                            <p class="mb-0 mt-2 font-13"><i class="bi bi-arrow-up"></i><span><span id="totalcategorieslast">0</span><!-- %--> from last week</span></p>
                            <input type="hidden" id="totalcategorieslast2" value="0">

                        </div>
                        <div class="ms-auto widget-icon bg-orange text-white">
                            <i class="lni lni-archive"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col">
        <a href="seo/view">
            <div class="card card-special rounded-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <p class="mb-1">Total Pages</p>
                            <h4 id="totalpages" class="mb-0">0</h4>
                            <input type="hidden" id="totalpages2" value="0">
                            <p class="mb-0 mt-2 font-13"><i class="bi bi-arrow-up"></i><span><span id="totalpageslast">0</span><!-- %--> from last week</span></p>
                            <input type="hidden" id="totalpageslast2" value="0">

                        </div>
                        <div class="ms-auto widget-icon bg-info text-white">
                            <i class="lni lni-seo"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<script>
    // silent load content
    $(document).ready(function() {
        //events
        $('#totalevents2').load('<?php echo $url; ?>tables/totals.php #coursecount', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalevents').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecount during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecount after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        $('#totaleventslast2').load('<?php echo $url; ?>tables/totals.php #coursecountlast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totaleventslast').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecountlast during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecountlast after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        //courses
        $('#totalcourses2').load('<?php echo $url; ?>tables/totals.php #coursescount', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalcourses').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecount during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecount after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        $('#totalcourseslast2').load('<?php echo $url; ?>tables/totals.php #coursescountlast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalcourseslast').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecountlast during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecountlast after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        //categories
        $('#totalcategories2').load('<?php echo $url; ?>tables/totals.php #categoriescount', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalcategories').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecount during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecount after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        $('#totalcategorieslast2').load('<?php echo $url; ?>tables/totals.php #categoriescountlast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalcategorieslast').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecountlast during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecountlast after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        //pages
        $('#totalpages2').load('<?php echo $url; ?>tables/totals.php #pagescount', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalpages').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecount during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecount after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });

        $('#totalpageslast2').load('<?php echo $url; ?>tables/totals.php #pagescountlast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#totalpageslast').animate({ count: currentCount }, {
                duration: 1000,
                step: function() {
                    // Update the content of #coursecountlast during the animation
                    $(this).text(Math.round(this.count).toLocaleString());
                },
                complete: function() {
                    // Update the content of #coursecountlast after the animation is complete
                    $(this).text(currentCount.toLocaleString());
                }
            });
        });
    });
</script>