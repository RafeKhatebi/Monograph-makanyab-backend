<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opening_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('place_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week')->comment('0=Sunday, 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday');
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
            $table->unique(['place_id', 'day_of_week']);
            $table->index(['place_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opening_hours');
    }
};
