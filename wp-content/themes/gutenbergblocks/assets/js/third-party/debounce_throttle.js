// Simple and EZ JavaScript debounce(fire method after short delay) method without lodash/underscore
//
// Courtesy of JDMcKinstry
// https://github.com/JDMcKinstry/debounce
//

;(function(){function h(e,a,b,c){var d;return function(){void 0==c&&(c=this);d&&clearTimeout(d);a=a&&"object"==typeof a?Array.prototype.slice.call(a):void 0!=a?[a]:arguments;(0>b||"number"!=typeof b)&&(b=250);d=setTimeout(function(){e.apply(c,a)},b)}}function k(e,a,b,c){(0>b||"number"!=typeof b)&&(b=250);var d,f;return function(){void 0==c&&(c=this);a=a&&"object"==typeof a?Array.prototype.slice.call(a):void 0!=a?[a]:arguments;var g=+new Date;if(d&&g<d+b)return f&&clearTimeout(f),f=setTimeout(function(){d=
	g;e.apply(c,a)},b);d=g;return e.apply(c,a)}}window.hasOwnProperty("debounce")||(window.debounce=h);window.hasOwnProperty("throttle")||(window.throttle=k);window.hasOwnProperty("jQuery")&&(jQuery.debounce||(jQuery.debounce=h),jQuery.throttle||(jQuery.throttle=k))})();