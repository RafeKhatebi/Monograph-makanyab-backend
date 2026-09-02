@props(['service'])

<div class="col-sm-6 col-md-4 p-2 place-card-col">
    <x-listing-card :item="$service" type="service" :date-label="$dateLabel ?? null" :date-value="$dateValue ?? null" />
</div>
