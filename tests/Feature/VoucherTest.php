<?php

namespace Tests\Feature;

use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoucherTest extends TestCase
{

    public function testCreateVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->voucher_code = "2352626263";
        $voucher->save();

        $this->assertNotNull($voucher->id);
    }

    public function testCreateVoucherUUID()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->save();

        $this->assertNotNull($voucher->id);
        $this->assertNotNull($voucher->voucher_code);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::query()->where("name", "=", "sample Voucher")->first();
        $voucher->delete();

        $voucher = Voucher::query()->where("name", "=", "sample Voucher")->first();
        $this->assertNull($voucher);

        $voucher = Voucher::withTrashed()->where("name", "=", "sample Voucher")->first();
        $this->assertNotNull($voucher);
    }
}
