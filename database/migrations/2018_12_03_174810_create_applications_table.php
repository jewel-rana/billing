<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mikrotik_id')->nullable();
            $table->string('name');
            $table->string('username');
            $table->string('email');
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
            $table->string('cable_cost')->default(0);
            $table->string('setup_charge')->default(0);
            $table->string('activation_date');
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
        Schema::dropIfExists('applications');
    }
}
