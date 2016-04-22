function notify(title, body) {
    $.gritter.add({
        title: title,
        text: body
    });
    return false;
}


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
   1.0 CONTROLLER - App
------------------------------- */
EventDB.controller('appController', ['$rootScope', '$scope', function($rootScope, $scope) {
    $scope.$on('$includeContentLoaded', function() {
        App.initComponent();
    });
    $scope.$on('$viewContentLoaded', function() {
        App.initComponent();
        App.initLocalStorage();
    });
    $scope.$on('$stateChangeStart', function() {
        // reset layout setting
        $rootScope.setting.layout.pageSidebarMinified = false;
        $rootScope.setting.layout.pageFixedFooter = false;
        $rootScope.setting.layout.pageRightSidebar = false;
        $rootScope.setting.layout.pageTwoSidebar = false;
        $rootScope.setting.layout.pageTopMenu = false;
        $rootScope.setting.layout.pageBoxedLayout = false;
        $rootScope.setting.layout.pageWithoutSidebar = false;
        $rootScope.setting.layout.pageContentFullHeight = false;
        $rootScope.setting.layout.pageContentFullWidth = false;
        $rootScope.setting.layout.paceTop = false;
        $rootScope.setting.layout.pageLanguageBar = false;
        $rootScope.setting.layout.pageSidebarTransparent = false;
        $rootScope.setting.layout.pageWideSidebar = false;
        $rootScope.setting.layout.pageLightSidebar = false;
        $rootScope.setting.layout.pageFooter = false;
        $rootScope.setting.layout.pageMegaMenu = false;
        $rootScope.setting.layout.pageWithoutHeader = false;
        $rootScope.setting.layout.pageBgWhite = false;
        
        App.scrollTop();
        $('.pace .pace-progress').addClass('hide');
        $('.pace').removeClass('pace-inactive');
    });
    $scope.$on('$stateChangeSuccess', function() {
        Pace.restart();
        App.initPageLoad();
        App.initSidebarSelection();
        App.initLocalStorage();
        App.initSidebarMobileSelection();
    });
    $scope.$on('$stateNotFound', function() {
        Pace.stop();
    });
    $scope.$on('$stateChangeError', function() {
        Pace.stop();
    });
}]);



/* -------------------------------
   2.0 CONTROLLER - Sidebar
------------------------------- */
EventDB.controller('sidebarController', function($scope, $rootScope, $state) {
    App.initSidebar();
});



/* -------------------------------
   3.0 CONTROLLER - Right Sidebar
------------------------------- */
EventDB.controller('rightSidebarController', function($scope, $rootScope, $state) {
    var getRandomValue = function() {
        var value = [];
        for (var i = 0; i<= 19; i++) {
            value.push(Math.floor((Math.random() * 10) + 1));
        }
        return value;
    };

    $('.knob').knob();

    var blue		= '#348fe2', green		= '#00acac', purple		= '#727cb6', red         = '#ff5b57';
    var options = { height: '50px', width: '100%', fillColor: 'transparent', type: 'bar', barWidth: 8, barColor: green };

    var value = getRandomValue();
    $('#sidebar-sparkline-1').sparkline(value, options);

    value = getRandomValue();
    options.barColor = blue;
    $('#sidebar-sparkline-2').sparkline(value, options);

    value = getRandomValue();
    options.barColor = purple;
    $('#sidebar-sparkline-3').sparkline(value, options);

    value = getRandomValue();
    options.barColor = red;
    $('#sidebar-sparkline-4').sparkline(value, options);
});



/* -------------------------------
   4.0 CONTROLLER - Header
------------------------------- */
EventDB.controller('headerController', function($scope, $rootScope, $state, $http) {
    $http.post("index.php", data={
    "page": "user",
    "function": "get_user",
    "args":{}}).success(function(response){
        $rootScope.username = response["username"];
    });

    $scope.logout = function() {
         $http.post("index.php", data={
        "page": "user",
        "function": "logout",
        "args":{}}).success(function(response){
            location.reload();
        });       
    }
});



/* -------------------------------
   5.0 CONTROLLER - Top Menu
------------------------------- */
EventDB.controller('topMenuController', function($scope, $rootScope, $state) {
    setTimeout(function() {
        App.initTopMenu();
    }, 0);
});



