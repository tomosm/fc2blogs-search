var EXAM0098 = EXAM0098 || {};

(function (app) {
  'use strict';

  /**
   * Blog ルータークラス。
   *
   * @constructor
   */
  var BlogRouter = function () {
    this.name = 'blog-search';
    // this.name = 'blogSearch';
  };

  /**
   * ルート処理。
   * URI に基づいて処理を振り分ける。
   */
  BlogRouter.prototype.route = function () {
    var self = this;
    var blogViewElement = Array.prototype.filter.call(document.getElementsByTagName('section'), function (section) {
      return section.className === self.name;
    })[0];

    new app.view.BlogView(blogViewElement, new app.model.BlogModel());
  };

  var blogRouter = new BlogRouter();
  blogRouter.route();

})(EXAM0098);