<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['ADMIN', 'PENCARI_KERJA', 'PEMILIK_KERJA'])->default('PENCARI_KERJA');
        });

        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan')->nullable();
            $table->string('logo')->nullable();
            $table->string('alamat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE')
            ;

        });
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_pekerjaan', [
                'FULL_TIME', 'PART_TIME',
                'CONTRACTOR', 'TEMPORARY',
                'INTERN', 'VOLUNTEER',
                'PER_DIEM', 'OTHER'
            ])->default('OTHER');
            $table->string('lokasi')->nullable();
            $table->string('gaji')->nullable();
            $table->date('tanggal_terakhir')->nullable();
            $table->timestamps();
            $table->foreign('perusahaan_id')->references('id')
                ->on('perusahaan')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
            ;
        });
        Schema::create('form_lamaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pekerjaan_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('status')->nullable();
            $table->date('tanggal_apply')->nullable();
            $table->timestamps();
            $table->foreign('pekerjaan_id')
                ->references('id')
                ->on('pekerjaan')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
            ;
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
            ;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_lamaran');
        Schema::dropIfExists('pekerjaan');
        Schema::dropIfExists('perusahaan');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
