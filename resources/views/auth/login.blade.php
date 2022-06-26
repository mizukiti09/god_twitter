@extends('app') @section('content')
<div class="c-contents">
    <section class="c-form__container">
        <div class="c-section__title">
            <h1>{{ __('Login') }}</h1>
        </div>

        <div class="c-form__list">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="c-form__item">
                    <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>
                    <div class="c-form__body">
                        @error('email')
                        <span class="c-form__input--feedback" role="alert">
                                <strong>{{ $message }}</strong>
                        </span> @enderror
                        <input id="email" type="text" class="c-form__input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>
                </div>

                <div class="c-form__item">
                    <label for="password" class="c-form__label">{{ __('Password') }}</label>
                    <div class="c-form__body">
                        @error('password')
                        <span class="c-form__input--feedback" role="alert">
                                <strong>{{ $message }}</strong>
                        </span> @enderror
                        <input id="password" type="password" class="c-form__input @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="new-password" autofocus>
                    </div>
                </div>

                <div class="c-form__item">
                    <button type="submit" class="c-form__button">
                            {{ __('Login') }}
                        </button>@if (Route::has('password.request'))
                        <a class="c-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a> @endif
                </div>
            </form>
        </div>

    </section>
</div>

@endsection