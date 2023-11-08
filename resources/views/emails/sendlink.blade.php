<x-mail::message>
# Login Link

Please click the button below to login
<x-mail::button :url=$url>
Click here to login
</x-mail::button>


Thanks,  
{{ config('app.name') }} Team  

If you're having trouble clicking the "Login" button, copy and paste the URL below into your web browser:  
{{ $url }}
</x-mail::message>

