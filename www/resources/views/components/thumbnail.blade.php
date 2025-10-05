<div>
    @if (empty($filename))
        <img src="{{ asset('images/no_image.jpg') }}" alt="画像"/>
    @else
        <img src="{{ asset("storage/{$type}/{$filename}") }}" alt="画像"/>
    @endif
</div>
