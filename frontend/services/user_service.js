(function(){
  "use strict";

  var user_service = function($http) {

    var login = function(login, password) {

      return $http.post('/api/login', {"login": login, "password": password}).then(function(response) {
        return response.data;
      });
    };

    var authenticateToken = function(token) {

      return $http.post('/api/authtoken', {"m-token": token}).then(function (response) {
        return response.data;
      });
    };

    return {
      login: login,
      authenticateToken: authenticateToken
    };

  };

   angular.module("app").factory("user_service", ["$http", user_service]);


})();
