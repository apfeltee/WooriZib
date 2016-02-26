  
var MyNamespace = MyNamespace || {};
 MyNamespace.helpers = {
     price: function(item) {
        var css = "border-radius:5px;color:white;padding:1px 2px 1px 2px; font-size:12px;";
        var value = "";

        if(item.type=="sell"){
          value = "<span style='"+css+"background-color:#D22129;'>매매</span> " + "매매가 <strong style='color:#D22129'>" + item.sell_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
          if(item.lease_price!="0"){
            value = value + ", 융자 <strong style='color:#D22129'>" + item.lease_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원";
          }
        } else if(item.type=="installation"){
          if(item.sell_price!="0"){
            value = "<span style='"+css+"background-color:#f39c12;'>분양</span> " + "실입주 <strong style='color:#f39c12'>" + item.sell_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원 " ;
          }
          if(item.lease_price!="0"){
            value = value + ", 분양가 <strong style='color:#f39c12'>" + item.lease_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원";
          }
        } else if(item.type=="full_rent"){
          value = "<span style='"+css+"background-color:#3865C0;'>전세</span> "  + "전세가 <strong style='color:#3865C0'>" + item.full_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
        } else if(item.type=="monthly_rent"){
          value = "<span style='"+css+"background-color:#209F4E;'>월세</span> " + "보증금 <strong style='color:#209F4E'>" + item.monthly_rent_deposit.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
          value = value + ", 월세 <strong style='color:#209F4E'>" + item.monthly_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
          if(item.premium_price!="0"){
            value = value + ", <strong style='color:#209F4E'> 권리금" + item.premium_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원";
          }

        } else if(item.type=="rent"){
          value = "<span style='"+css+"background-color:#3865C0;'>전/월세</span> " + "전세가 <strong style='color:#3865C0'>" + item.full_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
          value = value + "<span style='"+css+"background-color:#209F4E;'>월세</span> " + "보증금 <strong style='color:#3865C0'>" + item.monthly_rent_deposit.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
          value = value + ", 월세 <strong style='color:#209F4E'>" + item.monthly_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + "</strong> 만원" ;
        }
        return value;
     },
     price_text: function(item){
        var value = "";

        if(item.type=="sell"){
          value = "[매매] " + item.sell_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
          if(item.lease_price!="0"){
            value = value + ", 융자 " + item.lease_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원";
          }
        } else if(item.type=="installation"){
          value = "[분양] " + "실입주 " + item.sell_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
          if(item.lease_price!="0"){
            value = value + ", 분양가 " + item.lease_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원";
          }
        } else if(item.type=="full_rent"){
          value = "[전세] " + item.full_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
        } else if(item.type=="monthly_rent"){
          value = "[월세] " + item.monthly_rent_deposit.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
          value = value + " / " + item.monthly_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
          if(item.premium_price!="0"){
            value = value + ",  권리" + item.premium_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원";
          }

        } else if(item.type=="rent"){
          value = "[전세] " + "전세가 " + item.full_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
          value = value + "[월세] " + item.monthly_rent_deposit.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
          value = value + " / " + item.monthly_rent_price.toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) + " 만원" ;
        }
        return value;
     }
   };
   

angular.module('dungziApp.controllers', [])

.controller('HomeCtrl', function($ionicModal, $rootScope, $scope, $http, $sce, $ionicScrollDelegate, CompanyData, CategoryData, PostsData, PropertyService,PropertyData, MarkerData, LocalService, SubwayService) {


  /** top seach option start **/

  $scope.categoryies_label = '매물유형';
  $scope.types_label = '거래유형';
  $scope.locals_label = '지역';
  $scope.subways_label = '지하철';
  
  $scope.local = {parent_lat:"",parent_lng:"",gugun:"",address_id:"",id:""};
  $scope.subway = {hosun:"",lat:"",lng:"",name:"",id:"",hosun_id:""};

  $scope.types = [
    {id: '', text: '전체', checked: false}, 
    {id: 'sell', text: '매매', checked: false}, 
    {id: 'full_rent', text: '전세', checked: false}, 
    {id : 'monthly_rent', text: '월세', checked: true}];

  LocalService.getList().then(function(response){
    $scope.locals = response.data;
  });

  SubwayService.getList().then(function(response){
    $scope.subways = response.data;
  });

  /** top seach option end **/

  /** marker cluster modal **/
  $ionicModal.fromTemplateUrl('templates/marker.html', {
    scope: $scope,
    animation: 'slide-in-up',//'slide-left-right', 'slide-in-up', 'slide-right-left', 'slide-in-left'
  }).then(function(modal) {
    $scope.marker_modal = modal;
  });

  $scope.show_marker = function() {
    $scope.marker_modal.show();
  };

  $scope.closeMarkerModal = function() {
    $scope.marker_modal.hide();
  };

  /** modal end **/

  $scope.listtype = "list";
  $scope.type = "";
  $scope.category = "";
  $scope.pageSize = CompanyData.pageSize;
  $scope.posts = [];
  $scope.page = 0;
  $scope.company = CompanyData;

  var lat = CompanyData.center.latitude;
  var lng = CompanyData.center.longitude;
  var mapsVal;

  if(PostsData.lat!='') lat = PostsData.lat;
  if(PostsData.lng!='') lng = PostsData.lng;

    $scope.map = {
      center: {latitude: lat, longitude: lng }, 
      zoom: 14,
      icon: "img/nomarker.png",
      events: {
        tilesloaded: function (maps) {
          mapsVal = maps;
          //$scope.$apply(function () {
          //$scope.mapInstance = maps;
        },
        resize: function(maps){
          console.log("resize");
        },
        idle: function(maps){
          PostsData.lat = maps.getCenter().lat();
          PostsData.lng = maps.getCenter().lng();

          $scope.moveMap(maps);

          /**
          var bounds = new google.maps.LatLngBounds();
          for (var i in $scope.map.markers) {
            bounds.extend($scope.map.markers[i].location);
          }
          $scope.map.fitBounds(bounds);
          **/
        }
      },
      window: {
        marker: {},
        show: false,
        closeClick: function() {
            this.show = false;
        },
        options: {disableAutoPan:true , maxWidth:250} // define when map is ready
      }
    };


    /** 매물 유형 가져오기 **/
    $scope.categories = [];    
    $http({method: 'GET', url: CompanyData.host + "/json/category_json"}).
    success(function(data, status, headers, config) {
        $scope.categories = $scope.categories.concat({name:"전체유형",text:"전체유형",id:""});
        $scope.categories = $scope.categories.concat(data.categories);
        CategoryData.setData(data.categories); //저장
    }).
    error(function(data, status, headers, config) {

    });

    $scope.setListtype = function(type){

        $scope.listtype = type;
        if(type=="map"){
            $scope.render = true;
            $ionicScrollDelegate.scrollTop();
			setTimeout(function() {
              if ( angular.isDefined( mapsVal ) ) {
                google.maps.event.trigger(mapsVal, 'resize');
              }
            }, 300);      
            
        }
        
    };
    
    $scope.go=function(v,x,y){
      MarkerData.id = v;
      MarkerData.x = x;
      MarkerData.y = y;
      $rootScope.$broadcast("onMarker", "");
      $scope.show_marker();
    }

    // 홈에서 최신 목록 가져오기
    $scope.loadData = function(){

      if(mapsVal!=undefined) {
        $scope.moveMap(mapsVal);
      }

      PropertyService.getList($scope.page).then(function(response){
          
          $scope.more = response.data.pages !== $scope.page;
          $scope.posts = $scope.posts.concat(response.data.posts);
          $scope.total = response.data.total;

          if($scope.total==0){
            $scope.msg = CompanyData.property + "이 없습니다.";  
          } else {
            $scope.msg = "";
          }
      });
    }


    $scope.moveMap = function(maps){
      MarkerData.zoom = maps.getZoom();
      MarkerData.swlat = maps.getBounds().getSouthWest().lat();
      MarkerData.nelat = maps.getBounds().getNorthEast().lat();
      MarkerData.swlng = maps.getBounds().getSouthWest().lng();
      MarkerData.nelng = maps.getBounds().getNorthEast().lng();

      PropertyService.getMap(maps.getZoom(), maps.getBounds().getSouthWest().lat(), maps.getBounds().getNorthEast().lat(), maps.getBounds().getSouthWest().lng(), maps.getBounds().getNorthEast().lng()).then(function(response){
          $scope.map.markers = response.data.markers;
      });

    }

    $scope.hasMoreItems = function(){
      return $scope.page+$scope.pageSize < $scope.total;
    }

    $scope.showMoreItems = function(){
      $scope.page=$scope.page+$scope.pageSize;
      console.log($scope.page);
      $scope.msg = "로딩중...";   
      $scope.loadData();

      setTimeout(function() {
     
      $scope.$broadcast('scroll.infiniteScrollComplete');
      
      }, 1000);      


      //$scope.$broadcast('scroll.infiniteScrollComplete');
    }

    $scope.renderHtml = function (htmlCode) {
      return $sce.trustAsHtml(htmlCode);
    };

    $scope.hope_add = function (post){
      $http({method:'GET',url:CompanyData.host + "/json/hope_add/"+post.id}).
      success(function(data,status,headers,config){
        post.hope_cnt = 1;
      })
    }

    $scope.hope_remove = function (post){
      $http({method:'GET',url:CompanyData.host + "/json/hope_remove/"+post.id}).
      success(function(data,status,headers,config){
        post.hope_cnt = 0;
      })
    }

    /** 매물 상세 정보 보여주기 **/
    $scope.open = function(id, from){
      console.log
      PropertyData.id = id;
      PropertyData.type = from;
      $ionicModal.fromTemplateUrl('templates/property.html', {
        scope: $scope,
        animation: 'slide-in-left'//'slide-left-right', 'slide-in-up', 'slide-right-left', 'slide-in-left'
      }).then(function(modal) {
        $scope.property_modal = modal;
        $scope.property_modal.show();
      });

      $scope.closePropertyModal = function() {
        $scope.property_modal.hide();
      };
    }

    $rootScope.$on('onRefresh', function(e) {
      PostsData.category = $scope.category;
      PostsData.type = $scope.type;
      if($scope.local.id!=""){
        PostsData.search_type   = "address";
        PostsData.search_value  = $scope.local.address_id;
        PostsData.lat = $scope.local.parent_lat;
        PostsData.lng = $scope.local.parent_lng;
        PostsData.name= $scope.local.gugun;
      }

      if($scope.subway.id!=""){
        PostsData.search_type   = "subway";
        PostsData.search_value  = $scope.subway.id;
        PostsData.lat = $scope.subway.lat;
        PostsData.lng = $scope.subway.lng;
        PostsData.name= $scope.subway.name;
      } 

      if($scope.subway.id=="" && $scope.local.id==""){
        PostsData.search_type   = "";
        PostsData.search_value  = "";
        PostsData.lat = "";
        PostsData.lng = "";
        PostsData.name= "";
      }

      $scope.msg = "로딩중...";   
      $scope.posts = [];
      $scope.page = 0;
      $scope.loadData(); 
      $ionicScrollDelegate.$getByHandle('mainScroll').scrollTop();
    });

    $scope.helpers = MyNamespace.helpers;
    $scope.loadData();
})

