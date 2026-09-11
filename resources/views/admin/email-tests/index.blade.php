@extends('admin.layouts.app')

@section('title', 'Email Testing')

@section('content')
<style>
    .email-test-page{--accent:#611eb2;--accent-dark:#4d168f;--soft:#f4edfc;--ink:#171021}.email-test-head{display:flex;justify-content:space-between;gap:16px;align-items:flex-start;flex-wrap:wrap;margin-bottom:18px}.email-test-head h2{margin:0;font-weight:900;color:#111827}.email-test-head p{margin:6px 0 0;color:#64748b}.email-test-info{background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:14px 16px;margin-bottom:18px;display:flex;gap:18px;flex-wrap:wrap;align-items:center}.email-test-info-item{display:flex;align-items:center;gap:9px;color:#475569;font-size:13px;font-weight:700}.email-test-info-item i{color:var(--accent)}.recipient-pill{display:inline-flex;align-items:center;gap:6px;background:var(--soft);color:var(--accent);border-radius:999px;padding:6px 10px;font-size:12px;font-weight:800}.email-test-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.email-test-card{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:18px;box-shadow:0 10px 28px rgba(15,23,42,.04);display:flex;flex-direction:column;gap:15px}.email-test-card-top{display:flex;justify-content:space-between;gap:14px;align-items:flex-start}.email-test-icon{width:44px;height:44px;border-radius:13px;background:linear-gradient(135deg,var(--accent),var(--accent-dark));color:#fff;display:flex;align-items:center;justify-content:center;font-size:17px;flex:0 0 auto}.email-test-title{display:flex;gap:12px;align-items:flex-start}.email-test-title h4{margin:1px 0 3px;font-size:17px;font-weight:900;color:#111827}.email-test-title code{font-size:11px;color:#64748b}.email-test-card p{margin:0;color:#64748b;font-size:13px;line-height:1.55}.email-test-subject{background:#f8fafc;border:1px solid #edf0f4;border-radius:12px;padding:11px 12px}.email-test-subject span{display:block;font-size:10px;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;font-weight:900}.email-test-subject strong{display:block;margin-top:4px;font-size:13px;color:#334155}.email-test-send{margin-top:auto;display:flex;justify-content:flex-end}.email-test-send button{border:0;background:var(--accent);color:#fff;border-radius:999px;padding:10px 17px;font-weight:900;font-size:13px;display:inline-flex;align-items:center;gap:8px}.email-test-send button:hover{background:var(--accent-dark)}.email-test-send button:disabled{opacity:.65;cursor:not-allowed}.mail-warning{background:#fff8e6;border:1px solid #f6d98d;border-radius:14px;padding:12px 14px;color:#8a5a00;font-size:12px;font-weight:700;margin-bottom:18px}@media(max-width:900px){.email-test-grid{grid-template-columns:1fr}}
</style>

<div class="email-test-page">
    <div class="email-test-head">
        <div>
            <h2>Training Email Testing</h2>
            <p>Send any available ACES Training email template with safe sample data. No test bookings, orders, payments, or customers are written to the database.</p>
        </div>
        <span class="recipient-pill"><i class="fa-solid fa-envelope"></i>{{ count($emails) }} Email Functions</span>
    </div>

    <div class="email-test-info">
        <div class="email-test-info-item"><i class="fa-solid fa-paper-plane"></i><span>Mailer: <strong>{{ $mailer ?: 'Not configured' }}</strong></span></div>
        <div class="email-test-info-item"><i class="fa-solid fa-at"></i><span>From: <strong>{{ $fromAddress ?: 'Not configured' }}</strong></span></div>
        <div class="email-test-info-item"><i class="fa-solid fa-users"></i><span>Recipients:</span>
            @foreach($recipients as $recipient)
                <span class="recipient-pill">{{ $recipient }}</span>
            @endforeach
        </div>
    </div>

    @if(($mailer ?? '') === 'log')
        <div class="mail-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Your current mailer is <strong>log</strong>. Laravel will write the messages to the log instead of delivering real email. Configure an SMTP/mail provider when you want these buttons to send externally.</div>
    @endif

    <div class="email-test-grid">
        @foreach($emails as $key => $email)
            <article class="email-test-card">
                <div class="email-test-card-top">
                    <div class="email-test-title">
                        <div class="email-test-icon"><i class="fa-solid {{ $email['icon'] }}"></i></div>
                        <div>
                            <h4>{{ $email['name'] }}</h4>
                            <code>{{ $email['view'] }}</code>
                        </div>
                    </div>
                </div>

                <p>{{ $email['trigger'] }}</p>

                <div class="email-test-subject">
                    <span>Test Subject</span>
                    <strong>[TEST] {{ $email['subject'] }}</strong>
                </div>

                <div class="email-test-send">
                    <form method="POST" action="{{ route('admin.email-tests.send', $key) }}" onsubmit="return confirm('Send this test email to both configured testing addresses?');">
                        @csrf
                        <button type="submit"><i class="fa-solid fa-paper-plane"></i>Send Test</button>
                    </form>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection
