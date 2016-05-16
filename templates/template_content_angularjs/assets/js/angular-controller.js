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




EventDB.controller('createDocController', function($scope, $http, $rootScope, $stateParams, $state){

    
    $scope.set_team = function(k){
        var i = $scope.zebra_team.indexOf(k.toString())
        if ( i > -1){
            $scope.zebra_team.splice(i, 1);
        } else {
            $scope.zebra_team.push(k.toString());
        }
        //console.log($scope.zebra_team);
    }

    $scope.stage_options = stage_options;
    $scope.money_type_options = money_type_options;
    $scope.investment_type_options = investment_type_options;
    $scope.source_options = source_options;
    $scope.investment_structure_options = investment_structure_options;
    $scope.decision_stage_options = decision_stage_options;
    $scope.interest_level_options = interest_level_options;
    $scope.team_options = team_options;
    $scope.related_options = related_options;
    $scope.release_options = release_options;
    $scope.ftype_options = ftype_options;


    $scope.files = [{adate:"", ftype:"", note:"",files: [{"id":"", "file_name": "", "file_path": ""}]}];

    $scope.add_file_file = function(i){
        $scope.files[i].files.push(
            {"id":"", "file_name": "", "file_path": ""}
        );
    };

    $scope.add_file_row = function(){
        $scope.files.push({adate:"", ftype:"", note:"",files: [{"id":"", "file_name": "", "file_path": ""}]});
    };
    $scope.del_file_row = function(index){
        if (confirm("确定要删除文件吗?")){
            var dc_file_id = $scope.files[index]["id"];
            if (dc_file_id){
                $http.post("index.php", data={
                    "page": "doc",
                    "function": "delete_document_file",
                    "args": {
                        id: dc_file_id
                    }

                }).success(function(response){
                    if (response == "1") {
                        notify("删除文件", "成功");
                    } else {
                        notify("删除文件", response);
                    }
                });
            }
            $scope.files.splice(index, 1);
            if ($scope.logs[log_index].files.length == 0) {
                $scope.add_file_row(); 
            }
        }
    };

    $scope.del_file_file = function(row_index, file_index) {
        if (confirm("确定要删除文件吗？")) {
            var file_id = $scope.files[row_index].files[file_index]["id"];
            if (file_id) {
                $http.post("index.php", data={
                    "page": "doc",
                    "function": "delete_file",
                    "args": {
                        id: file_id
                    }
                }).success(function(response){
                    if (response == "1") {
                        notify("删除文件", "成功");
                    } else {
                        notify("删除文件", response);
                    }
                });
            }
            $scope.files[row_index].files.splice(file_index, 1);
            if ($scope.files[row_index].files.length == 0) {
                $scope.add_file_file(row_index); 
            }
        }
    };

    $scope.customer_id = $stateParams.customer_id;
    $scope.choice_log = 0;
    if ($scope.customer_id) {
        $scope.load_data = function(){
            $http.post("index.php", data={
                "page": "doc",
                "function": "get_obj",
                "args": {"id": $scope.customer_id}
            }).success(function (response){
                $scope.file_name = response["file_name"];
                $scope.date_opened = response["date_opened"];
                $scope.last_updated = response["last_updated"];
                $scope.industry = response["industry"];
                $scope.region = response["region"];
                $scope.stage = response["stage"];
                $scope.company_info = response["company_info"];
                $scope.money_type_1 = response["money_type_1"];

                $scope.raising_target = response["raising_target"];
                $scope.money_type_2 = response["money_type_2"];
                $scope.pre_money = response["pre_money"];
                $scope.investment_type = response["investment_type"];
                $scope.investment_structure = response["investment_structure"];
                $scope.source = response["source"];
                $scope.decision_stage = response["decision_stage"];
                $scope.interest_level = response["interest_level"];
                $scope.next_move = response["next_move"];        
                $scope.note = response["note"];   
                $scope.company_name = response["company_name"]; 
                $scope.company_address = response["company_address"]; 
                $scope.zebra_team = response["zebra_team"].split(','); 

                $scope.logs= response["logs"];
                $scope.contacts= response["contacts"];
                //console.log($scope.contacts);
                $scope.files= response["files"];

                $scope.stage_free = response["stage_free"],
                $scope.money_type_1_free = response["money_type_1_free"],
                $scope.money_type_2_free = response["money_type_2_free"],
                $scope.investment_type_free = response["investment_type_free"],
                $scope.investment_structure_free = response["investment_structure_free"],
                $scope.source_free = response["source_free"],
                $scope.decision_stage_free = response["decision_stage_free"],
                $scope.interest_level_free = response["interest_level_free"]

                for(var i=0; i<$scope.logs.length;i++){
                    if ($scope.logs[i].files.length == 0){
                        $scope.add_log_file(i); 
                    }
                }

                if ($scope.contacts.length == 0) {
                    $scope.add_contact_row();
                }
                if ($scope.logs.length == 0) {
                    $scope.add_log_row();
                }
                if ($scope.files.length == 0) {
                    $scope.add_file_row();
                }

            }).error(function(response){
                alert("网络错误");
            });
        }
        $scope.load_data();
    } else {
        $scope.zebra_team = new Array();
        $scope.date_opened = new Date().format("yyyy-MM-dd");
        $scope.last_updated = new Date().format("yyyy-MM-dd");
        $scope.contacts = [
            {id:"", name: "", title: "", phone: "", email: ""}
        ];
        $scope.logs = [
            {id: "", date: "", activity: "", document: "", document_file: "",  note: "", files: [{"id":"", "file_name": "", "file_path": ""}]}
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
                        $scope.contacts.splice(index, 1);
                    } else {
                        notify("删除联系人", response);
                    }
                });
            }
        } else {
            $scope.contacts.splice(index, 1);
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
            {id:"", date: "", activity: "", document: "", document_file: "",  note: "",
                files: [{"id":"", "file_name": "", "file_path": ""}]
            }
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
                        $scope.logs.splice(index, 1);
                    } else {
                        notify("删除日志", response);
                    }
                }); 
            }
        } else {
            $scope.logs.splice(index, 1);
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

    $scope.set_choice_log = function(smode, log_index, file_index) {
        $scope.smode = smode;
        $scope.choice_log = log_index;
        $scope.file_index = file_index;
    }
 
    $scope.add_log_file = function(i){
        $scope.logs[i].files.push(
            {"id":"", "file_name": "", "file_path": ""}
        );
    };

    $scope.upload_file_change = function () {
        var uploaded_file = document.getElementById("upload_file_path").value.split("==");
        //console.log(uploaded_file);

        if ($scope.smode == 1) {
            $scope.logs[$scope.choice_log].files[$scope.file_index]["file_name"] = uploaded_file[0];
            $scope.logs[$scope.choice_log].files[$scope.file_index]["file_path"] = uploaded_file[1];
        } else if ($scope.smode == 2) {
            $scope.files[$scope.choice_log].files[$scope.file_index]["file_name"] = uploaded_file[0];
            $scope.files[$scope.choice_log].files[$scope.file_index]["file_path"] = uploaded_file[1]; 
        }
    };

    $scope.del_log_file = function(log_index, file_index) {
        if (confirm("确定要删除文件吗？")) {
            var file_id = $scope.logs[log_index].files[file_index]["id"];
            if (file_id) {
                $http.post("index.php", data={
                    "page": "doc",
                    "function": "delete_file",
                    "args": {
                        id: file_id
                    }
                }).success(function(response){
                    if (response == "1") {
                        notify("删除文件", "成功");
                    } else {
                        notify("删除文件", response);
                    }
                });
            }
            $scope.logs[log_index].files.splice(file_index, 1);
            if ($scope.logs[log_index].files.length == 0) {
                $scope.add_log_file(log_index); 
            }
        }
    };

    $scope.upload_file = function(){
        var iframe = document.getElementById("upload_frame");
        iframe.contentWindow.document.getElementById("upload_form").submit();
    }

    $scope.submit = function() {
        if ($scope.customer_id > 0) {
            //console.log($scope.zebra_team);
            $http.post("index.php", data={
                "page": "doc",
                "function": "update_doc",
                "args": {
                    file_name:$scope.file_name,
                    date_opened:$scope.date_opened,
                    last_updated:$scope.last_updated,
                    industry:$scope.industry,
                    region:$scope.region,
                    stage:$scope.stage,
                    company_info:$scope.company_info,
                    money_type_1:$scope.money_type_1,
                    raising_target:$scope.raising_target,
                    money_type_2:$scope.money_type_2,
                    pre_money:$scope.pre_money,
                    investment_type:$scope.investment_type,
                    investment_structure:$scope.investment_structure,
                    source:$scope.source,
                    decision_stage:$scope.decision_stage,
                    interest_level:$scope.interest_level,
                    next_move:$scope.next_move,
                    note:$scope.note,
                    zebra_team:$scope.zebra_team.join(","),
                    company_name:$scope.company_name,
                    company_address:$scope.company_address,
                    contacts: $scope.contacts,
                    stage_free: $scope.stage_free,
                    money_type_1_free: $scope.money_type_1_free,
                    money_type_2_free: $scope.money_type_2_free,
                    investment_type_free: $scope.investment_type_free,
                    investment_structure_free: $scope.investment_structure_free,
                    source_free: $scope.source_free,
                    decision_stage_free: $scope.decision_stage_free,
                    interest_level_free: $scope.interest_level_free,
                    id: $scope.customer_id,
                    logs: $scope.logs,
                    files:$scope.files
                }
            }).success(function(response){
                if(response == "1") {
                    notify("文档", "保存成功");
                    $scope.load_data();
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
                    file_name:$scope.file_name,
                    date_opened:$scope.date_opened,
                    last_updated:$scope.last_updated,
                    industry:$scope.industry,
                    region:$scope.region,
                    stage:$scope.stage,
                    company_info:$scope.company_info,
                    money_type_1:$scope.money_type_1,
                    raising_target:$scope.raising_target,
                    money_type_2:$scope.money_type_2,
                    pre_money:$scope.pre_money,
                    investment_type:$scope.investment_type,
                    investment_structure:$scope.investment_structure,
                    source:$scope.source,
                    decision_stage:$scope.decision_stage,
                    interest_level:$scope.interest_level,
                    next_move:$scope.next_move,
                    note:$scope.note,
                    zebra_team:$scope.zebra_team.join(","),
                    company_name:$scope.company_name,
                    company_address:$scope.company_address,
                    contacts: $scope.contacts,
                    logs: $scope.logs,
                    files:$scope.files,
                    stage_free: $scope.stage_free,
                    money_type_1_free: $scope.money_type_1_free,
                    money_type_2_free: $scope.money_type_2_free,
                    investment_type_free: $scope.investment_type_free,
                    investment_structure_free: $scope.investment_structure_free,
                    source_free: $scope.source_free,
                    decision_stage_free: $scope.decision_stage_free,
                    interest_level_free: $scope.interest_level_free
                }
            }).success(function(response){
                $rootScope.customer_id = response;
                if(response > 0) {
                    notify("文档", "保存成功");
                    $state.go("app.doc.edit", {customer_id: response});
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
                $scope.file_name = response["file_name"];
                $scope.date_opened = response["date_opened"];
                $scope.last_updated = response["last_updated"];
                $scope.industry = response["industry"];
                $scope.region = response["region"];
                $scope.stage = response["stage"];
                $scope.company_info = response["company_info"];
                $scope.money_type_1 = response["money_type_1"];

                $scope.raising_target = response["raising_target"];
                $scope.money_type_2 = response["money_type_2"];
                $scope.pre_money = response["pre_money"];
                $scope.investment_type = response["investment_type"];
                $scope.investment_structure = response["investment_structure"];
                $scope.source = response["source"];
                $scope.decision_stage = response["decision_stage"];
                $scope.interest_level = response["interest_level"];
                $scope.next_move = response["next_move"];     
                $scope.note = response["note"];   
                $scope.company_name = response["company_name"]; 
                $scope.company_address = response["company_address"]; 
                $scope.zebra_team = response["zebra_team"].split(',');

                $scope.stage_free = response["stage_free"],
                $scope.money_type_1_free = response["money_type_1_free"],
                $scope.money_type_2_free = response["money_type_2_free"],
                $scope.investment_type_free = response["investment_type_free"],
                $scope.investment_structure_free = response["investment_structure_free"],
                $scope.source_free = response["source_free"],
                $scope.decision_stage_free = response["decision_stage_free"],
                $scope.interest_level_free = response["interest_level_free"]

                $scope.logs= response["logs"];
                $scope.contacts= response["contacts"];
                //console.log($scope.contacts);
                $scope.files= response["files"];


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

EventDB.controller('listDocController', function($scope, $http, $rootScope){
    $http.post("index.php", data={
        "page": "doc",
        "function": "get_list",
        "args": {}
    }).success(function(response){
    if ($('#data-table').length !== 0) {
        var dt = $('#data-table').DataTable({
            //responsive: true,
            columns: [
            {title: "ID"},
                {title: "立项时间"},
                {title: "项目名称"},
                {title: "行业"},
                {title: "地域"},
                {title: "融资"},
                {title: "估值"},
                {title: "类型"},
                {title: "阶段"},
                {title: "兴趣"},
                {title: "下步"},
                {title: ""}
            ],
            data: response,
            createdRow: function(row, data, index){
                //融资
                var raising_target_tmp = data[5].split("M");
                var raising_target = raising_target_tmp[0];
                if ($.isNumeric(raising_target_tmp[1])){
                    var money_type_1 = find_options_value(raising_target_tmp[1], money_type_options);
                    raising_target = raising_target + "M " + money_type_1;
                    $("td", row).eq(6).text(raising_target);
                }
                //估值
                var pre_money_tmp = data[6].split("M");
                var pre_money = pre_money_tmp[0];
                if ($.isNumeric(pre_money_tmp[1])){
                    var money_type_1 = find_options_value(pre_money_tmp[1], money_type_options);
                    pre_money = pre_money + "M " + money_type_1;
                    $("td", row).eq(5).text(pre_money);
                }
                //类型
                $("td", row).eq(6).text(find_options_value(data[6], investment_type_options));
                //阶段
                $("td", row).eq(7).text(find_options_value(data[7], decision_stage_options));
                //兴趣
                $("td", row).eq(8).text(find_options_value(data[8], interest_level_options));
            }
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

EventDB.controller("DocMailController", function ($stateParams, $state, $scope, $http){
    var doc_id = $stateParams.customer_id;
    $http.post("index.php", {
        "page": "doc",
        "function": "get_mail_list",
        "args": {"doc_id": doc_id}
    }).success(function(response){
        $scope.mails = response;
        console.log(response);
    });
});