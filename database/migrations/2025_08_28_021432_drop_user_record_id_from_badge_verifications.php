<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('badge_verifications', function (Blueprint $table) {
    if (Schema::hasColumn('badge_verifications', 'user_record_id')) {
        $table->dropColumn('user_record_id');
    }
});



}
public function down()
{
    Schema::table('badge_verifications', function (Blueprint $table) {
        $table->unsignedBigInteger('user_record_id');
    });
}

};
