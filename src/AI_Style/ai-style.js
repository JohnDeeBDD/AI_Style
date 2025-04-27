function t() {
    console.log("Applying ChatGPT-style to comment form");
    var t = document.getElementById('comment');
    t && (t.rows = 1, t.style.height = 'auto', t.addEventListener('input', function() {
        this.style.height = 'auto', this.style.height = this.scrollHeight + 'px';
    }));
    var e = document.getElementById('commentform');
    if (e) {
        e.addEventListener('click', function(o) {
            o.target === e && t.focus();
        });
        var o = document.querySelector('.form-submit input[type="submit"]');
        o && (o.value = 'SUBMIT');
    }
}
function o() {
    console.log("Template function loaded!");
}
function t1() {
    document.body.style.overflow = 'hidden';
    var t = document.getElementById('chat-sidebar');
    t && (t.style.overflowY = 'auto', t.style.height = '100%', t.addEventListener('wheel', function(t) {
        var e = 0 === this.scrollTop, o = this.scrollHeight - this.scrollTop === this.clientHeight;
        e && t.deltaY < 0 || o && t.deltaY > 0 || t.stopPropagation();
    }, {
        passive: !1
    }), t.__wheelListenerAttached = !0);
    var e = document.getElementById('chat-main');
    e && (e.style.overflowY = 'auto', e.style.height = '100%', e.addEventListener('wheel', function(t) {
        var e = 0 === this.scrollTop, o = this.scrollHeight - this.scrollTop === this.clientHeight;
        e && t.deltaY < 0 || o && t.deltaY > 0 || t.stopPropagation();
    }, {
        passive: !1
    }), e.__wheelListenerAttached = !0);
    var o = document.getElementById('chat-messages');
    o && (o.style.overflowY = 'auto', o.style.flex = '1 1 auto');
    var l = document.getElementById('chat-input');
    l && (l.style.position = 'sticky', l.style.bottom = '0', l.style.zIndex = '10', l.style.backgroundColor = '#343541');
}
function e() {
    function e() {
        var e = document.getElementById('chat-messages'), t = document.querySelector('.comment-respond');
        if (!e || !t) return void console.log("Required elements not found");
        var n = t.getBoundingClientRect();
        e.style.maxWidth = "".concat(n.width, "px"), e.style.width = '100%', e.style.height = "".concat(n.height, "px"), e.style.minHeight = "".concat(n.height, "px");
        var o = document.querySelectorAll('.respondent-message'), s = document.querySelectorAll('.interlocutor-message');
        o.forEach(function(e) {
            e.style.width = '100%';
        }), s.forEach(function(e) {
            e.style.width = '79%', e.style.maxWidth = '79%', e.style.marginLeft = 'auto';
            var t = e.parentElement;
            t && t.classList.contains('message') && (t.style.textAlign = 'right');
        }), console.log("Chat messages dimensions adjusted to match respond div");
    }
    console.log("Setting up columns for ChatGPT-style UI"), e(), window.addEventListener('resize', function() {
        e();
    });
    var t = document.getElementById('chat-main');
    t && new MutationObserver(function(t) {
        e();
    }).observe(t, {
        childList: !0,
        subtree: !0
    });
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js'), t(), o(), t1(), e(), console.log('Number of comments:', document.querySelectorAll('#chat-messages .message').length);
});
