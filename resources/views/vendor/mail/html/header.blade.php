@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<span style="font-size: 19px; font-weight: bold; color: #18181b;">University of Rizal System Binangonan</span>
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
