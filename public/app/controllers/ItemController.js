app.controller('AdminController', function ($scope, $http) {

  $scope.pools = [];

});

app.controller('ItemController', function (dataFactory, $scope, $http) {

  $scope.data = [];
  $scope.libraryTemp = {};
  $scope.totalItemsTemp = {};

  $scope.totalItems = 0;
  $scope.pageChanged = function (newPage) {
    getResultsPage(newPage);
  };

  getResultsPage(1);

  function getResultsPage(pageNumber) {
    if (!$.isEmptyObject($scope.libraryTemp)) {
      dataFactory.httpRequest('/items?search=' + $scope.searchText + '&page=' + pageNumber).then(function (data) {
        $scope.data = data.data;
        $scope.totalItems = data.total;
      });
    } else {
      dataFactory.httpRequest('/items?page=' + pageNumber).then(function (data) {
        $scope.data = data.data;
        $scope.totalItems = data.total;
      });
    }
  }

  $scope.searchDB = function () {
    if ($scope.searchText.length >= 3) {
      if ($.isEmptyObject($scope.libraryTemp)) {
        $scope.libraryTemp = $scope.data;
        $scope.totalItemsTemp = $scope.totalItems;
        $scope.data = {};
      }
      getResultsPage(1);
    } else {
      if (!$.isEmptyObject($scope.libraryTemp)) {
        $scope.data = $scope.libraryTemp;
        $scope.totalItems = $scope.totalItemsTemp;
        $scope.libraryTemp = {};
      }
    }
  }

  $scope.scrape = function () {
    var result = confirm("Are you sure scrape from Craigslist?\nIf Yes System will TRUNCATE Item table and Curl again");
    if (result) {
      dataFactory.httpRequest('items/1', 'DELETE').then(function (data) {
        $scope.data.splice(index, 1);
      });
    }
  }
});