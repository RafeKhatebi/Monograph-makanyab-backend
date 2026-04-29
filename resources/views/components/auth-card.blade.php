<div class="content-area" style="background-color: #FCFCFC; padding: 55px 0;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box-two" style="padding: 40px;">
                    <h3 style="font-size: 24px; font-weight: 600; color: #111827; margin-bottom: 25px; text-align: center;">
                        {{ $title }}
                    </h3>

                    @if (session('status'))
                        <div style="padding: 15px; background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 25px;">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div style="padding: 15px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; margin-bottom: 25px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
