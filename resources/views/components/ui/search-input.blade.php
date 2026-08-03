@props(['name' => 'search', 'value' => null, 'placeholder' => 'Search'])

<div class="mk-ui-search">
    <i class="fa fa-search mk-ui-search__icon" aria-hidden="true"></i>
    <x-ui.text-input type="search" :name="$name" :value="old($name, $value)" :placeholder="$placeholder" {{ $attributes }} />
</div>
