<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Switch to Training | Alcatraz Outlaws</title>
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--red:#f3282c;--black:#0a0a0a;--muted:#5f6673;--bg:#f3f4f6}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;font-family:'Karla',Arial,sans-serif;color:#101114;background:radial-gradient(circle at 18% 18%,rgba(243,40,44,.09),transparent 28%),linear-gradient(135deg,#f3f4f6 0%,#ffffff 52%,#f7f7f8 100%)}
        .switch-card{width:min(560px,100%);padding:34px;border:1px solid #e1e3e8;border-radius:28px;background:#fff;box-shadow:0 24px 70px rgba(17,24,39,.14)}
        .switch-icon{width:54px;height:54px;border-radius:18px;display:grid;place-items:center;background:#fff1f1;color:var(--red);font-size:24px;font-weight:900;margin-bottom:20px}
        h1{margin:0;font-size:30px;line-height:1.1;letter-spacing:-.03em}
        p{margin:14px 0 0;color:var(--muted);font-size:15px;line-height:1.65;font-weight:600}
        .switch-note{margin-top:18px;padding:14px 15px;border-radius:16px;background:#f8f9fb;border:1px solid #eceef2;color:#4e5562;font-size:13px;line-height:1.55}
        .switch-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:24px}
        .switch-actions form{margin:0}
        .switch-actions button,.switch-actions a{min-height:44px;border-radius:999px;padding:0 18px;display:inline-flex;align-items:center;justify-content:center;border:0;font-family:inherit;font-size:13px;font-weight:900;text-decoration:none;cursor:pointer}
        .continue{background:var(--red);color:#fff;box-shadow:0 10px 22px rgba(243,40,44,.22)}
        .continue:hover{background:#d92025}.cancel{background:var(--black);color:#fff}.cancel:hover{background:#000}
        @media(max-width:520px){.switch-card{padding:24px;border-radius:22px}.switch-actions,.switch-actions form,.switch-actions button,.switch-actions a{width:100%}}
    </style>
</head>
<body>
    <section class="switch-card">
        <div class="switch-icon">!</div>
        <h1>Switch to Training?</h1>
        <p>You are currently signed in to Shop. If you continue, Shop will be signed out so you can sign in to the Training module.</p>
        <div class="switch-note">Your Shop cart is preserved for this browser/account. When you return to Shop and sign in again, those items will still be available.</div>
        <div class="switch-actions">
            <form method="POST" action="{{ $continueUrl }}">
                @csrf
                <input type="hidden" name="redirect" value="{{ $redirect }}">
                <button class="continue" type="submit">Continue to Training</button>
            </form>
            <a class="cancel" href="{{ $cancelUrl }}">Stay in Shop</a>
        </div>
    </section>
</body>
</html>
