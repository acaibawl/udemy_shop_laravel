<div>
    @if (empty($shop->filename))
        <img src="{{ asset('images/no_image.jpg') }}" alt="画像"/>
    @else
        <img src="{{ asset("storage/shops/{$shop->filename}") }}" alt="画像"/>
    @endif
</div>
