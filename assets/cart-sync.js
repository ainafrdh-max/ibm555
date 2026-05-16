function updateNavCartCount(count) {
  const n = parseInt(count, 10) || 0;

  document.querySelectorAll('[data-cart-count]').forEach((el) => {
    el.textContent = n;
    el.style.display = n > 0 ? 'flex' : 'none';
  });

  document.querySelectorAll('[data-cart-count-text]').forEach((el) => {
    el.textContent = n > 0 ? `(${n})` : '';
  });

  const badge = document.getElementById('cartBadge');
  if (badge) {
    badge.textContent = n;
  }
}

function syncCartFromServer() {
  return fetch('cart_action.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=count',
  })
    .then((r) => r.json())
    .then((data) => {
      if (typeof data.cart_count !== 'undefined') {
        updateNavCartCount(data.cart_count);
      }
      return data;
    })
    .catch(() => null);
}
