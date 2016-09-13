var EXAM0098 = EXAM0098 || {};
EXAM0098.module || (EXAM0098.module = {});

(function (app) {
  'use strict';

  /**
   * HTML エレメントにイベントを追加する。
   *
   * @param element HTML エレメント
   * @param event イベント
   * @param func callback イベント
   * @param cap true: キャプチャリング / false: バブリング
   */
  function addEvent(element, event, func, cap) {
    try {
      var events = event.split(' ');
      for (var i = 0; i < events.length; i++) {
        if (element.addEventListener) {
          element.addEventListener(events[i], func, cap);
        } else if (element.attachEvent) {
          element.attachEvent('on' + events[i], func);
        }
      }
    } catch (e) {
      log('unsupported event [' + event + ']');
    }
  }

  /**
   * HTML エレメントにプロパティを設定する。
   *
   * @param element HTML エレメント
   * @param key キー
   * @param value 値
   */
  function setProperty(element, key, value) {
    if (element.setProperty) {
      element.setProperty(key, String(value)); // for IE.
    } else if (element.setAttribute) {
      element.setAttribute(key, value);
    }
  }

  /**
   * HTML エレメントからプロパティを削除する。
   *
   * @param element HTML エレメント
   * @param key キー
   */
  function removeProperty(element, key) {
    if (element.removeProperty) {
      element.removeProperty(key);
    } else if (element.removeAttribute) {
      element.removeAttribute(key);
    }
  }

  /**
   * HTML エレメントに CSS クラス名を追加する。
   *
   * @param element HTML エレメント
   * @param name CSS クラス名
   */
  function addClassName(element, name) {
    if (!name) return;
    if (element.className) {
      var classNameList = element.className.split(' ');
      if (classNameList.indexOf(name) == -1) {
        element.className += (' ' + name);
      }
    } else {
      element.className = name;
    }
  }

  /**
   * HTML エレメントの子エレメントを全て削除する。
   *
   * @param node HTML エレメント
   */
  function removeChildren(node) {
    var last;
    while (last = node.lastChild) node.removeChild(last);
  }

  /**
   * HTML エレメントを削除する。
   *
   * @param node HTML エレメント
   */
  function removeElement(node) {
    node.parentNode.removeChild(node);
  }

  /**
   * 指定した HTML エレメントを非表示にする。
   *
   * @param {Array} arguments HTML エレメント
   */
  function hideElement() {
    Array.prototype.forEach.call(arguments, function (element) {
      setProperty(element.style, 'display', 'none');
    })
  }

  /**
   * 指定した HTML エレメントを表示する。
   *
   * @param {Array} arguments HTML エレメント
   */
  function showElement() {
    Array.prototype.forEach.call(arguments, function (element) {
      removeProperty(element.style, 'display');
    })
  }

  app.module.addEvent = addEvent;
  app.module.setProperty = setProperty;
  app.module.addClassName = addClassName;
  app.module.removeChildren = removeChildren;
  app.module.removeElement = removeElement;
  app.module.hideElement = hideElement;
  app.module.showElement = showElement;

}(EXAM0098));
