(function(){
  "use strict";

  var bear_service = function($http) {

    
    var get_bears = function () {

      return $http.get('/api/bears').then(function(response) {
        return response.data;
      });
    };

    var get_bear_by_id = function(id) {

      return $http.get('/api/bears/' + id).then(function(response) {
        return response.data;
      });

    };

    var create_bear = function(bear) {
      return $http.post('/api/bears', bear).then(function(response) {
        return response.data;
      });
    };

    var delete_bear = function(id) {
      return $http.put('/api/bears/' + id).then(function(response) {
        return response.data;
      });
    };

    return {
      get_bears: get_bears,
      get_bear_by_id: get_bear_by_id,
      create_bear: create_bear,
      delete_bear: delete_bear
    };

  };

   angular.module("app").factory("bear_service", ["$http", bear_service]);


})();
