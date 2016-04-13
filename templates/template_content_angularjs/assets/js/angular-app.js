/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 1.9.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.9/admin/
*/

var EventDB = angular.module('EventDB', [
    'ui.router',
    'ui.bootstrap',
    'oc.lazyLoad'
]);

EventDB.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/app/doc/list');

    $stateProvider
        .state('app', {
            url: '/app',
            templateUrl: 'template/app.html',
            abstract: true
        })
        .state('app.doc', {
            url: '/doc',
            abstract: true,
            template: '<div class="ui-view"></div>',
        })
        .state('app.doc.create', {
            url: '/create',
            templateUrl: 'views/create_doc.html',
            resolve: {
                service: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/bootstrap-datepicker/css/datepicker.css',
                            'assets/plugins/bootstrap-datepicker/css/datepicker3.css',
                            'assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                            'assets/plugins/gritter/css/jquery.gritter.css',
                            'assets/plugins/gritter/js/jquery.gritter.js'
                        ] 
                    });
                }]
            },
            data: { pageTitle: 'Doc Create' }
        })
        .state('app.doc.delete', {
            url: '/delete/:customer_id',
            templaye:"<div class='ui-view'></div>",
            controller: "deleteCustomerController",
            resolve: {
                service: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/gritter/css/jquery.gritter.css',
                            'assets/plugins/gritter/js/jquery.gritter.js'
                        ] 
                    });
                }]
            }
        })
        .state('app.doc.edit', {
            url: '/edit/:customer_id',
            templateUrl: 'views/create_doc.html',
            resolve: {
                service: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/bootstrap-datepicker/css/datepicker.css',
                            'assets/plugins/bootstrap-datepicker/css/datepicker3.css',
                            'assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                            'assets/plugins/gritter/css/jquery.gritter.css',
                            'assets/plugins/gritter/js/jquery.gritter.js'
                        ] 
                    });
                }]
            },
            data: { pageTitle: 'Doc Edit' }
        })
        .state('app.doc.list', {
            url: '/list',
            templateUrl: 'views/list_doc.html',
            data: { pageTitle: 'Doc List' },
            resolve: {
                service: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        serie: true,
                        files: [
                            'assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css',
                            'assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css',
                            'assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css',
                            'assets/plugins/DataTables/media/js/jquery.dataTables.js',
                            'assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js',
                            'assets/plugins/DataTables/media/css/dataTables.tableTools.css',
                            'assets/plugins/DataTables/media/js/dataTables.tableTools.js',
                            'assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js'
                        ]
                    });
                }]
            }
        })
        .state('app.settings', {
            url: '/settings/',
            template: '<div class="ui-view"></div>',
            abstract: true
        })
        .state('app.settings.member', {
            url: 'member/',
            template: '<div class="ui-view"></div>',
            abstract: true
        })
        .state('app.settings.member.list', {
            url: 'list/',
            templateUrl: 'views/list_member.html',
            data: {pageTitle: "List Account"}
        })
        .state('app.settings.member.create', {
            url: 'create/',
            templateUrl: 'views/edit_member.html',
            data: { pageTitle: 'Create Account' },
            resolve: {
                service: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/gritter/css/jquery.gritter.css',
                            'assets/plugins/gritter/js/jquery.gritter.js'
                        ] 
                    });
                }]
            }
        })
        .state('app.settings.member.edit', {
            url: 'edit/:user_id',
            templateUrl: 'views/edit_member.html',
            data: { pageTitle: 'Edit Account' },
            resolve: {
                service: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        files: [
                            'assets/plugins/gritter/css/jquery.gritter.css',
                            'assets/plugins/gritter/js/jquery.gritter.js'
                        ] 
                    });
                }]
            }

        })
}]);

EventDB.run(['$rootScope', '$state', 'setting', function($rootScope, $state, setting) {
    $rootScope.$state = $state;
    $rootScope.setting = setting;
}]);


var EventMember= angular.module('EventMember', [
    'ui.router',
    'ui.bootstrap',
    'oc.lazyLoad'
]);

EventMember.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/member/login');

    $stateProvider
        .state('member', {
            url: '/member',
            template: '<div ui-view></div>',
            abstract: true
        })
        .state('member.login', {
            url: '/login',
            data: { pageTitle: 'Login' },
            templateUrl: 'views/login.html'
        })

}]);

EventMember.run(['$rootScope', '$state', 'setting', function($rootScope, $state, setting) {
    $rootScope.$state = $state;
    $rootScope.setting = setting;
}]);
