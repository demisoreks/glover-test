<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->default(0);
            $table->text('details');
            $table->enum('type', ['create', 'update', 'delete']);
            $table->bigInteger('inserted_by')->unsigned();
            $table->foreign('inserted_by')->references('id')->on('administrators');
            $table->timestamp('inserted_at');
            $table->boolean('approved')->default(false);
            $table->bigInteger('approved_by')->unsigned();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('success')->default(false);
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('requests');
    }
}
