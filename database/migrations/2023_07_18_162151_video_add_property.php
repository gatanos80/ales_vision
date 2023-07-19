<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('videos', function($table) {
            $table->text('return_link');
            $table->text('no_detection_label');
            $table->text('start_label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function($table) {
            $table->dropColumn('return_link');
            $table->dropColumn('no_detection_label');
            $table->dropColumn('start_label');
        });
    }
};
