var EXAM0098 = EXAM0098 || {};
EXAM0098.module || (EXAM0098.module = {});

(function (app) {
  'use strict';

  function replacePlusToSpace(value) {
    return value.replace(/\+/g, ' ');
  }

  function decodePlus(value) {
    return decodeURIComponent(replacePlusToSpace(value));
  }

  /**
   * cookie ヘルパー関数。
   * キーのみ指定した場合は cookieから 指定したキーに設定されてある値を取得して返す。
   * キーと値を null 以外指定した場合は、指定したキーの値を cookie に設定する。
   * キーと値を null で指定した場合、指定したキーのデータを cookie から削除する。
   *
   * @param key キー
   * @param value 値
   * @param options オプション
   * @returns {*} キーのみの場合のみ紐付く値
   */
  function cookie(key, value, options) {
    if (value !== undefined) {
      options || (options = {});

      if (value === null) {
        options.expires = -1;
      }

      if (typeof options.expires === 'number') {
        var days = options.expires, t = options.expires = new Date();
        t.setDate(t.getDate() + days);
      }

      value = JSON ? JSON.stringify(value) : String(value);

      return (document.cookie = [
        encodeURIComponent(key), '=', encodeURIComponent(value),
        options.expires ? '; expires=' + options.expires.toUTCString() : '',
        options.path ? '; path=' + options.path : '',
        options.domain ? '; domain=' + options.domain : '',
        options.secure ? '; secure' : ''
      ].join(''));
    }

    var cookies = document.cookie.split('; ');
    for (var i = 0, l = cookies.length; i < l; i++) {
      var parts = cookies[i].split('=');
      if (decodePlus(parts.shift()) === key) {
        var cookie = decodePlus(parts.join('='));
        return JSON ? JSON.parse(cookie) : cookie;
      }
    }

    return null;
  }


  app.module.cookie = cookie;

})(EXAM0098);