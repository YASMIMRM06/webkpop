<?php 
use Illuminate\Database\Migrations\Migration; 
use Illuminate\Database\Schema\Blueprint; 
use Illuminate\Support\Facades\Schema; 
return new class extends Migration 
{ 
    public function up() 
    { 
        Schema::create('musics', function (Blueprint $table) { 
            $table->id(); 
$table->foreignId('group_id')->constrained()->onDelete('cascade'); 
            $table->string('title'); 
            $table->time('duration'); 
            $table->string('youtube_link')->nullable(); 
            $table->date('release_date'); 
            $table->float('average_rating')->default(0); 
            $table->timestamps(); 
        }); 
    } 
    public function down() 
    { 
        Schema::dropIfExists('musics'); 
    } 
}; 