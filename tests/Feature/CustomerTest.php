<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Wallet;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function testOneToOne()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::query()->find("NOZAMI");
        $this->assertNotNull($customer);

        $wallet = $customer->wallet;
        $this->assertNotNull($wallet);
        $this->assertEquals(1000000000, $wallet->amount);
    }
    
    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = "NOZAMI";
        $customer->name = "Nozami";
        $customer->email = "nozami@gmail.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 1000000;

        $customer->wallet()->save($wallet);
        $this->assertNotNull($wallet->customer_id);
    }

    public function testHasONeThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find("NOZAMI");
        $this->assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        $this->assertNotNull($virtualAccount);
        $this->assertEquals("BCA", $virtualAccount->bank);
    }
}
