/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./js/block.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./js/block.js":
/*!*********************!*\
  !*** ./js/block.js ***!
  \*********************/
/*! exports provided: metadata, name */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
eval("__webpack_require__.r(__webpack_exports__);\n/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, \"name\", function() { return name; });\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./style.scss */ \"./js/style.scss\");\n/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_style_scss__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./editor.scss */ \"./js/editor.scss\");\n/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_editor_scss__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ \"./js/block.json\");\nvar _block_json__WEBPACK_IMPORTED_MODULE_2___namespace = /*#__PURE__*/__webpack_require__.t(/*! ./block.json */ \"./js/block.json\", 1);\n/* harmony reexport (default from named exports) */ __webpack_require__.d(__webpack_exports__, \"metadata\", function() { return _block_json__WEBPACK_IMPORTED_MODULE_2__; });\nvar _wp$editor=wp.editor,RichText=_wp$editor.RichText,MediaUpload=_wp$editor.MediaUpload,PlainText=_wp$editor.PlainText,registerBlockType=wp.blocks.registerBlockType,Button=wp.components.Button;// Import our CSS files\nvar name=_block_json__WEBPACK_IMPORTED_MODULE_2__.name;registerBlockType('card-block/main',{title:'Card',icon:'heart',category:'common',attributes:{title:{source:'text',selector:'.card__title'},body:{type:'array',source:'children',selector:'.card__body'},imageAlt:{attribute:'alt',selector:'.card__image'},imageUrl:{attribute:'src',selector:'.card__image'}},edit:function f(a){var b=a.attributes,c=a.className,d=a.setAttributes,e=function(a){return b.imageUrl?wp.element.createElement('img',{src:b.imageUrl,onClick:a,className:'image'}):wp.element.createElement('div',{className:'button-container'},wp.element.createElement(Button,{onClick:a,className:'button button-large'},'Pick an image'))};return wp.element.createElement('div',{className:'container'},wp.element.createElement(MediaUpload,{onSelect:function b(a){d({imageAlt:a.alt,imageUrl:a.url})},type:'image',value:b.imageID,render:function c(a){var b=a.open;return e(b)}}),wp.element.createElement(PlainText,{onChange:function b(a){return d({title:a})},value:b.title,placeholder:'Your card title',className:'heading'}),wp.element.createElement(RichText,{onChange:function b(a){return d({body:a})},value:b.body,multiline:'p',placeholder:'Your card text',formattingControls:['bold','italic','underline'],isSelected:b.isSelected}))},save:function c(a){var b=a.attributes;return wp.element.createElement('div',{className:'card'},function c(a,b){// No alt set, so let's hide it from screen readers\nreturn a?b?wp.element.createElement('img',{className:'card__image',src:a,alt:b}):wp.element.createElement('img',{className:'card__image',src:a,alt:'',\"aria-hidden\":'true'}):null}(b.imageUrl,b.imageAlt),wp.element.createElement('div',{className:'card__content'},wp.element.createElement('h3',{className:'card__title'},b.title),wp.element.createElement('div',{className:'card__body'},b.body)))}});\n\n//# sourceURL=webpack:///./js/block.js?");

/***/ }),

/***/ "./js/block.json":
/*!***********************!*\
  !*** ./js/block.json ***!
  \***********************/
/*! exports provided: name, category, attributes, default */
/***/ (function(module) {

eval("module.exports = JSON.parse(\"{\\\"name\\\":\\\"card-block/main\\\",\\\"category\\\":\\\"common\\\",\\\"attributes\\\":{\\\"title\\\":{\\\"source\\\":\\\"text\\\",\\\"selector\\\":\\\".card__title\\\"},\\\"body\\\":{\\\"type\\\":\\\"array\\\",\\\"source\\\":\\\"children\\\",\\\"selector\\\":\\\".card__body\\\"},\\\"imageAlt\\\":{\\\"attribute\\\":\\\"alt\\\",\\\"selector\\\":\\\".card__image\\\"},\\\"imageUrl\\\":{\\\"attribute\\\":\\\"src\\\",\\\"selector\\\":\\\".card__image\\\"}}}\");\n\n//# sourceURL=webpack:///./js/block.json?");

/***/ }),

/***/ "./js/editor.scss":
/*!************************!*\
  !*** ./js/editor.scss ***!
  \************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin\n\n//# sourceURL=webpack:///./js/editor.scss?");

/***/ }),

/***/ "./js/style.scss":
/*!***********************!*\
  !*** ./js/style.scss ***!
  \***********************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// removed by extract-text-webpack-plugin\n\n//# sourceURL=webpack:///./js/style.scss?");

/***/ })

/******/ });