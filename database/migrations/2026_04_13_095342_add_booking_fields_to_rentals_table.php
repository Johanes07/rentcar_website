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
    Schema::table('rentals', function (Blueprint $table) {
        $table->string('nama_penyewa')->nullable()->after('customer_id'); // ← tambah nullable
        $table->string('no_hp')->nullable()->after('nama_penyewa');       // ← tambah nullable
        $table->string('no_ktp')->nullable()->after('no_hp');             // ← tambah nullable
        $table->string('alamat')->nullable()->after('no_ktp');
        $table->string('foto_ktp')->nullable()->after('alamat');
        $table->string('bukti_transfer')->nullable()->after('foto_ktp');
        $table->enum('status_booking', ['pending', 'confirmed', 'cancelled', 'completed'])
              ->default('pending')->after('bukti_transfer');
        $table->text('catatan')->nullable()->after('status_booking');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('rentals', function (Blueprint $table) {
        $table->dropColumn([
            'nama_penyewa', 'no_hp', 'no_ktp', 'alamat',
            'foto_ktp', 'bukti_transfer', 'status_booking', 'catatan'
        ]);
    });
    }
};
