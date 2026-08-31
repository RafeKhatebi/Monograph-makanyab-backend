@php $rating = round($rating ?? 0); @endphp
<span class="mk-rating-stars" aria-label="{{ __('common.out_of_five', ['rating' => $rating]) }}">
    @for ($i = 1; $i <= 5; $i++)
        <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
    @endfor
</span>
