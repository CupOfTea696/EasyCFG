<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCfgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('easycfg.table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 128);
            $table->text('value');
            $table->string('configurable')->nullable();
            $table->integer('configurable_id')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('easycfg.table'));
    }
}
