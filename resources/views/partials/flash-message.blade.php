@if (session('success'))
    <div class="alert-box alert-success" style="margin:0 0 24px;padding:18px 20px;border-radius:14px;background:#D1FAE5;color:#065F46;border:1px solid #10B981;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert-box alert-danger" style="margin:0 0 24px;padding:18px 20px;border-radius:14px;background:#FEE2E2;color:#991B1B;border:1px solid #FECACA;">
        {{ session('error') }}
    </div>
@endif
