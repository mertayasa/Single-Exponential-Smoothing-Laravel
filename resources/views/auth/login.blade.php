@extends('layouts.app')

@section('content')
@push('styles')
    <style>
        #app{
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #1b9c6d 10%, #45d6a1 100%);
            background-size: cover;
            height: 100vh;
            display: table;
            width: 100%;
        }
        .form-card{
            display: table-cell;
            vertical-align: middle;
        }
        .form-card .actual-form{
            width: 500px;
            margin: 0 auto;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-login{
            width: 200px;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .mb-5{
            margin-bottom: 10vh !important;
        }

        .form-control{
            height: 40px !important;
        }

        .form-check-label{
            font-weight: bold;
        }

    </style>
@endpush
<div class="form-card">
    <div class="actual-form bg-white px-4 py-5 rounded">
        <h2 class="text-center text-success mb-4">Tempo Dulu Kopi</h2>
        <form id="loginForm" method="POST" action="{{ route('login') }}">
            @csrf
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="ID">
    
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
    
            <input id="password" type="password" class="form-control mt-4 @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
    
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
    
            
            <button type="submit" class="mt-4 btn center btn-success btn-login">
                Login
            </button>
        </form>
    </div>
</div>
@endsection