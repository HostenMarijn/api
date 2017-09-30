(function(){
  "use strict";

  var listpage_controller = function($scope, bear_service) {


    $scope.bears = [];

    bear_service.get_bears().then(function(bears) {
      $scope.bears = bears;
    });

    $scope.deleteBear = function(bear) {
      bear_service.delete_bear(bear.id).then(function(response) {

        if (response.msg === "success") {
          var index = $scope.bears.indexOf(bear);


          if (index > -1) {
            $scope.bears.splice(index, 1);
          }
        }
      });
    };

  };

  angular.module("app").controller("listpage_controller", ["$scope", "bear_service", listpage_controller]);
})();
