<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Places table - add indexes for common search/filter patterns
        if (! Schema::hasIndex('places', 'places_name_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('name');
            });
        }
        if (! Schema::hasIndex('places', 'places_place_category_id_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('place_category_id');
            });
        }
        if (! Schema::hasIndex('places', 'places_user_id_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
        if (! Schema::hasIndex('places', 'places_province_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('province');
            });
        }
        if (! Schema::hasIndex('places', 'places_district_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('district');
            });
        }
        if (! Schema::hasIndex('places', 'places_price_level_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('price_level');
            });
        }
        if (! Schema::hasIndex('places', 'places_created_at_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index('created_at');
            });
        }
        if (! Schema::hasIndex('places', 'places_is_active_is_verified_created_at_index')) {
            Schema::table('places', function (Blueprint $table) {
                $table->index(['is_active', 'is_verified', 'created_at']);
            });
        }

        // Services table - add indexes for common search/filter patterns
        if (! Schema::hasIndex('services', 'services_name_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('name');
            });
        }
        if (! Schema::hasIndex('services', 'services_service_category_id_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('service_category_id');
            });
        }
        if (! Schema::hasIndex('services', 'services_user_id_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
        if (! Schema::hasIndex('services', 'services_province_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('province');
            });
        }
        if (! Schema::hasIndex('services', 'services_district_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('district');
            });
        }
        if (! Schema::hasIndex('services', 'services_price_level_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('price_level');
            });
        }
        if (! Schema::hasIndex('services', 'services_created_at_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index('created_at');
            });
        }
        if (! Schema::hasIndex('services', 'services_is_active_is_verified_created_at_index')) {
            Schema::table('services', function (Blueprint $table) {
                $table->index(['is_active', 'is_verified', 'created_at']);
            });
        }

        // Users table
        if (! Schema::hasIndex('users', 'users_created_at_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('created_at');
            });
        }

        // Reviews table
        if (! Schema::hasIndex('reviews', 'reviews_is_approved_index')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->index('is_approved');
            });
        }

        // Posts table
        if (! Schema::hasIndex('posts', 'posts_is_published_index')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->index('is_published');
            });
        }
        if (! Schema::hasIndex('posts', 'posts_published_at_index')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->index('published_at');
            });
        }
        if (! Schema::hasIndex('posts', 'posts_user_id_index')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
        if (! Schema::hasIndex('posts', 'posts_is_published_published_at_index')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->index(['is_published', 'published_at']);
            });
        }

        // Favorites table
        if (! Schema::hasIndex('favorites', 'favorites_user_id_index')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
        if (! Schema::hasIndex('favorites', 'favorites_place_id_index')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->index('place_id');
            });
        }

        // Place categories table
        if (! Schema::hasIndex('place_categories', 'place_categories_parent_id_index')) {
            Schema::table('place_categories', function (Blueprint $table) {
                $table->index('parent_id');
            });
        }
        if (! Schema::hasIndex('place_categories', 'place_categories_is_active_index')) {
            Schema::table('place_categories', function (Blueprint $table) {
                $table->index('is_active');
            });
        }
        if (! Schema::hasIndex('place_categories', 'place_categories_sort_order_index')) {
            Schema::table('place_categories', function (Blueprint $table) {
                $table->index('sort_order');
            });
        }

        // Service categories table
        if (! Schema::hasIndex('service_categories', 'service_categories_parent_id_index')) {
            Schema::table('service_categories', function (Blueprint $table) {
                $table->index('parent_id');
            });
        }
        if (! Schema::hasIndex('service_categories', 'service_categories_is_active_index')) {
            Schema::table('service_categories', function (Blueprint $table) {
                $table->index('is_active');
            });
        }
        if (! Schema::hasIndex('service_categories', 'service_categories_sort_order_index')) {
            Schema::table('service_categories', function (Blueprint $table) {
                $table->index('sort_order');
            });
        }

        // Place suggestions table
        if (! Schema::hasIndex('place_suggestions', 'place_suggestions_user_id_index')) {
            Schema::table('place_suggestions', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
        if (! Schema::hasIndex('place_suggestions', 'place_suggestions_place_category_id_index')) {
            Schema::table('place_suggestions', function (Blueprint $table) {
                $table->index('place_category_id');
            });
        }
        if (! Schema::hasIndex('place_suggestions', 'place_suggestions_suggestion_status_index')) {
            Schema::table('place_suggestions', function (Blueprint $table) {
                $table->index('suggestion_status');
            });
        }

        // Service suggestions table
        if (! Schema::hasIndex('service_suggestions', 'service_suggestions_user_id_index')) {
            Schema::table('service_suggestions', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
        if (! Schema::hasIndex('service_suggestions', 'service_suggestions_service_category_id_index')) {
            Schema::table('service_suggestions', function (Blueprint $table) {
                $table->index('service_category_id');
            });
        }
        if (! Schema::hasIndex('service_suggestions', 'service_suggestions_suggestion_status_index')) {
            Schema::table('service_suggestions', function (Blueprint $table) {
                $table->index('suggestion_status');
            });
        }

        // Contact messages table
        if (! Schema::hasIndex('contact_messages', 'contact_messages_user_id_index')) {
            Schema::table('contact_messages', function (Blueprint $table) {
                $table->index('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['place_category_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['province']);
            $table->dropIndex(['district']);
            $table->dropIndex(['price_level']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_active', 'is_verified', 'created_at']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['service_category_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['province']);
            $table->dropIndex(['district']);
            $table->dropIndex(['price_level']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_active', 'is_verified', 'created_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['is_approved']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['is_published', 'published_at']);
        });

        Schema::table('favorites', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['place_id']);
        });

        Schema::table('place_categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['sort_order']);
        });

        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropIndex(['parent_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['sort_order']);
        });

        Schema::table('place_suggestions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['place_category_id']);
            $table->dropIndex(['suggestion_status']);
        });

        Schema::table('service_suggestions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['service_category_id']);
            $table->dropIndex(['suggestion_status']);
        });

        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
