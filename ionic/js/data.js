angular.module('dungziApp.data', [])

.factory('CompanyData', function(){
    var data = {};
    
    data = {
        name: "둥지닷컴",
        zoom: 14,
        center: {
            latitude: 37.4797459,
            longitude: 126.942765
        },
        phone: "1566-2395",
        host: "",
        subway: true,
        property: "매물",
        pageSize: 6,
		installation_flag: false
    };

    return data;
})

.factory('PostsData', function(){
    
    /* 매물 목록 가져오기위한 검색 조건 값 */
    var data = { 
                    category:'', 
                    type:'',
                    lat:'',
                    lng:'',
                    search_type:'',
                    search_value:'',
                    sell_start:0, 
                    sell_end:0, 
                    full_start:0, 
                    full_end:0, 
                    month_deposit_start: 0, 
                    month_deposit_end:0, 
                    month_start:0, 
                    month_end:0 
                };

    return data;
})

.factory('CategoryData', function(){
    var data = [];
    var service = {};
    
    service.setData = function(posts) { data = posts; };

    service.get = function(serverpostId) { return data[serverpostId]; };
    
    return service;
})

.factory('PropertyService',function($http, CompanyData, PostsData, PropertyData){
    
    var property = [];
    var service = {};

    service.getList = function(page) { 
        return $http({
            method: 'POST', 
            url: CompanyData.host + "/json/properties_json",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
              var str = [];
              for(var p in obj)
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
              return str.join("&");
            },
            data: {
                'page':page,
                'category':PostsData.category,
                'search_type':PostsData.search_type,
                'search_value':PostsData.search_value,
                'type':PostsData.type,
                'sell_start':PostsData.sell_start,
                'sell_end':PostsData.sell_end,
                'full_start':PostsData.full_start,
                'full_end':PostsData.full_end,
                'month_deposit_start':PostsData.month_deposit_start,
                'month_deposit_end':PostsData.month_deposit_end,
                'month_start':PostsData.month_start,
                'month_end':PostsData.month_end
            }}).
            success(function(response, status, headers, config) {
                property = property.concat(response.posts);
                return response; 
            });
    };
    
    service.getMap = function(zoom, swlat,nelat,swlng,nelng){
        return $http({
            method: 'POST', 
            url: CompanyData.host + "/json/map_server_json/"+zoom+"/"+swlat+"/"+nelat+"/"+swlng+"/"+nelng,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
            var str = [];
            for(var p in obj)
              str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            return str.join("&");
            },
            data: {
              'category':PostsData.category, 
              'search_type':PostsData.search_type,
              'search_value':PostsData.search_value,
              'type':PostsData.type,
              'sell_start':PostsData.sell_start,
              'sell_end':PostsData.sell_end,
              'full_start':PostsData.full_start,
              'full_end':PostsData.full_end,
              'month_deposit_start':PostsData.month_deposit_start,
              'month_deposit_end':PostsData.month_deposit_end,
              'month_start':PostsData.month_start,
              'month_end':PostsData.month_end
                }}).
            success(function(response, status, headers, config) {
                property = property.concat(response.markers);
                return response;
            });
    };

    service.getDetail = function() { 
        for(i=0;i<property.length;i++){
            if(property[i].id == PropertyData.id){
                return property[i];
            }
        }
    };
    
    return service;
})

.factory('GalleryService',function($http, CompanyData){
    var service = {};
    service.getList = function(propertyId){
        return $http.get(CompanyData.host + "/json/gallery_json/"+propertyId).then(function(response){
                return response;
        });
    };
    return service;
})

.factory('PriceRange', function(){
    var data = {init: 0};
    return data;
})

.factory('ZzimService',function($http, CompanyData, PropertyData){
    var property = [];
    var service = {};

    service.getList = function(page){
        return $http.get(CompanyData.host + "/json/favorite_json/"+page).then(function(response){
            property = response.data.favorites;
            return response;
        });
    };

    service.getDetail = function() { 
        for(i=0;i<property.length;i++){
            if(property[i].id == PropertyData.id){
                return property[i];
            }
        }
    };

    return service;
})

.factory('SeenService',function($http, CompanyData, PropertyData){
    var property = [];
    var service = {};

    service.getList = function(page){
        return $http.get(CompanyData.host + "/json/seen_json/"+page).then(function(response){
            property = response.data.seens;
            return response;
        });
    };

    service.getDetail = function() { 
        for(i=0;i<property.length;i++){
            if(property[i].id == PropertyData.id){
                return property[i];
            }
        }
    };    
    return service;
})

.factory('MarkerData', function(){
    var data = {
        id:'',
        x:'',
        y:'',
        zoom : '',
        swlat: '',
        nelat: '',
        swlng: '',
        nelng: ''
    };

    return data;
})

.factory('PropertyData', function(){
    var data = {
        id:'',
        type:''
    };

    return data;
})

.factory('MarkerService',function($http, CompanyData, MarkerData, PropertyData){
    var property = [];
    var service = {};

    service.getList = function(){
        return $http.get(CompanyData.host + "/json/cluster_json/"+MarkerData.id+"/"+MarkerData.zoom+"/"+MarkerData.x+"/"+MarkerData.y+"/"+MarkerData.swlat+"/"+MarkerData.nelat+"/"+MarkerData.swlng+"/"+MarkerData.nelng).then(function(response){
            property = response.data.markers;
            return response;
        });
    };

    service.getDetail = function() { 
        for(i=0;i<property.length;i++){
            if(property[i].id == PropertyData.id){
                return property[i];
            }
        }
    };

    return service;
})

.factory('LoginService',function($http, CompanyData){
    var service = {};
    service.loginAction = function(param){

        return $http({
            method: 'POST', 
            url: CompanyData.host + "/json/login_action",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            transformRequest: function(obj) {
              var str = [];
              for(var p in obj)
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
              return str.join("&");
            },
            data: {
                'email':param.email,
                'password':param.password
                  }}).
            success(function(response, status, headers, config) {
                
            });
    };

    return service;
})

.factory('LocalService',function($http, CompanyData, PropertyData){
    
    var service = {};

    service.getList = function(){
        return $http.get(CompanyData.host + "/json/local_json/").then(function(response){
            return response;
        });
    };

    return service;
})

.factory('SubwayService',function($http, CompanyData, PropertyData){
    
    var service = {};

    service.getList = function(){
        return $http.get(CompanyData.host + "/json/subway_json/").then(function(response){
            return response;
        });
    };

    return service;
})
;