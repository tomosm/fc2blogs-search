var EXAM0098 = EXAM0098 || {};
EXAM0098.module || (EXAM0098.module = {});

(function (app) {
  'use strict';

  /**
   * NO OPERATION。
   */
  function nop() {
    // nop.
  }

  /**
   * 値が {Object} インスタンスかチェックする。
   *
   * @param {Object} 値
   * @returns {boolean} true: {Object} インスタンス / false: {Object} インスタンスではない
   */
  function isObject(obj) {
    return Object.prototype.toString.call(obj) === '[object Object]';
  }

  /**
   * 値が {Function} インスタンスかチェックする。
   *
   * @param {Function} func 値
   * @returns {boolean} true: Function} インスタンス / false: Function} インスタンスではない
   */
  function isFunction(func) {
    return typeof func === 'function' || Object.prototype.toString.call(func) === '[object Function]';
  }

  /**
   * シャローコピーする。
   *
   * @param {Object} to コピー先オブジェクト
   * @param {Object} from コピー元オブジェクト
   * @returns {Object} to コピー先オブジェクト
   */
  function shallowCopy(to, from) {
    if (!isObject(to) || !isObject(from)) return to;
    for (var prop in from) {
      if (from.hasOwnProperty(prop)) {
        to[prop] = from[prop];
      }
    }
    return to;
  }

  /**
   * mixin する。
   *
   * @param {Object} to mixin 先オブジェクト
   * @param {Object} from mixin 元オブジェクト
   * @returns {Object} to mixin 先オブジェクト
   */
  function mixin(to, from) {
    if (!isFunction(to) || !isFunction(from)) return to;
    for (var prop in from.prototype) {
      if (from.prototype.hasOwnProperty(prop)) {
        to.prototype[prop] = from.prototype[prop];
      }
    }
    return to;
  }

  app.module.nop = nop;
  app.module.shallowCopy = shallowCopy;
  app.module.mixin = mixin;

}(EXAM0098));
