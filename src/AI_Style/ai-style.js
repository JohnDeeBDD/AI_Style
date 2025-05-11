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
function _type_of(obj) {
    "@swc/helpers - typeof";
    return obj && typeof Symbol !== "undefined" && obj.constructor === Symbol ? "symbol" : typeof obj;
}
function t() {
    console.log("Applying modern LLM-style to comment form");
    var t = document.getElementById('comment');
    t && (t.rows = 1, t.style.height = 'auto', '' === t.getAttribute('placeholder') && t.setAttribute('placeholder', 'Ask anything'), t.addEventListener('input', function() {
        this.style.height = 'auto', this.style.height = this.scrollHeight + 'px';
    }), t.style.height = t.scrollHeight + 'px');
    var n = document.getElementById('commentform');
    n && (n.addEventListener('click', function(e) {
        e.target === n && t.focus();
    }), function(t) {
        if (!document.getElementById('action-buttons-container')) {
            var n = document.createElement('div');
            n.id = 'action-buttons-container', n.className = 'action-buttons-container';
            var o = document.querySelector('.comment-form-comment');
            if (o) {
                t.insertBefore(n, o.nextSibling);
                var c = e('dashicons-plus', '', n);
                e('dashicons-upload', 'Attach', n), e('dashicons-editor-code', 'Code', n), e('dashicons-format-image', 'Image', n), c && (c.title = "Add attachment");
            }
        }
    }(n), function() {
        var t = document.querySelector('.form-submit input[type="submit"]'), e = document.getElementById('comment');
        if (t && e) {
            var n = document.querySelector('.form-submit');
            n && (n.style.display = 'flex', n.style.alignItems = 'center'), e.addEventListener('input', function() {
                '' === this.value.trim() ? (t.style.opacity = '0.6', t.style.cursor = 'default') : (t.style.opacity = '1', t.style.cursor = 'pointer');
            }), '' === e.value.trim() && (t.style.opacity = '0.6', t.style.cursor = 'default');
        }
    }());
}
function e(t, e, n) {
    var o = document.createElement('button');
    o.type = 'button', o.className = 'action-bubble';
    var c = document.createElement('span');
    if (c.className = "dashicons ".concat(t), o.appendChild(c), e) {
        var i = document.createElement('span');
        i.className = 'action-bubble-text', i.textContent = e, o.appendChild(i);
    }
    return o.addEventListener('click', function(t) {
        t.preventDefault(), console.log("Action button clicked: ".concat(e));
    }), n.appendChild(o), o;
}
function e1() {
    if (document.querySelectorAll('#chat-messages .message').length > 0) {
        var e = document.getElementById('main-call-to-action-1');
        e && (e.style.display = 'none');
    }
}
function e2() {
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
    o.className = 'message-content', o.id = 'message-content-' + n, o.innerHTML = s, a.appendChild(o), t.appendChild(a), e3();
}
function addRespondentMessage(s) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var n = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message respondent-message', a.id = n;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + n, o.innerHTML = s, a.appendChild(o), t.appendChild(a), e3();
}
function e3() {
    var e = document.querySelector('.scrollable-content');
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
function e4() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log('Customizing admin bar "New" button behavior');
        var e, t, o, a = document.getElementById('wp-admin-bar-new-content');
        if (!a) return void console.warn('Admin bar "New" button not found');
        !function(e) {
            var t = e.querySelector('a.ab-item');
            if (!t) return console.warn('Admin bar "New" button link not found');
            t.addEventListener('click', function(e) {
                e.preventDefault(), e.stopPropagation(), console.log('New button clicked'), clearMessages();
            });
        }((t = (e = a).cloneNode(!0), e.parentNode.replaceChild(t, e), (o = document.createElement('style')).textContent = "\n    #wp-admin-bar-new-content .ab-sub-wrapper {\n      display: none !important;\n    }\n    #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n      display: none !important;\n    }\n  ", document.head.appendChild(o), t));
    }
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
window.addInterlocutorMessage = __default.addInterlocutorMessage, window.addRespondentMessage = __default.addRespondentMessage, document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t(), e1(), e2(), console.log("PHP cacbot_data:"), console.log(window.cacbot_data);
    try {
        t1.initialize(window.cacbot_data || {}), console.log(t1.getAll());
    } catch (o) {
        console.error("Failed to initialize cacbotData:", o);
    }
    console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), e4();
});
