<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'username' => 'AdminGod',
            'role' => 1
        ]);

        // Categories
        \App\Models\Category::create([
            'name' => 'Seguridad',
        ]);
        \App\Models\Category::create([
            'name' => 'Sensores',
        ]);
        \App\Models\Category::create([
            'name' => 'Otros',
        ]);

        // Currencies
        \App\Models\Currency::create([
            'name' => 'Soles',
            'short_name' => 'PEN',
            'symbol' => 'S/',
        ]);
        \App\Models\Currency::create([
            'name' => 'Dólares americanos',
            'short_name' => 'USD',
            'symbol' => '$',
        ]);

        // Units
        \App\Models\Unit::create([
            'name' => 'UNIDAD (BIENES)',
            'code' => 'NIU',
        ]);
        \App\Models\Unit::create([
            'name' => 'UNIDAD (SERVICIOS)',
            'code' => 'ZZ',
        ]);
        \App\Models\Unit::create([
            'name' => 'METRO',
            'code' => 'MTR',
        ]);

        // Taxes
        \App\Models\Tax::create([
            'code' => '10',
            'name' => 'IGV',
            'percentage' => 0.18,
            'type' => '1000',
        ]);
        \App\Models\Tax::create([
            'code' => '10',
            'name' => 'IGV',
            'percentage' => 0.10,
            'type' => '1000',
        ]);
        \App\Models\Tax::create([
            'code' => '20',
            'name' => 'Exonerado',
            'percentage' => 0.00,
            'type' => '9996',
        ]);

        // Products
        \App\Models\Product::create([
            "code" => "P0001",
            "name" => "Sensor de temperatura",
            "image" => "OLpkXlb2loWvq2yXwgACaXe9pZStC6FUK2MUaYrF.jpg",
            "category_id" => 2,
            "unit_id" => 1,
            "tax_id" => 1,

            "selling_price" => 16.9492,
            "selling_price_currency_id" => 1,
        ]);

        // Storehouses
        \App\Models\Storehouse::create([
            "name" => "Almacén principal",
            "user_id" => 1,
        ]);

        // Inventories
        \App\Models\Inventory::create([
            "product_id" => 1,
            "storehouse_id" => 1,
            "total" => 100,
        ]);

        // ExchageRates
        \App\Models\ExchangeRate::create([
            "provider" => 1,
            "currency_id" => 2,
            "rate" => 3.7000,
        ]);


        // EntityDocumentTypes
        \App\Models\EntityDocumentType::create([
            'code' => '1',
            'name' => 'Documento Nacional de Identidad',
            'abbreviated' => 'DNI',
        ]);
        \App\Models\EntityDocumentType::create([
            'code' => '6',
            'name' => 'Registro Único de Contribuyentes',
            'abbreviated' => 'RUC',
        ]);
        \App\Models\EntityDocumentType::create([
            'code' => '0',
            'name' => 'OTROS',
            'abbreviated' => 'OTROS',
        ]);

        // Entities
        \App\Models\Entity::create([
            'type' => 1,
            'name' => 'ALVARO HUAYSARA JAUREGUI',
            'document_type_id' => 1,
            'document_number' => 70768167,
            'address' => 'Los Laureles Mz E Lt 13',
            'ubigeo' => '321321',
            'phone' => '926513695',
            'email' => 'alvarohuaysara@gmail.com',
        ]);


        // Company
        \App\Models\Company::create([
            "ruc" => "43214321432",
            "business_name" => "VEGACORP SOLUTIONS S.A.C",
            "trade_name" => "-",
            "fiscal_address" => "AV. CANADA NRO. 1966",
            "ubigeo" => "150130",
            "district" => "LIMA",
            "province" => "LIMA",
            "department" => "LIMA",

            "phone" => "987654321",
            "email" => "vengas@vegas.pe",
            "website" => "www.vegas.pe",
            "logo" => "nxrcaTstc3tSO2zuvwNwdj7LQC8QGttPb9QHCKVm.png",

            "secondary_user_username" => "MODDATOS",
            "secondary_user_password" => "MODDATOS",
            "client_id" => "test-85e5b0ae-255c-4891-a595-0b98c65c9854",
            "client_secret" => "test-Hty/M6QshYvPgItX2P0+Kw==",
            "access_token" => "test-eyJhbGciOiJIUzUxMiJ9.ImExODY3NjM3LWQ2YzItNGNkYi05OTc4LTcwYzQ4YjU5NjAwMSI.7Rqqbrq6Cr5TYa5BjHmlSk_HdgD7XaMeQb48i_W8aVAh3CDzkuYBOWZLy24bYI11s_rJFKXqVKx7RiuD7XuA9A",
        ]);

        // DocumentTypes
        \App\Models\DocumentType::create([
            "code" => "01",
            "name" => "Factura",
            "abbreviated" => "Factura",
        ]);
        \App\Models\DocumentType::create([
            "code" => "03",
            "name" => "Boleta de venta",
            "abbreviated" => "Boleta",
        ]);

        // Serials
        \App\Models\Serial::create([
            "serial" => "F001",
            "correlative" => 1,
            "description" => "Factura electrónica",
            "document_type_id" => 1,
        ]);
    }
}
