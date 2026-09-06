@if($event->start_date)
    <time datetime="{{ $event->start_date->toDateString() }}">{{ $event->start_date->translatedFormat('j F Y') }}</time>
    @if($event->end_date && ! $event->end_date->isSameDay($event->start_date))
        – <time datetime="{{ $event->end_date->toDateString() }}">{{ $event->end_date->translatedFormat('j F Y') }}</time>
    @endif
@else
    {{ app()->getLocale() === 'en' ? 'Date to be confirmed' : 'Data da definire' }}
@endif
