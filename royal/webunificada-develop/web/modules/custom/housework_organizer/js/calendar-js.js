(function (t) {
         "function" == typeof define && define.amd ? define(["jquery"], t) : t(jQuery);
})(function (t) {
         function e(t) {
                  for (var e = t.css("visibility"); "inherit" === e; ) (t = t.parent()), (e = t.css("visibility"));
                  return "hidden" !== e;
         }
         (t.ui = t.ui || {}), (t.ui.version = "1.12.1");
         var i = 0,
                  s = Array.prototype.slice;
         (t.cleanData = (function (e) {
                  return function (i) {
                           var s, n, o;
                           for (o = 0; null != (n = i[o]); o++)
                                    try {
                                             (s = t._data(n, "events")), s && s.remove && t(n).triggerHandler("remove");
                                    } catch (a) {}
                           e(i);
                  };
         })(t.cleanData)),
                  (t.widget = function (e, i, s) {
                           var n,
                                    o,
                                    a,
                                    r = {},
                                    l = e.split(".")[0];
                           e = e.split(".")[1];
                           var h = l + "-" + e;
                           return (
                                    s || ((s = i), (i = t.Widget)),
                                    t.isArray(s) && (s = t.extend.apply(null, [{}].concat(s))),
                                    (t.expr[":"][h.toLowerCase()] = function (e) {
                                             return !!t.data(e, h);
                                    }),
                                    (t[l] = t[l] || {}),
                                    (n = t[l][e]),
                                    (o = t[l][e] = function (t, e) {
                                             return this._createWidget ? (arguments.length && this._createWidget(t, e), void 0) : new o(t, e);
                                    }),
                                    t.extend(o, n, { version: s.version, _proto: t.extend({}, s), _childConstructors: [] }),
                                    (a = new i()),
                                    (a.options = t.widget.extend({}, a.options)),
                                    t.each(s, function (e, s) {
                                             return t.isFunction(s)
                                                      ? ((r[e] = (function () {
                                                                 function t() {
                                                                          return i.prototype[e].apply(this, arguments);
                                                                 }
                                                                 function n(t) {
                                                                          return i.prototype[e].apply(this, t);
                                                                 }
                                                                 return function () {
                                                                          var e,
                                                                                   i = this._super,
                                                                                   o = this._superApply;
                                                                          return (this._super = t), (this._superApply = n), (e = s.apply(this, arguments)), (this._super = i), (this._superApply = o), e;
                                                                 };
                                                        })()),
                                                        void 0)
                                                      : ((r[e] = s), void 0);
                                    }),
                                    (o.prototype = t.widget.extend(a, { widgetEventPrefix: n ? a.widgetEventPrefix || e : e }, r, { constructor: o, namespace: l, widgetName: e, widgetFullName: h })),
                                    n
                                             ? (t.each(n._childConstructors, function (e, i) {
                                                        var s = i.prototype;
                                                        t.widget(s.namespace + "." + s.widgetName, o, i._proto);
                                               }),
                                               delete n._childConstructors)
                                             : i._childConstructors.push(o),
                                    t.widget.bridge(e, o),
                                    o
                           );
                  }),
                  (t.widget.extend = function (e) {
                           for (var i, n, o = s.call(arguments, 1), a = 0, r = o.length; r > a; a++)
                                    for (i in o[a]) (n = o[a][i]), o[a].hasOwnProperty(i) && void 0 !== n && (e[i] = t.isPlainObject(n) ? (t.isPlainObject(e[i]) ? t.widget.extend({}, e[i], n) : t.widget.extend({}, n)) : n);
                           return e;
                  }),
                  (t.widget.bridge = function (e, i) {
                           var n = i.prototype.widgetFullName || e;
                           t.fn[e] = function (o) {
                                    var a = "string" == typeof o,
                                             r = s.call(arguments, 1),
                                             l = this;
                                    return (
                                             a
                                                      ? this.length || "instance" !== o
                                                               ? this.each(function () {
                                                                          var i,
                                                                                   s = t.data(this, n);
                                                                          return "instance" === o
                                                                                   ? ((l = s), !1)
                                                                                   : s
                                                                                   ? t.isFunction(s[o]) && "_" !== o.charAt(0)
                                                                                            ? ((i = s[o].apply(s, r)), i !== s && void 0 !== i ? ((l = i && i.jquery ? l.pushStack(i.get()) : i), !1) : void 0)
                                                                                            : t.error("no such method '" + o + "' for " + e + " widget instance")
                                                                                   : t.error("cannot call methods on " + e + " prior to initialization; " + "attempted to call method '" + o + "'");
                                                                 })
                                                               : (l = void 0)
                                                      : (r.length && (o = t.widget.extend.apply(null, [o].concat(r))),
                                                        this.each(function () {
                                                                 var e = t.data(this, n);
                                                                 e ? (e.option(o || {}), e._init && e._init()) : t.data(this, n, new i(o, this));
                                                        })),
                                             l
                                    );
                           };
                  }),
                  (t.Widget = function () {}),
                  (t.Widget._childConstructors = []),
                  (t.Widget.prototype = {
                           widgetName: "widget",
                           widgetEventPrefix: "",
                           defaultElement: "<div>",
                           options: { classes: {}, disabled: !1, create: null },
                           _createWidget: function (e, s) {
                                    (s = t(s || this.defaultElement || this)[0]),
                                             (this.element = t(s)),
                                             (this.uuid = i++),
                                             (this.eventNamespace = "." + this.widgetName + this.uuid),
                                             (this.bindings = t()),
                                             (this.hoverable = t()),
                                             (this.focusable = t()),
                                             (this.classesElementLookup = {}),
                                             s !== this &&
                                                      (t.data(s, this.widgetFullName, this),
                                                      this._on(!0, this.element, {
                                                               remove: function (t) {
                                                                        t.target === s && this.destroy();
                                                               },
                                                      }),
                                                      (this.document = t(s.style ? s.ownerDocument : s.document || s)),
                                                      (this.window = t(this.document[0].defaultView || this.document[0].parentWindow))),
                                             (this.options = t.widget.extend({}, this.options, this._getCreateOptions(), e)),
                                             this._create(),
                                             this.options.disabled && this._setOptionDisabled(this.options.disabled),
                                             this._trigger("create", null, this._getCreateEventData()),
                                             this._init();
                           },
                           _getCreateOptions: function () {
                                    return {};
                           },
                           _getCreateEventData: t.noop,
                           _create: t.noop,
                           _init: t.noop,
                           destroy: function () {
                                    var e = this;
                                    this._destroy(),
                                             t.each(this.classesElementLookup, function (t, i) {
                                                      e._removeClass(i, t);
                                             }),
                                             this.element.off(this.eventNamespace).removeData(this.widgetFullName),
                                             this.widget().off(this.eventNamespace).removeAttr("aria-disabled"),
                                             this.bindings.off(this.eventNamespace);
                           },
                           _destroy: t.noop,
                           widget: function () {
                                    return this.element;
                           },
                           option: function (e, i) {
                                    var s,
                                             n,
                                             o,
                                             a = e;
                                    if (0 === arguments.length) return t.widget.extend({}, this.options);
                                    if ("string" == typeof e)
                                             if (((a = {}), (s = e.split(".")), (e = s.shift()), s.length)) {
                                                      for (n = a[e] = t.widget.extend({}, this.options[e]), o = 0; s.length - 1 > o; o++) (n[s[o]] = n[s[o]] || {}), (n = n[s[o]]);
                                                      if (((e = s.pop()), 1 === arguments.length)) return void 0 === n[e] ? null : n[e];
                                                      n[e] = i;
                                             } else {
                                                      if (1 === arguments.length) return void 0 === this.options[e] ? null : this.options[e];
                                                      a[e] = i;
                                             }
                                    return this._setOptions(a), this;
                           },
                           _setOptions: function (t) {
                                    var e;
                                    for (e in t) this._setOption(e, t[e]);
                                    return this;
                           },
                           _setOption: function (t, e) {
                                    return "classes" === t && this._setOptionClasses(e), (this.options[t] = e), "disabled" === t && this._setOptionDisabled(e), this;
                           },
                           _setOptionClasses: function (e) {
                                    var i, s, n;
                                    for (i in e)
                                             (n = this.classesElementLookup[i]),
                                                      e[i] !== this.options.classes[i] && n && n.length && ((s = t(n.get())), this._removeClass(n, i), s.addClass(this._classes({ element: s, keys: i, classes: e, add: !0 })));
                           },
                           _setOptionDisabled: function (t) {
                                    this._toggleClass(this.widget(), this.widgetFullName + "-disabled", null, !!t), t && (this._removeClass(this.hoverable, null, "ui-state-hover"), this._removeClass(this.focusable, null, "ui-state-focus"));
                           },
                           enable: function () {
                                    return this._setOptions({ disabled: !1 });
                           },
                           disable: function () {
                                    return this._setOptions({ disabled: !0 });
                           },
                           _classes: function (e) {
                                    function i(i, o) {
                                             var a, r;
                                             for (r = 0; i.length > r; r++)
                                                      (a = n.classesElementLookup[i[r]] || t()),
                                                               (a = e.add ? t(t.unique(a.get().concat(e.element.get()))) : t(a.not(e.element).get())),
                                                               (n.classesElementLookup[i[r]] = a),
                                                               s.push(i[r]),
                                                               o && e.classes[i[r]] && s.push(e.classes[i[r]]);
                                    }
                                    var s = [],
                                             n = this;
                                    return (
                                             (e = t.extend({ element: this.element, classes: this.options.classes || {} }, e)),
                                             this._on(e.element, { remove: "_untrackClassesElement" }),
                                             e.keys && i(e.keys.match(/\S+/g) || [], !0),
                                             e.extra && i(e.extra.match(/\S+/g) || []),
                                             s.join(" ")
                                    );
                           },
                           _untrackClassesElement: function (e) {
                                    var i = this;
                                    t.each(i.classesElementLookup, function (s, n) {
                                             -1 !== t.inArray(e.target, n) && (i.classesElementLookup[s] = t(n.not(e.target).get()));
                                    });
                           },
                           _removeClass: function (t, e, i) {
                                    return this._toggleClass(t, e, i, !1);
                           },
                           _addClass: function (t, e, i) {
                                    return this._toggleClass(t, e, i, !0);
                           },
                           _toggleClass: function (t, e, i, s) {
                                    s = "boolean" == typeof s ? s : i;
                                    var n = "string" == typeof t || null === t,
                                             o = { extra: n ? e : i, keys: n ? t : e, element: n ? this.element : t, add: s };
                                    return o.element.toggleClass(this._classes(o), s), this;
                           },
                           _on: function (e, i, s) {
                                    var n,
                                             o = this;
                                    "boolean" != typeof e && ((s = i), (i = e), (e = !1)),
                                             s ? ((i = n = t(i)), (this.bindings = this.bindings.add(i))) : ((s = i), (i = this.element), (n = this.widget())),
                                             t.each(s, function (s, a) {
                                                      function r() {
                                                               return e || (o.options.disabled !== !0 && !t(this).hasClass("ui-state-disabled")) ? ("string" == typeof a ? o[a] : a).apply(o, arguments) : void 0;
                                                      }
                                                      "string" != typeof a && (r.guid = a.guid = a.guid || r.guid || t.guid++);
                                                      var l = s.match(/^([\w:-]*)\s*(.*)$/),
                                                               h = l[1] + o.eventNamespace,
                                                               u = l[2];
                                                      u ? n.on(h, u, r) : i.on(h, r);
                                             });
                           },
                           _off: function (e, i) {
                                    (i = (i || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace),
                                             e.off(i).off(i),
                                             (this.bindings = t(this.bindings.not(e).get())),
                                             (this.focusable = t(this.focusable.not(e).get())),
                                             (this.hoverable = t(this.hoverable.not(e).get()));
                           },
                           _delay: function (t, e) {
                                    function i() {
                                             return ("string" == typeof t ? s[t] : t).apply(s, arguments);
                                    }
                                    var s = this;
                                    return setTimeout(i, e || 0);
                           },
                           _hoverable: function (e) {
                                    (this.hoverable = this.hoverable.add(e)),
                                             this._on(e, {
                                                      mouseenter: function (e) {
                                                               this._addClass(t(e.currentTarget), null, "ui-state-hover");
                                                      },
                                                      mouseleave: function (e) {
                                                               this._removeClass(t(e.currentTarget), null, "ui-state-hover");
                                                      },
                                             });
                           },
                           _focusable: function (e) {
                                    (this.focusable = this.focusable.add(e)),
                                             this._on(e, {
                                                      focusin: function (e) {
                                                               this._addClass(t(e.currentTarget), null, "ui-state-focus");
                                                      },
                                                      focusout: function (e) {
                                                               this._removeClass(t(e.currentTarget), null, "ui-state-focus");
                                                      },
                                             });
                           },
                           _trigger: function (e, i, s) {
                                    var n,
                                             o,
                                             a = this.options[e];
                                    if (((s = s || {}), (i = t.Event(i)), (i.type = (e === this.widgetEventPrefix ? e : this.widgetEventPrefix + e).toLowerCase()), (i.target = this.element[0]), (o = i.originalEvent)))
                                             for (n in o) n in i || (i[n] = o[n]);
                                    return this.element.trigger(i, s), !((t.isFunction(a) && a.apply(this.element[0], [i].concat(s)) === !1) || i.isDefaultPrevented());
                           },
                  }),
                  t.each({ show: "fadeIn", hide: "fadeOut" }, function (e, i) {
                           t.Widget.prototype["_" + e] = function (s, n, o) {
                                    "string" == typeof n && (n = { effect: n });
                                    var a,
                                             r = n ? (n === !0 || "number" == typeof n ? i : n.effect || i) : e;
                                    (n = n || {}),
                                             "number" == typeof n && (n = { duration: n }),
                                             (a = !t.isEmptyObject(n)),
                                             (n.complete = o),
                                             n.delay && s.delay(n.delay),
                                             a && t.effects && t.effects.effect[r]
                                                      ? s[e](n)
                                                      : r !== e && s[r]
                                                      ? s[r](n.duration, n.easing, o)
                                                      : s.queue(function (i) {
                                                                 t(this)[e](), o && o.call(s[0]), i();
                                                        });
                           };
                  }),
                  t.widget,
                  (function () {
                           function e(t, e, i) {
                                    return [parseFloat(t[0]) * (c.test(t[0]) ? e / 100 : 1), parseFloat(t[1]) * (c.test(t[1]) ? i / 100 : 1)];
                           }
                           function i(e, i) {
                                    return parseInt(t.css(e, i), 10) || 0;
                           }
                           function s(e) {
                                    var i = e[0];
                                    return 9 === i.nodeType
                                             ? { width: e.width(), height: e.height(), offset: { top: 0, left: 0 } }
                                             : t.isWindow(i)
                                             ? { width: e.width(), height: e.height(), offset: { top: e.scrollTop(), left: e.scrollLeft() } }
                                             : i.preventDefault
                                             ? { width: 0, height: 0, offset: { top: i.pageY, left: i.pageX } }
                                             : { width: e.outerWidth(), height: e.outerHeight(), offset: e.offset() };
                           }
                           var n,
                                    o = Math.max,
                                    a = Math.abs,
                                    r = /left|center|right/,
                                    l = /top|center|bottom/,
                                    h = /[\+\-]\d+(\.[\d]+)?%?/,
                                    u = /^\w+/,
                                    c = /%$/,
                                    d = t.fn.position;
                           (t.position = {
                                    scrollbarWidth: function () {
                                             if (void 0 !== n) return n;
                                             var e,
                                                      i,
                                                      s = t("<div style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                                                      o = s.children()[0];
                                             return t("body").append(s), (e = o.offsetWidth), s.css("overflow", "scroll"), (i = o.offsetWidth), e === i && (i = s[0].clientWidth), s.remove(), (n = e - i);
                                    },
                                    getScrollInfo: function (e) {
                                             var i = e.isWindow || e.isDocument ? "" : e.element.css("overflow-x"),
                                                      s = e.isWindow || e.isDocument ? "" : e.element.css("overflow-y"),
                                                      n = "scroll" === i || ("auto" === i && e.width < e.element[0].scrollWidth),
                                                      o = "scroll" === s || ("auto" === s && e.height < e.element[0].scrollHeight);
                                             return { width: o ? t.position.scrollbarWidth() : 0, height: n ? t.position.scrollbarWidth() : 0 };
                                    },
                                    getWithinInfo: function (e) {
                                             var i = t(e || window),
                                                      s = t.isWindow(i[0]),
                                                      n = !!i[0] && 9 === i[0].nodeType,
                                                      o = !s && !n;
                                             return {
                                                      element: i,
                                                      isWindow: s,
                                                      isDocument: n,
                                                      offset: o ? t(e).offset() : { left: 0, top: 0 },
                                                      scrollLeft: i.scrollLeft(),
                                                      scrollTop: i.scrollTop(),
                                                      width: i.outerWidth(),
                                                      height: i.outerHeight(),
                                             };
                                    },
                           }),
                                    (t.fn.position = function (n) {
                                             if (!n || !n.of) return d.apply(this, arguments);
                                             n = t.extend({}, n);
                                             var c,
                                                      p,
                                                      f,
                                                      m,
                                                      g,
                                                      _,
                                                      v = t(n.of),
                                                      b = t.position.getWithinInfo(n.within),
                                                      y = t.position.getScrollInfo(b),
                                                      w = (n.collision || "flip").split(" "),
                                                      k = {};
                                             return (
                                                      (_ = s(v)),
                                                      v[0].preventDefault && (n.at = "left top"),
                                                      (p = _.width),
                                                      (f = _.height),
                                                      (m = _.offset),
                                                      (g = t.extend({}, m)),
                                                      t.each(["my", "at"], function () {
                                                               var t,
                                                                        e,
                                                                        i = (n[this] || "").split(" ");
                                                               1 === i.length && (i = r.test(i[0]) ? i.concat(["center"]) : l.test(i[0]) ? ["center"].concat(i) : ["center", "center"]),
                                                                        (i[0] = r.test(i[0]) ? i[0] : "center"),
                                                                        (i[1] = l.test(i[1]) ? i[1] : "center"),
                                                                        (t = h.exec(i[0])),
                                                                        (e = h.exec(i[1])),
                                                                        (k[this] = [t ? t[0] : 0, e ? e[0] : 0]),
                                                                        (n[this] = [u.exec(i[0])[0], u.exec(i[1])[0]]);
                                                      }),
                                                      1 === w.length && (w[1] = w[0]),
                                                      "right" === n.at[0] ? (g.left += p) : "center" === n.at[0] && (g.left += p / 2),
                                                      "bottom" === n.at[1] ? (g.top += f) : "center" === n.at[1] && (g.top += f / 2),
                                                      (c = e(k.at, p, f)),
                                                      (g.left += c[0]),
                                                      (g.top += c[1]),
                                                      this.each(function () {
                                                               var s,
                                                                        r,
                                                                        l = t(this),
                                                                        h = l.outerWidth(),
                                                                        u = l.outerHeight(),
                                                                        d = i(this, "marginLeft"),
                                                                        _ = i(this, "marginTop"),
                                                                        x = h + d + i(this, "marginRight") + y.width,
                                                                        C = u + _ + i(this, "marginBottom") + y.height,
                                                                        D = t.extend({}, g),
                                                                        T = e(k.my, l.outerWidth(), l.outerHeight());
                                                               "right" === n.my[0] ? (D.left -= h) : "center" === n.my[0] && (D.left -= h / 2),
                                                                        "bottom" === n.my[1] ? (D.top -= u) : "center" === n.my[1] && (D.top -= u / 2),
                                                                        (D.left += T[0]),
                                                                        (D.top += T[1]),
                                                                        (s = { marginLeft: d, marginTop: _ }),
                                                                        t.each(["left", "top"], function (e, i) {
                                                                                 t.ui.position[w[e]] &&
                                                                                          t.ui.position[w[e]][i](D, {
                                                                                                   targetWidth: p,
                                                                                                   targetHeight: f,
                                                                                                   elemWidth: h,
                                                                                                   elemHeight: u,
                                                                                                   collisionPosition: s,
                                                                                                   collisionWidth: x,
                                                                                                   collisionHeight: C,
                                                                                                   offset: [c[0] + T[0], c[1] + T[1]],
                                                                                                   my: n.my,
                                                                                                   at: n.at,
                                                                                                   within: b,
                                                                                                   elem: l,
                                                                                          });
                                                                        }),
                                                                        n.using &&
                                                                                 (r = function (t) {
                                                                                          var e = m.left - D.left,
                                                                                                   i = e + p - h,
                                                                                                   s = m.top - D.top,
                                                                                                   r = s + f - u,
                                                                                                   c = {
                                                                                                            target: { element: v, left: m.left, top: m.top, width: p, height: f },
                                                                                                            element: { element: l, left: D.left, top: D.top, width: h, height: u },
                                                                                                            horizontal: 0 > i ? "left" : e > 0 ? "right" : "center",
                                                                                                            vertical: 0 > r ? "top" : s > 0 ? "bottom" : "middle",
                                                                                                   };
                                                                                          h > p && p > a(e + i) && (c.horizontal = "center"),
                                                                                                   u > f && f > a(s + r) && (c.vertical = "middle"),
                                                                                                   (c.important = o(a(e), a(i)) > o(a(s), a(r)) ? "horizontal" : "vertical"),
                                                                                                   n.using.call(this, t, c);
                                                                                 }),
                                                                        l.offset(t.extend(D, { using: r }));
                                                      })
                                             );
                                    }),
                                    (t.ui.position = {
                                             fit: {
                                                      left: function (t, e) {
                                                               var i,
                                                                        s = e.within,
                                                                        n = s.isWindow ? s.scrollLeft : s.offset.left,
                                                                        a = s.width,
                                                                        r = t.left - e.collisionPosition.marginLeft,
                                                                        l = n - r,
                                                                        h = r + e.collisionWidth - a - n;
                                                               e.collisionWidth > a
                                                                        ? l > 0 && 0 >= h
                                                                                 ? ((i = t.left + l + e.collisionWidth - a - n), (t.left += l - i))
                                                                                 : (t.left = h > 0 && 0 >= l ? n : l > h ? n + a - e.collisionWidth : n)
                                                                        : l > 0
                                                                        ? (t.left += l)
                                                                        : h > 0
                                                                        ? (t.left -= h)
                                                                        : (t.left = o(t.left - r, t.left));
                                                      },
                                                      top: function (t, e) {
                                                               var i,
                                                                        s = e.within,
                                                                        n = s.isWindow ? s.scrollTop : s.offset.top,
                                                                        a = e.within.height,
                                                                        r = t.top - e.collisionPosition.marginTop,
                                                                        l = n - r,
                                                                        h = r + e.collisionHeight - a - n;
                                                               e.collisionHeight > a
                                                                        ? l > 0 && 0 >= h
                                                                                 ? ((i = t.top + l + e.collisionHeight - a - n), (t.top += l - i))
                                                                                 : (t.top = h > 0 && 0 >= l ? n : l > h ? n + a - e.collisionHeight : n)
                                                                        : l > 0
                                                                        ? (t.top += l)
                                                                        : h > 0
                                                                        ? (t.top -= h)
                                                                        : (t.top = o(t.top - r, t.top));
                                                      },
                                             },
                                             flip: {
                                                      left: function (t, e) {
                                                               var i,
                                                                        s,
                                                                        n = e.within,
                                                                        o = n.offset.left + n.scrollLeft,
                                                                        r = n.width,
                                                                        l = n.isWindow ? n.scrollLeft : n.offset.left,
                                                                        h = t.left - e.collisionPosition.marginLeft,
                                                                        u = h - l,
                                                                        c = h + e.collisionWidth - r - l,
                                                                        d = "left" === e.my[0] ? -e.elemWidth : "right" === e.my[0] ? e.elemWidth : 0,
                                                                        p = "left" === e.at[0] ? e.targetWidth : "right" === e.at[0] ? -e.targetWidth : 0,
                                                                        f = -2 * e.offset[0];
                                                               0 > u
                                                                        ? ((i = t.left + d + p + f + e.collisionWidth - r - o), (0 > i || a(u) > i) && (t.left += d + p + f))
                                                                        : c > 0 && ((s = t.left - e.collisionPosition.marginLeft + d + p + f - l), (s > 0 || c > a(s)) && (t.left += d + p + f));
                                                      },
                                                      top: function (t, e) {
                                                               var i,
                                                                        s,
                                                                        n = e.within,
                                                                        o = n.offset.top + n.scrollTop,
                                                                        r = n.height,
                                                                        l = n.isWindow ? n.scrollTop : n.offset.top,
                                                                        h = t.top - e.collisionPosition.marginTop,
                                                                        u = h - l,
                                                                        c = h + e.collisionHeight - r - l,
                                                                        d = "top" === e.my[1],
                                                                        p = d ? -e.elemHeight : "bottom" === e.my[1] ? e.elemHeight : 0,
                                                                        f = "top" === e.at[1] ? e.targetHeight : "bottom" === e.at[1] ? -e.targetHeight : 0,
                                                                        m = -2 * e.offset[1];
                                                               0 > u
                                                                        ? ((s = t.top + p + f + m + e.collisionHeight - r - o), (0 > s || a(u) > s) && (t.top += p + f + m))
                                                                        : c > 0 && ((i = t.top - e.collisionPosition.marginTop + p + f + m - l), (i > 0 || c > a(i)) && (t.top += p + f + m));
                                                      },
                                             },
                                             flipfit: {
                                                      left: function () {
                                                               t.ui.position.flip.left.apply(this, arguments), t.ui.position.fit.left.apply(this, arguments);
                                                      },
                                                      top: function () {
                                                               t.ui.position.flip.top.apply(this, arguments), t.ui.position.fit.top.apply(this, arguments);
                                                      },
                                             },
                                    });
                  })(),
                  t.ui.position,
                  t.extend(t.expr[":"], {
                           data: t.expr.createPseudo
                                    ? t.expr.createPseudo(function (e) {
                                               return function (i) {
                                                        return !!t.data(i, e);
                                               };
                                      })
                                    : function (e, i, s) {
                                               return !!t.data(e, s[3]);
                                      },
                  }),
                  t.fn.extend({
                           disableSelection: (function () {
                                    var t = "onselectstart" in document.createElement("div") ? "selectstart" : "mousedown";
                                    return function () {
                                             return this.on(t + ".ui-disableSelection", function (t) {
                                                      t.preventDefault();
                                             });
                                    };
                           })(),
                           enableSelection: function () {
                                    return this.off(".ui-disableSelection");
                           },
                  }),
                  (t.ui.focusable = function (i, s) {
                           var n,
                                    o,
                                    a,
                                    r,
                                    l,
                                    h = i.nodeName.toLowerCase();
                           return "area" === h
                                    ? ((n = i.parentNode), (o = n.name), i.href && o && "map" === n.nodeName.toLowerCase() ? ((a = t("img[usemap='#" + o + "']")), a.length > 0 && a.is(":visible")) : !1)
                                    : (/^(input|select|textarea|button|object)$/.test(h) ? ((r = !i.disabled), r && ((l = t(i).closest("fieldset")[0]), l && (r = !l.disabled))) : (r = "a" === h ? i.href || s : s),
                                      r && t(i).is(":visible") && e(t(i)));
                  }),
                  t.extend(t.expr[":"], {
                           focusable: function (e) {
                                    return t.ui.focusable(e, null != t.attr(e, "tabindex"));
                           },
                  }),
                  t.ui.focusable,
                  (t.fn.form = function () {
                           return "string" == typeof this[0].form ? this.closest("form") : t(this[0].form);
                  }),
                  (t.ui.formResetMixin = {
                           _formResetHandler: function () {
                                    var e = t(this);
                                    setTimeout(function () {
                                             var i = e.data("ui-form-reset-instances");
                                             t.each(i, function () {
                                                      this.refresh();
                                             });
                                    });
                           },
                           _bindFormResetHandler: function () {
                                    if (((this.form = this.element.form()), this.form.length)) {
                                             var t = this.form.data("ui-form-reset-instances") || [];
                                             t.length || this.form.on("reset.ui-form-reset", this._formResetHandler), t.push(this), this.form.data("ui-form-reset-instances", t);
                                    }
                           },
                           _unbindFormResetHandler: function () {
                                    if (this.form.length) {
                                             var e = this.form.data("ui-form-reset-instances");
                                             e.splice(t.inArray(this, e), 1), e.length ? this.form.data("ui-form-reset-instances", e) : this.form.removeData("ui-form-reset-instances").off("reset.ui-form-reset");
                                    }
                           },
                  }),
                  "1.7" === t.fn.jquery.substring(0, 3) &&
                           (t.each(["Width", "Height"], function (e, i) {
                                    function s(e, i, s, o) {
                                             return (
                                                      t.each(n, function () {
                                                               (i -= parseFloat(t.css(e, "padding" + this)) || 0), s && (i -= parseFloat(t.css(e, "border" + this + "Width")) || 0), o && (i -= parseFloat(t.css(e, "margin" + this)) || 0);
                                                      }),
                                                      i
                                             );
                                    }
                                    var n = "Width" === i ? ["Left", "Right"] : ["Top", "Bottom"],
                                             o = i.toLowerCase(),
                                             a = { innerWidth: t.fn.innerWidth, innerHeight: t.fn.innerHeight, outerWidth: t.fn.outerWidth, outerHeight: t.fn.outerHeight };
                                    (t.fn["inner" + i] = function (e) {
                                             return void 0 === e
                                                      ? a["inner" + i].call(this)
                                                      : this.each(function () {
                                                                 t(this).css(o, s(this, e) + "px");
                                                        });
                                    }),
                                             (t.fn["outer" + i] = function (e, n) {
                                                      return "number" != typeof e
                                                               ? a["outer" + i].call(this, e)
                                                               : this.each(function () {
                                                                          t(this).css(o, s(this, e, !0, n) + "px");
                                                                 });
                                             });
                           }),
                           (t.fn.addBack = function (t) {
                                    return this.add(null == t ? this.prevObject : this.prevObject.filter(t));
                           })),
                  (t.ui.keyCode = { BACKSPACE: 8, COMMA: 188, DELETE: 46, DOWN: 40, END: 35, ENTER: 13, ESCAPE: 27, HOME: 36, LEFT: 37, PAGE_DOWN: 34, PAGE_UP: 33, PERIOD: 190, RIGHT: 39, SPACE: 32, TAB: 9, UP: 38 }),
                  (t.ui.escapeSelector = (function () {
                           var t = /([!"#$%&'()*+,./:;<=>?@[\]^`{|}~])/g;
                           return function (e) {
                                    return e.replace(t, "\\$1");
                           };
                  })()),
                  (t.fn.labels = function () {
                           var e, i, s, n, o;
                           return this[0].labels && this[0].labels.length
                                    ? this.pushStack(this[0].labels)
                                    : ((n = this.eq(0).parents("label")),
                                      (s = this.attr("id")),
                                      s && ((e = this.eq(0).parents().last()), (o = e.add(e.length ? e.siblings() : this.siblings())), (i = "label[for='" + t.ui.escapeSelector(s) + "']"), (n = n.add(o.find(i).addBack(i)))),
                                      this.pushStack(n));
                  }),
                  (t.fn.scrollParent = function (e) {
                           var i = this.css("position"),
                                    s = "absolute" === i,
                                    n = e ? /(auto|scroll|hidden)/ : /(auto|scroll)/,
                                    o = this.parents()
                                             .filter(function () {
                                                      var e = t(this);
                                                      return s && "static" === e.css("position") ? !1 : n.test(e.css("overflow") + e.css("overflow-y") + e.css("overflow-x"));
                                             })
                                             .eq(0);
                           return "fixed" !== i && o.length ? o : t(this[0].ownerDocument || document);
                  }),
                  t.extend(t.expr[":"], {
                           tabbable: function (e) {
                                    var i = t.attr(e, "tabindex"),
                                             s = null != i;
                                    return (!s || i >= 0) && t.ui.focusable(e, s);
                           },
                  }),
                  t.fn.extend({
                           uniqueId: (function () {
                                    var t = 0;
                                    return function () {
                                             return this.each(function () {
                                                      this.id || (this.id = "ui-id-" + ++t);
                                             });
                                    };
                           })(),
                           removeUniqueId: function () {
                                    return this.each(function () {
                                             /^ui-id-\d+$/.test(this.id) && t(this).removeAttr("id");
                                    });
                           },
                  }),
                  (t.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()));
         var n = !1;
         t(document).on("mouseup", function () {
                  n = !1;
         }),
                  t.widget("ui.mouse", {
                           version: "1.12.1",
                           options: { cancel: "input, textarea, button, select, option", distance: 1, delay: 0 },
                           _mouseInit: function () {
                                    var e = this;
                                    this.element
                                             .on("mousedown." + this.widgetName, function (t) {
                                                      return e._mouseDown(t);
                                             })
                                             .on("click." + this.widgetName, function (i) {
                                                      return !0 === t.data(i.target, e.widgetName + ".preventClickEvent") ? (t.removeData(i.target, e.widgetName + ".preventClickEvent"), i.stopImmediatePropagation(), !1) : void 0;
                                             }),
                                             (this.started = !1);
                           },
                           _mouseDestroy: function () {
                                    this.element.off("." + this.widgetName), this._mouseMoveDelegate && this.document.off("mousemove." + this.widgetName, this._mouseMoveDelegate).off("mouseup." + this.widgetName, this._mouseUpDelegate);
                           },
                           _mouseDown: function (e) {
                                    if (!n) {
                                             (this._mouseMoved = !1), this._mouseStarted && this._mouseUp(e), (this._mouseDownEvent = e);
                                             var i = this,
                                                      s = 1 === e.which,
                                                      o = "string" == typeof this.options.cancel && e.target.nodeName ? t(e.target).closest(this.options.cancel).length : !1;
                                             return s && !o && this._mouseCapture(e)
                                                      ? ((this.mouseDelayMet = !this.options.delay),
                                                        this.mouseDelayMet ||
                                                                 (this._mouseDelayTimer = setTimeout(function () {
                                                                          i.mouseDelayMet = !0;
                                                                 }, this.options.delay)),
                                                        this._mouseDistanceMet(e) && this._mouseDelayMet(e) && ((this._mouseStarted = this._mouseStart(e) !== !1), !this._mouseStarted)
                                                                 ? (e.preventDefault(), !0)
                                                                 : (!0 === t.data(e.target, this.widgetName + ".preventClickEvent") && t.removeData(e.target, this.widgetName + ".preventClickEvent"),
                                                                   (this._mouseMoveDelegate = function (t) {
                                                                            return i._mouseMove(t);
                                                                   }),
                                                                   (this._mouseUpDelegate = function (t) {
                                                                            return i._mouseUp(t);
                                                                   }),
                                                                   this.document.on("mousemove." + this.widgetName, this._mouseMoveDelegate).on("mouseup." + this.widgetName, this._mouseUpDelegate),
                                                                   e.preventDefault(),
                                                                   (n = !0),
                                                                   !0))
                                                      : !0;
                                    }
                           },
                           _mouseMove: function (e) {
                                    if (this._mouseMoved) {
                                             if (t.ui.ie && (!document.documentMode || 9 > document.documentMode) && !e.button) return this._mouseUp(e);
                                             if (!e.which)
                                                      if (e.originalEvent.altKey || e.originalEvent.ctrlKey || e.originalEvent.metaKey || e.originalEvent.shiftKey) this.ignoreMissingWhich = !0;
                                                      else if (!this.ignoreMissingWhich) return this._mouseUp(e);
                                    }
                                    return (
                                             (e.which || e.button) && (this._mouseMoved = !0),
                                             this._mouseStarted
                                                      ? (this._mouseDrag(e), e.preventDefault())
                                                      : (this._mouseDistanceMet(e) &&
                                                                 this._mouseDelayMet(e) &&
                                                                 ((this._mouseStarted = this._mouseStart(this._mouseDownEvent, e) !== !1), this._mouseStarted ? this._mouseDrag(e) : this._mouseUp(e)),
                                                        !this._mouseStarted)
                                    );
                           },
                           _mouseUp: function (e) {
                                    this.document.off("mousemove." + this.widgetName, this._mouseMoveDelegate).off("mouseup." + this.widgetName, this._mouseUpDelegate),
                                             this._mouseStarted && ((this._mouseStarted = !1), e.target === this._mouseDownEvent.target && t.data(e.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(e)),
                                             this._mouseDelayTimer && (clearTimeout(this._mouseDelayTimer), delete this._mouseDelayTimer),
                                             (this.ignoreMissingWhich = !1),
                                             (n = !1),
                                             e.preventDefault();
                           },
                           _mouseDistanceMet: function (t) {
                                    return Math.max(Math.abs(this._mouseDownEvent.pageX - t.pageX), Math.abs(this._mouseDownEvent.pageY - t.pageY)) >= this.options.distance;
                           },
                           _mouseDelayMet: function () {
                                    return this.mouseDelayMet;
                           },
                           _mouseStart: function () {},
                           _mouseDrag: function () {},
                           _mouseStop: function () {},
                           _mouseCapture: function () {
                                    return !0;
                           },
                  }),
                  (t.ui.plugin = {
                           add: function (e, i, s) {
                                    var n,
                                             o = t.ui[e].prototype;
                                    for (n in s) (o.plugins[n] = o.plugins[n] || []), o.plugins[n].push([i, s[n]]);
                           },
                           call: function (t, e, i, s) {
                                    var n,
                                             o = t.plugins[e];
                                    if (o && (s || (t.element[0].parentNode && 11 !== t.element[0].parentNode.nodeType))) for (n = 0; o.length > n; n++) t.options[o[n][0]] && o[n][1].apply(t.element, i);
                           },
                  }),
                  (t.ui.safeActiveElement = function (t) {
                           var e;
                           try {
                                    e = t.activeElement;
                           } catch (i) {
                                    e = t.body;
                           }
                           return e || (e = t.body), e.nodeName || (e = t.body), e;
                  }),
                  (t.ui.safeBlur = function (e) {
                           e && "body" !== e.nodeName.toLowerCase() && t(e).trigger("blur");
                  }),
                  t.widget("ui.draggable", t.ui.mouse, {
                           version: "1.12.1",
                           widgetEventPrefix: "drag",
                           options: {
                                    addClasses: !0,
                                    appendTo: "parent",
                                    axis: !1,
                                    connectToSortable: !1,
                                    containment: !1,
                                    cursor: "auto",
                                    cursorAt: !1,
                                    grid: !1,
                                    handle: !1,
                                    helper: "original",
                                    iframeFix: !1,
                                    opacity: !1,
                                    refreshPositions: !1,
                                    revert: !1,
                                    revertDuration: 500,
                                    scope: "default",
                                    scroll: !0,
                                    scrollSensitivity: 20,
                                    scrollSpeed: 20,
                                    snap: !1,
                                    snapMode: "both",
                                    snapTolerance: 20,
                                    stack: !1,
                                    zIndex: !1,
                                    drag: null,
                                    start: null,
                                    stop: null,
                           },
                           _create: function () {
                                    "original" === this.options.helper && this._setPositionRelative(), this.options.addClasses && this._addClass("ui-draggable"), this._setHandleClassName(), this._mouseInit();
                           },
                           _setOption: function (t, e) {
                                    this._super(t, e), "handle" === t && (this._removeHandleClassName(), this._setHandleClassName());
                           },
                           _destroy: function () {
                                    return (this.helper || this.element).is(".ui-draggable-dragging") ? ((this.destroyOnClear = !0), void 0) : (this._removeHandleClassName(), this._mouseDestroy(), void 0);
                           },
                           _mouseCapture: function (e) {
                                    var i = this.options;
                                    return this.helper || i.disabled || t(e.target).closest(".ui-resizable-handle").length > 0
                                             ? !1
                                             : ((this.handle = this._getHandle(e)), this.handle ? (this._blurActiveElement(e), this._blockFrames(i.iframeFix === !0 ? "iframe" : i.iframeFix), !0) : !1);
                           },
                           _blockFrames: function (e) {
                                    this.iframeBlocks = this.document.find(e).map(function () {
                                             var e = t(this);
                                             return t("<div>").css("position", "absolute").appendTo(e.parent()).outerWidth(e.outerWidth()).outerHeight(e.outerHeight()).offset(e.offset())[0];
                                    });
                           },
                           _unblockFrames: function () {
                                    this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks);
                           },
                           _blurActiveElement: function (e) {
                                    var i = t.ui.safeActiveElement(this.document[0]),
                                             s = t(e.target);
                                    s.closest(i).length || t.ui.safeBlur(i);
                           },
                           _mouseStart: function (e) {
                                    var i = this.options;
                                    return (
                                             (this.helper = this._createHelper(e)),
                                             this._addClass(this.helper, "ui-draggable-dragging"),
                                             this._cacheHelperProportions(),
                                             t.ui.ddmanager && (t.ui.ddmanager.current = this),
                                             this._cacheMargins(),
                                             (this.cssPosition = this.helper.css("position")),
                                             (this.scrollParent = this.helper.scrollParent(!0)),
                                             (this.offsetParent = this.helper.offsetParent()),
                                             (this.hasFixedAncestor =
                                                      this.helper.parents().filter(function () {
                                                               return "fixed" === t(this).css("position");
                                                      }).length > 0),
                                             (this.positionAbs = this.element.offset()),
                                             this._refreshOffsets(e),
                                             (this.originalPosition = this.position = this._generatePosition(e, !1)),
                                             (this.originalPageX = e.pageX),
                                             (this.originalPageY = e.pageY),
                                             i.cursorAt && this._adjustOffsetFromHelper(i.cursorAt),
                                             this._setContainment(),
                                             this._trigger("start", e) === !1
                                                      ? (this._clear(), !1)
                                                      : (this._cacheHelperProportions(),
                                                        t.ui.ddmanager && !i.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e),
                                                        this._mouseDrag(e, !0),
                                                        t.ui.ddmanager && t.ui.ddmanager.dragStart(this, e),
                                                        !0)
                                    );
                           },
                           _refreshOffsets: function (t) {
                                    (this.offset = { top: this.positionAbs.top - this.margins.top, left: this.positionAbs.left - this.margins.left, scroll: !1, parent: this._getParentOffset(), relative: this._getRelativeOffset() }),
                                             (this.offset.click = { left: t.pageX - this.offset.left, top: t.pageY - this.offset.top });
                           },
                           _mouseDrag: function (e, i) {
                                    if ((this.hasFixedAncestor && (this.offset.parent = this._getParentOffset()), (this.position = this._generatePosition(e, !0)), (this.positionAbs = this._convertPositionTo("absolute")), !i)) {
                                             var s = this._uiHash();
                                             if (this._trigger("drag", e, s) === !1) return this._mouseUp(new t.Event("mouseup", e)), !1;
                                             this.position = s.position;
                                    }
                                    return (this.helper[0].style.left = this.position.left + "px"), (this.helper[0].style.top = this.position.top + "px"), t.ui.ddmanager && t.ui.ddmanager.drag(this, e), !1;
                           },
                           _mouseStop: function (e) {
                                    var i = this,
                                             s = !1;
                                    return (
                                             t.ui.ddmanager && !this.options.dropBehaviour && (s = t.ui.ddmanager.drop(this, e)),
                                             this.dropped && ((s = this.dropped), (this.dropped = !1)),
                                             ("invalid" === this.options.revert && !s) ||
                                             ("valid" === this.options.revert && s) ||
                                             this.options.revert === !0 ||
                                             (t.isFunction(this.options.revert) && this.options.revert.call(this.element, s))
                                                      ? t(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function () {
                                                                 i._trigger("stop", e) !== !1 && i._clear();
                                                        })
                                                      : this._trigger("stop", e) !== !1 && this._clear(),
                                             !1
                                    );
                           },
                           _mouseUp: function (e) {
                                    return this._unblockFrames(), t.ui.ddmanager && t.ui.ddmanager.dragStop(this, e), this.handleElement.is(e.target) && this.element.trigger("focus"), t.ui.mouse.prototype._mouseUp.call(this, e);
                           },
                           cancel: function () {
                                    return this.helper.is(".ui-draggable-dragging") ? this._mouseUp(new t.Event("mouseup", { target: this.element[0] })) : this._clear(), this;
                           },
                           _getHandle: function (e) {
                                    return this.options.handle ? !!t(e.target).closest(this.element.find(this.options.handle)).length : !0;
                           },
                           _setHandleClassName: function () {
                                    (this.handleElement = this.options.handle ? this.element.find(this.options.handle) : this.element), this._addClass(this.handleElement, "ui-draggable-handle");
                           },
                           _removeHandleClassName: function () {
                                    this._removeClass(this.handleElement, "ui-draggable-handle");
                           },
                           _createHelper: function (e) {
                                    var i = this.options,
                                             s = t.isFunction(i.helper),
                                             n = s ? t(i.helper.apply(this.element[0], [e])) : "clone" === i.helper ? this.element.clone().removeAttr("id") : this.element;
                                    return (
                                             n.parents("body").length || n.appendTo("parent" === i.appendTo ? this.element[0].parentNode : i.appendTo),
                                             s && n[0] === this.element[0] && this._setPositionRelative(),
                                             n[0] === this.element[0] || /(fixed|absolute)/.test(n.css("position")) || n.css("position", "absolute"),
                                             n
                                    );
                           },
                           _setPositionRelative: function () {
                                    /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative");
                           },
                           _adjustOffsetFromHelper: function (e) {
                                    "string" == typeof e && (e = e.split(" ")),
                                             t.isArray(e) && (e = { left: +e[0], top: +e[1] || 0 }),
                                             "left" in e && (this.offset.click.left = e.left + this.margins.left),
                                             "right" in e && (this.offset.click.left = this.helperProportions.width - e.right + this.margins.left),
                                             "top" in e && (this.offset.click.top = e.top + this.margins.top),
                                             "bottom" in e && (this.offset.click.top = this.helperProportions.height - e.bottom + this.margins.top);
                           },
                           _isRootNode: function (t) {
                                    return /(html|body)/i.test(t.tagName) || t === this.document[0];
                           },
                           _getParentOffset: function () {
                                    var e = this.offsetParent.offset(),
                                             i = this.document[0];
                                    return (
                                             "absolute" === this.cssPosition &&
                                                      this.scrollParent[0] !== i &&
                                                      t.contains(this.scrollParent[0], this.offsetParent[0]) &&
                                                      ((e.left += this.scrollParent.scrollLeft()), (e.top += this.scrollParent.scrollTop())),
                                             this._isRootNode(this.offsetParent[0]) && (e = { top: 0, left: 0 }),
                                             { top: e.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0), left: e.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0) }
                                    );
                           },
                           _getRelativeOffset: function () {
                                    if ("relative" !== this.cssPosition) return { top: 0, left: 0 };
                                    var t = this.element.position(),
                                             e = this._isRootNode(this.scrollParent[0]);
                                    return {
                                             top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + (e ? 0 : this.scrollParent.scrollTop()),
                                             left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + (e ? 0 : this.scrollParent.scrollLeft()),
                                    };
                           },
                           _cacheMargins: function () {
                                    this.margins = {
                                             left: parseInt(this.element.css("marginLeft"), 10) || 0,
                                             top: parseInt(this.element.css("marginTop"), 10) || 0,
                                             right: parseInt(this.element.css("marginRight"), 10) || 0,
                                             bottom: parseInt(this.element.css("marginBottom"), 10) || 0,
                                    };
                           },
                           _cacheHelperProportions: function () {
                                    this.helperProportions = { width: this.helper.outerWidth(), height: this.helper.outerHeight() };
                           },
                           _setContainment: function () {
                                    var e,
                                             i,
                                             s,
                                             n = this.options,
                                             o = this.document[0];
                                    return (
                                             (this.relativeContainer = null),
                                             n.containment
                                                      ? "window" === n.containment
                                                               ? ((this.containment = [
                                                                          t(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left,
                                                                          t(window).scrollTop() - this.offset.relative.top - this.offset.parent.top,
                                                                          t(window).scrollLeft() + t(window).width() - this.helperProportions.width - this.margins.left,
                                                                          t(window).scrollTop() + (t(window).height() || o.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top,
                                                                 ]),
                                                                 void 0)
                                                               : "document" === n.containment
                                                               ? ((this.containment = [
                                                                          0,
                                                                          0,
                                                                          t(o).width() - this.helperProportions.width - this.margins.left,
                                                                          (t(o).height() || o.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top,
                                                                 ]),
                                                                 void 0)
                                                               : n.containment.constructor === Array
                                                               ? ((this.containment = n.containment), void 0)
                                                               : ("parent" === n.containment && (n.containment = this.helper[0].parentNode),
                                                                 (i = t(n.containment)),
                                                                 (s = i[0]),
                                                                 s &&
                                                                          ((e = /(scroll|auto)/.test(i.css("overflow"))),
                                                                          (this.containment = [
                                                                                   (parseInt(i.css("borderLeftWidth"), 10) || 0) + (parseInt(i.css("paddingLeft"), 10) || 0),
                                                                                   (parseInt(i.css("borderTopWidth"), 10) || 0) + (parseInt(i.css("paddingTop"), 10) || 0),
                                                                                   (e ? Math.max(s.scrollWidth, s.offsetWidth) : s.offsetWidth) -
                                                                                            (parseInt(i.css("borderRightWidth"), 10) || 0) -
                                                                                            (parseInt(i.css("paddingRight"), 10) || 0) -
                                                                                            this.helperProportions.width -
                                                                                            this.margins.left -
                                                                                            this.margins.right,
                                                                                   (e ? Math.max(s.scrollHeight, s.offsetHeight) : s.offsetHeight) -
                                                                                            (parseInt(i.css("borderBottomWidth"), 10) || 0) -
                                                                                            (parseInt(i.css("paddingBottom"), 10) || 0) -
                                                                                            this.helperProportions.height -
                                                                                            this.margins.top -
                                                                                            this.margins.bottom,
                                                                          ]),
                                                                          (this.relativeContainer = i)),
                                                                 void 0)
                                                      : ((this.containment = null), void 0)
                                    );
                           },
                           _convertPositionTo: function (t, e) {
                                    e || (e = this.position);
                                    var i = "absolute" === t ? 1 : -1,
                                             s = this._isRootNode(this.scrollParent[0]);
                                    return {
                                             top: e.top + this.offset.relative.top * i + this.offset.parent.top * i - ("fixed" === this.cssPosition ? -this.offset.scroll.top : s ? 0 : this.offset.scroll.top) * i,
                                             left: e.left + this.offset.relative.left * i + this.offset.parent.left * i - ("fixed" === this.cssPosition ? -this.offset.scroll.left : s ? 0 : this.offset.scroll.left) * i,
                                    };
                           },
                           _generatePosition: function (t, e) {
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a = this.options,
                                             r = this._isRootNode(this.scrollParent[0]),
                                             l = t.pageX,
                                             h = t.pageY;
                                    return (
                                             (r && this.offset.scroll) || (this.offset.scroll = { top: this.scrollParent.scrollTop(), left: this.scrollParent.scrollLeft() }),
                                             e &&
                                                      (this.containment &&
                                                               (this.relativeContainer
                                                                        ? ((s = this.relativeContainer.offset()), (i = [this.containment[0] + s.left, this.containment[1] + s.top, this.containment[2] + s.left, this.containment[3] + s.top]))
                                                                        : (i = this.containment),
                                                               t.pageX - this.offset.click.left < i[0] && (l = i[0] + this.offset.click.left),
                                                               t.pageY - this.offset.click.top < i[1] && (h = i[1] + this.offset.click.top),
                                                               t.pageX - this.offset.click.left > i[2] && (l = i[2] + this.offset.click.left),
                                                               t.pageY - this.offset.click.top > i[3] && (h = i[3] + this.offset.click.top)),
                                                      a.grid &&
                                                               ((n = a.grid[1] ? this.originalPageY + Math.round((h - this.originalPageY) / a.grid[1]) * a.grid[1] : this.originalPageY),
                                                               (h = i ? (n - this.offset.click.top >= i[1] || n - this.offset.click.top > i[3] ? n : n - this.offset.click.top >= i[1] ? n - a.grid[1] : n + a.grid[1]) : n),
                                                               (o = a.grid[0] ? this.originalPageX + Math.round((l - this.originalPageX) / a.grid[0]) * a.grid[0] : this.originalPageX),
                                                               (l = i ? (o - this.offset.click.left >= i[0] || o - this.offset.click.left > i[2] ? o : o - this.offset.click.left >= i[0] ? o - a.grid[0] : o + a.grid[0]) : o)),
                                                      "y" === a.axis && (l = this.originalPageX),
                                                      "x" === a.axis && (h = this.originalPageY)),
                                             {
                                                      top: h - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.offset.scroll.top : r ? 0 : this.offset.scroll.top),
                                                      left: l - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.offset.scroll.left : r ? 0 : this.offset.scroll.left),
                                             }
                                    );
                           },
                           _clear: function () {
                                    this._removeClass(this.helper, "ui-draggable-dragging"),
                                             this.helper[0] === this.element[0] || this.cancelHelperRemoval || this.helper.remove(),
                                             (this.helper = null),
                                             (this.cancelHelperRemoval = !1),
                                             this.destroyOnClear && this.destroy();
                           },
                           _trigger: function (e, i, s) {
                                    return (
                                             (s = s || this._uiHash()),
                                             t.ui.plugin.call(this, e, [i, s, this], !0),
                                             /^(drag|start|stop)/.test(e) && ((this.positionAbs = this._convertPositionTo("absolute")), (s.offset = this.positionAbs)),
                                             t.Widget.prototype._trigger.call(this, e, i, s)
                                    );
                           },
                           plugins: {},
                           _uiHash: function () {
                                    return { helper: this.helper, position: this.position, originalPosition: this.originalPosition, offset: this.positionAbs };
                           },
                  }),
                  t.ui.plugin.add("draggable", "connectToSortable", {
                           start: function (e, i, s) {
                                    var n = t.extend({}, i, { item: s.element });
                                    (s.sortables = []),
                                             t(s.options.connectToSortable).each(function () {
                                                      var i = t(this).sortable("instance");
                                                      i && !i.options.disabled && (s.sortables.push(i), i.refreshPositions(), i._trigger("activate", e, n));
                                             });
                           },
                           stop: function (e, i, s) {
                                    var n = t.extend({}, i, { item: s.element });
                                    (s.cancelHelperRemoval = !1),
                                             t.each(s.sortables, function () {
                                                      var t = this;
                                                      t.isOver
                                                               ? ((t.isOver = 0),
                                                                 (s.cancelHelperRemoval = !0),
                                                                 (t.cancelHelperRemoval = !1),
                                                                 (t._storedCSS = { position: t.placeholder.css("position"), top: t.placeholder.css("top"), left: t.placeholder.css("left") }),
                                                                 t._mouseStop(e),
                                                                 (t.options.helper = t.options._helper))
                                                               : ((t.cancelHelperRemoval = !0), t._trigger("deactivate", e, n));
                                             });
                           },
                           drag: function (e, i, s) {
                                    t.each(s.sortables, function () {
                                             var n = !1,
                                                      o = this;
                                             (o.positionAbs = s.positionAbs),
                                                      (o.helperProportions = s.helperProportions),
                                                      (o.offset.click = s.offset.click),
                                                      o._intersectsWith(o.containerCache) &&
                                                               ((n = !0),
                                                               t.each(s.sortables, function () {
                                                                        return (
                                                                                 (this.positionAbs = s.positionAbs),
                                                                                 (this.helperProportions = s.helperProportions),
                                                                                 (this.offset.click = s.offset.click),
                                                                                 this !== o && this._intersectsWith(this.containerCache) && t.contains(o.element[0], this.element[0]) && (n = !1),
                                                                                 n
                                                                        );
                                                               })),
                                                      n
                                                               ? (o.isOver ||
                                                                          ((o.isOver = 1),
                                                                          (s._parent = i.helper.parent()),
                                                                          (o.currentItem = i.helper.appendTo(o.element).data("ui-sortable-item", !0)),
                                                                          (o.options._helper = o.options.helper),
                                                                          (o.options.helper = function () {
                                                                                   return i.helper[0];
                                                                          }),
                                                                          (e.target = o.currentItem[0]),
                                                                          o._mouseCapture(e, !0),
                                                                          o._mouseStart(e, !0, !0),
                                                                          (o.offset.click.top = s.offset.click.top),
                                                                          (o.offset.click.left = s.offset.click.left),
                                                                          (o.offset.parent.left -= s.offset.parent.left - o.offset.parent.left),
                                                                          (o.offset.parent.top -= s.offset.parent.top - o.offset.parent.top),
                                                                          s._trigger("toSortable", e),
                                                                          (s.dropped = o.element),
                                                                          t.each(s.sortables, function () {
                                                                                   this.refreshPositions();
                                                                          }),
                                                                          (s.currentItem = s.element),
                                                                          (o.fromOutside = s)),
                                                                 o.currentItem && (o._mouseDrag(e), (i.position = o.position)))
                                                               : o.isOver &&
                                                                 ((o.isOver = 0),
                                                                 (o.cancelHelperRemoval = !0),
                                                                 (o.options._revert = o.options.revert),
                                                                 (o.options.revert = !1),
                                                                 o._trigger("out", e, o._uiHash(o)),
                                                                 o._mouseStop(e, !0),
                                                                 (o.options.revert = o.options._revert),
                                                                 (o.options.helper = o.options._helper),
                                                                 o.placeholder && o.placeholder.remove(),
                                                                 i.helper.appendTo(s._parent),
                                                                 s._refreshOffsets(e),
                                                                 (i.position = s._generatePosition(e, !0)),
                                                                 s._trigger("fromSortable", e),
                                                                 (s.dropped = !1),
                                                                 t.each(s.sortables, function () {
                                                                          this.refreshPositions();
                                                                 }));
                                    });
                           },
                  }),
                  t.ui.plugin.add("draggable", "cursor", {
                           start: function (e, i, s) {
                                    var n = t("body"),
                                             o = s.options;
                                    n.css("cursor") && (o._cursor = n.css("cursor")), n.css("cursor", o.cursor);
                           },
                           stop: function (e, i, s) {
                                    var n = s.options;
                                    n._cursor && t("body").css("cursor", n._cursor);
                           },
                  }),
                  t.ui.plugin.add("draggable", "opacity", {
                           start: function (e, i, s) {
                                    var n = t(i.helper),
                                             o = s.options;
                                    n.css("opacity") && (o._opacity = n.css("opacity")), n.css("opacity", o.opacity);
                           },
                           stop: function (e, i, s) {
                                    var n = s.options;
                                    n._opacity && t(i.helper).css("opacity", n._opacity);
                           },
                  }),
                  t.ui.plugin.add("draggable", "scroll", {
                           start: function (t, e, i) {
                                    i.scrollParentNotHidden || (i.scrollParentNotHidden = i.helper.scrollParent(!1)),
                                             i.scrollParentNotHidden[0] !== i.document[0] && "HTML" !== i.scrollParentNotHidden[0].tagName && (i.overflowOffset = i.scrollParentNotHidden.offset());
                           },
                           drag: function (e, i, s) {
                                    var n = s.options,
                                             o = !1,
                                             a = s.scrollParentNotHidden[0],
                                             r = s.document[0];
                                    a !== r && "HTML" !== a.tagName
                                             ? ((n.axis && "x" === n.axis) ||
                                                        (s.overflowOffset.top + a.offsetHeight - e.pageY < n.scrollSensitivity
                                                                 ? (a.scrollTop = o = a.scrollTop + n.scrollSpeed)
                                                                 : e.pageY - s.overflowOffset.top < n.scrollSensitivity && (a.scrollTop = o = a.scrollTop - n.scrollSpeed)),
                                               (n.axis && "y" === n.axis) ||
                                                        (s.overflowOffset.left + a.offsetWidth - e.pageX < n.scrollSensitivity
                                                                 ? (a.scrollLeft = o = a.scrollLeft + n.scrollSpeed)
                                                                 : e.pageX - s.overflowOffset.left < n.scrollSensitivity && (a.scrollLeft = o = a.scrollLeft - n.scrollSpeed)))
                                             : ((n.axis && "x" === n.axis) ||
                                                        (e.pageY - t(r).scrollTop() < n.scrollSensitivity
                                                                 ? (o = t(r).scrollTop(t(r).scrollTop() - n.scrollSpeed))
                                                                 : t(window).height() - (e.pageY - t(r).scrollTop()) < n.scrollSensitivity && (o = t(r).scrollTop(t(r).scrollTop() + n.scrollSpeed))),
                                               (n.axis && "y" === n.axis) ||
                                                        (e.pageX - t(r).scrollLeft() < n.scrollSensitivity
                                                                 ? (o = t(r).scrollLeft(t(r).scrollLeft() - n.scrollSpeed))
                                                                 : t(window).width() - (e.pageX - t(r).scrollLeft()) < n.scrollSensitivity && (o = t(r).scrollLeft(t(r).scrollLeft() + n.scrollSpeed)))),
                                             o !== !1 && t.ui.ddmanager && !n.dropBehaviour && t.ui.ddmanager.prepareOffsets(s, e);
                           },
                  }),
                  t.ui.plugin.add("draggable", "snap", {
                           start: function (e, i, s) {
                                    var n = s.options;
                                    (s.snapElements = []),
                                             t(n.snap.constructor !== String ? n.snap.items || ":data(ui-draggable)" : n.snap).each(function () {
                                                      var e = t(this),
                                                               i = e.offset();
                                                      this !== s.element[0] && s.snapElements.push({ item: this, width: e.outerWidth(), height: e.outerHeight(), top: i.top, left: i.left });
                                             });
                           },
                           drag: function (e, i, s) {
                                    var n,
                                             o,
                                             a,
                                             r,
                                             l,
                                             h,
                                             u,
                                             c,
                                             d,
                                             p,
                                             f = s.options,
                                             m = f.snapTolerance,
                                             g = i.offset.left,
                                             _ = g + s.helperProportions.width,
                                             v = i.offset.top,
                                             b = v + s.helperProportions.height;
                                    for (d = s.snapElements.length - 1; d >= 0; d--)
                                             (l = s.snapElements[d].left - s.margins.left),
                                                      (h = l + s.snapElements[d].width),
                                                      (u = s.snapElements[d].top - s.margins.top),
                                                      (c = u + s.snapElements[d].height),
                                                      l - m > _ || g > h + m || u - m > b || v > c + m || !t.contains(s.snapElements[d].item.ownerDocument, s.snapElements[d].item)
                                                               ? (s.snapElements[d].snapping && s.options.snap.release && s.options.snap.release.call(s.element, e, t.extend(s._uiHash(), { snapItem: s.snapElements[d].item })),
                                                                 (s.snapElements[d].snapping = !1))
                                                               : ("inner" !== f.snapMode &&
                                                                          ((n = m >= Math.abs(u - b)),
                                                                          (o = m >= Math.abs(c - v)),
                                                                          (a = m >= Math.abs(l - _)),
                                                                          (r = m >= Math.abs(h - g)),
                                                                          n && (i.position.top = s._convertPositionTo("relative", { top: u - s.helperProportions.height, left: 0 }).top),
                                                                          o && (i.position.top = s._convertPositionTo("relative", { top: c, left: 0 }).top),
                                                                          a && (i.position.left = s._convertPositionTo("relative", { top: 0, left: l - s.helperProportions.width }).left),
                                                                          r && (i.position.left = s._convertPositionTo("relative", { top: 0, left: h }).left)),
                                                                 (p = n || o || a || r),
                                                                 "outer" !== f.snapMode &&
                                                                          ((n = m >= Math.abs(u - v)),
                                                                          (o = m >= Math.abs(c - b)),
                                                                          (a = m >= Math.abs(l - g)),
                                                                          (r = m >= Math.abs(h - _)),
                                                                          n && (i.position.top = s._convertPositionTo("relative", { top: u, left: 0 }).top),
                                                                          o && (i.position.top = s._convertPositionTo("relative", { top: c - s.helperProportions.height, left: 0 }).top),
                                                                          a && (i.position.left = s._convertPositionTo("relative", { top: 0, left: l }).left),
                                                                          r && (i.position.left = s._convertPositionTo("relative", { top: 0, left: h - s.helperProportions.width }).left)),
                                                                 !s.snapElements[d].snapping &&
                                                                          (n || o || a || r || p) &&
                                                                          s.options.snap.snap &&
                                                                          s.options.snap.snap.call(s.element, e, t.extend(s._uiHash(), { snapItem: s.snapElements[d].item })),
                                                                 (s.snapElements[d].snapping = n || o || a || r || p));
                           },
                  }),
                  t.ui.plugin.add("draggable", "stack", {
                           start: function (e, i, s) {
                                    var n,
                                             o = s.options,
                                             a = t.makeArray(t(o.stack)).sort(function (e, i) {
                                                      return (parseInt(t(e).css("zIndex"), 10) || 0) - (parseInt(t(i).css("zIndex"), 10) || 0);
                                             });
                                    a.length &&
                                             ((n = parseInt(t(a[0]).css("zIndex"), 10) || 0),
                                             t(a).each(function (e) {
                                                      t(this).css("zIndex", n + e);
                                             }),
                                             this.css("zIndex", n + a.length));
                           },
                  }),
                  t.ui.plugin.add("draggable", "zIndex", {
                           start: function (e, i, s) {
                                    var n = t(i.helper),
                                             o = s.options;
                                    n.css("zIndex") && (o._zIndex = n.css("zIndex")), n.css("zIndex", o.zIndex);
                           },
                           stop: function (e, i, s) {
                                    var n = s.options;
                                    n._zIndex && t(i.helper).css("zIndex", n._zIndex);
                           },
                  }),
                  t.ui.draggable,
                  t.widget("ui.droppable", {
                           version: "1.12.1",
                           widgetEventPrefix: "drop",
                           options: { accept: "*", addClasses: !0, greedy: !1, scope: "default", tolerance: "intersect", activate: null, deactivate: null, drop: null, out: null, over: null },
                           _create: function () {
                                    var e,
                                             i = this.options,
                                             s = i.accept;
                                    (this.isover = !1),
                                             (this.isout = !0),
                                             (this.accept = t.isFunction(s)
                                                      ? s
                                                      : function (t) {
                                                                 return t.is(s);
                                                        }),
                                             (this.proportions = function () {
                                                      return arguments.length ? ((e = arguments[0]), void 0) : e ? e : (e = { width: this.element[0].offsetWidth, height: this.element[0].offsetHeight });
                                             }),
                                             this._addToManager(i.scope),
                                             i.addClasses && this._addClass("ui-droppable");
                           },
                           _addToManager: function (e) {
                                    (t.ui.ddmanager.droppables[e] = t.ui.ddmanager.droppables[e] || []), t.ui.ddmanager.droppables[e].push(this);
                           },
                           _splice: function (t) {
                                    for (var e = 0; t.length > e; e++) t[e] === this && t.splice(e, 1);
                           },
                           _destroy: function () {
                                    var e = t.ui.ddmanager.droppables[this.options.scope];
                                    this._splice(e);
                           },
                           _setOption: function (e, i) {
                                    if ("accept" === e)
                                             this.accept = t.isFunction(i)
                                                      ? i
                                                      : function (t) {
                                                                 return t.is(i);
                                                        };
                                    else if ("scope" === e) {
                                             var s = t.ui.ddmanager.droppables[this.options.scope];
                                             this._splice(s), this._addToManager(i);
                                    }
                                    this._super(e, i);
                           },
                           _activate: function (e) {
                                    var i = t.ui.ddmanager.current;
                                    this._addActiveClass(), i && this._trigger("activate", e, this.ui(i));
                           },
                           _deactivate: function (e) {
                                    var i = t.ui.ddmanager.current;
                                    this._removeActiveClass(), i && this._trigger("deactivate", e, this.ui(i));
                           },
                           _over: function (e) {
                                    var i = t.ui.ddmanager.current;
                                    i && (i.currentItem || i.element)[0] !== this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this._addHoverClass(), this._trigger("over", e, this.ui(i)));
                           },
                           _out: function (e) {
                                    var i = t.ui.ddmanager.current;
                                    i && (i.currentItem || i.element)[0] !== this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this._removeHoverClass(), this._trigger("out", e, this.ui(i)));
                           },
                           _drop: function (e, i) {
                                    var s = i || t.ui.ddmanager.current,
                                             n = !1;
                                    return s && (s.currentItem || s.element)[0] !== this.element[0]
                                             ? (this.element
                                                        .find(":data(ui-droppable)")
                                                        .not(".ui-draggable-dragging")
                                                        .each(function () {
                                                                 var i = t(this).droppable("instance");
                                                                 return i.options.greedy &&
                                                                          !i.options.disabled &&
                                                                          i.options.scope === s.options.scope &&
                                                                          i.accept.call(i.element[0], s.currentItem || s.element) &&
                                                                          o(s, t.extend(i, { offset: i.element.offset() }), i.options.tolerance, e)
                                                                          ? ((n = !0), !1)
                                                                          : void 0;
                                                        }),
                                               n ? !1 : this.accept.call(this.element[0], s.currentItem || s.element) ? (this._removeActiveClass(), this._removeHoverClass(), this._trigger("drop", e, this.ui(s)), this.element) : !1)
                                             : !1;
                           },
                           ui: function (t) {
                                    return { draggable: t.currentItem || t.element, helper: t.helper, position: t.position, offset: t.positionAbs };
                           },
                           _addHoverClass: function () {
                                    this._addClass("ui-droppable-hover");
                           },
                           _removeHoverClass: function () {
                                    this._removeClass("ui-droppable-hover");
                           },
                           _addActiveClass: function () {
                                    this._addClass("ui-droppable-active");
                           },
                           _removeActiveClass: function () {
                                    this._removeClass("ui-droppable-active");
                           },
                  });
         var o = (t.ui.intersect = (function () {
                  function t(t, e, i) {
                           return t >= e && e + i > t;
                  }
                  return function (e, i, s, n) {
                           if (!i.offset) return !1;
                           var o = (e.positionAbs || e.position.absolute).left + e.margins.left,
                                    a = (e.positionAbs || e.position.absolute).top + e.margins.top,
                                    r = o + e.helperProportions.width,
                                    l = a + e.helperProportions.height,
                                    h = i.offset.left,
                                    u = i.offset.top,
                                    c = h + i.proportions().width,
                                    d = u + i.proportions().height;
                           switch (s) {
                                    case "fit":
                                             return o >= h && c >= r && a >= u && d >= l;
                                    case "intersect":
                                             return o + e.helperProportions.width / 2 > h && c > r - e.helperProportions.width / 2 && a + e.helperProportions.height / 2 > u && d > l - e.helperProportions.height / 2;
                                    case "pointer":
                                             return t(n.pageY, u, i.proportions().height) && t(n.pageX, h, i.proportions().width);
                                    case "touch":
                                             return ((a >= u && d >= a) || (l >= u && d >= l) || (u > a && l > d)) && ((o >= h && c >= o) || (r >= h && c >= r) || (h > o && r > c));
                                    default:
                                             return !1;
                           }
                  };
         })());
         (t.ui.ddmanager = {
                  current: null,
                  droppables: { default: [] },
                  prepareOffsets: function (e, i) {
                           var s,
                                    n,
                                    o = t.ui.ddmanager.droppables[e.options.scope] || [],
                                    a = i ? i.type : null,
                                    r = (e.currentItem || e.element).find(":data(ui-droppable)").addBack();
                           t: for (s = 0; o.length > s; s++)
                                    if (!(o[s].options.disabled || (e && !o[s].accept.call(o[s].element[0], e.currentItem || e.element)))) {
                                             for (n = 0; r.length > n; n++)
                                                      if (r[n] === o[s].element[0]) {
                                                               o[s].proportions().height = 0;
                                                               continue t;
                                                      }
                                             (o[s].visible = "none" !== o[s].element.css("display")),
                                                      o[s].visible &&
                                                               ("mousedown" === a && o[s]._activate.call(o[s], i),
                                                               (o[s].offset = o[s].element.offset()),
                                                               o[s].proportions({ width: o[s].element[0].offsetWidth, height: o[s].element[0].offsetHeight }));
                                    }
                  },
                  drop: function (e, i) {
                           var s = !1;
                           return (
                                    t.each((t.ui.ddmanager.droppables[e.options.scope] || []).slice(), function () {
                                             this.options &&
                                                      (!this.options.disabled && this.visible && o(e, this, this.options.tolerance, i) && (s = this._drop.call(this, i) || s),
                                                      !this.options.disabled && this.visible && this.accept.call(this.element[0], e.currentItem || e.element) && ((this.isout = !0), (this.isover = !1), this._deactivate.call(this, i)));
                                    }),
                                    s
                           );
                  },
                  dragStart: function (e, i) {
                           e.element.parentsUntil("body").on("scroll.droppable", function () {
                                    e.options.refreshPositions || t.ui.ddmanager.prepareOffsets(e, i);
                           });
                  },
                  drag: function (e, i) {
                           e.options.refreshPositions && t.ui.ddmanager.prepareOffsets(e, i),
                                    t.each(t.ui.ddmanager.droppables[e.options.scope] || [], function () {
                                             if (!this.options.disabled && !this.greedyChild && this.visible) {
                                                      var s,
                                                               n,
                                                               a,
                                                               r = o(e, this, this.options.tolerance, i),
                                                               l = !r && this.isover ? "isout" : r && !this.isover ? "isover" : null;
                                                      l &&
                                                               (this.options.greedy &&
                                                                        ((n = this.options.scope),
                                                                        (a = this.element.parents(":data(ui-droppable)").filter(function () {
                                                                                 return t(this).droppable("instance").options.scope === n;
                                                                        })),
                                                                        a.length && ((s = t(a[0]).droppable("instance")), (s.greedyChild = "isover" === l))),
                                                               s && "isover" === l && ((s.isover = !1), (s.isout = !0), s._out.call(s, i)),
                                                               (this[l] = !0),
                                                               (this["isout" === l ? "isover" : "isout"] = !1),
                                                               this["isover" === l ? "_over" : "_out"].call(this, i),
                                                               s && "isout" === l && ((s.isout = !1), (s.isover = !0), s._over.call(s, i)));
                                             }
                                    });
                  },
                  dragStop: function (e, i) {
                           e.element.parentsUntil("body").off("scroll.droppable"), e.options.refreshPositions || t.ui.ddmanager.prepareOffsets(e, i);
                  },
         }),
                  t.uiBackCompat !== !1 &&
                           t.widget("ui.droppable", t.ui.droppable, {
                                    options: { hoverClass: !1, activeClass: !1 },
                                    _addActiveClass: function () {
                                             this._super(), this.options.activeClass && this.element.addClass(this.options.activeClass);
                                    },
                                    _removeActiveClass: function () {
                                             this._super(), this.options.activeClass && this.element.removeClass(this.options.activeClass);
                                    },
                                    _addHoverClass: function () {
                                             this._super(), this.options.hoverClass && this.element.addClass(this.options.hoverClass);
                                    },
                                    _removeHoverClass: function () {
                                             this._super(), this.options.hoverClass && this.element.removeClass(this.options.hoverClass);
                                    },
                           }),
                  t.ui.droppable,
                  t.widget("ui.resizable", t.ui.mouse, {
                           version: "1.12.1",
                           widgetEventPrefix: "resize",
                           options: {
                                    alsoResize: !1,
                                    animate: !1,
                                    animateDuration: "slow",
                                    animateEasing: "swing",
                                    aspectRatio: !1,
                                    autoHide: !1,
                                    classes: { "ui-resizable-se": "ui-icon ui-icon-gripsmall-diagonal-se" },
                                    containment: !1,
                                    ghost: !1,
                                    grid: !1,
                                    handles: "e,s,se",
                                    helper: !1,
                                    maxHeight: null,
                                    maxWidth: null,
                                    minHeight: 10,
                                    minWidth: 10,
                                    zIndex: 90,
                                    resize: null,
                                    start: null,
                                    stop: null,
                           },
                           _num: function (t) {
                                    return parseFloat(t) || 0;
                           },
                           _isNumber: function (t) {
                                    return !isNaN(parseFloat(t));
                           },
                           _hasScroll: function (e, i) {
                                    if ("hidden" === t(e).css("overflow")) return !1;
                                    var s = i && "left" === i ? "scrollLeft" : "scrollTop",
                                             n = !1;
                                    return e[s] > 0 ? !0 : ((e[s] = 1), (n = e[s] > 0), (e[s] = 0), n);
                           },
                           _create: function () {
                                    var e,
                                             i = this.options,
                                             s = this;
                                    this._addClass("ui-resizable"),
                                             t.extend(this, {
                                                      _aspectRatio: !!i.aspectRatio,
                                                      aspectRatio: i.aspectRatio,
                                                      originalElement: this.element,
                                                      _proportionallyResizeElements: [],
                                                      _helper: i.helper || i.ghost || i.animate ? i.helper || "ui-resizable-helper" : null,
                                             }),
                                             this.element[0].nodeName.match(/^(canvas|textarea|input|select|button|img)$/i) &&
                                                      (this.element.wrap(
                                                               t("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({
                                                                        position: this.element.css("position"),
                                                                        width: this.element.outerWidth(),
                                                                        height: this.element.outerHeight(),
                                                                        top: this.element.css("top"),
                                                                        left: this.element.css("left"),
                                                               })
                                                      ),
                                                      (this.element = this.element.parent().data("ui-resizable", this.element.resizable("instance"))),
                                                      (this.elementIsWrapper = !0),
                                                      (e = {
                                                               marginTop: this.originalElement.css("marginTop"),
                                                               marginRight: this.originalElement.css("marginRight"),
                                                               marginBottom: this.originalElement.css("marginBottom"),
                                                               marginLeft: this.originalElement.css("marginLeft"),
                                                      }),
                                                      this.element.css(e),
                                                      this.originalElement.css("margin", 0),
                                                      (this.originalResizeStyle = this.originalElement.css("resize")),
                                                      this.originalElement.css("resize", "none"),
                                                      this._proportionallyResizeElements.push(this.originalElement.css({ position: "static", zoom: 1, display: "block" })),
                                                      this.originalElement.css(e),
                                                      this._proportionallyResize()),
                                             this._setupHandles(),
                                             i.autoHide &&
                                                      t(this.element)
                                                               .on("mouseenter", function () {
                                                                        i.disabled || (s._removeClass("ui-resizable-autohide"), s._handles.show());
                                                               })
                                                               .on("mouseleave", function () {
                                                                        i.disabled || s.resizing || (s._addClass("ui-resizable-autohide"), s._handles.hide());
                                                               }),
                                             this._mouseInit();
                           },
                           _destroy: function () {
                                    this._mouseDestroy();
                                    var e,
                                             i = function (e) {
                                                      t(e).removeData("resizable").removeData("ui-resizable").off(".resizable").find(".ui-resizable-handle").remove();
                                             };
                                    return (
                                             this.elementIsWrapper &&
                                                      (i(this.element),
                                                      (e = this.element),
                                                      this.originalElement.css({ position: e.css("position"), width: e.outerWidth(), height: e.outerHeight(), top: e.css("top"), left: e.css("left") }).insertAfter(e),
                                                      e.remove()),
                                             this.originalElement.css("resize", this.originalResizeStyle),
                                             i(this.originalElement),
                                             this
                                    );
                           },
                           _setOption: function (t, e) {
                                    switch ((this._super(t, e), t)) {
                                             case "handles":
                                                      this._removeHandles(), this._setupHandles();
                                                      break;
                                             default:
                                    }
                           },
                           _setupHandles: function () {
                                    var e,
                                             i,
                                             s,
                                             n,
                                             o,
                                             a = this.options,
                                             r = this;
                                    if (
                                             ((this.handles =
                                                      a.handles ||
                                                      (t(".ui-resizable-handle", this.element).length
                                                               ? {
                                                                          n: ".ui-resizable-n",
                                                                          e: ".ui-resizable-e",
                                                                          s: ".ui-resizable-s",
                                                                          w: ".ui-resizable-w",
                                                                          se: ".ui-resizable-se",
                                                                          sw: ".ui-resizable-sw",
                                                                          ne: ".ui-resizable-ne",
                                                                          nw: ".ui-resizable-nw",
                                                                 }
                                                               : "e,s,se")),
                                             (this._handles = t()),
                                             this.handles.constructor === String)
                                    )
                                             for ("all" === this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw"), s = this.handles.split(","), this.handles = {}, i = 0; s.length > i; i++)
                                                      (e = t.trim(s[i])),
                                                               (n = "ui-resizable-" + e),
                                                               (o = t("<div>")),
                                                               this._addClass(o, "ui-resizable-handle " + n),
                                                               o.css({ zIndex: a.zIndex }),
                                                               (this.handles[e] = ".ui-resizable-" + e),
                                                               this.element.append(o);
                                    (this._renderAxis = function (e) {
                                             var i, s, n, o;
                                             e = e || this.element;
                                             for (i in this.handles)
                                                      this.handles[i].constructor === String
                                                               ? (this.handles[i] = this.element.children(this.handles[i]).first().show())
                                                               : (this.handles[i].jquery || this.handles[i].nodeType) && ((this.handles[i] = t(this.handles[i])), this._on(this.handles[i], { mousedown: r._mouseDown })),
                                                               this.elementIsWrapper &&
                                                                        this.originalElement[0].nodeName.match(/^(textarea|input|select|button)$/i) &&
                                                                        ((s = t(this.handles[i], this.element)),
                                                                        (o = /sw|ne|nw|se|n|s/.test(i) ? s.outerHeight() : s.outerWidth()),
                                                                        (n = ["padding", /ne|nw|n/.test(i) ? "Top" : /se|sw|s/.test(i) ? "Bottom" : /^e$/.test(i) ? "Right" : "Left"].join("")),
                                                                        e.css(n, o),
                                                                        this._proportionallyResize()),
                                                               (this._handles = this._handles.add(this.handles[i]));
                                    }),
                                             this._renderAxis(this.element),
                                             (this._handles = this._handles.add(this.element.find(".ui-resizable-handle"))),
                                             this._handles.disableSelection(),
                                             this._handles.on("mouseover", function () {
                                                      r.resizing || (this.className && (o = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)), (r.axis = o && o[1] ? o[1] : "se"));
                                             }),
                                             a.autoHide && (this._handles.hide(), this._addClass("ui-resizable-autohide"));
                           },
                           _removeHandles: function () {
                                    this._handles.remove();
                           },
                           _mouseCapture: function (e) {
                                    var i,
                                             s,
                                             n = !1;
                                    for (i in this.handles) (s = t(this.handles[i])[0]), (s === e.target || t.contains(s, e.target)) && (n = !0);
                                    return !this.options.disabled && n;
                           },
                           _mouseStart: function (e) {
                                    var i,
                                             s,
                                             n,
                                             o = this.options,
                                             a = this.element;
                                    return (
                                             (this.resizing = !0),
                                             this._renderProxy(),
                                             (i = this._num(this.helper.css("left"))),
                                             (s = this._num(this.helper.css("top"))),
                                             o.containment && ((i += t(o.containment).scrollLeft() || 0), (s += t(o.containment).scrollTop() || 0)),
                                             (this.offset = this.helper.offset()),
                                             (this.position = { left: i, top: s }),
                                             (this.size = this._helper ? { width: this.helper.width(), height: this.helper.height() } : { width: a.width(), height: a.height() }),
                                             (this.originalSize = this._helper ? { width: a.outerWidth(), height: a.outerHeight() } : { width: a.width(), height: a.height() }),
                                             (this.sizeDiff = { width: a.outerWidth() - a.width(), height: a.outerHeight() - a.height() }),
                                             (this.originalPosition = { left: i, top: s }),
                                             (this.originalMousePosition = { left: e.pageX, top: e.pageY }),
                                             (this.aspectRatio = "number" == typeof o.aspectRatio ? o.aspectRatio : this.originalSize.width / this.originalSize.height || 1),
                                             (n = t(".ui-resizable-" + this.axis).css("cursor")),
                                             t("body").css("cursor", "auto" === n ? this.axis + "-resize" : n),
                                             this._addClass("ui-resizable-resizing"),
                                             this._propagate("start", e),
                                             !0
                                    );
                           },
                           _mouseDrag: function (e) {
                                    var i,
                                             s,
                                             n = this.originalMousePosition,
                                             o = this.axis,
                                             a = e.pageX - n.left || 0,
                                             r = e.pageY - n.top || 0,
                                             l = this._change[o];
                                    return (
                                             this._updatePrevProperties(),
                                             l
                                                      ? ((i = l.apply(this, [e, a, r])),
                                                        this._updateVirtualBoundaries(e.shiftKey),
                                                        (this._aspectRatio || e.shiftKey) && (i = this._updateRatio(i, e)),
                                                        (i = this._respectSize(i, e)),
                                                        this._updateCache(i),
                                                        this._propagate("resize", e),
                                                        (s = this._applyChanges()),
                                                        !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(),
                                                        t.isEmptyObject(s) || (this._updatePrevProperties(), this._trigger("resize", e, this.ui()), this._applyChanges()),
                                                        !1)
                                                      : !1
                                    );
                           },
                           _mouseStop: function (e) {
                                    this.resizing = !1;
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a,
                                             r,
                                             l,
                                             h = this.options,
                                             u = this;
                                    return (
                                             this._helper &&
                                                      ((i = this._proportionallyResizeElements),
                                                      (s = i.length && /textarea/i.test(i[0].nodeName)),
                                                      (n = s && this._hasScroll(i[0], "left") ? 0 : u.sizeDiff.height),
                                                      (o = s ? 0 : u.sizeDiff.width),
                                                      (a = { width: u.helper.width() - o, height: u.helper.height() - n }),
                                                      (r = parseFloat(u.element.css("left")) + (u.position.left - u.originalPosition.left) || null),
                                                      (l = parseFloat(u.element.css("top")) + (u.position.top - u.originalPosition.top) || null),
                                                      h.animate || this.element.css(t.extend(a, { top: l, left: r })),
                                                      u.helper.height(u.size.height),
                                                      u.helper.width(u.size.width),
                                                      this._helper && !h.animate && this._proportionallyResize()),
                                             t("body").css("cursor", "auto"),
                                             this._removeClass("ui-resizable-resizing"),
                                             this._propagate("stop", e),
                                             this._helper && this.helper.remove(),
                                             !1
                                    );
                           },
                           _updatePrevProperties: function () {
                                    (this.prevPosition = { top: this.position.top, left: this.position.left }), (this.prevSize = { width: this.size.width, height: this.size.height });
                           },
                           _applyChanges: function () {
                                    var t = {};
                                    return (
                                             this.position.top !== this.prevPosition.top && (t.top = this.position.top + "px"),
                                             this.position.left !== this.prevPosition.left && (t.left = this.position.left + "px"),
                                             this.size.width !== this.prevSize.width && (t.width = this.size.width + "px"),
                                             this.size.height !== this.prevSize.height && (t.height = this.size.height + "px"),
                                             this.helper.css(t),
                                             t
                                    );
                           },
                           _updateVirtualBoundaries: function (t) {
                                    var e,
                                             i,
                                             s,
                                             n,
                                             o,
                                             a = this.options;
                                    (o = {
                                             minWidth: this._isNumber(a.minWidth) ? a.minWidth : 0,
                                             maxWidth: this._isNumber(a.maxWidth) ? a.maxWidth : 1 / 0,
                                             minHeight: this._isNumber(a.minHeight) ? a.minHeight : 0,
                                             maxHeight: this._isNumber(a.maxHeight) ? a.maxHeight : 1 / 0,
                                    }),
                                             (this._aspectRatio || t) &&
                                                      ((e = o.minHeight * this.aspectRatio),
                                                      (s = o.minWidth / this.aspectRatio),
                                                      (i = o.maxHeight * this.aspectRatio),
                                                      (n = o.maxWidth / this.aspectRatio),
                                                      e > o.minWidth && (o.minWidth = e),
                                                      s > o.minHeight && (o.minHeight = s),
                                                      o.maxWidth > i && (o.maxWidth = i),
                                                      o.maxHeight > n && (o.maxHeight = n)),
                                             (this._vBoundaries = o);
                           },
                           _updateCache: function (t) {
                                    (this.offset = this.helper.offset()),
                                             this._isNumber(t.left) && (this.position.left = t.left),
                                             this._isNumber(t.top) && (this.position.top = t.top),
                                             this._isNumber(t.height) && (this.size.height = t.height),
                                             this._isNumber(t.width) && (this.size.width = t.width);
                           },
                           _updateRatio: function (t) {
                                    var e = this.position,
                                             i = this.size,
                                             s = this.axis;
                                    return (
                                             this._isNumber(t.height) ? (t.width = t.height * this.aspectRatio) : this._isNumber(t.width) && (t.height = t.width / this.aspectRatio),
                                             "sw" === s && ((t.left = e.left + (i.width - t.width)), (t.top = null)),
                                             "nw" === s && ((t.top = e.top + (i.height - t.height)), (t.left = e.left + (i.width - t.width))),
                                             t
                                    );
                           },
                           _respectSize: function (t) {
                                    var e = this._vBoundaries,
                                             i = this.axis,
                                             s = this._isNumber(t.width) && e.maxWidth && e.maxWidth < t.width,
                                             n = this._isNumber(t.height) && e.maxHeight && e.maxHeight < t.height,
                                             o = this._isNumber(t.width) && e.minWidth && e.minWidth > t.width,
                                             a = this._isNumber(t.height) && e.minHeight && e.minHeight > t.height,
                                             r = this.originalPosition.left + this.originalSize.width,
                                             l = this.originalPosition.top + this.originalSize.height,
                                             h = /sw|nw|w/.test(i),
                                             u = /nw|ne|n/.test(i);
                                    return (
                                             o && (t.width = e.minWidth),
                                             a && (t.height = e.minHeight),
                                             s && (t.width = e.maxWidth),
                                             n && (t.height = e.maxHeight),
                                             o && h && (t.left = r - e.minWidth),
                                             s && h && (t.left = r - e.maxWidth),
                                             a && u && (t.top = l - e.minHeight),
                                             n && u && (t.top = l - e.maxHeight),
                                             t.width || t.height || t.left || !t.top ? t.width || t.height || t.top || !t.left || (t.left = null) : (t.top = null),
                                             t
                                    );
                           },
                           _getPaddingPlusBorderDimensions: function (t) {
                                    for (
                                             var e = 0,
                                                      i = [],
                                                      s = [t.css("borderTopWidth"), t.css("borderRightWidth"), t.css("borderBottomWidth"), t.css("borderLeftWidth")],
                                                      n = [t.css("paddingTop"), t.css("paddingRight"), t.css("paddingBottom"), t.css("paddingLeft")];
                                             4 > e;
                                             e++
                                    )
                                             (i[e] = parseFloat(s[e]) || 0), (i[e] += parseFloat(n[e]) || 0);
                                    return { height: i[0] + i[2], width: i[1] + i[3] };
                           },
                           _proportionallyResize: function () {
                                    if (this._proportionallyResizeElements.length)
                                             for (var t, e = 0, i = this.helper || this.element; this._proportionallyResizeElements.length > e; e++)
                                                      (t = this._proportionallyResizeElements[e]),
                                                               this.outerDimensions || (this.outerDimensions = this._getPaddingPlusBorderDimensions(t)),
                                                               t.css({ height: i.height() - this.outerDimensions.height || 0, width: i.width() - this.outerDimensions.width || 0 });
                           },
                           _renderProxy: function () {
                                    var e = this.element,
                                             i = this.options;
                                    (this.elementOffset = e.offset()),
                                             this._helper
                                                      ? ((this.helper = this.helper || t("<div style='overflow:hidden;'></div>")),
                                                        this._addClass(this.helper, this._helper),
                                                        this.helper.css({
                                                                 width: this.element.outerWidth(),
                                                                 height: this.element.outerHeight(),
                                                                 position: "absolute",
                                                                 left: this.elementOffset.left + "px",
                                                                 top: this.elementOffset.top + "px",
                                                                 zIndex: ++i.zIndex,
                                                        }),
                                                        this.helper.appendTo("body").disableSelection())
                                                      : (this.helper = this.element);
                           },
                           _change: {
                                    e: function (t, e) {
                                             return { width: this.originalSize.width + e };
                                    },
                                    w: function (t, e) {
                                             var i = this.originalSize,
                                                      s = this.originalPosition;
                                             return { left: s.left + e, width: i.width - e };
                                    },
                                    n: function (t, e, i) {
                                             var s = this.originalSize,
                                                      n = this.originalPosition;
                                             return { top: n.top + i, height: s.height - i };
                                    },
                                    s: function (t, e, i) {
                                             return { height: this.originalSize.height + i };
                                    },
                                    se: function (e, i, s) {
                                             return t.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [e, i, s]));
                                    },
                                    sw: function (e, i, s) {
                                             return t.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [e, i, s]));
                                    },
                                    ne: function (e, i, s) {
                                             return t.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [e, i, s]));
                                    },
                                    nw: function (e, i, s) {
                                             return t.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [e, i, s]));
                                    },
                           },
                           _propagate: function (e, i) {
                                    t.ui.plugin.call(this, e, [i, this.ui()]), "resize" !== e && this._trigger(e, i, this.ui());
                           },
                           plugins: {},
                           ui: function () {
                                    return {
                                             originalElement: this.originalElement,
                                             element: this.element,
                                             helper: this.helper,
                                             position: this.position,
                                             size: this.size,
                                             originalSize: this.originalSize,
                                             originalPosition: this.originalPosition,
                                    };
                           },
                  }),
                  t.ui.plugin.add("resizable", "animate", {
                           stop: function (e) {
                                    var i = t(this).resizable("instance"),
                                             s = i.options,
                                             n = i._proportionallyResizeElements,
                                             o = n.length && /textarea/i.test(n[0].nodeName),
                                             a = o && i._hasScroll(n[0], "left") ? 0 : i.sizeDiff.height,
                                             r = o ? 0 : i.sizeDiff.width,
                                             l = { width: i.size.width - r, height: i.size.height - a },
                                             h = parseFloat(i.element.css("left")) + (i.position.left - i.originalPosition.left) || null,
                                             u = parseFloat(i.element.css("top")) + (i.position.top - i.originalPosition.top) || null;
                                    i.element.animate(t.extend(l, u && h ? { top: u, left: h } : {}), {
                                             duration: s.animateDuration,
                                             easing: s.animateEasing,
                                             step: function () {
                                                      var s = { width: parseFloat(i.element.css("width")), height: parseFloat(i.element.css("height")), top: parseFloat(i.element.css("top")), left: parseFloat(i.element.css("left")) };
                                                      n && n.length && t(n[0]).css({ width: s.width, height: s.height }), i._updateCache(s), i._propagate("resize", e);
                                             },
                                    });
                           },
                  }),
                  t.ui.plugin.add("resizable", "containment", {
                           start: function () {
                                    var e,
                                             i,
                                             s,
                                             n,
                                             o,
                                             a,
                                             r,
                                             l = t(this).resizable("instance"),
                                             h = l.options,
                                             u = l.element,
                                             c = h.containment,
                                             d = c instanceof t ? c.get(0) : /parent/.test(c) ? u.parent().get(0) : c;
                                    d &&
                                             ((l.containerElement = t(d)),
                                             /document/.test(c) || c === document
                                                      ? ((l.containerOffset = { left: 0, top: 0 }),
                                                        (l.containerPosition = { left: 0, top: 0 }),
                                                        (l.parentData = { element: t(document), left: 0, top: 0, width: t(document).width(), height: t(document).height() || document.body.parentNode.scrollHeight }))
                                                      : ((e = t(d)),
                                                        (i = []),
                                                        t(["Top", "Right", "Left", "Bottom"]).each(function (t, s) {
                                                                 i[t] = l._num(e.css("padding" + s));
                                                        }),
                                                        (l.containerOffset = e.offset()),
                                                        (l.containerPosition = e.position()),
                                                        (l.containerSize = { height: e.innerHeight() - i[3], width: e.innerWidth() - i[1] }),
                                                        (s = l.containerOffset),
                                                        (n = l.containerSize.height),
                                                        (o = l.containerSize.width),
                                                        (a = l._hasScroll(d, "left") ? d.scrollWidth : o),
                                                        (r = l._hasScroll(d) ? d.scrollHeight : n),
                                                        (l.parentData = { element: d, left: s.left, top: s.top, width: a, height: r })));
                           },
                           resize: function (e) {
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a = t(this).resizable("instance"),
                                             r = a.options,
                                             l = a.containerOffset,
                                             h = a.position,
                                             u = a._aspectRatio || e.shiftKey,
                                             c = { top: 0, left: 0 },
                                             d = a.containerElement,
                                             p = !0;
                                    d[0] !== document && /static/.test(d.css("position")) && (c = l),
                                             h.left < (a._helper ? l.left : 0) &&
                                                      ((a.size.width = a.size.width + (a._helper ? a.position.left - l.left : a.position.left - c.left)),
                                                      u && ((a.size.height = a.size.width / a.aspectRatio), (p = !1)),
                                                      (a.position.left = r.helper ? l.left : 0)),
                                             h.top < (a._helper ? l.top : 0) &&
                                                      ((a.size.height = a.size.height + (a._helper ? a.position.top - l.top : a.position.top)),
                                                      u && ((a.size.width = a.size.height * a.aspectRatio), (p = !1)),
                                                      (a.position.top = a._helper ? l.top : 0)),
                                             (n = a.containerElement.get(0) === a.element.parent().get(0)),
                                             (o = /relative|absolute/.test(a.containerElement.css("position"))),
                                             n && o
                                                      ? ((a.offset.left = a.parentData.left + a.position.left), (a.offset.top = a.parentData.top + a.position.top))
                                                      : ((a.offset.left = a.element.offset().left), (a.offset.top = a.element.offset().top)),
                                             (i = Math.abs(a.sizeDiff.width + (a._helper ? a.offset.left - c.left : a.offset.left - l.left))),
                                             (s = Math.abs(a.sizeDiff.height + (a._helper ? a.offset.top - c.top : a.offset.top - l.top))),
                                             i + a.size.width >= a.parentData.width && ((a.size.width = a.parentData.width - i), u && ((a.size.height = a.size.width / a.aspectRatio), (p = !1))),
                                             s + a.size.height >= a.parentData.height && ((a.size.height = a.parentData.height - s), u && ((a.size.width = a.size.height * a.aspectRatio), (p = !1))),
                                             p || ((a.position.left = a.prevPosition.left), (a.position.top = a.prevPosition.top), (a.size.width = a.prevSize.width), (a.size.height = a.prevSize.height));
                           },
                           stop: function () {
                                    var e = t(this).resizable("instance"),
                                             i = e.options,
                                             s = e.containerOffset,
                                             n = e.containerPosition,
                                             o = e.containerElement,
                                             a = t(e.helper),
                                             r = a.offset(),
                                             l = a.outerWidth() - e.sizeDiff.width,
                                             h = a.outerHeight() - e.sizeDiff.height;
                                    e._helper && !i.animate && /relative/.test(o.css("position")) && t(this).css({ left: r.left - n.left - s.left, width: l, height: h }),
                                             e._helper && !i.animate && /static/.test(o.css("position")) && t(this).css({ left: r.left - n.left - s.left, width: l, height: h });
                           },
                  }),
                  t.ui.plugin.add("resizable", "alsoResize", {
                           start: function () {
                                    var e = t(this).resizable("instance"),
                                             i = e.options;
                                    t(i.alsoResize).each(function () {
                                             var e = t(this);
                                             e.data("ui-resizable-alsoresize", { width: parseFloat(e.width()), height: parseFloat(e.height()), left: parseFloat(e.css("left")), top: parseFloat(e.css("top")) });
                                    });
                           },
                           resize: function (e, i) {
                                    var s = t(this).resizable("instance"),
                                             n = s.options,
                                             o = s.originalSize,
                                             a = s.originalPosition,
                                             r = { height: s.size.height - o.height || 0, width: s.size.width - o.width || 0, top: s.position.top - a.top || 0, left: s.position.left - a.left || 0 };
                                    t(n.alsoResize).each(function () {
                                             var e = t(this),
                                                      s = t(this).data("ui-resizable-alsoresize"),
                                                      n = {},
                                                      o = e.parents(i.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                                             t.each(o, function (t, e) {
                                                      var i = (s[e] || 0) + (r[e] || 0);
                                                      i && i >= 0 && (n[e] = i || null);
                                             }),
                                                      e.css(n);
                                    });
                           },
                           stop: function () {
                                    t(this).removeData("ui-resizable-alsoresize");
                           },
                  }),
                  t.ui.plugin.add("resizable", "ghost", {
                           start: function () {
                                    var e = t(this).resizable("instance"),
                                             i = e.size;
                                    (e.ghost = e.originalElement.clone()),
                                             e.ghost.css({ opacity: 0.25, display: "block", position: "relative", height: i.height, width: i.width, margin: 0, left: 0, top: 0 }),
                                             e._addClass(e.ghost, "ui-resizable-ghost"),
                                             t.uiBackCompat !== !1 && "string" == typeof e.options.ghost && e.ghost.addClass(this.options.ghost),
                                             e.ghost.appendTo(e.helper);
                           },
                           resize: function () {
                                    var e = t(this).resizable("instance");
                                    e.ghost && e.ghost.css({ position: "relative", height: e.size.height, width: e.size.width });
                           },
                           stop: function () {
                                    var e = t(this).resizable("instance");
                                    e.ghost && e.helper && e.helper.get(0).removeChild(e.ghost.get(0));
                           },
                  }),
                  t.ui.plugin.add("resizable", "grid", {
                           resize: function () {
                                    var e,
                                             i = t(this).resizable("instance"),
                                             s = i.options,
                                             n = i.size,
                                             o = i.originalSize,
                                             a = i.originalPosition,
                                             r = i.axis,
                                             l = "number" == typeof s.grid ? [s.grid, s.grid] : s.grid,
                                             h = l[0] || 1,
                                             u = l[1] || 1,
                                             c = Math.round((n.width - o.width) / h) * h,
                                             d = Math.round((n.height - o.height) / u) * u,
                                             p = o.width + c,
                                             f = o.height + d,
                                             m = s.maxWidth && p > s.maxWidth,
                                             g = s.maxHeight && f > s.maxHeight,
                                             _ = s.minWidth && s.minWidth > p,
                                             v = s.minHeight && s.minHeight > f;
                                    (s.grid = l),
                                             _ && (p += h),
                                             v && (f += u),
                                             m && (p -= h),
                                             g && (f -= u),
                                             /^(se|s|e)$/.test(r)
                                                      ? ((i.size.width = p), (i.size.height = f))
                                                      : /^(ne)$/.test(r)
                                                      ? ((i.size.width = p), (i.size.height = f), (i.position.top = a.top - d))
                                                      : /^(sw)$/.test(r)
                                                      ? ((i.size.width = p), (i.size.height = f), (i.position.left = a.left - c))
                                                      : ((0 >= f - u || 0 >= p - h) && (e = i._getPaddingPlusBorderDimensions(this)),
                                                        f - u > 0 ? ((i.size.height = f), (i.position.top = a.top - d)) : ((f = u - e.height), (i.size.height = f), (i.position.top = a.top + o.height - f)),
                                                        p - h > 0 ? ((i.size.width = p), (i.position.left = a.left - c)) : ((p = h - e.width), (i.size.width = p), (i.position.left = a.left + o.width - p)));
                           },
                  }),
                  t.ui.resizable,
                  t.widget("ui.selectable", t.ui.mouse, {
                           version: "1.12.1",
                           options: { appendTo: "body", autoRefresh: !0, distance: 0, filter: "*", tolerance: "touch", selected: null, selecting: null, start: null, stop: null, unselected: null, unselecting: null },
                           _create: function () {
                                    var e = this;
                                    this._addClass("ui-selectable"),
                                             (this.dragged = !1),
                                             (this.refresh = function () {
                                                      (e.elementPos = t(e.element[0]).offset()),
                                                               (e.selectees = t(e.options.filter, e.element[0])),
                                                               e._addClass(e.selectees, "ui-selectee"),
                                                               e.selectees.each(function () {
                                                                        var i = t(this),
                                                                                 s = i.offset(),
                                                                                 n = { left: s.left - e.elementPos.left, top: s.top - e.elementPos.top };
                                                                        t.data(this, "selectable-item", {
                                                                                 element: this,
                                                                                 $element: i,
                                                                                 left: n.left,
                                                                                 top: n.top,
                                                                                 right: n.left + i.outerWidth(),
                                                                                 bottom: n.top + i.outerHeight(),
                                                                                 startselected: !1,
                                                                                 selected: i.hasClass("ui-selected"),
                                                                                 selecting: i.hasClass("ui-selecting"),
                                                                                 unselecting: i.hasClass("ui-unselecting"),
                                                                        });
                                                               });
                                             }),
                                             this.refresh(),
                                             this._mouseInit(),
                                             (this.helper = t("<div>")),
                                             this._addClass(this.helper, "ui-selectable-helper");
                           },
                           _destroy: function () {
                                    this.selectees.removeData("selectable-item"), this._mouseDestroy();
                           },
                           _mouseStart: function (e) {
                                    var i = this,
                                             s = this.options;
                                    (this.opos = [e.pageX, e.pageY]),
                                             (this.elementPos = t(this.element[0]).offset()),
                                             this.options.disabled ||
                                                      ((this.selectees = t(s.filter, this.element[0])),
                                                      this._trigger("start", e),
                                                      t(s.appendTo).append(this.helper),
                                                      this.helper.css({ left: e.pageX, top: e.pageY, width: 0, height: 0 }),
                                                      s.autoRefresh && this.refresh(),
                                                      this.selectees.filter(".ui-selected").each(function () {
                                                               var s = t.data(this, "selectable-item");
                                                               (s.startselected = !0),
                                                                        e.metaKey ||
                                                                                 e.ctrlKey ||
                                                                                 (i._removeClass(s.$element, "ui-selected"),
                                                                                 (s.selected = !1),
                                                                                 i._addClass(s.$element, "ui-unselecting"),
                                                                                 (s.unselecting = !0),
                                                                                 i._trigger("unselecting", e, { unselecting: s.element }));
                                                      }),
                                                      t(e.target)
                                                               .parents()
                                                               .addBack()
                                                               .each(function () {
                                                                        var s,
                                                                                 n = t.data(this, "selectable-item");
                                                                        return n
                                                                                 ? ((s = (!e.metaKey && !e.ctrlKey) || !n.$element.hasClass("ui-selected")),
                                                                                   i._removeClass(n.$element, s ? "ui-unselecting" : "ui-selected")._addClass(n.$element, s ? "ui-selecting" : "ui-unselecting"),
                                                                                   (n.unselecting = !s),
                                                                                   (n.selecting = s),
                                                                                   (n.selected = s),
                                                                                   s ? i._trigger("selecting", e, { selecting: n.element }) : i._trigger("unselecting", e, { unselecting: n.element }),
                                                                                   !1)
                                                                                 : void 0;
                                                               }));
                           },
                           _mouseDrag: function (e) {
                                    if (((this.dragged = !0), !this.options.disabled)) {
                                             var i,
                                                      s = this,
                                                      n = this.options,
                                                      o = this.opos[0],
                                                      a = this.opos[1],
                                                      r = e.pageX,
                                                      l = e.pageY;
                                             return (
                                                      o > r && ((i = r), (r = o), (o = i)),
                                                      a > l && ((i = l), (l = a), (a = i)),
                                                      this.helper.css({ left: o, top: a, width: r - o, height: l - a }),
                                                      this.selectees.each(function () {
                                                               var i = t.data(this, "selectable-item"),
                                                                        h = !1,
                                                                        u = {};
                                                               i &&
                                                                        i.element !== s.element[0] &&
                                                                        ((u.left = i.left + s.elementPos.left),
                                                                        (u.right = i.right + s.elementPos.left),
                                                                        (u.top = i.top + s.elementPos.top),
                                                                        (u.bottom = i.bottom + s.elementPos.top),
                                                                        "touch" === n.tolerance
                                                                                 ? (h = !(u.left > r || o > u.right || u.top > l || a > u.bottom))
                                                                                 : "fit" === n.tolerance && (h = u.left > o && r > u.right && u.top > a && l > u.bottom),
                                                                        h
                                                                                 ? (i.selected && (s._removeClass(i.$element, "ui-selected"), (i.selected = !1)),
                                                                                   i.unselecting && (s._removeClass(i.$element, "ui-unselecting"), (i.unselecting = !1)),
                                                                                   i.selecting || (s._addClass(i.$element, "ui-selecting"), (i.selecting = !0), s._trigger("selecting", e, { selecting: i.element })))
                                                                                 : (i.selecting &&
                                                                                            ((e.metaKey || e.ctrlKey) && i.startselected
                                                                                                     ? (s._removeClass(i.$element, "ui-selecting"), (i.selecting = !1), s._addClass(i.$element, "ui-selected"), (i.selected = !0))
                                                                                                     : (s._removeClass(i.$element, "ui-selecting"),
                                                                                                       (i.selecting = !1),
                                                                                                       i.startselected && (s._addClass(i.$element, "ui-unselecting"), (i.unselecting = !0)),
                                                                                                       s._trigger("unselecting", e, { unselecting: i.element }))),
                                                                                   i.selected &&
                                                                                            (e.metaKey ||
                                                                                                     e.ctrlKey ||
                                                                                                     i.startselected ||
                                                                                                     (s._removeClass(i.$element, "ui-selected"),
                                                                                                     (i.selected = !1),
                                                                                                     s._addClass(i.$element, "ui-unselecting"),
                                                                                                     (i.unselecting = !0),
                                                                                                     s._trigger("unselecting", e, { unselecting: i.element })))));
                                                      }),
                                                      !1
                                             );
                                    }
                           },
                           _mouseStop: function (e) {
                                    var i = this;
                                    return (
                                             (this.dragged = !1),
                                             t(".ui-unselecting", this.element[0]).each(function () {
                                                      var s = t.data(this, "selectable-item");
                                                      i._removeClass(s.$element, "ui-unselecting"), (s.unselecting = !1), (s.startselected = !1), i._trigger("unselected", e, { unselected: s.element });
                                             }),
                                             t(".ui-selecting", this.element[0]).each(function () {
                                                      var s = t.data(this, "selectable-item");
                                                      i._removeClass(s.$element, "ui-selecting")._addClass(s.$element, "ui-selected"),
                                                               (s.selecting = !1),
                                                               (s.selected = !0),
                                                               (s.startselected = !0),
                                                               i._trigger("selected", e, { selected: s.element });
                                             }),
                                             this._trigger("stop", e),
                                             this.helper.remove(),
                                             !1
                                    );
                           },
                  }),
                  t.widget("ui.sortable", t.ui.mouse, {
                           version: "1.12.1",
                           widgetEventPrefix: "sort",
                           ready: !1,
                           options: {
                                    appendTo: "parent",
                                    axis: !1,
                                    connectWith: !1,
                                    containment: !1,
                                    cursor: "auto",
                                    cursorAt: !1,
                                    dropOnEmpty: !0,
                                    forcePlaceholderSize: !1,
                                    forceHelperSize: !1,
                                    grid: !1,
                                    handle: !1,
                                    helper: "original",
                                    items: "> *",
                                    opacity: !1,
                                    placeholder: !1,
                                    revert: !1,
                                    scroll: !0,
                                    scrollSensitivity: 20,
                                    scrollSpeed: 20,
                                    scope: "default",
                                    tolerance: "intersect",
                                    zIndex: 1e3,
                                    activate: null,
                                    beforeStop: null,
                                    change: null,
                                    deactivate: null,
                                    out: null,
                                    over: null,
                                    receive: null,
                                    remove: null,
                                    sort: null,
                                    start: null,
                                    stop: null,
                                    update: null,
                           },
                           _isOverAxis: function (t, e, i) {
                                    return t >= e && e + i > t;
                           },
                           _isFloating: function (t) {
                                    return /left|right/.test(t.css("float")) || /inline|table-cell/.test(t.css("display"));
                           },
                           _create: function () {
                                    (this.containerCache = {}), this._addClass("ui-sortable"), this.refresh(), (this.offset = this.element.offset()), this._mouseInit(), this._setHandleClassName(), (this.ready = !0);
                           },
                           _setOption: function (t, e) {
                                    this._super(t, e), "handle" === t && this._setHandleClassName();
                           },
                           _setHandleClassName: function () {
                                    var e = this;
                                    this._removeClass(this.element.find(".ui-sortable-handle"), "ui-sortable-handle"),
                                             t.each(this.items, function () {
                                                      e._addClass(this.instance.options.handle ? this.item.find(this.instance.options.handle) : this.item, "ui-sortable-handle");
                                             });
                           },
                           _destroy: function () {
                                    this._mouseDestroy();
                                    for (var t = this.items.length - 1; t >= 0; t--) this.items[t].item.removeData(this.widgetName + "-item");
                                    return this;
                           },
                           _mouseCapture: function (e, i) {
                                    var s = null,
                                             n = !1,
                                             o = this;
                                    return this.reverting
                                             ? !1
                                             : this.options.disabled || "static" === this.options.type
                                             ? !1
                                             : (this._refreshItems(e),
                                               t(e.target)
                                                        .parents()
                                                        .each(function () {
                                                                 return t.data(this, o.widgetName + "-item") === o ? ((s = t(this)), !1) : void 0;
                                                        }),
                                               t.data(e.target, o.widgetName + "-item") === o && (s = t(e.target)),
                                               s
                                                        ? !this.options.handle ||
                                                          i ||
                                                          (t(this.options.handle, s)
                                                                   .find("*")
                                                                   .addBack()
                                                                   .each(function () {
                                                                            this === e.target && (n = !0);
                                                                   }),
                                                          n)
                                                                 ? ((this.currentItem = s), this._removeCurrentsFromItems(), !0)
                                                                 : !1
                                                        : !1);
                           },
                           _mouseStart: function (e, i, s) {
                                    var n,
                                             o,
                                             a = this.options;
                                    if (
                                             ((this.currentContainer = this),
                                             this.refreshPositions(),
                                             (this.helper = this._createHelper(e)),
                                             this._cacheHelperProportions(),
                                             this._cacheMargins(),
                                             (this.scrollParent = this.helper.scrollParent()),
                                             (this.offset = this.currentItem.offset()),
                                             (this.offset = { top: this.offset.top - this.margins.top, left: this.offset.left - this.margins.left }),
                                             t.extend(this.offset, { click: { left: e.pageX - this.offset.left, top: e.pageY - this.offset.top }, parent: this._getParentOffset(), relative: this._getRelativeOffset() }),
                                             this.helper.css("position", "absolute"),
                                             (this.cssPosition = this.helper.css("position")),
                                             (this.originalPosition = this._generatePosition(e)),
                                             (this.originalPageX = e.pageX),
                                             (this.originalPageY = e.pageY),
                                             a.cursorAt && this._adjustOffsetFromHelper(a.cursorAt),
                                             (this.domPosition = { prev: this.currentItem.prev()[0], parent: this.currentItem.parent()[0] }),
                                             this.helper[0] !== this.currentItem[0] && this.currentItem.hide(),
                                             this._createPlaceholder(),
                                             a.containment && this._setContainment(),
                                             a.cursor &&
                                                      "auto" !== a.cursor &&
                                                      ((o = this.document.find("body")),
                                                      (this.storedCursor = o.css("cursor")),
                                                      o.css("cursor", a.cursor),
                                                      (this.storedStylesheet = t("<style>*{ cursor: " + a.cursor + " !important; }</style>").appendTo(o))),
                                             a.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", a.opacity)),
                                             a.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", a.zIndex)),
                                             this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()),
                                             this._trigger("start", e, this._uiHash()),
                                             this._preserveHelperProportions || this._cacheHelperProportions(),
                                             !s)
                                    )
                                             for (n = this.containers.length - 1; n >= 0; n--) this.containers[n]._trigger("activate", e, this._uiHash(this));
                                    return (
                                             t.ui.ddmanager && (t.ui.ddmanager.current = this),
                                             t.ui.ddmanager && !a.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e),
                                             (this.dragging = !0),
                                             this._addClass(this.helper, "ui-sortable-helper"),
                                             this._mouseDrag(e),
                                             !0
                                    );
                           },
                           _mouseDrag: function (e) {
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a = this.options,
                                             r = !1;
                                    for (
                                             this.position = this._generatePosition(e),
                                                      this.positionAbs = this._convertPositionTo("absolute"),
                                                      this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs),
                                                      this.options.scroll &&
                                                               (this.scrollParent[0] !== this.document[0] && "HTML" !== this.scrollParent[0].tagName
                                                                        ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - e.pageY < a.scrollSensitivity
                                                                                   ? (this.scrollParent[0].scrollTop = r = this.scrollParent[0].scrollTop + a.scrollSpeed)
                                                                                   : e.pageY - this.overflowOffset.top < a.scrollSensitivity && (this.scrollParent[0].scrollTop = r = this.scrollParent[0].scrollTop - a.scrollSpeed),
                                                                          this.overflowOffset.left + this.scrollParent[0].offsetWidth - e.pageX < a.scrollSensitivity
                                                                                   ? (this.scrollParent[0].scrollLeft = r = this.scrollParent[0].scrollLeft + a.scrollSpeed)
                                                                                   : e.pageX - this.overflowOffset.left < a.scrollSensitivity && (this.scrollParent[0].scrollLeft = r = this.scrollParent[0].scrollLeft - a.scrollSpeed))
                                                                        : (e.pageY - this.document.scrollTop() < a.scrollSensitivity
                                                                                   ? (r = this.document.scrollTop(this.document.scrollTop() - a.scrollSpeed))
                                                                                   : this.window.height() - (e.pageY - this.document.scrollTop()) < a.scrollSensitivity &&
                                                                                     (r = this.document.scrollTop(this.document.scrollTop() + a.scrollSpeed)),
                                                                          e.pageX - this.document.scrollLeft() < a.scrollSensitivity
                                                                                   ? (r = this.document.scrollLeft(this.document.scrollLeft() - a.scrollSpeed))
                                                                                   : this.window.width() - (e.pageX - this.document.scrollLeft()) < a.scrollSensitivity &&
                                                                                     (r = this.document.scrollLeft(this.document.scrollLeft() + a.scrollSpeed))),
                                                               r !== !1 && t.ui.ddmanager && !a.dropBehaviour && t.ui.ddmanager.prepareOffsets(this, e)),
                                                      this.positionAbs = this._convertPositionTo("absolute"),
                                                      (this.options.axis && "y" === this.options.axis) || (this.helper[0].style.left = this.position.left + "px"),
                                                      (this.options.axis && "x" === this.options.axis) || (this.helper[0].style.top = this.position.top + "px"),
                                                      i = this.items.length - 1;
                                             i >= 0;
                                             i--
                                    )
                                             if (
                                                      ((s = this.items[i]),
                                                      (n = s.item[0]),
                                                      (o = this._intersectsWithPointer(s)),
                                                      o &&
                                                               s.instance === this.currentContainer &&
                                                               n !== this.currentItem[0] &&
                                                               this.placeholder[1 === o ? "next" : "prev"]()[0] !== n &&
                                                               !t.contains(this.placeholder[0], n) &&
                                                               ("semi-dynamic" === this.options.type ? !t.contains(this.element[0], n) : !0))
                                             ) {
                                                      if (((this.direction = 1 === o ? "down" : "up"), "pointer" !== this.options.tolerance && !this._intersectsWithSides(s))) break;
                                                      this._rearrange(e, s), this._trigger("change", e, this._uiHash());
                                                      break;
                                             }
                                    return this._contactContainers(e), t.ui.ddmanager && t.ui.ddmanager.drag(this, e), this._trigger("sort", e, this._uiHash()), (this.lastPositionAbs = this.positionAbs), !1;
                           },
                           _mouseStop: function (e, i) {
                                    if (e) {
                                             if ((t.ui.ddmanager && !this.options.dropBehaviour && t.ui.ddmanager.drop(this, e), this.options.revert)) {
                                                      var s = this,
                                                               n = this.placeholder.offset(),
                                                               o = this.options.axis,
                                                               a = {};
                                                      (o && "x" !== o) || (a.left = n.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollLeft)),
                                                               (o && "y" !== o) || (a.top = n.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === this.document[0].body ? 0 : this.offsetParent[0].scrollTop)),
                                                               (this.reverting = !0),
                                                               t(this.helper).animate(a, parseInt(this.options.revert, 10) || 500, function () {
                                                                        s._clear(e);
                                                               });
                                             } else this._clear(e, i);
                                             return !1;
                                    }
                           },
                           cancel: function () {
                                    if (this.dragging) {
                                             this._mouseUp(new t.Event("mouseup", { target: null })),
                                                      "original" === this.options.helper ? (this.currentItem.css(this._storedCSS), this._removeClass(this.currentItem, "ui-sortable-helper")) : this.currentItem.show();
                                             for (var e = this.containers.length - 1; e >= 0; e--)
                                                      this.containers[e]._trigger("deactivate", null, this._uiHash(this)),
                                                               this.containers[e].containerCache.over && (this.containers[e]._trigger("out", null, this._uiHash(this)), (this.containers[e].containerCache.over = 0));
                                    }
                                    return (
                                             this.placeholder &&
                                                      (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]),
                                                      "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(),
                                                      t.extend(this, { helper: null, dragging: !1, reverting: !1, _noFinalSort: null }),
                                                      this.domPosition.prev ? t(this.domPosition.prev).after(this.currentItem) : t(this.domPosition.parent).prepend(this.currentItem)),
                                             this
                                    );
                           },
                           serialize: function (e) {
                                    var i = this._getItemsAsjQuery(e && e.connected),
                                             s = [];
                                    return (
                                             (e = e || {}),
                                             t(i).each(function () {
                                                      var i = (t(e.item || this).attr(e.attribute || "id") || "").match(e.expression || /(.+)[\-=_](.+)/);
                                                      i && s.push((e.key || i[1] + "[]") + "=" + (e.key && e.expression ? i[1] : i[2]));
                                             }),
                                             !s.length && e.key && s.push(e.key + "="),
                                             s.join("&")
                                    );
                           },
                           toArray: function (e) {
                                    var i = this._getItemsAsjQuery(e && e.connected),
                                             s = [];
                                    return (
                                             (e = e || {}),
                                             i.each(function () {
                                                      s.push(t(e.item || this).attr(e.attribute || "id") || "");
                                             }),
                                             s
                                    );
                           },
                           _intersectsWith: function (t) {
                                    var e = this.positionAbs.left,
                                             i = e + this.helperProportions.width,
                                             s = this.positionAbs.top,
                                             n = s + this.helperProportions.height,
                                             o = t.left,
                                             a = o + t.width,
                                             r = t.top,
                                             l = r + t.height,
                                             h = this.offset.click.top,
                                             u = this.offset.click.left,
                                             c = "x" === this.options.axis || (s + h > r && l > s + h),
                                             d = "y" === this.options.axis || (e + u > o && a > e + u),
                                             p = c && d;
                                    return "pointer" === this.options.tolerance ||
                                             this.options.forcePointerForContainers ||
                                             ("pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > t[this.floating ? "width" : "height"])
                                             ? p
                                             : e + this.helperProportions.width / 2 > o && a > i - this.helperProportions.width / 2 && s + this.helperProportions.height / 2 > r && l > n - this.helperProportions.height / 2;
                           },
                           _intersectsWithPointer: function (t) {
                                    var e,
                                             i,
                                             s = "x" === this.options.axis || this._isOverAxis(this.positionAbs.top + this.offset.click.top, t.top, t.height),
                                             n = "y" === this.options.axis || this._isOverAxis(this.positionAbs.left + this.offset.click.left, t.left, t.width),
                                             o = s && n;
                                    return o ? ((e = this._getDragVerticalDirection()), (i = this._getDragHorizontalDirection()), this.floating ? ("right" === i || "down" === e ? 2 : 1) : e && ("down" === e ? 2 : 1)) : !1;
                           },
                           _intersectsWithSides: function (t) {
                                    var e = this._isOverAxis(this.positionAbs.top + this.offset.click.top, t.top + t.height / 2, t.height),
                                             i = this._isOverAxis(this.positionAbs.left + this.offset.click.left, t.left + t.width / 2, t.width),
                                             s = this._getDragVerticalDirection(),
                                             n = this._getDragHorizontalDirection();
                                    return this.floating && n ? ("right" === n && i) || ("left" === n && !i) : s && (("down" === s && e) || ("up" === s && !e));
                           },
                           _getDragVerticalDirection: function () {
                                    var t = this.positionAbs.top - this.lastPositionAbs.top;
                                    return 0 !== t && (t > 0 ? "down" : "up");
                           },
                           _getDragHorizontalDirection: function () {
                                    var t = this.positionAbs.left - this.lastPositionAbs.left;
                                    return 0 !== t && (t > 0 ? "right" : "left");
                           },
                           refresh: function (t) {
                                    return this._refreshItems(t), this._setHandleClassName(), this.refreshPositions(), this;
                           },
                           _connectWith: function () {
                                    var t = this.options;
                                    return t.connectWith.constructor === String ? [t.connectWith] : t.connectWith;
                           },
                           _getItemsAsjQuery: function (e) {
                                    function i() {
                                             r.push(this);
                                    }
                                    var s,
                                             n,
                                             o,
                                             a,
                                             r = [],
                                             l = [],
                                             h = this._connectWith();
                                    if (h && e)
                                             for (s = h.length - 1; s >= 0; s--)
                                                      for (o = t(h[s], this.document[0]), n = o.length - 1; n >= 0; n--)
                                                               (a = t.data(o[n], this.widgetFullName)),
                                                                        a &&
                                                                                 a !== this &&
                                                                                 !a.options.disabled &&
                                                                                 l.push([
                                                                                          t.isFunction(a.options.items)
                                                                                                   ? a.options.items.call(a.element)
                                                                                                   : t(a.options.items, a.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),
                                                                                          a,
                                                                                 ]);
                                    for (
                                             l.push([
                                                      t.isFunction(this.options.items)
                                                               ? this.options.items.call(this.element, null, { options: this.options, item: this.currentItem })
                                                               : t(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"),
                                                      this,
                                             ]),
                                                      s = l.length - 1;
                                             s >= 0;
                                             s--
                                    )
                                             l[s][0].each(i);
                                    return t(r);
                           },
                           _removeCurrentsFromItems: function () {
                                    var e = this.currentItem.find(":data(" + this.widgetName + "-item)");
                                    this.items = t.grep(this.items, function (t) {
                                             for (var i = 0; e.length > i; i++) if (e[i] === t.item[0]) return !1;
                                             return !0;
                                    });
                           },
                           _refreshItems: function (e) {
                                    (this.items = []), (this.containers = [this]);
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a,
                                             r,
                                             l,
                                             h,
                                             u = this.items,
                                             c = [[t.isFunction(this.options.items) ? this.options.items.call(this.element[0], e, { item: this.currentItem }) : t(this.options.items, this.element), this]],
                                             d = this._connectWith();
                                    if (d && this.ready)
                                             for (i = d.length - 1; i >= 0; i--)
                                                      for (n = t(d[i], this.document[0]), s = n.length - 1; s >= 0; s--)
                                                               (o = t.data(n[s], this.widgetFullName)),
                                                                        o &&
                                                                                 o !== this &&
                                                                                 !o.options.disabled &&
                                                                                 (c.push([t.isFunction(o.options.items) ? o.options.items.call(o.element[0], e, { item: this.currentItem }) : t(o.options.items, o.element), o]),
                                                                                 this.containers.push(o));
                                    for (i = c.length - 1; i >= 0; i--)
                                             for (a = c[i][1], r = c[i][0], s = 0, h = r.length; h > s; s++) (l = t(r[s])), l.data(this.widgetName + "-item", a), u.push({ item: l, instance: a, width: 0, height: 0, left: 0, top: 0 });
                           },
                           refreshPositions: function (e) {
                                    (this.floating = this.items.length ? "x" === this.options.axis || this._isFloating(this.items[0].item) : !1), this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
                                    var i, s, n, o;
                                    for (i = this.items.length - 1; i >= 0; i--)
                                             (s = this.items[i]),
                                                      (s.instance !== this.currentContainer && this.currentContainer && s.item[0] !== this.currentItem[0]) ||
                                                               ((n = this.options.toleranceElement ? t(this.options.toleranceElement, s.item) : s.item),
                                                               e || ((s.width = n.outerWidth()), (s.height = n.outerHeight())),
                                                               (o = n.offset()),
                                                               (s.left = o.left),
                                                               (s.top = o.top));
                                    if (this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this);
                                    else
                                             for (i = this.containers.length - 1; i >= 0; i--)
                                                      (o = this.containers[i].element.offset()),
                                                               (this.containers[i].containerCache.left = o.left),
                                                               (this.containers[i].containerCache.top = o.top),
                                                               (this.containers[i].containerCache.width = this.containers[i].element.outerWidth()),
                                                               (this.containers[i].containerCache.height = this.containers[i].element.outerHeight());
                                    return this;
                           },
                           _createPlaceholder: function (e) {
                                    e = e || this;
                                    var i,
                                             s = e.options;
                                    (s.placeholder && s.placeholder.constructor !== String) ||
                                             ((i = s.placeholder),
                                             (s.placeholder = {
                                                      element: function () {
                                                               var s = e.currentItem[0].nodeName.toLowerCase(),
                                                                        n = t("<" + s + ">", e.document[0]);
                                                               return (
                                                                        e._addClass(n, "ui-sortable-placeholder", i || e.currentItem[0].className)._removeClass(n, "ui-sortable-helper"),
                                                                        "tbody" === s
                                                                                 ? e._createTrPlaceholder(e.currentItem.find("tr").eq(0), t("<tr>", e.document[0]).appendTo(n))
                                                                                 : "tr" === s
                                                                                 ? e._createTrPlaceholder(e.currentItem, n)
                                                                                 : "img" === s && n.attr("src", e.currentItem.attr("src")),
                                                                        i || n.css("visibility", "hidden"),
                                                                        n
                                                               );
                                                      },
                                                      update: function (t, n) {
                                                               (!i || s.forcePlaceholderSize) &&
                                                                        (n.height() || n.height(e.currentItem.innerHeight() - parseInt(e.currentItem.css("paddingTop") || 0, 10) - parseInt(e.currentItem.css("paddingBottom") || 0, 10)),
                                                                        n.width() || n.width(e.currentItem.innerWidth() - parseInt(e.currentItem.css("paddingLeft") || 0, 10) - parseInt(e.currentItem.css("paddingRight") || 0, 10)));
                                                      },
                                             })),
                                             (e.placeholder = t(s.placeholder.element.call(e.element, e.currentItem))),
                                             e.currentItem.after(e.placeholder),
                                             s.placeholder.update(e, e.placeholder);
                           },
                           _createTrPlaceholder: function (e, i) {
                                    var s = this;
                                    e.children().each(function () {
                                             t("<td>&#160;</td>", s.document[0])
                                                      .attr("colspan", t(this).attr("colspan") || 1)
                                                      .appendTo(i);
                                    });
                           },
                           _contactContainers: function (e) {
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a,
                                             r,
                                             l,
                                             h,
                                             u,
                                             c,
                                             d = null,
                                             p = null;
                                    for (i = this.containers.length - 1; i >= 0; i--)
                                             if (!t.contains(this.currentItem[0], this.containers[i].element[0]))
                                                      if (this._intersectsWith(this.containers[i].containerCache)) {
                                                               if (d && t.contains(this.containers[i].element[0], d.element[0])) continue;
                                                               (d = this.containers[i]), (p = i);
                                                      } else this.containers[i].containerCache.over && (this.containers[i]._trigger("out", e, this._uiHash(this)), (this.containers[i].containerCache.over = 0));
                                    if (d)
                                             if (1 === this.containers.length) this.containers[p].containerCache.over || (this.containers[p]._trigger("over", e, this._uiHash(this)), (this.containers[p].containerCache.over = 1));
                                             else {
                                                      for (
                                                               n = 1e4,
                                                                        o = null,
                                                                        u = d.floating || this._isFloating(this.currentItem),
                                                                        a = u ? "left" : "top",
                                                                        r = u ? "width" : "height",
                                                                        c = u ? "pageX" : "pageY",
                                                                        s = this.items.length - 1;
                                                               s >= 0;
                                                               s--
                                                      )
                                                               t.contains(this.containers[p].element[0], this.items[s].item[0]) &&
                                                                        this.items[s].item[0] !== this.currentItem[0] &&
                                                                        ((l = this.items[s].item.offset()[a]),
                                                                        (h = !1),
                                                                        e[c] - l > this.items[s][r] / 2 && (h = !0),
                                                                        n > Math.abs(e[c] - l) && ((n = Math.abs(e[c] - l)), (o = this.items[s]), (this.direction = h ? "up" : "down")));
                                                      if (!o && !this.options.dropOnEmpty) return;
                                                      if (this.currentContainer === this.containers[p])
                                                               return this.currentContainer.containerCache.over || (this.containers[p]._trigger("over", e, this._uiHash()), (this.currentContainer.containerCache.over = 1)), void 0;
                                                      o ? this._rearrange(e, o, null, !0) : this._rearrange(e, null, this.containers[p].element, !0),
                                                               this._trigger("change", e, this._uiHash()),
                                                               this.containers[p]._trigger("change", e, this._uiHash(this)),
                                                               (this.currentContainer = this.containers[p]),
                                                               this.options.placeholder.update(this.currentContainer, this.placeholder),
                                                               this.containers[p]._trigger("over", e, this._uiHash(this)),
                                                               (this.containers[p].containerCache.over = 1);
                                             }
                           },
                           _createHelper: function (e) {
                                    var i = this.options,
                                             s = t.isFunction(i.helper) ? t(i.helper.apply(this.element[0], [e, this.currentItem])) : "clone" === i.helper ? this.currentItem.clone() : this.currentItem;
                                    return (
                                             s.parents("body").length || t("parent" !== i.appendTo ? i.appendTo : this.currentItem[0].parentNode)[0].appendChild(s[0]),
                                             s[0] === this.currentItem[0] &&
                                                      (this._storedCSS = {
                                                               width: this.currentItem[0].style.width,
                                                               height: this.currentItem[0].style.height,
                                                               position: this.currentItem.css("position"),
                                                               top: this.currentItem.css("top"),
                                                               left: this.currentItem.css("left"),
                                                      }),
                                             (!s[0].style.width || i.forceHelperSize) && s.width(this.currentItem.width()),
                                             (!s[0].style.height || i.forceHelperSize) && s.height(this.currentItem.height()),
                                             s
                                    );
                           },
                           _adjustOffsetFromHelper: function (e) {
                                    "string" == typeof e && (e = e.split(" ")),
                                             t.isArray(e) && (e = { left: +e[0], top: +e[1] || 0 }),
                                             "left" in e && (this.offset.click.left = e.left + this.margins.left),
                                             "right" in e && (this.offset.click.left = this.helperProportions.width - e.right + this.margins.left),
                                             "top" in e && (this.offset.click.top = e.top + this.margins.top),
                                             "bottom" in e && (this.offset.click.top = this.helperProportions.height - e.bottom + this.margins.top);
                           },
                           _getParentOffset: function () {
                                    this.offsetParent = this.helper.offsetParent();
                                    var e = this.offsetParent.offset();
                                    return (
                                             "absolute" === this.cssPosition &&
                                                      this.scrollParent[0] !== this.document[0] &&
                                                      t.contains(this.scrollParent[0], this.offsetParent[0]) &&
                                                      ((e.left += this.scrollParent.scrollLeft()), (e.top += this.scrollParent.scrollTop())),
                                             (this.offsetParent[0] === this.document[0].body || (this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && t.ui.ie)) && (e = { top: 0, left: 0 }),
                                             { top: e.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0), left: e.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0) }
                                    );
                           },
                           _getRelativeOffset: function () {
                                    if ("relative" === this.cssPosition) {
                                             var t = this.currentItem.position();
                                             return { top: t.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(), left: t.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft() };
                                    }
                                    return { top: 0, left: 0 };
                           },
                           _cacheMargins: function () {
                                    this.margins = { left: parseInt(this.currentItem.css("marginLeft"), 10) || 0, top: parseInt(this.currentItem.css("marginTop"), 10) || 0 };
                           },
                           _cacheHelperProportions: function () {
                                    this.helperProportions = { width: this.helper.outerWidth(), height: this.helper.outerHeight() };
                           },
                           _setContainment: function () {
                                    var e,
                                             i,
                                             s,
                                             n = this.options;
                                    "parent" === n.containment && (n.containment = this.helper[0].parentNode),
                                             ("document" === n.containment || "window" === n.containment) &&
                                                      (this.containment = [
                                                               0 - this.offset.relative.left - this.offset.parent.left,
                                                               0 - this.offset.relative.top - this.offset.parent.top,
                                                               "document" === n.containment ? this.document.width() : this.window.width() - this.helperProportions.width - this.margins.left,
                                                               ("document" === n.containment ? this.document.height() || document.body.parentNode.scrollHeight : this.window.height() || this.document[0].body.parentNode.scrollHeight) -
                                                                        this.helperProportions.height -
                                                                        this.margins.top,
                                                      ]),
                                             /^(document|window|parent)$/.test(n.containment) ||
                                                      ((e = t(n.containment)[0]),
                                                      (i = t(n.containment).offset()),
                                                      (s = "hidden" !== t(e).css("overflow")),
                                                      (this.containment = [
                                                               i.left + (parseInt(t(e).css("borderLeftWidth"), 10) || 0) + (parseInt(t(e).css("paddingLeft"), 10) || 0) - this.margins.left,
                                                               i.top + (parseInt(t(e).css("borderTopWidth"), 10) || 0) + (parseInt(t(e).css("paddingTop"), 10) || 0) - this.margins.top,
                                                               i.left +
                                                                        (s ? Math.max(e.scrollWidth, e.offsetWidth) : e.offsetWidth) -
                                                                        (parseInt(t(e).css("borderLeftWidth"), 10) || 0) -
                                                                        (parseInt(t(e).css("paddingRight"), 10) || 0) -
                                                                        this.helperProportions.width -
                                                                        this.margins.left,
                                                               i.top +
                                                                        (s ? Math.max(e.scrollHeight, e.offsetHeight) : e.offsetHeight) -
                                                                        (parseInt(t(e).css("borderTopWidth"), 10) || 0) -
                                                                        (parseInt(t(e).css("paddingBottom"), 10) || 0) -
                                                                        this.helperProportions.height -
                                                                        this.margins.top,
                                                      ]));
                           },
                           _convertPositionTo: function (e, i) {
                                    i || (i = this.position);
                                    var s = "absolute" === e ? 1 : -1,
                                             n = "absolute" !== this.cssPosition || (this.scrollParent[0] !== this.document[0] && t.contains(this.scrollParent[0], this.offsetParent[0])) ? this.scrollParent : this.offsetParent,
                                             o = /(html|body)/i.test(n[0].tagName);
                                    return {
                                             top: i.top + this.offset.relative.top * s + this.offset.parent.top * s - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : o ? 0 : n.scrollTop()) * s,
                                             left: i.left + this.offset.relative.left * s + this.offset.parent.left * s - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : o ? 0 : n.scrollLeft()) * s,
                                    };
                           },
                           _generatePosition: function (e) {
                                    var i,
                                             s,
                                             n = this.options,
                                             o = e.pageX,
                                             a = e.pageY,
                                             r = "absolute" !== this.cssPosition || (this.scrollParent[0] !== this.document[0] && t.contains(this.scrollParent[0], this.offsetParent[0])) ? this.scrollParent : this.offsetParent,
                                             l = /(html|body)/i.test(r[0].tagName);
                                    return (
                                             "relative" !== this.cssPosition || (this.scrollParent[0] !== this.document[0] && this.scrollParent[0] !== this.offsetParent[0]) || (this.offset.relative = this._getRelativeOffset()),
                                             this.originalPosition &&
                                                      (this.containment &&
                                                               (e.pageX - this.offset.click.left < this.containment[0] && (o = this.containment[0] + this.offset.click.left),
                                                               e.pageY - this.offset.click.top < this.containment[1] && (a = this.containment[1] + this.offset.click.top),
                                                               e.pageX - this.offset.click.left > this.containment[2] && (o = this.containment[2] + this.offset.click.left),
                                                               e.pageY - this.offset.click.top > this.containment[3] && (a = this.containment[3] + this.offset.click.top)),
                                                      n.grid &&
                                                               ((i = this.originalPageY + Math.round((a - this.originalPageY) / n.grid[1]) * n.grid[1]),
                                                               (a = this.containment
                                                                        ? i - this.offset.click.top >= this.containment[1] && i - this.offset.click.top <= this.containment[3]
                                                                                 ? i
                                                                                 : i - this.offset.click.top >= this.containment[1]
                                                                                 ? i - n.grid[1]
                                                                                 : i + n.grid[1]
                                                                        : i),
                                                               (s = this.originalPageX + Math.round((o - this.originalPageX) / n.grid[0]) * n.grid[0]),
                                                               (o = this.containment
                                                                        ? s - this.offset.click.left >= this.containment[0] && s - this.offset.click.left <= this.containment[2]
                                                                                 ? s
                                                                                 : s - this.offset.click.left >= this.containment[0]
                                                                                 ? s - n.grid[0]
                                                                                 : s + n.grid[0]
                                                                        : s))),
                                             {
                                                      top: a - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : l ? 0 : r.scrollTop()),
                                                      left: o - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : l ? 0 : r.scrollLeft()),
                                             }
                                    );
                           },
                           _rearrange: function (t, e, i, s) {
                                    i ? i[0].appendChild(this.placeholder[0]) : e.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? e.item[0] : e.item[0].nextSibling),
                                             (this.counter = this.counter ? ++this.counter : 1);
                                    var n = this.counter;
                                    this._delay(function () {
                                             n === this.counter && this.refreshPositions(!s);
                                    });
                           },
                           _clear: function (t, e) {
                                    function i(t, e, i) {
                                             return function (s) {
                                                      i._trigger(t, s, e._uiHash(e));
                                             };
                                    }
                                    this.reverting = !1;
                                    var s,
                                             n = [];
                                    if ((!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), (this._noFinalSort = null), this.helper[0] === this.currentItem[0])) {
                                             for (s in this._storedCSS) ("auto" === this._storedCSS[s] || "static" === this._storedCSS[s]) && (this._storedCSS[s] = "");
                                             this.currentItem.css(this._storedCSS), this._removeClass(this.currentItem, "ui-sortable-helper");
                                    } else this.currentItem.show();
                                    for (
                                             this.fromOutside &&
                                                      !e &&
                                                      n.push(function (t) {
                                                               this._trigger("receive", t, this._uiHash(this.fromOutside));
                                                      }),
                                                      (!this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0]) ||
                                                               e ||
                                                               n.push(function (t) {
                                                                        this._trigger("update", t, this._uiHash());
                                                               }),
                                                      this !== this.currentContainer &&
                                                               (e ||
                                                                        (n.push(function (t) {
                                                                                 this._trigger("remove", t, this._uiHash());
                                                                        }),
                                                                        n.push(
                                                                                 function (t) {
                                                                                          return function (e) {
                                                                                                   t._trigger("receive", e, this._uiHash(this));
                                                                                          };
                                                                                 }.call(this, this.currentContainer)
                                                                        ),
                                                                        n.push(
                                                                                 function (t) {
                                                                                          return function (e) {
                                                                                                   t._trigger("update", e, this._uiHash(this));
                                                                                          };
                                                                                 }.call(this, this.currentContainer)
                                                                        ))),
                                                      s = this.containers.length - 1;
                                             s >= 0;
                                             s--
                                    )
                                             e || n.push(i("deactivate", this, this.containers[s])), this.containers[s].containerCache.over && (n.push(i("out", this, this.containers[s])), (this.containers[s].containerCache.over = 0));
                                    if (
                                             (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()),
                                             this._storedOpacity && this.helper.css("opacity", this._storedOpacity),
                                             this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "" : this._storedZIndex),
                                             (this.dragging = !1),
                                             e || this._trigger("beforeStop", t, this._uiHash()),
                                             this.placeholder[0].parentNode.removeChild(this.placeholder[0]),
                                             this.cancelHelperRemoval || (this.helper[0] !== this.currentItem[0] && this.helper.remove(), (this.helper = null)),
                                             !e)
                                    ) {
                                             for (s = 0; n.length > s; s++) n[s].call(this, t);
                                             this._trigger("stop", t, this._uiHash());
                                    }
                                    return (this.fromOutside = !1), !this.cancelHelperRemoval;
                           },
                           _trigger: function () {
                                    t.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel();
                           },
                           _uiHash: function (e) {
                                    var i = e || this;
                                    return { helper: i.helper, placeholder: i.placeholder || t([]), position: i.position, originalPosition: i.originalPosition, offset: i.positionAbs, item: i.currentItem, sender: e ? e.element : null };
                           },
                  }),
                  t.widget("ui.accordion", {
                           version: "1.12.1",
                           options: {
                                    active: 0,
                                    animate: {},
                                    classes: { "ui-accordion-header": "ui-corner-top", "ui-accordion-header-collapsed": "ui-corner-all", "ui-accordion-content": "ui-corner-bottom" },
                                    collapsible: !1,
                                    event: "click",
                                    header: "> li > :first-child, > :not(li):even",
                                    heightStyle: "auto",
                                    icons: { activeHeader: "ui-icon-triangle-1-s", header: "ui-icon-triangle-1-e" },
                                    activate: null,
                                    beforeActivate: null,
                           },
                           hideProps: { borderTopWidth: "hide", borderBottomWidth: "hide", paddingTop: "hide", paddingBottom: "hide", height: "hide" },
                           showProps: { borderTopWidth: "show", borderBottomWidth: "show", paddingTop: "show", paddingBottom: "show", height: "show" },
                           _create: function () {
                                    var e = this.options;
                                    (this.prevShow = this.prevHide = t()),
                                             this._addClass("ui-accordion", "ui-widget ui-helper-reset"),
                                             this.element.attr("role", "tablist"),
                                             e.collapsible || (e.active !== !1 && null != e.active) || (e.active = 0),
                                             this._processPanels(),
                                             0 > e.active && (e.active += this.headers.length),
                                             this._refresh();
                           },
                           _getCreateEventData: function () {
                                    return { header: this.active, panel: this.active.length ? this.active.next() : t() };
                           },
                           _createIcons: function () {
                                    var e,
                                             i,
                                             s = this.options.icons;
                                    s &&
                                             ((e = t("<span>")),
                                             this._addClass(e, "ui-accordion-header-icon", "ui-icon " + s.header),
                                             e.prependTo(this.headers),
                                             (i = this.active.children(".ui-accordion-header-icon")),
                                             this._removeClass(i, s.header)._addClass(i, null, s.activeHeader)._addClass(this.headers, "ui-accordion-icons"));
                           },
                           _destroyIcons: function () {
                                    this._removeClass(this.headers, "ui-accordion-icons"), this.headers.children(".ui-accordion-header-icon").remove();
                           },
                           _destroy: function () {
                                    var t;
                                    this.element.removeAttr("role"),
                                             this.headers.removeAttr("role aria-expanded aria-selected aria-controls tabIndex").removeUniqueId(),
                                             this._destroyIcons(),
                                             (t = this.headers.next().css("display", "").removeAttr("role aria-hidden aria-labelledby").removeUniqueId()),
                                             "content" !== this.options.heightStyle && t.css("height", "");
                           },
                           _setOption: function (t, e) {
                                    return "active" === t
                                             ? (this._activate(e), void 0)
                                             : ("event" === t && (this.options.event && this._off(this.headers, this.options.event), this._setupEvents(e)),
                                               this._super(t, e),
                                               "collapsible" !== t || e || this.options.active !== !1 || this._activate(0),
                                               "icons" === t && (this._destroyIcons(), e && this._createIcons()),
                                               void 0);
                           },
                           _setOptionDisabled: function (t) {
                                    this._super(t), this.element.attr("aria-disabled", t), this._toggleClass(null, "ui-state-disabled", !!t), this._toggleClass(this.headers.add(this.headers.next()), null, "ui-state-disabled", !!t);
                           },
                           _keydown: function (e) {
                                    if (!e.altKey && !e.ctrlKey) {
                                             var i = t.ui.keyCode,
                                                      s = this.headers.length,
                                                      n = this.headers.index(e.target),
                                                      o = !1;
                                             switch (e.keyCode) {
                                                      case i.RIGHT:
                                                      case i.DOWN:
                                                               o = this.headers[(n + 1) % s];
                                                               break;
                                                      case i.LEFT:
                                                      case i.UP:
                                                               o = this.headers[(n - 1 + s) % s];
                                                               break;
                                                      case i.SPACE:
                                                      case i.ENTER:
                                                               this._eventHandler(e);
                                                               break;
                                                      case i.HOME:
                                                               o = this.headers[0];
                                                               break;
                                                      case i.END:
                                                               o = this.headers[s - 1];
                                             }
                                             o && (t(e.target).attr("tabIndex", -1), t(o).attr("tabIndex", 0), t(o).trigger("focus"), e.preventDefault());
                                    }
                           },
                           _panelKeyDown: function (e) {
                                    e.keyCode === t.ui.keyCode.UP && e.ctrlKey && t(e.currentTarget).prev().trigger("focus");
                           },
                           refresh: function () {
                                    var e = this.options;
                                    this._processPanels(),
                                             (e.active === !1 && e.collapsible === !0) || !this.headers.length
                                                      ? ((e.active = !1), (this.active = t()))
                                                      : e.active === !1
                                                      ? this._activate(0)
                                                      : this.active.length && !t.contains(this.element[0], this.active[0])
                                                      ? this.headers.length === this.headers.find(".ui-state-disabled").length
                                                               ? ((e.active = !1), (this.active = t()))
                                                               : this._activate(Math.max(0, e.active - 1))
                                                      : (e.active = this.headers.index(this.active)),
                                             this._destroyIcons(),
                                             this._refresh();
                           },
                           _processPanels: function () {
                                    var t = this.headers,
                                             e = this.panels;
                                    (this.headers = this.element.find(this.options.header)),
                                             this._addClass(this.headers, "ui-accordion-header ui-accordion-header-collapsed", "ui-state-default"),
                                             (this.panels = this.headers.next().filter(":not(.ui-accordion-content-active)").hide()),
                                             this._addClass(this.panels, "ui-accordion-content", "ui-helper-reset ui-widget-content"),
                                             e && (this._off(t.not(this.headers)), this._off(e.not(this.panels)));
                           },
                           _refresh: function () {
                                    var e,
                                             i = this.options,
                                             s = i.heightStyle,
                                             n = this.element.parent();
                                    (this.active = this._findActive(i.active)),
                                             this._addClass(this.active, "ui-accordion-header-active", "ui-state-active")._removeClass(this.active, "ui-accordion-header-collapsed"),
                                             this._addClass(this.active.next(), "ui-accordion-content-active"),
                                             this.active.next().show(),
                                             this.headers
                                                      .attr("role", "tab")
                                                      .each(function () {
                                                               var e = t(this),
                                                                        i = e.uniqueId().attr("id"),
                                                                        s = e.next(),
                                                                        n = s.uniqueId().attr("id");
                                                               e.attr("aria-controls", n), s.attr("aria-labelledby", i);
                                                      })
                                                      .next()
                                                      .attr("role", "tabpanel"),
                                             this.headers.not(this.active).attr({ "aria-selected": "false", "aria-expanded": "false", tabIndex: -1 }).next().attr({ "aria-hidden": "true" }).hide(),
                                             this.active.length ? this.active.attr({ "aria-selected": "true", "aria-expanded": "true", tabIndex: 0 }).next().attr({ "aria-hidden": "false" }) : this.headers.eq(0).attr("tabIndex", 0),
                                             this._createIcons(),
                                             this._setupEvents(i.event),
                                             "fill" === s
                                                      ? ((e = n.height()),
                                                        this.element.siblings(":visible").each(function () {
                                                                 var i = t(this),
                                                                          s = i.css("position");
                                                                 "absolute" !== s && "fixed" !== s && (e -= i.outerHeight(!0));
                                                        }),
                                                        this.headers.each(function () {
                                                                 e -= t(this).outerHeight(!0);
                                                        }),
                                                        this.headers
                                                                 .next()
                                                                 .each(function () {
                                                                          t(this).height(Math.max(0, e - t(this).innerHeight() + t(this).height()));
                                                                 })
                                                                 .css("overflow", "auto"))
                                                      : "auto" === s &&
                                                        ((e = 0),
                                                        this.headers
                                                                 .next()
                                                                 .each(function () {
                                                                          var i = t(this).is(":visible");
                                                                          i || t(this).show(), (e = Math.max(e, t(this).css("height", "").height())), i || t(this).hide();
                                                                 })
                                                                 .height(e));
                           },
                           _activate: function (e) {
                                    var i = this._findActive(e)[0];
                                    i !== this.active[0] && ((i = i || this.active[0]), this._eventHandler({ target: i, currentTarget: i, preventDefault: t.noop }));
                           },
                           _findActive: function (e) {
                                    return "number" == typeof e ? this.headers.eq(e) : t();
                           },
                           _setupEvents: function (e) {
                                    var i = { keydown: "_keydown" };
                                    e &&
                                             t.each(e.split(" "), function (t, e) {
                                                      i[e] = "_eventHandler";
                                             }),
                                             this._off(this.headers.add(this.headers.next())),
                                             this._on(this.headers, i),
                                             this._on(this.headers.next(), { keydown: "_panelKeyDown" }),
                                             this._hoverable(this.headers),
                                             this._focusable(this.headers);
                           },
                           _eventHandler: function (e) {
                                    var i,
                                             s,
                                             n = this.options,
                                             o = this.active,
                                             a = t(e.currentTarget),
                                             r = a[0] === o[0],
                                             l = r && n.collapsible,
                                             h = l ? t() : a.next(),
                                             u = o.next(),
                                             c = { oldHeader: o, oldPanel: u, newHeader: l ? t() : a, newPanel: h };
                                    e.preventDefault(),
                                             (r && !n.collapsible) ||
                                                      this._trigger("beforeActivate", e, c) === !1 ||
                                                      ((n.active = l ? !1 : this.headers.index(a)),
                                                      (this.active = r ? t() : a),
                                                      this._toggle(c),
                                                      this._removeClass(o, "ui-accordion-header-active", "ui-state-active"),
                                                      n.icons && ((i = o.children(".ui-accordion-header-icon")), this._removeClass(i, null, n.icons.activeHeader)._addClass(i, null, n.icons.header)),
                                                      r ||
                                                               (this._removeClass(a, "ui-accordion-header-collapsed")._addClass(a, "ui-accordion-header-active", "ui-state-active"),
                                                               n.icons && ((s = a.children(".ui-accordion-header-icon")), this._removeClass(s, null, n.icons.header)._addClass(s, null, n.icons.activeHeader)),
                                                               this._addClass(a.next(), "ui-accordion-content-active")));
                           },
                           _toggle: function (e) {
                                    var i = e.newPanel,
                                             s = this.prevShow.length ? this.prevShow : e.oldPanel;
                                    this.prevShow.add(this.prevHide).stop(!0, !0),
                                             (this.prevShow = i),
                                             (this.prevHide = s),
                                             this.options.animate ? this._animate(i, s, e) : (s.hide(), i.show(), this._toggleComplete(e)),
                                             s.attr({ "aria-hidden": "true" }),
                                             s.prev().attr({ "aria-selected": "false", "aria-expanded": "false" }),
                                             i.length && s.length
                                                      ? s.prev().attr({ tabIndex: -1, "aria-expanded": "false" })
                                                      : i.length &&
                                                        this.headers
                                                                 .filter(function () {
                                                                          return 0 === parseInt(t(this).attr("tabIndex"), 10);
                                                                 })
                                                                 .attr("tabIndex", -1),
                                             i.attr("aria-hidden", "false").prev().attr({ "aria-selected": "true", "aria-expanded": "true", tabIndex: 0 });
                           },
                           _animate: function (t, e, i) {
                                    var s,
                                             n,
                                             o,
                                             a = this,
                                             r = 0,
                                             l = t.css("box-sizing"),
                                             h = t.length && (!e.length || t.index() < e.index()),
                                             u = this.options.animate || {},
                                             c = (h && u.down) || u,
                                             d = function () {
                                                      a._toggleComplete(i);
                                             };
                                    return (
                                             "number" == typeof c && (o = c),
                                             "string" == typeof c && (n = c),
                                             (n = n || c.easing || u.easing),
                                             (o = o || c.duration || u.duration),
                                             e.length
                                                      ? t.length
                                                               ? ((s = t.show().outerHeight()),
                                                                 e.animate(this.hideProps, {
                                                                          duration: o,
                                                                          easing: n,
                                                                          step: function (t, e) {
                                                                                   e.now = Math.round(t);
                                                                          },
                                                                 }),
                                                                 t.hide().animate(this.showProps, {
                                                                          duration: o,
                                                                          easing: n,
                                                                          complete: d,
                                                                          step: function (t, i) {
                                                                                   (i.now = Math.round(t)),
                                                                                            "height" !== i.prop
                                                                                                     ? "content-box" === l && (r += i.now)
                                                                                                     : "content" !== a.options.heightStyle && ((i.now = Math.round(s - e.outerHeight() - r)), (r = 0));
                                                                          },
                                                                 }),
                                                                 void 0)
                                                               : e.animate(this.hideProps, o, n, d)
                                                      : t.animate(this.showProps, o, n, d)
                                    );
                           },
                           _toggleComplete: function (t) {
                                    var e = t.oldPanel,
                                             i = e.prev();
                                    this._removeClass(e, "ui-accordion-content-active"),
                                             this._removeClass(i, "ui-accordion-header-active")._addClass(i, "ui-accordion-header-collapsed"),
                                             e.length && (e.parent()[0].className = e.parent()[0].className),
                                             this._trigger("activate", null, t);
                           },
                  });
         var a = /ui-corner-([a-z]){2,6}/g;
         t.widget("ui.controlgroup", {
                  version: "1.12.1",
                  defaultElement: "<div>",
                  options: {
                           direction: "horizontal",
                           disabled: null,
                           onlyVisible: !0,
                           items: {
                                    button: "input[type=button], input[type=submit], input[type=reset], button, a",
                                    controlgroupLabel: ".ui-controlgroup-label",
                                    checkboxradio: "input[type='checkbox'], input[type='radio']",
                                    selectmenu: "select",
                                    spinner: ".ui-spinner-input",
                           },
                  },
                  _create: function () {
                           this._enhance();
                  },
                  _enhance: function () {
                           this.element.attr("role", "toolbar"), this.refresh();
                  },
                  _destroy: function () {
                           this._callChildMethod("destroy"),
                                    this.childWidgets.removeData("ui-controlgroup-data"),
                                    this.element.removeAttr("role"),
                                    this.options.items.controlgroupLabel && this.element.find(this.options.items.controlgroupLabel).find(".ui-controlgroup-label-contents").contents().unwrap();
                  },
                  _initWidgets: function () {
                           var e = this,
                                    i = [];
                           t.each(this.options.items, function (s, n) {
                                    var o,
                                             a = {};
                                    return n
                                             ? "controlgroupLabel" === s
                                                      ? ((o = e.element.find(n)),
                                                        o.each(function () {
                                                                 var e = t(this);
                                                                 e.children(".ui-controlgroup-label-contents").length || e.contents().wrapAll("<span class='ui-controlgroup-label-contents'></span>");
                                                        }),
                                                        e._addClass(o, null, "ui-widget ui-widget-content ui-state-default"),
                                                        (i = i.concat(o.get())),
                                                        void 0)
                                                      : (t.fn[s] &&
                                                                 ((a = e["_" + s + "Options"] ? e["_" + s + "Options"]("middle") : { classes: {} }),
                                                                 e.element.find(n).each(function () {
                                                                          var n = t(this),
                                                                                   o = n[s]("instance"),
                                                                                   r = t.widget.extend({}, a);
                                                                          if ("button" !== s || !n.parent(".ui-spinner").length) {
                                                                                   o || (o = n[s]()[s]("instance")), o && (r.classes = e._resolveClassesValues(r.classes, o)), n[s](r);
                                                                                   var l = n[s]("widget");
                                                                                   t.data(l[0], "ui-controlgroup-data", o ? o : n[s]("instance")), i.push(l[0]);
                                                                          }
                                                                 })),
                                                        void 0)
                                             : void 0;
                           }),
                                    (this.childWidgets = t(t.unique(i))),
                                    this._addClass(this.childWidgets, "ui-controlgroup-item");
                  },
                  _callChildMethod: function (e) {
                           this.childWidgets.each(function () {
                                    var i = t(this),
                                             s = i.data("ui-controlgroup-data");
                                    s && s[e] && s[e]();
                           });
                  },
                  _updateCornerClass: function (t, e) {
                           var i = "ui-corner-top ui-corner-bottom ui-corner-left ui-corner-right ui-corner-all",
                                    s = this._buildSimpleOptions(e, "label").classes.label;
                           this._removeClass(t, null, i), this._addClass(t, null, s);
                  },
                  _buildSimpleOptions: function (t, e) {
                           var i = "vertical" === this.options.direction,
                                    s = { classes: {} };
                           return (s.classes[e] = { middle: "", first: "ui-corner-" + (i ? "top" : "left"), last: "ui-corner-" + (i ? "bottom" : "right"), only: "ui-corner-all" }[t]), s;
                  },
                  _spinnerOptions: function (t) {
                           var e = this._buildSimpleOptions(t, "ui-spinner");
                           return (e.classes["ui-spinner-up"] = ""), (e.classes["ui-spinner-down"] = ""), e;
                  },
                  _buttonOptions: function (t) {
                           return this._buildSimpleOptions(t, "ui-button");
                  },
                  _checkboxradioOptions: function (t) {
                           return this._buildSimpleOptions(t, "ui-checkboxradio-label");
                  },
                  _selectmenuOptions: function (t) {
                           var e = "vertical" === this.options.direction;
                           return {
                                    width: e ? "auto" : !1,
                                    classes: {
                                             middle: { "ui-selectmenu-button-open": "", "ui-selectmenu-button-closed": "" },
                                             first: { "ui-selectmenu-button-open": "ui-corner-" + (e ? "top" : "tl"), "ui-selectmenu-button-closed": "ui-corner-" + (e ? "top" : "left") },
                                             last: { "ui-selectmenu-button-open": e ? "" : "ui-corner-tr", "ui-selectmenu-button-closed": "ui-corner-" + (e ? "bottom" : "right") },
                                             only: { "ui-selectmenu-button-open": "ui-corner-top", "ui-selectmenu-button-closed": "ui-corner-all" },
                                    }[t],
                           };
                  },
                  _resolveClassesValues: function (e, i) {
                           var s = {};
                           return (
                                    t.each(e, function (n) {
                                             var o = i.options.classes[n] || "";
                                             (o = t.trim(o.replace(a, ""))), (s[n] = (o + " " + e[n]).replace(/\s+/g, " "));
                                    }),
                                    s
                           );
                  },
                  _setOption: function (t, e) {
                           return (
                                    "direction" === t && this._removeClass("ui-controlgroup-" + this.options.direction),
                                    this._super(t, e),
                                    "disabled" === t ? (this._callChildMethod(e ? "disable" : "enable"), void 0) : (this.refresh(), void 0)
                           );
                  },
                  refresh: function () {
                           var e,
                                    i = this;
                           this._addClass("ui-controlgroup ui-controlgroup-" + this.options.direction),
                                    "horizontal" === this.options.direction && this._addClass(null, "ui-helper-clearfix"),
                                    this._initWidgets(),
                                    (e = this.childWidgets),
                                    this.options.onlyVisible && (e = e.filter(":visible")),
                                    e.length &&
                                             (t.each(["first", "last"], function (t, s) {
                                                      var n = e[s]().data("ui-controlgroup-data");
                                                      if (n && i["_" + n.widgetName + "Options"]) {
                                                               var o = i["_" + n.widgetName + "Options"](1 === e.length ? "only" : s);
                                                               (o.classes = i._resolveClassesValues(o.classes, n)), n.element[n.widgetName](o);
                                                      } else i._updateCornerClass(e[s](), s);
                                             }),
                                             this._callChildMethod("refresh"));
                  },
         }),
                  t.widget("ui.checkboxradio", [
                           t.ui.formResetMixin,
                           {
                                    version: "1.12.1",
                                    options: { disabled: null, label: null, icon: !0, classes: { "ui-checkboxradio-label": "ui-corner-all", "ui-checkboxradio-icon": "ui-corner-all" } },
                                    _getCreateOptions: function () {
                                             var e,
                                                      i,
                                                      s = this,
                                                      n = this._super() || {};
                                             return (
                                                      this._readType(),
                                                      (i = this.element.labels()),
                                                      (this.label = t(i[i.length - 1])),
                                                      this.label.length || t.error("No label found for checkboxradio widget"),
                                                      (this.originalLabel = ""),
                                                      this.label
                                                               .contents()
                                                               .not(this.element[0])
                                                               .each(function () {
                                                                        s.originalLabel += 3 === this.nodeType ? t(this).text() : this.outerHTML;
                                                               }),
                                                      this.originalLabel && (n.label = this.originalLabel),
                                                      (e = this.element[0].disabled),
                                                      null != e && (n.disabled = e),
                                                      n
                                             );
                                    },
                                    _create: function () {
                                             var t = this.element[0].checked;
                                             this._bindFormResetHandler(),
                                                      null == this.options.disabled && (this.options.disabled = this.element[0].disabled),
                                                      this._setOption("disabled", this.options.disabled),
                                                      this._addClass("ui-checkboxradio", "ui-helper-hidden-accessible"),
                                                      this._addClass(this.label, "ui-checkboxradio-label", "ui-button ui-widget"),
                                                      "radio" === this.type && this._addClass(this.label, "ui-checkboxradio-radio-label"),
                                                      this.options.label && this.options.label !== this.originalLabel ? this._updateLabel() : this.originalLabel && (this.options.label = this.originalLabel),
                                                      this._enhance(),
                                                      t && (this._addClass(this.label, "ui-checkboxradio-checked", "ui-state-active"), this.icon && this._addClass(this.icon, null, "ui-state-hover")),
                                                      this._on({
                                                               change: "_toggleClasses",
                                                               focus: function () {
                                                                        this._addClass(this.label, null, "ui-state-focus ui-visual-focus");
                                                               },
                                                               blur: function () {
                                                                        this._removeClass(this.label, null, "ui-state-focus ui-visual-focus");
                                                               },
                                                      });
                                    },
                                    _readType: function () {
                                             var e = this.element[0].nodeName.toLowerCase();
                                             (this.type = this.element[0].type), ("input" === e && /radio|checkbox/.test(this.type)) || t.error("Can't create checkboxradio on element.nodeName=" + e + " and element.type=" + this.type);
                                    },
                                    _enhance: function () {
                                             this._updateIcon(this.element[0].checked);
                                    },
                                    widget: function () {
                                             return this.label;
                                    },
                                    _getRadioGroup: function () {
                                             var e,
                                                      i = this.element[0].name,
                                                      s = "input[name='" + t.ui.escapeSelector(i) + "']";
                                             return i
                                                      ? ((e = this.form.length
                                                                 ? t(this.form[0].elements).filter(s)
                                                                 : t(s).filter(function () {
                                                                            return 0 === t(this).form().length;
                                                                   })),
                                                        e.not(this.element))
                                                      : t([]);
                                    },
                                    _toggleClasses: function () {
                                             var e = this.element[0].checked;
                                             this._toggleClass(this.label, "ui-checkboxradio-checked", "ui-state-active", e),
                                                      this.options.icon && "checkbox" === this.type && this._toggleClass(this.icon, null, "ui-icon-check ui-state-checked", e)._toggleClass(this.icon, null, "ui-icon-blank", !e),
                                                      "radio" === this.type &&
                                                               this._getRadioGroup().each(function () {
                                                                        var e = t(this).checkboxradio("instance");
                                                                        e && e._removeClass(e.label, "ui-checkboxradio-checked", "ui-state-active");
                                                               });
                                    },
                                    _destroy: function () {
                                             this._unbindFormResetHandler(), this.icon && (this.icon.remove(), this.iconSpace.remove());
                                    },
                                    _setOption: function (t, e) {
                                             return "label" !== t || e
                                                      ? (this._super(t, e), "disabled" === t ? (this._toggleClass(this.label, null, "ui-state-disabled", e), (this.element[0].disabled = e), void 0) : (this.refresh(), void 0))
                                                      : void 0;
                                    },
                                    _updateIcon: function (e) {
                                             var i = "ui-icon ui-icon-background ";
                                             this.options.icon
                                                      ? (this.icon || ((this.icon = t("<span>")), (this.iconSpace = t("<span> </span>")), this._addClass(this.iconSpace, "ui-checkboxradio-icon-space")),
                                                        "checkbox" === this.type
                                                                 ? ((i += e ? "ui-icon-check ui-state-checked" : "ui-icon-blank"), this._removeClass(this.icon, null, e ? "ui-icon-blank" : "ui-icon-check"))
                                                                 : (i += "ui-icon-blank"),
                                                        this._addClass(this.icon, "ui-checkboxradio-icon", i),
                                                        e || this._removeClass(this.icon, null, "ui-icon-check ui-state-checked"),
                                                        this.icon.prependTo(this.label).after(this.iconSpace))
                                                      : void 0 !== this.icon && (this.icon.remove(), this.iconSpace.remove(), delete this.icon);
                                    },
                                    _updateLabel: function () {
                                             var t = this.label.contents().not(this.element[0]);
                                             this.icon && (t = t.not(this.icon[0])), this.iconSpace && (t = t.not(this.iconSpace[0])), t.remove(), this.label.append(this.options.label);
                                    },
                                    refresh: function () {
                                             var t = this.element[0].checked,
                                                      e = this.element[0].disabled;
                                             this._updateIcon(t),
                                                      this._toggleClass(this.label, "ui-checkboxradio-checked", "ui-state-active", t),
                                                      null !== this.options.label && this._updateLabel(),
                                                      e !== this.options.disabled && this._setOptions({ disabled: e });
                                    },
                           },
                  ]),
                  t.ui.checkboxradio,
                  t.widget("ui.button", {
                           version: "1.12.1",
                           defaultElement: "<button>",
                           options: { classes: { "ui-button": "ui-corner-all" }, disabled: null, icon: null, iconPosition: "beginning", label: null, showLabel: !0 },
                           _getCreateOptions: function () {
                                    var t,
                                             e = this._super() || {};
                                    return (
                                             (this.isInput = this.element.is("input")),
                                             (t = this.element[0].disabled),
                                             null != t && (e.disabled = t),
                                             (this.originalLabel = this.isInput ? this.element.val() : this.element.html()),
                                             this.originalLabel && (e.label = this.originalLabel),
                                             e
                                    );
                           },
                           _create: function () {
                                    !this.option.showLabel & !this.options.icon && (this.options.showLabel = !0),
                                             null == this.options.disabled && (this.options.disabled = this.element[0].disabled || !1),
                                             (this.hasTitle = !!this.element.attr("title")),
                                             this.options.label && this.options.label !== this.originalLabel && (this.isInput ? this.element.val(this.options.label) : this.element.html(this.options.label)),
                                             this._addClass("ui-button", "ui-widget"),
                                             this._setOption("disabled", this.options.disabled),
                                             this._enhance(),
                                             this.element.is("a") &&
                                                      this._on({
                                                               keyup: function (e) {
                                                                        e.keyCode === t.ui.keyCode.SPACE && (e.preventDefault(), this.element[0].click ? this.element[0].click() : this.element.trigger("click"));
                                                               },
                                                      });
                           },
                           _enhance: function () {
                                    this.element.is("button") || this.element.attr("role", "button"), this.options.icon && (this._updateIcon("icon", this.options.icon), this._updateTooltip());
                           },
                           _updateTooltip: function () {
                                    (this.title = this.element.attr("title")), this.options.showLabel || this.title || this.element.attr("title", this.options.label);
                           },
                           _updateIcon: function (e, i) {
                                    var s = "iconPosition" !== e,
                                             n = s ? this.options.iconPosition : i,
                                             o = "top" === n || "bottom" === n;
                                    this.icon
                                             ? s && this._removeClass(this.icon, null, this.options.icon)
                                             : ((this.icon = t("<span>")), this._addClass(this.icon, "ui-button-icon", "ui-icon"), this.options.showLabel || this._addClass("ui-button-icon-only")),
                                             s && this._addClass(this.icon, null, i),
                                             this._attachIcon(n),
                                             o
                                                      ? (this._addClass(this.icon, null, "ui-widget-icon-block"), this.iconSpace && this.iconSpace.remove())
                                                      : (this.iconSpace || ((this.iconSpace = t("<span> </span>")), this._addClass(this.iconSpace, "ui-button-icon-space")),
                                                        this._removeClass(this.icon, null, "ui-wiget-icon-block"),
                                                        this._attachIconSpace(n));
                           },
                           _destroy: function () {
                                    this.element.removeAttr("role"), this.icon && this.icon.remove(), this.iconSpace && this.iconSpace.remove(), this.hasTitle || this.element.removeAttr("title");
                           },
                           _attachIconSpace: function (t) {
                                    this.icon[/^(?:end|bottom)/.test(t) ? "before" : "after"](this.iconSpace);
                           },
                           _attachIcon: function (t) {
                                    this.element[/^(?:end|bottom)/.test(t) ? "append" : "prepend"](this.icon);
                           },
                           _setOptions: function (t) {
                                    var e = void 0 === t.showLabel ? this.options.showLabel : t.showLabel,
                                             i = void 0 === t.icon ? this.options.icon : t.icon;
                                    e || i || (t.showLabel = !0), this._super(t);
                           },
                           _setOption: function (t, e) {
                                    "icon" === t && (e ? this._updateIcon(t, e) : this.icon && (this.icon.remove(), this.iconSpace && this.iconSpace.remove())),
                                             "iconPosition" === t && this._updateIcon(t, e),
                                             "showLabel" === t && (this._toggleClass("ui-button-icon-only", null, !e), this._updateTooltip()),
                                             "label" === t && (this.isInput ? this.element.val(e) : (this.element.html(e), this.icon && (this._attachIcon(this.options.iconPosition), this._attachIconSpace(this.options.iconPosition)))),
                                             this._super(t, e),
                                             "disabled" === t && (this._toggleClass(null, "ui-state-disabled", e), (this.element[0].disabled = e), e && this.element.blur());
                           },
                           refresh: function () {
                                    var t = this.element.is("input, button") ? this.element[0].disabled : this.element.hasClass("ui-button-disabled");
                                    t !== this.options.disabled && this._setOptions({ disabled: t }), this._updateTooltip();
                           },
                  }),
                  t.uiBackCompat !== !1 &&
                           (t.widget("ui.button", t.ui.button, {
                                    options: { text: !0, icons: { primary: null, secondary: null } },
                                    _create: function () {
                                             this.options.showLabel && !this.options.text && (this.options.showLabel = this.options.text),
                                                      !this.options.showLabel && this.options.text && (this.options.text = this.options.showLabel),
                                                      this.options.icon || (!this.options.icons.primary && !this.options.icons.secondary)
                                                               ? this.options.icon && (this.options.icons.primary = this.options.icon)
                                                               : this.options.icons.primary
                                                               ? (this.options.icon = this.options.icons.primary)
                                                               : ((this.options.icon = this.options.icons.secondary), (this.options.iconPosition = "end")),
                                                      this._super();
                                    },
                                    _setOption: function (t, e) {
                                             return "text" === t
                                                      ? (this._super("showLabel", e), void 0)
                                                      : ("showLabel" === t && (this.options.text = e),
                                                        "icon" === t && (this.options.icons.primary = e),
                                                        "icons" === t &&
                                                                 (e.primary
                                                                          ? (this._super("icon", e.primary), this._super("iconPosition", "beginning"))
                                                                          : e.secondary && (this._super("icon", e.secondary), this._super("iconPosition", "end"))),
                                                        this._superApply(arguments),
                                                        void 0);
                                    },
                           }),
                           (t.fn.button = (function (e) {
                                    return function () {
                                             return !this.length || (this.length && "INPUT" !== this[0].tagName) || (this.length && "INPUT" === this[0].tagName && "checkbox" !== this.attr("type") && "radio" !== this.attr("type"))
                                                      ? e.apply(this, arguments)
                                                      : (t.ui.checkboxradio || t.error("Checkboxradio widget missing"), 0 === arguments.length ? this.checkboxradio({ icon: !1 }) : this.checkboxradio.apply(this, arguments));
                                    };
                           })(t.fn.button)),
                           (t.fn.buttonset = function () {
                                    return (
                                             t.ui.controlgroup || t.error("Controlgroup widget missing"),
                                             "option" === arguments[0] && "items" === arguments[1] && arguments[2]
                                                      ? this.controlgroup.apply(this, [arguments[0], "items.button", arguments[2]])
                                                      : "option" === arguments[0] && "items" === arguments[1]
                                                      ? this.controlgroup.apply(this, [arguments[0], "items.button"])
                                                      : ("object" == typeof arguments[0] && arguments[0].items && (arguments[0].items = { button: arguments[0].items }), this.controlgroup.apply(this, arguments))
                                    );
                           })),
                  t.ui.button,
                  t.widget("ui.menu", {
                           version: "1.12.1",
                           defaultElement: "<ul>",
                           delay: 300,
                           options: { icons: { submenu: "ui-icon-caret-1-e" }, items: "> *", menus: "ul", position: { my: "left top", at: "right top" }, role: "menu", blur: null, focus: null, select: null },
                           _create: function () {
                                    (this.activeMenu = this.element),
                                             (this.mouseHandled = !1),
                                             this.element.uniqueId().attr({ role: this.options.role, tabIndex: 0 }),
                                             this._addClass("ui-menu", "ui-widget ui-widget-content"),
                                             this._on({
                                                      "mousedown .ui-menu-item": function (t) {
                                                               t.preventDefault();
                                                      },
                                                      "click .ui-menu-item": function (e) {
                                                               var i = t(e.target),
                                                                        s = t(t.ui.safeActiveElement(this.document[0]));
                                                               !this.mouseHandled &&
                                                                        i.not(".ui-state-disabled").length &&
                                                                        (this.select(e),
                                                                        e.isPropagationStopped() || (this.mouseHandled = !0),
                                                                        i.has(".ui-menu").length
                                                                                 ? this.expand(e)
                                                                                 : !this.element.is(":focus") &&
                                                                                   s.closest(".ui-menu").length &&
                                                                                   (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menu").length && clearTimeout(this.timer)));
                                                      },
                                                      "mouseenter .ui-menu-item": function (e) {
                                                               if (!this.previousFilter) {
                                                                        var i = t(e.target).closest(".ui-menu-item"),
                                                                                 s = t(e.currentTarget);
                                                                        i[0] === s[0] && (this._removeClass(s.siblings().children(".ui-state-active"), null, "ui-state-active"), this.focus(e, s));
                                                               }
                                                      },
                                                      mouseleave: "collapseAll",
                                                      "mouseleave .ui-menu": "collapseAll",
                                                      focus: function (t, e) {
                                                               var i = this.active || this.element.find(this.options.items).eq(0);
                                                               e || this.focus(t, i);
                                                      },
                                                      blur: function (e) {
                                                               this._delay(function () {
                                                                        var i = !t.contains(this.element[0], t.ui.safeActiveElement(this.document[0]));
                                                                        i && this.collapseAll(e);
                                                               });
                                                      },
                                                      keydown: "_keydown",
                                             }),
                                             this.refresh(),
                                             this._on(this.document, {
                                                      click: function (t) {
                                                               this._closeOnDocumentClick(t) && this.collapseAll(t), (this.mouseHandled = !1);
                                                      },
                                             });
                           },
                           _destroy: function () {
                                    var e = this.element.find(".ui-menu-item").removeAttr("role aria-disabled"),
                                             i = e.children(".ui-menu-item-wrapper").removeUniqueId().removeAttr("tabIndex role aria-haspopup");
                                    this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeAttr("role aria-labelledby aria-expanded aria-hidden aria-disabled tabIndex").removeUniqueId().show(),
                                             i.children().each(function () {
                                                      var e = t(this);
                                                      e.data("ui-menu-submenu-caret") && e.remove();
                                             });
                           },
                           _keydown: function (e) {
                                    var i,
                                             s,
                                             n,
                                             o,
                                             a = !0;
                                    switch (e.keyCode) {
                                             case t.ui.keyCode.PAGE_UP:
                                                      this.previousPage(e);
                                                      break;
                                             case t.ui.keyCode.PAGE_DOWN:
                                                      this.nextPage(e);
                                                      break;
                                             case t.ui.keyCode.HOME:
                                                      this._move("first", "first", e);
                                                      break;
                                             case t.ui.keyCode.END:
                                                      this._move("last", "last", e);
                                                      break;
                                             case t.ui.keyCode.UP:
                                                      this.previous(e);
                                                      break;
                                             case t.ui.keyCode.DOWN:
                                                      this.next(e);
                                                      break;
                                             case t.ui.keyCode.LEFT:
                                                      this.collapse(e);
                                                      break;
                                             case t.ui.keyCode.RIGHT:
                                                      this.active && !this.active.is(".ui-state-disabled") && this.expand(e);
                                                      break;
                                             case t.ui.keyCode.ENTER:
                                             case t.ui.keyCode.SPACE:
                                                      this._activate(e);
                                                      break;
                                             case t.ui.keyCode.ESCAPE:
                                                      this.collapse(e);
                                                      break;
                                             default:
                                                      (a = !1),
                                                               (s = this.previousFilter || ""),
                                                               (o = !1),
                                                               (n = e.keyCode >= 96 && 105 >= e.keyCode ? "" + (e.keyCode - 96) : String.fromCharCode(e.keyCode)),
                                                               clearTimeout(this.filterTimer),
                                                               n === s ? (o = !0) : (n = s + n),
                                                               (i = this._filterMenuItems(n)),
                                                               (i = o && -1 !== i.index(this.active.next()) ? this.active.nextAll(".ui-menu-item") : i),
                                                               i.length || ((n = String.fromCharCode(e.keyCode)), (i = this._filterMenuItems(n))),
                                                               i.length
                                                                        ? (this.focus(e, i),
                                                                          (this.previousFilter = n),
                                                                          (this.filterTimer = this._delay(function () {
                                                                                   delete this.previousFilter;
                                                                          }, 1e3)))
                                                                        : delete this.previousFilter;
                                    }
                                    a && e.preventDefault();
                           },
                           _activate: function (t) {
                                    this.active && !this.active.is(".ui-state-disabled") && (this.active.children("[aria-haspopup='true']").length ? this.expand(t) : this.select(t));
                           },
                           refresh: function () {
                                    var e,
                                             i,
                                             s,
                                             n,
                                             o,
                                             a = this,
                                             r = this.options.icons.submenu,
                                             l = this.element.find(this.options.menus);
                                    this._toggleClass("ui-menu-icons", null, !!this.element.find(".ui-icon").length),
                                             (s = l
                                                      .filter(":not(.ui-menu)")
                                                      .hide()
                                                      .attr({ role: this.options.role, "aria-hidden": "true", "aria-expanded": "false" })
                                                      .each(function () {
                                                               var e = t(this),
                                                                        i = e.prev(),
                                                                        s = t("<span>").data("ui-menu-submenu-caret", !0);
                                                               a._addClass(s, "ui-menu-icon", "ui-icon " + r), i.attr("aria-haspopup", "true").prepend(s), e.attr("aria-labelledby", i.attr("id"));
                                                      })),
                                             this._addClass(s, "ui-menu", "ui-widget ui-widget-content ui-front"),
                                             (e = l.add(this.element)),
                                             (i = e.find(this.options.items)),
                                             i.not(".ui-menu-item").each(function () {
                                                      var e = t(this);
                                                      a._isDivider(e) && a._addClass(e, "ui-menu-divider", "ui-widget-content");
                                             }),
                                             (n = i.not(".ui-menu-item, .ui-menu-divider")),
                                             (o = n.children().not(".ui-menu").uniqueId().attr({ tabIndex: -1, role: this._itemRole() })),
                                             this._addClass(n, "ui-menu-item")._addClass(o, "ui-menu-item-wrapper"),
                                             i.filter(".ui-state-disabled").attr("aria-disabled", "true"),
                                             this.active && !t.contains(this.element[0], this.active[0]) && this.blur();
                           },
                           _itemRole: function () {
                                    return { menu: "menuitem", listbox: "option" }[this.options.role];
                           },
                           _setOption: function (t, e) {
                                    if ("icons" === t) {
                                             var i = this.element.find(".ui-menu-icon");
                                             this._removeClass(i, null, this.options.icons.submenu)._addClass(i, null, e.submenu);
                                    }
                                    this._super(t, e);
                           },
                           _setOptionDisabled: function (t) {
                                    this._super(t), this.element.attr("aria-disabled", t + ""), this._toggleClass(null, "ui-state-disabled", !!t);
                           },
                           focus: function (t, e) {
                                    var i, s, n;
                                    this.blur(t, t && "focus" === t.type),
                                             this._scrollIntoView(e),
                                             (this.active = e.first()),
                                             (s = this.active.children(".ui-menu-item-wrapper")),
                                             this._addClass(s, null, "ui-state-active"),
                                             this.options.role && this.element.attr("aria-activedescendant", s.attr("id")),
                                             (n = this.active.parent().closest(".ui-menu-item").children(".ui-menu-item-wrapper")),
                                             this._addClass(n, null, "ui-state-active"),
                                             t && "keydown" === t.type
                                                      ? this._close()
                                                      : (this.timer = this._delay(function () {
                                                                 this._close();
                                                        }, this.delay)),
                                             (i = e.children(".ui-menu")),
                                             i.length && t && /^mouse/.test(t.type) && this._startOpening(i),
                                             (this.activeMenu = e.parent()),
                                             this._trigger("focus", t, { item: e });
                           },
                           _scrollIntoView: function (e) {
                                    var i, s, n, o, a, r;
                                    this._hasScroll() &&
                                             ((i = parseFloat(t.css(this.activeMenu[0], "borderTopWidth")) || 0),
                                             (s = parseFloat(t.css(this.activeMenu[0], "paddingTop")) || 0),
                                             (n = e.offset().top - this.activeMenu.offset().top - i - s),
                                             (o = this.activeMenu.scrollTop()),
                                             (a = this.activeMenu.height()),
                                             (r = e.outerHeight()),
                                             0 > n ? this.activeMenu.scrollTop(o + n) : n + r > a && this.activeMenu.scrollTop(o + n - a + r));
                           },
                           blur: function (t, e) {
                                    e || clearTimeout(this.timer),
                                             this.active && (this._removeClass(this.active.children(".ui-menu-item-wrapper"), null, "ui-state-active"), this._trigger("blur", t, { item: this.active }), (this.active = null));
                           },
                           _startOpening: function (t) {
                                    clearTimeout(this.timer),
                                             "true" === t.attr("aria-hidden") &&
                                                      (this.timer = this._delay(function () {
                                                               this._close(), this._open(t);
                                                      }, this.delay));
                           },
                           _open: function (e) {
                                    var i = t.extend({ of: this.active }, this.options.position);
                                    clearTimeout(this.timer), this.element.find(".ui-menu").not(e.parents(".ui-menu")).hide().attr("aria-hidden", "true"), e.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(i);
                           },
                           collapseAll: function (e, i) {
                                    clearTimeout(this.timer),
                                             (this.timer = this._delay(function () {
                                                      var s = i ? this.element : t(e && e.target).closest(this.element.find(".ui-menu"));
                                                      s.length || (s = this.element), this._close(s), this.blur(e), this._removeClass(s.find(".ui-state-active"), null, "ui-state-active"), (this.activeMenu = s);
                                             }, this.delay));
                           },
                           _close: function (t) {
                                    t || (t = this.active ? this.active.parent() : this.element), t.find(".ui-menu").hide().attr("aria-hidden", "true").attr("aria-expanded", "false");
                           },
                           _closeOnDocumentClick: function (e) {
                                    return !t(e.target).closest(".ui-menu").length;
                           },
                           _isDivider: function (t) {
                                    return !/[^\-\u2014\u2013\s]/.test(t.text());
                           },
                           collapse: function (t) {
                                    var e = this.active && this.active.parent().closest(".ui-menu-item", this.element);
                                    e && e.length && (this._close(), this.focus(t, e));
                           },
                           expand: function (t) {
                                    var e = this.active && this.active.children(".ui-menu ").find(this.options.items).first();
                                    e &&
                                             e.length &&
                                             (this._open(e.parent()),
                                             this._delay(function () {
                                                      this.focus(t, e);
                                             }));
                           },
                           next: function (t) {
                                    this._move("next", "first", t);
                           },
                           previous: function (t) {
                                    this._move("prev", "last", t);
                           },
                           isFirstItem: function () {
                                    return this.active && !this.active.prevAll(".ui-menu-item").length;
                           },
                           isLastItem: function () {
                                    return this.active && !this.active.nextAll(".ui-menu-item").length;
                           },
                           _move: function (t, e, i) {
                                    var s;
                                    this.active && (s = "first" === t || "last" === t ? this.active["first" === t ? "prevAll" : "nextAll"](".ui-menu-item").eq(-1) : this.active[t + "All"](".ui-menu-item").eq(0)),
                                             (s && s.length && this.active) || (s = this.activeMenu.find(this.options.items)[e]()),
                                             this.focus(i, s);
                           },
                           nextPage: function (e) {
                                    var i, s, n;
                                    return this.active
                                             ? (this.isLastItem() ||
                                                        (this._hasScroll()
                                                                 ? ((s = this.active.offset().top),
                                                                   (n = this.element.height()),
                                                                   this.active.nextAll(".ui-menu-item").each(function () {
                                                                            return (i = t(this)), 0 > i.offset().top - s - n;
                                                                   }),
                                                                   this.focus(e, i))
                                                                 : this.focus(e, this.activeMenu.find(this.options.items)[this.active ? "last" : "first"]())),
                                               void 0)
                                             : (this.next(e), void 0);
                           },
                           previousPage: function (e) {
                                    var i, s, n;
                                    return this.active
                                             ? (this.isFirstItem() ||
                                                        (this._hasScroll()
                                                                 ? ((s = this.active.offset().top),
                                                                   (n = this.element.height()),
                                                                   this.active.prevAll(".ui-menu-item").each(function () {
                                                                            return (i = t(this)), i.offset().top - s + n > 0;
                                                                   }),
                                                                   this.focus(e, i))
                                                                 : this.focus(e, this.activeMenu.find(this.options.items).first())),
                                               void 0)
                                             : (this.next(e), void 0);
                           },
                           _hasScroll: function () {
                                    return this.element.outerHeight() < this.element.prop("scrollHeight");
                           },
                           select: function (e) {
                                    this.active = this.active || t(e.target).closest(".ui-menu-item");
                                    var i = { item: this.active };
                                    this.active.has(".ui-menu").length || this.collapseAll(e, !0), this._trigger("select", e, i);
                           },
                           _filterMenuItems: function (e) {
                                    var i = e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&"),
                                             s = RegExp("^" + i, "i");
                                    return this.activeMenu
                                             .find(this.options.items)
                                             .filter(".ui-menu-item")
                                             .filter(function () {
                                                      return s.test(t.trim(t(this).children(".ui-menu-item-wrapper").text()));
                                             });
                           },
                  }),
                  t.widget("ui.selectmenu", [
                           t.ui.formResetMixin,
                           {
                                    version: "1.12.1",
                                    defaultElement: "<select>",
                                    options: {
                                             appendTo: null,
                                             classes: { "ui-selectmenu-button-open": "ui-corner-top", "ui-selectmenu-button-closed": "ui-corner-all" },
                                             disabled: null,
                                             icons: { button: "ui-icon-triangle-1-s" },
                                             position: { my: "left top", at: "left bottom", collision: "none" },
                                             width: !1,
                                             change: null,
                                             close: null,
                                             focus: null,
                                             open: null,
                                             select: null,
                                    },
                                    _create: function () {
                                             var e = this.element.uniqueId().attr("id");
                                             (this.ids = { element: e, button: e + "-button", menu: e + "-menu" }), this._drawButton(), this._drawMenu(), this._bindFormResetHandler(), (this._rendered = !1), (this.menuItems = t());
                                    },
                                    _drawButton: function () {
                                             var e,
                                                      i = this,
                                                      s = this._parseOption(this.element.find("option:selected"), this.element[0].selectedIndex);
                                             (this.labels = this.element.labels().attr("for", this.ids.button)),
                                                      this._on(this.labels, {
                                                               click: function (t) {
                                                                        this.button.focus(), t.preventDefault();
                                                               },
                                                      }),
                                                      this.element.hide(),
                                                      (this.button = t("<span>", {
                                                               tabindex: this.options.disabled ? -1 : 0,
                                                               id: this.ids.button,
                                                               role: "combobox",
                                                               "aria-expanded": "false",
                                                               "aria-autocomplete": "list",
                                                               "aria-owns": this.ids.menu,
                                                               "aria-haspopup": "true",
                                                               title: this.element.attr("title"),
                                                      }).insertAfter(this.element)),
                                                      this._addClass(this.button, "ui-selectmenu-button ui-selectmenu-button-closed", "ui-button ui-widget"),
                                                      (e = t("<span>").appendTo(this.button)),
                                                      this._addClass(e, "ui-selectmenu-icon", "ui-icon " + this.options.icons.button),
                                                      (this.buttonItem = this._renderButtonItem(s).appendTo(this.button)),
                                                      this.options.width !== !1 && this._resizeButton(),
                                                      this._on(this.button, this._buttonEvents),
                                                      this.button.one("focusin", function () {
                                                               i._rendered || i._refreshMenu();
                                                      });
                                    },
                                    _drawMenu: function () {
                                             var e = this;
                                             (this.menu = t("<ul>", { "aria-hidden": "true", "aria-labelledby": this.ids.button, id: this.ids.menu })),
                                                      (this.menuWrap = t("<div>").append(this.menu)),
                                                      this._addClass(this.menuWrap, "ui-selectmenu-menu", "ui-front"),
                                                      this.menuWrap.appendTo(this._appendTo()),
                                                      (this.menuInstance = this.menu
                                                               .menu({
                                                                        classes: { "ui-menu": "ui-corner-bottom" },
                                                                        role: "listbox",
                                                                        select: function (t, i) {
                                                                                 t.preventDefault(), e._setSelection(), e._select(i.item.data("ui-selectmenu-item"), t);
                                                                        },
                                                                        focus: function (t, i) {
                                                                                 var s = i.item.data("ui-selectmenu-item");
                                                                                 null != e.focusIndex && s.index !== e.focusIndex && (e._trigger("focus", t, { item: s }), e.isOpen || e._select(s, t)),
                                                                                          (e.focusIndex = s.index),
                                                                                          e.button.attr("aria-activedescendant", e.menuItems.eq(s.index).attr("id"));
                                                                        },
                                                               })
                                                               .menu("instance")),
                                                      this.menuInstance._off(this.menu, "mouseleave"),
                                                      (this.menuInstance._closeOnDocumentClick = function () {
                                                               return !1;
                                                      }),
                                                      (this.menuInstance._isDivider = function () {
                                                               return !1;
                                                      });
                                    },
                                    refresh: function () {
                                             this._refreshMenu(),
                                                      this.buttonItem.replaceWith((this.buttonItem = this._renderButtonItem(this._getSelectedItem().data("ui-selectmenu-item") || {}))),
                                                      null === this.options.width && this._resizeButton();
                                    },
                                    _refreshMenu: function () {
                                             var t,
                                                      e = this.element.find("option");
                                             this.menu.empty(),
                                                      this._parseOptions(e),
                                                      this._renderMenu(this.menu, this.items),
                                                      this.menuInstance.refresh(),
                                                      (this.menuItems = this.menu.find("li").not(".ui-selectmenu-optgroup").find(".ui-menu-item-wrapper")),
                                                      (this._rendered = !0),
                                                      e.length && ((t = this._getSelectedItem()), this.menuInstance.focus(null, t), this._setAria(t.data("ui-selectmenu-item")), this._setOption("disabled", this.element.prop("disabled")));
                                    },
                                    open: function (t) {
                                             this.options.disabled ||
                                                      (this._rendered ? (this._removeClass(this.menu.find(".ui-state-active"), null, "ui-state-active"), this.menuInstance.focus(null, this._getSelectedItem())) : this._refreshMenu(),
                                                      this.menuItems.length && ((this.isOpen = !0), this._toggleAttr(), this._resizeMenu(), this._position(), this._on(this.document, this._documentClick), this._trigger("open", t)));
                                    },
                                    _position: function () {
                                             this.menuWrap.position(t.extend({ of: this.button }, this.options.position));
                                    },
                                    close: function (t) {
                                             this.isOpen && ((this.isOpen = !1), this._toggleAttr(), (this.range = null), this._off(this.document), this._trigger("close", t));
                                    },
                                    widget: function () {
                                             return this.button;
                                    },
                                    menuWidget: function () {
                                             return this.menu;
                                    },
                                    _renderButtonItem: function (e) {
                                             var i = t("<span>");
                                             return this._setText(i, e.label), this._addClass(i, "ui-selectmenu-text"), i;
                                    },
                                    _renderMenu: function (e, i) {
                                             var s = this,
                                                      n = "";
                                             t.each(i, function (i, o) {
                                                      var a;
                                                      o.optgroup !== n &&
                                                               ((a = t("<li>", { text: o.optgroup })),
                                                               s._addClass(a, "ui-selectmenu-optgroup", "ui-menu-divider" + (o.element.parent("optgroup").prop("disabled") ? " ui-state-disabled" : "")),
                                                               a.appendTo(e),
                                                               (n = o.optgroup)),
                                                               s._renderItemData(e, o);
                                             });
                                    },
                                    _renderItemData: function (t, e) {
                                             return this._renderItem(t, e).data("ui-selectmenu-item", e);
                                    },
                                    _renderItem: function (e, i) {
                                             var s = t("<li>"),
                                                      n = t("<div>", { title: i.element.attr("title") });
                                             return i.disabled && this._addClass(s, null, "ui-state-disabled"), this._setText(n, i.label), s.append(n).appendTo(e);
                                    },
                                    _setText: function (t, e) {
                                             e ? t.text(e) : t.html("&#160;");
                                    },
                                    _move: function (t, e) {
                                             var i,
                                                      s,
                                                      n = ".ui-menu-item";
                                             this.isOpen ? (i = this.menuItems.eq(this.focusIndex).parent("li")) : ((i = this.menuItems.eq(this.element[0].selectedIndex).parent("li")), (n += ":not(.ui-state-disabled)")),
                                                      (s = "first" === t || "last" === t ? i["first" === t ? "prevAll" : "nextAll"](n).eq(-1) : i[t + "All"](n).eq(0)),
                                                      s.length && this.menuInstance.focus(e, s);
                                    },
                                    _getSelectedItem: function () {
                                             return this.menuItems.eq(this.element[0].selectedIndex).parent("li");
                                    },
                                    _toggle: function (t) {
                                             this[this.isOpen ? "close" : "open"](t);
                                    },
                                    _setSelection: function () {
                                             var t;
                                             this.range && (window.getSelection ? ((t = window.getSelection()), t.removeAllRanges(), t.addRange(this.range)) : this.range.select(), this.button.focus());
                                    },
                                    _documentClick: {
                                             mousedown: function (e) {
                                                      this.isOpen && (t(e.target).closest(".ui-selectmenu-menu, #" + t.ui.escapeSelector(this.ids.button)).length || this.close(e));
                                             },
                                    },
                                    _buttonEvents: {
                                             mousedown: function () {
                                                      var t;
                                                      window.getSelection ? ((t = window.getSelection()), t.rangeCount && (this.range = t.getRangeAt(0))) : (this.range = document.selection.createRange());
                                             },
                                             click: function (t) {
                                                      this._setSelection(), this._toggle(t);
                                             },
                                             keydown: function (e) {
                                                      var i = !0;
                                                      switch (e.keyCode) {
                                                               case t.ui.keyCode.TAB:
                                                               case t.ui.keyCode.ESCAPE:
                                                                        this.close(e), (i = !1);
                                                                        break;
                                                               case t.ui.keyCode.ENTER:
                                                                        this.isOpen && this._selectFocusedItem(e);
                                                                        break;
                                                               case t.ui.keyCode.UP:
                                                                        e.altKey ? this._toggle(e) : this._move("prev", e);
                                                                        break;
                                                               case t.ui.keyCode.DOWN:
                                                                        e.altKey ? this._toggle(e) : this._move("next", e);
                                                                        break;
                                                               case t.ui.keyCode.SPACE:
                                                                        this.isOpen ? this._selectFocusedItem(e) : this._toggle(e);
                                                                        break;
                                                               case t.ui.keyCode.LEFT:
                                                                        this._move("prev", e);
                                                                        break;
                                                               case t.ui.keyCode.RIGHT:
                                                                        this._move("next", e);
                                                                        break;
                                                               case t.ui.keyCode.HOME:
                                                               case t.ui.keyCode.PAGE_UP:
                                                                        this._move("first", e);
                                                                        break;
                                                               case t.ui.keyCode.END:
                                                               case t.ui.keyCode.PAGE_DOWN:
                                                                        this._move("last", e);
                                                                        break;
                                                               default:
                                                                        this.menu.trigger(e), (i = !1);
                                                      }
                                                      i && e.preventDefault();
                                             },
                                    },
                                    _selectFocusedItem: function (t) {
                                             var e = this.menuItems.eq(this.focusIndex).parent("li");
                                             e.hasClass("ui-state-disabled") || this._select(e.data("ui-selectmenu-item"), t);
                                    },
                                    _select: function (t, e) {
                                             var i = this.element[0].selectedIndex;
                                             (this.element[0].selectedIndex = t.index),
                                                      this.buttonItem.replaceWith((this.buttonItem = this._renderButtonItem(t))),
                                                      this._setAria(t),
                                                      this._trigger("select", e, { item: t }),
                                                      t.index !== i && this._trigger("change", e, { item: t }),
                                                      this.close(e);
                                    },
                                    _setAria: function (t) {
                                             var e = this.menuItems.eq(t.index).attr("id");
                                             this.button.attr({ "aria-labelledby": e, "aria-activedescendant": e }), this.menu.attr("aria-activedescendant", e);
                                    },
                                    _setOption: function (t, e) {
                                             if ("icons" === t) {
                                                      var i = this.button.find("span.ui-icon");
                                                      this._removeClass(i, null, this.options.icons.button)._addClass(i, null, e.button);
                                             }
                                             this._super(t, e), "appendTo" === t && this.menuWrap.appendTo(this._appendTo()), "width" === t && this._resizeButton();
                                    },
                                    _setOptionDisabled: function (t) {
                                             this._super(t),
                                                      this.menuInstance.option("disabled", t),
                                                      this.button.attr("aria-disabled", t),
                                                      this._toggleClass(this.button, null, "ui-state-disabled", t),
                                                      this.element.prop("disabled", t),
                                                      t ? (this.button.attr("tabindex", -1), this.close()) : this.button.attr("tabindex", 0);
                                    },
                                    _appendTo: function () {
                                             var e = this.options.appendTo;
                                             return e && (e = e.jquery || e.nodeType ? t(e) : this.document.find(e).eq(0)), (e && e[0]) || (e = this.element.closest(".ui-front, dialog")), e.length || (e = this.document[0].body), e;
                                    },
                                    _toggleAttr: function () {
                                             this.button.attr("aria-expanded", this.isOpen),
                                                      this._removeClass(this.button, "ui-selectmenu-button-" + (this.isOpen ? "closed" : "open"))
                                                               ._addClass(this.button, "ui-selectmenu-button-" + (this.isOpen ? "open" : "closed"))
                                                               ._toggleClass(this.menuWrap, "ui-selectmenu-open", null, this.isOpen),
                                                      this.menu.attr("aria-hidden", !this.isOpen);
                                    },
                                    _resizeButton: function () {
                                             var t = this.options.width;
                                             return t === !1 ? (this.button.css("width", ""), void 0) : (null === t && ((t = this.element.show().outerWidth()), this.element.hide()), this.button.outerWidth(t), void 0);
                                    },
                                    _resizeMenu: function () {
                                             this.menu.outerWidth(Math.max(this.button.outerWidth(), this.menu.width("").outerWidth() + 1));
                                    },
                                    _getCreateOptions: function () {
                                             var t = this._super();
                                             return (t.disabled = this.element.prop("disabled")), t;
                                    },
                                    _parseOptions: function (e) {
                                             var i = this,
                                                      s = [];
                                             e.each(function (e, n) {
                                                      s.push(i._parseOption(t(n), e));
                                             }),
                                                      (this.items = s);
                                    },
                                    _parseOption: function (t, e) {
                                             var i = t.parent("optgroup");
                                             return { element: t, index: e, value: t.val(), label: t.text(), optgroup: i.attr("label") || "", disabled: i.prop("disabled") || t.prop("disabled") };
                                    },
                                    _destroy: function () {
                                             this._unbindFormResetHandler(), this.menuWrap.remove(), this.button.remove(), this.element.show(), this.element.removeUniqueId(), this.labels.attr("for", this.ids.element);
                                    },
                           },
                  ]);
         var r = "ui-effects-",
                  l = "ui-effects-style",
                  h = "ui-effects-animated",
                  u = t;
         (t.effects = { effect: {} }),
                  (function (t, e) {
                           function i(t, e, i) {
                                    var s = c[e.type] || {};
                                    return null == t ? (i || !e.def ? null : e.def) : ((t = s.floor ? ~~t : parseFloat(t)), isNaN(t) ? e.def : s.mod ? (t + s.mod) % s.mod : 0 > t ? 0 : t > s.max ? s.max : t);
                           }
                           function s(i) {
                                    var s = h(),
                                             n = (s._rgba = []);
                                    return (
                                             (i = i.toLowerCase()),
                                             f(l, function (t, o) {
                                                      var a,
                                                               r = o.re.exec(i),
                                                               l = r && o.parse(r),
                                                               h = o.space || "rgba";
                                                      return l ? ((a = s[h](l)), (s[u[h].cache] = a[u[h].cache]), (n = s._rgba = a._rgba), !1) : e;
                                             }),
                                             n.length ? ("0,0,0,0" === n.join() && t.extend(n, o.transparent), s) : o[i]
                                    );
                           }
                           function n(t, e, i) {
                                    return (i = (i + 1) % 1), 1 > 6 * i ? t + 6 * (e - t) * i : 1 > 2 * i ? e : 2 > 3 * i ? t + 6 * (e - t) * (2 / 3 - i) : t;
                           }
                           var o,
                                    a = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",
                                    r = /^([\-+])=\s*(\d+\.?\d*)/,
                                    l = [
                                             {
                                                      re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                                                      parse: function (t) {
                                                               return [t[1], t[2], t[3], t[4]];
                                                      },
                                             },
                                             {
                                                      re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                                                      parse: function (t) {
                                                               return [2.55 * t[1], 2.55 * t[2], 2.55 * t[3], t[4]];
                                                      },
                                             },
                                             {
                                                      re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,
                                                      parse: function (t) {
                                                               return [parseInt(t[1], 16), parseInt(t[2], 16), parseInt(t[3], 16)];
                                                      },
                                             },
                                             {
                                                      re: /#([a-f0-9])([a-f0-9])([a-f0-9])/,
                                                      parse: function (t) {
                                                               return [parseInt(t[1] + t[1], 16), parseInt(t[2] + t[2], 16), parseInt(t[3] + t[3], 16)];
                                                      },
                                             },
                                             {
                                                      re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                                                      space: "hsla",
                                                      parse: function (t) {
                                                               return [t[1], t[2] / 100, t[3] / 100, t[4]];
                                                      },
                                             },
                                    ],
                                    h = (t.Color = function (e, i, s, n) {
                                             return new t.Color.fn.parse(e, i, s, n);
                                    }),
                                    u = {
                                             rgba: { props: { red: { idx: 0, type: "byte" }, green: { idx: 1, type: "byte" }, blue: { idx: 2, type: "byte" } } },
                                             hsla: { props: { hue: { idx: 0, type: "degrees" }, saturation: { idx: 1, type: "percent" }, lightness: { idx: 2, type: "percent" } } },
                                    },
                                    c = { byte: { floor: !0, max: 255 }, percent: { max: 1 }, degrees: { mod: 360, floor: !0 } },
                                    d = (h.support = {}),
                                    p = t("<p>")[0],
                                    f = t.each;
                           (p.style.cssText = "background-color:rgba(1,1,1,.5)"),
                                    (d.rgba = p.style.backgroundColor.indexOf("rgba") > -1),
                                    f(u, function (t, e) {
                                             (e.cache = "_" + t), (e.props.alpha = { idx: 3, type: "percent", def: 1 });
                                    }),
                                    (h.fn = t.extend(h.prototype, {
                                             parse: function (n, a, r, l) {
                                                      if (n === e) return (this._rgba = [null, null, null, null]), this;
                                                      (n.jquery || n.nodeType) && ((n = t(n).css(a)), (a = e));
                                                      var c = this,
                                                               d = t.type(n),
                                                               p = (this._rgba = []);
                                                      return (
                                                               a !== e && ((n = [n, a, r, l]), (d = "array")),
                                                               "string" === d
                                                                        ? this.parse(s(n) || o._default)
                                                                        : "array" === d
                                                                        ? (f(u.rgba.props, function (t, e) {
                                                                                   p[e.idx] = i(n[e.idx], e);
                                                                          }),
                                                                          this)
                                                                        : "object" === d
                                                                        ? (n instanceof h
                                                                                   ? f(u, function (t, e) {
                                                                                              n[e.cache] && (c[e.cache] = n[e.cache].slice());
                                                                                     })
                                                                                   : f(u, function (e, s) {
                                                                                              var o = s.cache;
                                                                                              f(s.props, function (t, e) {
                                                                                                       if (!c[o] && s.to) {
                                                                                                                if ("alpha" === t || null == n[t]) return;
                                                                                                                c[o] = s.to(c._rgba);
                                                                                                       }
                                                                                                       c[o][e.idx] = i(n[t], e, !0);
                                                                                              }),
                                                                                                       c[o] && 0 > t.inArray(null, c[o].slice(0, 3)) && ((c[o][3] = 1), s.from && (c._rgba = s.from(c[o])));
                                                                                     }),
                                                                          this)
                                                                        : e
                                                      );
                                             },
                                             is: function (t) {
                                                      var i = h(t),
                                                               s = !0,
                                                               n = this;
                                                      return (
                                                               f(u, function (t, o) {
                                                                        var a,
                                                                                 r = i[o.cache];
                                                                        return (
                                                                                 r &&
                                                                                          ((a = n[o.cache] || (o.to && o.to(n._rgba)) || []),
                                                                                          f(o.props, function (t, i) {
                                                                                                   return null != r[i.idx] ? (s = r[i.idx] === a[i.idx]) : e;
                                                                                          })),
                                                                                 s
                                                                        );
                                                               }),
                                                               s
                                                      );
                                             },
                                             _space: function () {
                                                      var t = [],
                                                               e = this;
                                                      return (
                                                               f(u, function (i, s) {
                                                                        e[s.cache] && t.push(i);
                                                               }),
                                                               t.pop()
                                                      );
                                             },
                                             transition: function (t, e) {
                                                      var s = h(t),
                                                               n = s._space(),
                                                               o = u[n],
                                                               a = 0 === this.alpha() ? h("transparent") : this,
                                                               r = a[o.cache] || o.to(a._rgba),
                                                               l = r.slice();
                                                      return (
                                                               (s = s[o.cache]),
                                                               f(o.props, function (t, n) {
                                                                        var o = n.idx,
                                                                                 a = r[o],
                                                                                 h = s[o],
                                                                                 u = c[n.type] || {};
                                                                        null !== h && (null === a ? (l[o] = h) : (u.mod && (h - a > u.mod / 2 ? (a += u.mod) : a - h > u.mod / 2 && (a -= u.mod)), (l[o] = i((h - a) * e + a, n))));
                                                               }),
                                                               this[n](l)
                                                      );
                                             },
                                             blend: function (e) {
                                                      if (1 === this._rgba[3]) return this;
                                                      var i = this._rgba.slice(),
                                                               s = i.pop(),
                                                               n = h(e)._rgba;
                                                      return h(
                                                               t.map(i, function (t, e) {
                                                                        return (1 - s) * n[e] + s * t;
                                                               })
                                                      );
                                             },
                                             toRgbaString: function () {
                                                      var e = "rgba(",
                                                               i = t.map(this._rgba, function (t, e) {
                                                                        return null == t ? (e > 2 ? 1 : 0) : t;
                                                               });
                                                      return 1 === i[3] && (i.pop(), (e = "rgb(")), e + i.join() + ")";
                                             },
                                             toHslaString: function () {
                                                      var e = "hsla(",
                                                               i = t.map(this.hsla(), function (t, e) {
                                                                        return null == t && (t = e > 2 ? 1 : 0), e && 3 > e && (t = Math.round(100 * t) + "%"), t;
                                                               });
                                                      return 1 === i[3] && (i.pop(), (e = "hsl(")), e + i.join() + ")";
                                             },
                                             toHexString: function (e) {
                                                      var i = this._rgba.slice(),
                                                               s = i.pop();
                                                      return (
                                                               e && i.push(~~(255 * s)),
                                                               "#" +
                                                                        t
                                                                                 .map(i, function (t) {
                                                                                          return (t = (t || 0).toString(16)), 1 === t.length ? "0" + t : t;
                                                                                 })
                                                                                 .join("")
                                                      );
                                             },
                                             toString: function () {
                                                      return 0 === this._rgba[3] ? "transparent" : this.toRgbaString();
                                             },
                                    })),
                                    (h.fn.parse.prototype = h.fn),
                                    (u.hsla.to = function (t) {
                                             if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
                                             var e,
                                                      i,
                                                      s = t[0] / 255,
                                                      n = t[1] / 255,
                                                      o = t[2] / 255,
                                                      a = t[3],
                                                      r = Math.max(s, n, o),
                                                      l = Math.min(s, n, o),
                                                      h = r - l,
                                                      u = r + l,
                                                      c = 0.5 * u;
                                             return (
                                                      (e = l === r ? 0 : s === r ? (60 * (n - o)) / h + 360 : n === r ? (60 * (o - s)) / h + 120 : (60 * (s - n)) / h + 240),
                                                      (i = 0 === h ? 0 : 0.5 >= c ? h / u : h / (2 - u)),
                                                      [Math.round(e) % 360, i, c, null == a ? 1 : a]
                                             );
                                    }),
                                    (u.hsla.from = function (t) {
                                             if (null == t[0] || null == t[1] || null == t[2]) return [null, null, null, t[3]];
                                             var e = t[0] / 360,
                                                      i = t[1],
                                                      s = t[2],
                                                      o = t[3],
                                                      a = 0.5 >= s ? s * (1 + i) : s + i - s * i,
                                                      r = 2 * s - a;
                                             return [Math.round(255 * n(r, a, e + 1 / 3)), Math.round(255 * n(r, a, e)), Math.round(255 * n(r, a, e - 1 / 3)), o];
                                    }),
                                    f(u, function (s, n) {
                                             var o = n.props,
                                                      a = n.cache,
                                                      l = n.to,
                                                      u = n.from;
                                             (h.fn[s] = function (s) {
                                                      if ((l && !this[a] && (this[a] = l(this._rgba)), s === e)) return this[a].slice();
                                                      var n,
                                                               r = t.type(s),
                                                               c = "array" === r || "object" === r ? s : arguments,
                                                               d = this[a].slice();
                                                      return (
                                                               f(o, function (t, e) {
                                                                        var s = c["object" === r ? t : e.idx];
                                                                        null == s && (s = d[e.idx]), (d[e.idx] = i(s, e));
                                                               }),
                                                               u ? ((n = h(u(d))), (n[a] = d), n) : h(d)
                                                      );
                                             }),
                                                      f(o, function (e, i) {
                                                               h.fn[e] ||
                                                                        (h.fn[e] = function (n) {
                                                                                 var o,
                                                                                          a = t.type(n),
                                                                                          l = "alpha" === e ? (this._hsla ? "hsla" : "rgba") : s,
                                                                                          h = this[l](),
                                                                                          u = h[i.idx];
                                                                                 return "undefined" === a
                                                                                          ? u
                                                                                          : ("function" === a && ((n = n.call(this, u)), (a = t.type(n))),
                                                                                            null == n && i.empty
                                                                                                     ? this
                                                                                                     : ("string" === a && ((o = r.exec(n)), o && (n = u + parseFloat(o[2]) * ("+" === o[1] ? 1 : -1))), (h[i.idx] = n), this[l](h)));
                                                                        });
                                                      });
                                    }),
                                    (h.hook = function (e) {
                                             var i = e.split(" ");
                                             f(i, function (e, i) {
                                                      (t.cssHooks[i] = {
                                                               set: function (e, n) {
                                                                        var o,
                                                                                 a,
                                                                                 r = "";
                                                                        if ("transparent" !== n && ("string" !== t.type(n) || (o = s(n)))) {
                                                                                 if (((n = h(o || n)), !d.rgba && 1 !== n._rgba[3])) {
                                                                                          for (a = "backgroundColor" === i ? e.parentNode : e; ("" === r || "transparent" === r) && a && a.style; )
                                                                                                   try {
                                                                                                            (r = t.css(a, "backgroundColor")), (a = a.parentNode);
                                                                                                   } catch (l) {}
                                                                                          n = n.blend(r && "transparent" !== r ? r : "_default");
                                                                                 }
                                                                                 n = n.toRgbaString();
                                                                        }
                                                                        try {
                                                                                 e.style[i] = n;
                                                                        } catch (l) {}
                                                               },
                                                      }),
                                                               (t.fx.step[i] = function (e) {
                                                                        e.colorInit || ((e.start = h(e.elem, i)), (e.end = h(e.end)), (e.colorInit = !0)), t.cssHooks[i].set(e.elem, e.start.transition(e.end, e.pos));
                                                               });
                                             });
                                    }),
                                    h.hook(a),
                                    (t.cssHooks.borderColor = {
                                             expand: function (t) {
                                                      var e = {};
                                                      return (
                                                               f(["Top", "Right", "Bottom", "Left"], function (i, s) {
                                                                        e["border" + s + "Color"] = t;
                                                               }),
                                                               e
                                                      );
                                             },
                                    }),
                                    (o = t.Color.names = {
                                             aqua: "#00ffff",
                                             black: "#000000",
                                             blue: "#0000ff",
                                             fuchsia: "#ff00ff",
                                             gray: "#808080",
                                             green: "#008000",
                                             lime: "#00ff00",
                                             maroon: "#800000",
                                             navy: "#000080",
                                             olive: "#808000",
                                             purple: "#800080",
                                             red: "#ff0000",
                                             silver: "#c0c0c0",
                                             teal: "#008080",
                                             white: "#ffffff",
                                             yellow: "#ffff00",
                                             transparent: [null, null, null, 0],
                                             _default: "#ffffff",
                                    });
                  })(u),
                  (function () {
                           function e(e) {
                                    var i,
                                             s,
                                             n = e.ownerDocument.defaultView ? e.ownerDocument.defaultView.getComputedStyle(e, null) : e.currentStyle,
                                             o = {};
                                    if (n && n.length && n[0] && n[n[0]]) for (s = n.length; s--; ) (i = n[s]), "string" == typeof n[i] && (o[t.camelCase(i)] = n[i]);
                                    else for (i in n) "string" == typeof n[i] && (o[i] = n[i]);
                                    return o;
                           }
                           function i(e, i) {
                                    var s,
                                             o,
                                             a = {};
                                    for (s in i) (o = i[s]), e[s] !== o && (n[s] || ((t.fx.step[s] || !isNaN(parseFloat(o))) && (a[s] = o)));
                                    return a;
                           }
                           var s = ["add", "remove", "toggle"],
                                    n = { border: 1, borderBottom: 1, borderColor: 1, borderLeft: 1, borderRight: 1, borderTop: 1, borderWidth: 1, margin: 1, padding: 1 };
                           t.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function (e, i) {
                                    t.fx.step[i] = function (t) {
                                             (("none" !== t.end && !t.setAttr) || (1 === t.pos && !t.setAttr)) && (u.style(t.elem, i, t.end), (t.setAttr = !0));
                                    };
                           }),
                                    t.fn.addBack ||
                                             (t.fn.addBack = function (t) {
                                                      return this.add(null == t ? this.prevObject : this.prevObject.filter(t));
                                             }),
                                    (t.effects.animateClass = function (n, o, a, r) {
                                             var l = t.speed(o, a, r);
                                             return this.queue(function () {
                                                      var o,
                                                               a = t(this),
                                                               r = a.attr("class") || "",
                                                               h = l.children ? a.find("*").addBack() : a;
                                                      (h = h.map(function () {
                                                               var i = t(this);
                                                               return { el: i, start: e(this) };
                                                      })),
                                                               (o = function () {
                                                                        t.each(s, function (t, e) {
                                                                                 n[e] && a[e + "Class"](n[e]);
                                                                        });
                                                               }),
                                                               o(),
                                                               (h = h.map(function () {
                                                                        return (this.end = e(this.el[0])), (this.diff = i(this.start, this.end)), this;
                                                               })),
                                                               a.attr("class", r),
                                                               (h = h.map(function () {
                                                                        var e = this,
                                                                                 i = t.Deferred(),
                                                                                 s = t.extend({}, l, {
                                                                                          queue: !1,
                                                                                          complete: function () {
                                                                                                   i.resolve(e);
                                                                                          },
                                                                                 });
                                                                        return this.el.animate(this.diff, s), i.promise();
                                                               })),
                                                               t.when.apply(t, h.get()).done(function () {
                                                                        o(),
                                                                                 t.each(arguments, function () {
                                                                                          var e = this.el;
                                                                                          t.each(this.diff, function (t) {
                                                                                                   e.css(t, "");
                                                                                          });
                                                                                 }),
                                                                                 l.complete.call(a[0]);
                                                               });
                                             });
                                    }),
                                    t.fn.extend({
                                             addClass: (function (e) {
                                                      return function (i, s, n, o) {
                                                               return s ? t.effects.animateClass.call(this, { add: i }, s, n, o) : e.apply(this, arguments);
                                                      };
                                             })(t.fn.addClass),
                                             removeClass: (function (e) {
                                                      return function (i, s, n, o) {
                                                               return arguments.length > 1 ? t.effects.animateClass.call(this, { remove: i }, s, n, o) : e.apply(this, arguments);
                                                      };
                                             })(t.fn.removeClass),
                                             toggleClass: (function (e) {
                                                      return function (i, s, n, o, a) {
                                                               return "boolean" == typeof s || void 0 === s
                                                                        ? n
                                                                                 ? t.effects.animateClass.call(this, s ? { add: i } : { remove: i }, n, o, a)
                                                                                 : e.apply(this, arguments)
                                                                        : t.effects.animateClass.call(this, { toggle: i }, s, n, o);
                                                      };
                                             })(t.fn.toggleClass),
                                             switchClass: function (e, i, s, n, o) {
                                                      return t.effects.animateClass.call(this, { add: i, remove: e }, s, n, o);
                                             },
                                    });
                  })(),
                  (function () {
                           function e(e, i, s, n) {
                                    return (
                                             t.isPlainObject(e) && ((i = e), (e = e.effect)),
                                             (e = { effect: e }),
                                             null == i && (i = {}),
                                             t.isFunction(i) && ((n = i), (s = null), (i = {})),
                                             ("number" == typeof i || t.fx.speeds[i]) && ((n = s), (s = i), (i = {})),
                                             t.isFunction(s) && ((n = s), (s = null)),
                                             i && t.extend(e, i),
                                             (s = s || i.duration),
                                             (e.duration = t.fx.off ? 0 : "number" == typeof s ? s : s in t.fx.speeds ? t.fx.speeds[s] : t.fx.speeds._default),
                                             (e.complete = n || i.complete),
                                             e
                                    );
                           }
                           function i(e) {
                                    return !e || "number" == typeof e || t.fx.speeds[e] ? !0 : "string" != typeof e || t.effects.effect[e] ? (t.isFunction(e) ? !0 : "object" != typeof e || e.effect ? !1 : !0) : !0;
                           }
                           function s(t, e) {
                                    var i = e.outerWidth(),
                                             s = e.outerHeight(),
                                             n = /^rect\((-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto),?\s*(-?\d*\.?\d*px|-?\d+%|auto)\)$/,
                                             o = n.exec(t) || ["", 0, i, s, 0];
                                    return { top: parseFloat(o[1]) || 0, right: "auto" === o[2] ? i : parseFloat(o[2]), bottom: "auto" === o[3] ? s : parseFloat(o[3]), left: parseFloat(o[4]) || 0 };
                           }
                           t.expr &&
                                    t.expr.filters &&
                                    t.expr.filters.animated &&
                                    (t.expr.filters.animated = (function (e) {
                                             return function (i) {
                                                      return !!t(i).data(h) || e(i);
                                             };
                                    })(t.expr.filters.animated)),
                                    t.uiBackCompat !== !1 &&
                                             t.extend(t.effects, {
                                                      save: function (t, e) {
                                                               for (var i = 0, s = e.length; s > i; i++) null !== e[i] && t.data(r + e[i], t[0].style[e[i]]);
                                                      },
                                                      restore: function (t, e) {
                                                               for (var i, s = 0, n = e.length; n > s; s++) null !== e[s] && ((i = t.data(r + e[s])), t.css(e[s], i));
                                                      },
                                                      setMode: function (t, e) {
                                                               return "toggle" === e && (e = t.is(":hidden") ? "show" : "hide"), e;
                                                      },
                                                      createWrapper: function (e) {
                                                               if (e.parent().is(".ui-effects-wrapper")) return e.parent();
                                                               var i = { width: e.outerWidth(!0), height: e.outerHeight(!0), float: e.css("float") },
                                                                        s = t("<div></div>").addClass("ui-effects-wrapper").css({ fontSize: "100%", background: "transparent", border: "none", margin: 0, padding: 0 }),
                                                                        n = { width: e.width(), height: e.height() },
                                                                        o = document.activeElement;
                                                               try {
                                                                        o.id;
                                                               } catch (a) {
                                                                        o = document.body;
                                                               }
                                                               return (
                                                                        e.wrap(s),
                                                                        (e[0] === o || t.contains(e[0], o)) && t(o).trigger("focus"),
                                                                        (s = e.parent()),
                                                                        "static" === e.css("position")
                                                                                 ? (s.css({ position: "relative" }), e.css({ position: "relative" }))
                                                                                 : (t.extend(i, { position: e.css("position"), zIndex: e.css("z-index") }),
                                                                                   t.each(["top", "left", "bottom", "right"], function (t, s) {
                                                                                            (i[s] = e.css(s)), isNaN(parseInt(i[s], 10)) && (i[s] = "auto");
                                                                                   }),
                                                                                   e.css({ position: "relative", top: 0, left: 0, right: "auto", bottom: "auto" })),
                                                                        e.css(n),
                                                                        s.css(i).show()
                                                               );
                                                      },
                                                      removeWrapper: function (e) {
                                                               var i = document.activeElement;
                                                               return e.parent().is(".ui-effects-wrapper") && (e.parent().replaceWith(e), (e[0] === i || t.contains(e[0], i)) && t(i).trigger("focus")), e;
                                                      },
                                             }),
                                    t.extend(t.effects, {
                                             version: "1.12.1",
                                             define: function (e, i, s) {
                                                      return s || ((s = i), (i = "effect")), (t.effects.effect[e] = s), (t.effects.effect[e].mode = i), s;
                                             },
                                             scaledDimensions: function (t, e, i) {
                                                      if (0 === e) return { height: 0, width: 0, outerHeight: 0, outerWidth: 0 };
                                                      var s = "horizontal" !== i ? (e || 100) / 100 : 1,
                                                               n = "vertical" !== i ? (e || 100) / 100 : 1;
                                                      return { height: t.height() * n, width: t.width() * s, outerHeight: t.outerHeight() * n, outerWidth: t.outerWidth() * s };
                                             },
                                             clipToBox: function (t) {
                                                      return { width: t.clip.right - t.clip.left, height: t.clip.bottom - t.clip.top, left: t.clip.left, top: t.clip.top };
                                             },
                                             unshift: function (t, e, i) {
                                                      var s = t.queue();
                                                      e > 1 && s.splice.apply(s, [1, 0].concat(s.splice(e, i))), t.dequeue();
                                             },
                                             saveStyle: function (t) {
                                                      t.data(l, t[0].style.cssText);
                                             },
                                             restoreStyle: function (t) {
                                                      (t[0].style.cssText = t.data(l) || ""), t.removeData(l);
                                             },
                                             mode: function (t, e) {
                                                      var i = t.is(":hidden");
                                                      return "toggle" === e && (e = i ? "show" : "hide"), (i ? "hide" === e : "show" === e) && (e = "none"), e;
                                             },
                                             getBaseline: function (t, e) {
                                                      var i, s;
                                                      switch (t[0]) {
                                                               case "top":
                                                                        i = 0;
                                                                        break;
                                                               case "middle":
                                                                        i = 0.5;
                                                                        break;
                                                               case "bottom":
                                                                        i = 1;
                                                                        break;
                                                               default:
                                                                        i = t[0] / e.height;
                                                      }
                                                      switch (t[1]) {
                                                               case "left":
                                                                        s = 0;
                                                                        break;
                                                               case "center":
                                                                        s = 0.5;
                                                                        break;
                                                               case "right":
                                                                        s = 1;
                                                                        break;
                                                               default:
                                                                        s = t[1] / e.width;
                                                      }
                                                      return { x: s, y: i };
                                             },
                                             createPlaceholder: function (e) {
                                                      var i,
                                                               s = e.css("position"),
                                                               n = e.position();
                                                      return (
                                                               e
                                                                        .css({ marginTop: e.css("marginTop"), marginBottom: e.css("marginBottom"), marginLeft: e.css("marginLeft"), marginRight: e.css("marginRight") })
                                                                        .outerWidth(e.outerWidth())
                                                                        .outerHeight(e.outerHeight()),
                                                               /^(static|relative)/.test(s) &&
                                                                        ((s = "absolute"),
                                                                        (i = t("<" + e[0].nodeName + ">")
                                                                                 .insertAfter(e)
                                                                                 .css({
                                                                                          display: /^(inline|ruby)/.test(e.css("display")) ? "inline-block" : "block",
                                                                                          visibility: "hidden",
                                                                                          marginTop: e.css("marginTop"),
                                                                                          marginBottom: e.css("marginBottom"),
                                                                                          marginLeft: e.css("marginLeft"),
                                                                                          marginRight: e.css("marginRight"),
                                                                                          float: e.css("float"),
                                                                                 })
                                                                                 .outerWidth(e.outerWidth())
                                                                                 .outerHeight(e.outerHeight())
                                                                                 .addClass("ui-effects-placeholder")),
                                                                        e.data(r + "placeholder", i)),
                                                               e.css({ position: s, left: n.left, top: n.top }),
                                                               i
                                                      );
                                             },
                                             removePlaceholder: function (t) {
                                                      var e = r + "placeholder",
                                                               i = t.data(e);
                                                      i && (i.remove(), t.removeData(e));
                                             },
                                             cleanUp: function (e) {
                                                      t.effects.restoreStyle(e), t.effects.removePlaceholder(e);
                                             },
                                             setTransition: function (e, i, s, n) {
                                                      return (
                                                               (n = n || {}),
                                                               t.each(i, function (t, i) {
                                                                        var o = e.cssUnit(i);
                                                                        o[0] > 0 && (n[i] = o[0] * s + o[1]);
                                                               }),
                                                               n
                                                      );
                                             },
                                    }),
                                    t.fn.extend({
                                             effect: function () {
                                                      function i(e) {
                                                               function i() {
                                                                        r.removeData(h), t.effects.cleanUp(r), "hide" === s.mode && r.hide(), a();
                                                               }
                                                               function a() {
                                                                        t.isFunction(l) && l.call(r[0]), t.isFunction(e) && e();
                                                               }
                                                               var r = t(this);
                                                               (s.mode = c.shift()),
                                                                        t.uiBackCompat === !1 || o
                                                                                 ? "none" === s.mode
                                                                                          ? (r[u](), a())
                                                                                          : n.call(r[0], s, i)
                                                                                 : (r.is(":hidden") ? "hide" === u : "show" === u)
                                                                                 ? (r[u](), a())
                                                                                 : n.call(r[0], s, a);
                                                      }
                                                      var s = e.apply(this, arguments),
                                                               n = t.effects.effect[s.effect],
                                                               o = n.mode,
                                                               a = s.queue,
                                                               r = a || "fx",
                                                               l = s.complete,
                                                               u = s.mode,
                                                               c = [],
                                                               d = function (e) {
                                                                        var i = t(this),
                                                                                 s = t.effects.mode(i, u) || o;
                                                                        i.data(h, !0), c.push(s), o && ("show" === s || (s === o && "hide" === s)) && i.show(), (o && "none" === s) || t.effects.saveStyle(i), t.isFunction(e) && e();
                                                               };
                                                      return t.fx.off || !n
                                                               ? u
                                                                        ? this[u](s.duration, l)
                                                                        : this.each(function () {
                                                                                   l && l.call(this);
                                                                          })
                                                               : a === !1
                                                               ? this.each(d).each(i)
                                                               : this.queue(r, d).queue(r, i);
                                             },
                                             show: (function (t) {
                                                      return function (s) {
                                                               if (i(s)) return t.apply(this, arguments);
                                                               var n = e.apply(this, arguments);
                                                               return (n.mode = "show"), this.effect.call(this, n);
                                                      };
                                             })(t.fn.show),
                                             hide: (function (t) {
                                                      return function (s) {
                                                               if (i(s)) return t.apply(this, arguments);
                                                               var n = e.apply(this, arguments);
                                                               return (n.mode = "hide"), this.effect.call(this, n);
                                                      };
                                             })(t.fn.hide),
                                             toggle: (function (t) {
                                                      return function (s) {
                                                               if (i(s) || "boolean" == typeof s) return t.apply(this, arguments);
                                                               var n = e.apply(this, arguments);
                                                               return (n.mode = "toggle"), this.effect.call(this, n);
                                                      };
                                             })(t.fn.toggle),
                                             cssUnit: function (e) {
                                                      var i = this.css(e),
                                                               s = [];
                                                      return (
                                                               t.each(["em", "px", "%", "pt"], function (t, e) {
                                                                        i.indexOf(e) > 0 && (s = [parseFloat(i), e]);
                                                               }),
                                                               s
                                                      );
                                             },
                                             cssClip: function (t) {
                                                      return t ? this.css("clip", "rect(" + t.top + "px " + t.right + "px " + t.bottom + "px " + t.left + "px)") : s(this.css("clip"), this);
                                             },
                                             transfer: function (e, i) {
                                                      var s = t(this),
                                                               n = t(e.to),
                                                               o = "fixed" === n.css("position"),
                                                               a = t("body"),
                                                               r = o ? a.scrollTop() : 0,
                                                               l = o ? a.scrollLeft() : 0,
                                                               h = n.offset(),
                                                               u = { top: h.top - r, left: h.left - l, height: n.innerHeight(), width: n.innerWidth() },
                                                               c = s.offset(),
                                                               d = t("<div class='ui-effects-transfer'></div>")
                                                                        .appendTo("body")
                                                                        .addClass(e.className)
                                                                        .css({ top: c.top - r, left: c.left - l, height: s.innerHeight(), width: s.innerWidth(), position: o ? "fixed" : "absolute" })
                                                                        .animate(u, e.duration, e.easing, function () {
                                                                                 d.remove(), t.isFunction(i) && i();
                                                                        });
                                             },
                                    }),
                                    (t.fx.step.clip = function (e) {
                                             e.clipInit || ((e.start = t(e.elem).cssClip()), "string" == typeof e.end && (e.end = s(e.end, e.elem)), (e.clipInit = !0)),
                                                      t(e.elem).cssClip({
                                                               top: e.pos * (e.end.top - e.start.top) + e.start.top,
                                                               right: e.pos * (e.end.right - e.start.right) + e.start.right,
                                                               bottom: e.pos * (e.end.bottom - e.start.bottom) + e.start.bottom,
                                                               left: e.pos * (e.end.left - e.start.left) + e.start.left,
                                                      });
                                    });
                  })(),
                  (function () {
                           var e = {};
                           t.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function (t, i) {
                                    e[i] = function (e) {
                                             return Math.pow(e, t + 2);
                                    };
                           }),
                                    t.extend(e, {
                                             Sine: function (t) {
                                                      return 1 - Math.cos((t * Math.PI) / 2);
                                             },
                                             Circ: function (t) {
                                                      return 1 - Math.sqrt(1 - t * t);
                                             },
                                             Elastic: function (t) {
                                                      return 0 === t || 1 === t ? t : -Math.pow(2, 8 * (t - 1)) * Math.sin(((80 * (t - 1) - 7.5) * Math.PI) / 15);
                                             },
                                             Back: function (t) {
                                                      return t * t * (3 * t - 2);
                                             },
                                             Bounce: function (t) {
                                                      for (var e, i = 4; ((e = Math.pow(2, --i)) - 1) / 11 > t; );
                                                      return 1 / Math.pow(4, 3 - i) - 7.5625 * Math.pow((3 * e - 2) / 22 - t, 2);
                                             },
                                    }),
                                    t.each(e, function (e, i) {
                                             (t.easing["easeIn" + e] = i),
                                                      (t.easing["easeOut" + e] = function (t) {
                                                               return 1 - i(1 - t);
                                                      }),
                                                      (t.easing["easeInOut" + e] = function (t) {
                                                               return 0.5 > t ? i(2 * t) / 2 : 1 - i(-2 * t + 2) / 2;
                                                      });
                                    });
                  })();
         var c = t.effects;
         t.effects.define("blind", "hide", function (e, i) {
                  var s = { up: ["bottom", "top"], vertical: ["bottom", "top"], down: ["top", "bottom"], left: ["right", "left"], horizontal: ["right", "left"], right: ["left", "right"] },
                           n = t(this),
                           o = e.direction || "up",
                           a = n.cssClip(),
                           r = { clip: t.extend({}, a) },
                           l = t.effects.createPlaceholder(n);
                  (r.clip[s[o][0]] = r.clip[s[o][1]]),
                           "show" === e.mode && (n.cssClip(r.clip), l && l.css(t.effects.clipToBox(r)), (r.clip = a)),
                           l && l.animate(t.effects.clipToBox(r), e.duration, e.easing),
                           n.animate(r, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
         }),
                  t.effects.define("bounce", function (e, i) {
                           var s,
                                    n,
                                    o,
                                    a = t(this),
                                    r = e.mode,
                                    l = "hide" === r,
                                    h = "show" === r,
                                    u = e.direction || "up",
                                    c = e.distance,
                                    d = e.times || 5,
                                    p = 2 * d + (h || l ? 1 : 0),
                                    f = e.duration / p,
                                    m = e.easing,
                                    g = "up" === u || "down" === u ? "top" : "left",
                                    _ = "up" === u || "left" === u,
                                    v = 0,
                                    b = a.queue().length;
                           for (
                                    t.effects.createPlaceholder(a),
                                             o = a.css(g),
                                             c || (c = a["top" === g ? "outerHeight" : "outerWidth"]() / 3),
                                             h &&
                                                      ((n = { opacity: 1 }),
                                                      (n[g] = o),
                                                      a
                                                               .css("opacity", 0)
                                                               .css(g, _ ? 2 * -c : 2 * c)
                                                               .animate(n, f, m)),
                                             l && (c /= Math.pow(2, d - 1)),
                                             n = {},
                                             n[g] = o;
                                    d > v;
                                    v++
                           )
                                    (s = {}), (s[g] = (_ ? "-=" : "+=") + c), a.animate(s, f, m).animate(n, f, m), (c = l ? 2 * c : c / 2);
                           l && ((s = { opacity: 0 }), (s[g] = (_ ? "-=" : "+=") + c), a.animate(s, f, m)), a.queue(i), t.effects.unshift(a, b, p + 1);
                  }),
                  t.effects.define("clip", "hide", function (e, i) {
                           var s,
                                    n = {},
                                    o = t(this),
                                    a = e.direction || "vertical",
                                    r = "both" === a,
                                    l = r || "horizontal" === a,
                                    h = r || "vertical" === a;
                           (s = o.cssClip()),
                                    (n.clip = { top: h ? (s.bottom - s.top) / 2 : s.top, right: l ? (s.right - s.left) / 2 : s.right, bottom: h ? (s.bottom - s.top) / 2 : s.bottom, left: l ? (s.right - s.left) / 2 : s.left }),
                                    t.effects.createPlaceholder(o),
                                    "show" === e.mode && (o.cssClip(n.clip), (n.clip = s)),
                                    o.animate(n, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
                  }),
                  t.effects.define("drop", "hide", function (e, i) {
                           var s,
                                    n = t(this),
                                    o = e.mode,
                                    a = "show" === o,
                                    r = e.direction || "left",
                                    l = "up" === r || "down" === r ? "top" : "left",
                                    h = "up" === r || "left" === r ? "-=" : "+=",
                                    u = "+=" === h ? "-=" : "+=",
                                    c = { opacity: 0 };
                           t.effects.createPlaceholder(n),
                                    (s = e.distance || n["top" === l ? "outerHeight" : "outerWidth"](!0) / 2),
                                    (c[l] = h + s),
                                    a && (n.css(c), (c[l] = u + s), (c.opacity = 1)),
                                    n.animate(c, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
                  }),
                  t.effects.define("explode", "hide", function (e, i) {
                           function s() {
                                    b.push(this), b.length === c * d && n();
                           }
                           function n() {
                                    p.css({ visibility: "visible" }), t(b).remove(), i();
                           }
                           var o,
                                    a,
                                    r,
                                    l,
                                    h,
                                    u,
                                    c = e.pieces ? Math.round(Math.sqrt(e.pieces)) : 3,
                                    d = c,
                                    p = t(this),
                                    f = e.mode,
                                    m = "show" === f,
                                    g = p.show().css("visibility", "hidden").offset(),
                                    _ = Math.ceil(p.outerWidth() / d),
                                    v = Math.ceil(p.outerHeight() / c),
                                    b = [];
                           for (o = 0; c > o; o++)
                                    for (l = g.top + o * v, u = o - (c - 1) / 2, a = 0; d > a; a++)
                                             (r = g.left + a * _),
                                                      (h = a - (d - 1) / 2),
                                                      p
                                                               .clone()
                                                               .appendTo("body")
                                                               .wrap("<div></div>")
                                                               .css({ position: "absolute", visibility: "visible", left: -a * _, top: -o * v })
                                                               .parent()
                                                               .addClass("ui-effects-explode")
                                                               .css({ position: "absolute", overflow: "hidden", width: _, height: v, left: r + (m ? h * _ : 0), top: l + (m ? u * v : 0), opacity: m ? 0 : 1 })
                                                               .animate({ left: r + (m ? 0 : h * _), top: l + (m ? 0 : u * v), opacity: m ? 1 : 0 }, e.duration || 500, e.easing, s);
                  }),
                  t.effects.define("fade", "toggle", function (e, i) {
                           var s = "show" === e.mode;
                           t(this)
                                    .css("opacity", s ? 0 : 1)
                                    .animate({ opacity: s ? 1 : 0 }, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
                  }),
                  t.effects.define("fold", "hide", function (e, i) {
                           var s = t(this),
                                    n = e.mode,
                                    o = "show" === n,
                                    a = "hide" === n,
                                    r = e.size || 15,
                                    l = /([0-9]+)%/.exec(r),
                                    h = !!e.horizFirst,
                                    u = h ? ["right", "bottom"] : ["bottom", "right"],
                                    c = e.duration / 2,
                                    d = t.effects.createPlaceholder(s),
                                    p = s.cssClip(),
                                    f = { clip: t.extend({}, p) },
                                    m = { clip: t.extend({}, p) },
                                    g = [p[u[0]], p[u[1]]],
                                    _ = s.queue().length;
                           l && (r = (parseInt(l[1], 10) / 100) * g[a ? 0 : 1]),
                                    (f.clip[u[0]] = r),
                                    (m.clip[u[0]] = r),
                                    (m.clip[u[1]] = 0),
                                    o && (s.cssClip(m.clip), d && d.css(t.effects.clipToBox(m)), (m.clip = p)),
                                    s
                                             .queue(function (i) {
                                                      d && d.animate(t.effects.clipToBox(f), c, e.easing).animate(t.effects.clipToBox(m), c, e.easing), i();
                                             })
                                             .animate(f, c, e.easing)
                                             .animate(m, c, e.easing)
                                             .queue(i),
                                    t.effects.unshift(s, _, 4);
                  }),
                  t.effects.define("highlight", "show", function (e, i) {
                           var s = t(this),
                                    n = { backgroundColor: s.css("backgroundColor") };
                           "hide" === e.mode && (n.opacity = 0),
                                    t.effects.saveStyle(s),
                                    s.css({ backgroundImage: "none", backgroundColor: e.color || "#ffff99" }).animate(n, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
                  }),
                  t.effects.define("size", function (e, i) {
                           var s,
                                    n,
                                    o,
                                    a = t(this),
                                    r = ["fontSize"],
                                    l = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
                                    h = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
                                    u = e.mode,
                                    c = "effect" !== u,
                                    d = e.scale || "both",
                                    p = e.origin || ["middle", "center"],
                                    f = a.css("position"),
                                    m = a.position(),
                                    g = t.effects.scaledDimensions(a),
                                    _ = e.from || g,
                                    v = e.to || t.effects.scaledDimensions(a, 0);
                           t.effects.createPlaceholder(a),
                                    "show" === u && ((o = _), (_ = v), (v = o)),
                                    (n = { from: { y: _.height / g.height, x: _.width / g.width }, to: { y: v.height / g.height, x: v.width / g.width } }),
                                    ("box" === d || "both" === d) &&
                                             (n.from.y !== n.to.y && ((_ = t.effects.setTransition(a, l, n.from.y, _)), (v = t.effects.setTransition(a, l, n.to.y, v))),
                                             n.from.x !== n.to.x && ((_ = t.effects.setTransition(a, h, n.from.x, _)), (v = t.effects.setTransition(a, h, n.to.x, v)))),
                                    ("content" === d || "both" === d) && n.from.y !== n.to.y && ((_ = t.effects.setTransition(a, r, n.from.y, _)), (v = t.effects.setTransition(a, r, n.to.y, v))),
                                    p &&
                                             ((s = t.effects.getBaseline(p, g)),
                                             (_.top = (g.outerHeight - _.outerHeight) * s.y + m.top),
                                             (_.left = (g.outerWidth - _.outerWidth) * s.x + m.left),
                                             (v.top = (g.outerHeight - v.outerHeight) * s.y + m.top),
                                             (v.left = (g.outerWidth - v.outerWidth) * s.x + m.left)),
                                    a.css(_),
                                    ("content" === d || "both" === d) &&
                                             ((l = l.concat(["marginTop", "marginBottom"]).concat(r)),
                                             (h = h.concat(["marginLeft", "marginRight"])),
                                             a.find("*[width]").each(function () {
                                                      var i = t(this),
                                                               s = t.effects.scaledDimensions(i),
                                                               o = { height: s.height * n.from.y, width: s.width * n.from.x, outerHeight: s.outerHeight * n.from.y, outerWidth: s.outerWidth * n.from.x },
                                                               a = { height: s.height * n.to.y, width: s.width * n.to.x, outerHeight: s.height * n.to.y, outerWidth: s.width * n.to.x };
                                                      n.from.y !== n.to.y && ((o = t.effects.setTransition(i, l, n.from.y, o)), (a = t.effects.setTransition(i, l, n.to.y, a))),
                                                               n.from.x !== n.to.x && ((o = t.effects.setTransition(i, h, n.from.x, o)), (a = t.effects.setTransition(i, h, n.to.x, a))),
                                                               c && t.effects.saveStyle(i),
                                                               i.css(o),
                                                               i.animate(a, e.duration, e.easing, function () {
                                                                        c && t.effects.restoreStyle(i);
                                                               });
                                             })),
                                    a.animate(v, {
                                             queue: !1,
                                             duration: e.duration,
                                             easing: e.easing,
                                             complete: function () {
                                                      var e = a.offset();
                                                      0 === v.opacity && a.css("opacity", _.opacity), c || (a.css("position", "static" === f ? "relative" : f).offset(e), t.effects.saveStyle(a)), i();
                                             },
                                    });
                  }),
                  t.effects.define("scale", function (e, i) {
                           var s = t(this),
                                    n = e.mode,
                                    o = parseInt(e.percent, 10) || (0 === parseInt(e.percent, 10) ? 0 : "effect" !== n ? 0 : 100),
                                    a = t.extend(!0, { from: t.effects.scaledDimensions(s), to: t.effects.scaledDimensions(s, o, e.direction || "both"), origin: e.origin || ["middle", "center"] }, e);
                           e.fade && ((a.from.opacity = 1), (a.to.opacity = 0)), t.effects.effect.size.call(this, a, i);
                  }),
                  t.effects.define("puff", "hide", function (e, i) {
                           var s = t.extend(!0, {}, e, { fade: !0, percent: parseInt(e.percent, 10) || 150 });
                           t.effects.effect.scale.call(this, s, i);
                  }),
                  t.effects.define("pulsate", "show", function (e, i) {
                           var s = t(this),
                                    n = e.mode,
                                    o = "show" === n,
                                    a = "hide" === n,
                                    r = o || a,
                                    l = 2 * (e.times || 5) + (r ? 1 : 0),
                                    h = e.duration / l,
                                    u = 0,
                                    c = 1,
                                    d = s.queue().length;
                           for ((o || !s.is(":visible")) && (s.css("opacity", 0).show(), (u = 1)); l > c; c++) s.animate({ opacity: u }, h, e.easing), (u = 1 - u);
                           s.animate({ opacity: u }, h, e.easing), s.queue(i), t.effects.unshift(s, d, l + 1);
                  }),
                  t.effects.define("shake", function (e, i) {
                           var s = 1,
                                    n = t(this),
                                    o = e.direction || "left",
                                    a = e.distance || 20,
                                    r = e.times || 3,
                                    l = 2 * r + 1,
                                    h = Math.round(e.duration / l),
                                    u = "up" === o || "down" === o ? "top" : "left",
                                    c = "up" === o || "left" === o,
                                    d = {},
                                    p = {},
                                    f = {},
                                    m = n.queue().length;
                           for (t.effects.createPlaceholder(n), d[u] = (c ? "-=" : "+=") + a, p[u] = (c ? "+=" : "-=") + 2 * a, f[u] = (c ? "-=" : "+=") + 2 * a, n.animate(d, h, e.easing); r > s; s++)
                                    n.animate(p, h, e.easing).animate(f, h, e.easing);
                           n
                                    .animate(p, h, e.easing)
                                    .animate(d, h / 2, e.easing)
                                    .queue(i),
                                    t.effects.unshift(n, m, l + 1);
                  }),
                  t.effects.define("slide", "show", function (e, i) {
                           var s,
                                    n,
                                    o = t(this),
                                    a = { up: ["bottom", "top"], down: ["top", "bottom"], left: ["right", "left"], right: ["left", "right"] },
                                    r = e.mode,
                                    l = e.direction || "left",
                                    h = "up" === l || "down" === l ? "top" : "left",
                                    u = "up" === l || "left" === l,
                                    c = e.distance || o["top" === h ? "outerHeight" : "outerWidth"](!0),
                                    d = {};
                           t.effects.createPlaceholder(o),
                                    (s = o.cssClip()),
                                    (n = o.position()[h]),
                                    (d[h] = (u ? -1 : 1) * c + n),
                                    (d.clip = o.cssClip()),
                                    (d.clip[a[l][1]] = d.clip[a[l][0]]),
                                    "show" === r && (o.cssClip(d.clip), o.css(h, d[h]), (d.clip = s), (d[h] = n)),
                                    o.animate(d, { queue: !1, duration: e.duration, easing: e.easing, complete: i });
                  });
         var c;
         t.uiBackCompat !== !1 &&
                  (c = t.effects.define("transfer", function (e, i) {
                           t(this).transfer(e, i);
                  }));
});
(function (d) {
         var k = 0;
         d.widget("ech.multiselect", {
                  options: {
                           header: !0,
                           height: 105,
                           minWidth: 46,
                           classes: "",
                           checkAllText: "Check all",
                           uncheckAllText: "Uncheck all",
                           noneSelectedText: "Select options",
                           selectedText: "# seleccionada/s",
                           selectedList: 0,
                           show: null,
                           hide: null,
                           autoOpen: !1,
                           multiple: !0,
                           position: {},
                  },
                  _create: function () {
                           var a = this.element.hide(),
                                    b = this.options;
                           this.speed = d.fx.speeds._default;
                           this._isOpen = !1;
                           a = (this.button = d('<button type="button"><span class="ui-icon ui-icon-triangle-2-n-s"></span></button>'))
                                    .addClass("ui-multiselect ui-widget ui-state-default ui-corner-all")
                                    .addClass(b.classes)
                                    .attr({ title: a.attr("title"), "aria-haspopup": !0, tabIndex: a.attr("tabIndex") })
                                    .insertAfter(a);
                           (this.buttonlabel = d("<span />")).html(b.noneSelectedText).appendTo(a);
                           var a = (this.menu = d("<div />")).addClass("ui-multiselect-menu ui-widget ui-widget-content ui-corner-all").addClass(b.classes).appendTo(document.body),
                                    c = (this.header = d("<div />")).addClass("ui-widget-header ui-corner-all ui-multiselect-header ui-helper-clearfix").appendTo(a);
                           (this.headerLinkContainer = d("<ul />"))
                                    .addClass("ui-helper-reset")
                                    .html(function () {
                                             return !0 === b.header
                                                      ? '<li><a class="ui-multiselect-all" href="#"><span class="ui-icon ui-icon-check"></span><span>' +
                                                                 b.checkAllText +
                                                                 '</span></a></li><li><a class="ui-multiselect-none" href="#"><span class="ui-icon ui-icon-closethick"></span><span>' +
                                                                 b.uncheckAllText +
                                                                 "</span></a></li>"
                                                      : "string" === typeof b.header
                                                      ? "<li>" + b.header + "</li>"
                                                      : "";
                                    })
                                    .append('<li class="ui-multiselect-close"><a href="#" class="ui-multiselect-close"><span class="ui-icon ui-icon-circle-close"></span></a></li>')
                                    .appendTo(c);
                           (this.checkboxContainer = d("<ul />")).addClass("ui-multiselect-checkboxes ui-helper-reset").appendTo(a);
                           this._bindEvents();
                           this.refresh(!0);
                           b.multiple || a.addClass("ui-multiselect-single");
                  },
                  _init: function () {
                           !1 === this.options.header && this.header.hide();
                           this.options.multiple || this.headerLinkContainer.find(".ui-multiselect-all, .ui-multiselect-none").hide();
                           this.options.autoOpen && this.open();
                           this.element.is(":disabled") && this.disable();
                  },
                  refresh: function (a) {
                           var b = this.element,
                                    c = this.options,
                                    f = this.menu,
                                    h = this.checkboxContainer,
                                    g = [],
                                    e = "",
                                    i = b.attr("id") || k++;
                           b.find("option").each(function (b) {
                                    d(this);
                                    var a = this.parentNode,
                                             color = this.getAttribute("color"),
                                             image = this.getAttribute("image"),
                                             f = this.innerHTML,
                                             h = this.title,
                                             k = this.value,
                                             b = "ui-multiselect-" + (this.id || i + "-option-" + b),
                                             l = this.disabled,
                                             n = this.selected,
                                             m = ["ui-corner-all"],
                                             o = (l ? "ui-multiselect-disabled " : " ") + this.className,
                                             j;
                                    "OPTGROUP" === a.tagName && ((j = a.getAttribute("label")), -1 === d.inArray(j, g) && ((e += '<li class="ui-multiselect-optgroup-label ' + a.className + '"><a href="#">' + j + "</a></li>"), g.push(j)));
                                    l && m.push("ui-state-disabled");
                                    n && !c.multiple && m.push("ui-state-active");
                                    e += '<li class="' + o + '">';
                                    e += '<label for="' + b + '" title="' + h + '" class="' + m.join(" ") + '">';
                                    e += '<input id="' + b + '" name="multiselect_' + i + '" type="' + (c.multiple ? "checkbox" : "radio") + '" value="' + k + '" title="' + f + '"';
                                    n && ((e += ' checked="checked"'), (e += ' aria-selected="true"'));
                                    l && ((e += ' disabled="disabled"'), (e += ' aria-disabled="true"'));
                                    e += " /><span>";
                                    if (image != null) {
                                             if (color != "false") {
                                                      e += '<i style="background-color:' + color + '" class="multimgSelktr"></i>';
                                             } else {
                                                      e += '<img src="' + image + '" class="multimgSelktr">';
                                             }
                                    }
                                    e += f + "</span></label></li>";
                           });
                           h.html(e);
                           this.labels = f.find("label");
                           this.inputs = this.labels.children("input");
                           this._setButtonWidth();
                           this._setMenuWidth();
                           this.button[0].defaultValue = this.update();
                           a || this._trigger("refresh");
                  },
                  update: function () {
                           var o = this.options,
                                    $inputs = this.inputs,
                                    $checked = $inputs.filter(":checked"),
                                    numChecked = $checked.length,
                                    value;
                           if (numChecked === 0) {
                                    value = o.noneSelectedText;
                           } else {
                                    value = $checked
                                             .map(function () {
                                                      return $(this).next().html();
                                             })
                                             .get()
                                             .join(", ");
                                    if ($.isFunction(o.selectedText)) {
                                             value = o.selectedText.call(this, numChecked, $inputs.length, $checked.get());
                                    } else if (/\d/.test(o.selectedList) && o.selectedList > 0 && numChecked <= o.selectedList) {
                                             value = $checked
                                                      .map(function () {
                                                               return $(this).next().html();
                                                      })
                                                      .get()
                                                      .join(", ");
                                    } else {
                                             value = o.selectedText.replace("#", numChecked).replace("#", $inputs.length);
                                    }
                           }
                           this.buttonlabel.html(value);
                           return value;
                  },
                  _bindEvents: function () {
                           function a() {
                                    b[b._isOpen ? "close" : "open"]();
                                    return !1;
                           }
                           var b = this,
                                    c = this.button;
                           c.find("span").bind("click.multiselect", a);
                           c.bind({
                                    click: a,
                                    keypress: function (a) {
                                             switch (a.which) {
                                                      case 27:
                                                      case 38:
                                                      case 37:
                                                               b.close();
                                                               break;
                                                      case 39:
                                                      case 40:
                                                               b.open();
                                             }
                                    },
                                    mouseenter: function () {
                                             c.hasClass("ui-state-disabled") || d(this).addClass("ui-state-hover");
                                    },
                                    mouseleave: function () {
                                             d(this).removeClass("ui-state-hover");
                                    },
                                    focus: function () {
                                             c.hasClass("ui-state-disabled") || d(this).addClass("ui-state-focus");
                                    },
                                    blur: function () {
                                             d(this).removeClass("ui-state-focus");
                                    },
                           });
                           this.header.delegate("a", "click.multiselect", function (a) {
                                    if (d(this).hasClass("ui-multiselect-close")) b.close();
                                    else b[d(this).hasClass("ui-multiselect-all") ? "checkAll" : "uncheckAll"]();
                                    a.preventDefault();
                           });
                           this.menu
                                    .delegate("li.ui-multiselect-optgroup-label a", "click.multiselect", function (a) {
                                             a.preventDefault();
                                             var c = d(this),
                                                      g = c.parent().nextUntil("li.ui-multiselect-optgroup-label").find("input:visible:not(:disabled)"),
                                                      e = g.get(),
                                                      c = c.parent().text();
                                             !1 !== b._trigger("beforeoptgrouptoggle", a, { inputs: e, label: c }) &&
                                                      (b._toggleChecked(g.filter(":checked").length !== g.length, g), b._trigger("optgrouptoggle", a, { inputs: e, label: c, checked: e[0].checked }));
                                    })
                                    .delegate("label", "mouseenter.multiselect", function () {
                                             d(this).hasClass("ui-state-disabled") || (b.labels.removeClass("ui-state-hover"), d(this).addClass("ui-state-hover").find("input").focus());
                                    })
                                    .delegate("label", "keydown.multiselect", function (a) {
                                             a.preventDefault();
                                             switch (a.which) {
                                                      case 9:
                                                      case 27:
                                                               b.close();
                                                               break;
                                                      case 38:
                                                      case 40:
                                                      case 37:
                                                      case 39:
                                                               b._traverse(a.which, this);
                                                               break;
                                                      case 13:
                                                               d(this).find("input")[0].click();
                                             }
                                    })
                                    .delegate('input[type="checkbox"], input[type="radio"]', "click.multiselect", function (a) {
                                             var c = d(this),
                                                      g = this.value,
                                                      e = this.checked,
                                                      i = b.element.find("option");
                                             this.disabled || !1 === b._trigger("click", a, { value: g, text: this.title, checked: e })
                                                      ? a.preventDefault()
                                                      : (c.focus(),
                                                        c.attr("aria-selected", e),
                                                        i.each(function () {
                                                                 this.value === g ? (this.selected = e) : b.options.multiple || (this.selected = !1);
                                                        }),
                                                        b.options.multiple || (b.labels.removeClass("ui-state-active"), c.closest("label").toggleClass("ui-state-active", e), b.close()),
                                                        b.element.trigger("change"),
                                                        setTimeout(d.proxy(b.update, b), 10));
                                    });
                           d(document).bind("mousedown.multiselect", function (a) {
                                    b._isOpen && !d.contains(b.menu[0], a.target) && !d.contains(b.button[0], a.target) && a.target !== b.button[0] && b.close();
                           });
                           d(this.element[0].form).bind("reset.multiselect", function () {
                                    setTimeout(d.proxy(b.refresh, b), 10);
                           });
                  },
                  _setButtonWidth: function () {
                           var a = this.element.outerWidth(),
                                    b = this.options;
                           /\d/.test(b.minWidth) && a < b.minWidth && (a = b.minWidth);
                           this.button.width(a);
                  },
                  _setMenuWidth: function () {
                           var a = this.menu,
                                    b = this.button.outerWidth() - parseInt(a.css("padding-left"), 10) - parseInt(a.css("padding-right"), 10) - parseInt(a.css("border-right-width"), 10) - parseInt(a.css("border-left-width"), 10);
                           a.width(b || this.button.outerWidth());
                  },
                  _traverse: function (a, b) {
                           var c = d(b),
                                    f = 38 === a || 37 === a,
                                    c = c.parent()[f ? "prevAll" : "nextAll"]("li:not(.ui-multiselect-disabled, .ui-multiselect-optgroup-label)")[f ? "last" : "first"]();
                           c.length ? c.find("label").trigger("mouseover") : ((c = this.menu.find("ul").last()), this.menu.find("label")[f ? "last" : "first"]().trigger("mouseover"), c.scrollTop(f ? c.height() : 0));
                  },
                  _toggleState: function (a, b) {
                           return function () {
                                    this.disabled || (this[a] = b);
                                    b ? this.setAttribute("aria-selected", !0) : this.removeAttribute("aria-selected");
                           };
                  },
                  _toggleChecked: function (a, b) {
                           var c = b && b.length ? b : this.inputs,
                                    f = this;
                           c.each(this._toggleState("checked", a));
                           c.eq(0).focus();
                           this.update();
                           var h = c
                                    .map(function () {
                                             return this.value;
                                    })
                                    .get();
                           this.element.find("option").each(function () {
                                    !this.disabled && -1 < d.inArray(this.value, h) && f._toggleState("selected", a).call(this);
                           });
                           c.length && this.element.trigger("change");
                  },
                  _toggleDisabled: function (a) {
                           this.button.attr({ disabled: a, "aria-disabled": a })[a ? "addClass" : "removeClass"]("ui-state-disabled");
                           var b = this.menu.find("input"),
                                    b = a
                                             ? b.filter(":enabled").data("ech-multiselect-disabled", !0)
                                             : b
                                                        .filter(function () {
                                                                 return !0 === d.data(this, "ech-multiselect-disabled");
                                                        })
                                                        .removeData("ech-multiselect-disabled");
                           b.attr({ disabled: a, "arial-disabled": a }).parent()[a ? "addClass" : "removeClass"]("ui-state-disabled");
                           this.element.attr({ disabled: a, "aria-disabled": a });
                  },
                  open: function () {
                           var a = this.button,
                                    b = this.menu,
                                    c = this.speed,
                                    f = this.options,
                                    h = [];
                           if (!(!1 === this._trigger("beforeopen") || a.hasClass("ui-state-disabled") || this._isOpen)) {
                                    var g = b.find("ul").last(),
                                             e = f.show,
                                             i = a.offset();
                                    d.isArray(f.show) && ((e = f.show[0]), (c = f.show[1] || this.speed));
                                    e && (h = [e, c]);
                                    g.scrollTop(0).height(f.height);
                                    d.ui.position && !d.isEmptyObject(f.position) ? ((f.position.of = f.position.of || a), b.show().position(f.position).hide()) : b.css({ top: i.top + a.outerHeight(), left: i.left });
                                    d.fn.show.apply(b, h);
                                    this.labels.eq(0).trigger("mouseover").trigger("mouseenter").find("input").trigger("focus");
                                    a.addClass("ui-state-active");
                                    this._isOpen = !0;
                                    this._trigger("open");
                           }
                  },
                  close: function () {
                           if (!1 !== this._trigger("beforeclose")) {
                                    var a = this.options,
                                             b = a.hide,
                                             c = this.speed,
                                             f = [];
                                    d.isArray(a.hide) && ((b = a.hide[0]), (c = a.hide[1] || this.speed));
                                    b && (f = [b, c]);
                                    d.fn.hide.apply(this.menu, f);
                                    this.button.removeClass("ui-state-active").trigger("blur").trigger("mouseleave");
                                    this._isOpen = !1;
                                    this._trigger("close");
                           }
                  },
                  enable: function () {
                           this._toggleDisabled(!1);
                  },
                  disable: function () {
                           this._toggleDisabled(!0);
                  },
                  checkAll: function () {
                           this._toggleChecked(!0);
                           this._trigger("checkAll");
                  },
                  uncheckAll: function () {
                           this._toggleChecked(!1);
                           this._trigger("uncheckAll");
                  },
                  getChecked: function () {
                           return this.menu.find("input").filter(":checked");
                  },
                  destroy: function () {
                           d.Widget.prototype.destroy.call(this);
                           this.button.remove();
                           this.menu.remove();
                           this.element.show();
                           return this;
                  },
                  isOpen: function () {
                           return this._isOpen;
                  },
                  widget: function () {
                           return this.menu;
                  },
                  getButton: function () {
                           return this.button;
                  },
                  _setOption: function (a, b) {
                           var c = this.menu;
                           switch (a) {
                                    case "header":
                                             c.find("div.ui-multiselect-header")[b ? "show" : "hide"]();
                                             break;
                                    case "checkAllText":
                                             c.find("a.ui-multiselect-all span").eq(-1).text(b);
                                             break;
                                    case "uncheckAllText":
                                             c.find("a.ui-multiselect-none span").eq(-1).text(b);
                                             break;
                                    case "height":
                                             c.find("ul").last().height(parseInt(b, 10));
                                             break;
                                    case "minWidth":
                                             this.options[a] = parseInt(b, 10);
                                             this._setButtonWidth();
                                             this._setMenuWidth();
                                             break;
                                    case "selectedText":
                                    case "selectedList":
                                    case "noneSelectedText":
                                             this.options[a] = b;
                                             this.update();
                                             break;
                                    case "classes":
                                             c.add(this.button).removeClass(this.options.classes).addClass(b);
                                             break;
                                    case "multiple":
                                             c.toggleClass("ui-multiselect-single", !b), (this.options.multiple = b), (this.element[0].multiple = b), this.refresh();
                           }
                           d.Widget.prototype._setOption.apply(this, arguments);
                  },
         });
})(jQuery);
!(function (n) {
         "function" == typeof define && define.amd
                  ? define(["jquery"], n)
                  : "object" == typeof module && module.exports
                  ? (module.exports = function (e, t) {
                             return void 0 === t && (t = "undefined" != typeof window ? require("jquery") : require("jquery")(e)), n(t), t;
                    })
                  : n(jQuery);
})(function (u) {
         var e = (function () {
                           if (u && u.fn && u.fn.select2 && u.fn.select2.amd) var e = u.fn.select2.amd;
                           var t, n, r, h, o, s, f, g, m, v, y, _, i, a, w;
                           function b(e, t) {
                                    return i.call(e, t);
                           }
                           function l(e, t) {
                                    var n,
                                             r,
                                             i,
                                             o,
                                             s,
                                             a,
                                             l,
                                             c,
                                             u,
                                             d,
                                             p,
                                             h = t && t.split("/"),
                                             f = y.map,
                                             g = (f && f["*"]) || {};
                                    if (e) {
                                             for (
                                                      s = (e = e.split("/")).length - 1, y.nodeIdCompat && w.test(e[s]) && (e[s] = e[s].replace(w, "")), "." === e[0].charAt(0) && h && (e = h.slice(0, h.length - 1).concat(e)), u = 0;
                                                      u < e.length;
                                                      u++
                                             )
                                                      if ("." === (p = e[u])) e.splice(u, 1), (u -= 1);
                                                      else if (".." === p) {
                                                               if (0 === u || (1 === u && ".." === e[2]) || ".." === e[u - 1]) continue;
                                                               0 < u && (e.splice(u - 1, 2), (u -= 2));
                                                      }
                                             e = e.join("/");
                                    }
                                    if ((h || g) && f) {
                                             for (u = (n = e.split("/")).length; 0 < u; u -= 1) {
                                                      if (((r = n.slice(0, u).join("/")), h))
                                                               for (d = h.length; 0 < d; d -= 1)
                                                                        if ((i = (i = f[h.slice(0, d).join("/")]) && i[r])) {
                                                                                 (o = i), (a = u);
                                                                                 break;
                                                                        }
                                                      if (o) break;
                                                      !l && g && g[r] && ((l = g[r]), (c = u));
                                             }
                                             !o && l && ((o = l), (a = c)), o && (n.splice(0, a, o), (e = n.join("/")));
                                    }
                                    return e;
                           }
                           function A(t, n) {
                                    return function () {
                                             var e = a.call(arguments, 0);
                                             return "string" != typeof e[0] && 1 === e.length && e.push(null), s.apply(h, e.concat([t, n]));
                                    };
                           }
                           function x(t) {
                                    return function (e) {
                                             m[t] = e;
                                    };
                           }
                           function D(e) {
                                    if (b(v, e)) {
                                             var t = v[e];
                                             delete v[e], (_[e] = !0), o.apply(h, t);
                                    }
                                    if (!b(m, e) && !b(_, e)) throw new Error("No " + e);
                                    return m[e];
                           }
                           function c(e) {
                                    var t,
                                             n = e ? e.indexOf("!") : -1;
                                    return -1 < n && ((t = e.substring(0, n)), (e = e.substring(n + 1, e.length))), [t, e];
                           }
                           function S(e) {
                                    return e ? c(e) : [];
                           }
                           return (
                                    (e && e.requirejs) ||
                                             (e ? (n = e) : (e = {}),
                                             (m = {}),
                                             (v = {}),
                                             (y = {}),
                                             (_ = {}),
                                             (i = Object.prototype.hasOwnProperty),
                                             (a = [].slice),
                                             (w = /\.js$/),
                                             (f = function (e, t) {
                                                      var n,
                                                               r = c(e),
                                                               i = r[0],
                                                               o = t[1];
                                                      return (
                                                               (e = r[1]),
                                                               i && (n = D((i = l(i, o)))),
                                                               i
                                                                        ? (e =
                                                                                   n && n.normalize
                                                                                            ? n.normalize(
                                                                                                       e,
                                                                                                       (function (t) {
                                                                                                                return function (e) {
                                                                                                                         return l(e, t);
                                                                                                                };
                                                                                                       })(o)
                                                                                              )
                                                                                            : l(e, o))
                                                                        : ((i = (r = c((e = l(e, o))))[0]), (e = r[1]), i && (n = D(i))),
                                                               { f: i ? i + "!" + e : e, n: e, pr: i, p: n }
                                                      );
                                             }),
                                             (g = {
                                                      require: function (e) {
                                                               return A(e);
                                                      },
                                                      exports: function (e) {
                                                               var t = m[e];
                                                               return void 0 !== t ? t : (m[e] = {});
                                                      },
                                                      module: function (e) {
                                                               return {
                                                                        id: e,
                                                                        uri: "",
                                                                        exports: m[e],
                                                                        config: (function (e) {
                                                                                 return function () {
                                                                                          return (y && y.config && y.config[e]) || {};
                                                                                 };
                                                                        })(e),
                                                               };
                                                      },
                                             }),
                                             (o = function (e, t, n, r) {
                                                      var i,
                                                               o,
                                                               s,
                                                               a,
                                                               l,
                                                               c,
                                                               u,
                                                               d = [],
                                                               p = typeof n;
                                                      if (((c = S((r = r || e))), "undefined" == p || "function" == p)) {
                                                               for (t = !t.length && n.length ? ["require", "exports", "module"] : t, l = 0; l < t.length; l += 1)
                                                                        if ("require" === (o = (a = f(t[l], c)).f)) d[l] = g.require(e);
                                                                        else if ("exports" === o) (d[l] = g.exports(e)), (u = !0);
                                                                        else if ("module" === o) i = d[l] = g.module(e);
                                                                        else if (b(m, o) || b(v, o) || b(_, o)) d[l] = D(o);
                                                                        else {
                                                                                 if (!a.p) throw new Error(e + " missing " + o);
                                                                                 a.p.load(a.n, A(r, !0), x(o), {}), (d[l] = m[o]);
                                                                        }
                                                               (s = n ? n.apply(m[e], d) : void 0), e && (i && i.exports !== h && i.exports !== m[e] ? (m[e] = i.exports) : (s === h && u) || (m[e] = s));
                                                      } else e && (m[e] = n);
                                             }),
                                             (t = n = s = function (e, t, n, r, i) {
                                                      if ("string" == typeof e) return g[e] ? g[e](t) : D(f(e, S(t)).f);
                                                      if (!e.splice) {
                                                               if (((y = e).deps && s(y.deps, y.callback), !t)) return;
                                                               t.splice ? ((e = t), (t = n), (n = null)) : (e = h);
                                                      }
                                                      return (
                                                               (t = t || function () {}),
                                                               "function" == typeof n && ((n = r), (r = i)),
                                                               r
                                                                        ? o(h, e, t, n)
                                                                        : setTimeout(function () {
                                                                                   o(h, e, t, n);
                                                                          }, 4),
                                                               s
                                                      );
                                             }),
                                             (s.config = function (e) {
                                                      return s(e);
                                             }),
                                             (t._defined = m),
                                             ((r = function (e, t, n) {
                                                      if ("string" != typeof e) throw new Error("See almond README: incorrect module build, no module name");
                                                      t.splice || ((n = t), (t = [])), b(m, e) || b(v, e) || (v[e] = [e, t, n]);
                                             }).amd = { jQuery: !0 }),
                                             (e.requirejs = t),
                                             (e.require = n),
                                             (e.define = r)),
                                    e.define("almond", function () {}),
                                    e.define("jquery", [], function () {
                                             var e = u || $;
                                             return (
                                                      null == e &&
                                                               console &&
                                                               console.error &&
                                                               console.error("Select2: An instance of jQuery or a jQuery-compatible library was not found. Make sure that you are including jQuery before Select2 on your web page."),
                                                      e
                                             );
                                    }),
                                    e.define("select2/utils", ["jquery"], function (o) {
                                             var i = {};
                                             function u(e) {
                                                      var t = e.prototype,
                                                               n = [];
                                                      for (var r in t) {
                                                               "function" == typeof t[r] && "constructor" !== r && n.push(r);
                                                      }
                                                      return n;
                                             }
                                             (i.Extend = function (e, t) {
                                                      var n = {}.hasOwnProperty;
                                                      function r() {
                                                               this.constructor = e;
                                                      }
                                                      for (var i in t) n.call(t, i) && (e[i] = t[i]);
                                                      return (r.prototype = t.prototype), (e.prototype = new r()), (e.__super__ = t.prototype), e;
                                             }),
                                                      (i.Decorate = function (r, i) {
                                                               var e = u(i),
                                                                        t = u(r);
                                                               function o() {
                                                                        var e = Array.prototype.unshift,
                                                                                 t = i.prototype.constructor.length,
                                                                                 n = r.prototype.constructor;
                                                                        0 < t && (e.call(arguments, r.prototype.constructor), (n = i.prototype.constructor)), n.apply(this, arguments);
                                                               }
                                                               (i.displayName = r.displayName),
                                                                        (o.prototype = new (function () {
                                                                                 this.constructor = o;
                                                                        })());
                                                               for (var n = 0; n < t.length; n++) {
                                                                        var s = t[n];
                                                                        o.prototype[s] = r.prototype[s];
                                                               }
                                                               function a(e) {
                                                                        var t = function () {};
                                                                        e in o.prototype && (t = o.prototype[e]);
                                                                        var n = i.prototype[e];
                                                                        return function () {
                                                                                 return Array.prototype.unshift.call(arguments, t), n.apply(this, arguments);
                                                                        };
                                                               }
                                                               for (var l = 0; l < e.length; l++) {
                                                                        var c = e[l];
                                                                        o.prototype[c] = a(c);
                                                               }
                                                               return o;
                                                      });
                                             function e() {
                                                      this.listeners = {};
                                             }
                                             (e.prototype.on = function (e, t) {
                                                      (this.listeners = this.listeners || {}), e in this.listeners ? this.listeners[e].push(t) : (this.listeners[e] = [t]);
                                             }),
                                                      (e.prototype.trigger = function (e) {
                                                               var t = Array.prototype.slice,
                                                                        n = t.call(arguments, 1);
                                                               (this.listeners = this.listeners || {}),
                                                                        null == n && (n = []),
                                                                        0 === n.length && n.push({}),
                                                                        (n[0]._type = e) in this.listeners && this.invoke(this.listeners[e], t.call(arguments, 1)),
                                                                        "*" in this.listeners && this.invoke(this.listeners["*"], arguments);
                                                      }),
                                                      (e.prototype.invoke = function (e, t) {
                                                               for (var n = 0, r = e.length; n < r; n++) e[n].apply(this, t);
                                                      }),
                                                      (i.Observable = e),
                                                      (i.generateChars = function (e) {
                                                               for (var t = "", n = 0; n < e; n++) {
                                                                        t += Math.floor(36 * Math.random()).toString(36);
                                                               }
                                                               return t;
                                                      }),
                                                      (i.bind = function (e, t) {
                                                               return function () {
                                                                        e.apply(t, arguments);
                                                               };
                                                      }),
                                                      (i._convertData = function (e) {
                                                               for (var t in e) {
                                                                        var n = t.split("-"),
                                                                                 r = e;
                                                                        if (1 !== n.length) {
                                                                                 for (var i = 0; i < n.length; i++) {
                                                                                          var o = n[i];
                                                                                          (o = o.substring(0, 1).toLowerCase() + o.substring(1)) in r || (r[o] = {}), i == n.length - 1 && (r[o] = e[t]), (r = r[o]);
                                                                                 }
                                                                                 delete e[t];
                                                                        }
                                                               }
                                                               return e;
                                                      }),
                                                      (i.hasScroll = function (e, t) {
                                                               var n = o(t),
                                                                        r = t.style.overflowX,
                                                                        i = t.style.overflowY;
                                                               return (r !== i || ("hidden" !== i && "visible" !== i)) && ("scroll" === r || "scroll" === i || n.innerHeight() < t.scrollHeight || n.innerWidth() < t.scrollWidth);
                                                      }),
                                                      (i.escapeMarkup = function (e) {
                                                               var t = { "\\": "&#92;", "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;", "/": "&#47;" };
                                                               return "string" != typeof e
                                                                        ? e
                                                                        : String(e).replace(/[&<>"'\/\\]/g, function (e) {
                                                                                   return t[e];
                                                                          });
                                                      }),
                                                      (i.appendMany = function (e, t) {
                                                               if ("1.7" === o.fn.jquery.substr(0, 3)) {
                                                                        var n = o();
                                                                        o.map(t, function (e) {
                                                                                 n = n.add(e);
                                                                        }),
                                                                                 (t = n);
                                                               }
                                                               e.append(t);
                                                      }),
                                                      (i.__cache = {});
                                             var n = 0;
                                             return (
                                                      (i.GetUniqueElementId = function (e) {
                                                               var t = e.getAttribute("data-select2-id");
                                                               return null == t && (e.id ? ((t = e.id), e.setAttribute("data-select2-id", t)) : (e.setAttribute("data-select2-id", ++n), (t = n.toString()))), t;
                                                      }),
                                                      (i.StoreData = function (e, t, n) {
                                                               var r = i.GetUniqueElementId(e);
                                                               i.__cache[r] || (i.__cache[r] = {}), (i.__cache[r][t] = n);
                                                      }),
                                                      (i.GetData = function (e, t) {
                                                               var n = i.GetUniqueElementId(e);
                                                               return t ? (i.__cache[n] && null != i.__cache[n][t] ? i.__cache[n][t] : o(e).data(t)) : i.__cache[n];
                                                      }),
                                                      (i.RemoveData = function (e) {
                                                               var t = i.GetUniqueElementId(e);
                                                               null != i.__cache[t] && delete i.__cache[t], e.removeAttribute("data-select2-id");
                                                      }),
                                                      i
                                             );
                                    }),
                                    e.define("select2/results", ["jquery", "./utils"], function (h, f) {
                                             function r(e, t, n) {
                                                      (this.$element = e), (this.data = n), (this.options = t), r.__super__.constructor.call(this);
                                             }
                                             return (
                                                      f.Extend(r, f.Observable),
                                                      (r.prototype.render = function () {
                                                               var e = h('<ul class="select2-results__options" role="listbox"></ul>');
                                                               return this.options.get("multiple") && e.attr("aria-multiselectable", "true"), (this.$results = e);
                                                      }),
                                                      (r.prototype.clear = function () {
                                                               this.$results.empty();
                                                      }),
                                                      (r.prototype.displayMessage = function (e) {
                                                               var t = this.options.get("escapeMarkup");
                                                               this.clear(), this.hideLoading();
                                                               var n = h('<li role="alert" aria-live="assertive" class="select2-results__option"></li>'),
                                                                        r = this.options.get("translations").get(e.message);
                                                               n.append(t(r(e.args))), (n[0].className += " select2-results__message"), this.$results.append(n);
                                                      }),
                                                      (r.prototype.hideMessages = function () {
                                                               this.$results.find(".select2-results__message").remove();
                                                      }),
                                                      (r.prototype.append = function (e) {
                                                               this.hideLoading();
                                                               var t = [];
                                                               if (null != e.results && 0 !== e.results.length) {
                                                                        e.results = this.sort(e.results);
                                                                        for (var n = 0; n < e.results.length; n++) {
                                                                                 var r = e.results[n],
                                                                                          i = this.option(r);
                                                                                 t.push(i);
                                                                        }
                                                                        this.$results.append(t);
                                                               } else 0 === this.$results.children().length && this.trigger("results:message", { message: "noResults" });
                                                      }),
                                                      (r.prototype.position = function (e, t) {
                                                               t.find(".select2-results").append(e);
                                                      }),
                                                      (r.prototype.sort = function (e) {
                                                               return this.options.get("sorter")(e);
                                                      }),
                                                      (r.prototype.highlightFirstItem = function () {
                                                               var e = this.$results.find(".select2-results__option[aria-selected]"),
                                                                        t = e.filter("[aria-selected=true]");
                                                               0 < t.length ? t.first().trigger("mouseenter") : e.first().trigger("mouseenter"), this.ensureHighlightVisible();
                                                      }),
                                                      (r.prototype.setClasses = function () {
                                                               var t = this;
                                                               this.data.current(function (e) {
                                                                        var r = h.map(e, function (e) {
                                                                                 return e.id.toString();
                                                                        });
                                                                        t.$results.find(".select2-results__option[aria-selected]").each(function () {
                                                                                 var e = h(this),
                                                                                          t = f.GetData(this, "data"),
                                                                                          n = "" + t.id;
                                                                                 (null != t.element && t.element.selected) || (null == t.element && -1 < h.inArray(n, r)) ? e.attr("aria-selected", "true") : e.attr("aria-selected", "false");
                                                                        });
                                                               });
                                                      }),
                                                      (r.prototype.showLoading = function (e) {
                                                               this.hideLoading();
                                                               var t = { disabled: !0, loading: !0, text: this.options.get("translations").get("searching")(e) },
                                                                        n = this.option(t);
                                                               (n.className += " loading-results"), this.$results.prepend(n);
                                                      }),
                                                      (r.prototype.hideLoading = function () {
                                                               this.$results.find(".loading-results").remove();
                                                      }),
                                                      (r.prototype.option = function (e) {
                                                               var t = document.createElement("li");
                                                               t.className = "select2-results__option";
                                                               var n = { role: "option", "aria-selected": "false" },
                                                                        r = window.Element.prototype.matches || window.Element.prototype.msMatchesSelector || window.Element.prototype.webkitMatchesSelector;
                                                               for (var i in (((null != e.element && r.call(e.element, ":disabled")) || (null == e.element && e.disabled)) && (delete n["aria-selected"], (n["aria-disabled"] = "true")),
                                                               null == e.id && delete n["aria-selected"],
                                                               null != e._resultId && (t.id = e._resultId),
                                                               e.title && (t.title = e.title),
                                                               e.children && ((n.role = "group"), (n["aria-label"] = e.text), delete n["aria-selected"]),
                                                               n)) {
                                                                        var o = n[i];
                                                                        t.setAttribute(i, o);
                                                               }
                                                               if (e.children) {
                                                                        var s = h(t),
                                                                                 a = document.createElement("strong");
                                                                        a.className = "select2-results__group";
                                                                        h(a);
                                                                        this.template(e, a);
                                                                        for (var l = [], c = 0; c < e.children.length; c++) {
                                                                                 var u = e.children[c],
                                                                                          d = this.option(u);
                                                                                 l.push(d);
                                                                        }
                                                                        var p = h("<ul></ul>", { class: "select2-results__options select2-results__options--nested" });
                                                                        p.append(l), s.append(a), s.append(p);
                                                               } else this.template(e, t);
                                                               return f.StoreData(t, "data", e), t;
                                                      }),
                                                      (r.prototype.bind = function (t, e) {
                                                               var l = this,
                                                                        n = t.id + "-results";
                                                               this.$results.attr("id", n),
                                                                        t.on("results:all", function (e) {
                                                                                 l.clear(), l.append(e.data), t.isOpen() && (l.setClasses(), l.highlightFirstItem());
                                                                        }),
                                                                        t.on("results:append", function (e) {
                                                                                 l.append(e.data), t.isOpen() && l.setClasses();
                                                                        }),
                                                                        t.on("query", function (e) {
                                                                                 l.hideMessages(), l.showLoading(e);
                                                                        }),
                                                                        t.on("select", function () {
                                                                                 t.isOpen() && (l.setClasses(), l.options.get("scrollAfterSelect") && l.highlightFirstItem());
                                                                        }),
                                                                        t.on("unselect", function () {
                                                                                 t.isOpen() && (l.setClasses(), l.options.get("scrollAfterSelect") && l.highlightFirstItem());
                                                                        }),
                                                                        t.on("open", function () {
                                                                                 l.$results.attr("aria-expanded", "true"), l.$results.attr("aria-hidden", "false"), l.setClasses(), l.ensureHighlightVisible();
                                                                        }),
                                                                        t.on("close", function () {
                                                                                 l.$results.attr("aria-expanded", "false"), l.$results.attr("aria-hidden", "true"), l.$results.removeAttr("aria-activedescendant");
                                                                        }),
                                                                        t.on("results:toggle", function () {
                                                                                 var e = l.getHighlightedResults();
                                                                                 0 !== e.length && e.trigger("mouseup");
                                                                        }),
                                                                        t.on("results:select", function () {
                                                                                 var e = l.getHighlightedResults();
                                                                                 if (0 !== e.length) {
                                                                                          var t = f.GetData(e[0], "data");
                                                                                          "true" == e.attr("aria-selected") ? l.trigger("close", {}) : l.trigger("select", { data: t });
                                                                                 }
                                                                        }),
                                                                        t.on("results:previous", function () {
                                                                                 var e = l.getHighlightedResults(),
                                                                                          t = l.$results.find("[aria-selected]"),
                                                                                          n = t.index(e);
                                                                                 if (!(n <= 0)) {
                                                                                          var r = n - 1;
                                                                                          0 === e.length && (r = 0);
                                                                                          var i = t.eq(r);
                                                                                          i.trigger("mouseenter");
                                                                                          var o = l.$results.offset().top,
                                                                                                   s = i.offset().top,
                                                                                                   a = l.$results.scrollTop() + (s - o);
                                                                                          0 === r ? l.$results.scrollTop(0) : s - o < 0 && l.$results.scrollTop(a);
                                                                                 }
                                                                        }),
                                                                        t.on("results:next", function () {
                                                                                 var e = l.getHighlightedResults(),
                                                                                          t = l.$results.find("[aria-selected]"),
                                                                                          n = t.index(e) + 1;
                                                                                 if (!(n >= t.length)) {
                                                                                          var r = t.eq(n);
                                                                                          r.trigger("mouseenter");
                                                                                          var i = l.$results.offset().top + l.$results.outerHeight(!1),
                                                                                                   o = r.offset().top + r.outerHeight(!1),
                                                                                                   s = l.$results.scrollTop() + o - i;
                                                                                          0 === n ? l.$results.scrollTop(0) : i < o && l.$results.scrollTop(s);
                                                                                 }
                                                                        }),
                                                                        t.on("results:focus", function (e) {
                                                                                 e.element.addClass("select2-results__option--highlighted");
                                                                        }),
                                                                        t.on("results:message", function (e) {
                                                                                 l.displayMessage(e);
                                                                        }),
                                                                        h.fn.mousewheel &&
                                                                                 this.$results.on("mousewheel", function (e) {
                                                                                          var t = l.$results.scrollTop(),
                                                                                                   n = l.$results.get(0).scrollHeight - t + e.deltaY,
                                                                                                   r = 0 < e.deltaY && t - e.deltaY <= 0,
                                                                                                   i = e.deltaY < 0 && n <= l.$results.height();
                                                                                          r
                                                                                                   ? (l.$results.scrollTop(0), e.preventDefault(), e.stopPropagation())
                                                                                                   : i && (l.$results.scrollTop(l.$results.get(0).scrollHeight - l.$results.height()), e.preventDefault(), e.stopPropagation());
                                                                                 }),
                                                                        this.$results.on("mouseup", ".select2-results__option[aria-selected]", function (e) {
                                                                                 var t = h(this),
                                                                                          n = f.GetData(this, "data");
                                                                                 "true" !== t.attr("aria-selected")
                                                                                          ? l.trigger("select", { originalEvent: e, data: n })
                                                                                          : l.options.get("multiple")
                                                                                          ? l.trigger("unselect", { originalEvent: e, data: n })
                                                                                          : l.trigger("close", {});
                                                                        }),
                                                                        this.$results.on("mouseenter", ".select2-results__option[aria-selected]", function (e) {
                                                                                 var t = f.GetData(this, "data");
                                                                                 l.getHighlightedResults().removeClass("select2-results__option--highlighted"), l.trigger("results:focus", { data: t, element: h(this) });
                                                                        });
                                                      }),
                                                      (r.prototype.getHighlightedResults = function () {
                                                               return this.$results.find(".select2-results__option--highlighted");
                                                      }),
                                                      (r.prototype.destroy = function () {
                                                               this.$results.remove();
                                                      }),
                                                      (r.prototype.ensureHighlightVisible = function () {
                                                               var e = this.getHighlightedResults();
                                                               if (0 !== e.length) {
                                                                        var t = this.$results.find("[aria-selected]").index(e),
                                                                                 n = this.$results.offset().top,
                                                                                 r = e.offset().top,
                                                                                 i = this.$results.scrollTop() + (r - n),
                                                                                 o = r - n;
                                                                        (i -= 2 * e.outerHeight(!1)), t <= 2 ? this.$results.scrollTop(0) : (o > this.$results.outerHeight() || o < 0) && this.$results.scrollTop(i);
                                                               }
                                                      }),
                                                      (r.prototype.template = function (e, t) {
                                                               var n = this.options.get("templateResult"),
                                                                        r = this.options.get("escapeMarkup"),
                                                                        i = n(e, t);
                                                               null == i ? (t.style.display = "none") : "string" == typeof i ? (t.innerHTML = r(i)) : h(t).append(i);
                                                      }),
                                                      r
                                             );
                                    }),
                                    e.define("select2/keys", [], function () {
                                             return { BACKSPACE: 8, TAB: 9, ENTER: 13, SHIFT: 16, CTRL: 17, ALT: 18, ESC: 27, SPACE: 32, PAGE_UP: 33, PAGE_DOWN: 34, END: 35, HOME: 36, LEFT: 37, UP: 38, RIGHT: 39, DOWN: 40, DELETE: 46 };
                                    }),
                                    e.define("select2/selection/base", ["jquery", "../utils", "../keys"], function (n, r, i) {
                                             function o(e, t) {
                                                      (this.$element = e), (this.options = t), o.__super__.constructor.call(this);
                                             }
                                             return (
                                                      r.Extend(o, r.Observable),
                                                      (o.prototype.render = function () {
                                                               var e = n('<span class="select2-selection" role="combobox"  aria-haspopup="true" aria-expanded="false"></span>');
                                                               return (
                                                                        (this._tabindex = 0),
                                                                        null != r.GetData(this.$element[0], "old-tabindex")
                                                                                 ? (this._tabindex = r.GetData(this.$element[0], "old-tabindex"))
                                                                                 : null != this.$element.attr("tabindex") && (this._tabindex = this.$element.attr("tabindex")),
                                                                        e.attr("title", this.$element.attr("title")),
                                                                        e.attr("tabindex", this._tabindex),
                                                                        e.attr("aria-disabled", "false"),
                                                                        (this.$selection = e)
                                                               );
                                                      }),
                                                      (o.prototype.bind = function (e, t) {
                                                               var n = this,
                                                                        r = e.id + "-results";
                                                               (this.container = e),
                                                                        this.$selection.on("focus", function (e) {
                                                                                 n.trigger("focus", e);
                                                                        }),
                                                                        this.$selection.on("blur", function (e) {
                                                                                 n._handleBlur(e);
                                                                        }),
                                                                        this.$selection.on("keydown", function (e) {
                                                                                 n.trigger("keypress", e), e.which === i.SPACE && e.preventDefault();
                                                                        }),
                                                                        e.on("results:focus", function (e) {
                                                                                 n.$selection.attr("aria-activedescendant", e.data._resultId);
                                                                        }),
                                                                        e.on("selection:update", function (e) {
                                                                                 n.update(e.data);
                                                                        }),
                                                                        e.on("open", function () {
                                                                                 n.$selection.attr("aria-expanded", "true"), n.$selection.attr("aria-owns", r), n._attachCloseHandler(e);
                                                                        }),
                                                                        e.on("close", function () {
                                                                                 n.$selection.attr("aria-expanded", "false"),
                                                                                          n.$selection.removeAttr("aria-activedescendant"),
                                                                                          n.$selection.removeAttr("aria-owns"),
                                                                                          n.$selection.trigger("focus"),
                                                                                          n._detachCloseHandler(e);
                                                                        }),
                                                                        e.on("enable", function () {
                                                                                 n.$selection.attr("tabindex", n._tabindex), n.$selection.attr("aria-disabled", "false");
                                                                        }),
                                                                        e.on("disable", function () {
                                                                                 n.$selection.attr("tabindex", "-1"), n.$selection.attr("aria-disabled", "true");
                                                                        });
                                                      }),
                                                      (o.prototype._handleBlur = function (e) {
                                                               var t = this;
                                                               window.setTimeout(function () {
                                                                        document.activeElement == t.$selection[0] || n.contains(t.$selection[0], document.activeElement) || t.trigger("blur", e);
                                                               }, 1);
                                                      }),
                                                      (o.prototype._attachCloseHandler = function (e) {
                                                               n(document.body).on("mousedown.select2." + e.id, function (e) {
                                                                        var t = n(e.target).closest(".select2");
                                                                        n(".select2.select2-container--open").each(function () {
                                                                                 this != t[0] && r.GetData(this, "element").select2("close");
                                                                        });
                                                               });
                                                      }),
                                                      (o.prototype._detachCloseHandler = function (e) {
                                                               n(document.body).off("mousedown.select2." + e.id);
                                                      }),
                                                      (o.prototype.position = function (e, t) {
                                                               t.find(".selection").append(e);
                                                      }),
                                                      (o.prototype.destroy = function () {
                                                               this._detachCloseHandler(this.container);
                                                      }),
                                                      (o.prototype.update = function (e) {
                                                               throw new Error("The `update` method must be defined in child classes.");
                                                      }),
                                                      o
                                             );
                                    }),
                                    e.define("select2/selection/single", ["jquery", "./base", "../utils", "../keys"], function (e, t, n, r) {
                                             function i() {
                                                      i.__super__.constructor.apply(this, arguments);
                                             }
                                             return (
                                                      n.Extend(i, t),
                                                      (i.prototype.render = function () {
                                                               var e = i.__super__.render.call(this);
                                                               return (
                                                                        e.addClass("select2-selection--single"),
                                                                        e.html('<span class="select2-selection__rendered"></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span>'),
                                                                        e
                                                               );
                                                      }),
                                                      (i.prototype.bind = function (t, e) {
                                                               var n = this;
                                                               i.__super__.bind.apply(this, arguments);
                                                               var r = t.id + "-container";
                                                               this.$selection.find(".select2-selection__rendered").attr("id", r).attr("role", "textbox").attr("aria-readonly", "true"),
                                                                        this.$selection.attr("aria-labelledby", r),
                                                                        this.$selection.on("mousedown", function (e) {
                                                                                 1 === e.which && n.trigger("toggle", { originalEvent: e });
                                                                        }),
                                                                        this.$selection.on("focus", function (e) {}),
                                                                        this.$selection.on("blur", function (e) {}),
                                                                        t.on("focus", function (e) {
                                                                                 t.isOpen() || n.$selection.trigger("focus");
                                                                        });
                                                      }),
                                                      (i.prototype.clear = function () {
                                                               var e = this.$selection.find(".select2-selection__rendered");
                                                               e.empty(), e.removeAttr("title");
                                                      }),
                                                      (i.prototype.display = function (e, t) {
                                                               var n = this.options.get("templateSelection");
                                                               return this.options.get("escapeMarkup")(n(e, t));
                                                      }),
                                                      (i.prototype.selectionContainer = function () {
                                                               return e("<span></span>");
                                                      }),
                                                      (i.prototype.update = function (e) {
                                                               if (0 !== e.length) {
                                                                        var t = e[0],
                                                                                 n = this.$selection.find(".select2-selection__rendered"),
                                                                                 r = this.display(t, n);
                                                                        n.empty().append(r);
                                                                        var i = t.title || t.text;
                                                                        i ? n.attr("title", i) : n.removeAttr("title");
                                                               } else this.clear();
                                                      }),
                                                      i
                                             );
                                    }),
                                    e.define("select2/selection/multiple", ["jquery", "./base", "../utils"], function (i, e, l) {
                                             function n(e, t) {
                                                      n.__super__.constructor.apply(this, arguments);
                                             }
                                             return (
                                                      l.Extend(n, e),
                                                      (n.prototype.render = function () {
                                                               var e = n.__super__.render.call(this);
                                                               return e.addClass("select2-selection--multiple"), e.html('<ul class="select2-selection__rendered"></ul>'), e;
                                                      }),
                                                      (n.prototype.bind = function (e, t) {
                                                               var r = this;
                                                               n.__super__.bind.apply(this, arguments),
                                                                        this.$selection.on("click", function (e) {
                                                                                 r.trigger("toggle", { originalEvent: e });
                                                                        }),
                                                                        this.$selection.on("click", ".select2-selection__choice__remove", function (e) {
                                                                                 if (!r.options.get("disabled")) {
                                                                                          var t = i(this).parent(),
                                                                                                   n = l.GetData(t[0], "data");
                                                                                          r.trigger("unselect", { originalEvent: e, data: n });
                                                                                 }
                                                                        });
                                                      }),
                                                      (n.prototype.clear = function () {
                                                               var e = this.$selection.find(".select2-selection__rendered");
                                                               e.empty(), e.removeAttr("title");
                                                      }),
                                                      (n.prototype.display = function (e, t) {
                                                               var n = this.options.get("templateSelection");
                                                               return this.options.get("escapeMarkup")(n(e, t));
                                                      }),
                                                      (n.prototype.selectionContainer = function () {
                                                               return i('<li class="select2-selection__choice"><span class="select2-selection__choice__remove" role="presentation">&times;</span></li>');
                                                      }),
                                                      (n.prototype.update = function (e) {
                                                               if ((this.clear(), 0 !== e.length)) {
                                                                        for (var t = [], n = 0; n < e.length; n++) {
                                                                                 var r = e[n],
                                                                                          i = this.selectionContainer(),
                                                                                          o = this.display(r, i);
                                                                                 i.append(o);
                                                                                 var s = r.title || r.text;
                                                                                 s && i.attr("title", s), l.StoreData(i[0], "data", r), t.push(i);
                                                                        }
                                                                        var a = this.$selection.find(".select2-selection__rendered");
                                                                        l.appendMany(a, t);
                                                               }
                                                      }),
                                                      n
                                             );
                                    }),
                                    e.define("select2/selection/placeholder", ["../utils"], function (e) {
                                             function t(e, t, n) {
                                                      (this.placeholder = this.normalizePlaceholder(n.get("placeholder"))), e.call(this, t, n);
                                             }
                                             return (
                                                      (t.prototype.normalizePlaceholder = function (e, t) {
                                                               return "string" == typeof t && (t = { id: "", text: t }), t;
                                                      }),
                                                      (t.prototype.createPlaceholder = function (e, t) {
                                                               var n = this.selectionContainer();
                                                               return n.html(this.display(t)), n.addClass("select2-selection__placeholder").removeClass("select2-selection__choice"), n;
                                                      }),
                                                      (t.prototype.update = function (e, t) {
                                                               var n = 1 == t.length && t[0].id != this.placeholder.id;
                                                               if (1 < t.length || n) return e.call(this, t);
                                                               this.clear();
                                                               var r = this.createPlaceholder(this.placeholder);
                                                               this.$selection.find(".select2-selection__rendered").append(r);
                                                      }),
                                                      t
                                             );
                                    }),
                                    e.define("select2/selection/allowClear", ["jquery", "../keys", "../utils"], function (i, r, a) {
                                             function e() {}
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this;
                                                               e.call(this, t, n),
                                                                        null == this.placeholder &&
                                                                                 this.options.get("debug") &&
                                                                                 window.console &&
                                                                                 console.error &&
                                                                                 console.error("Select2: The `allowClear` option should be used in combination with the `placeholder` option."),
                                                                        this.$selection.on("mousedown", ".select2-selection__clear", function (e) {
                                                                                 r._handleClear(e);
                                                                        }),
                                                                        t.on("keypress", function (e) {
                                                                                 r._handleKeyboardClear(e, t);
                                                                        });
                                                      }),
                                                      (e.prototype._handleClear = function (e, t) {
                                                               if (!this.options.get("disabled")) {
                                                                        var n = this.$selection.find(".select2-selection__clear");
                                                                        if (0 !== n.length) {
                                                                                 t.stopPropagation();
                                                                                 var r = a.GetData(n[0], "data"),
                                                                                          i = this.$element.val();
                                                                                 this.$element.val(this.placeholder.id);
                                                                                 var o = { data: r };
                                                                                 if ((this.trigger("clear", o), o.prevented)) this.$element.val(i);
                                                                                 else {
                                                                                          for (var s = 0; s < r.length; s++) if (((o = { data: r[s] }), this.trigger("unselect", o), o.prevented)) return void this.$element.val(i);
                                                                                          this.$element.trigger("change"), this.trigger("toggle", {});
                                                                                 }
                                                                        }
                                                               }
                                                      }),
                                                      (e.prototype._handleKeyboardClear = function (e, t, n) {
                                                               n.isOpen() || (t.which != r.DELETE && t.which != r.BACKSPACE) || this._handleClear(t);
                                                      }),
                                                      (e.prototype.update = function (e, t) {
                                                               if ((e.call(this, t), !(0 < this.$selection.find(".select2-selection__placeholder").length || 0 === t.length))) {
                                                                        var n = this.options.get("translations").get("removeAllItems"),
                                                                                 r = i('<span class="select2-selection__clear" title="' + n() + '">&times;</span>');
                                                                        a.StoreData(r[0], "data", t), this.$selection.find(".select2-selection__rendered").prepend(r);
                                                               }
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/selection/search", ["jquery", "../utils", "../keys"], function (r, a, l) {
                                             function e(e, t, n) {
                                                      e.call(this, t, n);
                                             }
                                             return (
                                                      (e.prototype.render = function (e) {
                                                               var t = r(
                                                                        '<li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" /></li>'
                                                               );
                                                               (this.$searchContainer = t), (this.$search = t.find("input"));
                                                               var n = e.call(this);
                                                               return this._transferTabIndex(), n;
                                                      }),
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this,
                                                                        i = t.id + "-results";
                                                               e.call(this, t, n),
                                                                        t.on("open", function () {
                                                                                 r.$search.attr("aria-controls", i), r.$search.trigger("focus");
                                                                        }),
                                                                        t.on("close", function () {
                                                                                 r.$search.val(""), r.$search.removeAttr("aria-controls"), r.$search.removeAttr("aria-activedescendant"), r.$search.trigger("focus");
                                                                        }),
                                                                        t.on("enable", function () {
                                                                                 r.$search.prop("disabled", !1), r._transferTabIndex();
                                                                        }),
                                                                        t.on("disable", function () {
                                                                                 r.$search.prop("disabled", !0);
                                                                        }),
                                                                        t.on("focus", function (e) {
                                                                                 r.$search.trigger("focus");
                                                                        }),
                                                                        t.on("results:focus", function (e) {
                                                                                 e.data._resultId ? r.$search.attr("aria-activedescendant", e.data._resultId) : r.$search.removeAttr("aria-activedescendant");
                                                                        }),
                                                                        this.$selection.on("focusin", ".select2-search--inline", function (e) {
                                                                                 r.trigger("focus", e);
                                                                        }),
                                                                        this.$selection.on("focusout", ".select2-search--inline", function (e) {
                                                                                 r._handleBlur(e);
                                                                        }),
                                                                        this.$selection.on("keydown", ".select2-search--inline", function (e) {
                                                                                 if ((e.stopPropagation(), r.trigger("keypress", e), (r._keyUpPrevented = e.isDefaultPrevented()), e.which === l.BACKSPACE && "" === r.$search.val())) {
                                                                                          var t = r.$searchContainer.prev(".select2-selection__choice");
                                                                                          if (0 < t.length) {
                                                                                                   var n = a.GetData(t[0], "data");
                                                                                                   r.searchRemoveChoice(n), e.preventDefault();
                                                                                          }
                                                                                 }
                                                                        }),
                                                                        this.$selection.on("click", ".select2-search--inline", function (e) {
                                                                                 r.$search.val() && e.stopPropagation();
                                                                        });
                                                               var o = document.documentMode,
                                                                        s = o && o <= 11;
                                                               this.$selection.on("input.searchcheck", ".select2-search--inline", function (e) {
                                                                        s ? r.$selection.off("input.search input.searchcheck") : r.$selection.off("keyup.search");
                                                               }),
                                                                        this.$selection.on("keyup.search input.search", ".select2-search--inline", function (e) {
                                                                                 if (s && "input" === e.type) r.$selection.off("input.search input.searchcheck");
                                                                                 else {
                                                                                          var t = e.which;
                                                                                          t != l.SHIFT && t != l.CTRL && t != l.ALT && t != l.TAB && r.handleSearch(e);
                                                                                 }
                                                                        });
                                                      }),
                                                      (e.prototype._transferTabIndex = function (e) {
                                                               this.$search.attr("tabindex", this.$selection.attr("tabindex")), this.$selection.attr("tabindex", "-1");
                                                      }),
                                                      (e.prototype.createPlaceholder = function (e, t) {
                                                               this.$search.attr("placeholder", t.text);
                                                      }),
                                                      (e.prototype.update = function (e, t) {
                                                               var n = this.$search[0] == document.activeElement;
                                                               this.$search.attr("placeholder", ""),
                                                                        e.call(this, t),
                                                                        this.$selection.find(".select2-selection__rendered").append(this.$searchContainer),
                                                                        this.resizeSearch(),
                                                                        n && this.$search.trigger("focus");
                                                      }),
                                                      (e.prototype.handleSearch = function () {
                                                               if ((this.resizeSearch(), !this._keyUpPrevented)) {
                                                                        var e = this.$search.val();
                                                                        this.trigger("query", { term: e });
                                                               }
                                                               this._keyUpPrevented = !1;
                                                      }),
                                                      (e.prototype.searchRemoveChoice = function (e, t) {
                                                               this.trigger("unselect", { data: t }), this.$search.val(t.text), this.handleSearch();
                                                      }),
                                                      (e.prototype.resizeSearch = function () {
                                                               this.$search.css("width", "25px");
                                                               var e = "";
                                                               "" !== this.$search.attr("placeholder") ? (e = this.$selection.find(".select2-selection__rendered").width()) : (e = 0.75 * (this.$search.val().length + 1) + "em");
                                                               this.$search.css("width", e);
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/selection/eventRelay", ["jquery"], function (s) {
                                             function e() {}
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this,
                                                                        i = ["open", "opening", "close", "closing", "select", "selecting", "unselect", "unselecting", "clear", "clearing"],
                                                                        o = ["opening", "closing", "selecting", "unselecting", "clearing"];
                                                               e.call(this, t, n),
                                                                        t.on("*", function (e, t) {
                                                                                 if (-1 !== s.inArray(e, i)) {
                                                                                          t = t || {};
                                                                                          var n = s.Event("select2:" + e, { params: t });
                                                                                          r.$element.trigger(n), -1 !== s.inArray(e, o) && (t.prevented = n.isDefaultPrevented());
                                                                                 }
                                                                        });
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/translation", ["jquery", "require"], function (t, n) {
                                             function r(e) {
                                                      this.dict = e || {};
                                             }
                                             return (
                                                      (r.prototype.all = function () {
                                                               return this.dict;
                                                      }),
                                                      (r.prototype.get = function (e) {
                                                               return this.dict[e];
                                                      }),
                                                      (r.prototype.extend = function (e) {
                                                               this.dict = t.extend({}, e.all(), this.dict);
                                                      }),
                                                      (r._cache = {}),
                                                      (r.loadPath = function (e) {
                                                               if (!(e in r._cache)) {
                                                                        var t = n(e);
                                                                        r._cache[e] = t;
                                                               }
                                                               return new r(r._cache[e]);
                                                      }),
                                                      r
                                             );
                                    }),
                                    e.define("select2/diacritics", [], function () {
                                             return {
                                                      "Ⓐ": "A",
                                                      Ａ: "A",
                                                      À: "A",
                                                      Á: "A",
                                                      Â: "A",
                                                      Ầ: "A",
                                                      Ấ: "A",
                                                      Ẫ: "A",
                                                      Ẩ: "A",
                                                      Ã: "A",
                                                      Ā: "A",
                                                      Ă: "A",
                                                      Ằ: "A",
                                                      Ắ: "A",
                                                      Ẵ: "A",
                                                      Ẳ: "A",
                                                      Ȧ: "A",
                                                      Ǡ: "A",
                                                      Ä: "A",
                                                      Ǟ: "A",
                                                      Ả: "A",
                                                      Å: "A",
                                                      Ǻ: "A",
                                                      Ǎ: "A",
                                                      Ȁ: "A",
                                                      Ȃ: "A",
                                                      Ạ: "A",
                                                      Ậ: "A",
                                                      Ặ: "A",
                                                      Ḁ: "A",
                                                      Ą: "A",
                                                      Ⱥ: "A",
                                                      Ɐ: "A",
                                                      Ꜳ: "AA",
                                                      Æ: "AE",
                                                      Ǽ: "AE",
                                                      Ǣ: "AE",
                                                      Ꜵ: "AO",
                                                      Ꜷ: "AU",
                                                      Ꜹ: "AV",
                                                      Ꜻ: "AV",
                                                      Ꜽ: "AY",
                                                      "Ⓑ": "B",
                                                      Ｂ: "B",
                                                      Ḃ: "B",
                                                      Ḅ: "B",
                                                      Ḇ: "B",
                                                      Ƀ: "B",
                                                      Ƃ: "B",
                                                      Ɓ: "B",
                                                      "Ⓒ": "C",
                                                      Ｃ: "C",
                                                      Ć: "C",
                                                      Ĉ: "C",
                                                      Ċ: "C",
                                                      Č: "C",
                                                      Ç: "C",
                                                      Ḉ: "C",
                                                      Ƈ: "C",
                                                      Ȼ: "C",
                                                      Ꜿ: "C",
                                                      "Ⓓ": "D",
                                                      Ｄ: "D",
                                                      Ḋ: "D",
                                                      Ď: "D",
                                                      Ḍ: "D",
                                                      Ḑ: "D",
                                                      Ḓ: "D",
                                                      Ḏ: "D",
                                                      Đ: "D",
                                                      Ƌ: "D",
                                                      Ɗ: "D",
                                                      Ɖ: "D",
                                                      Ꝺ: "D",
                                                      Ǳ: "DZ",
                                                      Ǆ: "DZ",
                                                      ǲ: "Dz",
                                                      ǅ: "Dz",
                                                      "Ⓔ": "E",
                                                      Ｅ: "E",
                                                      È: "E",
                                                      É: "E",
                                                      Ê: "E",
                                                      Ề: "E",
                                                      Ế: "E",
                                                      Ễ: "E",
                                                      Ể: "E",
                                                      Ẽ: "E",
                                                      Ē: "E",
                                                      Ḕ: "E",
                                                      Ḗ: "E",
                                                      Ĕ: "E",
                                                      Ė: "E",
                                                      Ë: "E",
                                                      Ẻ: "E",
                                                      Ě: "E",
                                                      Ȅ: "E",
                                                      Ȇ: "E",
                                                      Ẹ: "E",
                                                      Ệ: "E",
                                                      Ȩ: "E",
                                                      Ḝ: "E",
                                                      Ę: "E",
                                                      Ḙ: "E",
                                                      Ḛ: "E",
                                                      Ɛ: "E",
                                                      Ǝ: "E",
                                                      "Ⓕ": "F",
                                                      Ｆ: "F",
                                                      Ḟ: "F",
                                                      Ƒ: "F",
                                                      Ꝼ: "F",
                                                      "Ⓖ": "G",
                                                      Ｇ: "G",
                                                      Ǵ: "G",
                                                      Ĝ: "G",
                                                      Ḡ: "G",
                                                      Ğ: "G",
                                                      Ġ: "G",
                                                      Ǧ: "G",
                                                      Ģ: "G",
                                                      Ǥ: "G",
                                                      Ɠ: "G",
                                                      Ꞡ: "G",
                                                      Ᵹ: "G",
                                                      Ꝿ: "G",
                                                      "Ⓗ": "H",
                                                      Ｈ: "H",
                                                      Ĥ: "H",
                                                      Ḣ: "H",
                                                      Ḧ: "H",
                                                      Ȟ: "H",
                                                      Ḥ: "H",
                                                      Ḩ: "H",
                                                      Ḫ: "H",
                                                      Ħ: "H",
                                                      Ⱨ: "H",
                                                      Ⱶ: "H",
                                                      Ɥ: "H",
                                                      "Ⓘ": "I",
                                                      Ｉ: "I",
                                                      Ì: "I",
                                                      Í: "I",
                                                      Î: "I",
                                                      Ĩ: "I",
                                                      Ī: "I",
                                                      Ĭ: "I",
                                                      İ: "I",
                                                      Ï: "I",
                                                      Ḯ: "I",
                                                      Ỉ: "I",
                                                      Ǐ: "I",
                                                      Ȉ: "I",
                                                      Ȋ: "I",
                                                      Ị: "I",
                                                      Į: "I",
                                                      Ḭ: "I",
                                                      Ɨ: "I",
                                                      "Ⓙ": "J",
                                                      Ｊ: "J",
                                                      Ĵ: "J",
                                                      Ɉ: "J",
                                                      "Ⓚ": "K",
                                                      Ｋ: "K",
                                                      Ḱ: "K",
                                                      Ǩ: "K",
                                                      Ḳ: "K",
                                                      Ķ: "K",
                                                      Ḵ: "K",
                                                      Ƙ: "K",
                                                      Ⱪ: "K",
                                                      Ꝁ: "K",
                                                      Ꝃ: "K",
                                                      Ꝅ: "K",
                                                      Ꞣ: "K",
                                                      "Ⓛ": "L",
                                                      Ｌ: "L",
                                                      Ŀ: "L",
                                                      Ĺ: "L",
                                                      Ľ: "L",
                                                      Ḷ: "L",
                                                      Ḹ: "L",
                                                      Ļ: "L",
                                                      Ḽ: "L",
                                                      Ḻ: "L",
                                                      Ł: "L",
                                                      Ƚ: "L",
                                                      Ɫ: "L",
                                                      Ⱡ: "L",
                                                      Ꝉ: "L",
                                                      Ꝇ: "L",
                                                      Ꞁ: "L",
                                                      Ǉ: "LJ",
                                                      ǈ: "Lj",
                                                      "Ⓜ": "M",
                                                      Ｍ: "M",
                                                      Ḿ: "M",
                                                      Ṁ: "M",
                                                      Ṃ: "M",
                                                      Ɱ: "M",
                                                      Ɯ: "M",
                                                      "Ⓝ": "N",
                                                      Ｎ: "N",
                                                      Ǹ: "N",
                                                      Ń: "N",
                                                      Ñ: "N",
                                                      Ṅ: "N",
                                                      Ň: "N",
                                                      Ṇ: "N",
                                                      Ņ: "N",
                                                      Ṋ: "N",
                                                      Ṉ: "N",
                                                      Ƞ: "N",
                                                      Ɲ: "N",
                                                      Ꞑ: "N",
                                                      Ꞥ: "N",
                                                      Ǌ: "NJ",
                                                      ǋ: "Nj",
                                                      "Ⓞ": "O",
                                                      Ｏ: "O",
                                                      Ò: "O",
                                                      Ó: "O",
                                                      Ô: "O",
                                                      Ồ: "O",
                                                      Ố: "O",
                                                      Ỗ: "O",
                                                      Ổ: "O",
                                                      Õ: "O",
                                                      Ṍ: "O",
                                                      Ȭ: "O",
                                                      Ṏ: "O",
                                                      Ō: "O",
                                                      Ṑ: "O",
                                                      Ṓ: "O",
                                                      Ŏ: "O",
                                                      Ȯ: "O",
                                                      Ȱ: "O",
                                                      Ö: "O",
                                                      Ȫ: "O",
                                                      Ỏ: "O",
                                                      Ő: "O",
                                                      Ǒ: "O",
                                                      Ȍ: "O",
                                                      Ȏ: "O",
                                                      Ơ: "O",
                                                      Ờ: "O",
                                                      Ớ: "O",
                                                      Ỡ: "O",
                                                      Ở: "O",
                                                      Ợ: "O",
                                                      Ọ: "O",
                                                      Ộ: "O",
                                                      Ǫ: "O",
                                                      Ǭ: "O",
                                                      Ø: "O",
                                                      Ǿ: "O",
                                                      Ɔ: "O",
                                                      Ɵ: "O",
                                                      Ꝋ: "O",
                                                      Ꝍ: "O",
                                                      Œ: "OE",
                                                      Ƣ: "OI",
                                                      Ꝏ: "OO",
                                                      Ȣ: "OU",
                                                      "Ⓟ": "P",
                                                      Ｐ: "P",
                                                      Ṕ: "P",
                                                      Ṗ: "P",
                                                      Ƥ: "P",
                                                      Ᵽ: "P",
                                                      Ꝑ: "P",
                                                      Ꝓ: "P",
                                                      Ꝕ: "P",
                                                      "Ⓠ": "Q",
                                                      Ｑ: "Q",
                                                      Ꝗ: "Q",
                                                      Ꝙ: "Q",
                                                      Ɋ: "Q",
                                                      "Ⓡ": "R",
                                                      Ｒ: "R",
                                                      Ŕ: "R",
                                                      Ṙ: "R",
                                                      Ř: "R",
                                                      Ȑ: "R",
                                                      Ȓ: "R",
                                                      Ṛ: "R",
                                                      Ṝ: "R",
                                                      Ŗ: "R",
                                                      Ṟ: "R",
                                                      Ɍ: "R",
                                                      Ɽ: "R",
                                                      Ꝛ: "R",
                                                      Ꞧ: "R",
                                                      Ꞃ: "R",
                                                      "Ⓢ": "S",
                                                      Ｓ: "S",
                                                      ẞ: "S",
                                                      Ś: "S",
                                                      Ṥ: "S",
                                                      Ŝ: "S",
                                                      Ṡ: "S",
                                                      Š: "S",
                                                      Ṧ: "S",
                                                      Ṣ: "S",
                                                      Ṩ: "S",
                                                      Ș: "S",
                                                      Ş: "S",
                                                      Ȿ: "S",
                                                      Ꞩ: "S",
                                                      Ꞅ: "S",
                                                      "Ⓣ": "T",
                                                      Ｔ: "T",
                                                      Ṫ: "T",
                                                      Ť: "T",
                                                      Ṭ: "T",
                                                      Ț: "T",
                                                      Ţ: "T",
                                                      Ṱ: "T",
                                                      Ṯ: "T",
                                                      Ŧ: "T",
                                                      Ƭ: "T",
                                                      Ʈ: "T",
                                                      Ⱦ: "T",
                                                      Ꞇ: "T",
                                                      Ꜩ: "TZ",
                                                      "Ⓤ": "U",
                                                      Ｕ: "U",
                                                      Ù: "U",
                                                      Ú: "U",
                                                      Û: "U",
                                                      Ũ: "U",
                                                      Ṹ: "U",
                                                      Ū: "U",
                                                      Ṻ: "U",
                                                      Ŭ: "U",
                                                      Ü: "U",
                                                      Ǜ: "U",
                                                      Ǘ: "U",
                                                      Ǖ: "U",
                                                      Ǚ: "U",
                                                      Ủ: "U",
                                                      Ů: "U",
                                                      Ű: "U",
                                                      Ǔ: "U",
                                                      Ȕ: "U",
                                                      Ȗ: "U",
                                                      Ư: "U",
                                                      Ừ: "U",
                                                      Ứ: "U",
                                                      Ữ: "U",
                                                      Ử: "U",
                                                      Ự: "U",
                                                      Ụ: "U",
                                                      Ṳ: "U",
                                                      Ų: "U",
                                                      Ṷ: "U",
                                                      Ṵ: "U",
                                                      Ʉ: "U",
                                                      "Ⓥ": "V",
                                                      Ｖ: "V",
                                                      Ṽ: "V",
                                                      Ṿ: "V",
                                                      Ʋ: "V",
                                                      Ꝟ: "V",
                                                      Ʌ: "V",
                                                      Ꝡ: "VY",
                                                      "Ⓦ": "W",
                                                      Ｗ: "W",
                                                      Ẁ: "W",
                                                      Ẃ: "W",
                                                      Ŵ: "W",
                                                      Ẇ: "W",
                                                      Ẅ: "W",
                                                      Ẉ: "W",
                                                      Ⱳ: "W",
                                                      "Ⓧ": "X",
                                                      Ｘ: "X",
                                                      Ẋ: "X",
                                                      Ẍ: "X",
                                                      "Ⓨ": "Y",
                                                      Ｙ: "Y",
                                                      Ỳ: "Y",
                                                      Ý: "Y",
                                                      Ŷ: "Y",
                                                      Ỹ: "Y",
                                                      Ȳ: "Y",
                                                      Ẏ: "Y",
                                                      Ÿ: "Y",
                                                      Ỷ: "Y",
                                                      Ỵ: "Y",
                                                      Ƴ: "Y",
                                                      Ɏ: "Y",
                                                      Ỿ: "Y",
                                                      "Ⓩ": "Z",
                                                      Ｚ: "Z",
                                                      Ź: "Z",
                                                      Ẑ: "Z",
                                                      Ż: "Z",
                                                      Ž: "Z",
                                                      Ẓ: "Z",
                                                      Ẕ: "Z",
                                                      Ƶ: "Z",
                                                      Ȥ: "Z",
                                                      Ɀ: "Z",
                                                      Ⱬ: "Z",
                                                      Ꝣ: "Z",
                                                      "ⓐ": "a",
                                                      ａ: "a",
                                                      ẚ: "a",
                                                      à: "a",
                                                      á: "a",
                                                      â: "a",
                                                      ầ: "a",
                                                      ấ: "a",
                                                      ẫ: "a",
                                                      ẩ: "a",
                                                      ã: "a",
                                                      ā: "a",
                                                      ă: "a",
                                                      ằ: "a",
                                                      ắ: "a",
                                                      ẵ: "a",
                                                      ẳ: "a",
                                                      ȧ: "a",
                                                      ǡ: "a",
                                                      ä: "a",
                                                      ǟ: "a",
                                                      ả: "a",
                                                      å: "a",
                                                      ǻ: "a",
                                                      ǎ: "a",
                                                      ȁ: "a",
                                                      ȃ: "a",
                                                      ạ: "a",
                                                      ậ: "a",
                                                      ặ: "a",
                                                      ḁ: "a",
                                                      ą: "a",
                                                      ⱥ: "a",
                                                      ɐ: "a",
                                                      ꜳ: "aa",
                                                      æ: "ae",
                                                      ǽ: "ae",
                                                      ǣ: "ae",
                                                      ꜵ: "ao",
                                                      ꜷ: "au",
                                                      ꜹ: "av",
                                                      ꜻ: "av",
                                                      ꜽ: "ay",
                                                      "ⓑ": "b",
                                                      ｂ: "b",
                                                      ḃ: "b",
                                                      ḅ: "b",
                                                      ḇ: "b",
                                                      ƀ: "b",
                                                      ƃ: "b",
                                                      ɓ: "b",
                                                      "ⓒ": "c",
                                                      ｃ: "c",
                                                      ć: "c",
                                                      ĉ: "c",
                                                      ċ: "c",
                                                      č: "c",
                                                      ç: "c",
                                                      ḉ: "c",
                                                      ƈ: "c",
                                                      ȼ: "c",
                                                      ꜿ: "c",
                                                      ↄ: "c",
                                                      "ⓓ": "d",
                                                      ｄ: "d",
                                                      ḋ: "d",
                                                      ď: "d",
                                                      ḍ: "d",
                                                      ḑ: "d",
                                                      ḓ: "d",
                                                      ḏ: "d",
                                                      đ: "d",
                                                      ƌ: "d",
                                                      ɖ: "d",
                                                      ɗ: "d",
                                                      ꝺ: "d",
                                                      ǳ: "dz",
                                                      ǆ: "dz",
                                                      "ⓔ": "e",
                                                      ｅ: "e",
                                                      è: "e",
                                                      é: "e",
                                                      ê: "e",
                                                      ề: "e",
                                                      ế: "e",
                                                      ễ: "e",
                                                      ể: "e",
                                                      ẽ: "e",
                                                      ē: "e",
                                                      ḕ: "e",
                                                      ḗ: "e",
                                                      ĕ: "e",
                                                      ė: "e",
                                                      ë: "e",
                                                      ẻ: "e",
                                                      ě: "e",
                                                      ȅ: "e",
                                                      ȇ: "e",
                                                      ẹ: "e",
                                                      ệ: "e",
                                                      ȩ: "e",
                                                      ḝ: "e",
                                                      ę: "e",
                                                      ḙ: "e",
                                                      ḛ: "e",
                                                      ɇ: "e",
                                                      ɛ: "e",
                                                      ǝ: "e",
                                                      "ⓕ": "f",
                                                      ｆ: "f",
                                                      ḟ: "f",
                                                      ƒ: "f",
                                                      ꝼ: "f",
                                                      "ⓖ": "g",
                                                      ｇ: "g",
                                                      ǵ: "g",
                                                      ĝ: "g",
                                                      ḡ: "g",
                                                      ğ: "g",
                                                      ġ: "g",
                                                      ǧ: "g",
                                                      ģ: "g",
                                                      ǥ: "g",
                                                      ɠ: "g",
                                                      ꞡ: "g",
                                                      ᵹ: "g",
                                                      ꝿ: "g",
                                                      "ⓗ": "h",
                                                      ｈ: "h",
                                                      ĥ: "h",
                                                      ḣ: "h",
                                                      ḧ: "h",
                                                      ȟ: "h",
                                                      ḥ: "h",
                                                      ḩ: "h",
                                                      ḫ: "h",
                                                      ẖ: "h",
                                                      ħ: "h",
                                                      ⱨ: "h",
                                                      ⱶ: "h",
                                                      ɥ: "h",
                                                      ƕ: "hv",
                                                      "ⓘ": "i",
                                                      ｉ: "i",
                                                      ì: "i",
                                                      í: "i",
                                                      î: "i",
                                                      ĩ: "i",
                                                      ī: "i",
                                                      ĭ: "i",
                                                      ï: "i",
                                                      ḯ: "i",
                                                      ỉ: "i",
                                                      ǐ: "i",
                                                      ȉ: "i",
                                                      ȋ: "i",
                                                      ị: "i",
                                                      į: "i",
                                                      ḭ: "i",
                                                      ɨ: "i",
                                                      ı: "i",
                                                      "ⓙ": "j",
                                                      ｊ: "j",
                                                      ĵ: "j",
                                                      ǰ: "j",
                                                      ɉ: "j",
                                                      "ⓚ": "k",
                                                      ｋ: "k",
                                                      ḱ: "k",
                                                      ǩ: "k",
                                                      ḳ: "k",
                                                      ķ: "k",
                                                      ḵ: "k",
                                                      ƙ: "k",
                                                      ⱪ: "k",
                                                      ꝁ: "k",
                                                      ꝃ: "k",
                                                      ꝅ: "k",
                                                      ꞣ: "k",
                                                      "ⓛ": "l",
                                                      ｌ: "l",
                                                      ŀ: "l",
                                                      ĺ: "l",
                                                      ľ: "l",
                                                      ḷ: "l",
                                                      ḹ: "l",
                                                      ļ: "l",
                                                      ḽ: "l",
                                                      ḻ: "l",
                                                      ſ: "l",
                                                      ł: "l",
                                                      ƚ: "l",
                                                      ɫ: "l",
                                                      ⱡ: "l",
                                                      ꝉ: "l",
                                                      ꞁ: "l",
                                                      ꝇ: "l",
                                                      ǉ: "lj",
                                                      "ⓜ": "m",
                                                      ｍ: "m",
                                                      ḿ: "m",
                                                      ṁ: "m",
                                                      ṃ: "m",
                                                      ɱ: "m",
                                                      ɯ: "m",
                                                      "ⓝ": "n",
                                                      ｎ: "n",
                                                      ǹ: "n",
                                                      ń: "n",
                                                      ñ: "n",
                                                      ṅ: "n",
                                                      ň: "n",
                                                      ṇ: "n",
                                                      ņ: "n",
                                                      ṋ: "n",
                                                      ṉ: "n",
                                                      ƞ: "n",
                                                      ɲ: "n",
                                                      ŉ: "n",
                                                      ꞑ: "n",
                                                      ꞥ: "n",
                                                      ǌ: "nj",
                                                      "ⓞ": "o",
                                                      ｏ: "o",
                                                      ò: "o",
                                                      ó: "o",
                                                      ô: "o",
                                                      ồ: "o",
                                                      ố: "o",
                                                      ỗ: "o",
                                                      ổ: "o",
                                                      õ: "o",
                                                      ṍ: "o",
                                                      ȭ: "o",
                                                      ṏ: "o",
                                                      ō: "o",
                                                      ṑ: "o",
                                                      ṓ: "o",
                                                      ŏ: "o",
                                                      ȯ: "o",
                                                      ȱ: "o",
                                                      ö: "o",
                                                      ȫ: "o",
                                                      ỏ: "o",
                                                      ő: "o",
                                                      ǒ: "o",
                                                      ȍ: "o",
                                                      ȏ: "o",
                                                      ơ: "o",
                                                      ờ: "o",
                                                      ớ: "o",
                                                      ỡ: "o",
                                                      ở: "o",
                                                      ợ: "o",
                                                      ọ: "o",
                                                      ộ: "o",
                                                      ǫ: "o",
                                                      ǭ: "o",
                                                      ø: "o",
                                                      ǿ: "o",
                                                      ɔ: "o",
                                                      ꝋ: "o",
                                                      ꝍ: "o",
                                                      ɵ: "o",
                                                      œ: "oe",
                                                      ƣ: "oi",
                                                      ȣ: "ou",
                                                      ꝏ: "oo",
                                                      "ⓟ": "p",
                                                      ｐ: "p",
                                                      ṕ: "p",
                                                      ṗ: "p",
                                                      ƥ: "p",
                                                      ᵽ: "p",
                                                      ꝑ: "p",
                                                      ꝓ: "p",
                                                      ꝕ: "p",
                                                      "ⓠ": "q",
                                                      ｑ: "q",
                                                      ɋ: "q",
                                                      ꝗ: "q",
                                                      ꝙ: "q",
                                                      "ⓡ": "r",
                                                      ｒ: "r",
                                                      ŕ: "r",
                                                      ṙ: "r",
                                                      ř: "r",
                                                      ȑ: "r",
                                                      ȓ: "r",
                                                      ṛ: "r",
                                                      ṝ: "r",
                                                      ŗ: "r",
                                                      ṟ: "r",
                                                      ɍ: "r",
                                                      ɽ: "r",
                                                      ꝛ: "r",
                                                      ꞧ: "r",
                                                      ꞃ: "r",
                                                      "ⓢ": "s",
                                                      ｓ: "s",
                                                      ß: "s",
                                                      ś: "s",
                                                      ṥ: "s",
                                                      ŝ: "s",
                                                      ṡ: "s",
                                                      š: "s",
                                                      ṧ: "s",
                                                      ṣ: "s",
                                                      ṩ: "s",
                                                      ș: "s",
                                                      ş: "s",
                                                      ȿ: "s",
                                                      ꞩ: "s",
                                                      ꞅ: "s",
                                                      ẛ: "s",
                                                      "ⓣ": "t",
                                                      ｔ: "t",
                                                      ṫ: "t",
                                                      ẗ: "t",
                                                      ť: "t",
                                                      ṭ: "t",
                                                      ț: "t",
                                                      ţ: "t",
                                                      ṱ: "t",
                                                      ṯ: "t",
                                                      ŧ: "t",
                                                      ƭ: "t",
                                                      ʈ: "t",
                                                      ⱦ: "t",
                                                      ꞇ: "t",
                                                      ꜩ: "tz",
                                                      "ⓤ": "u",
                                                      ｕ: "u",
                                                      ù: "u",
                                                      ú: "u",
                                                      û: "u",
                                                      ũ: "u",
                                                      ṹ: "u",
                                                      ū: "u",
                                                      ṻ: "u",
                                                      ŭ: "u",
                                                      ü: "u",
                                                      ǜ: "u",
                                                      ǘ: "u",
                                                      ǖ: "u",
                                                      ǚ: "u",
                                                      ủ: "u",
                                                      ů: "u",
                                                      ű: "u",
                                                      ǔ: "u",
                                                      ȕ: "u",
                                                      ȗ: "u",
                                                      ư: "u",
                                                      ừ: "u",
                                                      ứ: "u",
                                                      ữ: "u",
                                                      ử: "u",
                                                      ự: "u",
                                                      ụ: "u",
                                                      ṳ: "u",
                                                      ų: "u",
                                                      ṷ: "u",
                                                      ṵ: "u",
                                                      ʉ: "u",
                                                      "ⓥ": "v",
                                                      ｖ: "v",
                                                      ṽ: "v",
                                                      ṿ: "v",
                                                      ʋ: "v",
                                                      ꝟ: "v",
                                                      ʌ: "v",
                                                      ꝡ: "vy",
                                                      "ⓦ": "w",
                                                      ｗ: "w",
                                                      ẁ: "w",
                                                      ẃ: "w",
                                                      ŵ: "w",
                                                      ẇ: "w",
                                                      ẅ: "w",
                                                      ẘ: "w",
                                                      ẉ: "w",
                                                      ⱳ: "w",
                                                      "ⓧ": "x",
                                                      ｘ: "x",
                                                      ẋ: "x",
                                                      ẍ: "x",
                                                      "ⓨ": "y",
                                                      ｙ: "y",
                                                      ỳ: "y",
                                                      ý: "y",
                                                      ŷ: "y",
                                                      ỹ: "y",
                                                      ȳ: "y",
                                                      ẏ: "y",
                                                      ÿ: "y",
                                                      ỷ: "y",
                                                      ẙ: "y",
                                                      ỵ: "y",
                                                      ƴ: "y",
                                                      ɏ: "y",
                                                      ỿ: "y",
                                                      "ⓩ": "z",
                                                      ｚ: "z",
                                                      ź: "z",
                                                      ẑ: "z",
                                                      ż: "z",
                                                      ž: "z",
                                                      ẓ: "z",
                                                      ẕ: "z",
                                                      ƶ: "z",
                                                      ȥ: "z",
                                                      ɀ: "z",
                                                      ⱬ: "z",
                                                      ꝣ: "z",
                                                      Ά: "Α",
                                                      Έ: "Ε",
                                                      Ή: "Η",
                                                      Ί: "Ι",
                                                      Ϊ: "Ι",
                                                      Ό: "Ο",
                                                      Ύ: "Υ",
                                                      Ϋ: "Υ",
                                                      Ώ: "Ω",
                                                      ά: "α",
                                                      έ: "ε",
                                                      ή: "η",
                                                      ί: "ι",
                                                      ϊ: "ι",
                                                      ΐ: "ι",
                                                      ό: "ο",
                                                      ύ: "υ",
                                                      ϋ: "υ",
                                                      ΰ: "υ",
                                                      ώ: "ω",
                                                      ς: "σ",
                                                      "’": "'",
                                             };
                                    }),
                                    e.define("select2/data/base", ["../utils"], function (r) {
                                             function n(e, t) {
                                                      n.__super__.constructor.call(this);
                                             }
                                             return (
                                                      r.Extend(n, r.Observable),
                                                      (n.prototype.current = function (e) {
                                                               throw new Error("The `current` method must be defined in child classes.");
                                                      }),
                                                      (n.prototype.query = function (e, t) {
                                                               throw new Error("The `query` method must be defined in child classes.");
                                                      }),
                                                      (n.prototype.bind = function (e, t) {}),
                                                      (n.prototype.destroy = function () {}),
                                                      (n.prototype.generateResultId = function (e, t) {
                                                               var n = e.id + "-result-";
                                                               return (n += r.generateChars(4)), null != t.id ? (n += "-" + t.id.toString()) : (n += "-" + r.generateChars(4)), n;
                                                      }),
                                                      n
                                             );
                                    }),
                                    e.define("select2/data/select", ["./base", "../utils", "jquery"], function (e, a, l) {
                                             function n(e, t) {
                                                      (this.$element = e), (this.options = t), n.__super__.constructor.call(this);
                                             }
                                             return (
                                                      a.Extend(n, e),
                                                      (n.prototype.current = function (e) {
                                                               var n = [],
                                                                        r = this;
                                                               this.$element.find(":selected").each(function () {
                                                                        var e = l(this),
                                                                                 t = r.item(e);
                                                                        n.push(t);
                                                               }),
                                                                        e(n);
                                                      }),
                                                      (n.prototype.select = function (i) {
                                                               var o = this;
                                                               if (((i.selected = !0), l(i.element).is("option"))) return (i.element.selected = !0), void this.$element.trigger("change");
                                                               if (this.$element.prop("multiple"))
                                                                        this.current(function (e) {
                                                                                 var t = [];
                                                                                 (i = [i]).push.apply(i, e);
                                                                                 for (var n = 0; n < i.length; n++) {
                                                                                          var r = i[n].id;
                                                                                          -1 === l.inArray(r, t) && t.push(r);
                                                                                 }
                                                                                 o.$element.val(t), o.$element.trigger("change");
                                                                        });
                                                               else {
                                                                        var e = i.id;
                                                                        this.$element.val(e), this.$element.trigger("change");
                                                               }
                                                      }),
                                                      (n.prototype.unselect = function (i) {
                                                               var o = this;
                                                               if (this.$element.prop("multiple")) {
                                                                        if (((i.selected = !1), l(i.element).is("option"))) return (i.element.selected = !1), void this.$element.trigger("change");
                                                                        this.current(function (e) {
                                                                                 for (var t = [], n = 0; n < e.length; n++) {
                                                                                          var r = e[n].id;
                                                                                          r !== i.id && -1 === l.inArray(r, t) && t.push(r);
                                                                                 }
                                                                                 o.$element.val(t), o.$element.trigger("change");
                                                                        });
                                                               }
                                                      }),
                                                      (n.prototype.bind = function (e, t) {
                                                               var n = this;
                                                               (this.container = e).on("select", function (e) {
                                                                        n.select(e.data);
                                                               }),
                                                                        e.on("unselect", function (e) {
                                                                                 n.unselect(e.data);
                                                                        });
                                                      }),
                                                      (n.prototype.destroy = function () {
                                                               this.$element.find("*").each(function () {
                                                                        a.RemoveData(this);
                                                               });
                                                      }),
                                                      (n.prototype.query = function (r, e) {
                                                               var i = [],
                                                                        o = this;
                                                               this.$element.children().each(function () {
                                                                        var e = l(this);
                                                                        if (e.is("option") || e.is("optgroup")) {
                                                                                 var t = o.item(e),
                                                                                          n = o.matches(r, t);
                                                                                 null !== n && i.push(n);
                                                                        }
                                                               }),
                                                                        e({ results: i });
                                                      }),
                                                      (n.prototype.addOptions = function (e) {
                                                               a.appendMany(this.$element, e);
                                                      }),
                                                      (n.prototype.option = function (e) {
                                                               var t;
                                                               e.children
                                                                        ? ((t = document.createElement("optgroup")).label = e.text)
                                                                        : void 0 !== (t = document.createElement("option")).textContent
                                                                        ? (t.textContent = e.text)
                                                                        : (t.innerText = e.text),
                                                                        void 0 !== e.id && (t.value = e.id),
                                                                        e.disabled && (t.disabled = !0),
                                                                        e.selected && (t.selected = !0),
                                                                        e.title && (t.title = e.title);
                                                               var n = l(t),
                                                                        r = this._normalizeItem(e);
                                                               return (r.element = t), a.StoreData(t, "data", r), n;
                                                      }),
                                                      (n.prototype.item = function (e) {
                                                               var t = {};
                                                               if (null != (t = a.GetData(e[0], "data"))) return t;
                                                               if (e.is("option")) t = { id: e.val(), text: e.text(), disabled: e.prop("disabled"), selected: e.prop("selected"), title: e.prop("title") };
                                                               else if (e.is("optgroup")) {
                                                                        t = { text: e.prop("label"), children: [], title: e.prop("title") };
                                                                        for (var n = e.children("option"), r = [], i = 0; i < n.length; i++) {
                                                                                 var o = l(n[i]),
                                                                                          s = this.item(o);
                                                                                 r.push(s);
                                                                        }
                                                                        t.children = r;
                                                               }
                                                               return ((t = this._normalizeItem(t)).element = e[0]), a.StoreData(e[0], "data", t), t;
                                                      }),
                                                      (n.prototype._normalizeItem = function (e) {
                                                               e !== Object(e) && (e = { id: e, text: e });
                                                               return (
                                                                        null != (e = l.extend({}, { text: "" }, e)).id && (e.id = e.id.toString()),
                                                                        null != e.text && (e.text = e.text.toString()),
                                                                        null == e._resultId && e.id && null != this.container && (e._resultId = this.generateResultId(this.container, e)),
                                                                        l.extend({}, { selected: !1, disabled: !1 }, e)
                                                               );
                                                      }),
                                                      (n.prototype.matches = function (e, t) {
                                                               return this.options.get("matcher")(e, t);
                                                      }),
                                                      n
                                             );
                                    }),
                                    e.define("select2/data/array", ["./select", "../utils", "jquery"], function (e, f, g) {
                                             function r(e, t) {
                                                      (this._dataToConvert = t.get("data") || []), r.__super__.constructor.call(this, e, t);
                                             }
                                             return (
                                                      f.Extend(r, e),
                                                      (r.prototype.bind = function (e, t) {
                                                               r.__super__.bind.call(this, e, t), this.addOptions(this.convertToOptions(this._dataToConvert));
                                                      }),
                                                      (r.prototype.select = function (n) {
                                                               var e = this.$element.find("option").filter(function (e, t) {
                                                                        return t.value == n.id.toString();
                                                               });
                                                               0 === e.length && ((e = this.option(n)), this.addOptions(e)), r.__super__.select.call(this, n);
                                                      }),
                                                      (r.prototype.convertToOptions = function (e) {
                                                               var t = this,
                                                                        n = this.$element.find("option"),
                                                                        r = n
                                                                                 .map(function () {
                                                                                          return t.item(g(this)).id;
                                                                                 })
                                                                                 .get(),
                                                                        i = [];
                                                               function o(e) {
                                                                        return function () {
                                                                                 return g(this).val() == e.id;
                                                                        };
                                                               }
                                                               for (var s = 0; s < e.length; s++) {
                                                                        var a = this._normalizeItem(e[s]);
                                                                        if (0 <= g.inArray(a.id, r)) {
                                                                                 var l = n.filter(o(a)),
                                                                                          c = this.item(l),
                                                                                          u = g.extend(!0, {}, a, c),
                                                                                          d = this.option(u);
                                                                                 l.replaceWith(d);
                                                                        } else {
                                                                                 var p = this.option(a);
                                                                                 if (a.children) {
                                                                                          var h = this.convertToOptions(a.children);
                                                                                          f.appendMany(p, h);
                                                                                 }
                                                                                 i.push(p);
                                                                        }
                                                               }
                                                               return i;
                                                      }),
                                                      r
                                             );
                                    }),
                                    e.define("select2/data/ajax", ["./array", "../utils", "jquery"], function (e, t, o) {
                                             function n(e, t) {
                                                      (this.ajaxOptions = this._applyDefaults(t.get("ajax"))),
                                                               null != this.ajaxOptions.processResults && (this.processResults = this.ajaxOptions.processResults),
                                                               n.__super__.constructor.call(this, e, t);
                                             }
                                             return (
                                                      t.Extend(n, e),
                                                      (n.prototype._applyDefaults = function (e) {
                                                               var t = {
                                                                        data: function (e) {
                                                                                 return o.extend({}, e, { q: e.term });
                                                                        },
                                                                        transport: function (e, t, n) {
                                                                                 var r = o.ajax(e);
                                                                                 return r.then(t), r.fail(n), r;
                                                                        },
                                                               };
                                                               return o.extend({}, t, e, !0);
                                                      }),
                                                      (n.prototype.processResults = function (e) {
                                                               return e;
                                                      }),
                                                      (n.prototype.query = function (n, r) {
                                                               var i = this;
                                                               null != this._request && (o.isFunction(this._request.abort) && this._request.abort(), (this._request = null));
                                                               var t = o.extend({ type: "GET" }, this.ajaxOptions);
                                                               function e() {
                                                                        var e = t.transport(
                                                                                 t,
                                                                                 function (e) {
                                                                                          var t = i.processResults(e, n);
                                                                                          i.options.get("debug") &&
                                                                                                   window.console &&
                                                                                                   console.error &&
                                                                                                   ((t && t.results && o.isArray(t.results)) ||
                                                                                                            console.error("Select2: The AJAX results did not return an array in the `results` key of the response.")),
                                                                                                   r(t);
                                                                                 },
                                                                                 function () {
                                                                                          ("status" in e && (0 === e.status || "0" === e.status)) || i.trigger("results:message", { message: "errorLoading" });
                                                                                 }
                                                                        );
                                                                        i._request = e;
                                                               }
                                                               "function" == typeof t.url && (t.url = t.url.call(this.$element, n)),
                                                                        "function" == typeof t.data && (t.data = t.data.call(this.$element, n)),
                                                                        this.ajaxOptions.delay && null != n.term
                                                                                 ? (this._queryTimeout && window.clearTimeout(this._queryTimeout), (this._queryTimeout = window.setTimeout(e, this.ajaxOptions.delay)))
                                                                                 : e();
                                                      }),
                                                      n
                                             );
                                    }),
                                    e.define("select2/data/tags", ["jquery"], function (u) {
                                             function e(e, t, n) {
                                                      var r = n.get("tags"),
                                                               i = n.get("createTag");
                                                      void 0 !== i && (this.createTag = i);
                                                      var o = n.get("insertTag");
                                                      if ((void 0 !== o && (this.insertTag = o), e.call(this, t, n), u.isArray(r)))
                                                               for (var s = 0; s < r.length; s++) {
                                                                        var a = r[s],
                                                                                 l = this._normalizeItem(a),
                                                                                 c = this.option(l);
                                                                        this.$element.append(c);
                                                               }
                                             }
                                             return (
                                                      (e.prototype.query = function (e, c, u) {
                                                               var d = this;
                                                               this._removeOldTags(),
                                                                        null != c.term && null == c.page
                                                                                 ? e.call(this, c, function e(t, n) {
                                                                                            for (var r = t.results, i = 0; i < r.length; i++) {
                                                                                                     var o = r[i],
                                                                                                              s = null != o.children && !e({ results: o.children }, !0);
                                                                                                     if ((o.text || "").toUpperCase() === (c.term || "").toUpperCase() || s) return !n && ((t.data = r), void u(t));
                                                                                            }
                                                                                            if (n) return !0;
                                                                                            var a = d.createTag(c);
                                                                                            if (null != a) {
                                                                                                     var l = d.option(a);
                                                                                                     l.attr("data-select2-tag", !0), d.addOptions([l]), d.insertTag(r, a);
                                                                                            }
                                                                                            (t.results = r), u(t);
                                                                                   })
                                                                                 : e.call(this, c, u);
                                                      }),
                                                      (e.prototype.createTag = function (e, t) {
                                                               var n = u.trim(t.term);
                                                               return "" === n ? null : { id: n, text: n };
                                                      }),
                                                      (e.prototype.insertTag = function (e, t, n) {
                                                               t.unshift(n);
                                                      }),
                                                      (e.prototype._removeOldTags = function (e) {
                                                               this.$element.find("option[data-select2-tag]").each(function () {
                                                                        this.selected || u(this).remove();
                                                               });
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/data/tokenizer", ["jquery"], function (d) {
                                             function e(e, t, n) {
                                                      var r = n.get("tokenizer");
                                                      void 0 !== r && (this.tokenizer = r), e.call(this, t, n);
                                             }
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               e.call(this, t, n), (this.$search = t.dropdown.$search || t.selection.$search || n.find(".select2-search__field"));
                                                      }),
                                                      (e.prototype.query = function (e, t, n) {
                                                               var r = this;
                                                               t.term = t.term || "";
                                                               var i = this.tokenizer(t, this.options, function (e) {
                                                                        var t = r._normalizeItem(e);
                                                                        if (
                                                                                 !r.$element.find("option").filter(function () {
                                                                                          return d(this).val() === t.id;
                                                                                 }).length
                                                                        ) {
                                                                                 var n = r.option(t);
                                                                                 n.attr("data-select2-tag", !0), r._removeOldTags(), r.addOptions([n]);
                                                                        }
                                                                        !(function (e) {
                                                                                 r.trigger("select", { data: e });
                                                                        })(t);
                                                               });
                                                               i.term !== t.term && (this.$search.length && (this.$search.val(i.term), this.$search.trigger("focus")), (t.term = i.term)), e.call(this, t, n);
                                                      }),
                                                      (e.prototype.tokenizer = function (e, t, n, r) {
                                                               for (
                                                                        var i = n.get("tokenSeparators") || [],
                                                                                 o = t.term,
                                                                                 s = 0,
                                                                                 a =
                                                                                          this.createTag ||
                                                                                          function (e) {
                                                                                                   return { id: e.term, text: e.term };
                                                                                          };
                                                                        s < o.length;

                                                               ) {
                                                                        var l = o[s];
                                                                        if (-1 !== d.inArray(l, i)) {
                                                                                 var c = o.substr(0, s),
                                                                                          u = a(d.extend({}, t, { term: c }));
                                                                                 null != u ? (r(u), (o = o.substr(s + 1) || ""), (s = 0)) : s++;
                                                                        } else s++;
                                                               }
                                                               return { term: o };
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/data/minimumInputLength", [], function () {
                                             function e(e, t, n) {
                                                      (this.minimumInputLength = n.get("minimumInputLength")), e.call(this, t, n);
                                             }
                                             return (
                                                      (e.prototype.query = function (e, t, n) {
                                                               (t.term = t.term || ""),
                                                                        t.term.length < this.minimumInputLength
                                                                                 ? this.trigger("results:message", { message: "inputTooShort", args: { minimum: this.minimumInputLength, input: t.term, params: t } })
                                                                                 : e.call(this, t, n);
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/data/maximumInputLength", [], function () {
                                             function e(e, t, n) {
                                                      (this.maximumInputLength = n.get("maximumInputLength")), e.call(this, t, n);
                                             }
                                             return (
                                                      (e.prototype.query = function (e, t, n) {
                                                               (t.term = t.term || ""),
                                                                        0 < this.maximumInputLength && t.term.length > this.maximumInputLength
                                                                                 ? this.trigger("results:message", { message: "inputTooLong", args: { maximum: this.maximumInputLength, input: t.term, params: t } })
                                                                                 : e.call(this, t, n);
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/data/maximumSelectionLength", [], function () {
                                             function e(e, t, n) {
                                                      (this.maximumSelectionLength = n.get("maximumSelectionLength")), e.call(this, t, n);
                                             }
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this;
                                                               e.call(this, t, n),
                                                                        t.on("select", function () {
                                                                                 r._checkIfMaximumSelected();
                                                                        });
                                                      }),
                                                      (e.prototype.query = function (e, t, n) {
                                                               var r = this;
                                                               this._checkIfMaximumSelected(function () {
                                                                        e.call(r, t, n);
                                                               });
                                                      }),
                                                      (e.prototype._checkIfMaximumSelected = function (e, n) {
                                                               var r = this;
                                                               this.current(function (e) {
                                                                        var t = null != e ? e.length : 0;
                                                                        0 < r.maximumSelectionLength && t >= r.maximumSelectionLength
                                                                                 ? r.trigger("results:message", { message: "maximumSelected", args: { maximum: r.maximumSelectionLength } })
                                                                                 : n && n();
                                                               });
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/dropdown", ["jquery", "./utils"], function (t, e) {
                                             function n(e, t) {
                                                      (this.$element = e), (this.options = t), n.__super__.constructor.call(this);
                                             }
                                             return (
                                                      e.Extend(n, e.Observable),
                                                      (n.prototype.render = function () {
                                                               var e = t('<span class="select2-dropdown"><span class="select2-results"></span></span>');
                                                               return e.attr("dir", this.options.get("dir")), (this.$dropdown = e);
                                                      }),
                                                      (n.prototype.bind = function () {}),
                                                      (n.prototype.position = function (e, t) {}),
                                                      (n.prototype.destroy = function () {
                                                               this.$dropdown.remove();
                                                      }),
                                                      n
                                             );
                                    }),
                                    e.define("select2/dropdown/search", ["jquery", "../utils"], function (o, e) {
                                             function t() {}
                                             return (
                                                      (t.prototype.render = function (e) {
                                                               var t = e.call(this),
                                                                        n = o(
                                                                                 '<span class="select2-search select2-search--dropdown"><input class="select2-search__field" type="search" tabindex="-1" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" /></span>'
                                                                        );
                                                               return (this.$searchContainer = n), (this.$search = n.find("input")), t.prepend(n), t;
                                                      }),
                                                      (t.prototype.bind = function (e, t, n) {
                                                               var r = this,
                                                                        i = t.id + "-results";
                                                               e.call(this, t, n),
                                                                        this.$search.on("keydown", function (e) {
                                                                                 r.trigger("keypress", e), (r._keyUpPrevented = e.isDefaultPrevented());
                                                                        }),
                                                                        this.$search.on("input", function (e) {
                                                                                 o(this).off("keyup");
                                                                        }),
                                                                        this.$search.on("keyup input", function (e) {
                                                                                 r.handleSearch(e);
                                                                        }),
                                                                        t.on("open", function () {
                                                                                 r.$search.attr("tabindex", 0),
                                                                                          r.$search.attr("aria-controls", i),
                                                                                          r.$search.trigger("focus"),
                                                                                          window.setTimeout(function () {
                                                                                                   r.$search.trigger("focus");
                                                                                          }, 0);
                                                                        }),
                                                                        t.on("close", function () {
                                                                                 r.$search.attr("tabindex", -1),
                                                                                          r.$search.removeAttr("aria-controls"),
                                                                                          r.$search.removeAttr("aria-activedescendant"),
                                                                                          r.$search.val(""),
                                                                                          r.$search.trigger("blur");
                                                                        }),
                                                                        t.on("focus", function () {
                                                                                 t.isOpen() || r.$search.trigger("focus");
                                                                        }),
                                                                        t.on("results:all", function (e) {
                                                                                 (null != e.query.term && "" !== e.query.term) ||
                                                                                          (r.showSearch(e) ? r.$searchContainer.removeClass("select2-search--hide") : r.$searchContainer.addClass("select2-search--hide"));
                                                                        }),
                                                                        t.on("results:focus", function (e) {
                                                                                 e.data._resultId ? r.$search.attr("aria-activedescendant", e.data._resultId) : r.$search.removeAttr("aria-activedescendant");
                                                                        });
                                                      }),
                                                      (t.prototype.handleSearch = function (e) {
                                                               if (!this._keyUpPrevented) {
                                                                        var t = this.$search.val();
                                                                        this.trigger("query", { term: t });
                                                               }
                                                               this._keyUpPrevented = !1;
                                                      }),
                                                      (t.prototype.showSearch = function (e, t) {
                                                               return !0;
                                                      }),
                                                      t
                                             );
                                    }),
                                    e.define("select2/dropdown/hidePlaceholder", [], function () {
                                             function e(e, t, n, r) {
                                                      (this.placeholder = this.normalizePlaceholder(n.get("placeholder"))), e.call(this, t, n, r);
                                             }
                                             return (
                                                      (e.prototype.append = function (e, t) {
                                                               (t.results = this.removePlaceholder(t.results)), e.call(this, t);
                                                      }),
                                                      (e.prototype.normalizePlaceholder = function (e, t) {
                                                               return "string" == typeof t && (t = { id: "", text: t }), t;
                                                      }),
                                                      (e.prototype.removePlaceholder = function (e, t) {
                                                               for (var n = t.slice(0), r = t.length - 1; 0 <= r; r--) {
                                                                        var i = t[r];
                                                                        this.placeholder.id === i.id && n.splice(r, 1);
                                                               }
                                                               return n;
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/dropdown/infiniteScroll", ["jquery"], function (n) {
                                             function e(e, t, n, r) {
                                                      (this.lastParams = {}), e.call(this, t, n, r), (this.$loadingMore = this.createLoadingMore()), (this.loading = !1);
                                             }
                                             return (
                                                      (e.prototype.append = function (e, t) {
                                                               this.$loadingMore.remove(), (this.loading = !1), e.call(this, t), this.showLoadingMore(t) && (this.$results.append(this.$loadingMore), this.loadMoreIfNeeded());
                                                      }),
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this;
                                                               e.call(this, t, n),
                                                                        t.on("query", function (e) {
                                                                                 (r.lastParams = e), (r.loading = !0);
                                                                        }),
                                                                        t.on("query:append", function (e) {
                                                                                 (r.lastParams = e), (r.loading = !0);
                                                                        }),
                                                                        this.$results.on("scroll", this.loadMoreIfNeeded.bind(this));
                                                      }),
                                                      (e.prototype.loadMoreIfNeeded = function () {
                                                               var e = n.contains(document.documentElement, this.$loadingMore[0]);
                                                               if (!this.loading && e) {
                                                                        var t = this.$results.offset().top + this.$results.outerHeight(!1);
                                                                        this.$loadingMore.offset().top + this.$loadingMore.outerHeight(!1) <= t + 50 && this.loadMore();
                                                               }
                                                      }),
                                                      (e.prototype.loadMore = function () {
                                                               this.loading = !0;
                                                               var e = n.extend({}, { page: 1 }, this.lastParams);
                                                               e.page++, this.trigger("query:append", e);
                                                      }),
                                                      (e.prototype.showLoadingMore = function (e, t) {
                                                               return t.pagination && t.pagination.more;
                                                      }),
                                                      (e.prototype.createLoadingMore = function () {
                                                               var e = n('<li class="select2-results__option select2-results__option--load-more"role="option" aria-disabled="true"></li>'),
                                                                        t = this.options.get("translations").get("loadingMore");
                                                               return e.html(t(this.lastParams)), e;
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/dropdown/attachBody", ["jquery", "../utils"], function (f, a) {
                                             function e(e, t, n) {
                                                      (this.$dropdownParent = f(n.get("dropdownParent") || document.body)), e.call(this, t, n);
                                             }
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this;
                                                               e.call(this, t, n),
                                                                        t.on("open", function () {
                                                                                 r._showDropdown(), r._attachPositioningHandler(t), r._bindContainerResultHandlers(t);
                                                                        }),
                                                                        t.on("close", function () {
                                                                                 r._hideDropdown(), r._detachPositioningHandler(t);
                                                                        }),
                                                                        this.$dropdownContainer.on("mousedown", function (e) {
                                                                                 e.stopPropagation();
                                                                        });
                                                      }),
                                                      (e.prototype.destroy = function (e) {
                                                               e.call(this), this.$dropdownContainer.remove();
                                                      }),
                                                      (e.prototype.position = function (e, t, n) {
                                                               t.attr("class", n.attr("class")), t.removeClass("select2"), t.addClass("select2-container--open"), t.css({ position: "absolute", top: -999999 }), (this.$container = n);
                                                      }),
                                                      (e.prototype.render = function (e) {
                                                               var t = f("<span></span>"),
                                                                        n = e.call(this);
                                                               return t.append(n), (this.$dropdownContainer = t);
                                                      }),
                                                      (e.prototype._hideDropdown = function (e) {
                                                               this.$dropdownContainer.detach();
                                                      }),
                                                      (e.prototype._bindContainerResultHandlers = function (e, t) {
                                                               if (!this._containerResultsHandlersBound) {
                                                                        var n = this;
                                                                        t.on("results:all", function () {
                                                                                 n._positionDropdown(), n._resizeDropdown();
                                                                        }),
                                                                                 t.on("results:append", function () {
                                                                                          n._positionDropdown(), n._resizeDropdown();
                                                                                 }),
                                                                                 t.on("results:message", function () {
                                                                                          n._positionDropdown(), n._resizeDropdown();
                                                                                 }),
                                                                                 t.on("select", function () {
                                                                                          n._positionDropdown(), n._resizeDropdown();
                                                                                 }),
                                                                                 t.on("unselect", function () {
                                                                                          n._positionDropdown(), n._resizeDropdown();
                                                                                 }),
                                                                                 (this._containerResultsHandlersBound = !0);
                                                               }
                                                      }),
                                                      (e.prototype._attachPositioningHandler = function (e, t) {
                                                               var n = this,
                                                                        r = "scroll.select2." + t.id,
                                                                        i = "resize.select2." + t.id,
                                                                        o = "orientationchange.select2." + t.id,
                                                                        s = this.$container.parents().filter(a.hasScroll);
                                                               s.each(function () {
                                                                        a.StoreData(this, "select2-scroll-position", { x: f(this).scrollLeft(), y: f(this).scrollTop() });
                                                               }),
                                                                        s.on(r, function (e) {
                                                                                 var t = a.GetData(this, "select2-scroll-position");
                                                                                 f(this).scrollTop(t.y);
                                                                        }),
                                                                        f(window).on(r + " " + i + " " + o, function (e) {
                                                                                 n._positionDropdown(), n._resizeDropdown();
                                                                        });
                                                      }),
                                                      (e.prototype._detachPositioningHandler = function (e, t) {
                                                               var n = "scroll.select2." + t.id,
                                                                        r = "resize.select2." + t.id,
                                                                        i = "orientationchange.select2." + t.id;
                                                               this.$container.parents().filter(a.hasScroll).off(n), f(window).off(n + " " + r + " " + i);
                                                      }),
                                                      (e.prototype._positionDropdown = function () {
                                                               var e = f(window),
                                                                        t = this.$dropdown.hasClass("select2-dropdown--above"),
                                                                        n = this.$dropdown.hasClass("select2-dropdown--below"),
                                                                        r = null,
                                                                        i = this.$container.offset();
                                                               i.bottom = i.top + this.$container.outerHeight(!1);
                                                               var o = { height: this.$container.outerHeight(!1) };
                                                               (o.top = i.top), (o.bottom = i.top + o.height);
                                                               var s = this.$dropdown.outerHeight(!1),
                                                                        a = e.scrollTop(),
                                                                        l = e.scrollTop() + e.height(),
                                                                        c = a < i.top - s,
                                                                        u = l > i.bottom + s,
                                                                        d = { left: i.left, top: o.bottom },
                                                                        p = this.$dropdownParent;
                                                               "static" === p.css("position") && (p = p.offsetParent());
                                                               var h = { top: 0, left: 0 };
                                                               (f.contains(document.body, p[0]) || p[0].isConnected) && (h = p.offset()),
                                                                        (d.top -= h.top),
                                                                        (d.left -= h.left),
                                                                        t || n || (r = "below"),
                                                                        u || !c || t ? !c && u && t && (r = "below") : (r = "above"),
                                                                        ("above" == r || (t && "below" !== r)) && (d.top = o.top - h.top - s),
                                                                        null != r &&
                                                                                 (this.$dropdown.removeClass("select2-dropdown--below select2-dropdown--above").addClass("select2-dropdown--" + r),
                                                                                 this.$container.removeClass("select2-container--below select2-container--above").addClass("select2-container--" + r)),
                                                                        this.$dropdownContainer.css(d);
                                                      }),
                                                      (e.prototype._resizeDropdown = function () {
                                                               var e = { width: this.$container.outerWidth(!1) + "px" };
                                                               this.options.get("dropdownAutoWidth") && ((e.minWidth = e.width), (e.position = "relative"), (e.width = "auto")), this.$dropdown.css(e);
                                                      }),
                                                      (e.prototype._showDropdown = function (e) {
                                                               this.$dropdownContainer.appendTo(this.$dropdownParent), this._positionDropdown(), this._resizeDropdown();
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/dropdown/minimumResultsForSearch", [], function () {
                                             function e(e, t, n, r) {
                                                      (this.minimumResultsForSearch = n.get("minimumResultsForSearch")), this.minimumResultsForSearch < 0 && (this.minimumResultsForSearch = 1 / 0), e.call(this, t, n, r);
                                             }
                                             return (
                                                      (e.prototype.showSearch = function (e, t) {
                                                               return (
                                                                        !(
                                                                                 (function e(t) {
                                                                                          for (var n = 0, r = 0; r < t.length; r++) {
                                                                                                   var i = t[r];
                                                                                                   i.children ? (n += e(i.children)) : n++;
                                                                                          }
                                                                                          return n;
                                                                                 })(t.data.results) < this.minimumResultsForSearch
                                                                        ) && e.call(this, t)
                                                               );
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/dropdown/selectOnClose", ["../utils"], function (o) {
                                             function e() {}
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this;
                                                               e.call(this, t, n),
                                                                        t.on("close", function (e) {
                                                                                 r._handleSelectOnClose(e);
                                                                        });
                                                      }),
                                                      (e.prototype._handleSelectOnClose = function (e, t) {
                                                               if (t && null != t.originalSelect2Event) {
                                                                        var n = t.originalSelect2Event;
                                                                        if ("select" === n._type || "unselect" === n._type) return;
                                                               }
                                                               var r = this.getHighlightedResults();
                                                               if (!(r.length < 1)) {
                                                                        var i = o.GetData(r[0], "data");
                                                                        (null != i.element && i.element.selected) || (null == i.element && i.selected) || this.trigger("select", { data: i });
                                                               }
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/dropdown/closeOnSelect", [], function () {
                                             function e() {}
                                             return (
                                                      (e.prototype.bind = function (e, t, n) {
                                                               var r = this;
                                                               e.call(this, t, n),
                                                                        t.on("select", function (e) {
                                                                                 r._selectTriggered(e);
                                                                        }),
                                                                        t.on("unselect", function (e) {
                                                                                 r._selectTriggered(e);
                                                                        });
                                                      }),
                                                      (e.prototype._selectTriggered = function (e, t) {
                                                               var n = t.originalEvent;
                                                               (n && (n.ctrlKey || n.metaKey)) || this.trigger("close", { originalEvent: n, originalSelect2Event: t });
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/i18n/en", [], function () {
                                             return {
                                                      errorLoading: function () {
                                                               return "The results could not be loaded.";
                                                      },
                                                      inputTooLong: function (e) {
                                                               var t = e.input.length - e.maximum,
                                                                        n = "Please delete " + t + " character";
                                                               return 1 != t && (n += "s"), n;
                                                      },
                                                      inputTooShort: function (e) {
                                                               return "Please enter " + (e.minimum - e.input.length) + " or more characters";
                                                      },
                                                      loadingMore: function () {
                                                               return "Loading more results…";
                                                      },
                                                      maximumSelected: function (e) {
                                                               var t = "You can only select " + e.maximum + " item";
                                                               return 1 != e.maximum && (t += "s"), t;
                                                      },
                                                      noResults: function () {
                                                               return "No results found";
                                                      },
                                                      searching: function () {
                                                               return "Searching…";
                                                      },
                                                      removeAllItems: function () {
                                                               return "Remove all items";
                                                      },
                                             };
                                    }),
                                    e.define(
                                             "select2/defaults",
                                             [
                                                      "jquery",
                                                      "require",
                                                      "./results",
                                                      "./selection/single",
                                                      "./selection/multiple",
                                                      "./selection/placeholder",
                                                      "./selection/allowClear",
                                                      "./selection/search",
                                                      "./selection/eventRelay",
                                                      "./utils",
                                                      "./translation",
                                                      "./diacritics",
                                                      "./data/select",
                                                      "./data/array",
                                                      "./data/ajax",
                                                      "./data/tags",
                                                      "./data/tokenizer",
                                                      "./data/minimumInputLength",
                                                      "./data/maximumInputLength",
                                                      "./data/maximumSelectionLength",
                                                      "./dropdown",
                                                      "./dropdown/search",
                                                      "./dropdown/hidePlaceholder",
                                                      "./dropdown/infiniteScroll",
                                                      "./dropdown/attachBody",
                                                      "./dropdown/minimumResultsForSearch",
                                                      "./dropdown/selectOnClose",
                                                      "./dropdown/closeOnSelect",
                                                      "./i18n/en",
                                             ],
                                             function (c, u, d, p, h, f, g, m, v, y, s, t, _, $, w, b, A, x, D, S, E, C, O, T, q, L, I, j, e) {
                                                      function n() {
                                                               this.reset();
                                                      }
                                                      return (
                                                               (n.prototype.apply = function (e) {
                                                                        if (null == (e = c.extend(!0, {}, this.defaults, e)).dataAdapter) {
                                                                                 if (
                                                                                          (null != e.ajax ? (e.dataAdapter = w) : null != e.data ? (e.dataAdapter = $) : (e.dataAdapter = _),
                                                                                          0 < e.minimumInputLength && (e.dataAdapter = y.Decorate(e.dataAdapter, x)),
                                                                                          0 < e.maximumInputLength && (e.dataAdapter = y.Decorate(e.dataAdapter, D)),
                                                                                          0 < e.maximumSelectionLength && (e.dataAdapter = y.Decorate(e.dataAdapter, S)),
                                                                                          e.tags && (e.dataAdapter = y.Decorate(e.dataAdapter, b)),
                                                                                          (null == e.tokenSeparators && null == e.tokenizer) || (e.dataAdapter = y.Decorate(e.dataAdapter, A)),
                                                                                          null != e.query)
                                                                                 ) {
                                                                                          var t = u(e.amdBase + "compat/query");
                                                                                          e.dataAdapter = y.Decorate(e.dataAdapter, t);
                                                                                 }
                                                                                 if (null != e.initSelection) {
                                                                                          var n = u(e.amdBase + "compat/initSelection");
                                                                                          e.dataAdapter = y.Decorate(e.dataAdapter, n);
                                                                                 }
                                                                        }
                                                                        if (
                                                                                 (null == e.resultsAdapter &&
                                                                                          ((e.resultsAdapter = d),
                                                                                          null != e.ajax && (e.resultsAdapter = y.Decorate(e.resultsAdapter, T)),
                                                                                          null != e.placeholder && (e.resultsAdapter = y.Decorate(e.resultsAdapter, O)),
                                                                                          e.selectOnClose && (e.resultsAdapter = y.Decorate(e.resultsAdapter, I))),
                                                                                 null == e.dropdownAdapter)
                                                                        ) {
                                                                                 if (e.multiple) e.dropdownAdapter = E;
                                                                                 else {
                                                                                          var r = y.Decorate(E, C);
                                                                                          e.dropdownAdapter = r;
                                                                                 }
                                                                                 if (
                                                                                          (0 !== e.minimumResultsForSearch && (e.dropdownAdapter = y.Decorate(e.dropdownAdapter, L)),
                                                                                          e.closeOnSelect && (e.dropdownAdapter = y.Decorate(e.dropdownAdapter, j)),
                                                                                          null != e.dropdownCssClass || null != e.dropdownCss || null != e.adaptDropdownCssClass)
                                                                                 ) {
                                                                                          var i = u(e.amdBase + "compat/dropdownCss");
                                                                                          e.dropdownAdapter = y.Decorate(e.dropdownAdapter, i);
                                                                                 }
                                                                                 e.dropdownAdapter = y.Decorate(e.dropdownAdapter, q);
                                                                        }
                                                                        if (null == e.selectionAdapter) {
                                                                                 if (
                                                                                          (e.multiple ? (e.selectionAdapter = h) : (e.selectionAdapter = p),
                                                                                          null != e.placeholder && (e.selectionAdapter = y.Decorate(e.selectionAdapter, f)),
                                                                                          e.allowClear && (e.selectionAdapter = y.Decorate(e.selectionAdapter, g)),
                                                                                          e.multiple && (e.selectionAdapter = y.Decorate(e.selectionAdapter, m)),
                                                                                          null != e.containerCssClass || null != e.containerCss || null != e.adaptContainerCssClass)
                                                                                 ) {
                                                                                          var o = u(e.amdBase + "compat/containerCss");
                                                                                          e.selectionAdapter = y.Decorate(e.selectionAdapter, o);
                                                                                 }
                                                                                 e.selectionAdapter = y.Decorate(e.selectionAdapter, v);
                                                                        }
                                                                        (e.language = this._resolveLanguage(e.language)), e.language.push("en");
                                                                        for (var s = [], a = 0; a < e.language.length; a++) {
                                                                                 var l = e.language[a];
                                                                                 -1 === s.indexOf(l) && s.push(l);
                                                                        }
                                                                        return (e.language = s), (e.translations = this._processTranslations(e.language, e.debug)), e;
                                                               }),
                                                               (n.prototype.reset = function () {
                                                                        function a(e) {
                                                                                 return e.replace(/[^\u0000-\u007E]/g, function (e) {
                                                                                          return t[e] || e;
                                                                                 });
                                                                        }
                                                                        this.defaults = {
                                                                                 amdBase: "./",
                                                                                 amdLanguageBase: "./i18n/",
                                                                                 closeOnSelect: !0,
                                                                                 debug: !1,
                                                                                 dropdownAutoWidth: !1,
                                                                                 escapeMarkup: y.escapeMarkup,
                                                                                 language: {},
                                                                                 matcher: function e(t, n) {
                                                                                          if ("" === c.trim(t.term)) return n;
                                                                                          if (n.children && 0 < n.children.length) {
                                                                                                   for (var r = c.extend(!0, {}, n), i = n.children.length - 1; 0 <= i; i--) null == e(t, n.children[i]) && r.children.splice(i, 1);
                                                                                                   return 0 < r.children.length ? r : e(t, r);
                                                                                          }
                                                                                          var o = a(n.text).toUpperCase(),
                                                                                                   s = a(t.term).toUpperCase();
                                                                                          return -1 < o.indexOf(s) ? n : null;
                                                                                 },
                                                                                 minimumInputLength: 0,
                                                                                 maximumInputLength: 0,
                                                                                 maximumSelectionLength: 0,
                                                                                 minimumResultsForSearch: 0,
                                                                                 selectOnClose: !1,
                                                                                 scrollAfterSelect: !1,
                                                                                 sorter: function (e) {
                                                                                          return e;
                                                                                 },
                                                                                 templateResult: function (e) {
                                                                                          return e.text;
                                                                                 },
                                                                                 templateSelection: function (e) {
                                                                                          return e.text;
                                                                                 },
                                                                                 theme: "default",
                                                                                 width: "resolve",
                                                                        };
                                                               }),
                                                               (n.prototype.applyFromElement = function (e, t) {
                                                                        var n = e.language,
                                                                                 r = this.defaults.language,
                                                                                 i = t.prop("lang"),
                                                                                 o = t.closest("[lang]").prop("lang"),
                                                                                 s = Array.prototype.concat.call(this._resolveLanguage(i), this._resolveLanguage(n), this._resolveLanguage(r), this._resolveLanguage(o));
                                                                        return (e.language = s), e;
                                                               }),
                                                               (n.prototype._resolveLanguage = function (e) {
                                                                        if (!e) return [];
                                                                        if (c.isEmptyObject(e)) return [];
                                                                        if (c.isPlainObject(e)) return [e];
                                                                        var t;
                                                                        t = c.isArray(e) ? e : [e];
                                                                        for (var n = [], r = 0; r < t.length; r++)
                                                                                 if ((n.push(t[r]), "string" == typeof t[r] && 0 < t[r].indexOf("-"))) {
                                                                                          var i = t[r].split("-")[0];
                                                                                          n.push(i);
                                                                                 }
                                                                        return n;
                                                               }),
                                                               (n.prototype._processTranslations = function (e, t) {
                                                                        for (var n = new s(), r = 0; r < e.length; r++) {
                                                                                 var i = new s(),
                                                                                          o = e[r];
                                                                                 if ("string" == typeof o)
                                                                                          try {
                                                                                                   i = s.loadPath(o);
                                                                                          } catch (e) {
                                                                                                   try {
                                                                                                            (o = this.defaults.amdLanguageBase + o), (i = s.loadPath(o));
                                                                                                   } catch (e) {
                                                                                                            t &&
                                                                                                                     window.console &&
                                                                                                                     console.warn &&
                                                                                                                     console.warn(
                                                                                                                              'Select2: The language file for "' + o + '" could not be automatically loaded. A fallback will be used instead.'
                                                                                                                     );
                                                                                                   }
                                                                                          }
                                                                                 else i = c.isPlainObject(o) ? new s(o) : o;
                                                                                 n.extend(i);
                                                                        }
                                                                        return n;
                                                               }),
                                                               (n.prototype.set = function (e, t) {
                                                                        var n = {};
                                                                        n[c.camelCase(e)] = t;
                                                                        var r = y._convertData(n);
                                                                        c.extend(!0, this.defaults, r);
                                                               }),
                                                               new n()
                                                      );
                                             }
                                    ),
                                    e.define("select2/options", ["require", "jquery", "./defaults", "./utils"], function (r, d, i, p) {
                                             function e(e, t) {
                                                      if (
                                                               ((this.options = e),
                                                               null != t && this.fromElement(t),
                                                               null != t && (this.options = i.applyFromElement(this.options, t)),
                                                               (this.options = i.apply(this.options)),
                                                               t && t.is("input"))
                                                      ) {
                                                               var n = r(this.get("amdBase") + "compat/inputData");
                                                               this.options.dataAdapter = p.Decorate(this.options.dataAdapter, n);
                                                      }
                                             }
                                             return (
                                                      (e.prototype.fromElement = function (e) {
                                                               var t = ["select2"];
                                                               null == this.options.multiple && (this.options.multiple = e.prop("multiple")),
                                                                        null == this.options.disabled && (this.options.disabled = e.prop("disabled")),
                                                                        null == this.options.dir &&
                                                                                 (e.prop("dir")
                                                                                          ? (this.options.dir = e.prop("dir"))
                                                                                          : e.closest("[dir]").prop("dir")
                                                                                          ? (this.options.dir = e.closest("[dir]").prop("dir"))
                                                                                          : (this.options.dir = "ltr")),
                                                                        e.prop("disabled", this.options.disabled),
                                                                        e.prop("multiple", this.options.multiple),
                                                                        p.GetData(e[0], "select2Tags") &&
                                                                                 (this.options.debug &&
                                                                                          window.console &&
                                                                                          console.warn &&
                                                                                          console.warn(
                                                                                                   'Select2: The `data-select2-tags` attribute has been changed to use the `data-data` and `data-tags="true"` attributes and will be removed in future versions of Select2.'
                                                                                          ),
                                                                                 p.StoreData(e[0], "data", p.GetData(e[0], "select2Tags")),
                                                                                 p.StoreData(e[0], "tags", !0)),
                                                                        p.GetData(e[0], "ajaxUrl") &&
                                                                                 (this.options.debug &&
                                                                                          window.console &&
                                                                                          console.warn &&
                                                                                          console.warn(
                                                                                                   "Select2: The `data-ajax-url` attribute has been changed to `data-ajax--url` and support for the old attribute will be removed in future versions of Select2."
                                                                                          ),
                                                                                 e.attr("ajax--url", p.GetData(e[0], "ajaxUrl")),
                                                                                 p.StoreData(e[0], "ajax-Url", p.GetData(e[0], "ajaxUrl")));
                                                               var n = {};
                                                               function r(e, t) {
                                                                        return t.toUpperCase();
                                                               }
                                                               for (var i = 0; i < e[0].attributes.length; i++) {
                                                                        var o = e[0].attributes[i].name,
                                                                                 s = "data-";
                                                                        if (o.substr(0, s.length) == s) {
                                                                                 var a = o.substring(s.length),
                                                                                          l = p.GetData(e[0], a);
                                                                                 n[a.replace(/-([a-z])/g, r)] = l;
                                                                        }
                                                               }
                                                               d.fn.jquery && "1." == d.fn.jquery.substr(0, 2) && e[0].dataset && (n = d.extend(!0, {}, e[0].dataset, n));
                                                               var c = d.extend(!0, {}, p.GetData(e[0]), n);
                                                               for (var u in (c = p._convertData(c))) -1 < d.inArray(u, t) || (d.isPlainObject(this.options[u]) ? d.extend(this.options[u], c[u]) : (this.options[u] = c[u]));
                                                               return this;
                                                      }),
                                                      (e.prototype.get = function (e) {
                                                               return this.options[e];
                                                      }),
                                                      (e.prototype.set = function (e, t) {
                                                               this.options[e] = t;
                                                      }),
                                                      e
                                             );
                                    }),
                                    e.define("select2/core", ["jquery", "./options", "./utils", "./keys"], function (i, c, u, r) {
                                             var d = function (e, t) {
                                                      null != u.GetData(e[0], "select2") && u.GetData(e[0], "select2").destroy(),
                                                               (this.$element = e),
                                                               (this.id = this._generateId(e)),
                                                               (t = t || {}),
                                                               (this.options = new c(t, e)),
                                                               d.__super__.constructor.call(this);
                                                      var n = e.attr("tabindex") || 0;
                                                      u.StoreData(e[0], "old-tabindex", n), e.attr("tabindex", "-1");
                                                      var r = this.options.get("dataAdapter");
                                                      this.dataAdapter = new r(e, this.options);
                                                      var i = this.render();
                                                      this._placeContainer(i);
                                                      var o = this.options.get("selectionAdapter");
                                                      (this.selection = new o(e, this.options)), (this.$selection = this.selection.render()), this.selection.position(this.$selection, i);
                                                      var s = this.options.get("dropdownAdapter");
                                                      (this.dropdown = new s(e, this.options)), (this.$dropdown = this.dropdown.render()), this.dropdown.position(this.$dropdown, i);
                                                      var a = this.options.get("resultsAdapter");
                                                      (this.results = new a(e, this.options, this.dataAdapter)), (this.$results = this.results.render()), this.results.position(this.$results, this.$dropdown);
                                                      var l = this;
                                                      this._bindAdapters(),
                                                               this._registerDomEvents(),
                                                               this._registerDataEvents(),
                                                               this._registerSelectionEvents(),
                                                               this._registerDropdownEvents(),
                                                               this._registerResultsEvents(),
                                                               this._registerEvents(),
                                                               this.dataAdapter.current(function (e) {
                                                                        l.trigger("selection:update", { data: e });
                                                               }),
                                                               e.addClass("select2-hidden-accessible"),
                                                               e.attr("aria-hidden", "true"),
                                                               this._syncAttributes(),
                                                               u.StoreData(e[0], "select2", this),
                                                               e.data("select2", this);
                                             };
                                             return (
                                                      u.Extend(d, u.Observable),
                                                      (d.prototype._generateId = function (e) {
                                                               return (
                                                                        "select2-" +
                                                                        (null != e.attr("id") ? e.attr("id") : null != e.attr("name") ? e.attr("name") + "-" + u.generateChars(2) : u.generateChars(4)).replace(/(:|\.|\[|\]|,)/g, "")
                                                               );
                                                      }),
                                                      (d.prototype._placeContainer = function (e) {
                                                               e.insertAfter(this.$element);
                                                               var t = this._resolveWidth(this.$element, this.options.get("width"));
                                                               null != t && e.css("width", t);
                                                      }),
                                                      (d.prototype._resolveWidth = function (e, t) {
                                                               var n = /^width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/i;
                                                               if ("resolve" == t) {
                                                                        var r = this._resolveWidth(e, "style");
                                                                        return null != r ? r : this._resolveWidth(e, "element");
                                                               }
                                                               if ("element" == t) {
                                                                        var i = e.outerWidth(!1);
                                                                        return i <= 0 ? "auto" : i + "px";
                                                               }
                                                               if ("style" != t) return "computedstyle" != t ? t : window.getComputedStyle(e[0]).width;
                                                               var o = e.attr("style");
                                                               if ("string" != typeof o) return null;
                                                               for (var s = o.split(";"), a = 0, l = s.length; a < l; a += 1) {
                                                                        var c = s[a].replace(/\s/g, "").match(n);
                                                                        if (null !== c && 1 <= c.length) return c[1];
                                                               }
                                                               return null;
                                                      }),
                                                      (d.prototype._bindAdapters = function () {
                                                               this.dataAdapter.bind(this, this.$container), this.selection.bind(this, this.$container), this.dropdown.bind(this, this.$container), this.results.bind(this, this.$container);
                                                      }),
                                                      (d.prototype._registerDomEvents = function () {
                                                               var t = this;
                                                               this.$element.on("change.select2", function () {
                                                                        t.dataAdapter.current(function (e) {
                                                                                 t.trigger("selection:update", { data: e });
                                                                        });
                                                               }),
                                                                        this.$element.on("focus.select2", function (e) {
                                                                                 t.trigger("focus", e);
                                                                        }),
                                                                        (this._syncA = u.bind(this._syncAttributes, this)),
                                                                        (this._syncS = u.bind(this._syncSubtree, this)),
                                                                        this.$element[0].attachEvent && this.$element[0].attachEvent("onpropertychange", this._syncA);
                                                               var e = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;
                                                               null != e
                                                                        ? ((this._observer = new e(function (e) {
                                                                                   i.each(e, t._syncA), i.each(e, t._syncS);
                                                                          })),
                                                                          this._observer.observe(this.$element[0], { attributes: !0, childList: !0, subtree: !1 }))
                                                                        : this.$element[0].addEventListener &&
                                                                          (this.$element[0].addEventListener("DOMAttrModified", t._syncA, !1),
                                                                          this.$element[0].addEventListener("DOMNodeInserted", t._syncS, !1),
                                                                          this.$element[0].addEventListener("DOMNodeRemoved", t._syncS, !1));
                                                      }),
                                                      (d.prototype._registerDataEvents = function () {
                                                               var n = this;
                                                               this.dataAdapter.on("*", function (e, t) {
                                                                        n.trigger(e, t);
                                                               });
                                                      }),
                                                      (d.prototype._registerSelectionEvents = function () {
                                                               var n = this,
                                                                        r = ["toggle", "focus"];
                                                               this.selection.on("toggle", function () {
                                                                        n.toggleDropdown();
                                                               }),
                                                                        this.selection.on("focus", function (e) {
                                                                                 n.focus(e);
                                                                        }),
                                                                        this.selection.on("*", function (e, t) {
                                                                                 -1 === i.inArray(e, r) && n.trigger(e, t);
                                                                        });
                                                      }),
                                                      (d.prototype._registerDropdownEvents = function () {
                                                               var n = this;
                                                               this.dropdown.on("*", function (e, t) {
                                                                        n.trigger(e, t);
                                                               });
                                                      }),
                                                      (d.prototype._registerResultsEvents = function () {
                                                               var n = this;
                                                               this.results.on("*", function (e, t) {
                                                                        n.trigger(e, t);
                                                               });
                                                      }),
                                                      (d.prototype._registerEvents = function () {
                                                               var n = this;
                                                               this.on("open", function () {
                                                                        n.$container.addClass("select2-container--open");
                                                               }),
                                                                        this.on("close", function () {
                                                                                 n.$container.removeClass("select2-container--open");
                                                                        }),
                                                                        this.on("enable", function () {
                                                                                 n.$container.removeClass("select2-container--disabled");
                                                                        }),
                                                                        this.on("disable", function () {
                                                                                 n.$container.addClass("select2-container--disabled");
                                                                        }),
                                                                        this.on("blur", function () {
                                                                                 n.$container.removeClass("select2-container--focus");
                                                                        }),
                                                                        this.on("query", function (t) {
                                                                                 n.isOpen() || n.trigger("open", {}),
                                                                                          this.dataAdapter.query(t, function (e) {
                                                                                                   n.trigger("results:all", { data: e, query: t });
                                                                                          });
                                                                        }),
                                                                        this.on("query:append", function (t) {
                                                                                 this.dataAdapter.query(t, function (e) {
                                                                                          n.trigger("results:append", { data: e, query: t });
                                                                                 });
                                                                        }),
                                                                        this.on("keypress", function (e) {
                                                                                 var t = e.which;
                                                                                 n.isOpen()
                                                                                          ? t === r.ESC || t === r.TAB || (t === r.UP && e.altKey)
                                                                                                   ? (n.close(), e.preventDefault())
                                                                                                   : t === r.ENTER
                                                                                                   ? (n.trigger("results:select", {}), e.preventDefault())
                                                                                                   : t === r.SPACE && e.ctrlKey
                                                                                                   ? (n.trigger("results:toggle", {}), e.preventDefault())
                                                                                                   : t === r.UP
                                                                                                   ? (n.trigger("results:previous", {}), e.preventDefault())
                                                                                                   : t === r.DOWN && (n.trigger("results:next", {}), e.preventDefault())
                                                                                          : (t === r.ENTER || t === r.SPACE || (t === r.DOWN && e.altKey)) && (n.open(), e.preventDefault());
                                                                        });
                                                      }),
                                                      (d.prototype._syncAttributes = function () {
                                                               this.options.set("disabled", this.$element.prop("disabled")),
                                                                        this.options.get("disabled") ? (this.isOpen() && this.close(), this.trigger("disable", {})) : this.trigger("enable", {});
                                                      }),
                                                      (d.prototype._syncSubtree = function (e, t) {
                                                               var n = !1,
                                                                        r = this;
                                                               if (!e || !e.target || "OPTION" === e.target.nodeName || "OPTGROUP" === e.target.nodeName) {
                                                                        if (t)
                                                                                 if (t.addedNodes && 0 < t.addedNodes.length)
                                                                                          for (var i = 0; i < t.addedNodes.length; i++) {
                                                                                                   t.addedNodes[i].selected && (n = !0);
                                                                                          }
                                                                                 else t.removedNodes && 0 < t.removedNodes.length && (n = !0);
                                                                        else n = !0;
                                                                        n &&
                                                                                 this.dataAdapter.current(function (e) {
                                                                                          r.trigger("selection:update", { data: e });
                                                                                 });
                                                               }
                                                      }),
                                                      (d.prototype.trigger = function (e, t) {
                                                               var n = d.__super__.trigger,
                                                                        r = { open: "opening", close: "closing", select: "selecting", unselect: "unselecting", clear: "clearing" };
                                                               if ((void 0 === t && (t = {}), e in r)) {
                                                                        var i = r[e],
                                                                                 o = { prevented: !1, name: e, args: t };
                                                                        if ((n.call(this, i, o), o.prevented)) return void (t.prevented = !0);
                                                               }
                                                               n.call(this, e, t);
                                                      }),
                                                      (d.prototype.toggleDropdown = function () {
                                                               this.options.get("disabled") || (this.isOpen() ? this.close() : this.open());
                                                      }),
                                                      (d.prototype.open = function () {
                                                               this.isOpen() || this.trigger("query", {});
                                                      }),
                                                      (d.prototype.close = function () {
                                                               this.isOpen() && this.trigger("close", {});
                                                      }),
                                                      (d.prototype.isOpen = function () {
                                                               return this.$container.hasClass("select2-container--open");
                                                      }),
                                                      (d.prototype.hasFocus = function () {
                                                               return this.$container.hasClass("select2-container--focus");
                                                      }),
                                                      (d.prototype.focus = function (e) {
                                                               this.hasFocus() || (this.$container.addClass("select2-container--focus"), this.trigger("focus", {}));
                                                      }),
                                                      (d.prototype.enable = function (e) {
                                                               this.options.get("debug") &&
                                                                        window.console &&
                                                                        console.warn &&
                                                                        console.warn('Select2: The `select2("enable")` method has been deprecated and will be removed in later Select2 versions. Use $element.prop("disabled") instead.'),
                                                                        (null != e && 0 !== e.length) || (e = [!0]);
                                                               var t = !e[0];
                                                               this.$element.prop("disabled", t);
                                                      }),
                                                      (d.prototype.data = function () {
                                                               this.options.get("debug") &&
                                                                        0 < arguments.length &&
                                                                        window.console &&
                                                                        console.warn &&
                                                                        console.warn('Select2: Data can no longer be set using `select2("data")`. You should consider setting the value instead using `$element.val()`.');
                                                               var t = [];
                                                               return (
                                                                        this.dataAdapter.current(function (e) {
                                                                                 t = e;
                                                                        }),
                                                                        t
                                                               );
                                                      }),
                                                      (d.prototype.val = function (e) {
                                                               if (
                                                                        (this.options.get("debug") &&
                                                                                 window.console &&
                                                                                 console.warn &&
                                                                                 console.warn('Select2: The `select2("val")` method has been deprecated and will be removed in later Select2 versions. Use $element.val() instead.'),
                                                                        null == e || 0 === e.length)
                                                               )
                                                                        return this.$element.val();
                                                               var t = e[0];
                                                               i.isArray(t) &&
                                                                        (t = i.map(t, function (e) {
                                                                                 return e.toString();
                                                                        })),
                                                                        this.$element.val(t).trigger("change");
                                                      }),
                                                      (d.prototype.destroy = function () {
                                                               this.$container.remove(),
                                                                        this.$element[0].detachEvent && this.$element[0].detachEvent("onpropertychange", this._syncA),
                                                                        null != this._observer
                                                                                 ? (this._observer.disconnect(), (this._observer = null))
                                                                                 : this.$element[0].removeEventListener &&
                                                                                   (this.$element[0].removeEventListener("DOMAttrModified", this._syncA, !1),
                                                                                   this.$element[0].removeEventListener("DOMNodeInserted", this._syncS, !1),
                                                                                   this.$element[0].removeEventListener("DOMNodeRemoved", this._syncS, !1)),
                                                                        (this._syncA = null),
                                                                        (this._syncS = null),
                                                                        this.$element.off(".select2"),
                                                                        this.$element.attr("tabindex", u.GetData(this.$element[0], "old-tabindex")),
                                                                        this.$element.removeClass("select2-hidden-accessible"),
                                                                        this.$element.attr("aria-hidden", "false"),
                                                                        u.RemoveData(this.$element[0]),
                                                                        this.$element.removeData("select2"),
                                                                        this.dataAdapter.destroy(),
                                                                        this.selection.destroy(),
                                                                        this.dropdown.destroy(),
                                                                        this.results.destroy(),
                                                                        (this.dataAdapter = null),
                                                                        (this.selection = null),
                                                                        (this.dropdown = null),
                                                                        (this.results = null);
                                                      }),
                                                      (d.prototype.render = function () {
                                                               var e = i('<span class="select2 select2-container"><span class="selection"></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>');
                                                               return (
                                                                        e.attr("dir", this.options.get("dir")),
                                                                        (this.$container = e),
                                                                        this.$container.addClass("select2-container--" + this.options.get("theme")),
                                                                        u.StoreData(e[0], "element", this.$element),
                                                                        e
                                                               );
                                                      }),
                                                      d
                                             );
                                    }),
                                    e.define("jquery-mousewheel", ["jquery"], function (e) {
                                             return e;
                                    }),
                                    e.define("jquery.select2", ["jquery", "jquery-mousewheel", "./select2/core", "./select2/defaults", "./select2/utils"], function (i, e, o, t, s) {
                                             if (null == i.fn.select2) {
                                                      var a = ["open", "close", "destroy"];
                                                      i.fn.select2 = function (t) {
                                                               if ("object" == typeof (t = t || {}))
                                                                        return (
                                                                                 this.each(function () {
                                                                                          var e = i.extend(!0, {}, t);
                                                                                          new o(i(this), e);
                                                                                 }),
                                                                                 this
                                                                        );
                                                               if ("string" != typeof t) throw new Error("Invalid arguments for Select2: " + t);
                                                               var n,
                                                                        r = Array.prototype.slice.call(arguments, 1);
                                                               return (
                                                                        this.each(function () {
                                                                                 var e = s.GetData(this, "select2");
                                                                                 null == e && window.console && console.error && console.error("The select2('" + t + "') method was called on an element that is not using Select2."),
                                                                                          (n = e[t].apply(e, r));
                                                                        }),
                                                                        -1 < i.inArray(t, a) ? this : n
                                                               );
                                                      };
                                             }
                                             return null == i.fn.select2.defaults && (i.fn.select2.defaults = t), o;
                                    }),
                                    { define: e.define, require: e.require }
                           );
                  })(),
                  t = e.require("jquery.select2");
         return (u.fn.select2.amd = e), t;
});
var select_data_tasks = [
         ["Cocina", "ico-cocina.png", [["Suelo"], ["Muebles / superficies"], ["Electrodomésticos"], ["Vajilla"]]],
         ["Baño", "ico-bano.png", [["Suelo"], ["Muebles / superficies"], ["Ventanas /cristales"]]],
         ["Dormitorios", "ico-dormitorios.png", [["Suelo"], ["Muebles / superficies"], ["Ventanas /cristales"]]],
         ["Terraza/Jardín", "ico-terraza.png", [["Suelo"], ["Muebles / superficies"]]],
         ["Ropa", "ico-ropa.png", [["Lavadora"], ["Tender"], ["Planchar"]]],
         ["Salón", "ico-salon.png", [["Suelo"], ["Muebles / superficies"], ["Ventanas /cristales"]]],
];
var colors_aux = ["#edcabd", "#cfe3e4", "#eab6b6", "#eddebd", "#ccdccd", "#edcabd", "#cfe3e4", "#eab6b6", "#eddebd", "#ccdccd"];
var colors = [];
var person = [];
var tasks = [["", "", "", "", "", "", "", ""]];
var dayWeek = ["", "El nuevo Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
var mobile_day = 2;
function get_optionsAreas() {
         var options = "";
         var first_option = "";
         options += '<option value="">Selecciona una area</option>';
         select_data_tasks.forEach(function (data) {
                  if (data) {
                           var area = data[0];
                           var icon = data[1];
                           if (first_option == "") {
                                    first_option = area;
                           }
                           options += '<option image="modules/custom/housework_organizer/images/' + icon + '" color="false" value="' + area + '">' + area + "</option>";
                  }
         });
         return options;
}
function generateSelect_Areas() {
         var options = get_optionsAreas();
         $("#selectArea").html(options);
         $("#selectArea").multiselect({
                  multiple: false,
                  header: false,
                  height: "100px",
                  noneSelectedText: "Selecciona una area",
                  selectedList: 1,
                  show: ["blind", 200],
                  hide: ["fade", 200],
                  position: { my: "left top", at: "left bottom" },
         });
         $("#selectArea").multiselect("uncheckAll", true);
}
function generateSelect_Tasks(selected, id_selector) {
         if (id_selector === undefined) {
                  id_selector = "selectTask";
         }
         var area_selected = "";
         var options = "";
         select_data_tasks.forEach(function (data) {
                  if (data) {
                           var area = data[0];
                           var tareas = data[2];
                           if (area == selected) {
                                    area_selected = area;
                                    tareas.forEach(function (data) {
                                             var tarea = data[0];
                                             var icon = data[1];
                                             options += '<option value="' + tarea + '">' + tarea + "</option>";
                                    });
                           }
                  }
         });
         $("#" + id_selector).html(options);
         $("#" + id_selector).multiselect({
                  multiple: true,
                  header: false,
                  height: "140px",
                  noneSelectedText: "Selecciona una tarea",
                  selectedList: false,
                  show: ["blind", 200],
                  hide: ["fade", 200],
                  classes: "taskModal",
                  position: { my: "left top", at: "left bottom" },
                  click: function (event, ui) {
                           $(".ui-multiselect-menu").hide();
                  },
         });
         if (options != "")
                  $(".taskModal .ui-multiselect-checkboxes").append(
                           '<li class="addFreeTask"> <div><input type="text" placeholder="Añade una tarea" name="addFreeTask"><i onclick="addFreeTask(\'' +
                                    area_selected +
                                    "','" +
                                    selected +
                                    "', '" +
                                    id_selector +
                                    '\')" class="icon icon-plus"></i></div> </li>'
                  );
         $("#" + id_selector).multiselect("uncheckAll", true);
}
function addFreeTask(category, selected, id_selector) {
         var task = $(".addFreeTask input").val();
         select_data_tasks.forEach(function (data) {
                  if (data[0] == category) {
                           data[2].push([task]);
                  }
         });
         generateSelect_Tasks(selected, id_selector);
}
function addPerson() {
         var person_name = $('input[name="person_name"]').val();
         var person_color = $('input[name="person_color"]').val();
         if (person_color == "") {
                  var color_temp = colors_aux.shift();
                  colors.push(color_temp);
                  colors_aux.push(color_temp);
         } else {
                  colors.push(person_color);
         }
         if (person_name != "") {
                  $('input[name="person_name"]').val("");
                  person_name = person_name.toUpperCase();
                  person.push(person_name);
                  localStorage.setItem("person", person);
                  var n_row = parseInt(person.length);
                  tasks.push(["", "", "", "", "", "", "", ""]);
                  var row =
                           '<div id="person_' +
                           n_row +
                           '" data-row="' +
                           n_row +
                           '" class="row_task"><div class="col col_person"><b>' +
                           person_name.substring(0, 1) +
                           "</b><span>" +
                           person_name +
                           '</span></div><div class="col col_task" onclick="showModalTask(' +
                           n_row +
                           ',2);" data-row="' +
                           n_row +
                           '" data-col="2"></div><div class="col col_task" \tonclick="showModalTask(' +
                           n_row +
                           ',3);" data-row="' +
                           n_row +
                           '" data-col="3"></div><div class="col col_task" onclick="showModalTask(' +
                           n_row +
                           ',4);" data-row="' +
                           n_row +
                           '" data-col="4"></div><div class="col col_task" onclick="showModalTask(' +
                           n_row +
                           ',5);" data-row="' +
                           n_row +
                           '" data-col="5"></div><div class="col col_task" onclick="showModalTask(' +
                           n_row +
                           ',6);" data-row="' +
                           n_row +
                           '" data-col="6"></div><div \tclass="col col_task" onclick="showModalTask(' +
                           n_row +
                           ',7);" data-row="' +
                           n_row +
                           '" data-col="7"></div><div class="col col_task" onclick="showModalTask(' +
                           n_row +
                           ',8);" data-row="' +
                           n_row +
                           '" data-col="8"></div></div>';
                  $(".dummyContentCalendar .exampleRow:nth-of-type(1)").remove();
                  $(".dummyContentCalendar").before(row);
                  perfectRow();
                  $(".modal").hide();
                  mobileFix();
                  $(".modal").hide();
                  $("#calendar .col_task:nth-of-type(" + mobile_day + ")").show();
         } else {
                  alert("Escribe el nombre de una persona");
         }
}
function perfectRow() {
         $(".row_task").each(function () {
                  var maxH = 0;
                  var el = $(this);
                  var item = ".col";
                  el.find(item).css("height", "");
                  el.find(item).each(function () {
                           var tempH = $(this).outerHeight();
                           if (tempH > maxH) {
                                    maxH = tempH;
                           }
                  });
                  el.find(item).css("min-height", maxH);
         });
}
function saveCalendar() {
         var data_html = $(".contentCalendar").html();
         $.ajax({
                  url: APP_URL + "/organizador/save",
                  type: "post",
                  data: { calendar_id: cid, data: data_html },
                  dataType: "JSON",
                  async: false,
                  success: function (data) {
                           cid = parseInt(data);
                           return true;
                  },
         });
         return false;
}
function createPDF() {
         saveCalendar();
         window.open(APP_URL + "/organizador/pdf/" + cid, "_blank");
}
function showCol(col) {
         col = parseInt(col) + 1;
         $(".contentCalendar .row_task .col_task").hide();
         $(".contentCalendar .row_task .col_task:nth-of-type(" + col + ")").show();
}
function showModalTask(row, col) {
         $("#modalTasks .daylist .day input").prop("checked", false);
         var id_person = row - 1;
         var id_day = col - 1;
         if (tasks[id_person][id_day].length == 0) {
                  $("#selectPerson").html('<option value="' + id_person + '">' + person[id_person] + "</option>");
                  $("#modalTasks .daylist .day:nth-of-type(" + id_day + ") input").prop("checked", true);
                  event.preventDefault();
                  $(".modal").hide();
                  $("#selectArea").multiselect("uncheckAll", true);
                  $("#selectTask").multiselect("uncheckAll", true);
                  $(".rowSelectPerson").hide();
                  $("#modalTasks input[name=row]").val(id_person);
                  $("#modalTasks input[name=col]").val(col);
                  $("#modalTasks").show();
         }
}
function addNewPerson() {
         $("#modalTasks").hide();
         $("#modalPerson").show();
         $('input[name="person_name"]').focus();
}
function replaceCalendar() {
         var html = localStorage.getItem("data_calendar");
         person = JSON.parse(localStorage.getItem("persons"));
         $("#calendar").html(html);
}
function mobileFix() {
         if (window.innerWidth >= 992) {
                  $(".col_task").show();
                  $("#calendar").removeClass("mobile_view");
         } else {
                  if ($("#calendar.mobile_view").length == 0) {
                           $("#calendar .col_task").hide();
                           $("#calendar .col_task:nth-of-type(" + mobile_day + ")").show();
                           $("#calendar").addClass("mobile_view");
                  }
         }
}
function modTarea(col, row, area) {
         var html_data = "";
         var img = "";
         select_data_tasks.forEach(function (a) {
                  if (null != a && a.length) {
                           if (a[0] == "Baño") {
                                    img = a[1];
                           }
                  }
         });
         tasks[row][col].filter(function (data) {
                  if (data.area === area) {
                           var tareas = data.tarea;
                           html_data += '<h2><img src="modules/custom/housework_organizer/images/' + img + '"> ' + area + "</h2>";
                           tareas.forEach(function (tarea) {
                                    html_data += "<p>" + tarea + "</p>";
                           });
                  }
         });
         $("#modalEditTasks .taskEdit").html(html_data);
         $("#modalEditTasks .headModalEdit h3").html("Tareas " + person[row]);
         $("#modalEditTasks .headModalEdit p").html(dayWeek[col]);
         $('#modalEditTasks form input[name="col"]').val(col);
         $('#modalEditTasks form input[name="row"]').val(row);
         $('#modalEditTasks form input[name="area"]').val(area);
         $("#modalEditTasks").show();
}
function sendWhatsapp() {
         saveCalendar();
         window.open("https://api.whatsapp.com/send?phone=&text=" + APP_URL + "/organizador/" + cid, "_blank");
}
function hideElem(elem) {
         $(".url_ok").removeClass("fade-in");
         $(".url_ok").addClass("fade-out");
}
function copyToClipboard() {
         $(".url_ok").removeClass("fade-out");
         saveCalendar();
         var $temp = $("<input>");
         $("body").append($temp);
         $temp.val(APP_URL + "/organizador/" + cid).select();
         document.execCommand("copy");
         $temp.remove();
         $(".url_ok").addClass("fade-in");
}
function addTask() {
         var col = $("#modalTasks input[name=col]").val();
         var row = $("#modalTasks input[name=row]").val();
         var days_arr = [];
         if (col == "" || row == "") {
                  row = $("#selectPerson").val();
                  col = $("#modalTasks input[name=day]:checked").val();
                  var day_checkboxes = document.getElementsByName("day[]");
                  for (var i = 0, n = day_checkboxes.length; i < n; i++) {
                           if (day_checkboxes[i].checked) {
                                    days_arr.push(day_checkboxes[i].value);
                           }
                  }
         }
         if (!isNaN(col)) {
                  days_arr.push(col);
         }
         var area = $("#selectArea").val();
         var tarea = $("#selectTask").val();
         var error = false;
         var color = "#" + colors[row];
         if (row >= 0) {
                  if (days_arr.length > 0) {
                           days_arr.forEach(function (col) {
                                    col = col - 1;
                                    if (tarea !== null && tarea !== "") {
                                             if (tasks[row][col] && tasks[row][col].length != undefined) {
                                                      var existe_dato = false;
                                                      tasks[row][col].forEach(function (data) {
                                                               if (data.area === area) {
                                                                        existe_dato = true;
                                                                        var tmp_tareas = [];
                                                                        var exist_tarea = false;
                                                                        data.tarea.forEach(function (t) {
                                                                                 if (tarea == t) {
                                                                                          exist_tarea = true;
                                                                                 }
                                                                                 tmp_tareas.push(t);
                                                                        });
                                                                        if (!exist_tarea) {
                                                                                 tmp_tareas.push(tarea);
                                                                        }
                                                                        data.tarea = tmp_tareas;
                                                               }
                                                      });
                                                      if (existe_dato) {
                                                               jQuery(".contentCalendar .row_task").eq(row).find(".col").eq(col).html("");
                                                               tasks[row][col].forEach(function (p) {
                                                                        jQuery(".contentCalendar .row_task")
                                                                                 .eq(row)
                                                                                 .find(".col")
                                                                                 .eq(col)
                                                                                 .append(
                                                                                          '<div class="tarea" id="' +
                                                                                                   p.area +
                                                                                                   "_" +
                                                                                                   row +
                                                                                                   "_" +
                                                                                                   col +
                                                                                                   '" onclick="javascript:modTarea(' +
                                                                                                   col +
                                                                                                   "," +
                                                                                                   row +
                                                                                                   ",'" +
                                                                                                   p.area +
                                                                                                   "')\"><h5>" +
                                                                                                   p.area +
                                                                                                   "</h5><p>" +
                                                                                                   p.tarea.join(", ") +
                                                                                                   "</p></div>"
                                                                                 );
                                                               });
                                                      } else {
                                                               tasks[row][col].push({ area: area, tarea: tarea });
                                                               jQuery(".contentCalendar .row_task")
                                                                        .eq(row)
                                                                        .find(".col")
                                                                        .eq(col)
                                                                        .append(
                                                                                 '<div class="tarea" id="' +
                                                                                          area +
                                                                                          "_" +
                                                                                          row +
                                                                                          "_" +
                                                                                          col +
                                                                                          '" onclick="javascript:modTarea(' +
                                                                                          col +
                                                                                          "," +
                                                                                          row +
                                                                                          ",'" +
                                                                                          area +
                                                                                          "')\"><h5>" +
                                                                                          area +
                                                                                          "</h5><p>" +
                                                                                          tarea.join(", ") +
                                                                                          "</p></div>"
                                                                        );
                                                      }
                                             } else {
                                                      jQuery(".contentCalendar .row_task").eq(row).find(".col").eq(col).css("background-color", color);
                                                      jQuery(".contentCalendar .row_task")
                                                               .eq(row)
                                                               .find(".col")
                                                               .eq(col)
                                                               .html(
                                                                        '<div class="tarea" id="' +
                                                                                 area +
                                                                                 "_" +
                                                                                 row +
                                                                                 "_" +
                                                                                 col +
                                                                                 '" onclick="javascript:modTarea(' +
                                                                                 col +
                                                                                 "," +
                                                                                 row +
                                                                                 ",'" +
                                                                                 area +
                                                                                 "')\"><h5>" +
                                                                                 area +
                                                                                 "</h5><p>" +
                                                                                 tarea.join(", ") +
                                                                                 "</p></div>"
                                                               );
                                                      tasks[row][col] = [{ area: area, tarea: tarea }];
                                             }
                                    } else {
                                             alert("Selecciona una tarea");
                                             error = true;
                                    }
                           });
                  } else {
                           alert("Selecciona un dia");
                           error = true;
                  }
         } else {
                  alert("Selecciona una persona");
                  error = true;
         }
         if (!error) {
                  localStorage.setItem("tasks", JSON.stringify(tasks));
                  perfectRow();
                  $(".modal").hide();
         }
}
function deleteTask() {
         var col = $('#modalEditTasks form input[name="col"]').val();
         var row = $('#modalEditTasks form input[name="row"]').val();
         var area = $('#modalEditTasks form input[name="area"]').val();
         if (tasks[row][col].length > 1) {
                  tasks[row][col].forEach(function (item, index, object) {
                           if (item.area === area) {
                                    object.splice(index, 1);
                           }
                  });
                  jQuery(".contentCalendar .row_task").eq(row).find(".col").eq(col).html("");
                  tasks[row][col].forEach(function (p) {
                           jQuery(".contentCalendar .row_task")
                                    .eq(row)
                                    .find(".col")
                                    .eq(col)
                                    .append(
                                             '<div class="tarea" id="' +
                                                      p.area +
                                                      "_" +
                                                      row +
                                                      "_" +
                                                      col +
                                                      '" onclick="javascript:modTarea(' +
                                                      col +
                                                      "," +
                                                      row +
                                                      ",'" +
                                                      p.area +
                                                      "')\"><h5>" +
                                                      p.area +
                                                      "</h5><p>" +
                                                      p.tarea.join(", ") +
                                                      "</p></div>"
                                    );
                  });
         } else {
                  tasks[row][col] = {};
                  $(".contentCalendar .row_task").eq(row).find(".col").eq(col).css("background-color", "#ededed");
                  $(".contentCalendar .row_task").eq(row).find(".col").eq(col).html("");
         }
         $(".modal").hide();
}
function editTask() {
         var col = $('#modalEditTasks form input[name="col"]').val();
         var row = $('#modalEditTasks form input[name="row"]').val();
         var area = $('#modalEditTasks form input[name="area"]').val();
         $('#modalEditSelectedTask form input[name="col"]').val(col);
         $('#modalEditSelectedTask form input[name="row"]').val(row);
         $(".modal").hide();
         var options = get_optionsAreas();
         $("#editSelectArea").html(options);
         $("#editSelectArea").multiselect({
                  multiple: false,
                  header: false,
                  height: "100px",
                  noneSelectedText: "Selecciona una area",
                  selectedList: 1,
                  show: ["blind", 200],
                  hide: ["fade", 200],
                  position: { my: "left top", at: "left bottom" },
         });
         $("#editSelectArea").multiselect("uncheckAll", true);
         $("#editSelectArea").val(area);
         $("#editSelectArea").multiselect("refresh");
         generateSelect_Tasks(area, "editSelectTask");
         var tareas = [];
         tasks[row][col].forEach(function (item, index, object) {
                  if (item.area === area) {
                           tareas = item.tarea;
                  }
         });
         $("#editSelectTask").multiselect("uncheckAll", true);
         $("#editSelectTask").val(tareas);
         $("#editSelectTask").multiselect("refresh");
         $("#modalEditSelectedTask").show();
}
function saveModTask() {
         var col = $('#modalEditSelectedTask form input[name="col"]').val();
         var row = $('#modalEditSelectedTask form input[name="row"]').val();
         var area = $("#editSelectArea").val();
         var tarea = $("#editSelectTask").val();
         if (tasks[row][col].length > 1) {
                  tasks[row][col].forEach(function (item, index, object) {
                           if (item.area === area) {
                                    object.splice(index, 1);
                           }
                  });
                  tasks[row][col].push({ area: area, tarea: tarea });
         } else {
                  tasks[row][col] = [{ area: area, tarea: tarea }];
         }
         jQuery(".contentCalendar .row_task").eq(row).find(".col").eq(col).html("");
         tasks[row][col].forEach(function (p) {
                  jQuery(".contentCalendar .row_task")
                           .eq(row)
                           .find(".col")
                           .eq(col)
                           .append('<div class="tarea" id="' + p.area + "_" + row + "_" + col + '" onclick="javascript:modTarea(' + col + "," + row + ",'" + p.area + "')\"><h5>" + p.area + "</h5><p>" + p.tarea.join(", ") + "</p></div>");
         });
         $(".modal").hide();
}
function addNewInput() {
         var name = $('#modalInitPerson input[name="name"]').val();
         if (name != "") {
                  $('#modalInitPerson input[name="name"]').val("");
                  var j = $("#modalInitPerson input[type=text]").length - 1;
                  var color = colors_aux[j];
                  colors[j] = color;
                  $("#modalInitPerson .rowParticipantes").append(
                           '<div class="rowForm groupRow">' +
                                    '<div class="groupAddPerson"><input id="person_name_' +
                                    j +
                                    '" type="text" value="' +
                                    name +
                                    '" placeholder="Introducir nombre..." name="person_name[]" style="border:2px solid ' +
                                    color +
                                    '">' +
                                    '<label for="color_person_' +
                                    j +
                                    '">' +
                                    '<i class="icon-color"></i>' +
                                    '<input id="color_person_' +
                                    j +
                                    '" type="color" value="" onchange="javascript:addColor(' +
                                    j +
                                    ',this.value);">' +
                                    "</label>" +
                                    "</div>"
                  );
         }
}
function createPersons() {
         $("#modalInitPerson input[type=text]").each(function (index) {
                  if ($(this).val() != "") {
                           var person_name = $(this).val();
                           person_name = person_name.toUpperCase();
                           person.push(person_name);
                           var color_temp = colors_aux.shift();
                           console.log(color_temp);
                           colors.push(color_temp);
                           colors_aux.push(color_temp);
                           localStorage.setItem("person", person);
                           var n_row = parseInt(person.length);
                           tasks.push(["", "", "", "", "", "", "", ""]);
                           var row =
                                    '<div id="person_' +
                                    n_row +
                                    '" data-row="' +
                                    n_row +
                                    '" class="row_task"><div class="col col_person"><b>' +
                                    person_name.substring(0, 1) +
                                    "</b><span>" +
                                    person_name +
                                    '</span></div><div class="col col_task" onclick="showModalTask(' +
                                    n_row +
                                    ',2);" data-row="' +
                                    n_row +
                                    '" data-col="2"></div><div class="col col_task" \tonclick="showModalTask(' +
                                    n_row +
                                    ',3);" data-row="' +
                                    n_row +
                                    '" data-col="3"></div><div class="col col_task" onclick="showModalTask(' +
                                    n_row +
                                    ',4);" data-row="' +
                                    n_row +
                                    '" data-col="4"></div><div class="col col_task" onclick="showModalTask(' +
                                    n_row +
                                    ',5);" data-row="' +
                                    n_row +
                                    '" data-col="5"></div><div class="col col_task" onclick="showModalTask(' +
                                    n_row +
                                    ',6);" data-row="' +
                                    n_row +
                                    '" data-col="6"></div><div \tclass="col col_task" onclick="showModalTask(' +
                                    n_row +
                                    ',7);" data-row="' +
                                    n_row +
                                    '" data-col="7"></div><div class="col col_task" onclick="showModalTask(' +
                                    n_row +
                                    ',8);" data-row="' +
                                    n_row +
                                    '" data-col="8"></div></div>';
                           $(".dummyContentCalendar .exampleRow:nth-of-type(1)").remove();
                           $(".dummyContentCalendar").before(row);
                           perfectRow();
                           $("#calendar .col_task:nth-of-type(2)").show();
                  }
         });
         mobileFix();
         if (person.length > 0) {
                  $(".modal").hide();
         } else {
                  alert("Necesitas crear al menos un participante, para empezar.");
         }
}
function addColor(number, color) {
         colors[number] = color;
         $("#person_name_" + number).css("border", "2px solid " + color);
}
$(document).ready(function () {
         mobileFix();
         generateSelect_Areas();
         $("#modalPerson button.submit").click(function (event) {
                  event.preventDefault();
                  addPerson();
         });
         $("#modalTasks button.submit").click(function (event) {
                  event.preventDefault();
                  addTask();
         });
         $(".btn-add").click(function (event) {
                  event.preventDefault();
                  if (person.length > 0) {
                           $(".modal").hide();
                           $(".day input:checkbox").removeAttr("checked");
                           $("#modalTasks input[name=col]").val("");
                           $("#modalTasks input[name=row]").val("");
                           $("#selectArea").multiselect("uncheckAll", true);
                           $("#selectTask").multiselect("uncheckAll", true);
                           var options = "";
                           var i = 0;
                           person.forEach(function (p) {
                                    options = options + '<option image="modules/custom/housework_organizer/images/' + colors[i] + '.png" color="' + colors[i] + '"  value="' + i + '">' + p + "</option>";
                                    i = i + 1;
                           });
                           $("#selectPerson").html(options);
                           $("#selectPerson").multiselect({
                                    multiple: false,
                                    header: false,
                                    height: "100px",
                                    noneSelectedText: "Selecciona una persona",
                                    selectedList: 1,
                                    show: ["blind", 200],
                                    hide: ["fade", 200],
                                    position: { my: "left top", at: "left bottom" },
                           });
                           $("#selectPerson").multiselect("uncheckAll", true);
                           $(".rowSelectPerson").show();
                           $("#modalTasks").toggle();
                  } else {
                          // alert("Antes debes de añadir una persona");
                          $("<div class='modal'>Antes debes añadir una persona</div>").dialog();
                  }
         });
         $(".btnCloseModal").click(function (event) {
                  event.preventDefault();
                  $(".modal").hide();
         });
         $(".addPerson").click(function (event) {
                  event.preventDefault();
                  $(".modal").hide();
                  $("#modalPerson").toggle();
                  $('input[name="person_name"]').focus();
         });
         $("#modalEditTasks .btnClose").click(function (event) {
                  event.preventDefault();
                  $("#modalEditTasks").hide();
         });
         $(".change_day").click(function (event) {
                  event.preventDefault();
                  var day = $(this).data("day");
                  mobile_day = day;
                  $(".mobile_day_selector div").removeClass("day_active");
                  $(this).addClass("day_active");
                  var col = day + 1;
                  $("#calendar .col_task").hide();
                  $("#calendar .col_task:nth-of-type(" + col + ")").show();
         });
         if (person.length == 0) {
                  $("#modalInitPerson").show();
         }
         perfectRow();
         $(window).resize(function () {
                  mobileFix();
         });
         $("input").keydown(function (e) {
                  if (e.keyCode == 13) {
                           e.preventDefault();
                           return false;
                  }
         });
});
