@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(to right, #3b82f6, #a5b4fc)
        background-size: cover;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.88);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        backdrop-filter: blur(4px);
    }
</style>

<div class="min-h-screen flex items-center justify-center py-8">
    <div class="max-w-md w-full login-card">

        <h1 class="text-xl font-bold mb-4 text-center">Login</h1>

        <form method="POST" action="{{ route('auth.login') }}" class="space-y-4">
            @csrf

            @if($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror"
                       required>
                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input type="password" name="password"
                       class="mt-1 w-full border rounded px-3 py-2 @error('password') border-red-500 @enderror"
                       required>
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
                Masuk
            </button>
        </form>

    </div>
</div>

@endsection