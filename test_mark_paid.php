<!DOCTYPE html>
<!-- 
  ⚠️  Place this file at:  restaurant/test_mark_paid.php  (ROOT folder, NOT inside php/)
  Open at: localhost/restaurant/test_mark_paid.php
  DELETE after testing!
-->
<html>
<head>
  <title>Mark Paid Test</title>
  <style>
    body { font-family: sans-serif; padding: 2rem; background: #f5f5f5; }
    .box { background:#fff; padding:1.5rem; border-radius:12px; max-width:520px;
           box-shadow:0 4px 20px rgba(0,0,0,0.1); }
    h2   { margin-top:0; }
    input, button { padding:0.5rem 1rem; font-size:1rem; border-radius:8px; }
    input  { border:1.5px solid #ccc; margin-right:0.5rem; width:140px; }
    button { background:#2C7A2C; color:#fff; border:none; cursor:pointer; }
    pre    { background:#1a1a2e; color:#00ff88; padding:1rem; border-radius:8px;
             white-space:pre-wrap; word-break:break-all; font-size:0.9rem; margin-top:1rem; }
    .ok  { color:green; font-weight:bold; }
    .err { color:red;   font-weight:bold; }
    .step { background:#fff8e1; border-left:4px solid #ffc107;
            padding:0.8rem 1rem; border-radius:6px; margin-bottom:1rem; font-size:0.9rem; }
  </style>
</head>
<body>
<div class="box">
  <h2>🧪 Mark Paid — Connection Test</h2>

  <div class="step">
    📁 This file must be at <strong>restaurant/test_mark_paid.php</strong><br>
    (root folder, NOT inside php/)
  </div>

  <p>Enter any Order ID from your table and click Test:</p>
  <input type="number" id="oid" value="8" min="1" placeholder="Order ID">
  <button onclick="runTest()">▶ Run Test</button>

  <pre id="out">Click "Run Test" to check...</pre>
  <p id="verdict"></p>
</div>

<script>
async function runTest() {
  const oid     = document.getElementById('oid').value;
  const out     = document.getElementById('out');
  const verdict = document.getElementById('verdict');
  out.textContent = 'Calling php/mark_paid.php with order_id=' + oid + ' ...';
  verdict.innerHTML = '';

  try {
    const res  = await fetch('php/mark_paid.php', {
      method : 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body   : 'order_id=' + oid
    });

    const text = await res.text();
    out.textContent = 'HTTP Status: ' + res.status + '\n\nResponse:\n' + text;

    try {
      const data = JSON.parse(text);
      if (data.success) {
        verdict.innerHTML = '<span class="ok">✅ WORKING! Order #' + oid + ' marked as paid successfully.<br>You can now delete this test file.</span>';
      } else {
        verdict.innerHTML = '<span class="err">❌ PHP Error: ' + (data.error || JSON.stringify(data)) + '</span>';
      }
    } catch(e) {
      verdict.innerHTML = '<span class="err">❌ Response is not JSON — there is a PHP error in the response above.</span>';
    }

  } catch (err) {
    out.textContent = 'Fetch failed:\n' + err.message;
    verdict.innerHTML = '<span class="err">❌ Network error — make sure this file is in the restaurant/ root folder, not inside php/</span>';
  }
}
</script>
</body>
</html>