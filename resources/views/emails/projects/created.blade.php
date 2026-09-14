@component('mail::message')

# New Project Assigned

Hello {{ $member->name }},

You have been added to the project:

**{{ $project->name }}**

@component('mail::button', ['url' => route('project.show', $project->id)])
View Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent