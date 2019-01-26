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
            $table->integer('user_id')->index();
            $table->string('name');
            $table->string('username')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->text('address')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('type')->default('home')->index();
            $table->integer('zone_id')->index();
            $table->integer('area_id')->nullable()->index();
            $table->integer('package_id')->index();
            $table->string('remote_ip')->nullable();
            $table->string('remote_mac')->nullable();
            $table->string('monthly_discount')->default(0);
            $table->tinyInteger('status')->default(0)->index();
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
