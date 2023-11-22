<?php
require 'vendor/autoload.php'; 
$appname = "Course Factory";
$view = ""; // This variable will hold the path to the view file
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('POST', '/courses/update', function() {
        return 'tables/courses/update.php'; // Return the path as a string
   });

    $r->addRoute('GET', '/courses/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/courses/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/courses/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/courses/delete.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/courses/view', function() {
        return 'tables/courses/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/courses/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/courses/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/courses/edit', function() {
        return 'tables/courses/edit.php'; // Return the path as a string
    });


    $r->addRoute('GET', '/course_main/addmultyple/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/addmultyple.php'; // Return the path as a string
    });
 

    $r->addRoute('POST', '/course_main/updatemultiple', function() {
        return 'tables/course_main/updatemultiple.php'; // Return the path as a string
    });

    
    $r->addRoute('POST', '/course_main/update', function() {
        return 'tables/course_main/update.php'; // Return the path as a string
   });

    $r->addRoute('GET', '/course_main/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/course_main/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/delete.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/course_main/view', function() {
        return 'tables/course_main/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/course_main/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/course_main/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/course_main/edit', function() {
        return 'tables/course_main/edit.php'; // Return the path as a string
    });




    ////////////////////////////////   event 

    $r->addRoute('POST', '/event/update', function() {
        return 'tables/event/update.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/event/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/event/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/event/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/event/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addmultiple/', function() {
        return 'tables/event/addmultiple.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addmultiple/{cid:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $_GET['cid'] = $cid;
         $_GET['start'] = $cid; 
        return 'tables/event/addmultiple.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addmultipleforcourse/{cid:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $_GET['cid'] = $cid;
         $_GET['start'] = $cid; 
        return 'tables/event/addmultipleold.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addmultiple/{cid:[0-9]+}/{limit:[0-9]+}', function($params) {
        $cid = $params['cid']; // Extract the id from the params array
        $limit = $params['limit']; // Extract the id from the params array
        $_GET['start'] = $cid; 
        $_GET['limit'] = $limit; 
        return 'tables/event/addmultiple.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addmultiplecron/', function() {
        return 'tables/event/addmultiplecron.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addmultiplecron/{limit:[0-9]+}', function($params) {
        $limit = $params['cid']; // Extract the id from the params array
        $_GET['limit'] = $limit; 
        return 'tables/event/addmultiplecron.php'; // Return the path as a string
    });
    
    $r->addRoute('POST', '/event/updatemultiple', function() {
        return 'tables/event/updatemultiple.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/event/updateduplicate', function() {
        return 'tables/event/updateduplicate.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/view', function() {
        return 'tables/event/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/editprice', function() {
        return 'tables/event/editprice.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/event/updateprice', function() {
        return 'tables/event/updateprice.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/event/deleteprice', function() {
        return 'tables/event/deleteprice.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/event/updateDuration', function() {
        return 'tables/event/updateDuration.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/updateDuration/{id:[0-9]+}/{duration:[0-9]+}', function($params) {
        $_GET['id'] = $params['id'];
        $_GET['newDuration'] = $params['duration'];
        return 'tables/event/updateDuration.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/event/fixold', function() {
        return 'tables/event/fixold.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/fixduplicate', function() {
        return 'tables/event/fixduplicate.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/fixdurations', function() {
        return 'tables/event/fixdurations.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/fixdurations/auto', function() {
        $_GET['auto'] = 'true';
        return 'tables/event/fixdurations.php'; // Return the path as a string
    });
    
    $r->addRoute('GET', '/event/edit/{c_id:[0-9]+}', function($params) {
        $id = $params['c_id']; // Extract the id from the params array
        $_GET['c_id'] = $id; 
        return 'tables/event/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/edit/{course:[0-9]+}/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        $_GET['course'] = $params['course']; 
        return 'tables/event/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/edit', function() {
        return 'tables/event/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/event/addeventtocourse', function() {
        return 'tables/event/addeventtocourse.php'; // Return the path as a string
    });

    ///////////////////////////////////////////////

    $r->addRoute('POST', '/tags/update', function() {
        return 'tables/tags/update.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/tags/view', function() {
        return 'tables/tags/view.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/tags/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/tags/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/tags/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/tags/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/tags/edit', function() {
        return 'tables/tags/edit.php'; // Return the path as a string
    }); 
    $r->addRoute('GET', '/subtags/addmultyple', function() {
        return 'tables/subtags/addmultyple.php'; // Return the path as a string
    }); 

    $r->addRoute('POST', '/subtags/addmultypleupdate', function() {
        return 'tables/subtags/addmultypleupdate.php'; // Return the path as a string
    });


    $r->addRoute('POST', '/subtags/update', function() {
        return 'tables/subtags/update.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/subtags/view', function() {
        return 'tables/subtags/view.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/subtags/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/subtags/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/subtags/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/subtags/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/subtags/edit', function() {
        return 'tables/subtags/edit.php'; // Return the path as a string
    }); 

    $r->addRoute('GET', '/cities/view', function() {
        return 'tables/cities/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/cities/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/cities/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/cities/edit', function() {
        return 'tables/cities/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/cities/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/cities/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/cities/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/cities/trash.php'; // Return the path as a string
    });

    $r->addRoute('POST', '/cities/update', function() {
        return 'tables/cities/update.php'; // Return the path as a string
   });

    $r->addRoute('POST', '/categories/update', function() {
         return 'tables/categories/update.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/categories/view', function() {
        return 'tables/categories/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/categories/trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/categories/trash.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/categories/delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/categories/delete.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/categories/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/categories/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/categories/edit', function() {
        return 'tables/categories/edit.php'; // Return the path as a string
    }); 
    $r->addRoute('GET', '/users/view', function() {
        return 'tables/users/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/users/edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/users/edit.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/users/edit', function() {
        return 'tables/users/edit.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/users/update', function() {
        return 'tables/users/update.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/seo/view', function() {
        return 'tables/seo/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/seo/edit', function() {
        return 'tables/seo/edit.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/seo/update', function() {
        return 'tables/seo/update.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/seo/functions', function() {
        return 'tables/seo/functions.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/profile/view', function() {
        return 'tables/profile/view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/profile/edit', function() {
        return 'tables/profile/edit.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/profile/update', function() {
        return 'tables/profile/update.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/profile/upload', function() {
        return 'tables/profile/upload.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/keyword/keyword_category_add_multiple', function() {
        return 'tables/keyword/keyword_category_add_multiple.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/keyword/deleteall_relation', function() {
        return 'tables/keyword/deleteall_relation.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/keyword/subtag', function() {
        return 'tables/keyword/subtag.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/keyword/keyword_category_add_multiple_update', function() {
        return 'tables/keyword/keyword_category_add_multiple_update.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/keyword/keyword_category_update', function() {
        return 'tables/keyword/keyword_category_update.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/keyword/keyword_category_view', function() {
        return 'tables/keyword/keyword_category_view.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/keyword/keyword_category_trash/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/keyword/keyword_category_trash.php'; // Return the path as a string
    });
        $r->addRoute('GET', '/keyword/keyword_category_delete/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/keyword/keyword_category_delete.php'; // Return the path as a string
    });
 
    $r->addRoute('POST', '/keyword/delete_relation', function() {
        return 'tables/keyword/delete_relation.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/keyword/ai3', function() {
        return 'tables/keyword/ai3.php'; // Return the path as a string
    });


    $r->addRoute('GET', '/keyword/keyword_category_edit/{id:[0-9]+}', function($params) {
        $id = $params['id']; // Extract the id from the params array
        $_GET['id'] = $id; 
        return 'tables/keyword/keyword_category_edit.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/keyword/keyword_category_edit', function() {
        return 'tables/keyword/keyword_category_edit.php'; // Return the path as a string
    }); 


    $r->addRoute('GET', '/keyword/ai2', function() {
        return 'tables/keyword/ai2.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/keyword/ai', function() {
        return 'tables/keyword/ai.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/keyword/fix', function() {
        return 'tables/keyword/fix.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/keyword/tags', function() {
        return 'tables/keyword/tags.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/keyword/aikeysubtag', function() {
        return 'tables/keyword/aikeysubtag.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/keyword/view', function() {
        return 'tables/keyword/view.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/keyword/viewall', function() {
        return 'tables/keyword/viewall.php'; // Return the path as a string
    });


    $r->addRoute('GET', '/getimage/{t}/{c}/{col}/{remoteDirectory}/{dir}', function($params) {
        $_GET['t'] = $params['t'];
        $_GET['c'] = $params['c'];
        $_GET['col'] = $params['col'];
        $_GET['dir'] = $params['dir'];
        $_GET['remoteDirectory'] = $params['remoteDirectory'];
        return 'tables/getimage.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/getimageurl/', function($params) {
        $_GET['t'] = $_GET['t'];
        $_GET['c'] = $_GET['c'];
        $_GET['col'] = $_GET['col'];
        $_GET['url'] = $_GET['url'];
        
        return 'tables/getimage.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/getimage/{t}/{url}', function($params) {
        $_GET['t'] = $params['t'];
        $_GET['url'] = $params['url'];
        return 'tables/getimagepost.php'; // Return the path as a string
    });
    $r->addRoute('POST', '/uploadimage/{image}/{remoteDirectory}/{dir}', function($params) {
        $image = $params['image']; // Extract the id from the params array
        $_GET['image'] = $image; 
        $_GET['dir'] = $params['dir'];
        $_GET['remoteDirectory'] = $params['remoteDirectory'];
        return 'tables/uploadimage.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/image/{img}', function($params) {
        return 'tables/uploadimage.php'; // Return the path as a string
    });
    $r->addRoute('GET', '/deleteimage/{image}/{c}/{t}/{remoteDirectory}/{dir}', function($params) {
        $image = $params['image']; // Extract the id from the params array
        $_GET['t'] = $params['t'];
        $_GET['c'] = $params['c'];
        $_GET['image'] = $image; 
        $_GET['dir'] = $params['dir'];
        $_GET['remoteDirectory'] = $params['remoteDirectory'];
        return 'tables/delete_image.php'; // Return the path as a string
    });
    
    $r->addRoute('GET', '/', function() {
        return 'tables/home.php'; // Return the path as a string
    });

    $r->addRoute('GET', '/sitekeywords/view', function() {
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