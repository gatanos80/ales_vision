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
        Schema::create('recognitions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_video');
            $table->text('id_service_video');
            $table->text('progetto'); 
            $table->text('user'); 
            $table->float('time', 7, 2);
            $table->float('age', 5, 2);
            $table->text('gender')->nullable(); 
            $table->float('angry', 9, 6);
            $table->float('disgusted', 9, 6);
            $table->float('fearful', 9, 6);
            $table->float('happy', 9, 6);
            $table->float('neutral', 9, 6);
            $table->float('sad', 9, 6);
            $table->float('surprised', 9, 6);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recognitions');

    }
};
