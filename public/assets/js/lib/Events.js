var EXAM0098 = EXAM0098 || {};
EXAM0098.module || (EXAM0098.module = {});

(function (app) {
  'use strict';

  /**
   * イベントクラス。
   *
   * @constructor
   */
  var Events = function () {
    this.callbacks = {};
  };

  Events.prototype.eventSplitter = /\s+/;

  /**
   * イベント監視を追加する。
   *
   * @param events 監視するイベント、スペース区切りで複数指定可能
   * @param callback イベントが実行されたら実行される処理
   * @param context callback を実行するコンテキスト
   * @returns {Events} 自身のインスタンス
   */
  Events.prototype.on = function (events, callback, context) {
    if (!callback) return this;
    this.callbacks || (this.callbacks = {});
    events = events.split(this.eventSplitter);

    var event, list;
    while (event = events.shift()) {
      list = this.callbacks[event];
      list || (list = []);
      list.push({callback: callback, context: context});
      this.callbacks[event] = list;
    }

    return this;
  };

  /**
   * イベント監視を削除する。
   *
   * @param events 監視を削除するイベント、スペース区切りで複数指定可能
   * @param callback イベントが実行されたら実行される処理
   * @param context callback を実行するコンテキスト
   * @returns {Events} 自身のインスタンス
   */
  Events.prototype.off = function (events, callback, context) {
    var event;
    if (!this.callbacks) return;
    if (!(events || callback || context)) {
      delete this.callbacks;
      return this;
    }

    events = events.split(this.eventSplitter);
    while (event = events.shift()) {
      if (!callback) {
        delete this.callbacks[event];
        continue;
      }
      var list = this.callbacks[event] || [];
      for (var i = list.length - 1; i >= 0; i--) {
        if (list[i]["callback"] === callback) this.callbacks.splice(i, 1);
      }
    }

    return this;
  };

  /**
   * 指定したイベントを発火する。
   *
   * @param events 発火するイベント、スペース区切りで複数指定可能
   * @returns {Events} 自身のインスタンス
   */
  Events.prototype.trigger = function (events) {
    var event;
    if (!this.callbacks) return this;
    events = events.split(this.eventSplitter);
    var rest = Array.prototype.slice.call(arguments, 1);

    while (event = events.shift()) {
      var list = this.callbacks[event] || [];
      for (var i = 0, limit = list.length; i < limit; i++) {
        list[i].callback.apply(list[i].context || this, rest);
      }
    }

    return this;
  };

  app.module.Events = Events;

})(EXAM0098);
