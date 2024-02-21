<?php 
echo "<h3>Welcomback ";echo $username = $_SESSION['username']; echo "</h3>";
if(isset($_SESSION['db'])){
    if(isset($conn2)){
        createAdminLogTable($conn2);
        checkTableColumns('seo', $conn2);
    }
}
?>
<style>
        .dragable {
            cursor: grab;
            user-select: none;
        }
    </style>
<div class="sortable-container">


    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4">
        <div class="col " draggable="true">
            <a class="nodecoration" href="event/view">
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

        <div class="col " draggable="true">
            <a class="nodecoration" href="courses/view">
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

        <div class="col " draggable="true">
            <a class="nodecoration" href="categories/view">
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

        <div class="col " draggable="true">
            <a class="nodecoration" href="seo/view">
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
    <div class="row row-cols-1 row-cols-lg-4 radial-charts g-4">
        <div class="col " draggable="true">
            <?php if($_SESSION['userlevel'] > 8){?><a class="nodecoration" href="event/fixdurations"><?php }?>
            <div class="card card-special rounded-4">
                <div class="card-body">
                <div class="text-center">
                    <p class="mb-1">Durations need fix</p>
                    <h4 id="durations" class="">0</h4>
                    <input type="hidden" id="durations2" value="0">
                </div>
                <div class="" style="position: relative;">
                    <div  style="min-height: 50px;"><center><div style='width: 70%'><div id="durationsChart"></div></div></center></div>
                    <div id="svgContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <h2 id="durationsText" x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="16" fill="#333">0%</h2>
                    </div>
                <div class="resize-triggers"><div class="expand-trigger"><div style="width: 354px; height: 227px;"></div></div><div class="contract-trigger"></div></div></div>
                <div class="text-center">
                    <p class="mb-1">Increased since Last Week</p>
                    <h4 id="durationslast" class="">0</h4>
                    <input type="hidden" id="durationslast2" value="0">
                </div>
                </div>
            </div>
            <?php if($_SESSION['userlevel'] > 8){?></a><?php }?>
        </div>

        <div class="col " draggable="true">
            <?php if($_SESSION['userlevel'] > 8){?><a class="nodecoration" href="event/fixold"><?php }?>
            <div class="card card-special rounded-4">
                <div class="card-body">
                <div class="text-center">
                    <p class="mb-1">Old Events</p>
                    <h4 id="oldevents" class="">0</h4>
                    <input type="hidden" id="oldevents2" value="0">
                </div>
                <div class="" style="position: relative;">
                    <div  style="min-height: 50px;"><center><div style='width: 70%'><div id="oldeventsChart"></div></div></center></div>
                    <div id="svgContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <h2 id="oldeventsText" x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24" fill="#333">0%</h2>
                    </div>
                <div class="resize-triggers"><div class="expand-trigger"><div style="width: 354px; height: 227px;"></div></div><div class="contract-trigger"></div></div></div>
                <div class="text-center">
                    <p class="mb-1">Increased since Last Week</p>
                    <h4 id="oldeventslast" class="">0</h4>
                    <input type="hidden" id="oldeventslast2" value="0">
                </div>
                </div>
            </div>
            <?php if($_SESSION['userlevel'] > 8){?></a><?php }?>
        </div>

        <div class="col " draggable="true">
            <?php if($_SESSION['userlevel'] > 8){?><a class="nodecoration" href="event/fixduplicate"><?php }?>
            <div class="card card-special rounded-4">
                <div class="card-body">
                <div class="text-center">
                    <p class="mb-1">Duplicated Events</p>
                    <h4 id="duplicatedevents" class="">0</h4>
                    <input type="hidden" id="duplicatedevents2" value="0">
                </div>
                <div class="" style="position: relative;">
                    <div  style="min-height: 50px;"><center><div style='width: 70%'><div id="duplicatedeventsChart"></div></div></center></div>
                    <div id="svgContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <h2 id="duplicatedeventsText" x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24" fill="#333">0%</h2>
                    </div>
                <div class="resize-triggers"><div class="expand-trigger"><div style="width: 354px; height: 227px;"></div></div><div class="contract-trigger"></div></div></div>
                <div class="text-center">
                    <p class="mb-1">Increased since Last Week</p>
                    <h4 id="duplicatedeventslast" class="">0</h4>
                    <input type="hidden" id="duplicatedeventslast2" value="0">
                </div>
                </div>
            </div>
            <?php if($_SESSION['userlevel'] > 8){?></a><?php }?>
        </div>

        <div class="col " draggable="true">
            <?php if($_SESSION['userlevel'] > 8){?><a class="nodecoration" href="event/editprice"><?php }?>
            <div class="card card-special rounded-4">
                <div class="card-body">
                <div class="text-center">
                    <p class="mb-1">Price Errors</p>
                    <h4 id="priceerrors" class="">0</h4>
                    <input type="hidden" id="priceerrors2" value="0">
                </div>
                <div class="" style="position: relative;">
                    <div  style="min-height: 50px;"><center><div style='width: 70%'><div id="priceerrorsChart"></div></div></center></div>
                    <div id="svgContainer" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <h2 id="priceerrorsText" x="50%" y="50%" text-anchor="middle" dy=".3em" font-size="24" fill="#333">0%</h2>
                    </div>
                <div class="resize-triggers"><div class="expand-trigger"><div style="width: 354px; height: 227px;"></div></div><div class="contract-trigger"></div></div></div>
                <div class="text-center">
                    <p class="mb-1">Increased since Last Week</p>
                    <h4 id="priceerrorslast" class="">0</h4>
                    <input type="hidden" id="priceerrorslast2" value="0">
                </div>
                </div>
            </div>
            <?php if($_SESSION['userlevel'] > 8){?></a><?php }?>
        </div>
    </div>

    <div class="col-12 col-lg-12 col-xl-12 d-flex " draggable="true">
        <div class="card card-special rounded-4 w-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                <h6 class="mb-0">Events By Countries</h6>
                </div>
                <div id="map" style="height: 242px;"></div>
            </div>
        </div>
    </div>
