@props(['service'])

<x-listing-card :item="$service" type="service" :date-label="$dateLabel ?? null" :date-value="$dateValue ?? null" />
