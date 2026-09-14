@component('mail::message')

# Project Updated

Hello {{ $member->name }},

The following project has been updated:

**{{ $project->name }}**

Please review the latest project details.

@component('mail::button', ['url' => route('project.show', $project->id)])
View Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent