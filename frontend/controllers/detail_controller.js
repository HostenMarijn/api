(function(){

  "use strict";

  var detail_controller = function($scope, $routeParams, bear_service) {


    bear_service.get_bear_by_id($routeParams.id).then(function(bear) {

      $scope.currentBear = bear;
    });
  };

   angular.module("app").controller("detail_controller", ["$scope", "$routeParams", "bear_service", detail_controller]);

})();
