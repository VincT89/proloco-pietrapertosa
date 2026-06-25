<x-mail::message>
# Nuovo messaggio dal form di contatto

**Nome:** {{ $data['name'] }}  
**Email:** {{ $data['email'] }}  
@if(!empty($data['subject']))
**Oggetto:** {{ $data['subject'] }}  
@endif

**Messaggio:**  
{{ $data['message'] }}

---
**Data di invio:** {{ $data['date'] ?? now()->format('d/m/Y H:i') }}  
**Lingua form:** {{ $data['locale'] ?? strtoupper(app()->getLocale()) }}

Grazie,<br>
{{ config('app.name') }}
</x-mail::message>
