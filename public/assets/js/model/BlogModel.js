var EXAM0098 = EXAM0098 || {};
EXAM0098.model || (EXAM0098.model = {});

(function (app) {
  'use strict';

  /**
   * Blog モデルクラス。
   *
   * @constructor
   */
  var BlogModel = function () {
    this.name = 'blogs';
    this.offset = 0;
    this.totalCount = 0;
    this.data = [];
  };

  /**
   * Blog データを検索する。
   *
   * @param options
   */
  BlogModel.prototype.search = function (options) {
    options || (options = {});
    options.data || (options.data = {});
    options.data.limit = app.settings.COMPONENT.PAGER.COUNT;

    var onSuccessCallback = options.success;
    var self = this;
    options.success = function (data, headers) {
      self.offset = isNaN(Number(data.offset)) ? 0 : Number(data.offset);
      self.totalCount = isNaN(Number(headers['X-Total-Count'])) ? 0 : Number(headers['X-Total-Count']);
      self.data = data.data;
      if (onSuccessCallback) onSuccessCallback(data, headers);
      self.trigger("change:all", self);
    };
    app.module.ajax(app.settings.API.URL + this.name + '/search.php', options);
  };

  app.module.mixin(BlogModel, app.module.Events);

  app.model.BlogModel = BlogModel;

})(EXAM0098);