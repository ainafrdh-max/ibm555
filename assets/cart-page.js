function initCartPage(config) {
  let baseSubtotal = config.subtotal;
  let discountPct = 0;
  let voucherDiscountAmt = 0;
  let pointsUsed = 0;
  let pointsDiscountAmt = 0;
  const pointsPerRm = config.pointsPerRm || 100;

  function voucherDiscount() {
    return parseFloat((baseSubtotal * discountPct / 100).toFixed(2));
  }

  function updateSummary() {
    const sub = baseSubtotal;
    const vDisc = voucherDiscount();
    voucherDiscountAmt = vDisc;
    const afterVoucher = Math.max(0, sub - vDisc);
    const maxPts = Math.min(config.pointsBalance, Math.floor(afterVoucher * pointsPerRm));
    const slider = document.getElementById('pointsSlider');
    if (slider) {
      slider.max = maxPts;
      if (parseInt(slider.value, 10) > maxPts) {
        slider.value = maxPts;
        pointsUsed = maxPts;
        pointsDiscountAmt = maxPts / pointsPerRm;
        document.getElementById('hiddenPointsRedeemed').value = pointsUsed;
        document.getElementById('hiddenPointsDiscount').value = pointsDiscountAmt.toFixed(2);
      }
    }
    const afterAll = Math.max(0, afterVoucher - pointsDiscountAmt);
    const tax = parseFloat((afterAll * 0.06).toFixed(2));
    const total = afterAll + tax;

    document.getElementById('sumSubtotal').textContent = sub.toFixed(2);
    document.getElementById('sumTax').textContent = tax.toFixed(2);
    document.getElementById('sumTotal').textContent = total.toFixed(2);
    document.getElementById('earnPts').textContent = Math.floor(sub);

    const dr = document.getElementById('discountRow');
    if (discountPct > 0) {
      dr.style.display = '';
      document.getElementById('discPct').textContent = discountPct;
      document.getElementById('discAmt').textContent = vDisc.toFixed(2);
    } else {
      dr.style.display = 'none';
    }
    document.getElementById('hiddenDiscount').value = vDisc.toFixed(2);

    const pr = document.getElementById('pointsRow');
    if (pr) {
      if (pointsDiscountAmt > 0) {
        pr.style.display = '';
        document.getElementById('pointsDiscAmt').textContent = pointsDiscountAmt.toFixed(2);
      } else {
        pr.style.display = 'none';
      }
    }
  }

  window.selectVoucher = function (code) {
    document.getElementById('voucherCode').value = code;
    applyVoucher();
  };

  window.onPointsSlider = function (val) {
    const pts = parseInt(val, 10) || 0;
    const rm = (pts / pointsPerRm).toFixed(2);
    document.getElementById('pointsSliderLabel').textContent = pts + ' pts (RM ' + rm + ')';
  };

  window.applyPoints = function () {
    const slider = document.getElementById('pointsSlider');
    const pts = parseInt(slider?.value || 0, 10);
    const msgEl = document.getElementById('pointsMsg');
    const vDisc = voucherDiscount();

    fetch('apply_points.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `points=${pts}&subtotal=${baseSubtotal}&voucher_discount=${vDisc}`,
    })
      .then((r) => r.json())
      .then((data) => {
        if (data.success) {
          pointsUsed = data.points_used;
          pointsDiscountAmt = parseFloat(data.points_discount);
          document.getElementById('hiddenPointsRedeemed').value = pointsUsed;
          document.getElementById('hiddenPointsDiscount').value = pointsDiscountAmt.toFixed(2);
          msgEl.innerHTML = `<span class="voucher-ok">✓ ${data.message}</span>`;
          updateSummary();
        } else {
          pointsUsed = 0;
          pointsDiscountAmt = 0;
          document.getElementById('hiddenPointsRedeemed').value = 0;
          document.getElementById('hiddenPointsDiscount').value = '0';
          msgEl.innerHTML = `<span class="voucher-err">✗ ${data.error}</span>`;
          updateSummary();
        }
      });
  };

  window.applyVoucher = function () {
    const code = document.getElementById('voucherCode').value.trim();
    const msgEl = document.getElementById('voucherMsg');
    if (!code) {
      msgEl.innerHTML = '<span class="voucher-err">Please enter a voucher code.</span>';
      return;
    }

    fetch('apply_voucher.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `code=${encodeURIComponent(code)}&subtotal=${baseSubtotal}`,
    })
      .then((r) => r.json())
      .then((data) => {
        if (data.success) {
          discountPct = data.discount_percent;
          document.getElementById('hiddenVoucher').value = code.toUpperCase();
          document.getElementById('hiddenDiscountPct').value = discountPct;
          msgEl.innerHTML = `<span class="voucher-ok">✓ ${data.message}</span>`;
          updateSummary();
        } else {
          discountPct = 0;
          document.getElementById('hiddenVoucher').value = '';
          msgEl.innerHTML = `<span class="voucher-err">✗ ${data.error}</span>`;
          updateSummary();
        }
      });
  };

  function cartFetch(body) {
    return fetch('cart_action.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body,
    })
      .then((r) => r.json())
      .then((data) => {
        if (data.error) alert(data.error);
        if (typeof data.cart_count !== 'undefined' && typeof updateNavCartCount === 'function') {
          updateNavCartCount(data.cart_count);
        }
        return data;
      });
  }

  window.changeQty = function (cartId, delta, price) {
    const qtyEl = document.getElementById('qty-' + cartId);
    let qty = parseInt(qtyEl.textContent, 10) + delta;
    if (qty < 1) {
      removeItem(cartId);
      return;
    }
    qtyEl.textContent = qty;
    document.getElementById('line-' + cartId).textContent = (price * qty).toFixed(2);

    let newSub = 0;
    document.querySelectorAll('[id^="qty-"]').forEach((el) => {
      const cid = el.id.replace('qty-', '');
      const lineEl = document.getElementById('line-' + cid);
      if (lineEl) newSub += parseFloat(lineEl.textContent);
    });
    baseSubtotal = parseFloat(newSub.toFixed(2));
    updateSummary();
    cartFetch(`action=update&cart_id=${cartId}&quantity=${qty}`);
  };

  window.removeItem = function (cartId) {
    const row = document.getElementById('row-' + cartId);
    if (!row) return;
    const lineEl = document.getElementById('line-' + cartId);
    if (lineEl) baseSubtotal = parseFloat((baseSubtotal - parseFloat(lineEl.textContent)).toFixed(2));
    row.style.opacity = '0';
    setTimeout(() => {
      row.remove();
      if (!document.querySelector('.cart-item')) location.reload();
      updateSummary();
    }, 300);
    cartFetch(`action=remove&cart_id=${cartId}`);
  };

  updateSummary();
}
