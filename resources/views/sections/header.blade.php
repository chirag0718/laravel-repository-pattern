<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <div class="text-end">
                @if(!auth()->check())
                    <a href="/login" type="button" class="btn btn-outline-light me-2">Login</a>
                    <a href="/register" type="button" class="btn btn-warning">Sign-up</a>
                @endif
            </div>
        </div>
    </div>
</header>


