/**
 * Created by codetector on 9/5/16.
 */
var app = angular.module('fgInstall', [], function ($httpProvider) {
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    var param = function (obj) {
        var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for (name in obj) {
            value = obj[name];

            if (value instanceof Array) {
                for (i = 0; i < value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value instanceof Object) {
                for (subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };
    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function (data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
});
app.controller("settingCtrl",function ($scope,$http) {
    $scope.testStatus = 0;
    $scope.dbSetting = {};
    $scope.dbSetting.dbhost = "localhost";
    $scope.dbSetting.dbprefix = "frameG_";

    $scope.testConnection = function () {
        $http.post("/install/testdb.php",$scope.dbSetting).then(function (result) {
            $scope.testStatus = result.data.success?1:-1;
        });
    };

    $scope.createDB = function () {
        $('#userinputScreen').hide();
        $('#progress').show();

        $http.post("/install/createdbconf.php",$scope.dbSetting).then(function (result) {
            $scope.installResult = result.data.success;
            $('#progress').hide();
            $('#result').show();
        });
    }
});