.controller('MyCtrl', function($ionicModal, $rootScope, $scope, $http, $sce, $ionicScrollDelegate, CompanyData, CategoryData, PostsData, PropertyService,PropertyData, MarkerData, LocalService, SubwayService) {

  $scope.categoryies_label = '매물유형';
  $scope.types_label = '거래유형';
  $scope.locals_label = '지역';
  $scope.subways_label = '지하철';
  
  $scope.local = {parent_lat:"",parent_lng:"",gugun:"",address_id:"",id:""};
  $scope.subway = {hosun:"",lat:"",lng:"",name:"",id:"",hosun_id:""};

  $scope.types = [
    {id: '', text: '전체', checked: false}, 
    {id: 'sell', text: '매매', checked: false}, 
    {id: 'full_rent', text: '전세', checked: false}, 
    {id : 'monthly_rent', text: '월세', checked: true}];

  LocalService.getList().then(function(response){
    $scope.locals = response.data;
  });

  SubwayService.getList().then(function(response){
    $scope.subways = response.data;
  });


  /** top seach option end **/

  /** marker cluster modal **/
  $ionicModal.fromTemplateUrl('templates/marker.html', {
    scope: $scope,
    animation: 'slide-in-up',//'slide-left-right', 'slide-in-up', 'slide-right-left', 'slide-in-left'
  }).then(function(modal) {
    $scope.marker_modal = modal;
  });

  $scope.show_marker = function() {
    $scope.marker_modal.show();
  };

  $scope.closeMarkerModal = function() {
    $scope.marker_modal.hide();
  };

  /** modal end **/

  $scope.listtype = "list";
  $scope.type = "";
  $scope.category = "";
  $scope.pageSize = CompanyData.pageSize;
  $scope.posts = [];
  $scope.page = 0;
  $scope.company = CompanyData;

  var lat = CompanyData.center.latitude;
  var lng = CompanyData.center.longitude;
  var mapsVal;

  if(PostsData.lat!='') lat = PostsData.lat;
  if(PostsData.lng!='') lng = PostsData.lng;

    $scope.map = {
      center: {latitude: lat, longitude: lng }, 
      zoom: 14,
      icon: "img/nomarker.png",
      events: {
        tilesloaded: function (maps) {
          mapsVal = maps;
          //$scope.$apply(function () {
          //$scope.mapInstance = maps;
        },
        resize: function(maps){
          console.log("resize");
        },
        idle: function(maps){
          PostsData.lat = maps.getCenter().lat();
          PostsData.lng = maps.getCenter().lng();

          $scope.moveMap(maps);
        }
      },
      window: {
        marker: {},
        show: false,
        closeClick: function() {
            this.show = false;
        },
        options: {disableAutoPan:true , maxWidth:250} // define when map is ready
      }
    };


    /** 매물 유형 가져오기 **/
    $scope.categories = [];    
    $http({method: 'GET', url: CompanyData.host + "/json/category_json"}).
    success(function(data, status, headers, config) {
        $scope.categories = $scope.categories.concat({name:"전체유형",text:"전체유형",id:""});
        $scope.categories = $scope.categories.concat(data.categories);
        CategoryData.setData(data.categories); //저장
    }).
    error(function(data, status, headers, config) {

    });

    $scope.setListtype = function(type){

        $scope.listtype = type;
        if(type=="map"){
            $scope.render = true;
            $ionicScrollDelegate.scrollTop();
        } else {
		  /** 여긴 추가한 건데 맞겠지? **/
          $scope.page = 0;
          $scope.loadData();
        }
        
    };
    
    $scope.go=function(v,x,y){
      MarkerData.id = v;
      MarkerData.x = x;
      MarkerData.y = y;
      $rootScope.$broadcast("onMarker", "");
      $scope.show_marker();
    }

    // 홈에서 최신 목록 가져오기
    $scope.loadData = function(){

      if(mapsVal!=undefined) {
        $scope.moveMap(mapsVal);
      }

      PropertyService.getList($scope.page).then(function(response){
          
          $scope.more = response.data.pages !== $scope.page;
          $scope.posts = $scope.posts.concat(response.data.posts);
          //$scope.posts = data.posts;
          $scope.total = response.data.total;

          if($scope.total==0){
            $scope.msg = CompanyData.property + "이 없습니다.";  
          } else {
            $scope.msg = "";
          }

        //setTimeout(function() {
        //  ionic.material.ink.displayEffect();
        //}, 10);
      });
    }


    $scope.moveMap = function(maps){
      MarkerData.zoom = maps.getZoom();
      MarkerData.swlat = maps.getBounds().getSouthWest().lat();
      MarkerData.nelat = maps.getBounds().getNorthEast().lat();
      MarkerData.swlng = maps.getBounds().getSouthWest().lng();
      MarkerData.nelng = maps.getBounds().getNorthEast().lng();

      PropertyService.getMap(maps.getZoom(), maps.getBounds().getSouthWest().lat(), maps.getBounds().getNorthEast().lat(), maps.getBounds().getSouthWest().lng(), maps.getBounds().getNorthEast().lng()).then(function(response){
          $scope.map.markers = response.data.markers;
      });

    }

    $scope.hasMoreItems = function(){
      return $scope.page+$scope.pageSize < $scope.total;
    }

    $scope.showMoreItems = function(type){
      console.log($scope.page)
      $scope.page=$scope.page+$scope.pageSize;
      $scope.msg = "로딩중...";   
      $scope.loadData();
    }

    $scope.renderHtml = function (htmlCode) {
      return $sce.trustAsHtml(htmlCode);
    };

    $scope.hope_add = function (post){
      $http({method:'GET',url:CompanyData.host + "/json/hope_add/"+post.id}).
      success(function(data,status,headers,config){
        post.hope_cnt = 1;
      })
    }

    $scope.hope_remove = function (post){
      $http({method:'GET',url:CompanyData.host + "/json/hope_remove/"+post.id}).
      success(function(data,status,headers,config){
        post.hope_cnt = 0;
      })
    }

    /** 매물 상세 정보 보여주기 **/
    $scope.open = function(id, from){
      PropertyData.id = id;
      PropertyData.type = from;
      $ionicModal.fromTemplateUrl('templates/property.html', {
        scope: $scope,
        animation: 'slide-in-left'//'slide-left-right', 'slide-in-up', 'slide-right-left', 'slide-in-left'
      }).then(function(modal) {
        $scope.property_modal = modal;
        $scope.property_modal.show();
      });

      $scope.closePropertyModal = function() {
        $scope.property_modal.hide();
      };
    }

    $rootScope.$on('onRefresh', function(e) {
      PostsData.category = $scope.category;
      PostsData.type = $scope.type;
      if($scope.local.id!=""){
        PostsData.search_type   = "address";
        PostsData.search_value  = $scope.local.address_id;
        PostsData.lat = $scope.local.parent_lat;
        PostsData.lng = $scope.local.parent_lng;
        PostsData.name= $scope.local.gugun;
      }

      if($scope.subway.id!=""){
        PostsData.search_type   = "subway";
        PostsData.search_value  = $scope.subway.id;
        PostsData.lat = $scope.subway.lat;
        PostsData.lng = $scope.subway.lng;
        PostsData.name= $scope.subway.name;
      } 

      if($scope.subway.id=="" && $scope.local.id==""){
        PostsData.search_type   = "";
        PostsData.search_value  = "";
        PostsData.lat = "";
        PostsData.lng = "";
        PostsData.name= "";
      }

      $scope.msg = "로딩중...";   
      $scope.posts = [];
      $scope.page = 0;
      $scope.loadData(); 
      $ionicScrollDelegate.$getByHandle('mainScroll').scrollTop();
    });

    $scope.helpers = MyNamespace.helpers;
    $scope.loadData();
})

