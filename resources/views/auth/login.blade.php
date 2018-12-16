@extends('dashboard.layouts.modal')

@section('content')
          <div class="splash-container">
            <div class="card card-border-color card-border-color-primary">
              <div class="card-header"><img class="logo-img" src="assets/img/logo-xx.png" alt="logo" width="102" height="27"><span class="splash-description">Please enter your user information.</span></div>
              <div class="card-body">
                <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                  @csrf
                  <div class="form-group">
                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="username" type="text" placeholder="Email Address" value="{{ old('email') }}" required autofocus>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group">
                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" type="password" placeholder="Password" required>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group row login-tools">
                    <div class="col-6 login-remember">
                      <label class="custom-control custom-checkbox">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="custom-control-label">{{ __('Remember Me') }}</span>
                      </label>
                    </div>
                    <div class="col-6 login-forgot-password"><a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}?</a></div>
                  </div>
                  <div class="form-group login-submit">
                    <button type="submit" class="btn btn-primary btn-xl" data-dismiss="modal">{{ __('Sign me in') }}</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="splash-footer"><span>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></span></div>
@endsection