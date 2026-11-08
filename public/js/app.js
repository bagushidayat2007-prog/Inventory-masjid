// Chart ringan tanpa library eksternal (canvas murni)
(function () {
  function drawBarChart(canvas, labels, datasets) {
    if (!canvas) return;
    const dpr = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    const W = rect.width || canvas.parentElement.clientWidth;
    const H = rect.height || 300;
    canvas.width = W * dpr;
    canvas.height = H * dpr;
    const ctx = canvas.getContext("2d");
    ctx.scale(dpr, dpr);
    ctx.clearRect(0, 0, W, H);

    const pad = { top: 20, right: 16, bottom: 40, left: 56 };
    const chartW = W - pad.left - pad.right;
    const chartH = H - pad.top - pad.bottom;

    let max = 0;
    datasets.forEach((ds) => ds.data.forEach((v) => (max = Math.max(max, v))));
    if (max <= 0) max = 1;
    // bulatkan ke atas
    const mag = Math.pow(10, Math.floor(Math.log10(max)));
    max = Math.ceil(max / mag) * mag;

    // grid + sumbu y
    ctx.strokeStyle = "#e2e6e4";
    ctx.fillStyle = "#9aa5a0";
    ctx.font = "11px Segoe UI, sans-serif";
    ctx.textAlign = "right";
    for (let i = 0; i <= 4; i++) {
      const y = pad.top + (chartH / 4) * i;
      ctx.beginPath();
      ctx.moveTo(pad.left, y);
      ctx.lineTo(W - pad.right, y);
      ctx.stroke();
      const val = max - (max / 4) * i;
      ctx.fillText(formatShort(val), pad.left - 8, y + 4);
    }

    // bars
    const groups = labels.length || 1;
    const groupW = chartW / groups;
    const dsCount = datasets.length;
    const barW = Math.min(28, (groupW * 0.6) / dsCount);

    datasets.forEach((ds, di) => {
      ctx.fillStyle = ds.color;
      labels.forEach((label, i) => {
        const val = ds.data[i] || 0;
        const h = (val / max) * chartH;
        const gx = pad.left + groupW * i + groupW / 2;
        const x = gx - (barW * dsCount) / 2 + di * barW;
        const y = pad.top + chartH - h;
        roundRect(ctx, x, y, barW - 4, h, 4);
        ctx.fill();
      });
    });

    // x labels
    ctx.fillStyle = "#5f6b66";
    ctx.textAlign = "center";
    ctx.font = "11px Segoe UI, sans-serif";
    labels.forEach((label, i) => {
      const gx = pad.left + groupW * i + groupW / 2;
      ctx.fillText(label, gx, H - pad.bottom + 18);
    });
  }

  function roundRect(ctx, x, y, w, h, r) {
    if (h < 1) h = 1;
    if (w < 1) w = 1;
    if (w < r * 2) r = w / 2;
    ctx.beginPath();
    ctx.moveTo(x + r, y);
    ctx.arcTo(x + w, y, x + w, y + h, r);
    ctx.arcTo(x + w, y + h, x, y + h, 0);
    ctx.arcTo(x, y + h, x, y, 0);
    ctx.arcTo(x, y, x + w, y, r);
    ctx.closePath();
  }

  function formatShort(v) {
    if (v >= 1000000) return (v / 1000000).toFixed(v % 1000000 === 0 ? 0 : 1) + "jt";
    if (v >= 1000) return (v / 1000).toFixed(v % 1000 === 0 ? 0 : 0) + "rb";
    return String(Math.round(v));
  }

  window.App = window.App || {};
  window.App.drawBarChart = drawBarChart;
})();

document.addEventListener("DOMContentLoaded", function () {
  // Sidebar toggle
  const toggle = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  if (toggle && sidebar) {
    toggle.addEventListener("click", function () {
      sidebar.classList.toggle("open");
      if (overlay) overlay.classList.toggle("show");
    });
  }
  if (overlay) {
    overlay.addEventListener("click", function () {
      sidebar.classList.remove("open");
      overlay.classList.remove("show");
    });
  }

  // Auto hide alerts
  document.querySelectorAll(".alert .close").forEach(function (btn) {
    btn.addEventListener("click", function () {
      btn.closest(".alert").style.display = "none";
    });
  });

  // Confirm delete modal
  const modal = document.getElementById("confirmModal");
  if (modal) {
    let pendingForm = null;
    const title = modal.querySelector("[data-confirm-title]");
    const msg = modal.querySelector("[data-confirm-message]");
    const ok = modal.querySelector("[data-confirm-ok]");

    document.querySelectorAll("[data-confirm]").forEach(function (el) {
      el.addEventListener("click", function (e) {
        e.preventDefault();
        pendingForm = el.closest("form") || document.querySelector(el.getAttribute("data-form"));

        // Nilai default: konfirmasi hapus
        if (title) title.textContent = el.getAttribute("data-confirm-title") || "Konfirmasi Hapus";
        if (msg) msg.textContent = el.getAttribute("data-confirm");
        if (ok) {
          ok.textContent = el.getAttribute("data-confirm-ok") || "Ya, Hapus";
          ok.className = "btn " + (el.getAttribute("data-confirm-variant") || "btn-danger");
        }

        modal.classList.add("show");
      });
    });
    const cancel = modal.querySelector("[data-confirm-cancel]");
    if (cancel) cancel.addEventListener("click", function () { modal.classList.remove("show"); });
    if (ok) ok.addEventListener("click", function () {
      if (pendingForm) {
        const submitBtn = pendingForm.querySelector("[type=submit]");
        if (submitBtn) submitBtn.classList.add("loading");
        pendingForm.submit();
      }
    });
    modal.addEventListener("click", function (e) {
      if (e.target === modal) modal.classList.remove("show");
    });
  }

  // Loading state on submit forms
  document.querySelectorAll("form[data-loading]").forEach(function (form) {
    form.addEventListener("submit", function () {
      const btn = form.querySelector("[type=submit]");
      if (btn) { btn.classList.add("loading"); btn.disabled = true; }
    });
  });
});
