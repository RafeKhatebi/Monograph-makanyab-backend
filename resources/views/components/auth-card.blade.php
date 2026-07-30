<div class="content-area mk-page-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box-two mk-card">
                    <h3 class="mk-heading mk-heading--md text-center mk-stack-sm">
                        {{ $title }}
                    </h3>

                    @if (session('status'))
                        <div class="mk-alert mk-alert--success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mk-alert flash-error">
                            <ul>
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