.controller('MenuCtrl', function( $rootScope, $ionicModal, $scope, $http, CompanyData, LoginService, UserStorage) {
  

  $scope.company = CompanyData;
  $scope.member_id = UserStorage.get();
  $scope.msg = "";
  $scope.loginData = {};

  $ionicModal.fromTemplateUrl('templates/login.html', {
    scope: $scope,
    focusFirstInput: true
  }).then(function(modal) {
    $scope.modal = modal;
  });

  $scope.login = function() {
    $scope.modal.show();
  };

  $scope.closeLogin = function() {
    $scope.modal.hide();
  },

 $scope.$on('$destroy', function() {
    $scope.modal.remove();
  });

  $ionicModal.fromTemplateUrl('templates/register.html', {
    scope: $scope,
    focusFirstInput: true
  }).then(function(modal) {
    $scope.register_modal = modal;
  });

  $scope.closeRegisterModal = function(){
    $scope.register_modal.hide();
  }

  $ionicModal.fromTemplateUrl('templates/price.html', {
    scope: $scope,
    focusFirstInput: true
  }).then(function(modal) {
    $scope.price_modal = modal;
  });

 $scope.price = function() {
    $scope.price_modal.show();
    $rootScope.$broadcast("onPrice", "");
  };

  $scope.closePrice = function() {
    $scope.price_modal.hide();
  },

  $scope.logout = function() {
    $scope.id = "";
    UserStorage.clear();
  };

  $scope.doLogin = function() {
    LoginService.loginAction($scope.loginData).then(function(response){
      if(response.data.id!=""){

        UserStorage.save(response.data.id);
        $scope.member_id = UserStorage.get();
        $scope.loginData.password = "";
        $scope.modal.hide();

      } else {
        $scope.msg = "로그인에 실패하였습니다.";
      }
    });
  };

  $scope.register = function(){
    $scope.register_modal.show();
  }


  /*** 매물 등록 시작 ***/


  $ionicModal.fromTemplateUrl('templates/add.html', {
    scope: $scope,
    focusFirstInput: true
  }).then(function(modal) {
    $scope.add_modal = modal;
  });

  $scope.add = function(){
    $scope.add_modal.show();
  }

  $scope.closeAdd = function() {
    $scope.add_modal.hide();
  }

  /*** 매물 등록 종료 ***/

})

