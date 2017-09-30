(function(){
  "use strict";

  var cms_controller = function($scope, $cookies, $location, user_service) {

    // check for valid token

    var token = $cookies.get("m-token");

    if (!token) $location.path('/login');

    if (token) {

      // check if this token is legit
      user_service.authenticateToken(token).then(function(response) {
        console.log("authenticateToken:", response);
        if (response.success) {
          // stay on page
        }

        else if (response.error) {
          // redirect
          $location.path(response.redirect_url);
        }
      });
    }


  };

  angular.module("app").controller("cms_controller", ["$scope", "$cookies", "$location", "user_service", cms_controller]);
})();
