(function(){
  "use strict";

  var login_controller = function($scope, $location, user_service) {

    $scope.username = "marijn";
    $scope.password = "12345";

    $scope.login = function() {

      user_service.login($scope.username, sha256($scope.password)).then(function(response) {

        $location.path(response.redirect_url);
      });
    };
  };

  angular.module("app").controller("login_controller", ["$scope", "$location", "user_service", login_controller]);
})();
