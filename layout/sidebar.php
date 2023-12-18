    <!--start sidebar -->
    <aside class="sidebar-wrapper" data-simplebar="true">
        <div class="sidebar-header">
            <div>
                <img src="<?php echo $url; ?>assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
            </div>
            <div>
                <h4 class="logo-text"><?php echo $appname; ?></h4>
            </div>
            <div class="toggle-icon ms-auto"> <i class="bi bi-list"></i>
            </div>
        </div>
        <!--navigation-->
        <ul class="metismenu" id="menu"> 
            <li>
                <a  aria-expanded="false" href="<?php echo $url; ?>">
                    <div class="parent-icon">
                        <i class="bi bi-house-fill"></i>
                    </div>
                    <div class="menu-title">Home</div>
                </a>
            </li>
            <?php if (isset($db_id)){ ?>
                <?php if ($_SESSION['userlevel'] > 2 ) { ?>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                        <i class="lni lni-archive"></i>
                    </div>
                    <div class="menu-title">Categories</div>
                </a>
                <ul>
                    <li>
                        <a href="<?php echo $url; ?>categories/view"><i class="bi bi-circle"></i>View</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                        <i class="  lni lni-map-marker"></i>
                    </div>
                    <div class="menu-title">Cities</div>
                </a>
                <ul>
                    <li>
                        <a href="<?php echo $url; ?>cities/view"><i class="bi bi-circle"></i>View</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                        <i class="lni lni-certificate"></i>
                    </div>
                    <div class="menu-title">Courses</div>
                </a>
                <ul>
                    <li>
                        <a href="<?php echo $url; ?>courses/view"><i class="bi bi-circle"></i>View</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon">
                        <i class="lni lni-calendar"></i>
                    </div>
                    <div class="menu-title">Events</div>
                </a>
                <ul>
                    <li>
                        <a href="<?php echo $url; ?>event/view"><i class="bi bi-circle"></i>View</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>event/editprice"><i class="bi bi-circle"></i>Edit Events Prices</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>event/fixold"><i class="bi bi-circle"></i>Fix old events dates</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>event/fixduplicate"><i class="bi bi-circle"></i>Fix Duplicate events</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>event/addmultiple/"><i class="bi bi-circle"></i>Add Missing Events</a>
                    </li>
                    <?php if ($_SESSION['userlevel'] > 9 ) { ?>
                    <li>
                        <a href="<?php echo $url; ?>event/fixdurations"><i class="bi bi-circle"></i>Fix Durations</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="lni lni-calendar"></i></div>
                    <div class="menu-title">Website Keywords</div>
                </a>
                <ul>
                    <?php if ($_SESSION['userlevel'] > 9 ) { ?>
                    <li>
                        <a href="<?php echo $url; ?>sitekeywords/view"><i class="bi bi-circle"></i>View</a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
                <?php } ?>
            <?php } ?>
            <?php if ($_SESSION['userlevel'] > 9 ) { ?>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="lni lni-seo"></i></div>
                    <div class="menu-title">SEO</div>
                </a>
                <ul>
                    <li>
                        <a href="<?php echo $url; ?>seo/view"><i class="bi bi-circle"></i>View</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>seo/edit"><i class="bi bi-circle"></i>update</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="lni lni-tag"></i></div>
                    <div class="menu-title">Keywords</div>
                </a>
                <ul>
                    <li>
                        <a href="<?php echo $url; ?>keyword/view?filterby=4"><i class="bi bi-circle"></i>View keywords</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>keyword/viewall"><i class="bi bi-circle"></i>View All tags/subtags Tree</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>tags/view"><i class="bi bi-circle"></i>view All keywords</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>keyword/tags"><i class="bi bi-circle"></i>view tags/Keywords</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>keyword/fix"><i class="bi bi-circle"></i>fix tags</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>keyword/ai2"><i class="bi bi-circle"></i>AI</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>keyword/aikeysubtag"><i class="bi bi-circle"></i>AI Keywords SubTags</a>
                    </li>
                    <li>
                        <a href="<?php echo $url; ?>keyword/keyword_category_view"><i class="bi bi-circle"></i>AI Keywords SubTags Category</a>
                    </li>
                </ul>
            </li>
            <?php } ?>
            <?php 
            if ($userlevel > 9) { ?>
            <li class="menu-label">System</li>
            <li>
                <a href="<?php echo $url; ?>users/view">
                    <div class="parent-icon"><i class="bi bi-person-lines-fill"></i></div>
                    <div class="menu-title">Users Management</div>
                </a>
            </li>
            <?php } ?>
        </ul>
        <!--end navigation-->
    </aside>
    <!--end sidebar -->