@extends('dashboard.layouts.modal')

@section('content')
          <div class="splash-container forgot-password">
            <div class="card card-border-color card-border-color-primary">
              <div class="card-header"><img class="logo-img" src="{{ asset('assets/img/logo-xx.png') }}" alt="logo" width="102" height="#{conf.logoHeight}"><span class="splash-description">{{ __('Forgot your password') }}?</span></div>
              <div class="card-body">
                <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
                        @csrf
                  @if (session('status'))
                      <div class="alert alert-success" role="alert">
                          {{ session('status') }}
                      </div>
                  @else
                  <p>Don't worry, we'll send you an email to reset your password.</p>
                  @endif
                  <div class="form-group pt-4">
                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" type="email" name="email" required="" placeholder="Your Email" autocomplete="off">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>
                  <p class="pt-1 pb-4">Don't remember your email? <a href="#">Contact Support</a>.</p>
                  <div class="form-group pt-1">
                    <button class="btn btn-block btn-primary btn-xl" type="submit">{{ __('Send Password Reset Link') }}</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="splash-footer">&copy; 2018 Your Company</div>
          </div>
@endsection