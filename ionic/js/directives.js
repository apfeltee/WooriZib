angular.module('dungziApp.directives', [])

.directive('postRepeatDirective',
    ['$timeout',
    function($timeout) {
        return function(scope) {
            if (scope.$first)
                    window.a = new Date();   // window.a can be updated anywhere if to reset counter at some action if ng-repeat is not getting started from $first
            if (scope.$last)
                $timeout(function(){
                    console.log("## DOM rendering list took: " + (new Date() - window.a) + " ms");
                });
        };
    }
])
 
.directive('fancySelect', 
    [
        '$ionicModal','$rootScope',
        function($ionicModal, $rootScope) {
            return {
                /* Only use as <fancy-select> tag */
                restrict : 'E',

                /* Our template */
                templateUrl: 'fancy-select.html',

                /* Attributes to set */
                scope: {
                    'items'        : '=', /* Items list is mandatory */
                    'text'         : '=', /* Displayed text is mandatory */
                    'value'        : '=', /* Selected value binding is mandatory */
                    'callback'     : '&'
                },

                link: function (scope, element, attrs) {

                    /* Default values */

                    scope.singleSelect   = attrs.singleSelect === 'true' ? true : false;
                    scope.localSelect   = attrs.localSelect === 'true' ? true : false;
                    scope.subwaySelect   = attrs.subwaySelect === 'true' ? true : false;
                    scope.allowEmpty    = attrs.allowEmpty === 'false' ? false : true;


                    /* Header used in ion-header-bar */
                    scope.headerText    = attrs.headerText || '';

                    /* Text displayed on label */
                    // scope.text          = attrs.text || '';
                    scope.defaultText   = scope.text || '';

                    /* Notes in the right side of the label */
                    scope.noteText      = attrs.noteText || '';
                    scope.noteImg       = attrs.noteImg || '';
                    scope.noteImgClass  = attrs.noteImgClass || '';
                    
                    /* Optionnal callback function */
                    // scope.callback = attrs.callback || null;

                    /* Instanciate ionic modal view and set params */

                    /* Some additionnal notes here : 
                     * 
                     * In previous version of the directive,
                     * we were using attrs.parentSelector
                     * to open the modal box within a selector. 
                     * 
                     * This is handy in particular when opening
                     * the "fancy select" from the right pane of
                     * a side view. 
                     * 
                     * But the problem is that I had to edit ionic.bundle.js
                     * and the modal component each time ionic team
                     * make an update of the FW.
                     * 
                     * Also, seems that animations do not work 
                     * anymore.
                     * 
                     */
                    $ionicModal.fromTemplateUrl(
                        'fancy-select-items.html',
                          {'scope': scope}
                    ).then(function(modal) {
                        scope.modal = modal;
                    });

                    /* Show list */
                    scope.showItems = function (event) {
                        event.preventDefault();
                        scope.modal.show();
                    }

                    /* Hide list */
                    scope.hideItems = function () {
                        scope.modal.hide();
						setTimeout(function() {
						    $rootScope.$broadcast("onRefresh", "");
						}, 400);
                    }

                    /* Destroy modal */
                    scope.$on('$destroy', function() {
                      scope.modal.remove();
                    });
                    
                    scope.selectall = function (){
                    	scope.text = "전체";
                    	scope.value = {parent_lat:"",parent_lng:"",gugun:"",address_id:"",id:""};
                    	scope.hideItems();
                    }

                    scope.selectsubway = function (item){
                        scope.text = item.name;
                        scope.value = item;
                        scope.hideItems();
                    }

                    scope.selectgugun = function (item){
                    	scope.text = item.gugun;
                    	scope.value = item;
                    	scope.hideItems();
                    }

                    /* Validate single with data */
                    scope.validateSingle = function (item) {
                    	
                        // Set selected text
                        scope.text = item.text;

                        // Set selected value
                        scope.value = item.id;

                        // Hide items
                        scope.hideItems();
                        
                        // Execute callback function
                        if (typeof scope.callback == 'function') {
                            scope.callback (scope.value);
                        }


                    }
                }
            };
        }
    ]
)