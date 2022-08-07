@component('mail::message')
# Weekly Series Update

Hey {{ $user->name }},<br>
Checkout the latest races of your favorite series!

@component('mail::panel')
@foreach ($user->series_following as $series)
# {{ $series->full_name }} ({{ $series->name }})
@foreach ($series->races as $race)
### {{ $race->name }}<br>
<a href="http://localhost:8080/races/{{$race->id}}/?utm_source=newsletter&utm_medium=email"><img src="https://img.youtube.com/vi/{{$race->videos->first()->video_id}}/maxresdefault.jpg"><br></a><br>
@endforeach
@endforeach
@endcomponent

@endcomponent
