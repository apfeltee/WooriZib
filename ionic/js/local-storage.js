angular.module('dungziApp.storage', [])

.factory('UserStorage', function() {
  return {
    get: function() {
      var settings = window.localStorage['user_id'];
      if(settings) {
        return settings;
      }
      return "";
    },
    save: function(id) {
      window.localStorage['user_id'] = id;
    },
    clear: function() {
      window.localStorage.removeItem('user_id');
    }
  }
})