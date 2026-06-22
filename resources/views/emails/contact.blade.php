<x-mail::message>
# Nuovo messaggio dal form di contatto

**Nome:** {{ $data['name'] }}  
**Email:** {{ $data['email'] }}  
@if(!empty($data['subject']))
**Oggetto:** {{ $data['subject'] }}  
@endif

**Messaggio:**  
{{ $data['message'] }}

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
