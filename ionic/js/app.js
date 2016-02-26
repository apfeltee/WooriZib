// Mobionic: Mobile Ionic Framework

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'dungziApp' is the name of this angular module (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
angular.module('dungziApp', ['ionic', 'dungziApp.controllers', 'dungziApp.data', 'dungziApp.directives', 'dungziApp.filters', 'dungziApp.storage', 'ngSanitize', 'uiGmapgoogle-maps', 'ui.slider', 'pasvaz.bindonce'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if(window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if(window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
      
    // Open any external link with InAppBrowser Plugin
    $(document).on('click', 'a[href^=http], a[href^=https]', function(e){

        e.preventDefault();
        var $this = $(this); 
        var target = $this.data('inAppBrowser') || '_blank';

        window.open($this.attr('href'), target);

    });
      
    // Initialize Push Notifications
    var initPushwoosh = function() {
        var pushNotification = window.plugins.pushNotification;

		if(device.platform == "Android") {
			registerPushwooshAndroid();
		}
        if (device.platform == "iPhone" || device.platform == "iOS") {
            registerPushwooshIOS();
        }
    }
    
    // Uncomment the following initialization when you have made the appropriate configuration for iOS - http://goo.gl/YKQL8k and for Android - http://goo.gl/SPGWDJ
    // initPushwoosh();
      
  });
    
})

.run(function($rootScope, $ionicPlatform, $ionicHistory){
  $ionicPlatform.registerBackButtonAction(function(e){
    if ($rootScope.backButtonPressedOnceToExit) {
      ionic.Platform.exitApp();
    }

    else if ($ionicHistory.backView()) {
      $ionicHistory.goBack();
    }
    else {
      $rootScope.backButtonPressedOnceToExit = true;
      window.plugins.toast.showShortCenter(
        "뒤로가기를 한 번 더 클릭하시면 종료됩니다.",function(a){},function(b){}
      );
      setTimeout(function(){
        $rootScope.backButtonPressedOnceToExit = false;
      },2000);
    }
    e.preventDefault();
    return false;
  },101);

})

.config(function($stateProvider, $urlRouterProvider, $ionicConfigProvider) {
    
    // $ionicConfigProvider
    // http://ionicframework.com/docs/api/provider/%24ionicConfigProvider/
    $ionicConfigProvider.tabs.position('bottom');
    $ionicConfigProvider.navBar.alignTitle('center');
    
    $stateProvider

    .state('app', {
      url: "/app",
      abstract: true,
      templateUrl: "templates/menu.html",
      controller: 'MenuCtrl'
    })

    .state('app.home', {
      url: "/home",
      views: {
        'menuContent' :{
          templateUrl: "templates/home.html",
          controller: 'HomeCtrl'
        }
      }
    })

    .state('app.property', {
      url: "/property",
      views: {
        'menuContent' :{
          templateUrl: "templates/property.html",
          controller: 'PropertyCtrl'
        }
      }
    })

    .state('app.zzim', {
      url: "/zzim",
      views: {
        'menuContent' :{
          templateUrl: "templates/zzim.html",
          controller: 'ZzimCtrl'
        }
      }
    })

    .state('app.seen', {
      url: "/seen",
      views: {
        'menuContent' :{
          templateUrl: "templates/seen.html",
          controller: 'SeenCtrl'
        }
      }
    })

    .state('app.my', {
      url: "/my",
      views: {
        'menuContent' :{
          templateUrl: "templates/my.html",
          controller: 'MyCtrl'
        }
      }
    })

    // if none of the above states are matched, use this as the fallback
    $urlRouterProvider.otherwise('/app/home');
})