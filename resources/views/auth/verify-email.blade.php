<x-layouts.auth>
    <x-slot:heading>
        Verify Your Email Address
    </x-slot:heading>

    <div class="verify-container">
        <p>Click the link to resend email.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="pill">Link Sent!</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="link-button">Resend Email</button>
        </form>
    </div>
</x-layouts.auth>
