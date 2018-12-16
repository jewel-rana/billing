<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mikrotik_id')->nullable();
            $table->integer('user_id');
            $table->string('name');
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->text('address')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('type')->default('home');
            $table->integer('area_id');
            $table->integer('package_id');
            $table->string('remote_ip')->nullable();
            $table->string('remote_mac')->nullable();
            $table->string('monthly_discount')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('customers');
    }
}
