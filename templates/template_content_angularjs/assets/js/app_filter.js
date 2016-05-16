EventDB.filter('get_date', function(){
    var filter = function(input) {
        input = new Date(Date.parse(input));
        return input.format("yyyy-MM-dd");
    }
    return filter;
});

EventDB.filter('money_type_1', function(){
    var filter = function(input) {
        return find_options_value(input, money_type_options);
    }
    return filter;
});

EventDB.filter('money_type_2', function(){
    var filter = function(input) {
        return find_options_value(input, money_type_options);
    }
    return filter;
});

EventDB.filter('investment_type', function(){
    var filter = function(input) {
        return find_options_value(input, investment_type_options);
    }
    return filter;
});

EventDB.filter('investment_structure', function(){
    var filter = function(input) {
        return find_options_value(input, investment_structure_options);
    }
    return filter;
});

EventDB.filter('stage', function(){
    var filter = function(input) {
        return find_options_value(input, stage_options);
    }
    return filter;
});

EventDB.filter('source', function(){
    var filter = function(input) {
        return find_options_value(input, source_options);
    }
    return filter;
});

EventDB.filter('decision_stage', function(){
    var filter = function(input) {
        return find_options_value(input, decision_stage_options);
    }
    return filter;
});

EventDB.filter('interest_level', function(){
    var filter = function(input) {
        return find_options_value(input, interest_level_options);
    }
    return filter;
});

EventDB.filter('ftype', function(){
    var filter = function(input) {
        return find_options_value(input, ftype_options);
    }
    return filter;
});

EventDB.filter('related', function(){
    var filter = function(input) {
        return find_options_value(input, related_options);
    }
    return filter;
});

EventDB.filter('release', function(){
    var filter = function(input) {
        return find_options_value(input, release_options);
    }
    return filter;
});

EventDB.filter('team', function(){
    var filter = function(input) {
        if (input != undefined){
        var t = [];
        for (var i=0; i < team_options.length; i++){
            if (input.indexOf(team_options[i].id.toString()) > -1){
                t.push(team_options[i].label);
            }
        }
        return t.join(",");
    }
        //return find_options_value(input, release_options);
    }
    return filter;
});

EventDB.filter('get_time', function(){
    var filter = function(input) {
        input = new Date(Date.parse(input));
        return input.format("hh:mm");
    }
    return filter;
});

EventDB.filter("sanitize", ['$sce', function($sce) {
    return function(htmlCode){
        return $sce.trustAsHtml(htmlCode);
    }
}]);

EventDB.filter('mail_content', ['$sce', function($sce){
    return function(input) {
         var tmp ="<iframe src='_blank' on_init=('alert(\"input\")')></iframe>";
         return input; //$sce.trustAsHtml(tmp);
    };
}]);
