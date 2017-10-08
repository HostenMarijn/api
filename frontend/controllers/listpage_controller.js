(function(){
  "use strict";

  var listpage_controller = function($scope, bear_service, cms_service) {


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

    cms_service.getContentForPage(1).then(function(response) {
      
      $.each(response, function(i, content) {
        $('[data-t-id="' + content.id  +'"]').html(content.text);
      });
    });

  };

  angular.module("app").controller("listpage_controller", ["$scope", "bear_service", "cms_service", listpage_controller]);
})();