/* -------------------------------
   6.0 CONTROLLER - Page Loader
------------------------------- */
EventDB.controller('pageLoaderController', function($scope, $rootScope, $state) {
    App.initPageLoad();
});



/* -------------------------------
   7.0 CONTROLLER - Theme Panel
------------------------------- */
EventDB.controller('themePanelController', function($scope, $rootScope, $state) {
    App.initThemePanel();
});

EventDB.controller('createDocController', function($scope, $rootScope, $http, $stateParams, $state){
    $scope.customer_id = $stateParams.customer_id;
    $scope.choice_log = 0;
    if ($scope.customer_id) {
        $http.post("index.php", data={
            "page": "doc",
            "function": "get_obj",
            "args": {"id": $scope.customer_id}
        }).success(function (response){
            $scope.date_opened = response["date_opened"];
            $scope.last_updated = response["last_updated"];
            $scope.jurisdiction = response["jurisdiction"];
            $scope.region = response["region"];
            $scope.website = response["website"];
            $scope.industry = response["industry"];
            $scope.model = response["model"];
            $scope.product = response["product"];
            $scope.stage = response["stage"];
            $scope.pre_money = response["pre_money"];
            $scope.raising_target = response["raising_target"];
            $scope.zebra_stake = response["zebra_stake"];
            $scope.author = response["author"];
            $scope.current_status = response["current_status"];
            $scope.next_move = response["next_move"];
            $scope.note = response["note"];
            $scope.zebra_team = response["zebra_team"];
            $scope.source = response["source"];
            $scope.contact_note = response["contact_note"];
            $scope.file_name = response["file_name"];
            $scope.contacts = response["contacts"];
            $scope.logs = response["logs"];
            if ($scope.contacts.length == 0) {
                $scope.add_contact_row();
            }
            if ($scope.logs.length == 0) {
                $scope.add_log_row();
            }

        }).error(function(response){
            alert("网络错误");
        });
    } else {
        $scope.contacts = [
            {id:"", name: "", title: "", phone: "", email: ""}
        ];
        $scope.logs = [
            {id: "", date: "", activity: "", document: "", document_file: "",  note: ""}
        ];
    }
    $scope.add_contact_row = function() {
        $scope.contacts.push({id:"", name: "", title: "", phone: "", email: ""});
    }

    $scope.del_contact_row = function(index) {
        var contact_id = $scope.contacts[index]["id"];
        if (contact_id > 0) {
            var flag = confirm("确定要删除这条数据吗？");
            if (flag) {
                $http.post("index.php", data={
                    "page": "doc",
                    "function": "delete_contact",
                    "args": {"id": contact_id}
                }).success(function(response){
                    if(response == "1"){
                        notify("删除联系人", "成功");
                        $scope.contacts.pop(index);
                    } else {
                        notify("删除联系人", response);
                    }
                });
            }
        } else {
            $scope.contacts.pop(index);
        }
        if ($scope.contacts.length == 0) {
            $scope.add_contact_row();
        }
    }
    
    $scope.update_contact_row = function(index) {
        var contact_id = $scope.contacts[index]["id"];
        if (contact_id > 0) {
            $http.post("index.php", data={
                "page": "doc",
                "function": "update_contact",
                "args": $scope.contacts[index] 
            }).success(function(response){
                if(response == "1") {
                    notify("更新联系人", "成功");
                } else {
                    notify("更新联系人", response);
                }
            });
        } else {
            $scope.contacts[index]['customer_id'] = $scope.customer_id;
            $http.post("index.php", data={
                "page": "doc",
                "function": "create_contact",
                "args": $scope.contacts[index] 
            }).success(function(response){
                if (response.length > 0) {
                    notify("创建联系人", "成功");
                    $scope.contacts = response;
                } else {
                    notify("创建联系人", response);
                }
            });
        }
    }


    $scope.add_log_row = function() {
        $scope.logs.push(
            {id:"", date: "", activity: "", document: "", document_file: "",  note: ""}
        );
    }

    $scope.del_log_row = function(index) {
        var log_id = $scope.logs[index]["id"];
        if (log_id > 0) {
            if(confirm("确定要删除这条数据吗？")){
                $http.post("index.php", data={
                    "page": "doc",
                    "function": "delete_log",
                    "args": {"id": log_id}
                }).success(function(response){
                    if(response == "1") {
                        notify("删除日志", "成功");
                        $scope.logs.pop(index);
                    } else {
                        notify("删除日志", response);
                    }
                }); 
            }
        } else {
            $scope.logs.pop(index);
        }
    }
    
    $scope.update_log_row = function(index) {
        var log_id = $scope.logs[index]["id"];
        if(log_id > 0) {
            $http.post("index.php", data={
                "page": "doc",
                "function": "update_log",
                "args": $scope.logs[index]
            }).success(function(response){
                if (response == "1") {
                    notify("修改日志", "成功");
                } else {
                    notify("修改日志", response);
                }
            });
        } else {
            $scope.logs[index]["customer_id"] = $scope.customer_id;
             $http.post("index.php", data={
                "page": "doc",
                "function": "create_log",
                "args": $scope.logs[index]
            }).success(function(response){
                if (response.length > 0) {
                    $scope.logs = response;
                    notify("添加日志", "成功");
                } else {
                    notify("添加日志", response);
                }
            });           
        }
    }

    $scope.set_choice_log = function(index) {
        $scope.choice_log = index;
    }
   
    $scope.upload_file_change = function () {
        var uploaded_file = document.getElementById("upload_file_path").value.split("==");
        $scope.logs[$scope.choice_log]["document"] = uploaded_file[0];
        $scope.logs[$scope.choice_log]["document_file"] = uploaded_file[1];
    }

    $scope.upload_file = function(){
        var iframe = document.getElementById("upload_frame");
        iframe.contentWindow.document.getElementById("upload_form").submit();
    }

    $scope.submit = function() {
        if ($scope.customer_id > 0) {
            $http.post("index.php", data={
                "page": "doc",
                "function": "update_doc",
                "args": {
                    date_opened: $scope.date_opened,
                    last_updated: $scope.last_updated,
                    jurisdiction: $scope.jurisdiction,
                    region: $scope.region,
                    website: $scope.website,
                    industry: $scope.industry,
                    model: $scope.model,
                    product: $scope.product,
                    stage: $scope.stage,
                    pre_money: $scope.pre_money,
                    raising_target: $scope.raising_target,
                    zebra_stake: $scope.zebra_stake,
                    author: $scope.author,
                    current_status: $scope.current_status,
                    next_move: $scope.next_move,
                    note: $scope.note,
                    zebra_team: $scope.zebra_team,
                    source: $scope.source,
                    contact_note: $scope.contact_note,
                    file_name: $scope.file_name,
                    id: $scope.customer_id
                }
            }).success(function(response){
                if(response == "1") {
                    notify("文档", "保存成功");
                } else {
                    notify("文档", response);
                }
            });
        }
        else{
            $http.post("index.php", data={
                "page": "doc",
                "function": "create_doc",
                "args": {
                    date_opened: $scope.date_opened,
                    last_updated: $scope.last_updated,
                    jurisdiction: $scope.jurisdiction,
                    region: $scope.region,
                    website: $scope.website,
                    industry: $scope.industry,
                    model: $scope.model,
                    product: $scope.product,
                    stage: $scope.stage,
                    pre_money: $scope.pre_money,
                    raising_target: $scope.raising_target,
                    zebra_stake: $scope.zebra_stake,
                    author: $scope.author,
                    current_status: $scope.current_status,
                    next_move: $scope.next_move,
                    note: $scope.note,
                    zebra_team: $scope.zebra_team,
                    source: $scope.source,
                    contact_note: $scope.contact_note,
                    file_name: $scope.file_name,
                    contacts: $scope.contacts,
                    logs: $scope.logs
                }
            }).success(function(response){
                $rootScope.customer_id = response;
                if(response > 0) {
                    notify("文档", "保存成功");
                    $state.go("app.doc.edit", {id: response})
                } else {
                    notify("文档", response);
                }
            });
        }
    }
});


