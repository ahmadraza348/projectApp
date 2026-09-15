@component('mail::message')

# Project Deadline Reminder

Hello {{ $member->name }},

The project **{{ $project->name }}** is due in **3 days**.

**Deadline:** {{ $project->end_date->format('M d, Y') }}

Please review the project and make sure the remaining work is completed on time.

@component('mail::button', ['url' => route('project.show', $project->id)])
View Project
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent