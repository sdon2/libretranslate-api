<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibretranslateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libretranslate_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('source_text');
            $table->text('translated_text')->nullable();
            $table->boolean('translation_found');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libretranslate_translations');
    }
}
