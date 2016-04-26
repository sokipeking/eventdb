EventDB.filter('get_date', function(){
    var filter = function(input) {
        input = new Date(Date.parse(input));
        return input.format("yyyy-MM-dd");
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