EventDB.controller('showDocController', function($scope, $http, $stateParams) {
    $scope.customer_id = $stateParams.customer_id;
    if ($scope.customer_id) {
        $http.post("index.php", data={
            "page": "doc",
            "function": "get_obj",
            "args": {"id": $scope.customer_id}
        }).success(function (response){
            $scope.date_opened = response["date_opened"];
            $scope.last_updated = response["last_updated"];
            $scope.jurisdiction = response["jurisdiction"];
            $scope.region = response["region"];
            $scope.website = response["website"];
            $scope.industry = response["industry"];
            $scope.model = response["model"];
            $scope.product = response["product"];
            $scope.stage = response["stage"];
            $scope.pre_money = response["pre_money"];
            $scope.raising_target = response["raising_target"];
            $scope.zebra_stake = response["zebra_stake"];
            $scope.author = response["author"];
            $scope.current_status = response["current_status"];
            $scope.next_move = response["next_move"];
            $scope.note = response["note"];
            $scope.zebra_team = response["zebra_team"];
            $scope.source = response["source"];
            $scope.contact_note = response["contact_note"];
            $scope.file_name = response["file_name"];
            $scope.contacts = response["contacts"];
            $scope.logs = response["logs"];
            if ($scope.contacts.length == 0) {
                $scope.add_contact_row();
            }
            if ($scope.logs.length == 0) {
                $scope.add_log_row();
            }

        }).error(function(response){
            alert("网络错误");
        });
    }
});

