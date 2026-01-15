<x-layouts.auth>
    <div class="container">
        <h1>Verifikoni Email-in tuaj</h1>
        <p>Ju lutem klikoni linkun që ju dërguam në email për të vazhduar.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success">Një link i ri u dërgua!</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Ridërgo Email-in</button>
        </form>
    </div>
</x-layouts.auth>