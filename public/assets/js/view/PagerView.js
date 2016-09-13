var EXAM0098 = EXAM0098 || {};
EXAM0098.view || (EXAM0098.view = {});

(function (app) {
  'use strict';

  /**
   * li エレメント (display: none) を生成して返す。
   * li エレメントは a[href=#] エレメントを子供として保持させ、
   * かつ a エレメントに click イベントに callback を実行するように設定する。
   *
   * @param label ラベル
   * @param callback
   * @returns {Element} LI エレメント
   */
  function createLiEl(label, callback) {
    var liEl = document.createElement('li');
    var labelNode = document.createTextNode(label);

    var aEl = document.createElement('a');
    app.module.setProperty(aEl, 'href', '#');
    liEl.appendChild(aEl);
    app.module.addEvent(aEl, 'click', callback);
    aEl.appendChild(labelNode);

    app.module.hideElement(liEl);
    return liEl;
  }

  /**
   * ページャー機能ビュークラス。
   *
   * @param element 主管 HTML エレメント
   * @param model 主管モデル
   * @constructor
   */
  var PagerView = function (element, model) {
    this.el = element;
    this.model = model;
    this._render();
    this._handleEvents();
    this.currentPage = 0;
    this.lastPage = 0;
  };

  /**
   * イベントを発火する。
   * 発火するイベントは引数のイベントオブジェクトによって変化する。
   * 発火するイベントは、next, last, prev, first, page の5種類でありそれぞれページ数を引数として受け取る。
   *
   * @param e イベントオブジェクト
   * @private
   */
  PagerView.prototype._triggerClick = function (e) {
    e.preventDefault();

    var label = e.target.childNodes[0].data;
    switch (label) {
    case '>':
      this.trigger('next', this.currentPage < this.lastPage ? (this.currentPage + 1) : this.currentPage);
      break;
    case '>>':
      this.trigger('last', this.lastPage);
      break;
    case '<':
      this.trigger('prev', this.currentPage === 0 ? 0 : this.currentPage - 1);
      break;
    case '<<':
      this.trigger('first', 0);
      break;
    default:
      // page のインデックスは表示数-1
      this.trigger('page', Number(label) - 1);
      break;
    }
  };

  /**
   * ページャー機能の HTML を描画する。
   *
   * @private
   */
  PagerView.prototype._render = function () {
    var ulEl = document.createElement('ul');

    // first button
    this.firstLiEl = createLiEl('<<', this._triggerClick.bind(this));
    ulEl.appendChild(this.firstLiEl);
    app.module.setProperty(this.firstLiEl, 'title', '最初へ');

    // prev button
    this.prevLiEl = createLiEl('<', this._triggerClick.bind(this));
    ulEl.appendChild(this.prevLiEl);
    app.module.setProperty(this.prevLiEl, 'title', '1つ前のページへ');

    this.pageLiEls = [];
    for (var i = 0; i < app.settings.COMPONENT.PAGER.MAX_NAV; i++) {
      var pageLiEl = createLiEl('', this._triggerClick.bind(this));
      this.pageLiEls.push(pageLiEl);
      ulEl.appendChild(pageLiEl);
    }

    // next button
    this.nextLiEl = createLiEl('>', this._triggerClick.bind(this));
    ulEl.appendChild(this.nextLiEl);
    app.module.setProperty(this.nextLiEl, 'title', '1つ次のページへ');

    // last button
    this.lastLiEl = createLiEl('>>', this._triggerClick.bind(this));
    ulEl.appendChild(this.lastLiEl);
    app.module.setProperty(this.lastLiEl, 'title', '最後のページへ');

    this.el.appendChild(ulEl);
  };

  /**
   * イベント監視、および振り分け処理を設定する。
   *
   * @private
   */
  PagerView.prototype._handleEvents = function () {
    var self = this;
    this.model.on('change:all', function (model) {
      var offset = model.offset === 0 ? 0 : model.offset;
      var totalCount = model.totalCount === 0 ? 0 : model.totalCount - 1;

      this.currentPage = Math.floor(offset / app.settings.COMPONENT.PAGER.COUNT);
      this.lastPage = Math.floor(totalCount / app.settings.COMPONENT.PAGER.COUNT);

      var bundleCount = Math.floor(this.currentPage / app.settings.COMPONENT.PAGER.MAX_NAV);

      // << < > >> ボタンの表示・非表示制御
      if (this.lastPage === 0) {
        app.module.hideElement(self.prevLiEl, self.firstLiEl, self.nextLiEl, self.lastLiEl);
      } else if (this.currentPage === 0) {
        app.module.hideElement(self.prevLiEl, self.firstLiEl);
        app.module.showElement(self.nextLiEl, self.lastLiEl);
      } else if (this.currentPage === this.lastPage) {
        app.module.hideElement(self.nextLiEl, self.lastLiEl);
        app.module.showElement(self.prevLiEl, self.firstLiEl);
      } else {
        app.module.showElement(self.prevLiEl, self.nextLiEl, self.firstLiEl, self.lastLiEl);
      }

      // 1 2 3 ... ボタンの表示・非表示制御
      self.pageLiEls.forEach(function (pageLiEl, i) {
        var pageCount = bundleCount * app.settings.COMPONENT.PAGER.MAX_NAV + i;
        pageLiEl.childNodes[0].textContent = pageCount + 1;
        if (pageCount <= self.lastPage) {
          app.module.showElement(pageLiEl);
        } else {
          app.module.hideElement(pageLiEl);
        }
      });

      self._unsetCurrentAllLiEL();
      self._setCurrentLiEl();

    }, this);
  };

  /**
   * ページ選択を全解除する。
   *
   * @private
   */
  PagerView.prototype._unsetCurrentAllLiEL = function () {
    Array.prototype.forEach.call(this.el.getElementsByClassName('current'), function (currentEl) {
      Array.prototype.forEach.call(currentEl.childNodes, function (childNode) {
        if (childNode.tagName === 'SPAN') {
          app.module.removeElement(childNode);
        } else if (childNode.tagName === 'A') {
          app.module.showElement(childNode);
        }
      });
    });
  };

  /**
   * ページ選択を設定する。
   *
   * @private
   */
  PagerView.prototype._setCurrentLiEl = function () {
    for (var i = 0, l = this.pageLiEls.length; i < l; i++) {
      var liEl = this.pageLiEls[i];
      var aEl = liEl.getElementsByTagName('a')[0];

      if (Number(aEl.textContent) === (this.currentPage + 1)) {
        app.module.hideElement(aEl);
        var spanEl = document.createElement('span');
        liEl.appendChild(spanEl);
        app.module.addClassName(liEl, 'current');
        spanEl.textContent = Number(aEl.textContent);
        liEl.appendChild(spanEl);
        break;
      }
    }
  };

  app.module.mixin(PagerView, app.module.Events);

  app.view.PagerView = PagerView;

})(EXAM0098);