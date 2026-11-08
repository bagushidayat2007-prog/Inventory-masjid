@if (session('success'))
    <div class="alert alert-success">
        <span>&#10004;</span>
        <div>{{ session('success') }}</div>
        <button type="button" class="close">&times;</button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error">
        <span>&#9888;</span>
        <div>{{ session('error') }}</div>
        <button type="button" class="close">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-error">
        <span>&#9888;</span>
        <div>
            <strong>Terjadi kesalahan:</strong>
            <ul style="margin:6px 0 0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="close">&times;</button>
    </div>
@endif
