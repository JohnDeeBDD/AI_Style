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
function addInterlocutorMessage(n) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var s = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message interlocutor-message', a.id = s;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + s, o.innerHTML = n, a.appendChild(o), t.appendChild(a), e3();
}
function addRespondentMessage(n) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var s = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message respondent-message', a.id = s;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + s, o.innerHTML = n, a.appendChild(o), t.appendChild(a), e3();
}
function e3() {
    var e = document.querySelector('.scrollable-content');
    e && (e.scrollTop = e.scrollHeight);
}
const __default = {
    addInterlocutorMessage: addInterlocutorMessage,
    addRespondentMessage: addRespondentMessage
};
window.addInterlocutorMessage = __default.addInterlocutorMessage, window.addRespondentMessage = __default.addRespondentMessage, document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t(), e1(), e2(), console.log(cacbot_data), console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)');
});
