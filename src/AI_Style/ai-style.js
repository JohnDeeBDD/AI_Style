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
function e() {
    if (document.querySelectorAll('#chat-messages .message').length > 0) {
        var e = document.getElementById('main-call-to-action-1');
        e && (e.style.display = 'none');
    }
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('ai-style.js is loaded!'), t(), e(), console.log(cacbot_data), console.log(cacbot_data);
});
