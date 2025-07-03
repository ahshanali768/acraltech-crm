@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === config('app.name'))
<img src="{{ asset('logo.png') }}" class="logo" alt="{{ config('app.name') }} Logo" style="height: 60px; width: auto;">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
