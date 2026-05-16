<div id="chatWidget" class="chat-widget">
  <button type="button" class="chat-toggle" id="chatToggle" aria-label="Open chat">
    <i class="bi bi-chat-dots-fill"></i>
  </button>
  <div class="chat-panel" id="chatPanel">
    <div class="chat-header">
      <div>
        <strong>Blank Assistant</strong>
        <small>Products · Payment · Vouchers · FAQ</small>
      </div>
      <button type="button" class="chat-close" id="chatClose" aria-label="Close">×</button>
    </div>
    <div class="chat-messages" id="chatMessages">
      <div class="chat-bubble bot">
        Hi! Ask me about products, prices, payment methods, promo codes, points, or shipping. Try “What vouchers can I use?”
      </div>
    </div>
    <form class="chat-form" id="chatForm">
      <input type="text" id="chatInput" placeholder="Type your question…" autocomplete="off" required>
      <button type="submit" aria-label="Send"><i class="bi bi-send-fill"></i></button>
    </form>
  </div>
</div>

<style>
  .chat-widget { position: fixed; bottom: 24px; left: 24px; z-index: 10000; font-family: inherit; }
  .chat-toggle {
    width: 56px; height: 56px; border-radius: 50%; border: none;
    background: #000; color: #e8f7d0; font-size: 22px;
    box-shadow: 0 8px 28px rgba(0,0,0,.25); cursor: pointer; transition: .2s;
  }
  .chat-toggle:hover { transform: scale(1.05); background: #222; }
  .chat-panel {
    display: none; position: absolute; bottom: 70px; left: 0;
    width: min(360px, calc(100vw - 48px)); height: 460px;
    background: #fff; border-radius: 20px;
    box-shadow: 0 16px 50px rgba(0,0,0,.18);
    flex-direction: column; overflow: hidden; border: 1.5px solid #eee;
  }
  .chat-panel.open { display: flex; }
  .chat-header {
    background: #e8f7d0; padding: 14px 16px; display: flex;
    justify-content: space-between; align-items: center;
  }
  .chat-header strong { display: block; font-size: 14px; }
  .chat-header small { font-size: 11px; color: #555; }
  .chat-close { border: none; background: none; font-size: 22px; cursor: pointer; line-height: 1; }
  .chat-messages { flex: 1; overflow-y: auto; padding: 14px; display: flex; flex-direction: column; gap: 10px; }
  .chat-bubble {
    max-width: 88%; padding: 10px 14px; border-radius: 14px; font-size: 13px; line-height: 1.55;
  }
  .chat-bubble.bot { background: #f5f5f5; align-self: flex-start; border-bottom-left-radius: 4px; }
  .chat-bubble.user { background: #000; color: #fff; align-self: flex-end; border-bottom-right-radius: 4px; }
  .chat-bubble a { color: inherit; font-weight: 600; }
  .chat-bubble.bot a { color: #000; }
  .chat-form { display: flex; gap: 8px; padding: 12px; border-top: 1px solid #eee; }
  .chat-form input {
    flex: 1; border: 1.5px solid #e5e5e5; border-radius: 999px;
    padding: 10px 16px; font-size: 13px; outline: none;
  }
  .chat-form input:focus { border-color: #000; }
  .chat-form button {
    width: 42px; height: 42px; border-radius: 50%; border: none;
    background: #000; color: #fff; cursor: pointer;
  }
  .chat-typing { font-size: 12px; color: #999; padding: 0 14px 8px; }
  @media (max-width: 576px) {
    .chat-widget { bottom: 16px; left: 16px; }
    .chat-panel { height: 70vh; }
  }
</style>

<script>
(function () {
  const toggle = document.getElementById('chatToggle');
  const panel = document.getElementById('chatPanel');
  const closeBtn = document.getElementById('chatClose');
  const form = document.getElementById('chatForm');
  const input = document.getElementById('chatInput');
  const messages = document.getElementById('chatMessages');

  if (!toggle || !panel) return;

  toggle.addEventListener('click', () => panel.classList.toggle('open'));
  closeBtn?.addEventListener('click', () => panel.classList.remove('open'));

  function addBubble(text, type) {
    const div = document.createElement("div");
    div.className = 'chat-bubble ' + type;
    div.innerHTML = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;
    addBubble(text.replace(/</g, '&lt;'), 'user');
    input.value = '';

    const typing = document.createElement('div');
    typing.className = 'chat-typing';
    typing.textContent = 'Typing…';
    messages.appendChild(typing);

    try {
      const res = await fetch('api/chat.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(text),
      });
      const data = await res.json();
      typing.remove();
      addBubble(data.reply || 'Sorry, something went wrong.', 'bot');
    } catch {
      typing.remove();
      addBubble('Connection error. Please try again.', 'bot');
    }
  });
})();
</script>
