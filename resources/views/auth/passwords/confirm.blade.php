@extends('app') @section('content')
    <div class="c-contents">
        <section class="c-form__container">
            <div class="c-section__title">
                <h1>{{ __('Confirm Password') }}</h1>
            </div>

            <div class="c-form__list">
                {{ __('Please confirm your password before continuing.') }}

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="c-form__item">
                        <label for="password" class="c-form__label">{{ __('Password') }}</label>

                        <div class="c-form__body">
                            <span class="c-form__input--feedback" role="alert">
                                <strong>{{ $message }}</strong>
                        </span> @enderror
                        <input id="password" type="password"
                            class="c-form__input @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password" autofocus> @error('password')
                        </div>
                    </div>

                    <div class="c-form__item">
                        <button type="submit" class="c-form__button">
                            {{ __('Confirm Password') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="c-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="c-form__item">
                        <button type="submit" class="c-form__button">
                            {{ __('Confirm Password') }}
                        </button>
                        @if (Route::has('password.request'))
                            <a class="c-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
            </div>
            </form>
    </div>
    </div>
    </div>
@endsection
