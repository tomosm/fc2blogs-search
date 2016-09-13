(function (app) {
  'use strict';

  describe('module', function () {
    var testee = app.module;

    describe('utils.js', function () {

      it('can shallow copy', function () {
        var from = {"a": "a1", "b": "b1", "c": {"d": "d1"}};

        var actual = testee.shallowCopy({}, from);
        expect(from).to.eql(actual);

        from["c"]["d"] = "xxx";
        expect("xxx").to.eql(actual["c"]["d"]);
      });

      it('can mixin', function () {
        var to = function () {
        };
        to.prototype.to1 = function () {
        };
        to.prototype.to2 = function () {
        };

        var from = function () {
        };
        from.prototype.from1 = function () {
        };
        from.prototype.to1 = function () {
        };

        var actual = testee.mixin(to, from);
        expect(from.prototype.to1).to.eql(actual.prototype.to1);
        expect(to.prototype.to2).to.eql(actual.prototype.to2);
        expect(from.prototype.from1).to.eql(actual.prototype.from1);
      });

    });

    describe('cookie.js', function () {
      it('stores value to cookie', function () {
        expect(null).to.eql(testee.cookie(null));

        var key = "testkey";
        expect(null).to.eql(testee.cookie(key));

        // set
        var val = "testvalue";
        testee.cookie(key, val);
        expect(val).to.eql(testee.cookie(key));

        // remove
        testee.cookie(key, null);
        expect(null).to.eql(testee.cookie(key));
      });

    });

    describe('ajax.js', function () {

      it('can ajax', function () {

        try {
          testee.ajax(null)
        } catch (e) {
          expect(e instanceof TypeError).to.eql(true);
        }

        testee.ajax(location.href.replace(/\/[\w\d]+\.html$|\/$/, '/') + '../../', {
          dataType: "html",
          success: function (data) {
            expect(typeof data === "string").to.eql(true);
            expect(data.length > 0).to.eql(true);
          }
        });

      });

    });

    describe('Events.js', function () {

      it('can on/off/fire events', function () {
        var events = new testee.Events();
        events.trigger("nothing");

        var detectAllFired = false;
        events.on("detect1 detect2", function () {
          detectAllFired = true;
        });
        var detect1Fired = false;
        events.on("detect1", function () {
          detect1Fired = true;
        });
        var detect2Fired = false;
        events.on("detect2", function () {
          detect2Fired = true;
        });

        events.trigger("nothing");
        expect(false).to.eql(detect1Fired);
        expect(false).to.eql(detect2Fired);
        expect(false).to.eql(detectAllFired);

        events.trigger("detect1");
        expect(true).to.eql(detect1Fired);
        expect(false).to.eql(detect2Fired);
        expect(true).to.eql(detectAllFired);

        events.off("detect2");
        events.trigger("detect2");
        expect(true).to.eql(detect1Fired);
        expect(false).to.eql(detect2Fired);
        expect(true).to.eql(detectAllFired);
      });
    });
  });

}(EXAM0098));