/**
 * 2015년 5월 20일 - 기존에는 products에 thumb_name필드가 있었으나 이 기능이 삭제되어 슬라이드 보여지는 부분을 수정하였다.
 **/
.controller('PropertyCtrl', function($scope, $sce, $http, CompanyData, PropertyService, PropertyData, ZzimService, SeenService, MarkerService, GalleryService, $ionicSlideBoxDelegate) {

  $scope.id = PropertyData.id;
  if(PropertyData.type=="zzim"){
    $scope.post = ZzimService.getDetail();
  } else if(PropertyData.type=="seen"){
    $scope.post = SeenService.getDetail();      
  } else if(PropertyData.type=="marker"){
    $scope.post = MarkerService.getDetail();      
  } else {
    $scope.post = PropertyService.getDetail();
  }

  /** subway **/
  $http({method:'GET',url:CompanyData.host + "/json/subway_one_json/"+$scope.id}).
  success(function(data,status,headers,config){
    $scope.subways = data.subways;
  })

  /** 관심 **/
  $http({method:'GET',url:CompanyData.host + "/json/hope_json/"+$scope.id}).
  success(function(data,status,headers,config){
    $scope.hope = data.hope;
  })

  /** gallery **/

  $scope.slides = [];
  
  var i = 0;

  GalleryService.getList($scope.id).then(function(response){
    
    $scope.galleries = response.data.galleries;
    angular.forEach($scope.galleries,function(item,index){
      i=i+1;
      $scope.slides.push({filename: CompanyData.host + "/uploads/gallery/"+$scope.id+"/"+item.filename,item: (i)});
    });

    if(i>0) $ionicSlideBoxDelegate.update();

  });

  $scope.hope_add = function (id){
    $http({method:'GET',url:CompanyData.host + "/json/hope_add/"+$scope.id}).
    success(function(data,status,headers,config){
      $scope.hope = 1;
    })
  }

  $scope.hope_remove = function (id){
    $http({method:'GET',url:CompanyData.host + "/json/hope_remove/"+$scope.id}).
    success(function(data,status,headers,config){
      $scope.hope = 0;
    })
  }

  $scope.helpers = MyNamespace.helpers;
  $scope.renderHtml = function (htmlCode) {
    return $sce.trustAsHtml(htmlCode);
  };
})

