<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('path')->nullable();
            $table->string('widget_type');
            $table->tinyInteger('taxonomy_selectable')->default(0);
            $table->enum('taxonomy',['tag','category','post','tag-category','image'])->nullable();
            $table->tinyInteger('limit_required')->default(0);
            $table->tinyInteger('title_required')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1 = Active , 0 = Inactive');

            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
    }
}
