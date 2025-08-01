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
    zoomLevel: 100
}, e2 = 'ai_style_sidebar_visible';
function t2() {
    try {
        localStorage.setItem(e2, i.isVisible.toString()), console.log('Sidebar state saved to localStorage:', i.isVisible);
    } catch (i) {
        console.warn('Failed to save sidebar state to localStorage:', i);
    }
}
function initToggleSidebar() {
    var n, o, s;
    console.log('Initializing toggle sidebar functionality'), n = window.devicePixelRatio || 1, o = screen.width / window.outerWidth, 100 === (s = Math.round(100 * n)) && o > 1 && (s = Math.round(100 * o)), i.zoomLevel = s, console.log('Detected zoom level:', s + '%'), function(e) {
        var n, o, s, d = document.getElementById('chat-sidebar');
        if (!d) return console.warn('Sidebar element not found');
        i.originalWidth = window.getComputedStyle(d).width, null !== e ? console.log('Using saved sidebar state:', s = e) : console.log('Using zoom level logic for sidebar state:', s = i.zoomLevel < 175, 'at zoom level:', i.zoomLevel + '%'), i.isVisible = s, s ? ((n = d).classList.remove('sidebar-hidden'), n.style.width = i.originalWidth, n.style.minWidth = i.originalWidth, n.style.overflow = 'auto', n.style.paddingLeft = '16px', n.style.paddingRight = '16px', a(!1)) : ((o = d).style.width = '0', o.style.minWidth = '0', o.style.overflow = 'hidden', o.style.paddingLeft = '0', o.style.paddingRight = '0', o.classList.add('sidebar-hidden'), a(!0)), t2();
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
            i.id = 'sidebar-toggle-styles', i.textContent = "\n    /* Sidebar toggle animation styles */\n    #chat-sidebar.sidebar-transitioning {\n      transition: width 300ms ease-in-out, \n                  min-width 300ms ease-in-out,\n                  padding-left 300ms ease-in-out,\n                  padding-right 300ms ease-in-out;\n    }\n    \n    #chat-sidebar.sidebar-hidden {\n      width: 0 !important;\n      min-width: 0 !important;\n      padding-left: 0 !important;\n      padding-right: 0 !important;\n      overflow: hidden !important;\n      border-right: none !important;\n    }\n    \n    /* Ensure main content expands when sidebar is hidden */\n    #chat-sidebar.sidebar-hidden + #chat-main {\n      width: 100% !important;\n      max-width: 100% !important;\n    }\n    \n    /* Add smooth transition for footer position changes */\n    .site-footer {\n      transition: left 300ms ease-in-out;\n    }\n    \n    /* Admin bar toggle button styles */\n    #wp-admin-bar-sidebar-toggle .ab-item {\n      display: flex !important;\n      align-items: center;\n      gap: 4px;\n    }\n    \n    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {\n      font-size: 24px !important;\n      width: 24px !important;\n      height: 24px !important;\n      line-height: 1 !important;\n      vertical-align: middle !important;\n      margin: 0 !important;\n      padding: 0 !important;\n      display: inline-block !important;\n    }\n    \n    #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n      font-size: 13px;\n    }\n    \n    /* Hide label text at high zoom levels (250%+) following WordPress patterns */\n    @media screen and (min-resolution: 2.5dppx) {\n      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n        display: none;\n      }\n    }\n    \n    /* Alternative media query for browsers that don't support dppx */\n    @media screen and (-webkit-min-device-pixel-ratio: 2.5) {\n      #wp-admin-bar-sidebar-toggle .ab-item .ab-label {\n        display: none;\n      }\n    }\n    \n    /* Ensure icon remains visible at all zoom levels */\n    #wp-admin-bar-sidebar-toggle .ab-item .dashicons {\n      display: inline-block !important;\n      visibility: visible !important;\n      opacity: 1 !important;\n    }\n  ", document.head.appendChild(i), console.log('Added sidebar toggle animation CSS');
        }
    }(), console.log('Toggle sidebar initialized with state:', i);
}
function toggleSidebarVisibility() {
    if (i.isAnimating) return void console.log('Sidebar animation in progress, ignoring toggle request');
    var e = document.getElementById('chat-sidebar');
    if (!e) return void console.warn('Sidebar element not found');
    i.isAnimating = !0, i.isVisible ? n1(e) : o(e), i.isVisible = !i.isVisible, t2(), console.log('Toggled sidebar visibility. New state:', i.isVisible ? 'visible' : 'hidden');
}
function n1(e) {
    e.classList.add('sidebar-transitioning'), e.style.width = '0', e.style.minWidth = '0', e.style.overflow = 'hidden', e.style.paddingLeft = '0', e.style.paddingRight = '0', a(!0), setTimeout(function() {
        e.classList.remove('sidebar-transitioning'), e.classList.add('sidebar-hidden'), i.isAnimating = !1;
    }, 300);
}
function o(e) {
    e.classList.remove('sidebar-hidden'), e.classList.add('sidebar-transitioning'), e.style.width = i.originalWidth, e.style.minWidth = i.originalWidth, e.style.overflow = 'hidden', e.style.paddingLeft = '16px', e.style.paddingRight = '16px', a(!1), setTimeout(function() {
        e.classList.remove('sidebar-transitioning'), e.style.overflow = 'auto', i.isAnimating = !1;
    }, 300);
}
function a(i) {
    var e = document.querySelector('.site-footer');
    if (!e) return void console.warn('Footer element not found');
    i ? (e.style.left = '0px', console.log('Footer position updated: left = 0px (sidebar hidden)')) : (e.style.left = '377px', console.log('Footer position updated: left = 377px (sidebar visible)'));
}
function isSidebarVisible() {
    return i.isVisible;
}
function overrideHoverBehavior(e) {
    var o = e.cloneNode(!0);
    e.parentNode.replaceChild(o, e);
    var n = document.createElement('style');
    return n.textContent = "\n    #wp-admin-bar-new-content .ab-sub-wrapper {\n      display: none !important;\n    }\n    #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n      display: none !important;\n    }\n  ", document.head.appendChild(n), o;
}
function overrideClickBehavior(t) {
    var a = t.querySelector('a.ab-item');
    if (!a) return void console.warn('Admin bar "New" button link not found');
    a.addEventListener('click', function(t) {
        t.preventDefault(), t.stopPropagation(), console.log('New button clicked'), clearMessages();
        var a = t1.getPostId(), i = AIStyleSettings.nonce;
        if (console.log("nonce:", i), a && i) {
            var r1 = new FormData();
            r1.append('post_id', a), r1.append('nonce', i), r(a, r1, "/wp-json/cacbot/v1/unlink-conversation").then(function(e) {
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
    var o = document.getElementById('wp-admin-bar-new-content');
    if (!o) return void console.warn('Cannot position sidebar toggle: New button not found');
    var n = document.createElement('li');
    n.id = 'wp-admin-bar-sidebar-toggle', n.className = 'menupop';
    var a = document.createElement('a');
    a.className = 'ab-item', a.href = '#', a.setAttribute('aria-label', 'Toggle Sidebar');
    var i = document.createElement('span');
    i.className = 'dashicons';
    var r = document.createElement('span');
    r.className = 'ab-label', updateToggleButton(i, r), a.appendChild(i), a.appendChild(r), n.appendChild(a);
    var d = o.nextSibling;
    d ? e.insertBefore(n, d) : e.appendChild(n), a.addEventListener('click', function(e) {
        e.preventDefault(), e.stopPropagation(), console.log('Sidebar toggle button clicked'), toggleSidebarVisibility(), updateToggleButton(i, r);
    }), console.log('Added sidebar toggle button to admin bar');
}
function updateToggleButton(e, o) {
    e && o && (e.classList.remove('dashicons-arrow-left', 'dashicons-arrow-right'), isSidebarVisible() ? (e.classList.add('dashicons-arrow-left'), e.setAttribute('title', 'Close Sidebar'), o.textContent = 'Close Sidebar') : (e.classList.add('dashicons-arrow-right'), e.setAttribute('title', 'Open Sidebar'), o.textContent = 'Open Sidebar'));
}
function initializeZoomDetection() {
    function e() {
        var e = window.devicePixelRatio || 1, o = Math.round(screen.width / window.outerWidth * 100) / 100, n = e;
        Math.abs(o - 1) > 0.1 && (n = o), document.body.classList.remove('zoom-200-plus', 'zoom-250-plus'), n >= 2.5 && (document.body.classList.add('zoom-250-plus'), console.log('Applied zoom-250-plus class'));
    }
    console.log('Initializing zoom detection for admin bar'), e(), window.addEventListener('resize', e), 'onzoom' in window && window.addEventListener('zoom', e), setInterval(e, 1000);
}
function i1() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log(AIStyleSettings), console.log('Customizing admin bar "New" button behavior');
        var e = document.getElementById('wp-admin-bar-new-content');
        if (!e) return void console.warn('Admin bar "New" button not found');
        overrideClickBehavior(overrideHoverBehavior(e)), addSidebarToggleButton(), initializeZoomDetection();
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
    window.addInterlocutorMessage = addInterlocutorMessage, window.addRespondentMessage = addRespondentMessage, window.clearMessages = clearMessages, console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), console.log('- clearMessages()'), initToggleSidebar(), i1(), o2(), initSidebarClickListeners(), console.log('Toggle sidebar functions are available globally:'), console.log('- toggleSidebarVisibility()'), console.log('- isSidebarVisible()'), console.log('- showSidebar()'), console.log('- hideSidebar()'), console.log('Admin bar customization functions are available globally:'), console.log('- overrideHoverBehavior(newButton)'), console.log('- overrideClickBehavior(newButton)'), console.log('- addSidebarToggleButton()'), console.log('- updateToggleButton(iconElement, labelElement)'), console.log('- initializeZoomDetection()'), e3();
});