.controller('PriceCtrl', function($state, $rootScope, $scope, $http, PostsData, PriceRange, CompanyData) {
  
    $scope.loadData = function () {
      
      $http({method:'GET',url:CompanyData.host + "/json/price_setting_json"}).
          success(function(data,status,headers,config){
            $scope.sellprice = {"maxBasic":data.price.sellprice,"minVal":0,"maxVal":data.price.sellprice};
            $scope.fullrentprice = {"maxBasic":data.price.fullrentprice,"minVal":0,"maxVal":data.price.fullrentprice};
            $scope.rentdepositprice = {"maxBasic":data.price.rentdepositprice,"minVal":0,"maxVal":data.price.rentdepositprice};
            $scope.rentmonthprice = {"maxBasic":data.price.rentmonthprice,"minVal":0,"maxVal":data.price.rentmonthprice};
            
            PriceRange.maxBasic = data.price;
            //$scope.save(); -- 불필요한 호출이라서 주석처리하였다.
       })
    };

    $rootScope.$on('onPrice', function(e) {

      if(PriceRange.init==0){
        $scope.sellprice = {"maxBasic":0,"minVal":0,"maxVal":0};
        $scope.fullrentprice = {"maxBasic":0,"minVal":0,"maxVal":0};
        $scope.rentdepositprice = {"maxBasic":0,"minVal":0,"maxVal":0};
        $scope.rentmonthprice = {"maxBasic":0,"minVal":0,"maxVal":0};
       
        $scope.loadData();
        PriceRange.init = 1;

      } else {
        $scope.sellprice = {"maxBasic":PriceRange.maxBasic.sellprice,"minVal":PostsData.amount_sell_start,"maxVal":PostsData.amount_sell_end};
        $scope.fullrentprice = {"maxBasic":PriceRange.maxBasic.fullrentprice,"minVal":PostsData.amount_full_start,"maxVal":PostsData.amount_full_end};
        $scope.rentdepositprice = {"maxBasic":PriceRange.maxBasic.rentdepositprice,"minVal":PostsData.amount_rent_deposit_start,"maxVal":PostsData.amount_rent_deposit_end};
        $scope.rentmonthprice = {"maxBasic":PriceRange.maxBasic.rentmonthprice,"minVal":PostsData.amount_rent_monthly_start,"maxVal":PostsData.amount_rent_monthly_end};
      }

    });

    $scope.save = function(){
      PostsData.amount_sell_start = $scope.sellprice.minVal;
      PostsData.amount_sell_end = $scope.sellprice.maxVal;

      PostsData.amount_full_start  = $scope.fullrentprice.minVal;
      PostsData.amount_full_end = $scope.fullrentprice.maxVal;

      PostsData.amount_rent_deposit_start = $scope.rentdepositprice.minVal;
      PostsData.amount_rent_deposit_end = $scope.rentdepositprice.maxVal;

      PostsData.amount_rent_monthly_start = $scope.rentmonthprice.minVal;
      PostsData.amount_rent_monthly_end = $scope.rentmonthprice.maxVal; 
      $rootScope.$broadcast("onRefresh", "");
    }

    $scope.currencyFormatting = function(value) { return value.toString() + " 원" }

})

