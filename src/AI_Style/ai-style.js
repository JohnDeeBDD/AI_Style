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
function createActionButtonsContainer(t) {
    if (document.getElementById('action-buttons-container')) return document.getElementById('action-buttons-container');
    var e = document.createElement('div');
    e.id = 'action-buttons-container', e.className = 'action-buttons-container';
    var n = document.querySelector('.comment-form-comment');
    if (n) {
        t.insertBefore(e, n.nextSibling);
        var c = addActionBubble('dashicons-plus', 'Attach', e);
        addActionBubble('dashicons-hammer', 'Act', e), c && (c.title = "Add attachment");
    }
    return e;
}
function addActionBubble(t, e, n) {
    var c = document.createElement('button');
    c.type = 'button', c.className = 'action-bubble';
    var o = document.createElement('span');
    if (o.className = "dashicons ".concat(t), c.appendChild(o), e) {
        var a = document.createElement('span');
        a.className = 'action-bubble-text', a.textContent = e, c.appendChild(a);
    }
    return c.addEventListener('click', function(t) {
        t.preventDefault(), console.log("Action button clicked: ".concat(e));
    }), n.appendChild(c), c;
}
function e() {
    var e = document.getElementById('commentform');
    e && (e.addEventListener('click', function(t) {
        t.target === e && commentTextarea.focus();
    }), createActionButtonsContainer(e), function() {
        var t = document.querySelector('.form-submit input[type="submit"]'), e = document.getElementById('comment');
        if (t && e) {
            var o = document.querySelector('.form-submit');
            o && (o.style.display = 'flex', o.style.alignItems = 'center'), e.addEventListener('input', function() {
                '' === this.value.trim() ? (t.style.opacity = '0.6', t.style.cursor = 'default') : (t.style.opacity = '1', t.style.cursor = 'pointer');
            }), '' === e.value.trim() && (t.style.opacity = '0.6', t.style.cursor = 'default');
        }
    }());
}
function e1() {
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
function e2(e) {
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
    s.className = 'message-content', s.id = 'message-content-' + r, s.innerHTML = e2(o), a.appendChild(s), t.appendChild(a), n();
}
function addRespondentMessage(o) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var r = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message respondent-message', a.id = r;
    var s = document.createElement('div');
    s.className = 'message-content', s.id = 'message-content-' + r, s.innerHTML = e2(o), a.appendChild(s), t.appendChild(a), n();
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
var t = new (/*#__PURE__*/ function() {
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
function overrideHoverBehavior(n) {
    var o, e, t, r = (e = (o = n).cloneNode(!0), o.parentNode.replaceChild(e, o), e);
    return (t = document.createElement('style')).textContent = "\n      #wp-admin-bar-new-content .ab-sub-wrapper {\n        display: none !important;\n      }\n      #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n        display: none !important;\n      }\n    ", document.head.appendChild(t), r;
}
function overrideClickBehavior(n) {
    var o, e = ((o = n.querySelector('a.ab-item')) || console.warn('Admin bar "New" button link not found'), o);
    e && e.addEventListener('click', t1);
}
function t1(t1) {
    t1.preventDefault(), t1.stopPropagation(), console.log('New button clicked'), clearMessages();
    var r1, a, i, c = t.getPostId(), d = AIStyleSettings.nonce;
    console.log("nonce:", d), c && d ? (r1 = c, a = d, (i = new FormData()).append('post_id', r1), i.append('nonce', a), r(r1, i, "/wp-json/cacbot/v1/unlink-conversation").then(function(n) {
        console.log('Archive conversation response:', n), window.location.reload();
    }).catch(function(n) {
        console.error('Error archiving conversation:', n);
    })) : console.warn('Cannot archive conversation: Missing post_id or nonce');
}
function r1() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log(AIStyleSettings), console.log('Customizing admin bar "New" button behavior');
        var n = document.getElementById('wp-admin-bar-new-content');
        if (!n) return void console.warn('Admin bar "New" button not found');
        overrideClickBehavior(overrideHoverBehavior(n));
    }
}
var e3 = {
    isVisible: !0,
    isAnimating: !1,
    originalWidth: '377px',
    isMobileView: !1,
    isDesktopView: !0,
    resizeTimeout: null
}, i = 'ai_style_sidebar_visible';
function t2() {
    try {
        localStorage.setItem(i, e3.isVisible.toString()), console.log('Sidebar state saved to localStorage:', e3.isVisible);
    } catch (e) {
        console.warn('Failed to save sidebar state to localStorage:', e);
    }
}
function initToggleSidebar() {
    console.log('Initializing toggle sidebar functionality'), a(), console.log('Responsive modes detected:', {
        isMobileView: e3.isMobileView,
        isDesktopView: e3.isDesktopView
    }), function(i) {
        var s, n, o = document.getElementById('chat-sidebar');
        if (!o) return console.warn('Sidebar element not found');
        e3.originalWidth = window.getComputedStyle(o).width, null !== i ? console.log('Using saved sidebar state:', n = i) : console.log('Using responsive logic for sidebar state:', n = e3.isDesktopView, 'Mode:', e3.isDesktopView ? 'desktop' : 'mobile'), e3.isVisible = n, n ? function(i) {
            if (i.classList.remove('sidebar-hidden'), e3.isMobileView) i.classList.add('sidebar-visible'), i.style.left = '0';
            else {
                var t = l();
                i.style.width = t, i.style.minWidth = t, i.style.paddingLeft = '16px', i.style.paddingRight = '16px', b(!1);
            }
            i.style.overflow = 'auto';
        }(o) : (s = o, e3.isMobileView ? (s.classList.remove('sidebar-visible'), s.style.left = '-100%') : (s.style.width = '0', s.style.minWidth = '0', s.style.paddingLeft = '0', s.style.paddingRight = '0', s.classList.add('sidebar-hidden'), b(!0)), s.style.overflow = 'hidden'), t2();
    }(function() {
        try {
            var e = localStorage.getItem(i);
            if (null !== e) {
                var t = 'true' === e;
                return console.log('Sidebar state loaded from localStorage:', t), t;
            }
        } catch (e) {
            console.warn('Failed to load sidebar state from localStorage:', e);
        }
        return null;
    }()), function() {
        if (!document.getElementById('sidebar-toggle-styles')) {
            var e = document.createElement('style');
            e.id = 'sidebar-toggle-styles', e.textContent = "\n    /* Sidebar toggle animation styles */\n    #chat-sidebar.sidebar-transitioning {\n      transition: width 300ms ease-in-out,\n                  min-width 300ms ease-in-out,\n                  padding-left 300ms ease-in-out,\n                  padding-right 300ms ease-in-out;\n    }\n    \n    #chat-sidebar.sidebar-hidden {\n      width: 0 !important;\n      min-width: 0 !important;\n      padding-left: 0 !important;\n      padding-right: 0 !important;\n      overflow: hidden !important;\n      border-right: none !important;\n    }\n    \n    /* Ensure main content expands when sidebar is hidden */\n    #chat-sidebar.sidebar-hidden + #chat-main {\n      width: 100% !important;\n      max-width: 100% !important;\n    }\n    \n    /* Add smooth transition for footer position changes */\n    .site-footer {\n      transition: left 300ms ease-in-out, display 300ms ease-in-out;\n    }\n    \n    /* Add smooth transition for comment form visibility */\n    #fixed-content {\n      transition: display 300ms ease-in-out;\n    }\n    \n    /* Admin bar toggle button styles */\n    #wp-admin-bar-sidebar-toggle .ab-item {\n      display: flex !important;\n      align-items: center;\n      gap: 4px;\n    }\n    \n    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {\n      font-size: 24px !important;\n      width: 24px !important;\n      height: 24px !important;\n      line-height: 1 !important;\n      vertical-align: middle !important;\n      margin: 0 !important;\n      padding: 0 !important;\n      display: inline-block !important;\n    }\n    \n    #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n      font-size: 13px;\n    }\n    \n    /* Ensure icon remains visible at all viewport sizes */\n    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {\n      display: inline-block !important;\n      visibility: visible !important;\n      opacity: 1 !important;\n    }\n  ", document.head.appendChild(e), console.log('Added sidebar toggle animation CSS with responsive styles');
        }
    }(), window.addEventListener('resize', s), console.log('Toggle sidebar initialized with state:', e3);
}
function s() {
    clearTimeout(e3.resizeTimeout), e3.resizeTimeout = setTimeout(function() {
        if (a()) {
            console.log('Responsive mode changed on resize:', {
                isMobileView: e3.isMobileView,
                isDesktopView: e3.isDesktopView
            });
            var i = document.getElementById('chat-sidebar');
            if (i) if (e3.isMobileView) console.log('Switching to mobile overlay mode'), e3.isVisible ? (i.classList.add('sidebar-visible'), i.style.left = '0') : (i.classList.remove('sidebar-visible'), i.style.left = '-100%'), i.style.width = '', i.style.minWidth = '', i.style.position = '';
            else if (console.log('Switching to desktop push/shrink mode'), i.classList.remove('sidebar-visible'), i.style.left = '', e3.isVisible) {
                var t = l();
                i.style.width = t, i.style.minWidth = t, i.classList.remove('sidebar-hidden');
            } else i.style.width = '0', i.style.minWidth = '0', i.classList.add('sidebar-hidden');
        }
    }, 250);
}
function n1() {
    return window.innerWidth < 782;
}
function o() {
    return window.innerWidth >= 782;
}
function a() {
    var i = e3.isMobileView, t = e3.isDesktopView;
    e3.isMobileView = n1(), e3.isDesktopView = o();
    var s = i !== e3.isMobileView || t !== e3.isDesktopView;
    return console.log('Responsive mode updated:', {
        isMobileView: e3.isMobileView,
        isDesktopView: e3.isDesktopView,
        modeChanged: s
    }), s;
}
function l() {
    return e3.originalWidth;
}
function toggleSidebarVisibility() {
    if (e3.isAnimating) return void console.log('Sidebar animation in progress, ignoring toggle request');
    var i = document.getElementById('chat-sidebar');
    if (!i) return void console.warn('Sidebar element not found');
    e3.isAnimating = !0, a(), e3.isVisible ? d(i) : r2(i), e3.isVisible = !e3.isVisible, t2(), updateToggleButton(), console.log('Toggled sidebar visibility. New state:', e3.isVisible ? 'visible' : 'hidden', 'Mode:', e3.isMobileView ? 'mobile' : 'desktop');
}
function d(i) {
    i.classList.add('sidebar-transitioning'), e3.isMobileView ? (i.classList.remove('sidebar-visible'), i.style.left = '-100%') : (i.style.width = '0', i.style.minWidth = '0', i.style.paddingLeft = '0', i.style.paddingRight = '0', b(!0)), i.style.overflow = 'hidden', setTimeout(function() {
        i.classList.remove('sidebar-transitioning'), e3.isMobileView || i.classList.add('sidebar-hidden'), e3.isAnimating = !1;
    }, 300);
}
function r2(i) {
    if (i.classList.remove('sidebar-hidden'), i.classList.add('sidebar-transitioning'), e3.isMobileView) i.classList.add('sidebar-visible'), i.style.left = '0';
    else {
        var t = l();
        i.style.width = t, i.style.minWidth = t, i.style.paddingLeft = '16px', i.style.paddingRight = '16px', b(!1);
    }
    i.style.overflow = 'hidden', setTimeout(function() {
        i.classList.remove('sidebar-transitioning'), i.style.overflow = 'auto', e3.isAnimating = !1;
    }, 300);
}
function b(e) {
    var i = document.querySelector('.site-footer');
    if (!i) return void console.warn('Footer element not found');
    e ? (i.style.left = '0px', console.log('Footer position updated: left = 0px (sidebar hidden)')) : (i.style.left = '377px', console.log('Footer position updated: left = 377px (sidebar visible)'));
}
function updateToggleButton() {
    var i = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null, t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : null, s = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : null, n = null !== s ? s : e3.isVisible, o = i || document.querySelector('#wp-admin-bar-sidebar-toggle .dashicons'), a = t || document.querySelector('#wp-admin-bar-sidebar-toggle .ab-label');
    if (!o || !a) return void console.warn('Toggle button elements not found for state update');
    o.classList.remove('dashicons-arrow-left', 'dashicons-arrow-right'), n ? (o.classList.add('dashicons-arrow-left'), o.setAttribute('title', 'Close Sidebar'), a.textContent = 'Close Sidebar') : (o.classList.add('dashicons-arrow-right'), o.setAttribute('title', 'Open Sidebar'), a.textContent = 'Open Sidebar'), console.log('Updated toggle button state:', n ? 'arrow-left (close)' : 'arrow-right (open)');
}
function isSidebarVisible() {
    return e3.isVisible;
}
function showSidebar() {
    if (!e3.isVisible) {
        var i = document.getElementById('chat-sidebar');
        i && (r2(i), e3.isVisible = !0, t2(), updateToggleButton());
    }
}
function hideSidebar() {
    if (e3.isVisible) {
        var i = document.getElementById('chat-sidebar');
        i && (d(i), e3.isVisible = !1, t2(), updateToggleButton());
    }
}
function addMobileHamburgerIcon() {
    var a = document.getElementById('wp-admin-bar-root-default');
    if (!a) return void console.warn('Admin bar root element not found');
    var t = document.createElement('li');
    t.id = 'wp-admin-bar-mobile-hamburger', t.className = 'menupop';
    var o = document.createElement('a');
    o.className = 'ab-item', o.href = '#', o.setAttribute('aria-label', 'Toggle Sidebar');
    var r = document.createElement('span');
    r.className = 'dashicons dashicons-menu', r.setAttribute('title', 'Toggle Sidebar'), o.appendChild(r), t.appendChild(o);
    var n = a.firstChild;
    n ? a.insertBefore(t, n) : a.appendChild(t), o.addEventListener('click', function(a) {
        a.preventDefault(), a.stopPropagation(), console.log('Mobile hamburger icon clicked'), toggleSidebarVisibility();
    }), console.log('Added mobile hamburger icon to admin bar');
}
var o1 = {
    button: null,
    icon: null,
    label: null,
    isInitialized: !1
};
function initializeArrowToggleButton() {
    if (console.log('Initializing desktop arrow toggle button'), o1.isInitialized) return void console.log('Arrow toggle button already initialized');
    var e = document.getElementById('wp-admin-bar-root-default');
    if (!e) return void console.warn('Admin bar root element not found');
    var t = document.getElementById('wp-admin-bar-new-content');
    if (!t) return void console.warn('Cannot position sidebar toggle: New button not found');
    var l = document.createElement('li');
    l.id = 'wp-admin-bar-sidebar-toggle', l.className = 'menupop';
    var i = document.createElement('a');
    i.className = 'ab-item', i.href = '#', i.setAttribute('aria-label', 'Toggle Sidebar');
    var r = document.createElement('span');
    r.className = 'dashicons';
    var a = document.createElement('span');
    a.className = 'ab-label', o1.button = l, o1.icon = r, o1.label = a, i.appendChild(r), i.appendChild(a), l.appendChild(i);
    var u = t.nextSibling;
    u ? e.insertBefore(l, u) : e.appendChild(l), i.addEventListener('click', n2), o1.isInitialized = !0, updateArrowToggleButton(), console.log('Desktop arrow toggle button initialized successfully');
}
function n2(e) {
    e.preventDefault(), e.stopPropagation(), console.log('Desktop arrow toggle button clicked'), toggleSidebarVisibility(), updateArrowToggleButton();
}
function updateArrowToggleButton() {
    if (!o1.isInitialized || !o1.icon || !o1.label) return void console.warn('Arrow toggle button not initialized or elements missing');
    updateToggleButton(o1.icon, o1.label), console.log('Desktop arrow toggle button state updated');
}
function o2(o) {
    if ((o = parseInt(o, 10)) === parseInt(t.get('linked_post_id'), 10)) return void console.log('Clicked post is already the current linked post');
    var e = t.getPostId(), i = AIStyleSettings.nonce, c = new FormData();
    c.append('nonce', i), c.append('post_id', e), c.append('linked_post_id', o), r(e, c, "/wp-json/cacbot/v1/link-conversation").then(function(t) {
        console.log('conversation successfully linked:', t), window.location.reload();
    }).catch(function(t) {
        console.error('Error linking conversation!:', t);
    });
}
function initSidebarClickListeners() {
    console.log("initSidebarClickListerners"), document.querySelectorAll('.anchor-post-list li a').forEach(function(t) {
        t.addEventListener('click', function(t) {
            t.preventDefault(), o2(this.closest('li').getAttribute('data-post-id'));
        });
    });
}
function e4() {
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
function o3() {
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
    console.log('ai-style.js is loaded!'), e(), e1();
    try {
        t.initialize(window.cacbot_data || {}), window.cacbotData = t, console.log(t.getAll());
    } catch (o) {
        console.error("Failed to initialize cacbotData:", o);
    }
    window.addInterlocutorMessage = addInterlocutorMessage, window.addRespondentMessage = addRespondentMessage, window.clearMessages = clearMessages, console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), console.log('- clearMessages()'), initToggleSidebar(), window.toggleSidebarVisibility = toggleSidebarVisibility, window.isSidebarVisible = isSidebarVisible, window.showSidebar = showSidebar, window.hideSidebar = hideSidebar, r1(), initializeArrowToggleButton(), addMobileHamburgerIcon(), o3(), initSidebarClickListeners(), console.log('Toggle sidebar functions are available globally:'), console.log('- toggleSidebarVisibility()'), console.log('- isSidebarVisible()'), console.log('- showSidebar()'), console.log('- hideSidebar()'), console.log('Admin bar customization functions are available globally:'), console.log('- overrideHoverBehavior(newButton)'), console.log('- overrideClickBehavior(newButton)'), console.log('Toggle button functions initialized:'), console.log('- Desktop arrow toggle button initialized'), console.log('- Mobile hamburger button initialized'), e4();
});
