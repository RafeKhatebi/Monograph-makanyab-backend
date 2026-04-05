@php $rating = round($rating ?? 0); @endphp
@for ($i = 1; $i <= 5; $i++)
    <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}" style="color: #10B981;"></i>
@endfor
