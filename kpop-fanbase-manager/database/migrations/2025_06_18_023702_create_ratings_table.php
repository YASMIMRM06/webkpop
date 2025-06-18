<?php 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema; 
return new class extends Migration 
{ 
    public function up() 
    { 
        Schema::create('ratings', function (Blueprint $table) { 
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('music_id')->constrained()->onDelete('cascade'); 
            $table->integer('rating')->unsigned()->between(1, 5); 
            $table->text('comment')->nullable(); 
            $table->timestamps(); 
            $table->unique(['user_id', 'music_id']); 
        }); 
    } 
    public function down() 
    { 
        Schema::dropIfExists('ratings'); 
    } 
};