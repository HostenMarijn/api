(function(){
  "use strict";

  var cms_service = function($http) {

    var getAllContent = function() {

      return $http.get('/api/cms/pages').then(function(response) {
        return response.data;
      });
    };

    var getContentForPage = function(pageId) {

      return $http.get('/api/cms/page/' + pageId).then(function(response) {
        return response.data;
      });
    };

    var updateContentForTextId = function(id, text) {

      return $http.post('/api/cms/page/' + id, {"text": text}).then(function (response) {
        return response.data;
      });
    };

    return {
      getAllContent: getAllContent,
      getContentForPage: getContentForPage,
      updateContentForTextId: updateContentForTextId
    };

  };

   angular.module("app").factory("cms_service", ["$http", cms_service]);


})();
