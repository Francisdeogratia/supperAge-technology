<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('badge_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('badge_verifications', 'id_image_path')) {
                $table->renameColumn('id_image_path', 'gov_id_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('badge_verifications', function (Blueprint $table) {
            if (Schema::hasColumn('badge_verifications', 'gov_id_path')) {
                $table->renameColumn('gov_id_path', 'id_image_path');
            }
        });
    }
};

