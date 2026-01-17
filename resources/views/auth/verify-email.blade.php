<x-layouts.auth>
    <x-slot:heading>
        Verifikoni Email-in tuaj
    </x-slot:heading>

    <div class="verify-container">
        <p>Ju lutem klikoni linkun që ju dërguam në email për të vazhduar.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="pill">Një link i ri u dërgua!</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="link-button">Ridërgo Email-in</button>
        </form>
    </div>
</x-layouts.auth>
