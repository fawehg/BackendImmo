<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('url'); // URL or path to the image
            $table->unsignedBigInteger('imageable_id'); // ID of the parent model
            $table->string('imageable_type'); // Type of the parent model (e.g., App\Models\Maison)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}