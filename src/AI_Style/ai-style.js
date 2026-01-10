function _array_like_to_array(arr, len) {
    if (len == null || len > arr.length) len = arr.length;
    for(var i = 0, arr2 = new Array(len); i < len; i++)arr2[i] = arr[i];
    return arr2;
}
function _array_with_holes(arr) {
    if (Array.isArray(arr)) return arr;
}
function _array_without_holes(arr) {
    if (Array.isArray(arr)) return _array_like_to_array(arr);
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
function _iterable_to_array(iter) {
    if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
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
function _non_iterable_spread() {
    throw new TypeError("Invalid attempt to spread non-iterable instance.\\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
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
function _to_consumable_array(arr) {
    return _array_without_holes(arr) || _iterable_to_array(arr) || _unsupported_iterable_to_array(arr) || _non_iterable_spread();
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
function createActionButtonsContainer(n) {
    if (document.getElementById('action-buttons-container')) return document.getElementById('action-buttons-container');
    var e = document.createElement('div');
    e.id = 'action-buttons-container', e.className = 'action-buttons-container';
    var o = document.querySelector('.comment-form-comment');
    if (o) {
        n.insertBefore(e, o.nextSibling), addActionBubble('dashicons-hammer', 'Build Plugin', e, 'action-button-build-plugin');
        var i = document.getElementById('action-button-build-plugin');
        i && (i.style.display = 'none'), document.addEventListener('cacbot-data-updated', function(n) {
            console.log('CommentFormButtons: Received updated data from app.ts:', n.detail), t(n.detail);
        }), t(window.cacbot_data);
    }
    return e;
}
function t(t) {
    if (!t) return void console.log('CommentFormButtons: No data provided, keeping buttons hidden');
    var n = document.getElementById('action-button-build-plugin');
    if (!n) return void console.log('CommentFormButtons: Build Plugin button not found');
    "1" === t._cacbot_action_enabled_build_plugin || !0 === t.action_enabled_build_plugin || !0 === t.action_enabled_create_new_linked_post ? (console.log('CommentFormButtons: Showing Build Plugin button'), n.style.display = '') : (console.log('CommentFormButtons: Hiding Build Plugin button'), n.style.display = 'none');
}
function addActionBubble(t, n, e) {
    var o = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : {};
    'string' == typeof o && (o = {
        id: o
    });
    var i = o.id, a = void 0 === i ? '' : i, d = o.callback, l = void 0 === d ? null : d, c = o.tooltip, u = void 0 === c ? '' : c, r = o.disabled, s = o.className, m = void 0 === s ? '' : s, b = o.data, p = o.position, v = document.createElement('button');
    v.type = 'button', v.className = "action-bubble".concat(m ? ' ' + m : ''), v.disabled = void 0 !== r && r, a && (v.id = a), u && (v.title = u, v.setAttribute('aria-label', u)), Object.entries(void 0 === b ? {} : b).forEach(function(t) {
        var n = _sliced_to_array(t, 2), e = n[0], o = n[1];
        v.dataset[e] = o;
    });
    var g = document.createElement('span');
    if (g.className = "dashicons ".concat(t), v.appendChild(g), n) {
        var f = document.createElement('span');
        f.className = 'action-bubble-text', f.textContent = n, v.appendChild(f);
    }
    return l && 'function' == typeof l && v.addEventListener('click', l), (void 0 === p ? 'append' : p) === 'prepend' ? e.insertBefore(v, e.firstChild) : e.appendChild(v), v;
}
function refreshButtonVisibility() {
    var n = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null;
    t(n || window.cacbot_data);
}
function t1() {
    var t = document.getElementById('commentform');
    t && (t.addEventListener('click', function(e) {
        e.target === t && commentTextarea.focus();
    }), createActionButtonsContainer(t), function() {
        var e = document.querySelector('.form-submit input[type="submit"]');
        if (e) {
            var t, i, o = document.querySelector('.form-submit');
            o && (o.style.display = 'flex', o.style.alignItems = 'center'), t = e, (i = document.getElementById('commentform')) && i.addEventListener('submit', function(e) {
                n(t, !0);
            });
        }
    }());
}
function n(e, t) {
    t ? (e.dataset.originalText || (e.dataset.originalText = e.value), e.dataset.submitting = 'true', e.disabled = !0, e.style.opacity = '0.7', e.style.cursor = 'not-allowed', e.value = 'Submitting...', e.style.position = 'relative', function(e) {
        i(e);
        var t = document.createElement('span');
        if (t.className = 'comment-submit-spinner', t.innerHTML = '⟳', t.style.cssText = "\n        display: inline-block;\n        margin-left: 8px;\n        animation: spin 1s linear infinite;\n        font-size: 14px;\n    ", !document.querySelector('#comment-spinner-styles')) {
            var n = document.createElement('style');
            n.id = 'comment-spinner-styles', n.textContent = "\n            @keyframes spin {\n                0% { transform: rotate(0deg); }\n                100% { transform: rotate(360deg); }\n            }\n        ", document.head.appendChild(n);
        }
        e.parentNode.insertBefore(t, e.nextSibling);
    }(e)) : (e.dataset.submitting = 'false', e.disabled = !1, e.style.opacity = '1', e.style.cursor = 'pointer', e.dataset.originalText && (e.value = e.dataset.originalText), i(e));
}
function i(e) {
    var t = e.parentNode.querySelector('.comment-submit-spinner');
    t && t.remove();
}
function resetCommentSubmitButton() {
    var e = document.querySelector('.form-submit input[type="submit"]');
    e && n(e, !1);
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
var e1 = new Map(), t2 = !0;
function n1(e) {
    if ('function' == typeof window.mmd) try {
        return window.mmd(e);
    } catch (t) {
        return console.warn('Error processing markdown with mmd:', t), e;
    }
    if ('function' == typeof window.MarkupMarkdown) try {
        var t = new window.MarkupMarkdown();
        if ('function' == typeof t.transform) return t.transform(e);
    } catch (e) {
        console.warn('Error processing markdown with MarkupMarkdown class:', e);
    }
    return e;
}
function initializeCommentMonitoring() {
    console.log('ChatMessages: Initializing comment monitoring...'), console.log('ChatMessages: window.cacbotData available:', !!window.cacbotData), console.log('ChatMessages: subscribeToComments function available:', window.cacbotData && 'function' == typeof window.cacbotData.subscribeToComments), window.cacbotData && 'function' == typeof window.cacbotData.subscribeToComments ? (window.cacbotData.subscribeToComments(o), console.log('ChatMessages: Successfully subscribed to comment updates')) : (console.warn('ChatMessages: cacbotData not available for comment monitoring'), console.warn('ChatMessages: Available window properties:', Object.keys(window).filter(function(e) {
        return e.includes('cacbot');
    }))), document.addEventListener('cacbot-data-updated', function(e) {
        if (console.log('ChatMessages: Received cacbot-data-updated event:', e.detail), e.detail && e.detail.comments) {
            var t = e.detail.comments, n = t.length;
            console.log('ChatMessages: Processing cacbot-data-updated with', n, 'comments'), o(t, n);
        } else console.warn('ChatMessages: cacbot-data-updated event received but no comments data found');
    }), console.log('ChatMessages: Successfully set up cacbot-data-updated event listener');
}
function o(n, o) {
    var s, c, r, i;
    if (console.log('ChatMessages: Received comment update', {
        commentCount: o,
        commentsLength: n ? n.length : 0,
        isInitialLoad: t2
    }), !n || !Array.isArray(n)) return void console.warn('ChatMessages: Invalid comments data received');
    if (t2) {
        a(n), t2 = !1;
        return;
    }
    c = new Map((s = n).map(function(e) {
        return [
            e.comment_ID,
            e
        ];
    })), r = s.filter(function(t) {
        return !e1.has(t.comment_ID);
    }), i = _to_consumable_array(e1.keys()).filter(function(e) {
        return !c.has(e);
    }), r.forEach(function(e) {
        var t, n, o, a, s, c, r, i;
        c = (console.log('ChatMessages: Determining message type for comment', {
            commentId: (n = t = e).comment_ID,
            userId: n.user_id,
            userIdType: _type_of(n.user_id),
            authorName: n.comment_author || 'N/A',
            authorEmail: n.comment_author_email || 'N/A'
        }), o = parseInt(n.user_id) || 0, a = 'Assistant' === n.comment_author || 'assistant@cacbot.com' === n.comment_author_email, console.log('ChatMessages: User ID analysis', {
            commentId: n.comment_ID,
            originalUserId: n.user_id,
            parsedUserId: o,
            isTestRespondent: a,
            shouldBeRespondent: 0 === o || a
        }), s = 0 === o || a ? 'respondent' : 'interlocutor', console.log('ChatMessages: Final message type determination', {
            commentId: n.comment_ID,
            userId: o,
            messageType: s,
            reason: 0 === o ? 'user_id is 0' : a ? 'test assistant comment' : 'logged in user'
        }), s), r = t.comment_content, i = "comment-".concat(t.comment_ID), 'interlocutor' === c ? addInterlocutorMessage(r, i) : addRespondentMessage(r, i), console.log('ChatMessages: Added comment to UI', {
            commentId: t.comment_ID,
            messageType: c,
            messageId: i
        });
    }), i.forEach(function(e) {
        var t, n;
        t = e, (n = document.getElementById("comment-".concat(t))) && (n.remove(), console.log('ChatMessages: Removed comment from UI', {
            commentId: t
        }));
    }), console.log('ChatMessages: Processed comment changes', {
        added: r.length,
        removed: i.length
    }), a(n);
}
function a(t) {
    e1 = new Map(t.map(function(e) {
        return [
            e.comment_ID,
            e
        ];
    }));
}
function addInterlocutorMessage(e) {
    var o = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : null, a = document.getElementById('chat-messages');
    if (!a) return void console.error('Chat messages container not found');
    var c = o || 'message-' + Date.now(), r = document.createElement('div');
    r.className = 'message interlocutor-message', r.id = c, t2 || o || r.classList.add('message-fade-in');
    var i = document.createElement('div');
    i.className = 'message-content', i.id = 'message-content-' + c, i.innerHTML = n1(e), r.appendChild(i), a.appendChild(r), s();
}
function addRespondentMessage(e) {
    var o = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : null, a = document.getElementById('chat-messages');
    if (!a) return void console.error('Chat messages container not found');
    var c = o || 'message-' + Date.now(), r = document.createElement('div');
    r.className = 'message respondent-message', r.id = c, t2 || o || r.classList.add('message-fade-in');
    var i = document.createElement('div');
    i.className = 'message-content', i.id = 'message-content-' + c, i.innerHTML = n1(e), r.appendChild(i), a.appendChild(r), s();
}
function s() {
    var e = document.querySelector('#scrollable-content');
    e && (e.scrollTop = e.scrollHeight);
}
function clearMessages() {
    var e = document.getElementById('chat-messages');
    if (!e) return void console.error('Chat messages container not found');
    for(; e.firstChild;)e.removeChild(e.firstChild);
}
'loading' === document.readyState ? document.addEventListener('DOMContentLoaded', function() {
    initializeCommentMonitoring();
}) : initializeCommentMonitoring();
var t3 = new (/*#__PURE__*/ function() {
    function t() {
        _class_call_check(this, t), this.data = {}, this.commentSubscribers = new Set(), this.lastCommentCount = 0, this.lastCommentIds = new Set(), this.isInitialized = !1, this.pollingIntervalId = null, this.currentPostId = null, this.pollingInterval = 3000;
    }
    return _create_class(t, [
        {
            key: "initialize",
            value: function(t) {
                if (!t || (void 0 === t ? "undefined" : _type_of(t)) !== 'object') throw Error('Invalid data: rawData must be a non-null object');
                var n = [
                    'nonce'
                ].filter(function(n) {
                    return !t.hasOwnProperty(n);
                });
                if (n.length > 0) throw Error("Missing required fields: ".concat(n.join(', ')));
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
                var n = this.get("action_enabled_".concat(t));
                return '1' === n || !0 === n;
            }
        },
        {
            key: "initializeEventMonitoring",
            value: function() {
                var t = this;
                this.isInitialized || (console.log('CacbotData: Initializing event monitoring...'), document.addEventListener('cacbot-data-updated', this.handleCacbotEvent.bind(this)), this.isInitialized = !0, console.log('CacbotData: Event monitoring initialized - listening for cacbot-data-updated events'), document.addEventListener('DOMContentLoaded', function() {
                    console.log('CacbotData: DOM loaded, checking for existing comment data...'), t.checkForInitialCommentData();
                }));
            }
        },
        {
            key: "checkForInitialCommentData",
            value: function() {
                if (console.log('CacbotData: Checking for initial comment data...'), console.log('CacbotData: Current data keys:', Object.keys(this.data)), console.log('CacbotData: Has comments in data:', this.has('comments')), this.has('comments')) {
                    var t = this.get('comments');
                    console.log('CacbotData: Found initial comments:', t), this.notifyCommentSubscribers({
                        comments: t,
                        comment_count: t.length
                    });
                } else console.log('CacbotData: No initial comment data found');
            }
        },
        {
            key: "handleCacbotEvent",
            value: function(t) {
                if (console.log('CacbotData: Received cacbot-data-updated event', t), !t.detail) return void console.warn('CacbotData: Event has no detail data');
                var n = t.detail;
                console.log('CacbotData: Processing new data', {
                    hasComments: !!n.comments,
                    commentCount: n.comment_count || 0,
                    dataKeys: Object.keys(n),
                    postId: n.post_id,
                    commentsData: n.comments
                }), this.updateData(n), this.hasCommentChanges(n) ? (console.log('CacbotData: Comment changes detected, notifying subscribers'), this.notifyCommentSubscribers(n)) : console.log('CacbotData: No comment changes detected');
            }
        },
        {
            key: "updateData",
            value: function(t) {
                this.data = _object_spread({}, this.data, t), this.updateCommentTracking(t);
            }
        },
        {
            key: "hasCommentChanges",
            value: function(t) {
                var n = this;
                if (!t.comments || !Array.isArray(t.comments)) return console.log('CacbotData: No comments array in data or not an array', {
                    hasComments: !!t.comments,
                    isArray: Array.isArray(t.comments),
                    commentsType: _type_of(t.comments)
                }), !1;
                var e = this.transformWordPressComments(t.comments);
                t.comments = e;
                var o = t.comment_count || 0, a = new Set(e.map(function(t) {
                    return t.comment_ID;
                }));
                if (console.log('CacbotData: Comment change detection', {
                    newCommentCount: o,
                    lastCommentCount: this.lastCommentCount,
                    newCommentIds: Array.from(a),
                    lastCommentIds: Array.from(this.lastCommentIds),
                    transformedComments: e
                }), o !== this.lastCommentCount) return console.log('CacbotData: Comment count changed', {
                    old: this.lastCommentCount,
                    new: o
                }), !0;
                var i = _to_consumable_array(a).some(function(t) {
                    return !n.lastCommentIds.has(t);
                }), s = _to_consumable_array(this.lastCommentIds).some(function(t) {
                    return !a.has(t);
                });
                return (!!i || !!s) && (console.log('CacbotData: Comment composition changed', {
                    newComments: i,
                    removedComments: s,
                    newCommentsList: _to_consumable_array(a).filter(function(t) {
                        return !n.lastCommentIds.has(t);
                    }),
                    removedCommentsList: _to_consumable_array(this.lastCommentIds).filter(function(t) {
                        return !a.has(t);
                    })
                }), !0);
            }
        },
        {
            key: "transformWordPressComments",
            value: function(t) {
                return t.map(function(t) {
                    var n = {
                        comment_ID: t.comment_ID || t.id || t.ID,
                        comment_content: t.comment_content || t.content,
                        comment_author: t.comment_author || t.author_name || 'Anonymous',
                        comment_author_email: t.comment_author_email || t.author_email || '',
                        comment_date: t.comment_date || t.date,
                        user_id: t.user_id || t.author || '0',
                        post_id: t.comment_post_ID || t.post_id || t.post
                    };
                    return console.log('CacbotData: Transforming comment', {
                        original: t,
                        transformed: n
                    }), n;
                });
            }
        },
        {
            key: "updateCommentTracking",
            value: function(t) {
                t.comments && Array.isArray(t.comments) && (this.lastCommentCount = t.comment_count || 0, this.lastCommentIds = new Set(t.comments.map(function(t) {
                    return t.comment_ID;
                })), console.log('CacbotData: Updated comment tracking', {
                    commentCount: this.lastCommentCount,
                    commentIds: Array.from(this.lastCommentIds)
                }));
            }
        },
        {
            key: "subscribeToComments",
            value: function(t) {
                if ('function' != typeof t) throw Error('Callback must be a function');
                this.commentSubscribers.add(t), console.log('CacbotData: Comment subscriber added');
            }
        },
        {
            key: "unsubscribeFromComments",
            value: function(t) {
                this.commentSubscribers.delete(t), console.log('CacbotData: Comment subscriber removed');
            }
        },
        {
            key: "notifyCommentSubscribers",
            value: function(t) {
                this.commentSubscribers.forEach(function(n) {
                    try {
                        n(t.comments, t.comment_count);
                    } catch (t) {
                        console.error('CacbotData: Error notifying comment subscriber:', t);
                    }
                });
            }
        }
    ]), t;
}())();
'loading' === document.readyState ? document.addEventListener('DOMContentLoaded', function() {
    t3.initializeEventMonitoring(), window.cacbot_data && (console.log('CacbotData: Initializing with existing window.cacbot_data:', window.cacbot_data), t3.initialize(window.cacbot_data));
}) : (t3.initializeEventMonitoring(), window.cacbot_data && (console.log('CacbotData: Initializing with existing window.cacbot_data:', window.cacbot_data), t3.initialize(window.cacbot_data))), window.cacbotData = t3;
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
function disableDropdownMenu(n) {
    var o, e, t, a = (e = (o = n).cloneNode(!0), o.parentNode.replaceChild(e, o), e);
    return (t = document.createElement('style')).textContent = "\n      #wp-admin-bar-new-content .ab-sub-wrapper {\n        display: none !important;\n      }\n      #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n        display: none !important;\n      }\n    ", document.head.appendChild(t), a;
}
function setupArchiveClickHandler(n) {
    var o, e = ((o = n.querySelector('a.ab-item')) || console.warn('Admin bar "New" button link not found'), o);
    e && e.addEventListener('click', t4);
}
function t4(t) {
    t.preventDefault(), t.stopPropagation(), console.log('New button clicked'), clearMessages();
    var a, r1, i, c = t3.getPostId(), s = AIStyleSettings.nonce;
    console.log("nonce:", s), c && s ? (a = c, r1 = s, (i = new FormData()).append('post_id', a), i.append('nonce', r1), r(a, i, "/wp-json/cacbot/v1/unlink-conversation").then(function(n) {
        console.log('Archive conversation response:', n), window.location.reload();
    }).catch(function(n) {
        console.error('Error archiving conversation:', n);
    })) : console.warn('Cannot archive conversation: Missing post_id or nonce');
}
function a1() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log(AIStyleSettings), console.log('Customizing admin bar "New" button behavior');
        var n = document.getElementById('wp-admin-bar-new-content');
        if (!n) return void console.warn('Admin bar "New" button not found');
        setupArchiveClickHandler(disableDropdownMenu(n));
    }
}
var e2 = {
    isVisible: !0,
    isAnimating: !1,
    originalWidth: '377px',
    isMobileView: !1,
    isDesktopView: !0,
    resizeTimeout: null
}, i1 = 'ai_style_sidebar_visible';
function t5() {
    try {
        localStorage.setItem(i1, e2.isVisible.toString()), console.log('Sidebar state saved to localStorage:', e2.isVisible);
    } catch (e) {
        console.warn('Failed to save sidebar state to localStorage:', e);
    }
}
function initToggleSidebar() {
    console.log('Initializing toggle sidebar functionality'), a2(), console.log('Responsive modes detected:', {
        isMobileView: e2.isMobileView,
        isDesktopView: e2.isDesktopView
    }), function(i) {
        var s, n, o = document.getElementById('chat-sidebar');
        if (!o) return console.warn('Sidebar element not found');
        e2.originalWidth = window.getComputedStyle(o).width, null !== i ? console.log('Using saved sidebar state:', n = i) : console.log('Using responsive logic for sidebar state:', n = e2.isDesktopView, 'Mode:', e2.isDesktopView ? 'desktop' : 'mobile'), e2.isVisible = n, n ? function(i) {
            if (i.classList.remove('sidebar-hidden'), e2.isMobileView) i.classList.add('sidebar-visible'), i.style.left = '0';
            else {
                var t = l();
                i.style.width = t, i.style.minWidth = t, i.style.paddingLeft = '16px', i.style.paddingRight = '16px', b(!1);
            }
            i.style.overflow = 'auto';
        }(o) : (s = o, e2.isMobileView ? (s.classList.remove('sidebar-visible'), s.style.left = '-100%') : (s.style.width = '0', s.style.minWidth = '0', s.style.paddingLeft = '0', s.style.paddingRight = '0', s.classList.add('sidebar-hidden'), b(!0)), s.style.overflow = 'hidden'), t5();
    }(function() {
        try {
            var e = localStorage.getItem(i1);
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
    }(), window.addEventListener('resize', s1), console.log('Toggle sidebar initialized with state:', e2);
}
function s1() {
    clearTimeout(e2.resizeTimeout), e2.resizeTimeout = setTimeout(function() {
        if (a2()) {
            console.log('Responsive mode changed on resize:', {
                isMobileView: e2.isMobileView,
                isDesktopView: e2.isDesktopView
            });
            var i = document.getElementById('chat-sidebar');
            if (i) if (e2.isMobileView) console.log('Switching to mobile overlay mode'), e2.isVisible ? (i.classList.add('sidebar-visible'), i.style.left = '0') : (i.classList.remove('sidebar-visible'), i.style.left = '-100%'), i.style.width = '', i.style.minWidth = '', i.style.position = '';
            else if (console.log('Switching to desktop push/shrink mode'), i.classList.remove('sidebar-visible'), i.style.left = '', e2.isVisible) {
                var t = l();
                i.style.width = t, i.style.minWidth = t, i.classList.remove('sidebar-hidden');
            } else i.style.width = '0', i.style.minWidth = '0', i.classList.add('sidebar-hidden');
        }
    }, 250);
}
function n2() {
    return window.innerWidth < 782;
}
function o1() {
    return window.innerWidth >= 782;
}
function a2() {
    var i = e2.isMobileView, t = e2.isDesktopView;
    e2.isMobileView = n2(), e2.isDesktopView = o1();
    var s = i !== e2.isMobileView || t !== e2.isDesktopView;
    return console.log('Responsive mode updated:', {
        isMobileView: e2.isMobileView,
        isDesktopView: e2.isDesktopView,
        modeChanged: s
    }), s;
}
function l() {
    return e2.originalWidth;
}
function toggleSidebarVisibility() {
    if (e2.isAnimating) return void console.log('Sidebar animation in progress, ignoring toggle request');
    var i = document.getElementById('chat-sidebar');
    if (!i) return void console.warn('Sidebar element not found');
    e2.isAnimating = !0, a2(), e2.isVisible ? d(i) : r1(i), e2.isVisible = !e2.isVisible, t5(), updateToggleButton(), console.log('Toggled sidebar visibility. New state:', e2.isVisible ? 'visible' : 'hidden', 'Mode:', e2.isMobileView ? 'mobile' : 'desktop');
}
function d(i) {
    i.classList.add('sidebar-transitioning'), e2.isMobileView ? (i.classList.remove('sidebar-visible'), i.style.left = '-100%') : (i.style.width = '0', i.style.minWidth = '0', i.style.paddingLeft = '0', i.style.paddingRight = '0', b(!0)), i.style.overflow = 'hidden', setTimeout(function() {
        i.classList.remove('sidebar-transitioning'), e2.isMobileView || i.classList.add('sidebar-hidden'), e2.isAnimating = !1;
    }, 300);
}
function r1(i) {
    if (i.classList.remove('sidebar-hidden'), i.classList.add('sidebar-transitioning'), e2.isMobileView) i.classList.add('sidebar-visible'), i.style.left = '0';
    else {
        var t = l();
        i.style.width = t, i.style.minWidth = t, i.style.paddingLeft = '16px', i.style.paddingRight = '16px', b(!1);
    }
    i.style.overflow = 'hidden', setTimeout(function() {
        i.classList.remove('sidebar-transitioning'), i.style.overflow = 'auto', e2.isAnimating = !1;
    }, 300);
}
function b(e) {
    var i = document.querySelector('.site-footer'), t = document.getElementById('fixed-content');
    if (!i) return void console.warn('Footer element not found');
    e ? (i.style.left = '0px', console.log('Footer position updated: left = 0px (sidebar hidden)'), t && (t.style.left = '0px', console.log('Fixed-content position updated: left = 0px (sidebar hidden)'))) : (i.style.left = '377px', console.log('Footer position updated: left = 377px (sidebar visible)'), t && (t.style.left = '377px', console.log('Fixed-content position updated: left = 377px (sidebar visible)')));
}
function updateToggleButton() {
    var i = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : null, t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : null, s = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : null, n = null !== s ? s : e2.isVisible, o = i || document.querySelector('#wp-admin-bar-sidebar-toggle .dashicons'), a = t || document.querySelector('#wp-admin-bar-sidebar-toggle .ab-label');
    if (!o || !a) return void console.warn('Toggle button elements not found for state update');
    o.classList.remove('dashicons-arrow-left', 'dashicons-arrow-right'), n ? (o.classList.add('dashicons-arrow-left'), o.setAttribute('title', 'Close Sidebar'), a.textContent = 'Close Sidebar') : (o.classList.add('dashicons-arrow-right'), o.setAttribute('title', 'Open Sidebar'), a.textContent = 'Open Sidebar'), console.log('Updated toggle button state:', n ? 'arrow-left (close)' : 'arrow-right (open)');
}
function isSidebarVisible() {
    return e2.isVisible;
}
function showSidebar() {
    if (!e2.isVisible) {
        var i = document.getElementById('chat-sidebar');
        i && (r1(i), e2.isVisible = !0, t5(), updateToggleButton());
    }
}
function hideSidebar() {
    if (e2.isVisible) {
        var i = document.getElementById('chat-sidebar');
        i && (d(i), e2.isVisible = !1, t5(), updateToggleButton());
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
var o2 = {
    button: null,
    icon: null,
    label: null,
    isInitialized: !1
};
function initializeArrowToggleButton() {
    if (console.log('Initializing desktop arrow toggle button'), o2.isInitialized) return void console.log('Arrow toggle button already initialized');
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
    a.className = 'ab-label', o2.button = l, o2.icon = r, o2.label = a, i.appendChild(r), i.appendChild(a), l.appendChild(i);
    var u = t.nextSibling;
    u ? e.insertBefore(l, u) : e.appendChild(l), i.addEventListener('click', n3), o2.isInitialized = !0, updateArrowToggleButton(), console.log('Desktop arrow toggle button initialized successfully');
}
function n3(e) {
    e.preventDefault(), e.stopPropagation(), console.log('Desktop arrow toggle button clicked'), toggleSidebarVisibility(), updateArrowToggleButton();
}
function updateArrowToggleButton() {
    if (!o2.isInitialized || !o2.icon || !o2.label) return void console.warn('Arrow toggle button not initialized or elements missing');
    updateToggleButton(o2.icon, o2.label), console.log('Desktop arrow toggle button state updated');
}
function o3(o) {
    if ((o = parseInt(o, 10)) === parseInt(t3.get('linked_post_id'), 10)) return void console.log('Clicked post is already the current linked post');
    var e = t3.getPostId(), i = AIStyleSettings.nonce, c = new FormData();
    c.append('nonce', i), c.append('post_id', e), c.append('linked_post_id', o), r(e, c, "/wp-json/cacbot/v1/link-conversation").then(function(t) {
        console.log('conversation successfully linked:', t), window.location.reload();
    }).catch(function(t) {
        console.error('Error linking conversation!:', t);
    });
}
function initSidebarClickListeners() {
    console.log("initSidebarClickListerners"), document.querySelectorAll('.anchor-post-list li a').forEach(function(t) {
        t.addEventListener('click', function(t) {
            t.preventDefault(), o3(this.closest('li').getAttribute('data-post-id'));
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
function t6(t) {
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
function o4() {
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
                    s && (s.innerHTML = '<div class="loading">Loading post content...</div>'), t6(a).then(function(t) {
                        updatePostUI(t), window.scrollTo(0, 0);
                    }).catch(function(t) {
                        console.error('Error navigating to post:', t), s && (s.innerHTML = '<div class="error">Error loading post: '.concat(t.message, "</div>"));
                    });
                }
            }
        }
    }), window.addEventListener('popstate', function(o) {
        o.state && o.state.postId && t6(o.state.postId).then(updatePostUI).catch(function(t) {
            console.error('Error handling history navigation:', t);
        });
    });
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t1(), e();
    try {
        t3.initialize(window.cacbot_data || {}), window.cacbotData = t3, console.log(t3.getAll());
    } catch (o) {
        console.error("Failed to initialize cacbotData:", o);
    }
    window.addInterlocutorMessage = addInterlocutorMessage, window.addRespondentMessage = addRespondentMessage, window.clearMessages = clearMessages, console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), console.log('- clearMessages()'), initToggleSidebar(), window.toggleSidebarVisibility = toggleSidebarVisibility, window.isSidebarVisible = isSidebarVisible, window.showSidebar = showSidebar, window.hideSidebar = hideSidebar, a1(), initializeArrowToggleButton(), addMobileHamburgerIcon(), o4(), initSidebarClickListeners(), console.log('Toggle sidebar functions are available globally:'), console.log('- toggleSidebarVisibility()'), console.log('- isSidebarVisible()'), console.log('- showSidebar()'), console.log('- hideSidebar()'), console.log('Admin bar customization functions are available globally:'), console.log('- disableDropdownMenu(newButton)'), console.log('- setupArchiveClickHandler(newButton)'), console.log('Toggle button functions initialized:'), console.log('- Desktop arrow toggle button initialized'), console.log('- Mobile hamburger button initialized'), window.resetCommentSubmitButton = resetCommentSubmitButton, window.addActionBubble = addActionBubble, window.createActionButtonsContainer = createActionButtonsContainer, window.refreshButtonVisibility = refreshButtonVisibility, e3(), console.log('Comment form functions are available globally:'), console.log('- resetCommentSubmitButton()'), console.log('- addActionBubble(dashiconClass, text, container, options)'), console.log('- createActionButtonsContainer(commentForm)'), console.log('- refreshButtonVisibility(data)');
});
