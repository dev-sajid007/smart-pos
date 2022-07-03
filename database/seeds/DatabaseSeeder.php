<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatusTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(SubModuleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
//         $this->call(UserRolesTableSeeder::class);
        $this->call(SystemAdminSeeder::class);
        $this->call(RolePermissionsTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(SubCategoryTableSeeder::class);
        $this->call(ProductUnitTableSeeder::class);
        $this->call(BillerTableSeeder::class);
        $this->call(CustomerCategorySeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(SupplierTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(AccountTableSeeder::class);
        $this->call(AccountChartTableSeeder::class);
        $this->call(PaymentTableSeeder::class);
        $this->call(PartyTableSeeder::class);
        $this->call(BillingCyclesTableSeeder::class);
        $this->call(PackageTableSeeder::class);
        $this->call(CompanyPackageTableSeeder::class);
        $this->call(SmsQuotasTableSeeder::class);
        $this->call(GlAccountTableSeeder::class);

        $this->call(PaymentMethodTableSeeder::class);
    }
}