.controller('ZzimCtrl',function($sce, $ionicModal,$scope, $http, $sce, PostsData, CompanyData, ZzimService, PropertyData) {
	$scope.pageSize = CompanyData.pageSize;
	$scope.posts = [];
	$scope.page = 0;
	$scope.company = CompanyData;
	$scope.loadData = function(){
		ZzimService.getList($scope.page).then(function(response){
			$scope.more = response.data.favorites !== $scope.page;
			$scope.posts = $scope.posts.concat(response.data.favorites);
			$scope.total = response.data.total;

			if($scope.total==0){
				$scope.msg = "찜한 "+ CompanyData.property + "이 없습니다.";  
			} else {
				$scope.msg = "";
			}
		});
	}

  $scope.renderHtml = function (htmlCode) {
    return $sce.trustAsHtml(htmlCode);
  };

  /**
  $scope.hope_add = function (post){
    $http({method:'GET',url:CompanyData.host + "/json/hope_add/"+post.id}).
    success(function(data,status,headers,config){
      post.hope_cnt = 1;
    })
  }

  $scope.hope_remove = function (post){
    $http({method:'GET',url:CompanyData.host + "/json/hope_remove/"+post.id}).
    success(function(data,status,headers,config){
      post.hope_cnt = 0;
    })
  }    
  **/

  $scope.helpers = MyNamespace.helpers;
  $scope.loadData();
})

