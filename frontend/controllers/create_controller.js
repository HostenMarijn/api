(function(){

  "use strict";

  var create_controller = function($scope, bear_service) {

    $scope.newBear = {
      name: '',
      home: ''
    };

    $scope.createBear = function() {

      if ($scope.newBear.name && $scope.newBear.home) {
        bear_service.create_bear($scope.newBear).then(function(bear) {

          if (bear.id && bear.name && bear.home) {
            $scope.bears.push(bear);
            $scope.newBear = {name: '', home: ''};
          }
        });
      }
    };
  };

  angular.module("app").controller("create_controller", ["$scope", "bear_service", create_controller]);

})();
