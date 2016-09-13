var EXAM0098 = EXAM0098 || {};
EXAM0098.view || (EXAM0098.view = {});

(function (app) {
  'use strict';

  /**
   * 値が設定されている場合のみ、検索条件として検索条件オブジェクトに設定する。
   *
   * @param data 検索条件オブジェクト
   * @param key キー
   * @param value 値
   */
  function setSearchCriteria(data, key, value) {
    if (value === undefined || value === null || value === '') {
      app.module.cookie(key, null);
      return;
    }
    data[key] = value;
    app.module.cookie(key, value);
  }

  /**
   * Blog ビュークラス。
   *
   * @param element 主管 HTML エレメント
   * @param model 主管モデル
   * @constructor
   */
  var BlogView = function (element, model) {
    this.el = element;
    this.model = model;
    this._render();
    this._handleEvents();
  };

  /**
   * Blog の HTML を描画する。
   *
   * @private
   */
  BlogView.prototype._render = function () {
    var self = this;

    // 検索条件
    function renderCriteria() {
      var renderLabel = function (label, className) {
        className = !!className ? ' ' + className : '';
        var labelEl = document.createElement('span');
        app.module.addClassName(labelEl, 'label' + className);
        labelEl.textContent = label;
        return labelEl;
      };
      var formEl = self.el.getElementsByTagName('form')[0];
      var searchCondDiv1 = document.createElement('div');
      var dateInput = document.createElement('input');
      app.module.setProperty(dateInput, 'type', 'date');
      app.module.addClassName(dateInput, 'date-input right-space');
      var urlInput = document.createElement('input');
      app.module.setProperty(urlInput, 'type', 'text');
      app.module.addClassName(urlInput, 'url-input right-space');
      searchCondDiv1.appendChild(renderLabel('日付'));
      searchCondDiv1.appendChild(dateInput);
      searchCondDiv1.appendChild(renderLabel('URL'));
      searchCondDiv1.appendChild(urlInput);

      var searchCondDiv2 = document.createElement('div');
      var userNameInput = document.createElement('input');
      app.module.setProperty(userNameInput, 'type', 'text');
      app.module.addClassName(userNameInput, 'user-name-input right-space');
      var serverNoInput = document.createElement('input');
      app.module.setProperty(serverNoInput, 'type', 'number');
      app.module.addClassName(serverNoInput, 'server-no-input right-space');
      searchCondDiv2.appendChild(renderLabel('ユーザー名'));
      searchCondDiv2.appendChild(userNameInput);
      searchCondDiv2.appendChild(renderLabel('サーバー番号'));
      searchCondDiv2.appendChild(serverNoInput);

      var searchCondDiv3 = document.createElement('div');
      var entryNoInput = document.createElement('input');
      app.module.setProperty(entryNoInput, 'type', 'number');
      app.module.addClassName(entryNoInput, 'entry-no-input');
      searchCondDiv3.appendChild(renderLabel('エントリーNo'));
      searchCondDiv3.appendChild(entryNoInput);
      searchCondDiv3.appendChild(renderLabel('以上', 'small'));

      var buttonDiv = document.createElement('div');
      var resetButton = document.createElement('button');
      resetButton.appendChild(document.createTextNode('クリア'));
      app.module.setProperty(resetButton, 'type', 'reset');
      var searchButton = document.createElement('button');
      searchButton.appendChild(document.createTextNode('検索'));
      app.module.setProperty(searchButton, 'type', 'button');
      buttonDiv.appendChild(resetButton);
      buttonDiv.appendChild(searchButton);
      self.searchButton = searchButton;
      self.resetButton = resetButton;

      formEl.appendChild(searchCondDiv1);
      formEl.appendChild(searchCondDiv2);
      formEl.appendChild(searchCondDiv3);
      formEl.appendChild(buttonDiv);

      // cookieからデフォルト値設定
      urlInput.value = app.module.cookie('link') || '';
      dateInput.value = app.module.cookie('postedAt') || '';
      entryNoInput.value = app.module.cookie('entryNo') || '';
      serverNoInput.value = app.module.cookie('serverNo') || '';
      userNameInput.value = app.module.cookie('userName') || '';
    }

    // 検索結果
    function renderResult() {
      var tableEl = Array.prototype.filter.call(self.el.getElementsByTagName('div'), function (div) {
        return div.className === 'table';
      })[0];

      // <table ...>
      var table = document.createElement('table');
      var tHead = document.createElement('thead');
      self.tBodyEl = document.createElement('tbody');

      // thead > tr > th
      var tr = document.createElement('tr');
      var dateTh = document.createElement('th');
      dateTh.appendChild(document.createTextNode('日付'));
      app.module.addClassName(dateTh, 'date');
      var urlTh = document.createElement('th');
      urlTh.appendChild(document.createTextNode('URL'));
      app.module.addClassName(urlTh, 'url');
      var titleTh = document.createElement('th');
      titleTh.appendChild(document.createTextNode('タイトル'));
      app.module.addClassName(titleTh, 'title');
      var descriptionTh = document.createElement('th');
      descriptionTh.appendChild(document.createTextNode('description'));
      app.module.addClassName(descriptionTh, 'description');
      tr.appendChild(dateTh);
      tr.appendChild(urlTh);
      tr.appendChild(titleTh);
      tr.appendChild(descriptionTh);
      tHead.appendChild(tr);

      table.appendChild(tHead);
      table.appendChild(self.tBodyEl);

      tableEl.appendChild(table);
    }

    // ページャー機能
    function renderPaging() {
      var pagerEl = Array.prototype.filter.call(self.el.getElementsByTagName('div'), function (div) {
        return div.className === 'pager';
      })[0];
      self.pagerView = new app.view.PagerView(pagerEl, self.model);
    }

    renderCriteria();
    renderPaging();
    renderResult();
    app.module.showElement(this.el);
  };

  var searchInputMap = {
    link: 'url-input',
    postedAt: 'date-input',
    entryNo: 'entry-no-input',
    serverNo: 'server-no-input',
    userName: 'user-name-input'
  };

  /**
   * データを検索する。
   *
   * @param page ページ数(1ページは0)
   * @private
   */
  BlogView.prototype._onSearch = function (page) {
    app.module.removeChildren(this.tBodyEl);

    page = Number(page);
    page = (isNaN(page) || page < 0) ? 0 : page;
    var data = {offset: (page * app.settings.COMPONENT.PAGER.COUNT)};
    for (var key in searchInputMap) {
      setSearchCriteria(data, key, this.el.getElementsByClassName(searchInputMap[key])[0].value);
    }

    this.model.search({
      data: data,
      error: function () {
        alert('通信エラーが発生しました。サーバー管理者へ問い合わせしてください。');
      }
    });
  };

  /**
   * イベント監視、および振り分け処理を設定する。
   *
   * @private
   */
  BlogView.prototype._handleEvents = function () {
    var self = this;

    // 検索ボタンを監視
    app.module.addEvent(this.searchButton, 'click', function () {
      self._onSearch()
    });

    // リセットボタンを監視
    app.module.addEvent(this.resetButton, 'click', function () {
      Object.keys(searchInputMap).forEach(function (key) {
        app.module.cookie(key, null);
      });
    });

    // 主管モデルを監視
    this.model.on('change:all', function (model) {
      // 検索結果のタグを生成
      model.data.forEach(function (blog) {
        var tr = document.createElement('tr');
        var dateTd = document.createElement('td');
        dateTd.appendChild(document.createTextNode(blog.postedAt));
        var urlTd = document.createElement('td');
        urlTd.appendChild(document.createTextNode(blog.link));
        var titleTd = document.createElement('td');
        titleTd.appendChild(document.createTextNode(blog.title));
        var descriptionTd = document.createElement('td');
        descriptionTd.appendChild(document.createTextNode(blog.description));
        tr.appendChild(dateTd);
        tr.appendChild(urlTd);
        tr.appendChild(titleTd);
        tr.appendChild(descriptionTd);

        self.tBodyEl.appendChild(tr);
      });
    }, this);

    this.pagerView.on('first last next prev page', this._onSearch.bind(this));

  };

  app.view.BlogView = BlogView;

})(EXAM0098);