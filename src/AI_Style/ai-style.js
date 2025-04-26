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
function e() {
    console.log("Enabling independent scrolling for chat elements"), document.body.style.overflow = 'hidden';
    var e = document.getElementById('chat-sidebar');
    e && (e.style.overflowY = 'auto', e.style.height = '100%', e.addEventListener('wheel', function(e) {
        var t = 0 === this.scrollTop, l = this.scrollHeight - this.scrollTop === this.clientHeight;
        t && e.deltaY < 0 || l && e.deltaY > 0 || e.stopPropagation();
    }, {
        passive: !1
    }), e.__wheelListenerAttached = !0);
    var t = document.getElementById('chat-main');
    t && (t.style.overflowY = 'auto', t.style.height = '100%', t.addEventListener('wheel', function(e) {
        var t = 0 === this.scrollTop, l = this.scrollHeight - this.scrollTop === this.clientHeight;
        t && e.deltaY < 0 || l && e.deltaY > 0 || e.stopPropagation();
    }, {
        passive: !1
    }), t.__wheelListenerAttached = !0);
    var l = document.getElementById('chat-messages');
    l && (l.style.overflowY = 'auto', l.style.flex = '1 1 auto');
    var o = document.getElementById('chat-input');
    o && (o.style.position = 'sticky', o.style.bottom = '0', o.style.zIndex = '10', o.style.backgroundColor = '#343541');
}
console.log('ai-style.js'), t(), o(), e();
