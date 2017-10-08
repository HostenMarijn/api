(function(){
  "use strict";

  var cms_controller = function($scope, $cookies, $location, user_service, cms_service) {

    $scope.homeTexts = [];
    $scope.detailTexts = [];
    $scope.showInput = false;

    // check for valid token
    var token = $cookies.get("m-token");
    if (!token) $location.path('/login');
    if (token) {

      // check if this token is legit
      user_service.authenticateToken(token).then(function(response) {

        if (response.success) {
          // get all text content? or per page?
          init();
        }

        else if (response.error) {
          // redirect
          $location.path(response.redirect_url);
        }
      });
    }

    var init = function() {

      cms_service.getContentForPage(1).then(function(response) {

        $scope.homeTexts = response;
      });

      cms_service.getContentForPage(2).then(function(response) {

        $scope.detailTexts = response;
      });
    };

    $scope.saveText = function(t) {
      if (!t.id) return;
      cms_service.updateContentForTextId(t.id, t.text).then(function(response) {
        console.log(response);
      });
    };

  };

  angular.module("app").controller("cms_controller", ["$scope", "$cookies", "$location", "user_service", "cms_service", cms_controller]);
})();
