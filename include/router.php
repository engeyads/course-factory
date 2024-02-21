<?php
require 'vendor/autoload.php'; 
$appname = "Course Factory";
$view = ""; // This variable will hold the path to the view file


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
if($_SERVER['HTTP_HOST'] == 'admint.blackbird-training.com' || $_SERVER['HTTP_HOST'] == 'mercuryt.mercury-training.com'){
    $lcl = '';
}else{
    $lcl = '/';
}
    $r->addRoute('POST', $lcl.'courses/update', function() {
        return 'tables/courses/update.php'; // Return the path as a string
   });

    $r->addRoute('GET', $lcl.'courses/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/courses/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'courses/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/courses/delete.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'courses/view', function() {
        return 'tables/courses/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'courses/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/courses/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'courses/edit', function() {
        return 'tables/courses/edit.php'; // Return the path as a string
    });


    $r->addRoute('GET', $lcl.'course_main/addmultyple/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/addmultyple.php'; // Return the path as a string
    });
 

    $r->addRoute('POST', $lcl.'course_main/updatemultiple', function() {
        return 'tables/course_main/updatemultiple.php'; // Return the path as a string
    });

    
    $r->addRoute('POST', $lcl.'course_main/update', function() {
        return 'tables/course_main/update.php'; // Return the path as a string
   });

    $r->addRoute('GET', $lcl.'course_main/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'course_main/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/delete.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'course_main/view', function() {
        return 'tables/course_main/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'course_main/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'course_main/edit', function() {
        return 'tables/course_main/edit.php'; // Return the path as a string
    });




    ////////////////////////////////   event 

    $r->addRoute('POST', $lcl.'event/update', function() {
        return 'tables/event/update.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'event/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/event/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'event/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/event/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultiple/', function() {
        return 'tables/event/addmultiple.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultiple/{cid:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $_GET['cid'] = $cid;
         $_GET['start'] = $cid; 
        return 'tables/event/addmultiple.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultiplebycourse/{cid:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $_GET['cid'] = $cid;
         $_GET['start'] = $cid; 
        return 'tables/event/addmultipleold.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultipleforcourse/{cid:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $_GET['cid'] = $cid;
         $_GET['start'] = $cid; 
        return 'tables/event/addmultipleold.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultiple/{cid:[0-9]+}/{limit:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $limit = $params['limit']; // Extract the id from the params array
        $_GET['start'] = $cid; 
        $_GET['limit'] = $limit; 
        return 'tables/event/addmultiple.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultiplecron/', function() {
        return 'tables/event/addmultiplecron.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addmultiplecron/{limit:[0-9]+}', function($params) {
        $limit = $params['cid']; // Extract the id from the params array
        $_GET['limit'] = $limit; 
        return 'tables/event/addmultiplecron.php'; // Return the path as a string
    });
    
    $r->addRoute('POST', $lcl.'event/updatemultiple', function() {
        return 'tables/event/updatemultiple.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'event/updateduplicate', function() {
        return 'tables/event/updateduplicate.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/view/{category}/{city}/{upcomming}/{monday}/{y1}/{y2}/{m1}/{m2}/{d1}/{d2}/{class}/{weeks}/{searchfor}', function($params) {
        $category = $params['category']; // Extract the category from the params array
        $city = $params['city']; // Extract the city from the params array
        $upcomming = $params['upcomming']; // Extract the upcomming from the params array
        $monday = $params['monday']; // Extract the monday from the params array
        $y1 = $params['y1']; // Extract the y1 from the params array
        $y2 = $params['y2']; // Extract the y2 from the params array
        $m1 = $params['m1']; // Extract the m1 from the params array
        $m2 = $params['m2']; // Extract the m2 from the params array
        $d1 = $params['d1']; // Extract the d1 from the params array
        $d2 = $params['d2']; // Extract the d2 from the params array
        $class = $params['class']; // Extract the class from the params array
        $weeks = $params['weeks']; // Extract the weeks from the params array
        $searchfor = $params['searchfor']; // Extract the searchfor from the params array
        $_GET['category'] = $category; 
        $_GET['city'] = $city; 
        $_GET['upcomming'] = $upcomming; 
        $_GET['monday'] = $monday; 
        $_GET['y1'] = $y1;
        $_GET['y2'] = $y2;
        $_GET['m1'] = $m1;
        $_GET['m2'] = $m2;
        $_GET['d1'] = $d1;
        $_GET['d2'] = $d2;
        $_GET['class'] = $class;
        $_GET['weeks'] = $weeks;
        if($searchfor != '0'){
            $_GET['searchfor'] = $searchfor;
        }
        return 'tables/event/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/view', function() {
        return 'tables/event/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/editprice', function() {
        return 'tables/event/editprice.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'event/updateprice', function() {
        return 'tables/event/updateprice.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'event/deleteprice', function() {
        return 'tables/event/deleteprice.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'event/updateDuration', function() {
        return 'tables/event/updateDuration.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/updateDuration/{id:[0-9]+}/{duration:[0-9]+}', function($params) {
        $_GET['id'] = $params['id'];
        $_GET['newDuration'] = $params['duration'];
        return 'tables/event/updateDuration.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'event/fixold', function() {
        return 'tables/event/fixold.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/fixduplicate', function() {
        return 'tables/event/fixduplicate.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/fixdurations', function() {
        return 'tables/event/fixdurations.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/fixdurations/auto', function() {
        $_GET['auto'] = 'true';
        return 'tables/event/fixdurations.php'; // Return the path as a string
    });
    
    $r->addRoute('GET', $lcl.'event/edit/{c_id:[0-9]+}', function($params) {
        $id = $params['c_id']; // Extract the id from the params array
        $_GET['c_id'] = $id; 
        return 'tables/event/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/edit/{course:[0-9]+}/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        $_GET['course'] = $params['course']; 
        return 'tables/event/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/edit', function() {
        return 'tables/event/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'event/addeventtocourse', function() {
        return 'tables/event/addeventtocourse.php'; // Return the path as a string
    });

    ///////////////////////////////////////////////

    $r->addRoute('POST', $lcl.'tags/update', function() {
        return 'tables/tags/update.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'tags/view', function() {
        return 'tables/tags/view.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'tags/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/tags/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'tags/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/tags/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'tags/edit', function() {
        return 'tables/tags/edit.php'; // Return the path as a string
    }); 
    $r->addRoute('GET', $lcl.'subtags/addmultyple', function() {
        return 'tables/subtags/addmultyple.php'; // Return the path as a string
    }); 

    $r->addRoute('POST', $lcl.'subtags/addmultypleupdate', function() {
        return 'tables/subtags/addmultypleupdate.php'; // Return the path as a string
    });


    $r->addRoute('POST', $lcl.'subtags/update', function() {
        return 'tables/subtags/update.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'subtags/view', function() {
        return 'tables/subtags/view.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'subtags/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/subtags/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'subtags/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/subtags/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'subtags/edit', function() {
        return 'tables/subtags/edit.php'; // Return the path as a string
    }); 

    $r->addRoute('GET', $lcl.'cities/view', function() {
        return 'tables/cities/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'cities/view/{startpage:[0-9]+}', function($params) {
        $startpage = $params['startpage']; // Extract the id from the params array
        $_GET['startpage'] = $params['startpage'];
        return 'tables/cities/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'cities/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/cities/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'cities/edit', function() {
        return 'tables/cities/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'cities/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/cities/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'cities/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/cities/trash.php'; // Return the path as a string
    });

    $r->addRoute('POST', $lcl.'cities/update', function() {
        return 'tables/cities/update.php'; // Return the path as a string
   });

    $r->addRoute('POST', $lcl.'categories/update', function() {
         return 'tables/categories/update.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'categories/view', function() {
        return 'tables/categories/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'categories/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/categories/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'categories/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/categories/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'categories/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/categories/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'categories/edit', function() {
        return 'tables/categories/edit.php'; // Return the path as a string
    }); 
    $r->addRoute('GET', $lcl.'users/view', function() {
        return 'tables/users/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'users/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/users/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'users/edit', function() {
        return 'tables/users/edit.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'users/update', function() {
        return 'tables/users/update.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'seo/view', function() {
        return 'tables/seo/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'seo/edit', function() {
        return 'tables/seo/edit.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'seo/update', function() {
        return 'tables/seo/update.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'seo/functions', function() {
        return 'tables/seo/functions.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'seo/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/seo/delete.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'profile/view', function() {
        return 'tables/profile/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'profile/edit', function() {
        return 'tables/profile/edit.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'profile/update', function() {
        return 'tables/profile/update.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'profile/upload', function() {
        return 'tables/profile/upload.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'keyword/keyword_category_add_multiple', function() {
        return 'tables/keyword/keyword_category_add_multiple.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'keyword/deleteall_relation', function() {
        return 'tables/keyword/deleteall_relation.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'keyword/subtag', function() {
        return 'tables/keyword/subtag.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'keyword/keyword_category_add_multiple_update', function() {
        return 'tables/keyword/keyword_category_add_multiple_update.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'keyword/keyword_category_update', function() {
        return 'tables/keyword/keyword_category_update.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'keyword/keyword_category_view', function() {
        return 'tables/keyword/keyword_category_view.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'keyword/keyword_category_trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/keyword/keyword_category_trash.php'; // Return the path as a string
    });
        $r->addRoute('GET', $lcl.'keyword/keyword_category_delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/keyword/keyword_category_delete.php'; // Return the path as a string
    });
 
    $r->addRoute('POST', $lcl.'keyword/delete_relation', function() {
        return 'tables/keyword/delete_relation.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'keyword/ai3', function() {
        return 'tables/keyword/ai3.php'; // Return the path as a string
    });


    $r->addRoute('GET', $lcl.'keyword/keyword_category_edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/keyword/keyword_category_edit.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'keyword/keyword_category_edit', function() {
        return 'tables/keyword/keyword_category_edit.php'; // Return the path as a string
    }); 


    $r->addRoute('GET', $lcl.'keyword/ai2', function() {
        return 'tables/keyword/ai2.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'keyword/ai', function() {
        return 'tables/keyword/ai.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'keyword/fix', function() {
        return 'tables/keyword/fix.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'keyword/tags', function() {
        return 'tables/keyword/tags.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'keyword/aikeysubtag', function() {
        return 'tables/keyword/aikeysubtag.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'keyword/view', function() {
        return 'tables/keyword/view.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'keyword/viewall', function() {
        return 'tables/keyword/viewall.php'; // Return the path as a string
    });


    $r->addRoute('GET', $lcl.'getimage/{t}/{c}/{col}/{remoteDirectory}/{dir}', function($params) {
        $_GET['t'] = $params['t'];
        $_GET['c'] = $params['c'];
        $_GET['col'] = $params['col'];
        $_GET['dir'] = $params['dir'];
        $_GET['remoteDirectory'] = $params['remoteDirectory'];
        return 'tables/getimage.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'getimageurl/', function($params) {
        $_GET['t'] = $_GET['t'];
        $_GET['c'] = $_GET['c'];
        $_GET['col'] = $_GET['col'];
        $_GET['url'] = $_GET['url'];
        
        return 'tables/getimage.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'getimage/{t}/{url}', function($params) {
        $_GET['t'] = $params['t'];
        $_GET['url'] = $params['url'];
        return 'tables/getimagepost.php'; // Return the path as a string
    });
    $r->addRoute('POST', $lcl.'uploadimage/{image}/{remoteDirectory}/{dir}', function($params) {
        $image = $params['image']; // Extract the id from the params array
        $_GET['image'] = $image; 
        $_GET['dir'] = $params['dir'];
        $_GET['remoteDirectory'] = $params['remoteDirectory'];
        return 'tables/uploadimage.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'image/{img}', function($params) {
        return 'tables/uploadimage.php'; // Return the path as a string
    });
    $r->addRoute('GET', $lcl.'deleteimage/{image}/{c}/{t}/{remoteDirectory}/{dir}', function($params) {
        $image = $params['image']; // Extract the id from the params array
        $_GET['t'] = $params['t'];
        $_GET['c'] = $params['c'];
        $_GET['image'] = $image; 
        $_GET['dir'] = $params['dir'];
        $_GET['remoteDirectory'] = $params['remoteDirectory'];
        return 'tables/delete_image.php'; // Return the path as a string
    });
    
    $r->addRoute('GET', $lcl.'', function() {
        return 'tables/home.php'; // Return the path as a string
    });

    $r->addRoute('GET', $lcl.'sitekeywords/view', function() {
        return 'tables/sitekeywords/view.php'; // Return the path as a string
    });
});
$httpMethod = $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];
$basePath = dirname($_SERVER['SCRIPT_NAME']);

if (substr($uri, 0, strlen($basePath)) === $basePath) {
    $uri = substr($uri, strlen($basePath));
}

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        exit('404 Not Found');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed. Allowed methods are: ' . implode(', ', $allowedMethods);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $view = call_user_func($handler, $vars); // Set $view to the returned string
        break;
}
?>