@php $rating = round($rating ?? 0); @endphp
<span class="mk-rating-stars" aria-label="{{ $rating }} out of 5 stars">
    @for ($i = 1; $i <= 5; $i++)
        <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
    @endfor
</span>