.controller('SeenCtrl',function($sce, $ionicModal, $scope, $http, PostsData, CompanyData, SeenService, PropertyData) {
	$scope.pageSize = CompanyData.pageSize;
	$scope.posts = [];
	$scope.page = 0;
	$scope.company = CompanyData;

	$scope.loadData = function(){
		SeenService.getList($scope.page).then(function(response){
			$scope.more = response.data.seens !== $scope.page;
			$scope.posts = $scope.posts.concat(response.data.seens);
			$scope.total = response.data.total;

			if($scope.total==0){
				$scope.msg = "본 " + CompanyData.property + "이 없습니다.";  
			} else {
				$scope.msg = "";
			}
		});
	}

  $scope.renderHtml = function (htmlCode) {
    return $sce.trustAsHtml(htmlCode);
  };  
  
  /**

  $scope.hope_add = function (post){
    $http({method:'GET',url:CompanyData.host + "/json/hope_add/"+post.id}).
    success(function(data,status,headers,config){
      post.hope_cnt = 1;
    })
  }

  $scope.hope_remove = function (post){
    $http({method:'GET',url:CompanyData.host + "/json/hope_remove/"+post.id}).
    success(function(data,status,headers,config){
      post.hope_cnt = 0;
    })
  }    

  **/

  $scope.open = function(id, from){
    PropertyData.id = id;
    PropertyData.type = from;
    $ionicModal.fromTemplateUrl('templates/property.html', {
      scope: $scope,
      animation: 'slide-in-left'//'slide-left-right', 'slide-in-up', 'slide-right-left', 'slide-in-left'
    }).then(function(modal) {
      $scope.property_modal = modal;
      $scope.property_modal.show();
    });

    $scope.closePropertyModal = function() {
      $scope.property_modal.hide();
    };
  }
    
  $scope.helpers = MyNamespace.helpers;
  $scope.loadData();
})


.controller('MarkerCtrl',function($ionicModal, $rootScope, $scope, $http, $sce, PostsData, CompanyData, MarkerData, MarkerService, PropertyData) {
  $scope.posts = [];
  $scope.company = CompanyData;

  $scope.loadData = function(){
    MarkerService.getList().then(function(response){
      $scope.posts = response.data.markers;

      if($scope.total==0){
        $scope.msg = CompanyData.property + "이 없습니다.";  
      } else {
        $scope.msg = "";
      }
    });
  }

  $scope.renderHtml = function (htmlCode) {
    return $sce.trustAsHtml(htmlCode);
  };
  
  $scope.hope_add = function (post){
    $http({method:'GET',url:CompanyData.host + "/json/hope_add/"+post.id}).
    success(function(data,status,headers,config){
      post.hope_cnt = 1;
    })
  }

  $scope.hope_remove = function (post){
    $http({method:'GET',url:CompanyData.host + "/json/hope_remove/"+post.id}).
    success(function(data,status,headers,config){
      post.hope_cnt = 0;
    })
  }    

  /** 매물 상세 정보 보여주기 **/
  $scope.open = function(id, from){
    PropertyData.id = id;
    PropertyData.type = from;
    $ionicModal.fromTemplateUrl('templates/property.html', {
      scope: $scope,
      animation: 'slide-in-left'//'slide-left-right', 'slide-in-up', 'slide-right-left', 'slide-in-left'
    }).then(function(modal) {
      $scope.property_modal = modal;
      $scope.property_modal.show();
    });


    $scope.closePropertyModal = function() {
      $scope.property_modal.hide();
    };
  }

  $scope.helpers = MyNamespace.helpers;
  $rootScope.$on('onMarker', function(e) {
      $scope.loadData();
  });
})