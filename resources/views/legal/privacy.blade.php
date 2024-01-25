@extends('livewire.welcome')

@section('home-content')
    <div class="bg-zinc-50">
        <div class="mx-auto max-w-[768px] px-4 py-32">
            <h1 class="pb-20 text-center text-7xl font-bold">Privacy Policy</h1>
            <div class="bg-white">
                <div class="p-10">
                    <h2 class="mb-5 border-b-[1px] pb-2 text-3xl border-black font-semibold">
                        Commitment To Privacy</h2>
                    <p>At {{ env('APP_NAME') }}, we respect your privacy and are committed to
                        protecting your
                        personal information. This Privacy Policy explains how we
                        collect, use, and
                        safeguard the information you provide when using our AI
                        Article
                        Generator.</p>

                    <h2 class="mb-5 mt-10 border-b-[1px] pb-2 text-3xl border-black font-semibold">
                        Information We Collect</h2>
                    <p>The only personal information we collect from you is your
                        email
                        address when you
                        sign up to create an account. We do not collect any other
                        personally
                        identifiable information.</p>

                    <h2 class="mb-5 mt-10 border-b-[1px] pb-2 text-3xl border-black font-semibold">Use
                        of Information</h2>
                    <p>Your email address is solely used for the purpose of sending
                        our
                        newsletter and
                        facilitating your account management, including login and
                        support. We do not
                        share your personal information with any third parties.</p>
                    <br /><br />

                    <p>The content that you generate through the dashboard can be
                        reviewed
                        by {{ env('APP_NAME') }},
                        in order to increase the quality of the product. We'll never
                        share
                        your articles
                        with any third-parties.</p>

                    <h2 class="mb-5 mt-10 border-b-[1px] pb-2 text-3xl border-black font-semibold">
                        Contact Us</h2>
                    <p>If you have any questions or comments about this Privacy
                        Policy or
                        your personal
                        information, to make an access or correction request, to
                        exercise
                        any applicable
                        rights, to make a complaint, or to obtain information about
                        our
                        policies and
                        practices, our Privacy Officer (or Data Protection Officer)
                        can be
                        reached by
                        email at 
                    <a class="underline text-[#1E64E6]" href="{{ 'mailto:support@' . env('APP_NAME') . '.com'}}">{{ strtolower('support@' . env('APP_NAME') . '.com') }}</a>.
                            
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
