<?php

use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->text('address')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        CompanyProfile::create(
        [
            'name'    => 'Name Here',
            'phone'   => 'Phone Number here',
            'email'   => 'mail@email.com',
            'address' => 'Address Here',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profiles');
    }
}
