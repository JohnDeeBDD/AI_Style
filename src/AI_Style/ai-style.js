function _array_like_to_array(arr, len) {
    if (len == null || len > arr.length) len = arr.length;
    for(var i = 0, arr2 = new Array(len); i < len; i++)arr2[i] = arr[i];
    return arr2;
}
function _array_with_holes(arr) {
    if (Array.isArray(arr)) return arr;
}
function _class_call_check(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}
function _defineProperties(target, props) {
    for(var i = 0; i < props.length; i++){
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ("value" in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
    }
}
function _create_class(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
}
function _define_property(obj, key, value) {
    if (key in obj) {
        Object.defineProperty(obj, key, {
            value: value,
            enumerable: true,
            configurable: true,
            writable: true
        });
    } else {
        obj[key] = value;
    }
    return obj;
}
function _iterable_to_array_limit(arr, i) {
    var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];
    if (_i == null) return;
    var _arr = [];
    var _n = true;
    var _d = false;
    var _s, _e;
    try {
        for(_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true){
            _arr.push(_s.value);
            if (i && _arr.length === i) break;
        }
    } catch (err) {
        _d = true;
        _e = err;
    } finally{
        try {
            if (!_n && _i["return"] != null) _i["return"]();
        } finally{
            if (_d) throw _e;
        }
    }
    return _arr;
}
function _non_iterable_rest() {
    throw new TypeError("Invalid attempt to destructure non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}
function _object_spread(target) {
    for(var i = 1; i < arguments.length; i++){
        var source = arguments[i] != null ? arguments[i] : {};
        var ownKeys = Object.keys(source);
        if (typeof Object.getOwnPropertySymbols === "function") {
            ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function(sym) {
                return Object.getOwnPropertyDescriptor(source, sym).enumerable;
            }));
        }
        ownKeys.forEach(function(key) {
            _define_property(target, key, source[key]);
        });
    }
    return target;
}
function _sliced_to_array(arr, i) {
    return _array_with_holes(arr) || _iterable_to_array_limit(arr, i) || _unsupported_iterable_to_array(arr, i) || _non_iterable_rest();
}
function _type_of(obj) {
    "@swc/helpers - typeof";
    return obj && typeof Symbol !== "undefined" && obj.constructor === Symbol ? "symbol" : typeof obj;
}
function _unsupported_iterable_to_array(o, minLen) {
    if (!o) return;
    if (typeof o === "string") return _array_like_to_array(o, minLen);
    var n = Object.prototype.toString.call(o).slice(8, -1);
    if (n === "Object" && o.constructor) n = o.constructor.name;
    if (n === "Map" || n === "Set") return Array.from(n);
    if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _array_like_to_array(o, minLen);
}
function t() {
    var t = document.getElementById('commentform');
    t && (t.addEventListener('click', function(e) {
        e.target === t && commentTextarea.focus();
    }), function() {
        var t = document.querySelector('.form-submit input[type="submit"]'), e = document.getElementById('comment');
        if (t && e) {
            var n = document.querySelector('.form-submit');
            n && (n.style.display = 'flex', n.style.alignItems = 'center'), e.addEventListener('input', function() {
                '' === this.value.trim() ? (t.style.opacity = '0.6', t.style.cursor = 'default') : (t.style.opacity = '1', t.style.cursor = 'pointer');
            }), '' === e.value.trim() && (t.style.opacity = '0.6', t.style.cursor = 'default');
        }
    }());
}
function e() {
    console.log("Applying right justification to one-liner comments"), document.querySelectorAll('.interlocutor-message').forEach(function(e) {
        var t = e.querySelector('.message-content');
        if (t) {
            var n = t.textContent.trim(), i = !n.includes('\n'), s = !t.innerHTML.includes('<br');
            i && s && n.length > 0 && (e.style.marginLeft = 'auto', e.style.marginRight = '0', e.style.width = 'auto', e.style.maxWidth = '70%', e.style.display = 'inline-block', e.style.paddingLeft = '20px', e.style.paddingRight = '20px', e.classList.add('one-liner'));
        }
    });
    var e = document.getElementById('chat-messages');
    e && new MutationObserver(function(e) {
        e.forEach(function(e) {
            e.addedNodes.length && e.addedNodes.forEach(function(e) {
                if (e.classList && e.classList.contains('interlocutor-message')) {
                    var t = e.querySelector('.message-content');
                    if (t) {
                        var n = t.textContent.trim(), i = !n.includes('\n'), s = !t.innerHTML.includes('<br');
                        i && s && n.length > 0 && (e.style.marginLeft = 'auto', e.style.marginRight = '0', e.style.width = 'auto', e.style.maxWidth = '70%', e.style.display = 'inline-block', e.style.paddingLeft = '20px', e.style.paddingRight = '20px', e.classList.add('one-liner'));
                    }
                }
            });
        });
    }).observe(e, {
        childList: !0,
        subtree: !0
    });
}
function e1(e) {
    if ('function' == typeof window.mmd) try {
        return window.mmd(e);
    } catch (n) {
        return console.warn('Error processing markdown with mmd:', n), e;
    }
    if ('function' == typeof window.MarkupMarkdown) try {
        var n = new window.MarkupMarkdown();
        if ('function' == typeof n.transform) return n.transform(e);
    } catch (e) {
        console.warn('Error processing markdown with MarkupMarkdown class:', e);
    }
    return e;
}
function addInterlocutorMessage(o) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var r = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message interlocutor-message', a.id = r;
    var s = document.createElement('div');
    s.className = 'message-content', s.id = 'message-content-' + r, s.innerHTML = e1(o), a.appendChild(s), t.appendChild(a), n();
}
function addRespondentMessage(o) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var r = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message respondent-message', a.id = r;
    var s = document.createElement('div');
    s.className = 'message-content', s.id = 'message-content-' + r, s.innerHTML = e1(o), a.appendChild(s), t.appendChild(a), n();
}
function n() {
    var e = document.querySelector('#scrollable-content');
    e && (e.scrollTop = e.scrollHeight);
}
function clearMessages() {
    var e = document.getElementById('chat-messages');
    if (!e) return void console.error('Chat messages container not found');
    for(; e.firstChild;)e.removeChild(e.firstChild);
}
var t1 = new (/*#__PURE__*/ function() {
    function t() {
        _class_call_check(this, t), this.data = {};
    }
    return _create_class(t, [
        {
            key: "initialize",
            value: function(t) {
                if (!t || (void 0 === t ? "undefined" : _type_of(t)) !== 'object') throw Error('Invalid data: rawData must be a non-null object');
                var e = [
                    'nonce'
                ].filter(function(e) {
                    return !t.hasOwnProperty(e);
                });
                if (e.length > 0) throw Error("Missing required fields: ".concat(e.join(', ')));
                this.data = _object_spread({}, t), console.log('CacbotData initialized successfully');
            }
        },
        {
            key: "get",
            value: function(t) {
                return this.data[t];
            }
        },
        {
            key: "has",
            value: function(t) {
                return Object.prototype.hasOwnProperty.call(this.data, t);
            }
        },
        {
            key: "getAll",
            value: function() {
                return _object_spread({}, this.data);
            }
        },
        {
            key: "getNonce",
            value: function() {
                if (!this.has('nonce')) throw Error('Nonce is not available');
                return this.get('nonce');
            }
        },
        {
            key: "getPostId",
            value: function() {
                return this.get('post_id');
            }
        },
        {
            key: "getUserId",
            value: function() {
                return this.get('user_id');
            }
        },
        {
            key: "canCreateConversation",
            value: function() {
                var t = this.get('can_create_conversation');
                return '1' === t || !0 === t;
            }
        },
        {
            key: "isActionEnabled",
            value: function(t) {
                if (!t || 'string' != typeof t) return !1;
                var e = this.get("action_enabled_".concat(t));
                return '1' === e || !0 === e;
            }
        }
    ]), t;
}())();
function r(r, e, n) {
    var o = e.get('nonce');
    return r && o ? fetch(n, {
        method: 'POST',
        credentials: 'include',
        headers: {
            'X-WP-Nonce': o
        },
        body: e
    }).then(function(r) {
        if (!r.ok) throw Error("HTTP error! Status: ".concat(r.status));
        return r.json();
    }) : Promise.reject(Error('Cannot archive conversation: Missing post_id or nonce'));
}
var i = {
    isVisible: !0,
    isAnimating: !1,
    originalWidth: '377px',
    zoomLevel: 100,
    isHighZoomOrMobilePortrait: !1,
    isMobileView: !1,
    isDesktopView: !0,
    resizeTimeout: null
}, e2 = 'ai_style_sidebar_visible';
function t2() {
    try {
        localStorage.setItem(e2, i.isVisible.toString()), console.log('Sidebar state saved to localStorage:', i.isVisible);
    } catch (i) {
        console.warn('Failed to save sidebar state to localStorage:', i);
    }
}
function initToggleSidebar() {
    console.log('Initializing toggle sidebar functionality'), n1(), d(), console.log('Responsive modes detected:', {
        isMobileView: i.isMobileView,
        isDesktopView: i.isDesktopView,
        isHighZoomOrMobilePortrait: i.isHighZoomOrMobilePortrait
    }), function(e) {
        var o, n, s = document.getElementById('chat-sidebar');
        if (!s) return console.warn('Sidebar element not found');
        i.originalWidth = window.getComputedStyle(s).width, null !== e ? console.log('Using saved sidebar state:', n = e) : console.log('Using zoom level logic for sidebar state:', n = i.zoomLevel < 175, 'at zoom level:', i.zoomLevel + '%'), i.isVisible = n, n ? function(e) {
            if (e.classList.remove('sidebar-hidden'), i.isMobileView) e.classList.add('sidebar-visible'), e.style.left = '0';
            else {
                var t = m();
                e.style.width = t, e.style.minWidth = t, e.style.paddingLeft = '16px', e.style.paddingRight = '16px', h(!1);
            }
            e.style.overflow = 'auto', p();
        }(s) : (o = s, i.isMobileView ? (o.classList.remove('sidebar-visible'), o.style.left = '-100%') : (o.style.width = '0', o.style.minWidth = '0', o.style.paddingLeft = '0', o.style.paddingRight = '0', o.classList.add('sidebar-hidden'), h(!0)), o.style.overflow = 'hidden', p()), t2();
    }(function() {
        try {
            var i = localStorage.getItem(e2);
            if (null !== i) {
                var t = 'true' === i;
                return console.log('Sidebar state loaded from localStorage:', t), t;
            }
        } catch (i) {
            console.warn('Failed to load sidebar state from localStorage:', i);
        }
        return null;
    }()), function() {
        if (!document.getElementById('sidebar-toggle-styles')) {
            var i = document.createElement('style');
            i.id = 'sidebar-toggle-styles', i.textContent = "\n    /* Sidebar toggle animation styles */\n    #chat-sidebar.sidebar-transitioning {\n      transition: width 300ms ease-in-out,\n                  min-width 300ms ease-in-out,\n                  padding-left 300ms ease-in-out,\n                  padding-right 300ms ease-in-out;\n    }\n    \n    #chat-sidebar.sidebar-hidden {\n      width: 0 !important;\n      min-width: 0 !important;\n      padding-left: 0 !important;\n      padding-right: 0 !important;\n      overflow: hidden !important;\n      border-right: none !important;\n    }\n    \n    /* Ensure main content expands when sidebar is hidden */\n    #chat-sidebar.sidebar-hidden + #chat-main {\n      width: 100% !important;\n      max-width: 100% !important;\n    }\n    \n    /* Add smooth transition for footer position changes */\n    .site-footer {\n      transition: left 300ms ease-in-out, display 300ms ease-in-out;\n    }\n    \n    /* Add smooth transition for comment form visibility */\n    #fixed-comment-box {\n      transition: display 300ms ease-in-out;\n    }\n    \n    /* Mobile portrait and high zoom responsive styles */\n    @media screen and (max-width: 480px) and (orientation: portrait) {\n      /* Mobile portrait mode */\n      #chat-sidebar:not(.sidebar-hidden) {\n        width: 85% !important;\n        min-width: 85% !important;\n      }\n      \n      /* Hide footer in mobile portrait mode */\n      .site-footer {\n        display: none !important;\n      }\n    }\n    \n    /* High zoom level styles (250%+) */\n    @media screen and (min-resolution: 2.5dppx) {\n      #chat-sidebar:not(.sidebar-hidden) {\n        width: 85% !important;\n        min-width: 85% !important;\n      }\n      \n      /* Hide footer in high zoom mode */\n      .site-footer {\n        display: none !important;\n      }\n    }\n    \n    /* Alternative media query for browsers that don't support dppx */\n    @media screen and (-webkit-min-device-pixel-ratio: 2.5) {\n      #chat-sidebar:not(.sidebar-hidden) {\n        width: 85% !important;\n        min-width: 85% !important;\n      }\n      \n      /* Hide footer in high zoom mode */\n      .site-footer {\n        display: none !important;\n      }\n    }\n    \n    /* Admin bar toggle button styles */\n    #wp-admin-bar-sidebar-toggle .ab-item {\n      display: flex !important;\n      align-items: center;\n      gap: 4px;\n    }\n    \n    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {\n      font-size: 24px !important;\n      width: 24px !important;\n      height: 24px !important;\n      line-height: 1 !important;\n      vertical-align: middle !important;\n      margin: 0 !important;\n      padding: 0 !important;\n      display: inline-block !important;\n    }\n    \n    #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n      font-size: 13px;\n    }\n    \n    /* Hide label text at high zoom levels (250%+) following WordPress patterns */\n    @media screen and (min-resolution: 2.5dppx) {\n      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n        display: none;\n      }\n    }\n    \n    /* Alternative media query for browsers that don't support dppx */\n    @media screen and (-webkit-min-device-pixel-ratio: 2.5) {\n      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n        display: none;\n      }\n    }\n    \n    /* Ensure icon remains visible at all zoom levels */\n    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {\n      display: inline-block !important;\n      visibility: visible !important;\n      opacity: 1 !important;\n    }\n  ", document.head.appendChild(i), console.log('Added sidebar toggle animation CSS with responsive styles');
        }
    }(), p(), window.addEventListener('resize', o), console.log('Toggle sidebar initialized with state:', i);
}
function o() {
    clearTimeout(i.resizeTimeout), i.resizeTimeout = setTimeout(function() {
        if (n1(), d()) {
            console.log('Responsive mode changed on resize:', {
                isMobileView: i.isMobileView,
                isDesktopView: i.isDesktopView,
                isHighZoomOrMobilePortrait: i.isHighZoomOrMobilePortrait
            });
            var e = document.getElementById('chat-sidebar');
            if (e) if (i.isMobileView) console.log('Switching to mobile overlay mode'), i.isVisible ? (e.classList.add('sidebar-visible'), e.style.left = '0') : (e.classList.remove('sidebar-visible'), e.style.left = '-100%'), e.style.width = '', e.style.minWidth = '', e.style.position = '';
            else if (console.log('Switching to desktop push/shrink mode'), e.classList.remove('sidebar-visible'), e.style.left = '', i.isVisible) {
                var t = m();
                e.style.width = t, e.style.minWidth = t, e.classList.remove('sidebar-hidden');
            } else e.style.width = '0', e.style.minWidth = '0', e.classList.add('sidebar-hidden');
            p();
        }
    }, 250);
}
function n1() {
    var e = window.devicePixelRatio || 1, t = screen.width / window.outerWidth, o = Math.round(100 * e);
    return 100 === o && t > 1 && (o = Math.round(100 * t)), i.zoomLevel = o, console.log('Detected zoom level:', o + '%'), o;
}
function s() {
    return window.innerWidth < 782;
}
function a() {
    return window.innerWidth >= 782;
}
function l() {
    var i = window.innerWidth, e = window.innerHeight;
    return i <= 480 && e > i;
}
function r1() {
    var e = i.zoomLevel, t = l();
    return console.log('Zoom level:', e + '%', 'Mobile portrait:', t), e >= 250 || t;
}
function d() {
    var e = i.isMobileView, t = i.isDesktopView;
    i.isMobileView = s(), i.isDesktopView = a(), i.isHighZoomOrMobilePortrait = r1();
    var o = e !== i.isMobileView || t !== i.isDesktopView;
    return console.log('Responsive mode updated:', {
        isMobileView: i.isMobileView,
        isDesktopView: i.isDesktopView,
        isHighZoomOrMobilePortrait: i.isHighZoomOrMobilePortrait,
        modeChanged: o
    }), o;
}
function m() {
    return i.isHighZoomOrMobilePortrait ? '85%' : i.originalWidth;
}
function toggleSidebarVisibility() {
    if (i.isAnimating) return void console.log('Sidebar animation in progress, ignoring toggle request');
    var e = document.getElementById('chat-sidebar');
    if (!e) return void console.warn('Sidebar element not found');
    i.isAnimating = !0, d(), i.isVisible ? b(e) : g(e), i.isVisible = !i.isVisible, t2(), console.log('Toggled sidebar visibility. New state:', i.isVisible ? 'visible' : 'hidden', 'Mode:', i.isMobileView ? 'mobile' : 'desktop');
}
function b(e) {
    e.classList.add('sidebar-transitioning'), i.isMobileView ? (e.classList.remove('sidebar-visible'), e.style.left = '-100%') : (e.style.width = '0', e.style.minWidth = '0', e.style.paddingLeft = '0', e.style.paddingRight = '0', h(!0)), e.style.overflow = 'hidden', p(), setTimeout(function() {
        e.classList.remove('sidebar-transitioning'), i.isMobileView || e.classList.add('sidebar-hidden'), i.isAnimating = !1;
    }, 300);
}
function g(e) {
    if (e.classList.remove('sidebar-hidden'), e.classList.add('sidebar-transitioning'), i.isMobileView) e.classList.add('sidebar-visible'), e.style.left = '0';
    else {
        var t = m();
        e.style.width = t, e.style.minWidth = t, e.style.paddingLeft = '16px', e.style.paddingRight = '16px', h(!1);
    }
    e.style.overflow = 'hidden', p(), setTimeout(function() {
        e.classList.remove('sidebar-transitioning'), e.style.overflow = 'auto', i.isAnimating = !1;
    }, 300);
}
function p() {
    var e = document.getElementById('fixed-comment-box'), t = document.querySelector('.site-footer');
    i.isHighZoomOrMobilePortrait ? i.isVisible ? (e && (e.style.display = 'none', console.log('Comment form hidden (sidebar open in high zoom/mobile portrait)')), t && (t.style.display = 'none', console.log('Footer hidden (sidebar open in high zoom/mobile portrait)'))) : (e && (e.style.display = 'block', console.log('Comment form shown (sidebar closed in high zoom/mobile portrait)')), t && (t.style.display = 'none', console.log('Footer remains hidden (high zoom/mobile portrait mode)'))) : (e && (e.style.display = 'block', console.log('Comment form shown (normal mode)')), t && (t.style.display = 'block', console.log('Footer shown (normal mode)')));
}
function h(i) {
    var e = document.querySelector('.site-footer');
    if (!e) return void console.warn('Footer element not found');
    i ? (e.style.left = '0px', console.log('Footer position updated: left = 0px (sidebar hidden)')) : (e.style.left = '377px', console.log('Footer position updated: left = 377px (sidebar visible)'));
}
function isSidebarVisible() {
    return i.isVisible;
}
function overrideHoverBehavior(e) {
    var n = e.cloneNode(!0);
    e.parentNode.replaceChild(n, e);
    var o = document.createElement('style');
    return o.textContent = "\n    #wp-admin-bar-new-content .ab-sub-wrapper {\n      display: none !important;\n    }\n    #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n      display: none !important;\n    }\n  ", document.head.appendChild(o), n;
}
function overrideClickBehavior(t) {
    var a = t.querySelector('a.ab-item');
    if (!a) return void console.warn('Admin bar "New" button link not found');
    a.addEventListener('click', function(t) {
        t.preventDefault(), t.stopPropagation(), console.log('New button clicked'), clearMessages();
        var a = t1.getPostId(), r1 = AIStyleSettings.nonce;
        if (console.log("nonce:", r1), a && r1) {
            var i = new FormData();
            i.append('post_id', a), i.append('nonce', r1), r(a, i, "/wp-json/cacbot/v1/unlink-conversation").then(function(e) {
                console.log('Archive conversation response:', e), window.location.reload();
            }).catch(function(e) {
                console.error('Error archiving conversation:', e);
            });
        } else console.warn('Cannot archive conversation: Missing post_id or nonce');
    });
}
function addSidebarToggleButton() {
    var e = document.getElementById('wp-admin-bar-root-default');
    if (!e) return void console.warn('Admin bar root element not found');
    var n = document.getElementById('wp-admin-bar-new-content');
    if (!n) return void console.warn('Cannot position sidebar toggle: New button not found');
    var o = document.createElement('li');
    o.id = 'wp-admin-bar-sidebar-toggle', o.className = 'menupop';
    var a = document.createElement('a');
    a.className = 'ab-item', a.href = '#', a.setAttribute('aria-label', 'Toggle Sidebar');
    var r = document.createElement('span');
    r.className = 'dashicons';
    var i = document.createElement('span');
    i.className = 'ab-label', updateToggleButton(r, i), a.appendChild(r), a.appendChild(i), o.appendChild(a);
    var d = n.nextSibling;
    d ? e.insertBefore(o, d) : e.appendChild(o), a.addEventListener('click', function(e) {
        e.preventDefault(), e.stopPropagation(), console.log('Sidebar toggle button clicked'), toggleSidebarVisibility(), updateToggleButton(r, i);
    }), console.log('Added sidebar toggle button to admin bar');
}
function addMobileHamburgerIcon() {
    var e = document.getElementById('wp-admin-bar-root-default');
    if (!e) return void console.warn('Admin bar root element not found');
    var n = document.createElement('li');
    n.id = 'wp-admin-bar-mobile-hamburger', n.className = 'menupop';
    var o = document.createElement('a');
    o.className = 'ab-item', o.href = '#', o.setAttribute('aria-label', 'Toggle Sidebar');
    var a = document.createElement('span');
    a.className = 'dashicons dashicons-menu', a.setAttribute('title', 'Toggle Sidebar'), o.appendChild(a), n.appendChild(o);
    var r = e.firstChild;
    r ? e.insertBefore(n, r) : e.appendChild(n), o.addEventListener('click', function(e) {
        e.preventDefault(), e.stopPropagation(), console.log('Mobile hamburger icon clicked'), toggleSidebarVisibility();
    }), console.log('Added mobile hamburger icon to admin bar');
}
function updateToggleButton(e, n) {
    e && n && (e.classList.remove('dashicons-arrow-left', 'dashicons-arrow-right'), isSidebarVisible() ? (e.classList.add('dashicons-arrow-left'), e.setAttribute('title', 'Close Sidebar'), n.textContent = 'Close Sidebar') : (e.classList.add('dashicons-arrow-right'), e.setAttribute('title', 'Open Sidebar'), n.textContent = 'Open Sidebar'));
}
function r2() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log(AIStyleSettings), console.log('Customizing admin bar "New" button behavior');
        var e = document.getElementById('wp-admin-bar-new-content');
        if (!e) return void console.warn('Admin bar "New" button not found');
        overrideClickBehavior(overrideHoverBehavior(e)), addSidebarToggleButton(), addMobileHamburgerIcon();
    }
}
function o1(o) {
    if ((o = parseInt(o, 10)) === parseInt(t1.get('linked_post_id'), 10)) return void console.log('Clicked post is already the current linked post');
    var e = t1.getPostId(), i = AIStyleSettings.nonce, c = new FormData();
    c.append('nonce', i), c.append('post_id', e), c.append('linked_post_id', o), r(e, c, "/wp-json/cacbot/v1/link-conversation").then(function(t) {
        console.log('conversation successfully linked:', t), window.location.reload();
    }).catch(function(t) {
        console.error('Error linking conversation!:', t);
    });
}
function initSidebarClickListeners() {
    console.log("initSidebarClickListerners"), document.querySelectorAll('.anchor-post-list li a').forEach(function(t) {
        t.addEventListener('click', function(t) {
            t.preventDefault(), o1(this.closest('li').getAttribute('data-post-id'));
        });
    });
}
function e3() {
    var e = null, t = !0, o = !1, n = void 0;
    try {
        for(var l, m = [
            '.comment',
            '.comment-body',
            '.commentlist li',
            '#comments .comment',
            '.wp-block-comment',
            '[id^="comment-"]',
            '.message',
            '.interlocutor-message',
            '.respondent-message',
            '.message-content'
        ][Symbol.iterator](); !(t = (l = m.next()).done); t = !0){
            var r = l.value, c = document.querySelectorAll(r);
            if (c.length > 0) {
                e = c[c.length - 1];
                break;
            }
        }
    } catch (e) {
        o = !0, n = e;
    } finally{
        try {
            t || null == m.return || m.return();
        } finally{
            if (o) throw n;
        }
    }
    if (e) {
        e.hasAttribute('tabindex') || e.setAttribute('tabindex', '-1'), e.focus();
        var s = document.getElementById('scrollable-content');
        s && s.scrollTo({
            top: s.scrollHeight,
            behavior: 'smooth'
        }), console.log('Focus set to last comment:', e);
    } else console.log('No comments found on the page');
}
function t3(t) {
    var n, e;
    return Promise.all([
        (n = t, fetch("/wp-json/wp/v2/posts/".concat(n)).then(function(t) {
            if (!t.ok) throw Error("Failed to fetch post content: ".concat(t.status, " ").concat(t.statusText));
            return t.json();
        }).then(function(t) {
            return {
                id: t.id,
                title: t.title.rendered,
                content: t.content.rendered,
                date: t.date,
                author: t.author,
                status: t.status
            };
        })),
        (e = t, fetch("/wp-json/wp/v2/comments?post=".concat(e, "&orderby=date&order=asc")).then(function(t) {
            if (!t.ok) throw Error("Failed to fetch comments: ".concat(t.status, " ").concat(t.statusText));
            return t.json();
        }).then(function(t) {
            return t.map(function(t) {
                return {
                    id: t.id,
                    author: t.author_name,
                    content: t.content.rendered,
                    date: t.date,
                    parent: t.parent
                };
            });
        }))
    ]).then(function(t) {
        var n = _sliced_to_array(t, 2);
        return {
            postContent: n[0],
            comments: n[1]
        };
    }).catch(function(t) {
        throw console.error('Error fetching post data:', t), t;
    });
}
function updatePostUI(t) {
    var n = document.querySelector('.entry-content');
    if (n && t.postContent) {
        n.innerHTML = t.postContent.content;
        var e = document.querySelector('.entry-title');
        e && (e.innerHTML = t.postContent.title);
        var o = "/index.php/".concat(t.postContent.id, "/");
        window.history.pushState({
            postId: t.postContent.id
        }, t.postContent.title, o);
    }
    var c = document.getElementById('comments');
    if (c && t.comments) {
        var r = c.querySelector('.comment-list') || document.createElement('ol');
        r.className = 'comment-list', r.innerHTML = '', t.comments.forEach(function(t) {
            var n = document.createElement('li');
            n.id = "comment-".concat(t.id), n.className = 'comment', n.innerHTML = '\n                <article class="comment-body">\n                    <footer class="comment-meta">\n                        <div class="comment-author">\n                            <b class="fn">'.concat(t.author, '</b>\n                        </div>\n                        <div class="comment-metadata">\n                            <time datetime="').concat(t.date, '">').concat(new Date(t.date).toLocaleString(), '</time>\n                        </div>\n                    </footer>\n                    <div class="comment-content">').concat(t.content, "</div>\n                </article>\n            "), r.appendChild(n);
        }), c.querySelector('.comment-list') || c.appendChild(r);
    }
}
function o2() {
    console.log("setupPostNavigation()"), document.addEventListener('click', function(o) {
        var e = o.target.closest('a');
        if (!e) return;
        var r = e.getAttribute('href');
        if (!(!r || r.startsWith('#') || r.startsWith('http')) && -1 === r.indexOf('wp-admin')) {
            var i = r.match(/\/(\d+)\/?$/);
            if (i) {
                var a = parseInt(i[1], 10);
                if (!isNaN(a)) {
                    o.preventDefault();
                    var s = document.querySelector('.entry-content');
                    s && (s.innerHTML = '<div class="loading">Loading post content...</div>'), t3(a).then(function(t) {
                        updatePostUI(t), window.scrollTo(0, 0);
                    }).catch(function(t) {
                        console.error('Error navigating to post:', t), s && (s.innerHTML = '<div class="error">Error loading post: '.concat(t.message, "</div>"));
                    });
                }
            }
        }
    }), window.addEventListener('popstate', function(o) {
        o.state && o.state.postId && t3(o.state.postId).then(updatePostUI).catch(function(t) {
            console.error('Error handling history navigation:', t);
        });
    });
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t(), e();
    try {
        t1.initialize(window.cacbot_data || {}), window.cacbotData = t1, console.log(t1.getAll());
    } catch (o) {
        console.error("Failed to initialize cacbotData:", o);
    }
    window.addInterlocutorMessage = addInterlocutorMessage, window.addRespondentMessage = addRespondentMessage, window.clearMessages = clearMessages, console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), console.log('- clearMessages()'), initToggleSidebar(), r2(), o2(), initSidebarClickListeners(), console.log('Toggle sidebar functions are available globally:'), console.log('- toggleSidebarVisibility()'), console.log('- isSidebarVisible()'), console.log('- showSidebar()'), console.log('- hideSidebar()'), console.log('Admin bar customization functions are available globally:'), console.log('- overrideHoverBehavior(newButton)'), console.log('- overrideClickBehavior(newButton)'), console.log('- addSidebarToggleButton()'), console.log('- updateToggleButton(iconElement, labelElement)'), console.log('- initializeZoomDetection()'), e3();
});
