(function(){

  "use strict";

  var app = angular.module("app", ["ngRoute", "ngCookies"]);

  app.config(["$routeProvider", "$locationProvider", function($routeProvider, $locationProvider) {
    $routeProvider
      .when('/', {
        templateUrl: "views/public/pages/listpage.html",
        controller: "listpage_controller"
      })
      .when('/bears/:id', {
        templateUrl: "views/public/pages/detailpage.html",
        controller: "detail_controller"
      })
      .when('/login', {
        templateUrl: "views/public/pages/login.html",
        controller: "login_controller"
      })
      .when('/cms', {
        templateUrl: "views/public/pages/cms.html",
        controller: "cms_controller"
      })
      .otherwise({redirectTo: "/"});

    $locationProvider.html5Mode(true);
  }]);

})();
