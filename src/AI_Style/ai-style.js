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
function o() {
    console.log("enableCreateCacbotConversationFromUI function loaded!");
    var o = document.querySelector('#wp-admin-bar-new-cacbot-conversation a');
    if (!o) return void console.warn('Cacbot Conversation link not found in admin bar');
    o.addEventListener('click', function(o) {
        o.preventDefault(), console.log('Creating new Cacbot Conversation via AJAX...'), jQuery.ajax({
            url: '/wp-json/ai-style/cacbot-conversation',
            method: 'POST',
            beforeSend: function(o) {
                window.cacbot_data && window.cacbot_data.nonce && o.setRequestHeader('X-WP-Nonce', window.cacbot_data.nonce);
            },
            success: function(o) {
                console.log('Cacbot Conversation created successfully:', o), o.success && o.post_id ? window.location.href = "/wp-admin/post.php?post=".concat(o.post_id, "&action=edit") : (console.error('Invalid response from server:', o), alert('Error creating Cacbot Conversation. Please try again.'));
            },
            error: function(o, n, e) {
                console.error('Error creating Cacbot Conversation:', e), alert('Error creating Cacbot Conversation. Please try again.');
            }
        });
    });
}
function t1() {}
function n() {
    if (!document.body.classList.contains('wp-admin')) {
        console.log('Customizing admin bar "New" button behavior');
        var n, e, t, o = document.getElementById('wp-admin-bar-new-content');
        if (!o) return void console.warn('Admin bar "New" button not found');
        e = (n = o).cloneNode(!0), n.parentNode.replaceChild(e, n), (t = document.createElement('style')).textContent = "\n    #wp-admin-bar-new-content .ab-sub-wrapper {\n      display: none !important;\n    }\n    #wp-admin-bar-new-content:hover .ab-sub-wrapper {\n      display: none !important;\n    }\n  ", document.head.appendChild(t), function(n) {
            var e = n.querySelector('a.ab-item');
            if (!e) return console.warn('Admin bar "New" button link not found');
            e.addEventListener('click', function(n) {
                n.preventDefault(), console.log('New button clicked');
            });
        }(o);
    }
}
window.addInterlocutorMessage = __default.addInterlocutorMessage, window.addRespondentMessage = __default.addRespondentMessage, document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t(), e1(), e2(), console.log("Cacbot data:"), console.log(cacbot_data), o(), console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)'), t1(), n();
});
