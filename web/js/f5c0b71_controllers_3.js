var wordsTestApp = angular.module('wordsTestApp', ['ui.bootstrap', 'ngAnimate']);

wordsTestApp.controller('WordsTestCntrl', function ($scope, $http) {
    
    $scope.isTestStarted = false;
    $scope.userName = '';
    
    $scope.beginTest = function() {
      $scope.isTestStarted = true;
      $http.post('/startTest', {'user_name': $scope.userName}).success(function(data) {
        //alert(data);
      }).error($scope.httpError);
    };
    
    $scope.finishTest = function() {
      
    };
    
    $scope.getQuestion = function() {
      
    };
    
    $scope.saveQuestionResult = function() {
      
    };
    
    $scope.httpError = function(data) {
      alert('Возникла ошибка - ' + data);
    }
});