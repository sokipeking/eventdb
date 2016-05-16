/*   
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 1.9.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v1.9/admin/
*/


var stage_options = [{id:1, label:"初创"},{id:2, label: "早期"},{id:3, label:"成长"},{id:4, label: "晚期"},{id:5, label: "已上市"}, {id:999, label:"其他"}];
var money_type_options = [{id:1, label: "US$"}, {id:2,label:"RMB"}, {id:999, label:"其他"}];
var investment_type_options = [{id:1, label: "早期控股"}, {id:2, label:"少数 （领投）"},{id:3, label: "少数 （跟投）"}, {id:4, label: "晚期控股"}, {id:999, label:"其他"}];
var source_options = [{id:1, label: "FA推介"}, {id:2, label: "会议"}, {id:3, label:"自有Network"}, {id:4, label:"公司主动联系"}, {id:5, label:"主动联系公司"}, {id:999, label:"其他"}];
var investment_structure_options = [{id:1, label: "美元投资"}, {id:2, label:"人民币投资"}, {id:3, label: "待定"}, {id:999, label:"其他"}];
var decision_stage_options = [{id:1, label: "筛选"}, {id:2, label:"评估"}, {id:3, label:"尽调"}, {id:4, label:"交割"}, {id:5, label:"投后"}, {id:6, label:"已否"}, {id:999, label:"其他"}];
var interest_level_options = [{id:1, label:"高"}, {id:2, label:"中"}, {id:3, label:"低"},{id:999, label:"其他"}];
var team_options = [{id:1, label: "CC"}, {id:2, label: "David"},
{id:3, label: "Yilu"}, {id:4, label: "Rachel"}, {id:5, label:"KC"},
{id:6, label:"Meng Yang"}, {id:7, label:"Zhang Mei"}, {id:8, label:"George"},{id:9, label:"Zoe"}, {id:999, label:"其他"}];
var related_options = [{id:1, label:"目标公司"}, {id:2, label:"投资人（跟投）"}, {id:3, label:"投资人（领投）"}, {id:4, label:"介绍人"}, {id:5, label:"第三方"}, {id:999, label:"其他"}];
var release_options = [{id:1, label:"管理层"}, {id:2, label:"FA"}, {id:3, label:"投行"}, {id:4, label:"律师"}, {id:5, label:"会计师"}, {id:6, label:"咨询顾问"}, {id:999, label:"其他"}];
var ftype_options = [{id:1, label: "Deal Memo"}, {id:2, label:"Preliminary IC Memo"}, {id:3, label:"Term Sheet"}, {id:4, label:"Final IC Memo"}, {id:999, label:"其他"}];


function find_options_value(id, options) {
    for (var i=0; i < options.length; i ++){
        if (options[i].id == id){
            return options[i].label;
        }
    }
}



var EventDB = angular.module('EventDB', [
    'ui.router',
    'ui.bootstrap',
    'oc.lazyLoad'
]);

EventDB.config(['$stateProvider', '$urlRouterProvider', "$sceProvider", function($stateProvider, $urlRouterProvider, $sceProvider) {
    $sceProvider.enabled(true);
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
        .state('app.doc.show', {
            url: '/show/:customer_id',
            templateUrl: 'views/show_doc.html',
            data: { pageTitle: 'Doc Show' }
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
        .state('app.doc.email', {
            url: '/:customer_id/mail',
            templateUrl: 'views/doc_mail.html',
            data: { pageTitle: 'Doc MAIL' },
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
