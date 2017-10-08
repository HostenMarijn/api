<!DOCTYPE html>
<html class="no-js" ng-app="app">
<head>
    <meta charset="utf-8">
    <base href="/">
    <title>Bears</title>

    <link rel="stylesheet" href="views/public/libs/fontawesome/font-awesome.min.css"> <!-- font-awesome css -->
    <link rel="stylesheet" href="views/public/libs/foundation/foundation.min.css"> <!-- foundation css -->
    <!--<link rel="stylesheet" href="views/public/css/reset.css">--> <!-- eigen stijlen -->
    <link rel="stylesheet" href="views/public/css/style.min.css"> <!-- eigen stijlen -->
</head>
<body>

<div class="main">
    <div ng-view></div>
</div>

<script src="views/public/libs/sha256.min.js"></script>

<script src="views/public/libs/angular/angular.min.js"></script><!-- angular script -->
<script src="views/public/libs/angular/angular-route.min.js"></script> <!-- angular route script -->
<script src="views/public/libs/angular/angular-cookies.min.js"></script>

<script src="views/public/libs/jquery/jquery.js"></script> <!-- jquery script -->
<script src="views/public/libs/foundation/foundation.min.js"></script> <!-- foundation script -->
<script type="text/javascript">$(document).foundation();</script> <!-- activate foundation -->

<script src="views/public/script/script.min.js"></script> <!-- gecompileerde code (controllers, services, ...) -->

</body>
</html>
