@props([
    'attachments' => null
])
@foreach ($attachments as $index => $attachment)
<flux:icon.photo class="size-4" />
<a href="{{ route('file.viewer', ['fileId' => $attachment->id]) }}" target="_blank"
    rel="noopener noreferrer"
    class="text-sm text-zinc-500 hover:underline">{{  $attachment->original_filename }}
</a>
@endforeach