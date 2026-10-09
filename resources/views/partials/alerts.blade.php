{{--
    Alerts Partial — Nagpapakita ng success, error, at validation feedback.
    May close button at auto-hide para sa success notifications para malinis ang UI.
--}}
@if (session('success'))
    <div class="alert alert-success" role="alert" data-auto-dismiss="true">
        <div class="alert-content">
            <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            <span>{{ session('success') }}</span>
        </div>
        <button type="button" class="alert-close" onclick="closeAlert(this)" aria-label="Close notification">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        <div class="alert-content">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
            <span>{{ session('error') }}</span>
        </div>
        <button type="button" class="alert-close" onclick="closeAlert(this)" aria-label="Close notification">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <div class="alert-content">
            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
            <div>
                <strong>Pakitama ang sumusunod na errors:</strong>
                <ul style="margin-top: 4px; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="alert-close" onclick="closeAlert(this)" aria-label="Close error list">&times;</button>
    </div>
@endif