</div>


<script>
    // silent load content
    $(document).ready(function() {

        $.ajax({
            type: "POST",
            url: "tables/cities.php",
            success: function(data){
                try {
                    

                    // Parse the JSON data
                    var cities = data;

                    // Initialize an object to store country codes and colors
                    var countryColors = {};
                    var countryIds = {};

                    // Iterate over each city data
                    cities.forEach(function (city) {
                        // Set the color for the country code
                        countryColors[city.code] = city.color;
                        // Set the ID for the country code
                        countryIds[city.code] = city.id;
                    });

                    $('#map').vectorMap({
                    map:'world_mill_en',
                    backgroundColor: 'transparent',
                    borderColor: '#818181',
                    borderOpacity: 0.25,
                    regionStyle : {
                    initial : {
                    fill : '#becbd4'
                    }
                    },

                    onRegionClick: function(event, code){
                        code = code.toUpperCase();

                        // Get the ID based on the clicked code
                        var cityId = countryIds[code];
                        // Check if city ID exists
                        if (cityId) {
                            // Redirect to the city edit page using the city ID
                            window.location = 'cities/edit/' + cityId;
                        }
                    },
                    series: {
                        regions: [{
                            // Use the country colors from the data
                            values: countryColors
                        }]
                    }

                });
                } catch (error) {
                    console.error('Error parsing JSON data:', error);
                }
                
            }
        });
        

        let greenColor = '#4CAF50'; // Green color for 0% Used
        let yellowColor = '#FFC107'; // Yellow color for 5-10% Used
        let redColor = '#FF5722'; // Red color for >10% Used
        let lightgreenColor = '#8BC34A'; // Green color for 0% Used
        let lightyellowColor = '#FFD54F'; // Yellow color for 5-10% Used
        let lightredColor = '#FF8A65'; // Red color for >10% Used
        
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
                    //durations
                    $('#durations2').load('<?php echo $url; ?>tables/totals.php #durations', function() {
                        // Get the current count
                        var currentCnt = parseInt($(this).text());
                        // Get the current count
                        var percentage = currentCnt !== 0 ? (currentCnt / currentCount) * 100 : 0;
                        var usedColor = percentage === 0 ? greenColor : (percentage <= 10 ? yellowColor : redColor);
                        var unusedColor = percentage === 0 ? lightgreenColor : (percentage <= 10 ? lightyellowColor : lightredColor);
                        // Animate the counting from current count to the new count
                        // Update the content of #coursecountlast after the animation is complete
                        $(this).text(currentCnt.toLocaleString());
                        var options = {
                            chart: {
                                type: 'donut'
                            },
                            series: [percentage,100-percentage],
                            labels: ['Need Fix', 'All Events'], // Define labels for the legend
                            dataLabels: {
                                enabled: false, // Enable dataLabels
                                // formatter: function (val, opts) {
                                //     return opts.seriesIndex === 0 ? val.toFixed(2) + '%' : ''; // Show dataLabel only for the "Used" segment
                                // },
                            },
                            tooltip: {
                                enabled: false // Disable tooltip to hide data values
                            },
                            legend: {
                                show: false // Hide the legend
                            },
                            colors: [usedColor, unusedColor],
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '90%' // Adjust the size of the donut hole
                                    }
                                }
                            },
                            stroke: {
                                show: false, // Hide the border
                                width: 0,    // Set the border width to 0
                                colors: undefined // Clear any existing border colors
                            }
                        }
                        var chart = new ApexCharts(document.querySelector("#durationsChart"), options);
                        chart.render();
                        // Update the percentage text in the middle of the plot
                        $("#durationsText").text(percentage.toFixed(2) + "%");
                        // Animate the counting from current count to the new count
                        $('#durations').animate({ count: currentCnt }, {
                            duration: 1000,
                            step: function() {
                                // Update the content of #coursecount during the animation
                                $(this).text(Math.round(this.count).toLocaleString());
                            },
                            complete: function() {
                                // Update the content of #coursecount after the animation is complete
                                $(this).text(currentCnt.toLocaleString());
                               
                            }
                        });
                    });
                    //old events
                    $('#oldevents2').load('<?php echo $url; ?>tables/totals.php #oldevents', function() {
                        // Get the current count
                        var currentCnt = parseInt($(this).text());
                        // Get the current count
                        var percentage = currentCnt !== 0 ? (currentCnt / currentCount) * 100 : 0;

                        var usedColor = percentage === 0 ? greenColor : (percentage <= 10 ? yellowColor : redColor);
                        var unusedColor = percentage === 0 ? lightgreenColor : (percentage <= 10 ? lightyellowColor : lightredColor);
                        // Animate the counting from current count to the new count
                        // Update the content of #coursecountlast after the animation is complete
                        $(this).text(currentCnt.toLocaleString());
                        var options = {
                            chart: {
                                type: 'donut'
                            },
                            series: [percentage,100-percentage],
                            labels: ['Need Fix', 'All Events'], // Define labels for the legend
                            dataLabels: {
                                enabled: false, // Enable dataLabels
                                // formatter: function (val, opts) {
                                //     return opts.seriesIndex === 0 ? val.toFixed(2) + '%' : ''; // Show dataLabel only for the "Used" segment
                                // },
                            },
                            tooltip: {
                                enabled: false // Disable tooltip to hide data values
                            },
                            legend: {
                                show: false // Hide the legend
                            },
                            colors: [usedColor, unusedColor],
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '90%' // Adjust the size of the donut hole
                                    }
                                }
                            },
                            stroke: {
                                show: false, // Hide the border
                                width: 0,    // Set the border width to 0
                                colors: undefined // Clear any existing border colors
                            }
                        }
                        var chart = new ApexCharts(document.querySelector("#oldeventsChart"), options);
                        chart.render();
                        // Update the percentage text in the middle of the plot
                        $("#oldeventsText").text(percentage.toFixed(2) + "%");
                        // Animate the counting from current count to the new count
                        $('#oldevents').animate({ count: currentCnt }, {
                            duration: 1000,
                            step: function() {
                                // Update the content of #coursecount during the animation
                                $(this).text(Math.round(this.count).toLocaleString());
                            },
                            complete: function() {
                                // Update the content of #coursecount after the animation is complete
                                $(this).text(currentCnt.toLocaleString());
                               
                            }
                        });
                    });
                    //duplicated events
                    $('#duplicatedevents2').load('<?php echo $url; ?>tables/totals.php #duplicatedevents', function() {
                        // Get the current count
                        var currentCnt = parseInt($(this).text());
                        // Get the current count
                        var percentage = currentCnt !== 0 ? (currentCnt / currentCount) * 100 : 0;

                        var usedColor = percentage === 0 ? greenColor : (percentage <= 10 ? yellowColor : redColor);
                        var unusedColor = percentage === 0 ? lightgreenColor : (percentage <= 10 ? lightyellowColor : lightredColor);
                        // Animate the counting from current count to the new count
                        // Update the content of #coursecountlast after the animation is complete
                        $(this).text(currentCnt.toLocaleString());
                        var options = {
                            chart: {
                                type: 'donut'
                            },
                            series: [percentage,100-percentage],
                            labels: ['Need Fix', 'All Events'], // Define labels for the legend
                            dataLabels: {
                                enabled: false, // Enable dataLabels
                                // formatter: function (val, opts) {
                                //     return opts.seriesIndex === 0 ? val.toFixed(2) + '%' : ''; // Show dataLabel only for the "Used" segment
                                // },
                            },
                            tooltip: {
                                enabled: false // Disable tooltip to hide data values
                            },
                            legend: {
                                show: false // Hide the legend
                            },
                            colors: [usedColor, unusedColor],
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '90%' // Adjust the size of the donut hole
                                    }
                                }
                            },
                            stroke: {
                                show: false, // Hide the border
                                width: 0,    // Set the border width to 0
                                colors: undefined // Clear any existing border colors
                            }
                        }
                        var chart = new ApexCharts(document.querySelector("#duplicatedeventsChart"), options);
                        chart.render();
                        // Update the percentage text in the middle of the plot
                        $("#duplicatedeventsText").text(percentage.toFixed(2) + "%");
                        // Animate the counting from current count to the new count
                        $('#duplicatedevents').animate({ count: currentCnt }, {
                            duration: 1000,
                            step: function() {
                                // Update the content of #coursecount during the animation
                                $(this).text(Math.round(this.count).toLocaleString());
                            },
                            complete: function() {
                                // Update the content of #coursecount after the animation is complete
                                $(this).text(currentCnt.toLocaleString());
                               
                            }
                        });
                    });

                    //duplicated events
                    $('#priceerrors2').load('<?php echo $url; ?>tables/priceerror.php #priceerrors', function() {
                        // Get the current count
                        var currentCnt = parseInt($(this).text());
                        // Get the current count
                        var percentage = currentCnt !== 0 ? (currentCnt / currentCount) * 100 : 0;

                        var usedColor = percentage === 0 ? greenColor : (percentage <= 10 ? yellowColor : redColor);
                        var unusedColor = percentage === 0 ? lightgreenColor : (percentage <= 10 ? lightyellowColor : lightredColor);
                        // Animate the counting from current count to the new count
                        // Update the content of #coursecountlast after the animation is complete
                        $(this).text(currentCnt.toLocaleString());
                        var options = {
                            chart: {
                                type: 'donut'
                            },
                            series: [percentage,100-percentage],
                            labels: ['Need Fix', 'All Events'], // Define labels for the legend
                            dataLabels: {
                                enabled: false, // Enable dataLabels
                                // formatter: function (val, opts) {
                                //     return opts.seriesIndex === 0 ? val.toFixed(2) + '%' : ''; // Show dataLabel only for the "Used" segment
                                // },
                            },
                            tooltip: {
                                enabled: false // Disable tooltip to hide data values
                            },
                            legend: {
                                show: false // Hide the legend
                            },
                            colors: [usedColor, unusedColor],
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '90%' // Adjust the size of the donut hole
                                    }
                                }
                            },
                            stroke: {
                                show: false, // Hide the border
                                width: 0,    // Set the border width to 0
                                colors: undefined // Clear any existing border colors
                            }
                        }
                        var chart = new ApexCharts(document.querySelector("#priceerrorsChart"), options);
                        chart.render();
                        // Update the percentage text in the middle of the plot
                        $("#priceerrorsText").text(percentage.toFixed(2) + "%");
                        // Animate the counting from current count to the new count
                        $('#priceerrors').animate({ count: currentCnt }, {
                            duration: 1000,
                            step: function() {
                                // Update the content of #coursecount during the animation
                                $(this).text(Math.round(this.count).toLocaleString());
                            },
                            complete: function() {
                                // Update the content of #coursecount after the animation is complete
                                $(this).text(currentCnt.toLocaleString());
                               
                            }
                        });
                    });
                }
            });
        });

        $('#priceerrorslast2').load('<?php echo $url; ?>tables/priceerror.php #priceerrorslast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#priceerrorslast').animate({ count: currentCount }, {
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

        

        $('#durationslast2').load('<?php echo $url; ?>tables/totals.php #durationslast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#durationslast').animate({ count: currentCount }, {
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

        $('#oldeventslast2').load('<?php echo $url; ?>tables/totals.php #oldeventslast', function() {
            // Get the current count
            var currentCount = parseInt($(this).text());

            // Animate the counting from current count to the new count
            $('#oldeventslast').animate({ count: currentCount }, {
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

    // const sortable = new Sortable(document.querySelector('.sortable-container'), {
    //     animation: 150,
    //     ghostClass: 'dragging',
    //     chosenClass: 'chosen',
    //     dragClass: 'drag',
    //     handle: '.dragable'
    // });
</script>