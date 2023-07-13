@foreach ($images as $key => $image)
    <li data-target="#slider" data-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
    <audio id="audio-player"></audio>
@endforeach
