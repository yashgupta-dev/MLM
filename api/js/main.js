/*
 Platform.js <https://mths.be/platform>
 Copyright 2014-2018 Benjamin Tan <https://bnjmnt4n.now.sh/>
 Copyright 2011-2013 John-David Dalton
 Available under MIT license <https://mths.be/mit>
*/
(function () {
    var a = "undefined" !== typeof window && "undefined" !== typeof window.document ? window.document : {},
        c = "undefined" !== typeof module && module.exports,
        b = "undefined" !== typeof Element && "ALLOW_KEYBOARD_INPUT" in Element,
        d = (function () {
            for (
                var b,
                    c = [
                        "requestFullscreen exitFullscreen fullscreenElement fullscreenEnabled fullscreenchange fullscreenerror".split(" "),
                        "webkitRequestFullscreen webkitExitFullscreen webkitFullscreenElement webkitFullscreenEnabled webkitfullscreenchange webkitfullscreenerror".split(" "),
                        "webkitRequestFullScreen webkitCancelFullScreen webkitCurrentFullScreenElement webkitCancelFullScreen webkitfullscreenchange webkitfullscreenerror".split(" "),
                        "mozRequestFullScreen mozCancelFullScreen mozFullScreenElement mozFullScreenEnabled mozfullscreenchange mozfullscreenerror".split(" "),
                        "msRequestFullscreen msExitFullscreen msFullscreenElement msFullscreenEnabled MSFullscreenChange MSFullscreenError".split(" "),
                    ],
                    e = 0,
                    g = c.length,
                    d = {};
                e < g;
                e++
            )
                if ((b = c[e]) && b[1] in a) {
                    for (e = 0; e < b.length; e++) d[c[0][e]] = b[e];
                    return d;
                }
            return !1;
        })(),
        h = { change: d.fullscreenchange, error: d.fullscreenerror },
        k = {
            request: function (c) {
                var f = d.requestFullscreen;
                c = c || a.documentElement;
                if (/5\.1[.\d]* Safari/.test(navigator.userAgent)) c[f]();
                else c[f](b && Element.ALLOW_KEYBOARD_INPUT);
            },
            exit: function () {
                a[d.exitFullscreen]();
            },
            toggle: function (a) {
                this.isFullscreen ? this.exit() : this.request(a);
            },
            onchange: function (a) {
                this.on("change", a);
            },
            onerror: function (a) {
                this.on("error", a);
            },
            on: function (b, c) {
                var e = h[b];
                e && a.addEventListener(e, c, !1);
            },
            off: function (b, c) {
                var e = h[b];
                e && a.removeEventListener(e, c, !1);
            },
            raw: d,
        };
    d
        ? (Object.defineProperties(k, {
              isFullscreen: {
                  get: function () {
                      return !!a[d.fullscreenElement];
                  },
              },
              element: {
                  enumerable: !0,
                  get: function () {
                      return a[d.fullscreenElement];
                  },
              },
              enabled: {
                  enumerable: !0,
                  get: function () {
                      return !!a[d.fullscreenEnabled];
                  },
              },
          }),
          c ? (module.exports = k) : (window.screenfull = k))
        : c
        ? (module.exports = !1)
        : (window.screenfull = !1);
})();
(function () {
    function a(a) {
        a = String(a);
        return a.charAt(0).toUpperCase() + a.slice(1);
    }
    function c(a, b) {
        var c = -1,
            e = a ? a.length : 0;
        if ("number" == typeof e && -1 < e && e <= q) for (; ++c < e; ) b(a[c], c, a);
        else d(a, b);
    }
    function b(b) {
        b = String(b).replace(/^ +| +$/g, "");
        return /^(?:webOS|i(?:OS|P))/.test(b) ? b : a(b);
    }
    function d(a, b) {
        for (var c in a) w.call(a, c) && b(a[c], c, a);
    }
    function h(b) {
        return null == b ? a(b) : A.call(b).slice(8, -1);
    }
    function k(a, b) {
        var c = null != a ? typeof a[b] : "number";
        return !/^(?:boolean|number|string|undefined)$/.test(c) && ("object" == c ? !!a[b] : !0);
    }
    function f(a) {
        return String(a).replace(/([ -])(?!$)/g, "$1?");
    }
    function p(a, b) {
        var e = null;
        c(a, function (c, g) {
            e = b(e, c, g, a);
        });
        return e;
    }
    function e(a) {
        function c(c) {
            return p(c, function (c, e) {
                var g = e.pattern || f(e);
                !c &&
                    (c = RegExp("\\b" + g + " *\\d+[.\\w_]*", "i").exec(a) || RegExp("\\b" + g + " *\\w+-[\\w]*", "i").exec(a) || RegExp("\\b" + g + "(?:; *(?:[a-z]+[_-])?[a-z]+\\d+|[^ ();-]*)", "i").exec(a)) &&
                    ((c = String(e.label && !RegExp(g, "i").test(e.label) ? e.label : c).split("/"))[1] && !/[\d.]+/.test(c[0]) && (c[0] += " " + c[1]),
                    (e = e.label || e),
                    (c = b(
                        c[0]
                            .replace(RegExp(g, "i"), e)
                            .replace(RegExp("; *(?:" + e + "[_-])?", "i"), " ")
                            .replace(RegExp("(" + e + ")[-_.]?(\\w)", "i"), "$1 $2")
                    )));
                return c;
            });
        }
        function g(b) {
            return p(b, function (b, c) {
                return b || (RegExp(c + "(?:-[\\d.]+/|(?: for [\\w-]+)?[ /-])([\\d.]+[^ ();/_-]*)", "i").exec(a) || 0)[1] || null;
            });
        }
        var m = n,
            q = a && "object" == typeof a && "String" != h(a);
        q && ((m = a), (a = null));
        var r = m.navigator || {},
            t = r.userAgent || "";
        a || (a = t);
        var w = q ? !!r.likeChrome : /\bChrome\b/.test(a) && !/internal|\n/i.test(A.toString()),
            z = q ? "Object" : "ScriptBridgingProxyObject",
            E = q ? "Object" : "Environment",
            L = q && m.java ? "JavaPackage" : h(m.java),
            M = q ? "Object" : "RuntimeObject";
        E = (L = /\bJava/.test(L) && m.java) && h(m.environment) == E;
        var y = L ? "a" : "\u03b1",
            R = L ? "b" : "\u03b2",
            T = m.document || {},
            I = m.operamini || m.opera,
            S = x.test((S = q && I ? I["[[Class]]"] : h(I))) ? S : (I = null),
            l,
            P = a;
        q = [];
        var U = null,
            G = a == t;
        t = G && I && "function" == typeof I.version && I.version();
        var B = (function (b) {
                return p(b, function (b, c) {
                    return b || (RegExp("\\b" + (c.pattern || f(c)) + "\\b", "i").exec(a) && (c.label || c));
                });
            })([{ label: "EdgeHTML", pattern: "Edge" }, "Trident", { label: "WebKit", pattern: "AppleWebKit" }, "iCab", "Presto", "NetFront", "Tasman", "KHTML", "Gecko"]),
            u = (function (b) {
                return p(b, function (b, c) {
                    return b || (RegExp("\\b" + (c.pattern || f(c)) + "\\b", "i").exec(a) && (c.label || c));
                });
            })([
                "Adobe AIR",
                "Arora",
                "Avant Browser",
                "Breach",
                "Camino",
                "Electron",
                "Epiphany",
                "Fennec",
                "Flock",
                "Galeon",
                "GreenBrowser",
                "iCab",
                "Iceweasel",
                "K-Meleon",
                "Konqueror",
                "Lunascape",
                "Maxthon",
                { label: "Microsoft Edge", pattern: "Edge" },
                "Midori",
                "Nook Browser",
                "PaleMoon",
                "PhantomJS",
                "Raven",
                "Rekonq",
                "RockMelt",
                { label: "Samsung Internet", pattern: "SamsungBrowser" },
                "SeaMonkey",
                { label: "Silk", pattern: "(?:Cloud9|Silk-Accelerated)" },
                "Sleipnir",
                "SlimBrowser",
                { label: "SRWare Iron", pattern: "Iron" },
                "Sunrise",
                "Swiftfox",
                "Waterfox",
                "WebPositive",
                "Opera Mini",
                { label: "Opera Mini", pattern: "OPiOS" },
                "Opera",
                { label: "Opera", pattern: "OPR" },
                "Chrome",
                { label: "Chrome Mobile", pattern: "(?:CriOS|CrMo)" },
                { label: "Firefox", pattern: "(?:Firefox|Minefield)" },
                { label: "Firefox for iOS", pattern: "FxiOS" },
                { label: "IE", pattern: "IEMobile" },
                { label: "IE", pattern: "MSIE" },
                "Safari",
            ]),
            C = c([
                { label: "BlackBerry", pattern: "BB10" },
                "BlackBerry",
                { label: "Galaxy S", pattern: "GT-I9000" },
                { label: "Galaxy S2", pattern: "GT-I9100" },
                { label: "Galaxy S3", pattern: "GT-I9300" },
                { label: "Galaxy S4", pattern: "GT-I9500" },
                { label: "Galaxy S5", pattern: "SM-G900" },
                { label: "Galaxy S6", pattern: "SM-G920" },
                { label: "Galaxy S6 Edge", pattern: "SM-G925" },
                { label: "Galaxy S7", pattern: "SM-G930" },
                { label: "Galaxy S7 Edge", pattern: "SM-G935" },
                "Google TV",
                "Lumia",
                "iPad",
                "iPod",
                "iPhone",
                "Kindle",
                { label: "Kindle Fire", pattern: "(?:Cloud9|Silk-Accelerated)" },
                "Nexus",
                "Nook",
                "PlayBook",
                "PlayStation Vita",
                "PlayStation",
                "TouchPad",
                "Transformer",
                { label: "Wii U", pattern: "WiiU" },
                "Wii",
                "Xbox One",
                { label: "Xbox 360", pattern: "Xbox" },
                "Xoom",
            ]),
            K = (function (b) {
                return p(b, function (b, c, e) {
                    return b || ((c[C] || c[/^[a-z]+(?: +[a-z]+\b)*/i.exec(C)] || RegExp("\\b" + f(e) + "(?:\\b|\\w*\\d)", "i").exec(a)) && e);
                });
            })({
                Apple: { iPad: 1, iPhone: 1, iPod: 1 },
                Archos: {},
                Amazon: { Kindle: 1, "Kindle Fire": 1 },
                Asus: { Transformer: 1 },
                "Barnes & Noble": { Nook: 1 },
                BlackBerry: { PlayBook: 1 },
                Google: { "Google TV": 1, Nexus: 1 },
                HP: { TouchPad: 1 },
                HTC: {},
                LG: {},
                Microsoft: { Xbox: 1, "Xbox One": 1 },
                Motorola: { Xoom: 1 },
                Nintendo: { "Wii U": 1, Wii: 1 },
                Nokia: { Lumia: 1 },
                Samsung: { "Galaxy S": 1, "Galaxy S2": 1, "Galaxy S3": 1, "Galaxy S4": 1 },
                Sony: { PlayStation: 1, "PlayStation Vita": 1 },
            }),
            v = (function (c) {
                return p(c, function (c, e) {
                    var g = e.pattern || f(e);
                    if (!c && (c = RegExp("\\b" + g + "(?:/[\\d.]+|[ \\w.]*)", "i").exec(a))) {
                        var d = c,
                            B = e.label || e,
                            k = {
                                "10.0": "10",
                                "6.4": "10 Technical Preview",
                                "6.3": "8.1",
                                "6.2": "8",
                                "6.1": "Server 2008 R2 / 7",
                                "6.0": "Server 2008 / Vista",
                                "5.2": "Server 2003 / XP 64-bit",
                                "5.1": "XP",
                                "5.01": "2000 SP1",
                                "5.0": "2000",
                                "4.0": "NT",
                                "4.90": "ME",
                            };
                        g && B && /^Win/i.test(d) && !/^Windows Phone /i.test(d) && (k = k[/[\d.]+$/.exec(d)]) && (d = "Windows " + k);
                        d = String(d);
                        g && B && (d = d.replace(RegExp(g, "i"), B));
                        c = d = b(
                            d
                                .replace(/ ce$/i, " CE")
                                .replace(/\bhpw/i, "web")
                                .replace(/\bMacintosh\b/, "Mac OS")
                                .replace(/_PowerPC\b/i, " OS")
                                .replace(/\b(OS X) [^ \d]+/i, "$1")
                                .replace(/\bMac (OS X)\b/, "$1")
                                .replace(/\/(\d)/, " $1")
                                .replace(/_/g, ".")
                                .replace(/(?: BePC|[ .]*fc[ \d.]+)$/i, "")
                                .replace(/\bx86\.64\b/gi, "x86_64")
                                .replace(/\b(Windows Phone) OS\b/, "$1")
                                .replace(/\b(Chrome OS \w+) [\d.]+\b/, "$1")
                                .split(" on ")[0]
                        );
                    }
                    return c;
                });
            })([
                "Windows Phone",
                "Android",
                "CentOS",
                { label: "Chrome OS", pattern: "CrOS" },
                "Debian",
                "Fedora",
                "FreeBSD",
                "Gentoo",
                "Haiku",
                "Kubuntu",
                "Linux Mint",
                "OpenBSD",
                "Red Hat",
                "SuSE",
                "Ubuntu",
                "Xubuntu",
                "Cygwin",
                "Symbian OS",
                "hpwOS",
                "webOS ",
                "webOS",
                "Tablet OS",
                "Tizen",
                "Linux",
                "Mac OS X",
                "Macintosh",
                "Mac",
                "Windows 98;",
                "Windows ",
            ]);
        B && (B = [B]);
        K && !C && (C = c([K]));
        if ((l = /\bGoogle TV\b/.exec(C))) C = l[0];
        /\bSimulator\b/i.test(a) && (C = (C ? C + " " : "") + "Simulator");
        "Opera Mini" == u && /\bOPiOS\b/.test(a) && q.push("running in Turbo/Uncompressed mode");
        "IE" == u && /\blike iPhone OS\b/.test(a)
            ? ((l = e(a.replace(/like iPhone OS/, ""))), (K = l.manufacturer), (C = l.product))
            : /^iP/.test(C)
            ? (u || (u = "Safari"), (v = "iOS" + ((l = / OS ([\d_]+)/i.exec(a)) ? " " + l[1].replace(/_/g, ".") : "")))
            : "Konqueror" != u || /buntu/i.test(v)
            ? (K && "Google" != K && ((/Chrome/.test(u) && !/\bMobile Safari\b/i.test(a)) || /\bVita\b/.test(C))) || (/\bAndroid\b/.test(v) && /^Chrome/.test(u) && /\bVersion\//i.test(a))
                ? ((u = "Android Browser"), (v = /\bAndroid\b/.test(v) ? v : "Android"))
                : "Silk" == u
                ? (/\bMobi/i.test(a) || ((v = "Android"), q.unshift("desktop mode")), /Accelerated *= *true/i.test(a) && q.unshift("accelerated"))
                : "PaleMoon" == u && (l = /\bFirefox\/([\d.]+)\b/.exec(a))
                ? q.push("identifying as Firefox " + l[1])
                : "Firefox" == u && (l = /\b(Mobile|Tablet|TV)\b/i.exec(a))
                ? (v || (v = "Firefox OS"), C || (C = l[1]))
                : !u || (l = !/\bMinefield\b/i.test(a) && /\b(?:Firefox|Safari)\b/.exec(u))
                ? (u && !C && /[\/,]|^[^(]+?\)/.test(a.slice(a.indexOf(l + "/") + 8)) && (u = null),
                  (l = C || K || v) && (C || K || /\b(?:Android|Symbian OS|Tablet OS|webOS)\b/.test(v)) && (u = /[a-z]+(?: Hat)?/i.exec(/\bAndroid\b/.test(v) ? v : l) + " Browser"))
                : "Electron" == u && (l = (/\bChrome\/([\d.]+)\b/.exec(a) || 0)[1]) && q.push("Chromium " + l)
            : (v = "Kubuntu");
        t || (t = g(["(?:Cloud9|CriOS|CrMo|Edge|FxiOS|IEMobile|Iron|Opera ?Mini|OPiOS|OPR|Raven|SamsungBrowser|Silk(?!/[\\d.]+$))", "Version", f(u), "(?:Firefox|Minefield|NetFront)"]));
        if (
            (l =
                ("iCab" == B && 3 < parseFloat(t) && "WebKit") ||
                (/\bOpera\b/.test(u) && (/\bOPR\b/.test(a) ? "Blink" : "Presto")) ||
                (/\b(?:Midori|Nook|Safari)\b/i.test(a) && !/^(?:Trident|EdgeHTML)$/.test(B) && "WebKit") ||
                (!B && /\bMSIE\b/i.test(a) && ("Mac OS" == v ? "Tasman" : "Trident")) ||
                ("WebKit" == B && /\bPlayStation\b(?! Vita\b)/i.test(u) && "NetFront"))
        )
            B = [l];
        "IE" == u && (l = (/; *(?:XBLWP|ZuneWP)(\d+)/i.exec(a) || 0)[1])
            ? ((u += " Mobile"), (v = "Windows Phone " + (/\+$/.test(l) ? l : l + ".x")), q.unshift("desktop mode"))
            : /\bWPDesktop\b/i.test(a)
            ? ((u = "IE Mobile"), (v = "Windows Phone 8.x"), q.unshift("desktop mode"), t || (t = (/\brv:([\d.]+)/.exec(a) || 0)[1]))
            : "IE" != u && "Trident" == B && (l = /\brv:([\d.]+)/.exec(a)) && (u && q.push("identifying as " + u + (t ? " " + t : "")), (u = "IE"), (t = l[1]));
        if (G) {
            if (k(m, "global"))
                if ((L && ((l = L.lang.System), (P = l.getProperty("os.arch")), (v = v || l.getProperty("os.name") + " " + l.getProperty("os.version"))), E)) {
                    try {
                        (t = m.require("ringo/engine").version.join(".")), (u = "RingoJS");
                    } catch (X) {
                        (l = m.system) && l.global.system == m.system && ((u = "Narwhal"), v || (v = l[0].os || null));
                    }
                    u || (u = "Rhino");
                } else
                    "object" == typeof m.process &&
                        !m.process.browser &&
                        (l = m.process) &&
                        ("object" == typeof l.versions &&
                            ("string" == typeof l.versions.electron
                                ? (q.push("Node " + l.versions.node), (u = "Electron"), (t = l.versions.electron))
                                : "string" == typeof l.versions.nw && (q.push("Chromium " + t, "Node " + l.versions.node), (u = "NW.js"), (t = l.versions.nw))),
                        u || ((u = "Node.js"), (P = l.arch), (v = l.platform), (t = (t = /[\d.]+/.exec(l.version)) ? t[0] : null)));
            else
                h((l = m.runtime)) == z
                    ? ((u = "Adobe AIR"), (v = l.flash.system.Capabilities.os))
                    : h((l = m.phantom)) == M
                    ? ((u = "PhantomJS"), (t = (l = l.version || null) && l.major + "." + l.minor + "." + l.patch))
                    : "number" == typeof T.documentMode && (l = /\bTrident\/(\d+)/i.exec(a))
                    ? ((t = [t, T.documentMode]), (l = +l[1] + 4) != t[1] && (q.push("IE " + t[1] + " mode"), B && (B[1] = ""), (t[1] = l)), (t = "IE" == u ? String(t[1].toFixed(1)) : t[0]))
                    : "number" == typeof T.documentMode && /^(?:Chrome|Firefox)\b/.test(u) && (q.push("masking as " + u + " " + t), (u = "IE"), (t = "11.0"), (B = ["Trident"]), (v = "Windows"));
            v = v && b(v);
        }
        t &&
            (l = /(?:[ab]|dp|pre|[ab]\d+pre)(?:\d+\+?)?$/i.exec(t) || /(?:alpha|beta)(?: ?\d)?/i.exec(a + ";" + (G && r.appMinorVersion)) || (/\bMinefield\b/i.test(a) && "a")) &&
            ((U = /b/i.test(l) ? "beta" : "alpha"), (t = t.replace(RegExp(l + "\\+?$"), "") + ("beta" == U ? R : y) + (/\d+\+?/.exec(l) || "")));
        if ("Fennec" == u || ("Firefox" == u && /\b(?:Android|Firefox OS)\b/.test(v))) u = "Firefox Mobile";
        else if ("Maxthon" == u && t) t = t.replace(/\.[\d.]+/, ".x");
        else if (/\bXbox\b/i.test(C)) "Xbox 360" == C && (v = null), "Xbox 360" == C && /\bIEMobile\b/.test(a) && q.unshift("mobile mode");
        else if ((!/^(?:Chrome|IE|Opera)$/.test(u) && (!u || C || /Browser|Mobi/.test(u))) || ("Windows CE" != v && !/Mobi/i.test(a)))
            if ("IE" == u && G)
                try {
                    null === m.external && q.unshift("platform preview");
                } catch (X) {
                    q.unshift("embedded");
                }
            else
                (/\bBlackBerry\b/.test(C) || /\bBB10\b/.test(a)) && (l = (RegExp(C.replace(/ +/g, " *") + "/([.\\d]+)", "i").exec(a) || 0)[1] || t)
                    ? ((l = [l, /BB10/.test(a)]), (v = (l[1] ? ((C = null), (K = "BlackBerry")) : "Device Software") + " " + l[0]), (t = null))
                    : this != d &&
                      "Wii" != C &&
                      ((G && I) ||
                          (/Opera/.test(u) && /\b(?:MSIE|Firefox)\b/i.test(a)) ||
                          ("Firefox" == u && /\bOS X (?:\d+\.){2,}/.test(v)) ||
                          ("IE" == u && ((v && !/^Win/.test(v) && 5.5 < t) || (/\bWindows XP\b/.test(v) && 8 < t) || (8 == t && !/\bTrident\b/.test(a))))) &&
                      !x.test((l = e.call(d, a.replace(x, "") + ";"))) &&
                      l.name &&
                      ((l = "ing as " + l.name + ((l = l.version) ? " " + l : "")),
                      x.test(u) ? (/\bIE\b/.test(l) && "Mac OS" == v && (v = null), (l = "identify" + l)) : ((l = "mask" + l), (u = S ? b(S.replace(/([a-z])([A-Z])/g, "$1 $2")) : "Opera"), /\bIE\b/.test(l) && (v = null), G || (t = null)),
                      (B = ["Presto"]),
                      q.push(l));
        else u += " Mobile";
        if ((l = (/\bAppleWebKit\/([\d.]+\+?)/i.exec(a) || 0)[1])) {
            l = [parseFloat(l.replace(/\.(\d)$/, ".0$1")), l];
            if ("Safari" == u && "+" == l[1].slice(-1)) (u = "WebKit Nightly"), (U = "alpha"), (t = l[1].slice(0, -1));
            else if (t == l[1] || t == (l[2] = (/\bSafari\/([\d.]+\+?)/i.exec(a) || 0)[1])) t = null;
            l[1] = (/\bChrome\/([\d.]+)/i.exec(a) || 0)[1];
            537.36 == l[0] && 537.36 == l[2] && 28 <= parseFloat(l[1]) && "WebKit" == B && (B = ["Blink"]);
            G && (w || l[1])
                ? (B && (B[1] = "like Chrome"),
                  (l =
                      l[1] ||
                      ((l = l[0]),
                      530 > l
                          ? 1
                          : 532 > l
                          ? 2
                          : 532.05 > l
                          ? 3
                          : 533 > l
                          ? 4
                          : 534.03 > l
                          ? 5
                          : 534.07 > l
                          ? 6
                          : 534.1 > l
                          ? 7
                          : 534.13 > l
                          ? 8
                          : 534.16 > l
                          ? 9
                          : 534.24 > l
                          ? 10
                          : 534.3 > l
                          ? 11
                          : 535.01 > l
                          ? 12
                          : 535.02 > l
                          ? "13+"
                          : 535.07 > l
                          ? 15
                          : 535.11 > l
                          ? 16
                          : 535.19 > l
                          ? 17
                          : 536.05 > l
                          ? 18
                          : 536.1 > l
                          ? 19
                          : 537.01 > l
                          ? 20
                          : 537.11 > l
                          ? "21+"
                          : 537.13 > l
                          ? 23
                          : 537.18 > l
                          ? 24
                          : 537.24 > l
                          ? 25
                          : 537.36 > l
                          ? 26
                          : "Blink" != B
                          ? "27"
                          : "28")))
                : (B && (B[1] = "like Safari"), (l = ((l = l[0]), 400 > l ? 1 : 500 > l ? 2 : 526 > l ? 3 : 533 > l ? 4 : 534 > l ? "4+" : 535 > l ? 5 : 537 > l ? 6 : 538 > l ? 7 : 601 > l ? 8 : "8")));
            B && (B[1] += " " + (l += "number" == typeof l ? ".x" : /[.+]/.test(l) ? "" : "+"));
            "Safari" == u && (!t || 45 < parseInt(t)) && (t = l);
        }
        "Opera" == u && (l = /\bzbov|zvav$/.exec(v))
            ? ((u += " "), q.unshift("desktop mode"), "zvav" == l ? ((u += "Mini"), (t = null)) : (u += "Mobile"), (v = v.replace(RegExp(" *" + l + "$"), "")))
            : "Safari" == u && /\bChrome\b/.exec(B && B[1]) && (q.unshift("desktop mode"), (u = "Chrome Mobile"), (t = null), /\bOS X\b/.test(v) ? ((K = "Apple"), (v = "iOS 4.3+")) : (v = null));
        t && 0 == t.indexOf((l = /[\d.]+$/.exec(v))) && -1 < a.indexOf("/" + l + "-") && (v = String(v.replace(l, "")).replace(/^ +| +$/g, ""));
        B &&
            !/\b(?:Avant|Nook)\b/.test(u) &&
            (/Browser|Lunascape|Maxthon/.test(u) || ("Safari" != u && /^iOS/.test(v) && /\bSafari\b/.test(B[1])) || (/^(?:Adobe|Arora|Breach|Midori|Opera|Phantom|Rekonq|Rock|Samsung Internet|Sleipnir|Web)/.test(u) && B[1])) &&
            (l = B[B.length - 1]) &&
            q.push(l);
        q.length && (q = ["(" + q.join("; ") + ")"]);
        K && C && 0 > C.indexOf(K) && q.push("on " + K);
        C && q.push((/^on /.test(q[q.length - 1]) ? "" : "on ") + C);
        if (v) {
            var W = (l = / ([\d.+]+)$/.exec(v)) && "/" == v.charAt(v.length - l[0].length - 1);
            v = {
                architecture: 32,
                family: l && !W ? v.replace(l[0], "") : v,
                version: l ? l[1] : null,
                toString: function () {
                    var a = this.version;
                    return this.family + (a && !W ? " " + a : "") + (64 == this.architecture ? " 64-bit" : "");
                },
            };
        }
        (l = /\b(?:AMD|IA|Win|WOW|x86_|x)64\b/i.exec(P)) && !/\bi686\b/i.test(P)
            ? (v && ((v.architecture = 64), (v.family = v.family.replace(RegExp(" *" + l), ""))), u && (/\bWOW64\b/i.test(a) || (G && /\w(?:86|32)$/.test(r.cpuClass || r.platform) && !/\bWin64; x64\b/i.test(a))) && q.unshift("32-bit"))
            : v && /^OS X/.test(v.family) && "Chrome" == u && 39 <= parseFloat(t) && (v.architecture = 64);
        a || (a = null);
        m = {};
        m.description = a;
        m.layout = B && B[0];
        m.manufacturer = K;
        m.name = u;
        m.prerelease = U;
        m.product = C;
        m.ua = a;
        m.version = u && t;
        m.os = v || {
            architecture: null,
            family: null,
            version: null,
            toString: function () {
                return "null";
            },
        };
        m.parse = e;
        m.toString = function () {
            return this.description || "";
        };
        m.version && q.unshift(t);
        m.name && q.unshift(u);
        v && u && (v != String(v).split(" ")[0] || (v != u.split(" ")[0] && !C)) && q.push(C ? "(" + v + ")" : "on " + v);
        q.length && (m.description = q.join(" "));
        return m;
    }
    var g = { function: !0, object: !0 },
        n = (g[typeof window] && window) || this,
        r = g[typeof exports] && exports;
    g = g[typeof module] && module && !module.nodeType && module;
    var m = r && g && "object" == typeof global && global;
    !m || (m.global !== m && m.window !== m && m.self !== m) || (n = m);
    var q = Math.pow(2, 53) - 1,
        x = /\bOpera/;
    m = Object.prototype;
    var w = m.hasOwnProperty,
        A = m.toString,
        E = e();
    "function" == typeof define && "object" == typeof define.amd && define.amd
        ? ((n.platform = E),
          define(function () {
              return E;
          }))
        : r && g
        ? d(E, function (a, b) {
              r[b] = a;
          })
        : (n.platform = E);
}.call(this));
!(function () {
    function a(c) {
        var k = c;
        if (d[k]) k = d[k];
        else {
            for (var f = k, p, e = [], g = 0; f; ) {
                if (null !== (p = b.text.exec(f))) e.push(p[0]);
                else if (null !== (p = b.modulo.exec(f))) e.push("%");
                else if (null !== (p = b.placeholder.exec(f))) {
                    if (p[2]) {
                        g |= 1;
                        var h = [],
                            r = p[2],
                            m;
                        if (null !== (m = b.key.exec(r)))
                            for (h.push(m[1]); "" !== (r = r.substring(m[0].length)); )
                                if (null !== (m = b.key_access.exec(r))) h.push(m[1]);
                                else if (null !== (m = b.index_access.exec(r))) h.push(m[1]);
                                else throw new SyntaxError("[sprintf] failed to parse named argument key");
                        else throw new SyntaxError("[sprintf] failed to parse named argument key");
                        p[2] = h;
                    } else g |= 2;
                    if (3 === g) throw Error("[sprintf] mixing positional and named placeholders is not (yet) supported");
                    e.push({ placeholder: p[0], param_no: p[1], keys: p[2], sign: p[3], pad_char: p[4], align: p[5], width: p[6], precision: p[7], type: p[8] });
                } else throw new SyntaxError("[sprintf] unexpected placeholder");
                f = f.substring(p[0].length);
            }
            k = d[k] = e;
        }
        f = arguments;
        p = 1;
        e = k.length;
        h = "";
        var q, x;
        for (r = 0; r < e; r++)
            if ("string" === typeof k[r]) h += k[r];
            else if ("object" === typeof k[r]) {
                m = k[r];
                if (m.keys)
                    for (g = f[p], q = 0; q < m.keys.length; q++) {
                        if (void 0 == g) throw Error(a('[sprintf] Cannot access property "%s" of undefined value "%s"', m.keys[q], m.keys[q - 1]));
                        g = g[m.keys[q]];
                    }
                else g = m.param_no ? f[m.param_no] : f[p++];
                b.not_type.test(m.type) && b.not_primitive.test(m.type) && g instanceof Function && (g = g());
                if (b.numeric_arg.test(m.type) && "number" !== typeof g && isNaN(g)) throw new TypeError(a("[sprintf] expecting number but found %T", g));
                b.number.test(m.type) && (x = 0 <= g);
                switch (m.type) {
                    case "b":
                        g = parseInt(g, 10).toString(2);
                        break;
                    case "c":
                        g = String.fromCharCode(parseInt(g, 10));
                        break;
                    case "d":
                    case "i":
                        g = parseInt(g, 10);
                        break;
                    case "j":
                        g = JSON.stringify(g, null, m.width ? parseInt(m.width) : 0);
                        break;
                    case "e":
                        g = m.precision ? parseFloat(g).toExponential(m.precision) : parseFloat(g).toExponential();
                        break;
                    case "f":
                        g = m.precision ? parseFloat(g).toFixed(m.precision) : parseFloat(g);
                        break;
                    case "g":
                        g = m.precision ? String(Number(g.toPrecision(m.precision))) : parseFloat(g);
                        break;
                    case "o":
                        g = (parseInt(g, 10) >>> 0).toString(8);
                        break;
                    case "s":
                        g = String(g);
                        g = m.precision ? g.substring(0, m.precision) : g;
                        break;
                    case "t":
                        g = String(!!g);
                        g = m.precision ? g.substring(0, m.precision) : g;
                        break;
                    case "T":
                        g = Object.prototype.toString.call(g).slice(8, -1).toLowerCase();
                        g = m.precision ? g.substring(0, m.precision) : g;
                        break;
                    case "u":
                        g = parseInt(g, 10) >>> 0;
                        break;
                    case "v":
                        g = g.valueOf();
                        g = m.precision ? g.substring(0, m.precision) : g;
                        break;
                    case "x":
                        g = (parseInt(g, 10) >>> 0).toString(16);
                        break;
                    case "X":
                        g = (parseInt(g, 10) >>> 0).toString(16).toUpperCase();
                }
                if (b.json.test(m.type)) h += g;
                else {
                    if (!b.number.test(m.type) || (x && !m.sign)) var w = "";
                    else (w = x ? "+" : "-"), (g = g.toString().replace(b.sign, ""));
                    q = m.pad_char ? ("0" === m.pad_char ? "0" : m.pad_char.charAt(1)) : " ";
                    var A = m.width - (w + g).length;
                    A = m.width ? (0 < A ? q.repeat(A) : "") : "";
                    h += m.align ? w + g + A : "0" === q ? w + A + g : A + w + g;
                }
            }
        return h;
    }
    function c(b, c) {
        return a.apply(null, [b].concat(c || []));
    }
    var b = {
            not_string: /[^s]/,
            not_bool: /[^t]/,
            not_type: /[^T]/,
            not_primitive: /[^v]/,
            number: /[diefg]/,
            numeric_arg: /[bcdiefguxX]/,
            json: /[j]/,
            not_json: /[^j]/,
            text: /^[^\x25]+/,
            modulo: /^\x25{2}/,
            placeholder: /^\x25(?:([1-9]\d*)\$|\(([^)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,
            key: /^([a-z_][a-z_\d]*)/i,
            key_access: /^\.([a-z_][a-z_\d]*)/i,
            index_access: /^\[(\d+)\]/,
            sign: /^[+-]/,
        },
        d = Object.create(null);
    "undefined" !== typeof exports && ((exports.sprintf = a), (exports.vsprintf = c));
    "undefined" !== typeof window &&
        ((window.sprintf = a),
        (window.vsprintf = c),
        "function" === typeof define &&
            define.amd &&
            define(function () {
                return { sprintf: a, vsprintf: c };
            }));
})();
function buildIOSMeta() {
    for (
        var a = [
                { name: "viewport", content: "width=device-width, height=device-height, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" },
                { name: "apple-mobile-web-app-capable", content: "yes" },
                { name: "apple-mobile-web-app-status-bar-style", content: "black" },
            ],
            c = 0;
        c < a.length;
        c++
    ) {
        var b = document.createElement("meta");
        b.name = a[c].name;
        b.content = a[c].content;
        var d = window.document.head.querySelector('meta[name="' + b.name + '"]');
        d && d.parentNode.removeChild(d);
        window.document.head.appendChild(b);
    }
}
function hideIOSFullscreenPanel() {
    jQuery(".xxx-ios-fullscreen-message").css("display", "none");
    jQuery(".xxx-ios-fullscreen-scroll").css("display", "none");
    jQuery(".xxx-game-iframe-full").removeClass("xxx-game-iframe-iphone-se");
}
function buildIOSFullscreenPanel() {
    jQuery("body").append('<div class="xxx-ios-fullscreen-message"><div class="xxx-ios-fullscreen-swipe"></div></div><div class="xxx-ios-fullscreen-scroll"></div>');
}
function showIOSFullscreenPanel() {
    jQuery(".xxx-ios-fullscreen-message").css("display", "block");
    jQuery(".xxx-ios-fullscreen-scroll").css("display", "block");
}
function __iosResize() {
    window.scrollTo(0, 0);
    if ("iPhone" === platform.product)
        switch (window.devicePixelRatio) {
            case 2:
                switch (window.innerWidth) {
                    case 568:
                        320 !== window.innerHeight && jQuery(".xxx-game-iframe-full").addClass("xxx-game-iframe-iphone-se");
                        break;
                    case 667:
                        375 === window.innerHeight ? hideIOSFullscreenPanel() : showIOSFullscreenPanel();
                        break;
                    default:
                        hideIOSFullscreenPanel();
                }
                break;
            case 3:
                switch (window.innerWidth) {
                    case 736:
                        414 === window.innerHeight ? hideIOSFullscreenPanel() : showIOSFullscreenPanel();
                        break;
                    case 724:
                        375 === window.innerHeight ? hideIOSFullscreenPanel() : showIOSFullscreenPanel();
                        break;
                    default:
                        hideIOSFullscreenPanel();
                }
                break;
            default:
                hideIOSFullscreenPanel();
        }
}
function iosResize() {
    __iosResize();
    setTimeout(function () {
        __iosResize();
    }, 500);
}
function iosInIframe() {
    try {
        return window.self !== window.top;
    } catch (a) {
        return !0;
    }
}
$(document).ready(function () {
    platform && "iPhone" === platform.product && !iosInIframe() && (buildIOSFullscreenPanel(), buildIOSMeta());
});
jQuery(window).resize(function () {
    platform && "iPhone" === platform.product && !iosInIframe() && iosResize();
});
var s_iScaleFactor = 1,
    s_bIsIphone = !1,
    s_iOffsetX,
    s_iOffsetY;
(function (a) {
    (jQuery.browser = jQuery.browser || {}).mobile =
        /android|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(ad|hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|tablet|treo|up\.(browser|link)|vodafone|wap|webos|windows (ce|phone)|xda|xiino/i.test(
            a
        ) ||
        /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(
            a.substr(0, 4)
        );
})(navigator.userAgent || navigator.vendor || window.opera);
$(window).resize(function () {
    sizeHandler();
});
function trace(a) {
    console.log(a);
}
function getSize(a) {
    var c = a.toLowerCase(),
        b = window.document,
        d = b.documentElement;
    if (void 0 === window["inner" + a]) a = d["client" + a];
    else if (window["inner" + a] != d["client" + a]) {
        var h = b.createElement("body");
        h.id = "vpw-test-b";
        h.style.cssText = "overflow:scroll";
        var k = b.createElement("div");
        k.id = "vpw-test-d";
        k.style.cssText = "position:absolute;top:-1000px";
        k.innerHTML = "<style>@media(" + c + ":" + d["client" + a] + "px){body#vpw-test-b div#vpw-test-d{" + c + ":7px!important}}</style>";
        h.appendChild(k);
        d.insertBefore(h, b.head);
        a = 7 == k["offset" + a] ? d["client" + a] : window["inner" + a];
        d.removeChild(h);
    } else a = window["inner" + a];
    return a;
}
function sizeHandler() {
    window.scrollTo(0, 1);
    if ($("#canvas")) {
        var a = "safari" === platform.name.toLowerCase() ? getIOSWindowHeight() : getSize("Height");
        var c = getSize("Width");
        _checkOrientation(c, a);
        var b = Math.min(a / CANVAS_HEIGHT, c / CANVAS_WIDTH),
            d = CANVAS_WIDTH * b;
        b *= CANVAS_HEIGHT;
        if (b < a) {
            var h = a - b;
            b += h;
            d += (CANVAS_WIDTH / CANVAS_HEIGHT) * h;
        } else d < c && ((h = c - d), (d += h), (b += (CANVAS_HEIGHT / CANVAS_WIDTH) * h));
        h = a / 2 - b / 2;
        var k = c / 2 - d / 2,
            f = CANVAS_WIDTH / d;
        if (k * f < -EDGEBOARD_X || h * f < -EDGEBOARD_Y)
            (b = Math.min(a / (CANVAS_HEIGHT - 2 * EDGEBOARD_Y), c / (CANVAS_WIDTH - 2 * EDGEBOARD_X))), (d = CANVAS_WIDTH * b), (b *= CANVAS_HEIGHT), (h = (a - b) / 2), (k = (c - d) / 2), (f = CANVAS_WIDTH / d);
        s_iOffsetX = -1 * k * f;
        s_iOffsetY = -1 * h * f;
        0 <= h && (s_iOffsetY = 0);
        0 <= k && (s_iOffsetX = 0);
        null !== s_oInterface && s_oInterface.refreshButtonPos(s_iOffsetX, s_iOffsetY);
        null !== s_oMenu && s_oMenu.refreshButtonPos(s_iOffsetX, s_iOffsetY);
        s_bIsIphone
            ? ((canvas = document.getElementById("canvas")),
              (s_oStage.canvas.width = 2 * d),
              (s_oStage.canvas.height = 2 * b),
              (canvas.style.width = d + "px"),
              (canvas.style.height = b + "px"),
              (c = Math.min(d / CANVAS_WIDTH, b / CANVAS_HEIGHT)),
              (s_iScaleFactor = 2 * c),
              (s_oStage.scaleX = s_oStage.scaleY = 2 * c))
            : s_bMobile || isChrome()
            ? ($("#canvas").css("width", d + "px"), $("#canvas").css("height", b + "px"))
            : ((s_oStage.canvas.width = d), (s_oStage.canvas.height = b), (s_iScaleFactor = Math.min(d / CANVAS_WIDTH, b / CANVAS_HEIGHT)), (s_oStage.scaleX = s_oStage.scaleY = s_iScaleFactor));
        0 > h || (h = (a - b) / 2);
        $("#canvas").css("top", h + "px");
        $("#canvas").css("left", k + "px");
        fullscreenHandler();
    }
}
window.addEventListener("orientationchange", onOrientationChange);
function onOrientationChange() {
    window.matchMedia("(orientation: portrait)").matches && sizeHandler();
    window.matchMedia("(orientation: landscape)").matches && sizeHandler();
}
function isChrome() {
    return /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
}
function isIOS() {
    var a = "iPad Simulator;iPhone Simulator;iPod Simulator;iPad;iPhone;iPod".split(";");
    for (-1 !== navigator.userAgent.toLowerCase().indexOf("iphone") && (s_bIsIphone = !0); a.length; ) if (navigator.platform === a.pop()) return !0;
    return (s_bIsIphone = !1);
}
function _checkOrientation(a, c) {
    s_bMobile &&
        ENABLE_CHECK_ORIENTATION &&
        (a > c
            ? "landscape" === $(".orientation-msg-container").attr("data-orientation")
                ? ($(".orientation-msg-container").css("display", "none"), s_oMain.startUpdate())
                : ($(".orientation-msg-container").css("display", "block"), s_oMain.stopUpdate())
            : "portrait" === $(".orientation-msg-container").attr("data-orientation")
            ? ($(".orientation-msg-container").css("display", "none"), s_oMain.startUpdate())
            : ($(".orientation-msg-container").css("display", "block"), s_oMain.stopUpdate()));
}
function getIOSWindowHeight() {
    return (document.documentElement.clientWidth / window.innerWidth) * window.innerHeight;
}
function getHeightOfIOSToolbars() {
    var a = (0 === window.orientation ? screen.height : screen.width) - getIOSWindowHeight();
    return 1 < a ? a : 0;
}
function playSound(a, c, b) {
    return !1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile ? (s_aSounds[a].play(), s_aSounds[a].volume(c), s_aSounds[a].loop(b), s_aSounds[a]) : null;
}
function stopSound(a) {
    (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || s_aSounds[a].stop();
}
function setVolume(a, c) {
    (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || s_aSounds[a].volume(c);
}
function setMute(a, c) {
    (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || s_aSounds[a].mute(c);
}
function fadeSound(a, c, b, d) {
    (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || s_aSounds[a].fade(c, b, d);
}
function soundPlaying(a) {
    if (!1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile) return s_aSounds[a].playing();
}
function createBitmap(a, c, b) {
    var d = new createjs.Bitmap(a),
        h = new createjs.Shape();
    c && b ? h.graphics.beginFill("#fff").drawRect(0, 0, c, b) : h.graphics.beginFill("#ff0").drawRect(0, 0, a.width, a.height);
    d.hitArea = h;
    return d;
}
function createSprite(a, c, b, d, h, k) {
    a = null !== c ? new createjs.Sprite(a, c) : new createjs.Sprite(a);
    c = new createjs.Shape();
    c.graphics.beginFill("#000000").drawRect(-b, -d, h, k);
    a.hitArea = c;
    return a;
}
function randomFloatBetween(a, c, b) {
    "undefined" === typeof b && (b = 2);
    return parseFloat(Math.min(a + Math.random() * (c - a), c).toFixed(b));
}
function tweenVectorsOnX(a, c, b) {
    return a + b * (c - a);
}
function shuffle(a) {
    for (var c = a.length, b, d; 0 !== c; ) (d = Math.floor(Math.random() * c)), --c, (b = a[c]), (a[c] = a[d]), (a[d] = b);
    return a;
}
function bubbleSort(a) {
    do {
        var c = !1;
        for (var b = 0; b < a.length - 1; b++) a[b] > a[b + 1] && ((c = a[b]), (a[b] = a[b + 1]), (a[b + 1] = c), (c = !0));
    } while (c);
}
function compare(a, c) {
    return a.index > c.index ? -1 : a.index < c.index ? 1 : 0;
}
function easeLinear(a, c, b, d) {
    return (b * a) / d + c;
}
function easeInQuad(a, c, b, d) {
    return b * (a /= d) * a + c;
}
function easeInSine(a, c, b, d) {
    return -b * Math.cos((a / d) * (Math.PI / 2)) + b + c;
}
function easeInCubic(a, c, b, d) {
    return b * (a /= d) * a * a + c;
}
function getTrajectoryPoint(a, c) {
    var b = new createjs.Point(),
        d = (1 - a) * (1 - a),
        h = a * a;
    b.x = d * c.start.x + 2 * (1 - a) * a * c.traj.x + h * c.end.x;
    b.y = d * c.start.y + 2 * (1 - a) * a * c.traj.y + h * c.end.y;
    return b;
}
function formatTime(a) {
    a /= 1e3;
    var c = Math.floor(a / 60);
    a = parseFloat(a - 60 * c).toFixed(1);
    var b = "";
    b = 10 > c ? b + ("0" + c + ":") : b + (c + ":");
    return 10 > a ? b + ("0" + a) : b + a;
}
function degreesToRadians(a) {
    return (a * Math.PI) / 180;
}
function checkRectCollision(a, c) {
    var b = getBounds(a, 0.9);
    var d = getBounds(c, 0.98);
    return calculateIntersection(b, d);
}
function calculateIntersection(a, c) {
    var b, d, h, k;
    var f = a.x + (b = a.width / 2);
    var p = a.y + (d = a.height / 2);
    var e = c.x + (h = c.width / 2);
    var g = c.y + (k = c.height / 2);
    f = Math.abs(f - e) - (b + h);
    p = Math.abs(p - g) - (d + k);
    return 0 > f && 0 > p ? ((f = Math.min(Math.min(a.width, c.width), -f)), (p = Math.min(Math.min(a.height, c.height), -p)), { x: Math.max(a.x, c.x), y: Math.max(a.y, c.y), width: f, height: p, rect1: a, rect2: c }) : null;
}
function getBounds(a, c) {
    var b = { x: Infinity, y: Infinity, width: 0, height: 0 };
    if (a instanceof createjs.Container) {
        b.x2 = -Infinity;
        b.y2 = -Infinity;
        var d = a.children,
            h = d.length,
            k;
        for (k = 0; k < h; k++) {
            var f = getBounds(d[k], 1);
            f.x < b.x && (b.x = f.x);
            f.y < b.y && (b.y = f.y);
            f.x + f.width > b.x2 && (b.x2 = f.x + f.width);
            f.y + f.height > b.y2 && (b.y2 = f.y + f.height);
        }
        Infinity == b.x && (b.x = 0);
        Infinity == b.y && (b.y = 0);
        Infinity == b.x2 && (b.x2 = 0);
        Infinity == b.y2 && (b.y2 = 0);
        b.width = b.x2 - b.x;
        b.height = b.y2 - b.y;
        delete b.x2;
        delete b.y2;
    } else {
        if (a instanceof createjs.Bitmap) {
            h = a.sourceRect || a.image;
            k = h.width * c;
            var p = h.height * c;
        } else if (a instanceof createjs.Sprite)
            if (a.spriteSheet._frames && a.spriteSheet._frames[a.currentFrame] && a.spriteSheet._frames[a.currentFrame].image) {
                h = a.spriteSheet.getFrame(a.currentFrame);
                k = h.rect.width;
                p = h.rect.height;
                d = h.regX;
                var e = h.regY;
            } else (b.x = a.x || 0), (b.y = a.y || 0);
        else (b.x = a.x || 0), (b.y = a.y || 0);
        d = d || 0;
        k = k || 0;
        e = e || 0;
        p = p || 0;
        b.regX = d;
        b.regY = e;
        h = a.localToGlobal(0 - d, 0 - e);
        f = a.localToGlobal(k - d, p - e);
        k = a.localToGlobal(k - d, 0 - e);
        d = a.localToGlobal(0 - d, p - e);
        b.x = Math.min(Math.min(Math.min(h.x, f.x), k.x), d.x);
        b.y = Math.min(Math.min(Math.min(h.y, f.y), k.y), d.y);
        b.width = Math.max(Math.max(Math.max(h.x, f.x), k.x), d.x) - b.x;
        b.height = Math.max(Math.max(Math.max(h.y, f.y), k.y), d.y) - b.y;
    }
    return b;
}
function NoClickDelay(a) {
    this.element = a;
    window.Touch && this.element.addEventListener("touchstart", this, !1);
}
function shuffle(a) {
    for (var c = a.length, b, d; 0 < c; ) (d = Math.floor(Math.random() * c)), c--, (b = a[c]), (a[c] = a[d]), (a[d] = b);
    return a;
}
NoClickDelay.prototype = {
    handleEvent: function (a) {
        switch (a.type) {
            case "touchstart":
                this.onTouchStart(a);
                break;
            case "touchmove":
                this.onTouchMove(a);
                break;
            case "touchend":
                this.onTouchEnd(a);
        }
    },
    onTouchStart: function (a) {
        a.preventDefault();
        this.moved = !1;
        this.element.addEventListener("touchmove", this, !1);
        this.element.addEventListener("touchend", this, !1);
    },
    onTouchMove: function (a) {
        this.moved = !0;
    },
    onTouchEnd: function (a) {
        this.element.removeEventListener("touchmove", this, !1);
        this.element.removeEventListener("touchend", this, !1);
        if (!this.moved) {
            a = document.elementFromPoint(a.changedTouches[0].clientX, a.changedTouches[0].clientY);
            3 == a.nodeType && (a = a.parentNode);
            var c = document.createEvent("MouseEvents");
            c.initEvent("click", !0, !0);
            a.dispatchEvent(c);
        }
    },
};
(function () {
    function a(a) {
        var b = { focus: "visible", focusin: "visible", pageshow: "visible", blur: "hidden", focusout: "hidden", pagehide: "hidden" };
        a = a || window.event;
        a.type in b ? (document.body.className = b[a.type]) : ((document.body.className = this[c] ? "hidden" : "visible"), "hidden" === document.body.className ? s_oMain.stopUpdate() : s_oMain.startUpdate());
    }
    var c = "hidden";
    c in document
        ? document.addEventListener("visibilitychange", a)
        : (c = "mozHidden") in document
        ? document.addEventListener("mozvisibilitychange", a)
        : (c = "webkitHidden") in document
        ? document.addEventListener("webkitvisibilitychange", a)
        : (c = "msHidden") in document
        ? document.addEventListener("msvisibilitychange", a)
        : "onfocusin" in document
        ? (document.onfocusin = document.onfocusout = a)
        : (window.onpageshow = window.onpagehide = window.onfocus = window.onblur = a);
})();
function ctlArcadeResume() {
    null !== s_oMain && s_oMain.startUpdate();
}
function ctlArcadePause() {
    null !== s_oMain && s_oMain.stopUpdate();
}
function getParamValue(a) {
    for (var c = window.location.search.substring(1).split("&"), b = 0; b < c.length; b++) {
        var d = c[b].split("=");
        if (d[0] == a) return d[1];
    }
}
function fullscreenHandler() {
    ENABLE_FULLSCREEN && screenfull.enabled && ((s_bFullscreen = screenfull.isFullscreen), null !== s_oInterface && s_oInterface.resetFullscreenBut(), null !== s_oMenu && s_oMenu.resetFullscreenBut());
}
if (screenfull.enabled)
    screenfull.on("change", function () {
        s_bFullscreen = screenfull.isFullscreen;
        null !== s_oInterface && s_oInterface.resetFullscreenBut();
        null !== s_oMenu && s_oMenu.resetFullscreenBut();
    });
function CSpriteLibrary() {
    var a = {},
        c,
        b,
        d,
        h,
        k,
        f;
    this.init = function (a, e, g) {
        c = {};
        d = b = 0;
        h = a;
        k = e;
        f = g;
    };
    this.addSprite = function (d, e) {
        if (!a.hasOwnProperty(d)) {
            var g = new Image();
            a[d] = c[d] = { szPath: e, oSprite: g, bLoaded: !1 };
            b++;
        }
    };
    this.getSprite = function (b) {
        return a.hasOwnProperty(b) ? a[b].oSprite : null;
    };
    this._onSpritesLoaded = function () {
        b = 0;
        k.call(f);
    };
    this._onSpriteLoaded = function () {
        h.call(f);
        ++d === b && this._onSpritesLoaded();
    };
    this.loadSprites = function () {
        for (var a in c)
            (c[a].oSprite.oSpriteLibrary = this),
                (c[a].oSprite.szKey = a),
                (c[a].oSprite.onload = function () {
                    this.oSpriteLibrary.setLoaded(this.szKey);
                    this.oSpriteLibrary._onSpriteLoaded(this.szKey);
                }),
                (c[a].oSprite.onerror = function (a) {
                    var b = a.currentTarget;
                    setTimeout(function () {
                        c[b.szKey].oSprite.src = c[b.szKey].szPath;
                    }, 500);
                }),
                (c[a].oSprite.src = c[a].szPath);
    };
    this.setLoaded = function (b) {
        a[b].bLoaded = !0;
    };
    this.isLoaded = function (b) {
        return a[b].bLoaded;
    };
    this.getNumSprites = function () {
        return b;
    };
}
CTLText.prototype = {
    constructor: CTLText,
    __autofit: function () {
        if (this._bFitText) {
            for (
                var a = this._iFontSize;
                (this._oText.getBounds().height > this._iHeight - 2 * this._iPaddingV || this._oText.getBounds().width > this._iWidth - 2 * this._iPaddingH) &&
                !(a--, (this._oText.font = a + "px " + this._szFont), (this._oText.lineHeight = Math.round(a * this._fLineHeightFactor)), this.__updateY(), this.__verticalAlign(), 8 > a);

            );
            this._iFontSize = a;
        }
    },
    __verticalAlign: function () {
        if (this._bVerticalAlign) {
            var a = this._oText.getBounds().height;
            this._oText.y -= (a - this._iHeight) / 2 + this._iPaddingV;
        }
    },
    __updateY: function () {
        this._oText.y = this._y + this._iPaddingV;
        switch (this._oText.textBaseline) {
            case "middle":
                this._oText.y += this._oText.lineHeight / 2 + (this._iFontSize * this._fLineHeightFactor - this._iFontSize);
        }
    },
    __createText: function (a) {
        this._bDebug && ((this._oDebugShape = new createjs.Shape()), this._oDebugShape.graphics.beginFill("rgba(255,0,0,0.5)").drawRect(this._x, this._y, this._iWidth, this._iHeight), this._oContainer.addChild(this._oDebugShape));
        this._oText = new createjs.Text(a, this._iFontSize + "px " + this._szFont, this._szColor);
        this._oText.textBaseline = "middle";
        this._oText.lineHeight = Math.round(this._iFontSize * this._fLineHeightFactor);
        this._oText.textAlign = this._szAlign;
        this._oText.lineWidth = this._bMultiline ? this._iWidth - 2 * this._iPaddingH : null;
        switch (this._szAlign) {
            case "center":
                this._oText.x = this._x + this._iWidth / 2;
                break;
            case "left":
                this._oText.x = this._x + this._iPaddingH;
                break;
            case "right":
                this._oText.x = this._x + this._iWidth - this._iPaddingH;
        }
        this._oContainer.addChild(this._oText);
        this.refreshText(a);
    },
    setVerticalAlign: function (a) {
        this._bVerticalAlign = a;
    },
    setOutline: function (a) {
        null !== this._oText && (this._oText.outline = a);
    },
    setShadow: function (a, c, b, d) {
        null !== this._oText && (this._oText.shadow = new createjs.Shadow(a, c, b, d));
    },
    setColor: function (a) {
        this._oText.color = a;
    },
    setAlpha: function (a) {
        this._oText.alpha = a;
    },
    setY: function (a) {
        this._y = this._oText.y = a;
    },
    removeTweens: function () {
        createjs.Tween.removeTweens(this._oText);
    },
    getText: function () {
        return this._oText;
    },
    getY: function () {
        return this._y;
    },
    getFontSize: function () {
        return this._iFontSize;
    },
    refreshText: function (a) {
        "" === a && (a = " ");
        null === this._oText && this.__createText(a);
        this._oText.text = a;
        this._oText.font = this._iFontSize + "px " + this._szFont;
        this._oText.lineHeight = Math.round(this._iFontSize * this._fLineHeightFactor);
        this.__autofit();
        this.__updateY();
        this.__verticalAlign();
    },
};
function CTLText(a, c, b, d, h, k, f, p, e, g, n, r, m, q, x, w, A) {
    this._oContainer = a;
    this._x = c;
    this._y = b;
    this._iWidth = d;
    this._iHeight = h;
    this._bMultiline = w;
    this._iFontSize = k;
    this._szAlign = f;
    this._szColor = p;
    this._szFont = e;
    this._iPaddingH = n;
    this._iPaddingV = r;
    this._bVerticalAlign = x;
    this._bFitText = q;
    this._bDebug = A;
    this._oDebugShape = null;
    this._fLineHeightFactor = g;
    this._oText = null;
    m && this.__createText(m);
}
var CANVAS_WIDTH = 1080,
    CANVAS_HEIGHT = 1136,
    EDGEBOARD_X = 220,
    EDGEBOARD_Y = 0,
    FPS = 24,
    FPS_TIME = 1e3 / 24,
    DISABLE_SOUND_MOBILE = !1,
    PRIMARY_FONT = "walibi",
    SOUNDTRACK_VOLUME_IN_GAME = 0.6,
    STATE_LOADING = 0,
    STATE_MENU = 1,
    STATE_HELP = 1,
    STATE_GAME = 3,
    ON_MOUSE_DOWN = 0,
    ON_MOUSE_UP = 1,
    ON_MOUSE_OVER = 2,
    ON_MOUSE_OUT = 3,
    ON_DRAG_START = 4,
    ON_DRAG_END = 5,
    H_MOVE = 37,
    V_MOVE = 50,
    LEVEL_TIME,
    MAX_LEVEL_DIFFICULTY = 20,
    SCORE_IN_NEST,
    SCORE_WITH_FLY,
    SCORE_DEATH,
    UNLOAD_OFFSET = 100,
    TURTLE_OFFSET = 74,
    NUM_CONSECUTIVE_TURTLE_0 = 3,
    NUM_CONSECUTIVE_TURTLE_3 = 2,
    SINK_TURTLE_OCCURRENCY,
    NUM_LEVEL_INCREASE_SINK,
    TIME_FLY_TO_SPAWN,
    TIME_FLY_TO_DISAPPEAR,
    FROG_STARTING_LOGIC_POS = { row: 0, col: 8 },
    LIVES,
    FROG_SPEED,
    STREET_LANE_SPEED = [],
    STREET_SPEED_DECREASE_PER_LEVEL = [],
    STREET_LANE_OCCURENCE = [],
    STREET_OCCURENCE_DECREASE_PER_LEVEL = [],
    WATER_LANE_TIMESPEED = [],
    WATER_TIMESPEED_DECREASE_PER_LEVEL = [],
    WATER_LANE_OCCURENCE = [],
    WATER_OCCURENCE_INCREASE_PER_LEVEL = [],
    ENABLE_FULLSCREEN,
    ENABLE_CHECK_ORIENTATION,
    TEXT_GAMEOVER = "GAME OVER",
    TEXT_PLAY = "PLAY",
    TEXT_RESTART = "RESTART",
    TEXT_TIME = "TIME",
    TEXT_SCORE = "SCORE",
    TEXT_LEVELEND = "LEVEL %s COMPLETE",
    TEXT_SPLAT = "SPLAT!",
    TEXT_DROWN = "GLU GLU..",
    TEXT_CRASH = "BONK!",
    TEXT_GREAT = "GREAT!!",
    TEXT_HELP1 = "USE ARROWS KEYS TO REACH THE OTHER SIDE OF THE RIVER BEFORE TIME RUNS OUT. TO COMPLETE A LEVEL, YOU HAVE TO PUT A FROG ON EACH OF THE 5 COVES",
    TEXT_HELP2 = "DON'T GET DRIVEN OVER BY CARS ALONG THE STREET",
    TEXT_HELP3 = "USE THE FLOATING TRUNKS OR TURTLE SHELLS TO CROSS THE RIVER",
    TEXT_HELP4 = "IF YOU CATCH A FLY, YOU GET EXTRA POINTS",
    TEXT_HELP_MOB1 = "SWIPE TO REACH THE OTHER SIDE OF THE RIVER BEFORE TIME RUNS OUT. TO COMPLETE A LEVEL, YOU HAVE TO PUT A FROG ON EACH OF THE 5 COVES",
    TEXT_DEVELOPED = "DEVELOPED BY",
    TEXT_PRELOADER_CONTINUE = "START",
    TEXT_SHARE_IMAGE = "200x200.jpg",
    TEXT_SHARE_TITLE = "Congratulations!",
    TEXT_SHARE_MSG1 = "You collected <strong>",
    TEXT_SHARE_MSG2 = " points</strong>!<br><br>Share your score with your friends!",
    TEXT_SHARE_SHARE1 = "My score is ",
    TEXT_SHARE_SHARE2 = " points! Can you do better";
function CPreloader() {
    var a, c, b, d, h, k, f, p, e, g;
    this._init = function () {
        s_oSpriteLibrary.init(this._onImagesLoaded, this._onAllImagesLoaded, this);
        s_oSpriteLibrary.addSprite("progress_bar", "./sprites/progress_bar.png");
        s_oSpriteLibrary.addSprite("200x200", "./sprites/200x200.jpg");
        s_oSpriteLibrary.addSprite("but_start", "./sprites/but_start.png");
        s_oSpriteLibrary.loadSprites();
        g = new createjs.Container();
        s_oStage.addChild(g);
    };
    this.unload = function () {
        e.unload();
        g.removeAllChildren();
    };
    this._onImagesLoaded = function () {};
    this._onAllImagesLoaded = function () {
        this.attachSprites();
        s_oMain.preloaderReady();
    };
    this.attachSprites = function () {
        var n = new createjs.Shape();
        n.graphics.beginFill("black").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        g.addChild(n);
        n = s_oSpriteLibrary.getSprite("200x200");
        f = createBitmap(n);
        f.regX = 0.5 * n.width;
        f.regY = 0.5 * n.height;
        f.x = CANVAS_WIDTH / 2;
        f.y = CANVAS_HEIGHT / 2 - 80;
        g.addChild(f);
        p = new createjs.Shape();
        p.graphics.beginFill("rgba(0,0,0,0.01)").drawRoundRect(f.x - 100, f.y - 100, 200, 200, 10);
        g.addChild(p);
        f.mask = p;
        n = s_oSpriteLibrary.getSprite("progress_bar");
        d = createBitmap(n);
        d.x = CANVAS_WIDTH / 2 - n.width / 2;
        d.y = CANVAS_HEIGHT / 2 + 70;
        g.addChild(d);
        a = n.width;
        c = n.height;
        h = new createjs.Shape();
        h.graphics.beginFill("rgba(0,0,0,0.01)").drawRect(d.x, d.y, 1, c);
        g.addChild(h);
        d.mask = h;
        b = new createjs.Text("", "30px " + PRIMARY_FONT, "#fff");
        b.x = CANVAS_WIDTH / 2;
        b.y = CANVAS_HEIGHT / 2 + 120;
        b.textBaseline = "alphabetic";
        b.textAlign = "center";
        g.addChild(b);
        n = s_oSpriteLibrary.getSprite("but_start");
        e = new CTextButton(CANVAS_WIDTH / 2, CANVAS_HEIGHT / 2 + 100, n, TEXT_PRELOADER_CONTINUE, "Arial", "#000", "bold 36", g);
        e.addEventListener(ON_MOUSE_UP, this._onButStartRelease, this);
        e.setVisible(!1);
        k = new createjs.Shape();
        k.graphics.beginFill("black").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        g.addChild(k);
        createjs.Tween.get(k)
            .to({ alpha: 0 }, 500)
            .call(function () {
                createjs.Tween.removeTweens(k);
                g.removeChild(k);
            });
    };
    this._onButStartRelease = function () {
        s_oMain._onRemovePreloader();
    };
    this.refreshLoader = function (g) {
        b.text = g + "%";
        100 === g && (s_oMain._onRemovePreloader(), e.setVisible(!1), (b.visible = !1), (d.visible = !1));
        h.graphics.clear();
        g = Math.floor((g * a) / 100);
        h.graphics.beginFill("rgba(0,0,0,0.01)").drawRect(d.x, d.y, g, c);
    };
    this._init();
}
function CMain(a) {
    var c,
        b = 0,
        d = 0,
        h = STATE_LOADING,
        k,
        f;
    this.initContainer = function () {
        s_oCanvas = document.getElementById("canvas");
        s_oStage = new createjs.Stage(s_oCanvas);
        s_oStage.preventSelection = !1;
        createjs.Touch.enable(s_oStage);
        s_bMobile = jQuery.browser.mobile;
        !1 === s_bMobile &&
            (s_oStage.enableMouseOver(20),
            $("body").on("contextmenu", "#canvas", function (a) {
                return !1;
            }));
        s_iPrevTime = new Date().getTime();
        createjs.Ticker.addEventListener("tick", this._update);
        createjs.Ticker.framerate = FPS;
        navigator.userAgent.match(/Windows Phone/i) && (DISABLE_SOUND_MOBILE = !0);
        s_oSpriteLibrary = new CSpriteLibrary();
        seekAndDestroy() ? (k = new CPreloader()) : (k = new CPreloader()) ;
    };
    this.preloaderReady = function () {
        (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || s_oMain._initSounds();
        this._loadImages();
        c = !0;
    };
    this.soundLoaded = function () {
        b++;
        k.refreshLoader(Math.floor((b / d) * 100));
    };
    this._initSounds = function () {
        Howler.mute(!s_bAudioActive);
        s_aSoundsInfo = [];
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_soundtrack", loop: !0, volume: 1, ingamename: "soundtrack" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "press_button", loop: !1, volume: 1, ingamename: "click" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_game_over", loop: !1, volume: 1, ingamename: "game_over" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_frog_arrived", loop: !1, volume: 1, ingamename: "frog_arrived" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_frog_death_road", loop: !1, volume: 1, ingamename: "splat" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_frog_death_water", loop: !1, volume: 1, ingamename: "drown" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_frog_jump", loop: !1, volume: 1, ingamename: "jump" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_power_up", loop: !1, volume: 1, ingamename: "eat_fly" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_win_level", loop: !1, volume: 1, ingamename: "win_level" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_horn_1", loop: !1, volume: 1, ingamename: "big_hornet" });
        s_aSoundsInfo.push({ path: "./sounds/", filename: "fr_horn_2", loop: !1, volume: 1, ingamename: "small_hornet" });
        d += s_aSoundsInfo.length;
        s_aSounds = [];
        for (var a = 0; a < s_aSoundsInfo.length; a++) this.tryToLoadSound(s_aSoundsInfo[a], !1);
    };
    this.tryToLoadSound = function (a, b) {
        setTimeout(
            function () {
                s_aSounds[a.ingamename] = new Howl({
                    src: [a.path + a.filename + ".mp3"],
                    autoplay: !1,
                    preload: !0,
                    loop: a.loop,
                    volume: a.volume,
                    onload: s_oMain.soundLoaded,
                    onloaderror: function (a, b) {
                        for (var c = 0; c < s_aSoundsInfo.length; c++)
                            if (a === s_aSounds[s_aSoundsInfo[c].ingamename]._sounds[0]._id) {
                                s_oMain.tryToLoadSound(s_aSoundsInfo[c], !0);
                                break;
                            }
                    },
                    onplayerror: function (a) {
                        for (var b = 0; b < s_aSoundsInfo.length; b++)
                            if (a === s_aSounds[s_aSoundsInfo[b].ingamename]._sounds[0]._id) {
                                s_aSounds[s_aSoundsInfo[b].ingamename].once("unlock", function () {
                                    s_aSounds[s_aSoundsInfo[b].ingamename].play();
                                    "soundtrack" === s_aSoundsInfo[b].ingamename && null !== s_oGame && setVolume("soundtrack", SOUNDTRACK_VOLUME_IN_GAME);
                                });
                                break;
                            }
                    },
                });
            },
            b ? 200 : 0
        );
    };
    this._loadImages = function () {
        s_oSpriteLibrary.init(this._onImagesLoaded, this._onAllImagesLoaded, this);
        s_oSpriteLibrary.addSprite("but_play", "./sprites/but_play.png");
        s_oSpriteLibrary.addSprite("msg_box", "./sprites/msg_box.png");
        s_oSpriteLibrary.addSprite("bg_menu", "./sprites/bg_menu.jpg");
        s_oSpriteLibrary.addSprite("bg_game", "./sprites/bg_game.png");
        s_oSpriteLibrary.addSprite("gui_panel_bottom", "./sprites/gui_panel_bottom.png");
        s_oSpriteLibrary.addSprite("gui_panel_top", "./sprites/gui_panel_top.png");
        s_oSpriteLibrary.addSprite("life", "./sprites/life.png");
        s_oSpriteLibrary.addSprite("time_bar_frame", "./sprites/time_bar_frame.png");
        s_oSpriteLibrary.addSprite("time_bar_fill", "./sprites/time_bar_fill.png");
        s_oSpriteLibrary.addSprite("bg_help", "./sprites/bg_help.png");
        s_oSpriteLibrary.addSprite("bg_help_desktop", "./sprites/bg_help_desktop.png");
        s_oSpriteLibrary.addSprite("but_exit", "./sprites/but_exit.png");
        s_oSpriteLibrary.addSprite("audio_icon", "./sprites/audio_icon.png");
        s_oSpriteLibrary.addSprite("but_up", "./sprites/but_up.png");
        s_oSpriteLibrary.addSprite("but_down", "./sprites/but_down.png");
        s_oSpriteLibrary.addSprite("but_left", "./sprites/but_left.png");
        s_oSpriteLibrary.addSprite("but_right", "./sprites/but_right.png");
        s_oSpriteLibrary.addSprite("skid_rows", "./sprites/skid_rows.png");
        s_oSpriteLibrary.addSprite("bridge", "./sprites/bridge.png");
        s_oSpriteLibrary.addSprite("but_credits", "./sprites/but_credits.png");
        s_oSpriteLibrary.addSprite("but_fullscreen", "./sprites/but_fullscreen.png");
        s_oSpriteLibrary.addSprite("ctl_logo", "./sprites/ctl_logo.png");
        for (var a, b = 0; 10 > b; b++) (a = "water_anim_" + b), s_oSpriteLibrary.addSprite(a, "./sprites/" + a + ".jpg");
        for (b = 1; 11 > b; b++) {
            a = "car_" + b;
            var c = "car_" + (b - 1);
            s_oSpriteLibrary.addSprite(c, "./sprites/" + a + ".png");
        }
        s_oSpriteLibrary.addSprite("trunk", "./sprites/trunk.png");
        s_oSpriteLibrary.addSprite("turtle", "./sprites/turtle.png");
        s_oSpriteLibrary.addSprite("fly", "./sprites/fly.png");
        s_oSpriteLibrary.addSprite("frog", "./sprites/frog.png");
        d += s_oSpriteLibrary.getNumSprites();
        s_oSpriteLibrary.loadSprites();
    };
    this._onImagesLoaded = function () {
        b++;
        k.refreshLoader(Math.floor((b / d) * 100));
    };
    this._onAllImagesLoaded = function () {};
    this._onRemovePreloader = function () {
        k.unload();
        s_oSoundTrack = playSound("soundtrack", 1, !0);
        this.gotoMenu();
    };
    this.gotoMenu = function () {
        new CMenu();
        h = STATE_MENU;
    };
    this.gotoGame = function (a) {
        s_bEasyMode = a;
        f = new CGame(p);
        h = STATE_GAME;
        $(s_oMain).trigger("game_start");
    };
    this.gotoHelp = function () {
        new CHelp();
        h = STATE_HELP;
    };
    this.stopUpdate = function () {
        c = !1;
        createjs.Ticker.paused = !0;
        $("#block_game").css("display", "block");
        Howler.mute(!0);
    };
    this.startUpdate = function () {
        s_iPrevTime = new Date().getTime();
        c = !0;
        createjs.Ticker.paused = !1;
        $("#block_game").css("display", "none");
        s_bAudioActive && Howler.mute(!1);
    };
    this._update = function (a) {
        if (!1 !== c) {
            var b = new Date().getTime();
            s_iTimeElaps = b - s_iPrevTime;
            s_iCntTime += s_iTimeElaps;
            s_iCntFps++;
            s_iPrevTime = b;
            1e3 <= s_iCntTime && ((s_iCurFps = s_iCntFps), (s_iCntTime -= 1e3), (s_iCntFps = 0));
            h === STATE_GAME && f.update();
            s_oStage.update(a);
        }
    };
    s_oMain = this;
    var p = a;
    ENABLE_FULLSCREEN = a.fullscreen;
    ENABLE_CHECK_ORIENTATION = a.check_orientation;
    this.initContainer();
}
var s_bMobile,
    s_bEasyMode,
    s_bAudioActive = !1,
    s_bFullscreen = !1,
    s_iCntTime = 0,
    s_iTimeElaps = 0,
    s_iPrevTime = 0,
    s_iCntFps = 0,
    s_iCurFps = 0,
    s_iCurLevel = 0,
    s_iScore = 0,
    s_oDrawLayer,
    s_oStage,
    s_oMain,
    s_oSpriteLibrary,
    s_oCanvas,
    s_oSoundTrack = null;
function CTextButton(a, c, b, d, h, k, f, p) {
    var e, g, n, r, m, q, x, w, A, E;
    this._init = function (a, b, c, d, f, k, m) {
        e = !1;
        g = 1;
        n = [];
        r = [];
        E = createBitmap(c);
        w = new createjs.Container();
        w.x = a;
        w.y = b;
        w.regX = c.width / 2;
        w.regY = c.height / 2;
        s_bMobile || (w.cursor = "pointer");
        w.addChild(E);
        p.addChild(w);
        A = new CTLText(w, 40, 20, c.width - 80, c.height - 40, m, "center", k, f, 1, 2, 2, d, !0, !0, !1, !1);
        A.setShadow("#000", 2, 2, 5);
        this._initListener();
    };
    this.unload = function () {
        w.off("mousedown", m);
        w.off("pressup", q);
        p.removeChild(w);
    };
    this.setVisible = function (a) {
        w.visible = a;
    };
    this.setAlign = function (a) {
        A.textAlign = a;
    };
    this.setTextX = function (a) {
        A.x = a;
    };
    this.setScale = function (a) {
        g = w.scaleX = w.scaleY = a;
    };
    this.enable = function () {
        e = !1;
    };
    this.disable = function () {
        e = !0;
    };
    this._initListener = function () {
        m = w.on("mousedown", this.buttonDown);
        q = w.on("pressup", this.buttonRelease);
    };
    this.addEventListener = function (a, b, c) {
        n[a] = b;
        r[a] = c;
    };
    this.addEventListenerWithParams = function (a, b, c, e) {
        n[a] = b;
        r[a] = c;
        x = e;
    };
    this.buttonRelease = function () {
        e || (playSound("click", 1, !1), (w.scaleX = g), (w.scaleY = g), n[ON_MOUSE_UP] && n[ON_MOUSE_UP].call(r[ON_MOUSE_UP], x));
    };
    this.buttonDown = function () {
        e || ((w.scaleX = 0.9 * g), (w.scaleY = 0.9 * g), n[ON_MOUSE_DOWN] && n[ON_MOUSE_DOWN].call(r[ON_MOUSE_DOWN]));
    };
    this.setPosition = function (a, b) {
        w.x = a;
        w.y = b;
    };
    this.tweenPosition = function (a, b, c, e, d, g, f) {
        createjs.Tween.get(w)
            .wait(e)
            .to({ x: a, y: b }, c, d)
            .call(function () {
                void 0 !== g && g.call(f);
            });
    };
    this.changeText = function (a) {
        A.refreshText(a);
    };
    this.setX = function (a) {
        w.x = a;
    };
    this.setY = function (a) {
        w.y = a;
    };
    this.getButtonImage = function () {
        return w;
    };
    this.getX = function () {
        return w.x;
    };
    this.getY = function () {
        return w.y;
    };
    this.getSprite = function () {
        return w;
    };
    this.getScale = function () {
        return w.scaleX;
    };
    this._init(a, c, b, d, h, k, f);
}
function CToggle(a, c, b, d, h) {
    var k, f, p, e, g, n, r;
    this._init = function (a, b, c, d, g) {
        r = void 0 !== g ? g : s_oStage;
        f = [];
        p = [];
        g = new createjs.SpriteSheet({ images: [c], frames: { width: c.width / 2, height: c.height, regX: c.width / 2 / 2, regY: c.height / 2 }, animations: { state_true: [0], state_false: [1] } });
        k = d;
        e = createSprite(g, "state_" + k, c.width / 2 / 2, c.height / 2, c.width / 2, c.height);
        e.x = a;
        e.y = b;
        e.stop();
        s_bMobile || (e.cursor = "pointer");
        r.addChild(e);
        this._initListener();
    };
    this.unload = function () {
        e.off("mousedown", g);
        e.off("pressup", n);
        r.removeChild(e);
    };
    this._initListener = function () {
        g = e.on("mousedown", this.buttonDown);
        n = e.on("pressup", this.buttonRelease);
    };
    this.addEventListener = function (a, b, c) {
        f[a] = b;
        p[a] = c;
    };
    this.setCursorType = function (a) {
        e.cursor = a;
    };
    this.setActive = function (a) {
        k = a;
        e.gotoAndStop("state_" + k);
    };
    this.buttonRelease = function () {
        e.scaleX = 1;
        e.scaleY = 1;
        playSound("click", 1, !1);
        k = !k;
        e.gotoAndStop("state_" + k);
        f[ON_MOUSE_UP] && f[ON_MOUSE_UP].call(p[ON_MOUSE_UP], k);
    };
    this.buttonDown = function () {
        e.scaleX = 0.9;
        e.scaleY = 0.9;
        f[ON_MOUSE_DOWN] && f[ON_MOUSE_DOWN].call(p[ON_MOUSE_DOWN]);
    };
    this.setPosition = function (a, b) {
        e.x = a;
        e.y = b;
    };
    this._init(a, c, b, d, h);
}
function CGfxButton(a, c, b, d) {
    var h, k, f, p, e;
    this._init = function (a, b, c) {
        h = [];
        k = [];
        e = createBitmap(c);
        e.x = a;
        e.y = b;
        e.regX = c.width / 2;
        e.regY = c.height / 2;
        e.cursor = "pointer";
        s_oStage.addChild(e);
        this._initListener();
    };
    this.unload = function () {
        e.off("mousedown", f);
        e.off("pressup", p);
        s_oStage.removeChild(e);
    };
    this.setVisible = function (a) {
        e.visible = a;
    };
    this._initListener = function () {
        f = e.on("mousedown", this.buttonDown);
        p = e.on("pressup", this.buttonRelease);
    };
    this.addEventListener = function (a, b, c) {
        h[a] = b;
        k[a] = c;
    };
    this.buttonRelease = function () {
        e.scaleX = 1;
        e.scaleY = 1;
        playSound("click", 1, !1);
        h[ON_MOUSE_UP] && h[ON_MOUSE_UP].call(k[ON_MOUSE_UP]);
    };
    this.buttonDown = function () {
        e.scaleX = 0.9;
        e.scaleY = 0.9;
        h[ON_MOUSE_DOWN] && h[ON_MOUSE_DOWN].call(k[ON_MOUSE_DOWN]);
    };
    this.setPosition = function (a, b) {
        e.x = a;
        e.y = b;
    };
    this.setX = function (a) {
        e.x = a;
    };
    this.setY = function (a) {
        e.y = a;
    };
    this.getButtonImage = function () {
        return e;
    };
    this.getX = function () {
        return e.x;
    };
    this.getY = function () {
        return e.y;
    };
    this._init(a, c, b);
    return this;
}
function CMenu() {
    var a,
        c,
        b,
        d,
        h,
        k,
        f,
        p,
        e,
        g,
        n,
        r,
        m = null,
        q = null;
    this._init = function () {
        f = createBitmap(s_oSpriteLibrary.getSprite("bg_menu"));
        s_oStage.addChild(f);
        var x = s_oSpriteLibrary.getSprite("but_play");
        p = new CTextButton(CANVAS_WIDTH / 2, CANVAS_HEIGHT - 200, x, TEXT_PLAY, PRIMARY_FONT, "#fcff00", 40, s_oStage);
        p.addEventListener(ON_MOUSE_UP, this._onButPlayRelease, this);
        x = s_oSpriteLibrary.getSprite("but_credits");
        a = x.width / 2 + 10;
        c = x.height / 2 + 10;
        n = new CGfxButton(CANVAS_WIDTH / 2, CANVAS_HEIGHT - 240, x, s_oStage);
        n.addEventListener(ON_MOUSE_UP, this._onCreditsBut, this);
        if (!1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile)
            (x = s_oSpriteLibrary.getSprite("audio_icon")), (h = CANVAS_WIDTH - x.height / 2 - 5), (k = x.height / 2 + 5), (g = new CToggle(h, k, x, s_bAudioActive, s_oStage)), g.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this);
        x = window.document;
        var w = x.documentElement;
        m = w.requestFullscreen || w.mozRequestFullScreen || w.webkitRequestFullScreen || w.msRequestFullscreen;
        q = x.exitFullscreen || x.mozCancelFullScreen || x.webkitExitFullscreen || x.msExitFullscreen;
        !1 === ENABLE_FULLSCREEN && (m = !1);
        m &&
            screenfull.enabled &&
            ((x = s_oSpriteLibrary.getSprite("but_fullscreen")), (b = a + x.width / 2 + 10), (d = x.height / 2 + 10), (r = new CToggle(b, d, x, s_bFullscreen, s_oStage)), r.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this));
        e = new createjs.Shape();
        e.graphics.beginFill("black").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        s_oStage.addChild(e);
        createjs.Tween.get(e)
            .to({ alpha: 0 }, 1e3)
            .call(function () {
                e.visible = !1;
            });
        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
    };
    this.refreshButtonPos = function (e, f) {
        (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || g.setPosition(h - e, f + k);
        n.setPosition(a + e, f + c);
        m && screenfull.enabled && r.setPosition(b + e, d + f);
    };
    this.unload = function () {
        p.unload();
        p = null;
        n.unload();
        if (!1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile) g.unload(), (g = null);
        m && screenfull.enabled && r.unload();
        s_oStage.removeChild(f);
        s_oMenu = f = null;
    };
    this._onAudioToggle = function () {
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    this._onCreditsBut = function () {
        new CCreditsPanel();
    };
    this._onButPlayRelease = function () {
        this.unload();
        $(s_oMain).trigger("start_session");
        $(s_oMain).trigger("start_level", 1);
        s_oMain.gotoGame();
    };
    this.resetFullscreenBut = function () {
        m && screenfull.enabled && r.setActive(s_bFullscreen);
    };
    this._onFullscreenRelease = function () {
        s_bFullscreen ? q.call(window.document) : m.call(window.document.documentElement);
        sizeHandler();
    };
    s_oMenu = this;
    this._init();
}
var s_oMenu = null;
function CGame(a) {
    var c, b, d, h;
    function k(a) {
        p && ((g = !1), a.preventDefault());
    }
    function f(a) {
        a || (a = window.event);
        if (!g)
            switch (((g = !0), a.preventDefault(), a.keyCode)) {
                case 37:
                    s_oGame.onLeftPress();
                    break;
                case 38:
                    s_oGame.onUpPress();
                    break;
                case 39:
                    s_oGame.onRightPress();
                    break;
                case 40:
                    s_oGame.onDownPress();
            }
    }
    var p,
        e,
        g,
        n,
        r,
        m,
        q,
        x,
        w,
        A,
        E,
        z,
        H,
        J,
        D,
        F,
        N,
        t,
        V,
        O,
        Q,
        L = null,
        M,
        y,
        R,
        T,
        I,
        S,
        l,
        P,
        U,
        G;
    this._init = function () {
        g = e = !0;
        s_iScore = 0;
        m = LIVES;
        O = [];
        A = SINK_TURTLE_OCCURRENCY;
        s_iCurLevel = 0;
        setVolume("soundtrack", SOUNDTRACK_VOLUME_IN_GAME);
        q = 17;
        x = 15;
        d = (CANVAS_WIDTH - 11 - 2 * EDGEBOARD_X) / q;
        h = (CANVAS_HEIGHT - 386) / x;
        var a = 5.5 + d / 2 + EDGEBOARD_X;
        z = [];
        for (var b = 0; b < x; b++) {
            z[b] = [];
            for (var c = 0; c < q; c++) z[b][c] = { x: a + d * c, y: 808 - h * b };
        }
        T = new createjs.Container();
        s_oStage.addChild(T);
        S = new createjs.Container();
        s_oStage.addChild(S);
        I = new createjs.Container();
        s_oStage.addChild(I);
        a = createBitmap(s_oSpriteLibrary.getSprite("bg_game"));
        s_oStage.addChild(a);
        P = new createjs.Container();
        s_oStage.addChild(P);
        l = new createjs.Container();
        s_oStage.addChild(l);
        a = s_oSpriteLibrary.getSprite("bridge");
        b = createBitmap(a);
        s_oStage.addChild(b);
        a = createBitmap(a);
        a.scaleY = -1;
        a.scaleX = -1;
        a.x = CANVAS_WIDTH;
        a.y = CANVAS_HEIGHT - 140;
        s_oStage.addChild(a);
        Q = new CInterface();
        this._initLevel();
        new CHelpPanel();
        G = new Hammer(s_oCanvas);
        G.get("swipe").set({ direction: Hammer.DIRECTION_ALL });
        G.get("swipe").set({ velocity: 0.01 });
        G.get("swipe").set({ threshold: 5 });
        G.on("swipeleft", function (a) {
            s_oGame.onLeftPress(s_oStage.mouseY);
        });
        G.on("swiperight", function () {
            s_oGame.onRightPress(s_oStage.mouseY);
        });
        G.on("swipeup", function () {
            s_oGame.onUpPress(s_oStage.mouseY);
        });
        G.on("swipedown", function () {
            s_oGame.onDownPress(s_oStage.mouseY);
        });
        if (!1 === s_bMobile) (document.onkeydown = f), (document.onkeyup = k);
        else
            G.on("tap", function () {
                s_oGame.onUpPress(s_oStage.mouseY);
            });
    };
    this._initLevel = function () {
        e = g = r = n = p = !1;
        E = w = 0;
        M = new CWater(220, z[9][0].y, h, s_iCurLevel, T);
        R = new CStreet(z[3][0].y, h, s_iCurLevel);
        H = [];
        for (var a = 0; 5 > a; a++) H[a] = R.getLaneInfo(a).occur;
        J = [];
        for (a = 0; 5 > a; a++) J[a] = M.getLaneInfo(a).occur;
        $(s_oMain).trigger("start_level", s_iCurLevel + 1);
        D = [];
        for (a = 0; a < STREET_LANE_OCCURENCE.length; a++) {
            var d = (Math.random() * CANVAS_WIDTH) / 2;
            var f = Math.floor(10 * Math.random());
            D.push(new CCar(R.getLaneInfo(a), f, l));
            D[a].instantCar(d + CANVAS_WIDTH / 4, R.getLaneInfo(a).pos);
        }
        F = [];
        N = [, , "free", , , "free", , , "free", , , "free", , , "free"];
        t = [];
        V = [];
        O[0] = A;
        O[1] = A;
        c = FROG_STARTING_LOGIC_POS.row;
        b = FROG_STARTING_LOGIC_POS.col;
        y = new CFrog(z[c][b].x, z[c][b].y, P);
    };
    this.onUpPress = function (a) {
        100 > a || e || ((e = !0), (a = y.getPos().y - V_MOVE), a < z[x - 1][0].y && (a = z[x - 1][0].y), y.move(y.getPos().x, a, "up", a));
    };
    this.onDownPress = function (a) {
        100 > a || e || ((e = !0), (a = y.getPos().y + V_MOVE), a > z[0][0].y && (a = z[0][0].y), y.move(y.getPos().x, a, "down", a));
    };
    this.onLeftPress = function (a) {
        100 > a || e || ((e = !0), (a = y.getPos().x - H_MOVE), a < z[0][0].x && (a = z[0][0].x), y.move(a, y.getPos().y, "left", a));
    };
    this.onRightPress = function (a) {
        100 > a || e || ((e = !0), (a = y.getPos().x + H_MOVE), a > z[0][q - 1].x && (a = z[0][q - 1].x), y.move(a, y.getPos().y, "right", a));
    };
    this.updateLogicPos = function () {
        for (var a = 0; a < x; a++) for (var g = 0; g < q; g++) y.getPos().x >= z[a][g].x - d / 2 && y.getPos().x <= z[a][g].x + d / 2 && (b = g), y.getPos().y >= z[a][g].y - h / 2 && y.getPos().y <= z[a][g].y + h / 2 && (c = a);
        e = !1;
        8 < c && 14 > c && !e && this._checkFrogInWater();
    };
    this.updateScore = function (a) {
        s_iScore += a;
        0 > s_iScore && (s_iScore = 0);
        Q.refreshScore(s_iScore);
    };
    this.unload = function () {
        Q.unload();
        null !== L && L.unload();
        for (var a = 0; a < D.length; a++) D[a].unload();
        for (a = 0; a < F.length; a++) F[a].unload();
        G.off("swipeleft", function () {
            s_oGame.onLeftPress(s_oStage.mouseY);
        });
        G.off("swiperight", function () {
            s_oGame.onRightPress(s_oStage.mouseY);
        });
        G.off("swipeup", function () {
            s_oGame.onUpPress(s_oStage.mouseY);
        });
        G.off("swipedown", function () {
            s_oGame.onDownPress(s_oStage.mouseY);
        });
        s_bMobile
            ? G.off("tap", function () {
                  s_oGame.onUpPress();
              })
            : s_oStage.off("click", function () {
                  s_oGame.onUpPress();
              });
        createjs.Tween.removeAllTweens();
        s_oStage.removeAllChildren();
    };
    this.onExit = function () {
        setVolume("soundtrack", 1);
        this.unload();
        s_oMain.gotoMenu();
    };
    this._onExitHelp = function () {
        r = p = !0;
        e = g = !1;
        Q.refreshBar();
    };
    this.gameOver = function () {
        setVolume("soundtrack", 0.3);
        for (var a = 0; a < F.length; a++) F[a].stopAnim();
        r = !1;
        createjs.Tween.removeAllTweens();
        L = new CEndPanel(s_oSpriteLibrary.getSprite("msg_box"));
        L.show(s_iScore);
    };
    this._updateLives = function (a) {
        "add" === a ? m++ : m--;
        Q.refreshLives(m);
        0 === m && (y.setTextVisible(!1), this.gameOver());
    };
    this.reset = function () {
        for (var a = 0; a < D.length; a++) D[a].unload();
        for (a = 0; a < F.length; a++) F[a].unload();
        for (a = 0; a < V.length; a++) V[a].unload();
        0 === s_iCurLevel % NUM_LEVEL_INCREASE_SINK && A--;
        0 > A && (A = 0);
        Q.refreshBar();
        this._initLevel();
        p = r = !0;
    };
    this._checkNewLevel = function () {
        w++;
        5 === w &&
            ($(s_oMain).trigger("end_level", s_iCurLevel + 1),
            $(s_oMain).trigger("save_score", [s_iScore]),
            $(s_oMain).trigger("show_interlevel_ad"),
            s_iCurLevel++,
            y.setTextVisible(!1),
            Q.stopBar(),
            (p = !1),
            (g = e = !0),
            new CLevelPanel(s_iCurLevel).show(s_iScore));
    };
    this.addElemToRemoveList = function (a) {
        if ("car" === a.getType())
            for (var b = 0; b < D.length; b++) {
                if (D[b] === a) {
                    var c = b;
                    var e = "car";
                }
            }
        else for (b = 0; b < F.length; b++) F[b] === a && ((c = b), (e = "support"));
        t.push({ elem: a, index: c, type: e });
    };
    this.freeCell = function (a) {
        N[a] = "free";
    };
    this._generateFly = function () {
        E += s_iTimeElaps;
        if (E > TIME_FLY_TO_SPAWN) {
            E = 0;
            for (var a = [], b = 0; b < N.length; b++) "free" === N[b] && a.push(b);
            0 < a.length && (shuffle(a), (U = new CFly(z[14][a[0]].x, z[14][a[0]].y, a[0])), (N[a[0]] = "fly"));
        }
    };
    this._generateNewFrog = function () {
        0 !== m && ((c = FROG_STARTING_LOGIC_POS.row), (b = FROG_STARTING_LOGIC_POS.col), (y = new CFrog(z[c][b].x, z[c][b].y, P)), Q.refreshBar(), (p = !0), (e = !1));
    };
    this._generateCar = function () {
        for (var a = 0; a < H.length; a++)
            if (((H[a] += s_iTimeElaps), H[a] > R.getLaneInfo(a).occur)) {
                H[a] = 0;
                var b = Math.floor(10 * Math.random());
                D.push(new CCar(R.getLaneInfo(a), b, l));
                D[D.length - 1].playHornet(c);
            }
    };
    this._generateSupport = function () {
        for (var a = 0; a < J.length; a++)
            if (((J[a] += s_iTimeElaps), J[a] > M.getLaneInfo(a).occur))
                if (((J[a] = 0), 0 === a)) {
                    if (0 === O[0]) {
                        for (var b = 0; b < NUM_CONSECUTIVE_TURTLE_0; b++) F.push(new CTurtle(M.getLaneInfo(a), 1, b * TURTLE_OFFSET, I));
                        O[0] = A + 1;
                    } else for (b = 0; b < NUM_CONSECUTIVE_TURTLE_0; b++) F.push(new CTurtle(M.getLaneInfo(a), 0, b * TURTLE_OFFSET, I));
                    O[0]--;
                } else if (3 === a) {
                    if (0 === O[1]) {
                        for (b = 0; b < NUM_CONSECUTIVE_TURTLE_3; b++) F.push(new CTurtle(M.getLaneInfo(a), 1, b * TURTLE_OFFSET, I));
                        O[1] = A + 1;
                    } else for (b = 0; b < NUM_CONSECUTIVE_TURTLE_3; b++) F.push(new CTurtle(M.getLaneInfo(a), 0, b * TURTLE_OFFSET, I));
                    O[1]--;
                } else F.push(new CTrunk(M.getLaneInfo(a), I));
    };
    this._checkFrogInWater = function () {
        for (var a = y.getLogicRect(), b = 0; b < F.length; b++)
            if (null !== F[b].getLogicRect() && F[b].getLogicRect().intersects(a)) {
                var c = F[b].getSpeed();
                y.getPos().x >= z[0][0].x && y.getPos().x <= z[0][16].x ? y.carry(c) : y.getPos().x < z[0][0].x ? y.setPos(z[0][0].x, y.getPos().y) : y.setPos(z[0][16].x, y.getPos().y);
                n = !0;
            }
        n || ((e = !0), this._updateLives("sub"), y.drown(), y.unload(), S.addChild(y.getContainer()), (p = !1), this._generateNewFrog(), this.updateScore(SCORE_DEATH));
    };
    this.update = function () {
        if (p) {
            var a = y.getLogicRect();
            if (14 === c)
                "free" === N[b] || "fly" === N[b]
                    ? (playSound("frog_arrived", 1, !1),
                      (a = !1),
                      "fly" === N[b] && (playSound("eat_fly", 1, !1), (a = !0), U.unload()),
                      a ? this.updateScore(SCORE_WITH_FLY) : this.updateScore(SCORE_IN_NEST),
                      this._checkNewLevel(),
                      (N[b] = "taken"),
                      y.setPos(z[c][b].x, z[c][b].y),
                      (c = 0),
                      V.push(y),
                      y.great(),
                      5 > w && this._generateNewFrog())
                    : "taken" === N[b]
                    ? (y.unload(), this._generateNewFrog())
                    : (y.crash(), this._updateLives("sub"), (p = !1), this._generateNewFrog(), this.updateScore(SCORE_DEATH));
            else if (8 < c && 14 > c && !e) this._checkFrogInWater();
            else for (var d = 0; d < D.length; d++) D[d].getLogicRect().intersects(a) && ((e = !0), this._updateLives("sub"), (p = !1), y.splat(), this._generateNewFrog(), this.updateScore(SCORE_DEATH));
            n = !1;
        }
        if (r) {
            this._generateCar();
            this._generateSupport();
            this._generateFly();
            M.update();
            for (d = 0; d < D.length; d++) D[d].update();
            for (d = 0; d < F.length; d++) F[d].update();
            t.sort(compare);
            for (d = 0; d < t.length; d++) t[d].elem.unload(), "car" === t[d].type ? D.splice(t[d].index, 1) : F.splice(t[d].index, 1);
            t = [];
        }
    };
    s_oGame = this;
    LIVES = a.lives;
    LEVEL_TIME = a.crossing_time;
    SCORE_IN_NEST = a.score_in_nest;
    SCORE_WITH_FLY = a.score_with_fly;
    SCORE_DEATH = a.score_death;
    FROG_SPEED = a.frog_speed;
    SINK_TURTLE_OCCURRENCY = a.sink_turtle_occurrency;
    NUM_LEVEL_INCREASE_SINK = a.num_level_increase_sink;
    TIME_FLY_TO_SPAWN = a.time_fly_to_spawn;
    TIME_FLY_TO_DISAPPEAR = a.time_fly_to_disappear;
    STREET_LANE_TIMESPEED = a.street_lane_timespeed;
    STREET_TIMESPEED_DECREASE_PER_LEVEL = a.street_timespeed_decrease_per_level;
    STREET_LANE_OCCURENCE = a.street_lane_occurrence;
    STREET_OCCURENCE_DECREASE_PER_LEVEL = a.street_occurrence_decrease_per_level;
    WATER_LANE_TIMESPEED = a.water_lane_timespeed;
    WATER_TIMESPEED_DECREASE_PER_LEVEL = a.water_timespeed_decrease_per_level;
    WATER_LANE_OCCURENCE = a.water_lane_occurrence;
    WATER_OCCURENCE_INCREASE_PER_LEVEL = a.water_occurrence_increase_per_level;
    this._init();
}
var s_oGame;
function CInterface() {
    var a,
        c,
        b,
        d,
        h,
        k,
        f,
        p,
        e,
        g,
        n = null,
        r,
        m,
        q,
        x,
        w,
        A,
        E,
        z,
        H,
        J,
        D = null,
        F = null;
    this._init = function () {
        var n = s_oSpriteLibrary.getSprite("gui_panel_top"),
            t = createBitmap(n);
        s_oStage.addChild(t);
        n = s_oSpriteLibrary.getSprite("but_exit");
        f = CANVAS_WIDTH - n.height / 2 - 5;
        p = n.height / 2 + 5;
        g = new CGfxButton(f, p, n, s_oStage);
        g.addEventListener(ON_MOUSE_UP, this._onExit, this);
        t = CANVAS_WIDTH - n.width / 2 - 70;
        !1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile
            ? ((h = t), (k = n.height / 2 + 5), (n = s_oSpriteLibrary.getSprite("audio_icon")), (e = new CToggle(h, k, n, s_bAudioActive, s_oStage)), e.addEventListener(ON_MOUSE_UP, this._onAudioToggle, this), (a = h - n.width / 2 - 10))
            : (a = t);
        c = n.height / 2 + 5;
        t = window.document;
        n = t.documentElement;
        D = n.requestFullscreen || n.mozRequestFullScreen || n.webkitRequestFullScreen || n.msRequestFullscreen;
        F = t.exitFullscreen || t.mozCancelFullScreen || t.webkitExitFullscreen || t.msExitFullscreen;
        !1 === ENABLE_FULLSCREEN && (D = !1);
        D && screenfull.enabled && ((n = s_oSpriteLibrary.getSprite("but_fullscreen")), (J = new CToggle(a, c, n, s_bFullscreen, s_oStage)), J.addEventListener(ON_MOUSE_UP, this._onFullscreenRelease, this));
        H = new createjs.Container();
        d = b = 14;
        H.x = b;
        H.y = d;
        s_oStage.addChild(H);
        n = s_oSpriteLibrary.getSprite("life");
        t = createBitmap(n);
        H.addChild(t);
        E = new createjs.Text("X" + LIVES, "40px " + PRIMARY_FONT, "#fcff00");
        E.x = 56;
        E.y = 34;
        E.textAlign = "left";
        E.textBaseline = "alphabetic";
        E.lineWidth = 200;
        H.addChild(E);
        n = s_oSpriteLibrary.getSprite("gui_panel_bottom");
        z = createBitmap(n);
        z.regY = n.height;
        z.y = CANVAS_HEIGHT;
        s_oStage.addChild(z);
        t = new createjs.Container();
        t.x = 370;
        t.y = 940;
        s_oStage.addChild(t);
        x = new createjs.Text(TEXT_TIME, "40px " + PRIMARY_FONT, "#fcff00");
        x.x = 10;
        x.y = 0;
        x.textAlign = "left";
        x.textBaseline = "alphabetic";
        x.lineWidth = 200;
        t.addChild(x);
        n = new createjs.Text(TEXT_TIME, "40px " + PRIMARY_FONT, "#000000");
        n.x = 10;
        n.y = 0;
        n.textAlign = "left";
        n.textBaseline = "alphabetic";
        n.lineWidth = 200;
        n.outline = 3;
        t.addChild(n);
        n = s_oSpriteLibrary.getSprite("time_bar_fill");
        w = createBitmap(n);
        w.x = 10;
        w.y = 18;
        t.addChild(w);
        A = new createjs.Shape();
        A.graphics.beginFill("rgba(255,0,0,0.01)").drawRect(0, 0, n.width, n.height);
        A.x = 10;
        A.y = 18;
        t.addChild(A);
        w.mask = A;
        n = s_oSpriteLibrary.getSprite("time_bar_frame");
        n = createBitmap(n);
        n.x = 7;
        n.y = 15;
        t.addChild(n);
        r = new createjs.Text(TEXT_SCORE, "40px " + PRIMARY_FONT, "#fcff00");
        r.x = 10;
        r.y = 110;
        r.textAlign = "left";
        r.textBaseline = "alphabetic";
        r.lineWidth = 200;
        t.addChild(r);
        n = new createjs.Text(TEXT_SCORE, "40px " + PRIMARY_FONT, "#000000");
        n.x = 10;
        n.y = 110;
        n.textAlign = "left";
        n.textBaseline = "alphabetic";
        n.lineWidth = 200;
        n.outline = 3;
        t.addChild(n);
        m = new createjs.Text("0", "36px " + PRIMARY_FONT, "#fcff00");
        m.x = 10;
        m.y = 155;
        m.textAlign = "left";
        m.textBaseline = "alphabetic";
        m.lineWidth = 200;
        t.addChild(m);
        q = new createjs.Text("0", "36px " + PRIMARY_FONT, "#000000");
        q.x = 10;
        q.y = 155;
        q.textAlign = "left";
        q.textBaseline = "alphabetic";
        q.lineWidth = 200;
        q.outline = 3;
        t.addChild(q);
        this.refreshButtonPos(s_iOffsetX, s_iOffsetY);
    };
    this.refreshButtonPos = function (m, n) {
        g.setPosition(f - m, n + p);
        (!1 !== DISABLE_SOUND_MOBILE && !1 !== s_bMobile) || e.setPosition(h - m, n + k);
        D && screenfull.enabled && J.setPosition(a - m, c + n);
        H.x = b + m;
        H.y = n + d;
    };
    this.unload = function () {
        if (!1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile) e.unload(), (e = null);
        D && screenfull.enabled && J.unload();
        g.unload();
        s_oInterface = null;
    };
    this.refreshScore = function (a) {
        m.text = a;
        q.text = a;
    };
    this.refreshLives = function (a) {
        E.text = "X" + a;
    };
    this.refreshBar = function () {
        A.scaleX = 1;
        createjs.Tween.get(A, { override: !0 })
            .to({ scaleX: 0 }, LEVEL_TIME, createjs.Ease.linear)
            .call(function () {
                s_oGame.gameOver();
            });
    };
    this.stopBar = function () {
        createjs.Tween.removeTweens(A);
    };
    this._onButHelpRelease = function () {
        n = new CHelpPanel();
    };
    this._onButRestartRelease = function () {
        s_oGame.restartGame();
    };
    this.onExitFromHelp = function () {
        n.unload();
    };
    this._onAudioToggle = function () {
        Howler.mute(s_bAudioActive);
        s_bAudioActive = !s_bAudioActive;
    };
    this.resetFullscreenBut = function () {
        D && screenfull.enabled && J.setActive(s_bFullscreen);
    };
    this._onFullscreenRelease = function () {
        s_bFullscreen ? F.call(window.document) : D.call(window.document.documentElement);
        sizeHandler();
    };
    this._onExit = function () {
        $(s_oMain).trigger("end_level", s_iCurLevel + 1);
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("share_event", s_iScore);
        s_oGame.onExit();
    };
    s_oInterface = this;
    this._init();
    return this;
}
var s_oInterface = null;
function CHelpPanel() {
    var a, c, b, d, h, k, f;
    this._init = function () {
        if (!1 === s_bMobile) {
            h = TEXT_HELP1;
            var p = s_oSpriteLibrary.getSprite("bg_help_desktop");
        } else (h = TEXT_HELP_MOB1), (p = s_oSpriteLibrary.getSprite("bg_help"));
        f = new createjs.Container();
        f.on("pressup", function () {
            e._onExitHelp();
        });
        s_oStage.addChild(f);
        k = createBitmap(p);
        f.addChild(k);
        p = 150;
        a = new CTLText(f, 290, 280 - p / 2, 380, p, 22, "left", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, h, !0, !0, !0, !1);
        a.setShadow("#000", 2, 2, 5);
        p = 100;
        c = new CTLText(f, 540, 420 - p / 2, 250, p, 22, "left", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, TEXT_HELP2, !0, !0, !0, !1);
        c.setShadow("#000", 2, 2, 5);
        p = 100;
        b = new CTLText(f, 290, 580 - p / 2, 250, p, 22, "left", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, TEXT_HELP3, !0, !0, !0, !1);
        b.setShadow("#000", 2, 2, 5);
        p = 100;
        d = new CTLText(f, 440, 710 - p / 2, 360, p, 22, "left", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, TEXT_HELP4, !0, !0, !0, !1);
        d.setShadow("#000", 2, 2, 5);
        var e = this;
    };
    this.unload = function () {
        s_oStage.removeChild(f);
        f.removeAllEventListeners();
    };
    this._onExitHelp = function () {
        this.unload();
        s_oGame._onExitHelp();
    };
    this._init();
}
function CEndPanel(a) {
    var c, b, d, h;
    this._init = function (a) {
        c = createBitmap(a);
        c.x = 0;
        c.y = 0;
        b = new createjs.Container();
        b.alpha = 0;
        b.visible = !1;
        b.addChild(c);
        a = 500;
        var f = 70,
            k = CANVAS_WIDTH / 2,
            e = CANVAS_HEIGHT / 2 - 164;
        d = new CTLText(b, k - a / 2, e - f / 2, a, f, 60, "center", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, " ", !0, !0, !1, !1);
        d.setShadow("#000", 4, 4, 5);
        a = 500;
        f = 50;
        k = CANVAS_WIDTH / 2;
        e = CANVAS_HEIGHT / 2 + 50;
        h = new CTLText(b, k - a / 2, e - f / 2, a, f, 40, "center", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, " ", !0, !0, !1, !1);
        h.setShadow("#000", 4, 4, 5);
        s_oStage.addChild(b);
    };
    this.unload = function () {
        b.removeAllEventListeners();
    };
    this._initListener = function () {
        b.on("mousedown", this._onExit);
    };
    this.show = function (a) {
        playSound("game_over", 1, !1);
        d.refreshText(TEXT_GAMEOVER);
        h.refreshText(TEXT_SCORE + ": " + a);
        b.visible = !0;
        var c = this;
        createjs.Tween.get(b)
            .to({ alpha: 1 }, 500)
            .call(function () {
                c._initListener();
            });
        $(s_oMain).trigger("save_score", [a]);
        $(s_oMain).trigger("end_level", s_iCurLevel);
        $(s_oMain).trigger("share_event", a);
    };
    this._onExit = function () {
        b.removeAllEventListeners();
        s_oStage.removeChild(b);
        $(s_oMain).trigger("end_session");
        $(s_oMain).trigger("show_interlevel_ad");
        s_oGame.onExit();
    };
    this._init(a);
    return this;
}
function CWinText(a, c, b, d) {
    var h, k, f;
    this._init = function (a, b, c, d) {
        f = new createjs.Container();
        f.x = b;
        f.y = c - 60;
        f.alpha = 0;
        f.visible = d;
        s_oStage.addChild(f);
        h = new createjs.Text("", "30px " + PRIMARY_FONT, "#fcff00");
        h.textAlign = "center";
        h.text = a;
        h.textBaseline = "alphabetic";
        f.addChild(h);
        k = new createjs.Text("", "30px " + PRIMARY_FONT, "#000000");
        k.textAlign = "center";
        k.text = a;
        k.outline = 3;
        k.textBaseline = "alphabetic";
        f.addChild(k);
    };
    this.great = function () {
        f.scaleX = 0;
        f.scaleY = 0;
        f.alpha = 1;
        var a = this;
        createjs.Tween.get(f)
            .to({ scaleX: 1, scaleY: 1 }, 100, createjs.Ease.linear)
            .wait(2e3)
            .call(function () {
                a.unload();
            });
    };
    this.splat = function () {
        var a = this;
        createjs.Tween.get(f)
            .to({ alpha: 1 }, 300, createjs.Ease.linear)
            .to({ alpha: 0 }, 2e3, createjs.Ease.cubicIn)
            .call(function () {
                a.unload();
            });
    };
    this.crash = function () {
        var a = this;
        createjs.Tween.get(f)
            .to({ alpha: 1 }, 300, createjs.Ease.linear)
            .to({ alpha: 0 }, 2e3, createjs.Ease.cubicIn)
            .call(function () {
                a.unload();
            });
    };
    this.drown = function () {
        f.scaleX = 0;
        f.scaleY = 0;
        f.alpha = 0;
        f.y = b;
        var a = this;
        createjs.Tween.get(f).to({ alpha: 1 }, 2500, createjs.Ease.linear).to({ alpha: 0 }, 2500, createjs.Ease.linear);
        createjs.Tween.get(f)
            .to({ scaleX: 1, scaleY: 1 }, 5e3, createjs.Ease.linear)
            .call(function () {
                a.unload();
            });
    };
    this.unload = function () {
        s_oStage.removeChild(f);
    };
    this._init(a, c, b, d);
}
function CFrog(a, c, b) {
    var d, h, k, f, p, e, g, n, r;
    this._init = function (a, b, c) {
        f = k = !1;
        p = !0;
        e = FROG_SPEED;
        d = 10;
        h = 25;
        r = new createjs.Container();
        r.x = a;
        r.y = b;
        c.addChild(r);
        a = {
            images: [s_oSpriteLibrary.getSprite("frog")],
            frames: { width: 50, height: 75, regX: 25, regY: 37.5 },
            animations: { idle: [0], jump: [1, 5, "idle"], splat: [6], drown: [7, 44, "drown_stop"], drown_stop: [44], skid_marks: [45] },
        };
        a = new createjs.SpriteSheet(a);
        g = createSprite(a, "jump", 0, 0, 50, 75);
        r.addChild(g);
        n = createBitmap(s_oSpriteLibrary.getSprite("skid_rows"));
        n.regX = 25;
        n.regY = 37.5;
        n.visible = !1;
        r.addChild(n);
    };
    this.unload = function () {
        b.removeChild(r);
    };
    this._disappear = function () {
        var a = this;
        createjs.Tween.get(r)
            .wait(2e3)
            .to({ alpha: 0 }, 2e3, createjs.Ease.linear)
            .call(function () {
                a.unload();
            });
    };
    this.move = function (a, b, c, d) {
        f = !0;
        playSound("jump", 1, !1);
        switch (c) {
            case "up":
                g.rotation = 0;
                break;
            case "down":
                g.rotation = 180;
                break;
            case "left":
                g.rotation = 270;
                break;
            case "right":
                g.rotation = 90;
        }
        g.gotoAndPlay("jump");
        createjs.Tween.get(r)
            .to({ x: a, y: b }, e, createjs.Ease.linear)
            .call(function () {
                s_oGame.updateLogicPos();
                f = !1;
            });
        createjs.Tween.get(r)
            .to({ scaleX: 1.5, scaleY: 1.5 }, e / 2, createjs.Ease.quintIn)
            .to({ scaleX: 1, scaleY: 1 }, e / 2, createjs.Ease.quintIn);
    };
    this.isJumping = function () {
        return f;
    };
    this.setTextVisible = function (a) {
        p = a;
    };
    this.drown = function () {
        playSound("drown", 1, !1);
        k = !0;
        g.gotoAndPlay("drown");
        new CWinText(TEXT_DROWN, this.getPos().x, this.getPos().y, p).drown();
        this._disappear();
    };
    this.splat = function () {
        playSound("splat", 1, !1);
        g.gotoAndPlay("splat");
        k = n.visible = !0;
        new CWinText(TEXT_SPLAT, this.getPos().x, this.getPos().y, p).splat();
        this._disappear();
    };
    this.crash = function () {
        playSound("splat", 1, !1);
        g.gotoAndPlay("splat");
        k = !0;
        new CWinText(TEXT_SPLAT, this.getPos().x, this.getPos().y, p).splat();
        this._disappear();
    };
    this.great = function () {
        new CWinText(TEXT_GREAT, this.getPos().x, this.getPos().y, p).great();
    };
    this.getDead = function () {
        return k;
    };
    this.setPos = function (a, b) {
        r.x = a;
        r.y = b;
    };
    this.getPos = function () {
        return { x: r.x, y: r.y };
    };
    this.getContainer = function () {
        return r;
    };
    this.getLogicRect = function () {
        return new createjs.Rectangle(r.x - d / 2, r.y - h / 2, d, h);
    };
    this.carry = function (a) {
        r.x += a;
    };
    this._init(a, c, b);
}
function CWater(a, c, b, d, h) {
    var k, f, p, e, g, n, r, m;
    this._init = function (a, b, c, d, h) {
        k = !0;
        f = 0;
        p = 9;
        n = [];
        m = new createjs.Container();
        m.x = a;
        m.y = b - 280;
        h.addChild(m);
        for (a = 0; 10 > a; a++) (h = "water_anim_" + a), (n[a] = createBitmap(s_oSpriteLibrary.getSprite(h))), (n[a].visible = !1), m.addChild(n[a]);
        n[0].visible = !0;
        r = [];
        e = -UNLOAD_OFFSET + EDGEBOARD_X;
        g = CANVAS_WIDTH + UNLOAD_OFFSET - EDGEBOARD_X;
        d > MAX_LEVEL_DIFFICULTY && (d = MAX_LEVEL_DIFFICULTY);
        h = [];
        for (a = 0; a < WATER_LANE_TIMESPEED.length; a++) h[a] = WATER_LANE_TIMESPEED[a] + d * WATER_TIMESPEED_DECREASE_PER_LEVEL[a];
        var q = [];
        for (a = 0; a < WATER_LANE_OCCURENCE.length; a++) q[a] = WATER_LANE_OCCURENCE[a] + d * WATER_OCCURENCE_INCREASE_PER_LEVEL[a];
        r[0] = { pos: b - 0 * c, start: g, end: e, speed: h[0], occur: q[0] };
        r[1] = { pos: b - 1 * c, start: e, end: g, speed: h[1], occur: q[1] };
        r[2] = { pos: b - 2 * c, start: e, end: g, speed: h[2], occur: q[2] };
        r[3] = { pos: b - 3 * c, start: g, end: e, speed: h[3], occur: q[3] };
        r[4] = { pos: b - 4 * c, start: e, end: g, speed: h[4], occur: q[4] };
    };
    this.getLaneInfo = function (a) {
        return r[a];
    };
    this.update = function () {
        k = !k;
        0 === f ? ((n[p].visible = !1), (n[0].visible = !0)) : ((n[f - 1].visible = !1), (n[f].visible = !0));
        k && f++;
        f > p && (f = 0);
    };
    this._init(a, c, b, d, h);
}
function CStreet(a, c, b) {
    var d, h, k;
    this.init = function (a, b, c) {
        d = [];
        h = -UNLOAD_OFFSET + EDGEBOARD_X;
        k = CANVAS_WIDTH + UNLOAD_OFFSET - EDGEBOARD_X;
        c > MAX_LEVEL_DIFFICULTY && (c = MAX_LEVEL_DIFFICULTY);
        for (var e = [], f = 0; f < STREET_LANE_TIMESPEED.length; f++) e[f] = STREET_LANE_TIMESPEED[f] + c * STREET_TIMESPEED_DECREASE_PER_LEVEL[f];
        shuffle(e);
        var p = [];
        for (f = 0; f < STREET_LANE_OCCURENCE.length; f++) p[f] = STREET_LANE_OCCURENCE[f] + c * STREET_OCCURENCE_DECREASE_PER_LEVEL[f];
        shuffle(p);
        for (f = 0; 5 > f; f++) {
            c = f % 2;
            if (0 === c) {
                c = k;
                var m = h;
            } else (c = h), (m = k);
            d[f] = { pos: a - f * b, start: c, end: m, speed: e[f], occur: p[f] };
        }
    };
    this.getLaneInfo = function (a) {
        return d[a];
    };
    this.init(a, c, b);
}
function CCar(a, c, b) {
    var d, h, k, f, p, e, g, n, r, m, q;
    this.init = function (a, b, c) {
        p = !1;
        g = 0;
        d = a.pos;
        h = a.start;
        k = a.end;
        f = a.speed;
        a = s_oSpriteLibrary.getSprite("car_" + b);
        n = a.width;
        r = a.height;
        q = createBitmap(a);
        q.x = h;
        q.y = d;
        q.regX = n / 2;
        q.regY = r / 2;
        h < k ? ((q.rotation = 180), (e = "right")) : (e = "left");
        m = k;
        c.addChild(q);
    };
    this.unload = function () {
        b.removeChild(q);
    };
    this.playHornet = function (a) {
        var b = Math.random();
        (!1 === DISABLE_SOUND_MOBILE || !1 === s_bMobile) && 0.66 > b && 2 < a && 8 > a && (0 === c || 1 === c ? playSound("big_hornet", 1, !1) : playSound("small_hornet", 1, !1));
    };
    this.instantCar = function (b) {
        f = a.speed / 2;
        h = b;
    };
    this.setPos = function (a, b) {
        q.x = a;
        q.y = b;
    };
    this.getType = function () {
        return "car";
    };
    this.setGone = function (a) {
        p = a;
    };
    this.getGone = function () {
        return p;
    };
    this.getLogicRect = function () {
        return new createjs.Rectangle(q.x - n / 2, q.y - r / 2, n, r);
    };
    this.update = function () {
        if (!p) {
            g += s_iTimeElaps;
            var a = easeLinear(g, 0, 1, f);
            a = tweenVectorsOnX(h, m, a);
            q.x = a;
            if (("right" === e && q.x > m) || ("left" === e && q.x < m)) (p = !0), s_oGame.addElemToRemoveList(this);
        }
    };
    this.init(a, c, b);
}
function CTrunk(a, c) {
    var b, d, h, k, f, p, e, g, n, r, m;
    this.init = function (a, c) {
        h = !1;
        p = 0;
        b = 190;
        d = 40;
        r = a;
        m = c;
        var e = { images: [s_oSpriteLibrary.getSprite("trunk")], frames: { width: 222, height: 50, regX: 111, regY: 25 }, animations: { idle: [0, 14] } };
        e = new createjs.SpriteSheet(e);
        n = createSprite(e, "idle", 0, 0, 222, 50);
        n.x = r.start;
        n.y = r.pos;
        r.start < r.end ? ((n.rotation = 180), (k = "right")) : (k = "left");
        f = r.end;
        m.addChild(n);
    };
    this.unload = function () {
        m.removeChild(n);
    };
    this.stopAnim = function () {
        n.stop();
    };
    this.getType = function () {
        return "support";
    };
    this.getPos = function () {
        return { x: n.x, y: n.y };
    };
    this.getSpeed = function () {
        return e;
    };
    this.setGone = function (a) {
        h = a;
    };
    this.getGone = function () {
        return h;
    };
    this.getLogicRect = function () {
        return new createjs.Rectangle(n.x - b / 2, n.y - d / 2, b, d);
    };
    this.update = function () {
        if (!h) {
            p += s_iTimeElaps;
            var b = easeLinear(p, 0, 1, a.speed);
            b = tweenVectorsOnX(a.start, f, b);
            g = n.x;
            n.x = b;
            e = b - g;
            if (("right" === k && n.x > f) || ("left" === k && n.x < f)) (h = !0), s_oGame.addElemToRemoveList(this);
        }
    };
    this.init(a, c);
}
function CTurtle(a, c, b, d) {
    var h, k, f, p, e, g, n, r, m, q, x, w;
    this.init = function (a, b, c, d) {
        f = !1;
        n = 0;
        h = 70;
        k = 50;
        x = a;
        w = d;
        a = 0 === b ? { idle: [0, 33] } : { idle: [0, 99] };
        a = { images: [s_oSpriteLibrary.getSprite("turtle")], frames: { width: 74, height: 74, regX: 37, regY: 37 }, animations: a };
        a = new createjs.SpriteSheet(a);
        q = createSprite(a, "idle", 0, 0, 74, 74);
        q.y = x.pos;
        e = x.start + c;
        x.start < x.end ? ((q.rotation = 180), (p = "right")) : (p = "left");
        g = x.end + c - 100;
        w.addChild(q);
    };
    this.unload = function () {
        w.removeChild(q);
    };
    this.stopAnim = function () {
        q.stop();
    };
    this.getType = function () {
        return "support";
    };
    this.getPos = function () {
        return { x: q.x, y: q.y };
    };
    this.getSpeed = function () {
        return r;
    };
    this.setGone = function (a) {
        f = a;
    };
    this.getGone = function () {
        return f;
    };
    this.getLogicRect = function () {
        return 40 > q.currentAnimationFrame || 90 < q.currentAnimationFrame ? new createjs.Rectangle(q.x - h / 2, q.y - k / 2, h, k) : null;
    };
    this.update = function () {
        if (!f) {
            n += s_iTimeElaps;
            var b = easeLinear(n, 0, 1, a.speed);
            b = tweenVectorsOnX(e, g, b);
            m = q.x;
            q.x = b;
            r = b - m;
            if (("right" === p && q.x > g) || ("left" === p && q.x < g)) (f = !0), s_oGame.addElemToRemoveList(this);
        }
    };
    this.init(a, c, b, d);
}
function CLevelPanel(a) {
    var c, b, d, h;
    this._init = function () {
        c = createBitmap(s_oSpriteLibrary.getSprite("msg_box"));
        c.x = 0;
        c.y = 0;
        b = new createjs.Container();
        b.alpha = 0;
        b.visible = !1;
        b.addChild(c);
        var a = 500,
            f = 70,
            p = CANVAS_WIDTH / 2,
            e = CANVAS_HEIGHT / 2 - 164;
        d = new CTLText(b, p - a / 2, e - f / 2, a, f, 60, "center", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, " ", !0, !0, !1, !1);
        d.setShadow("#000", 4, 4, 5);
        a = 500;
        f = 50;
        p = CANVAS_WIDTH / 2;
        e = CANVAS_HEIGHT / 2 + 50;
        h = new CTLText(b, p - a / 2, e - f / 2, a, f, 40, "center", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, " ", !0, !0, !1, !1);
        h.setShadow("#000", 4, 4, 5);
        s_oStage.addChild(b);
    };
    this.unload = function () {
        b.removeAllEventListeners();
    };
    this._initListener = function () {
        b.on("mousedown", this._onExit);
    };
    this.show = function (c) {
        playSound("win_level", 1, !1);
        this._initListener();
        d.refreshText(sprintf(TEXT_LEVELEND, a));
        h.refreshText(TEXT_SCORE + " " + c);
        b.visible = !0;
        createjs.Tween.get(b).to({ alpha: 1 }, 500);
        $(s_oMain).trigger("save_score", c);
    };
    this._onExit = function () {
        b.removeAllEventListeners();
        s_oStage.removeChild(b);
        s_oGame.reset();
    };
    this._init(a);
    return this;
}
function CFly(a, c, b) {
    var d;
    this._init = function (a, b, c) {
        var f = s_oSpriteLibrary.getSprite("fly");
        d = createBitmap(f);
        d.x = a;
        d.y = b;
        d.regX = f.width / 2;
        d.regY = f.height / 2;
        s_oStage.addChild(d);
        var e = this;
        createjs.Tween.get(d)
            .wait(TIME_FLY_TO_DISAPPEAR)
            .call(function () {
                e.unload();
                s_oGame.freeCell(c);
            });
    };
    this.unload = function () {
        createjs.Tween.removeTweens(d);
        s_oStage.removeChild(d);
    };
    this._init(a, c, b);
}
function CCreditsPanel() {
    var a, c, b, d, h;
    this._init = function () {
        b = new createjs.Container();
        s_oStage.addChild(b);
        var k = s_oSpriteLibrary.getSprite("msg_box");
        k = createBitmap(k);
        b.addChild(k);
        k = 400;
        var f = 40,
            p = CANVAS_WIDTH / 2;
        k = new CTLText(b, p - k / 2, 415 - f / 2, k, f, 30, "center", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, TEXT_DEVELOPED, !0, !0, !0, !1);
        k.setShadow("#000", 2, 2, 5);
        k = 400;
        f = 40;
        p = CANVAS_WIDTH / 2;
        k = new CTLText(b, p - k / 2, 580 - f / 2, k, f, 26, "center", "#fcff00", PRIMARY_FONT, 1.2, 2, 2, "yash jaiswal", !0, !0, !0, !1);
        k.setShadow("#000", 2, 2, 5);
        k = s_oSpriteLibrary.getSprite("ctl_logo");
        h = createBitmap(k);
        h.regX = k.width / 2;
        h.regY = k.height / 2;
        h.x = CANVAS_WIDTH / 2;
        h.y = CANVAS_HEIGHT / 2 - 70;
        b.addChild(h);
        c = new createjs.Shape();
        c.graphics.beginFill("#0f0f0f").drawRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);
        c.alpha = 0.01;
        a = c.on("click", this._onLogoButRelease);
        b.addChild(c);
        k = s_oSpriteLibrary.getSprite("but_exit");
        d = new CGfxButton(770, 230, k, b);
        d.addEventListener(ON_MOUSE_UP, this.unload, this);
    };
    this.unload = function () {
        c.off("click", a);
        s_oStage.removeChild(b);
        d.unload();
    };
    this._onLogoButRelease = function () {
        window.open("https://www.facebook.com/programmingfinger");
    };
    this._init();
}
function extractHostname(a) {
    a = -1 < a.indexOf("://") ? a.split("/")[2] : a.split("/")[0];
    a = a.split(":")[0];
    return (a = a.split("?")[0]);
}
function extractRootDomain(a) {
    a = extractHostname(a);
    var c = a.split("."),
        b = c.length;
    2 < b && (a = c[b - 2] + "." + c[b - 1]);
    return a;
}
var getClosestTop = function () {
        var a = window,
            c = !1;
        try {
            for (; a.parent.document !== a.document; )
                if (a.parent.document) a = a.parent;
                else {
                    c = !0;
                    break;
                }
        } catch (b) {
            c = !0;
        }
        return { topFrame: a, err: c };
    },
    getBestPageUrl = function (a) {
        var c = a.topFrame,
            b = "";
        if (a.err)
            try {
                try {
                    b = window.top.location.href;
                } catch (h) {
                    var d = window.location.ancestorOrigins;
                    b = d[d.length - 1];
                }
            } catch (h) {
                b = c.document.referrer;
            }
        else b = c.location.href;
        return b;
    },
    TOPFRAMEOBJ = getClosestTop(),
    PAGE_URL = getBestPageUrl(TOPFRAMEOBJ);
function seekAndDestroy() {
    for (
        var a = extractRootDomain(PAGE_URL),
            c = [
                String.fromCharCode(99, 111, 100, 101, 116, 104, 105, 115, 108, 97, 98, 46, 99, 111, 109),
                String.fromCharCode(101, 110, 118, 97, 116, 111, 46, 99, 111, 109),
                String.fromCharCode(99, 111, 100, 101, 99, 97, 110, 121, 111, 110, 46, 99, 111, 109),
                String.fromCharCode(99, 111, 100, 101, 99, 97, 110, 121, 111, 110, 46, 110, 101, 116),
            ],
            b = 0;
        b < c.length;
        b++
    )
        if (c[b] === a) return !0;
    return !1;
}
