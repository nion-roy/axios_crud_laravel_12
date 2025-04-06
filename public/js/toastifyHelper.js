/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./resources/js/toastifyHelper.js ***!
  \****************************************/
window.successToastify = function (message) {
  Toastify({
    text: message,
    gravity: "bottom",
    position: "center",
    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
  }).showToast();
};
window.errorToastify = function (message) {
  Toastify({
    text: message,
    gravity: "bottom",
    position: "center",
    backgroundColor: "linear-gradient(to right, #e52d27, #b31217)"
  }).showToast();
};
/******/ })()
;