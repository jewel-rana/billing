@extends('dashboard.layouts.modal')

@section('content')
          <div class="splash-container sign-up">
            <div class="card card-border-color card-border-color-primary">
              <div class="card-header"><img class="logo-img" src="assets/img/logo-xx.png" alt="logo" width="102" height="27"><span class="splash-description">Please enter your user information.</span></div>
              <div class="card-body">
                <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                        @csrf
                        <span class="splash-title pb-4">{{ __('Sign Up') }}</span>
                  <div class="form-group">
                    <input type="text" placeholder="Name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                    @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group">
                    <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" type="email" name="email" required="" placeholder="E-mail" autocomplete="off">
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group row signup-password">
                    <div class="col-6">
                      <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="pass1" type="password" required="" placeholder="Password">
                    </div>
                    <div class="col-6">
                      <input type="password" class="form-control" name="password_confirmation" required="" placeholder="Confirm">
                    </div>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('password_confirmation'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                  </div>
                  <div class="form-group pt-2">
                    <button class="btn btn-block btn-primary btn-xl" type="submit">{{ __('Sign Up') }}</button>
                  </div>
                  <div class="title"><span class="splash-title pb-3">Or</span></div>
                  <div class="form-group row social-signup pt-0">
                    <div class="col-6">
                      <button class="btn btn-lg btn-block btn-social btn-facebook btn-color" type="button"><i class="mdi mdi-facebook icon icon-left"></i>Facebook</button>
                    </div>
                    <div class="col-6">
                      <button class="btn btn-lg btn-block btn-social btn-google-plus btn-color" type="button"><i class="mdi mdi-google-plus icon icon-left"></i>Google Plus</button>
                    </div>
                  </div>
                  <div class="form-group pt-3 mb-3">
                    <label class="custom-control custom-checkbox">
                      <input class="custom-control-input" type="checkbox"><span class="custom-control-label">By creating an account, you agree the <a href="#">terms and conditions</a>.</span>
                    </label>
                  </div>
                </form>
              </div>
            </div>
            <div class="splash-footer">&copy; 2018 Your Company</div>
          </div>
@endsection