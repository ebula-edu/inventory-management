@if (session('success'))
    <div class="alert alert-success" role="alert">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <strong>Please correct the following errors:</strong>
            <ul style="margin-top: 4px; padding-left: 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
