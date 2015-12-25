var wordsTestApp = angular.module('wordsTestApp', ['ui.bootstrap', 'ngAnimate']);

wordsTestApp.controller('WordsTestCntrl', function ($scope, $http, $timeout) {
    
    var maxQuestionErrorsCount = 2;
    
    $scope.isTestStarted = false;
    $scope.isTestFinished = false;
    $scope.isLoading = false;
    
    $scope.userName = '';
    $scope.userNameError = '';
    
    $scope.answersCount = 0;
    $scope.errorsCount = 0;
    
    $scope.currentWordId = 0;
    $scope.currentWord = '';
    $scope.currentTranslate = '';
    $scope.translations = [],
    $scope.questionError = '';
    
    $scope.isValidAnswer = false;
    $scope.isTryAgain = false;
    
    //init test method
    $scope.beginTest = function() {
      $scope.isLoading = true;
      $http.post('/startTest', {'user_name': $scope.userName}).success(function(data) {
        $timeout(function() {
          $scope.isLoading = false;
          if (data['success']) {
            $scope.isTestStarted = true;
            $scope.getQuestion();
          } else if (data['error']) {
            switch (data['error']) {
              case 'Invalid name':
                $scope.userNameError = 'Неправильное имя';
                break;
            }
          }
        }, 200);
      }).error($scope.httpError);
    };
    
    //end test method
    $scope.finishTest = function() {
      $scope.isLoading = true;
      $http.post('/finishTest', {'user_name': $scope.userName}).success(function(data) {
        $timeout(function() {
          $scope.isLoading = false;
          $scope.isTestFinished = true;
          $scope.currentWordId = 0;
          $scope.answersCount = data['score'];
          $scope.errorsCount = data['errors_count'];
        }, 200);
      }).error($scope.httpError);      
    };
    
    //get first or next question
    $scope.getQuestion = function() {
      $scope.questionError = '';
      $scope.isLoading = true;
      $http.post('dict/getQuestion', {'word_id': $scope.currentWordId}).success(function(data) {
        $timeout(function() {
          $scope.isLoading = false;
          if (data['finish']) {
            $scope.finishTest();
          } else if (data['word_id']) {
            $scope.currentWordId = data['word_id'];
            $scope.currentWord = data['word'];
            $scope.currentTranslate = '';
            $scope.translations = data['translations'];
          } else {
            $scope.httpError(data);
          }
        }, 200);
      }).error($scope.httpError);
    };
    
    //save result of the current question
    $scope.saveQuestionResult = function() {
      $scope.isLoading = true;
      $http.post('dict/saveQuestionResult', 
       {'word_id': $scope.currentWordId, 'translate' : $scope.currentTranslate}).success(function(data) {
        $timeout(function() {
          $scope.isLoading = false;
          if (data['success']) {
            $scope.answersCount++;
            $scope.isTryAgain = false;
          } else if (data['error']) {
            switch (data['error']) {
              case 'wrong data':
                $scope.questionError = 'Неправильные данные. Очень странно...';
                break;
              case 'invalid answer':
                $scope.errorsCount++;
                
                //if too many errors finish test
                if ($scope.errorsCount > maxQuestionErrorsCount) {
                  $scope.finishTest();
                  return false;
                }
                
                //if user can try to answer one more time
                if (!$scope.isTryAgain) {
                  $scope.questionError = 'Ответ неправильный. Попытайтесь еще раз';
                  $scope.isTryAgain = true;
                  return false;
                } else {
                  $scope.isTryAgain = false;
                }
              
            }
          }
          //load next question
          $scope.getQuestion();
        }, 200);
      }).error($scope.httpError);      
    };
    
    //handle http error - 404, 500 etc
    $scope.httpError = function(data) {
      $scope.isLoading = false
      alert('Возникла ошибка - ' + data);
    }
});