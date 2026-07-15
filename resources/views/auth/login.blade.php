<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        @if (session('status'))
            <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif

        <div class="form-group">
            <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="admin@abncorporation.com" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password"><i class="fa-solid fa-lock"></i> Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
            @error('password')
                <span style="color:var(--danger);font-size:0.8rem;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-options">
            <label class="checkbox-label">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fa-solid fa-right-to-bracket"></i> Sign In
        </button>
    </form>
</x-guest-layout>
