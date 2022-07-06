@component('mail::message')
Hi!

One of your submissions is Ready now! Check your prescription in the page.

@component('mail::button', ['url' => 'localhost:3000/login'])
Login 
@endcomponent

Thanks<br>
@endcomponent
