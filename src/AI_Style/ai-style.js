function t() {
    console.log("Applying ChatGPT-style to comment form");
    var t = document.getElementById('comment');
    t && (t.rows = 1, t.style.height = 'auto', t.addEventListener('input', function() {
        this.style.height = 'auto', this.style.height = this.scrollHeight + 'px';
    }));
    var e = document.getElementById('commentform');
    e && e.addEventListener('click', function(n) {
        n.target === e && t.focus();
    });
}
function e() {
    if (document.querySelectorAll('#chat-messages .message').length > 0) {
        var e = document.getElementById('main-call-to-action-1');
        e && (e.style.display = 'none');
    }
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
function addInterlocutorMessage(n) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var s = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message interlocutor-message', a.id = s;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + s, o.innerHTML = n, a.appendChild(o), t.appendChild(a), e2();
}
function addRespondentMessage(n) {
    var t = document.getElementById('chat-messages');
    if (!t) return void console.error('Chat messages container not found');
    var s = 'message-' + Date.now(), a = document.createElement('div');
    a.className = 'message respondent-message', a.id = s;
    var o = document.createElement('div');
    o.className = 'message-content', o.id = 'message-content-' + s, o.innerHTML = n, a.appendChild(o), t.appendChild(a), e2();
}
function e2() {
    var e = document.querySelector('.scrollable-content');
    e && (e.scrollTop = e.scrollHeight);
}
const __default = {
    addInterlocutorMessage: addInterlocutorMessage,
    addRespondentMessage: addRespondentMessage
};
window.addInterlocutorMessage = __default.addInterlocutorMessage, window.addRespondentMessage = __default.addRespondentMessage, document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t(), e(), e1(), console.log(cacbot_data), console.log('Chat message functions are available globally:'), console.log('- addInterlocutorMessage(message)'), console.log('- addRespondentMessage(message)');
});
