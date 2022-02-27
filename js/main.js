const app=angular.module('myApp',[])
app.directive('quizpoly',function(){
    return {
        restrict:'AE',
        scope:{},
        templateUrl:'template-quiz.php'
    }
})