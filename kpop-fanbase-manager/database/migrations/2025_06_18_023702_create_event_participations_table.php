<?php 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema; 
return new class extends Migration 
{ 
    public function up() 
    { 
        Schema::create('event_participations', function (Blueprint $table) { 
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); 
            $table->enum('status', ['confirmed', 'waiting', 'canceled'])->default('confirmed'); 
            $table->timestamps(); 
             
            $table->unique(['user_id', 'event_id']); 
        }); 
    } 
    public function down() 
    { 
        Schema::dropIfExists('event_participations'); 
    } 
};