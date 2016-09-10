var app = angular.module('cms', ['ngRoute'], function ($httpProvider) {
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
app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.interceptors.push('httpInterceptor');
}]);
app.factory('httpInterceptor', ['$q', '$injector', function ($q, $injector) {
    var httpInterceptor = {
        'responseError': function (response) {
            if (response.status == 401) {
                Cookies.remove('session',{path:"/"});
                window.location.href = "/";
            }
            return $q.reject(response);
        },
        'response': function (response) {
            return response;
        },
        'request': function (config) {
            return config;
        },
        'requestError': function (config) {
            return $q.reject(config);
        }
    };
    return httpInterceptor;
}]);


app.config(['$routeProvider', function($routeProvider){
    $routeProvider
        .when('/',{
            templateUrl:'template/view.index.ng',
            controller:'indexCtrl'
        })
        .when('/login',{templateUrl:'template/view.login.ng'})
        // .when('/register',{templateUrl:'template/view.register.ng'})
        .when('/admin',{templateUrl:'template/admin/view.admin.dashboard.ng',controller:'adminDashboardCtrl'})
        .when('/admin/settings',{templateUrl:'template/admin/view.admin.settings.ng',controller:'adminDashboardCtrl'})
        .otherwise({redirectTo:'/'});
}]);
app.factory("config", function ($http) {
    return {
        get: function (name) {
            return $http.get("/api/config?name="+name);
        }
    };
});
app.factory("userdata", function ($http) {
    var data = {};
    return {
        setSession:function (session) {
            Cookies.set('session',session,{ path: '/' });
        },
        resetSession:function () {
            Cookies.remove('session',{path: '/'});
            data = {};
        },
        get: function () {
            return data;
        },
        set: function (newVal) {
            data = newVal;
        },
        update: function () {
            $http.post("/api/userdata", {session: Cookies.get('session')}).then(function (result) {
                if (result.data.valid) {
                    data = (result.data);
                }
            });
        },
        isLogin: function () {
            return Cookies.get("session")!=null && Cookies.get("session")!="";
        }
    };
});

app.controller('globalCtrl',function ($scope,$http,config,userdata) {
    config.get("siteTitle").then(function (result) {
        $scope.cms_site_title = result.data.value;
    });
    config.get("siteICP").then(function (result) {
        $scope.cms_site_ICP = result.data.value;
    });
    config.get("companyName").then(function (result) {
        $scope.cms_companyName = result.data.value;
    });
    $http.get("/api/version").then(function (result) {
        $scope.cms_backendVersion = result.data.version;
    });
    $scope.cms_frontendVersion = "0.2 Alpha";
    if (userdata.isLogin()){
        userdata.update();
    }
});
app.controller('navbarCtrl',function ($scope,$http,userdata,config) {
    $scope.userdata = userdata;
    $scope.logout = function () {
        userdata.resetSession();
        window.location.href="/";
    };
});
app.controller('loginCtrl', function($scope,$http,userdata) {
    $scope.isLoggingIn = false;
    $scope.login = function () {
        $http.post("/api/login",{username:$scope.username,password:$scope.password}).then(function (result) {
            var tooltip = $.zui.messager.show(result.data.success?"登录成功":"登录失败",{placement:'top',time:1000,type:result.data.success?"success":"danger"});
            tooltip.show();
            if (result.data.success){
                userdata.setSession(result.data.session);
                userdata.update();
                window.location.href="#";
            }
        });
    };
});

app.controller('indexCtrl',function ($scope) {

});
app.controller('passageViewCtrl',function ($scope) {

});
app.controller('adminSideNavCtrl',function ($scope) {
    $scope.getActiveClass = function (targetURL) {
        return (window.location.hash == (targetURL));
    };
});
app.controller('adminDashboardCtrl',function ($scope) {

});