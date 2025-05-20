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
        var c = addActionBubble('dashicons-plus', '', e);
        c && (c.title = "Add attachment");
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
    console.log("Applying modern LLM-style to comment form");
    var e = document.getElementById('comment');
    e && (e.rows = 1, e.style.height = 'auto', '' === e.getAttribute('placeholder') && e.setAttribute('placeholder', 'Ask anything'), e.addEventListener('input', function() {
        this.style.height = 'auto', this.style.height = this.scrollHeight + 'px';
    }), e.style.height = e.scrollHeight + 'px');
    var o = document.getElementById('commentform');
    o && (o.addEventListener('click', function(t) {
        t.target === o && e.focus();
    }), createActionButtonsContainer(o), function() {
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
function addInterlocutorMessage(s) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var n = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message interlocutor-message', a.id = n;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + n, o.innerHTML = s, a.appendChild(o), t.appendChild(a), e2();
}
function addRespondentMessage(s) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var n = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message respondent-message', a.id = n;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + n, o.innerHTML = s, a.appendChild(o), t.appendChild(a), e2();
}
function e2() {
    var e = document.querySelector('#scrollable-content');
    e && (e.scrollTop = e.scrollHeight);
}
function clearMessages() {
    var e = document.getElementById('chat-messages');
    if (!e) return void console.error('Chat messages container not found');
    for(; e.firstChild;)e.removeChild(e.firstChild);
}
const __default = {
    addInterlocutorMessage: addInterlocutorMessage,
    addRespondentMessage: addRespondentMessage,
    clearMessages: clearMessages
};
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
function t1() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log(AIStyleSettings), console.log('Customizing admin bar "New" button behavior');
        var t1, a, r1, i = document.getElementById('wp-admin-bar-new-content');
        if (!i) return void console.warn('Admin bar "New" button not found');
        !function(t1) {
            var a = t1.querySelector('a.ab-item');
            if (!a) return console.warn('Admin bar "New" button link not found');
            a.addEventListener('click', function(t1) {
                t1.preventDefault(), t1.stopPropagation(), console.log('New button clicked'), clearMessages();
                var a = t.getPostId(), r1 = AIStyleSettings.nonce;
                if (console.log("nonce:", r1), a && r1) {
                    var i = new FormData();
                    i.append('post_id', a), i.append('nonce', r1), r(a, i, "/wp-json/cacbot/v1/unlink-conversation").then(function(n) {
                        console.log('Archive conversation response:', n), window.location.reload();
                    }).catch(function(n) {
                        console.error('Error archiving conversation:', n);
                    });
                } else console.warn('Cannot archive conversation: Missing post_id or nonce');
            });
        }((a = (t1 = i).cloneNode(!0), t1.parentNode.replaceChild(a, t1), (r1 = document.createElement('style')).textContent = "\n    #wp-admin-bar-new-content .ab-sub-wrapper {\n      display: none !important;\n    }\n    #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n      display: none !important;\n    }\n  ", document.head.appendChild(r1), a));
    }
}
function t2(t) {
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
function o(o) {
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
            t.preventDefault(), o(this.closest('li').getAttribute('data-post-id'));
        });
    });
}
window.addInterlocutorMessage = __default.addInterlocutorMessage, window.addRespondentMessage = __default.addRespondentMessage, window.fetchPost = t2, window.updatePostUI = updatePostUI, document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), e(), e1(), console.log("PHP cacbot_data:"), console.log(window.cacbot_data);
    try {
        t.initialize(window.cacbot_data || {}), console.log(t.getAll());
    } catch (t) {
        console.error("Failed to initialize cacbotData:", t);
    }
    console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), t1(), document.addEventListener('click', function(t) {
        var o = t.target.closest('a');
        if (!o) return;
        var e = o.getAttribute('href');
        if (!(!e || e.startsWith('#') || e.startsWith('http')) && -1 === e.indexOf('wp-admin')) {
            var a = e.match(/\/(\d+)\/?$/);
            if (a) {
                var n = parseInt(a[1], 10);
                if (!isNaN(n)) {
                    t.preventDefault();
                    var i = document.querySelector('.entry-content');
                    i && (i.innerHTML = '<div class="loading">Loading post content...</div>'), t2(n).then(function(t) {
                        updatePostUI(t), window.scrollTo(0, 0);
                    }).catch(function(t) {
                        console.error('Error navigating to post:', t), i && (i.innerHTML = '<div class="error">Error loading post: '.concat(t.message, "</div>"));
                    });
                }
            }
        }
    }), window.addEventListener('popstate', function(t) {
        t.state && t.state.postId && t2(t.state.postId).then(updatePostUI).catch(function(t) {
            console.error('Error handling history navigation:', t);
        });
    }), initSidebarClickListeners();
});
