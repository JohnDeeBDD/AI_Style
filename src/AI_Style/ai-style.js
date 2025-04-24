function t() {
    console.log("Applying ChatGPT-style to comment form");
    var t = document.getElementById('comment');
    t && (t.placeholder = 'Ask anything', t.addEventListener('input', function() {
        this.style.height = 'auto', this.style.height = this.scrollHeight + 'px';
    }));
    var e = document.getElementById('commentform');
    if (e) {
        e.addEventListener('click', function(n) {
            n.target === e && t.focus();
        });
        var n = document.querySelector('.form-submit input[type="submit"]');
        n && (n.value = 'SUBMIT');
    }
}
function o() {
    console.log("Template function loaded!");
}
function e() {
    console.log("Enabling independent scrolling for chat divs");
    var e = function() {
        var e = document.getElementById('chat-sidebar'), t = document.getElementById('chat-main'), n = document.getElementById('chat-messages');
        if (!e || !t) return void console.warn("Chat sidebar or main div not found");
        e.style.overflowY = 'auto', t.style.overflowY = 'auto', window.innerWidth <= 768 ? (e.style.height = '40vh', t.style.height = '50vh', e.dataset.heightUnit = 'vh', t.dataset.heightUnit = 'vh') : (e.style.height = '400px', t.style.height = '400px', e.dataset.heightUnit = 'px', t.dataset.heightUnit = 'px'), n && (n.style.minHeight = '800px');
        var i = function(e) {
            e.addEventListener('wheel', function(t) {
                (!(t.deltaY < 0) || !(e.scrollTop <= 0)) && (t.deltaY > 0 && e.scrollTop + e.clientHeight >= e.scrollHeight || (t.preventDefault(), e.scrollTop += t.deltaY));
            });
        };
        i(e), i(t), console.log("Independent scrolling enabled");
    };
    e(), setTimeout(e, 100), window.addEventListener('resize', e), window.getHeightUnit = function(e) {
        var t = document.getElementById(e);
        if (!t) return '';
        if (t.dataset.heightUnit) return t.dataset.heightUnit;
        var n = t.style.height;
        return n.includes('vh') ? 'vh' : n.includes('px') ? 'px' : n.includes('%') ? '%' : '';
    };
}
console.log('ai-style.js'), t(), o(), e();
