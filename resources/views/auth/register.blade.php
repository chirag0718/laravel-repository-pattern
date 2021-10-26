@extends("layouts.guest")

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <img class="mb-4" src="../assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
            <input required type="text" class="form-control" name="name" value="{{ old('name') }}" id="floatingInput" placeholder="Chirag">
            <label for="floatingInput">Name</label>
        </div>
        <div class="form-floating">
            <input required type="email" class="form-control" id="floatingInput" value="{{ old('email') }}" name="email" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input required type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-floating">
            <input required type="password" class="form-control" id="floatingPasswordConfirmation" name="password_confirmation" placeholder="Password Confirmation">
            <label for="floatingPasswordConfirmation">Confirm Password</label>
        </div>

        <div class="checkbox mb-3">
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
        <p class="mt-5 mb-3 text-muted">&copy;2017–2021</p>
    </form>
@endsection
