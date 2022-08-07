@component('mail::message')
# New race problem reported

@component('mail::panel')
**User:** {{ $raceProblem?->user?->name }}\
**Race:** {{ $raceProblem?->race?->name }}\
**Description:** {{ $raceProblem?->description }}
@endcomponent

@component('mail::button', ['url' => config('app.url').'/admin/resources/race-problems/'.$raceProblem?->id])
View Details
@endcomponent

@endcomponent
