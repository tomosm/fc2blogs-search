var EXAM0098 = EXAM0098 || {};
EXAM0098.module || (EXAM0098.module = {});


(function (app) {
  'use strict';

  /**
   * パラメータ付き URL 文字列を返す。
   *
   * @param {Object} object
   * @returns {string} パラメータ付き URL 文字列
   */
  function urlParams(object) {
    var pieces = [];
    for (var prop in object) {
      if (object.hasOwnProperty(prop)) {
        pieces.push(
          encodeURIComponent(prop) + '=' +
          encodeURIComponent(object[prop]));
      }
    }
    return pieces.join('&');
  }

  /**
   * 送信する際のデータを設定する。
   *
   * @param {Object} options
   */
  function setData(options) {
    if (options.data) {
      if (options.method === 'GET') {
        var hasParams = options.url.indexOf('?') >= 0;
        options.url += hasParams ? '&' : '?';
        options.url += urlParams(options.data);
        delete options.data;
      } else {
        options.data = urlParams(options.data);
      }
    }
  }

  /**
   * リクエストが成功したかどうかをチェックする。
   *
   * @param {XMLHttpRequest} transport
   * @returns {boolean} true:成功 / false: 失敗
   */
  function isSuccess(transport) {
    var status = transport.status;

    return (status >= 200 && status < 300)
      || status == 304;
  }

  /**
   * データを JSON オブジェクトとして返す。
   *
   * @param {XMLHttpRequest} transport
   * @returns {Object} JSON オブジェクト
   */
  function toJsonObject(transport) {
    if (transport && transport.responseText) {
      return JSON.parse(transport.responseText);
    }
    return {};
  }

  /**
   * AJAX クラス。
   *
   * @constructor
   */
  function AJAX() {
    this.transport = new XMLHttpRequest();
  }

  /**
   * リクエストを初期化する。
   *
   * @param method 使用する HTTP メソッド。"GET"、"POST"、"PUT"、"DELETE" など。HTTP(S) URL でない場合は無視されます。
   * @param url URL
   * @param async 非同期で操作を実行するかを示す、オプションの真偽値
   * @param username 認証を目的として使用される、ユーザー名のオプション
   * @param password 認証を目的として使用される、パスワードのオプション
   * @param options その他オプション
   *     options.withCredentials true の場合 withCredentials を http request に追加
   */
  AJAX.prototype.open = function (method, url, async, username, password, options) {
    this.transport.open(method, url, async, username, password);
    if (options.withCredentials && ((typeof this.transport.withCredentials) !== 'undefined')) {
      this.transport['withCredentials'] = options.withCredentials;
    }

    var self = this;
    this.transport.onreadystatechange = function () {
      if (self.transport.readyState == 4) {
        self.requestComplete(options);
        self.transport.onreadystatechange = app.module.nop; // for memory leak on IE
      }
    };
  };
  /**
   * リクエストを送信する。
   *
   * @param data 送信データ
   */
  AJAX.prototype.send = function (data) {
    this.transport.send(data);
  };
  /**
   * @returns {Object} 全てのレスポンスヘッダ
   */
  AJAX.prototype.getAllResponseHeaders = function () {
    return this.transport.getAllResponseHeaders().split('\n').reduce(function (allHeaders, responseHeader) {
      var headerKeyAndValue = responseHeader.split(':');
      if (headerKeyAndValue.length === 2) {
        allHeaders[headerKeyAndValue[0].trim()] = headerKeyAndValue[1].trim();
      }
      return allHeaders;
    }, {});
  };

  /**
   * リクエストが完了した後に実行される。
   * 成功時は options.success が実行される。
   * 失敗時は options.error が実行される。
   *
   * @param options
   * @throws TypeError options.dataType が json 以外の場合
   */
  AJAX.prototype.requestComplete = function (options) {
    var outputData = {};
    if (options.dataType === "json") {
      outputData = toJsonObject(this.transport);
    } else {
      outputData = options.dataType;
    }
    if (isSuccess(this.transport)) {
      if (typeof options.success === 'function') {
        options.success(outputData, this.getAllResponseHeaders());
      }
    } else {
      if (typeof options.error === 'function') {
        options.error(outputData, this.getAllResponseHeaders());
      }
    }
  };

  /**
   * AJAX ヘルパー関数。
   *
   * @param url URL
   * @param options
   */
  function ajax(url, options) {
    options = options || {};

    if (typeof url !== 'string') {
      throw new TypeError('URL must be string');
    }

    options = app.module.shallowCopy({}, options);
    options.url = url;
    options.method || (options.method = 'GET');
    setData(options);

    options.dataType || (options.dataType = 'json');

    var ajaxInstance = new AJAX();
    ajaxInstance.open(options.method, options.url, true, null, null, options);
    ajaxInstance.send(options.data);
  }

  app.module.ajax = ajax;

}(EXAM0098));
