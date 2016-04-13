/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 1.9.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.9/admin/
*/

var blue		= '#348fe2',
    blueLight	= '#5da5e8',
    blueDark	= '#1993E4',
    aqua		= '#49b6d6',
    aquaLight	= '#6dc5de',
    aquaDark	= '#3a92ab',
    green		= '#00acac',
    greenLight	= '#33bdbd',
    greenDark	= '#008a8a',
    orange		= '#f59c1a',
    orangeLight	= '#f7b048',
    orangeDark	= '#c47d15',
    dark		= '#2d353c',
    grey		= '#b6c2c9',
    purple		= '#727cb6',
    purpleLight	= '#8e96c5',
    purpleDark	= '#5b6392',
    red         = '#ff5b57';


/* -------------------------------
   58.0 CONTROLLER - Login V1
------------------------------- */
EventMember.controller('loginV1Controller', function($scope, $rootScope, $state, $http) {
    $rootScope.setting.layout.pageWithoutHeader = true;
    $rootScope.setting.layout.paceTop = true;
    $scope.username = '';
    $scope.password = '';
    
    $scope.submitForm = function(form) {
        if ($scope.username == '' ||  $scope.password == '') {
            alert("请输入账号名或密码");
            return false;
        }
        $http({
            method: "POST",
            url: "index.php",
            data: {
                "page": "user",
                "function": "login",
                "args": {
                    "username": $scope.username,
                    "password": $scope.password
                }
            }
        }).success(function (response, status) {
            if (response == "ok") {
                location.reload();
            } else {
                alert(response);
            }
        }).error(function (){
            alert("内部错误");
        });
    };
});

/* -------------------------------
   6.0 CONTROLLER - Page Loader
------------------------------- */
EventMember.controller('pageLoaderController', function($scope, $rootScope, $state) {
    App.initPageLoad();
});