EventDB.controller('deleteCustomerController', function($scope, $http, $stateParams,$state){
    $scope.customer_id = $stateParams.customer_id;
    if (confirm("确定要删除这条资料吗？")){
        $http.post("index.php", data={
            "page": "doc",
            "function": "delete_doc",
            "args": {"id": $scope.customer_id}
        }).success(function(response){
            if (response=="1"){
                $state.go("app.doc.list");
            } else {
                notify("删除文档", response);
            }
        });
    }
});

EventDB.controller('listDocController', function($scope, $http){
    $http.post("index.php", data={
        "page": "doc",
        "function": "get_list",
        "args": {}
    }).success(function(response){
    if ($('#data-table').length !== 0) {
        var dt = $('#data-table').DataTable({
            //responsive: true,
            columns: [
                {title: "No."},
                {title: "Name"},
                {title: "Industry"},
                {title: "Region"},
                {title: "Product"},
                {title: "Pre-Money Valuation"},
                {title: "Raising Target"},
                {title: "Current Status"},
                {title: "Next Move"},
                {title: "Operate"}
            ],
            data: response
        });
        var tt = new $.fn.dataTable.TableTools(dt);
        $( tt.fnContainer() ).insertBefore('div#data-table_length');
    }}).error(function(response){
        alert("网络错误");
    });
});

EventDB.controller('listMemberController', function($scope, $http){
    $http.post("index.php", data={
        "page": "user",
        "function": "list_user",
        "args": {}
    }).success(function(response){
        $scope.users = response;
    });
});

EventDB.controller('editMemberController', function($stateParams, $state, $scope, $http){
    $scope.user_id = $stateParams.user_id;
    $scope.spassword = "";
    if ($scope.user_id > 0){
        $http.post("index.php", data={
            "page": "user",
            "function": "get_user_info",
            "args": {"id": $scope.user_id}
        }).success(function(response){
            $scope.status = response["status"];
            $scope.susername = response["username"];
        });
    }
    else {
        $scope.status=true;
        $scope.susername = "";
    }
    $scope.set_status = function(bool){
        $scope.status = bool;
    };
    $scope.submit = function(){
        if ($scope.susername.length < 5) {
            notify("system", "用户名长度不能小于5位");
            return false;
        } else if ($scope.spassword.length < 10) {
            notify("system", "密码长度不能小于10位");
            return false;
        }
        $http.post("index.php", data={
            "page": "user",
            "function": "edit_user",
            "args": {
                "id": $scope.user_id,
                "username": $scope.susername,
                "password": $scope.spassword,
                "status": $scope.status
                }
        }).success(function(response){
            if (response == "1") {
                notify("system", "账户编辑成功");
                $state.go("app.settings.member.list");
            } else {
                notify("system", response);
            }
        });
    };
    
});
