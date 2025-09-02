<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddB2bFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->enum('user_type', ['buyer', 'seller', 'both'])->default('buyer')->after('username');
            $table->string('company_name')->nullable()->after('user_type');
            $table->string('business_license')->nullable()->after('company_name');
            $table->string('phone')->nullable()->after('business_license');
            $table->text('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->default('Bangladesh')->after('city');
            $table->string('postal_code')->nullable()->after('country');
            $table->string('website')->nullable()->after('postal_code');
            $table->text('description')->nullable()->after('website');
            $table->boolean('is_verified')->default(false)->after('description');
            $table->boolean('is_active')->default(true)->after('is_verified');
            $table->decimal('credit_limit', 12, 2)->default(0)->after('is_active');
            $table->integer('payment_terms')->default(30)->after('credit_limit'); // days
            $table->string('avatar')->nullable()->after('payment_terms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'user_type', 'company_name', 'business_license',
                'phone', 'address', 'city', 'country', 'postal_code',
                'website', 'description', 'is_verified', 'is_active',
                'credit_limit', 'payment_terms', 'avatar'
            ]);
        });
    }
}
