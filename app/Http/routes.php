<?php

require('Routes/website.php');

foreach(File::allFiles(app_path().'/Http/Routes/Site') as $route) {
    require_once $route->getPathname();
}

foreach(File::allFiles(app_path().'/Http/Routes/Api') as $route) {
    require_once $route->getPathname();
}