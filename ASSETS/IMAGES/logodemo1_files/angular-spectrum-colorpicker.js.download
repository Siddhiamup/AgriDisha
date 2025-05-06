/*!
 * angular-spectrum-colorpicker v1.4.5
 * https://github.com/Jimdo/angular-spectrum-colorpicker
 *
 * Angular directive for a colorpicker, that bases on http://bgrins.github.io/spectrum/
 * Idea from http://jsfiddle.net/g/LAJCa/
 *
 * Copyright 2016, Jimdo GmbH
 * Released under the MIT license
 */
(function(angular, undefined) {
  'use strict';

  // src/js/helper.module.js
  var angularSpectrumColorpicker = angular.module('angularSpectrumColorpicker', []);

  // src/js/spectrumColorpicker.directive.js
  (function(undefined) {
    'use strict';
    angularSpectrumColorpicker.directive('spectrumColorpicker', function() {
      return {
        restrict: 'EA',
        require: 'ngModel',
        scope: {
          fallbackValue: '=',
          disabled: '=?',
          format: '=?',
          options: '=?',
          triggerId: '@?',
  		palette : '=?',
          onChange: '&?',
          onShow: '&?',
          onHide: '&?',
          onMove: '&?',
  		  onDragstop: '&?',
          onBeforeShow: '&?',
  
  	    onChangeOptions: '=?',
  	    onShowOptions: '=?',
  	    onHideOptions: '=?',
  	    onMoveOptions: '=?'
        },
        replace: true,
        templateUrl: 'directive.html',
        link: function($scope, $element, attrs, $ngModel) {

		  console.log('Initialisation du color Picker');
          var $input = $element.find('input');
  
  
  		  $element.bind( "reflow", function() {
			//console.log('reflow');
		//	$input.spectrum('set', "#ff0000");
			$input.spectrum('reflow');

		  });
          function formatColor(tiny) {
            var formatted = tiny;
            if (formatted) {
              formatted = tiny.toString($scope.format);
            }
            return formatted;
          }
  
          function callOnChange(color) {
            if (angular.isFunction($scope.onChange)) {
				//console.log('isFunction');
              $scope.onChange({color: color});
            }
          }
  
          function setViewValue(color) {
            var value = $scope.fallbackValue;
  
            if (color) {
              value = formatColor(color);
            } else if (angular.isUndefined($scope.fallbackValue)) {
              value = color;
            }
  
            $ngModel.$setViewValue(value);
            callOnChange(value);
          }
  
          var onChange = function(color) {
            $scope.$evalAsync(function() {
              setViewValue(color);
            });
          };
          var onToggle = function() {
            $input.spectrum('toggle');
            return false;
          };
  
  

		  $element.bind( "foo", function() {
			  // console.log( "User clicked on 'foo.'" );
			//   $input.spectrum('set', "#00ff00");
		  });
		  
          var baseOpts = {
            color: $ngModel.$viewValue
          };
  
          var localOpts = {};
  
              angular.forEach({
                  'change': 'onChange',
                  'move': 'onMove',
                  'hide': 'onHide',
                  'show': 'onShow',
				  'dragstop' : 'onDragstop'
              },
              function (eventHandlerName, eventName) {
                  var spectrumEventHandlerOptions = $scope[eventHandlerName + 'Options'];
                  localOpts[eventName] = function (color) {
                      if (!spectrumEventHandlerOptions || spectrumEventHandlerOptions.update) {
                          onChange(color);
                      }
                      // we don't do this for change, because we expose the current
                      // value actively through the model
                      if (eventHandlerName !== 'change' && angular.isFunction($scope[eventHandlerName])) {
                          return $scope[eventHandlerName]({ color: formatColor(color) });
                      } else {
                          return null;
                      }
                 };
            });
  
          if (angular.isFunction($scope.onBeforeShow)) {
            localOpts.beforeShow = function(color) {
              return $scope.onBeforeShow({color: formatColor(color)});
            };
          }
  
  		if ($scope.palette) {
            localOpts.palette = $scope.palette;
          }
  
          var options = angular.extend({}, baseOpts, $scope.options, localOpts);
  
          if ($scope.triggerId) {
            angular.element(document.body).on('click', '#' + $scope.triggerId, onToggle);
          }
  
          $ngModel.$render = function() {
            $input.spectrum('set', $ngModel.$viewValue || '');
			
            callOnChange($ngModel.$viewValue);
          };
  
          if (options.color && options.color.substring(0,1) == '#') {
            $input.spectrum('set', options.color || '');
            setViewValue(options.color);
          }
  
  		$input.spectrum(options);
		/*
		var initialized = false;
      	$scope.init = function() {
            if (!initialized && ((options.flat !== true) || 
                element.parent().is(':visible'))) {
                initialized = true;
                $timeout(function() {
                    console.log('Redrawing spectrum...'); 
                    input.spectrum('reflow');
                });
            }
            return initialized;
        };*/

  
          $scope.$on('$destroy', function() {
            if ($scope.triggerId) {
              angular.element(document.body).off('click', '#' + $scope.triggerId, onToggle);
            }
          });
		  
		  if (angular.isFunction($scope.onDragstop)) {
			  $element.on('dragstop.spectrum', function(e,color){
				  $scope.onDragstop({e:e, color:color, rgb: formatColor(color)});
			  });
		  }
		  
		  
  		$element.on('$destroy', function(){
  			$input.spectrum('destroy');
  		});
  
          if(angular.isDefined(options.disabled)) {
            $scope.disabled = !!options.disabled;
          }
  
          $scope.$watch('disabled', function (newDisabled) {
            $input.spectrum(newDisabled ? 'disable' : 'enable');
          });
  
  		$scope.$watch('palette', function (palette) {

  		  $input.spectrum('option', 'palette', palette);
  		}, true);
        }
      };
    });
  })();

  // .tmp/all-partials.js
  angular.module('angularSpectrumColorpicker').run(['$templateCache', function($templateCache) {
    'use strict';
  
    $templateCache.put('directive.html',
      "<span><input class=\"input-small\"></span>"
    );
  
  }]);
})(window.angular);
