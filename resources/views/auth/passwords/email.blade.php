@extends('app') @section('content')
    <div class="c-contents">
        <section class="c-form__container">
            <div class="c-section__title">
                <h1>{{ __('Reset Password') }}</h1>
            </div>

            <div class="c-form__list">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="c-form__item">
                        <label for="email" class="c-form__label">{{ __('E-Mail Address') }}</label>
                        <div class="c-form__body">
                            @error('email')
                                <span class="c-form__input--feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <input id="email" type="text" class="c-form__input @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>
                    </div>

                    <div class="c-form__item">
                        <button type="submit" class="c-form__button">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
            </div>
            </form>

    </div>
    </div>
    </div>
@endsection
