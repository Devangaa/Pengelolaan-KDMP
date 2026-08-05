<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Models\MemberModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');

        $userModel              = new UserModel();
        $memberModel            = new MemberModel();
        $productModel           = new ProductModel();
        $transactionModel       = new TransactionModel();
        $transactionDetailModel = new TransactionDetailModel();

        $transactionModel->protect(false);
        $transactionDetailModel->protect(false);

        $userIds   = $userModel->findColumn('id');
        $memberIds = $memberModel->findColumn('id');
        $products  = $productModel->findAll();

        if (empty($userIds) || empty($memberIds) || empty($products)) {
            echo "Gagal: Seeder Transaction membutuhkan data User, Member, dan Product terlebih dahulu.\n";
            return;
        }

        $dates = [];
        for ($i = 0; $i < 15; $i++) {
            $dates[] = $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d');
        }
        sort($dates); 

        foreach ($dates as $dateString) {
            $fullDateTime = $dateString . ' ' . $faker->time('H:i:s');
            $dateFormatted = date('Ymd', strtotime($dateString));

            $todayTransactionCount = $transactionModel
                ->like('created_at', $dateString, 'after')
                ->countAllResults();

            $nextSequence = $todayTransactionCount + 1;
            
            $invoiceNumber = 'TRX-' . $dateFormatted . '-' . sprintf('%04d', $nextSequence);

            $itemCount   = rand(1, 4);
            $grandTotal  = 0;
            $detailsData = [];

            for ($j = 0; $j < $itemCount; $j++) {
                $product  = $faker->randomElement($products);
                $quantity = rand(1, 5);
                $price    = (int) $product['sell_price'];
                $subtotal = $price * $quantity;

                $grandTotal += $subtotal;

                $detailsData[] = [
                    'product_id' => $product['id'],
                    'price'      => $price,
                    'quantity'   => $quantity,
                    'subtotal'   => $subtotal,
                    'created_at' => $fullDateTime,
                    'updated_at' => $fullDateTime,
                ];
            }

            $pay = (int) (ceil($grandTotal / 10000) * 10000);
            if ($pay < $grandTotal) {
                $pay = $grandTotal;
            }
            $change = $pay - $grandTotal;

            $transactionData = [
                'transaction_id' => $invoiceNumber,
                'user_id'        => $faker->randomElement($userIds),
                'member_id'      => $faker->randomElement($memberIds),
                'total'          => $grandTotal,
                'pay'            => $pay,
                'change'         => $change,
                'created_at'     => $fullDateTime,
                'updated_at'     => $fullDateTime,
            ];

            $insertedUUID = $transactionModel->insert($transactionData);
            if ($insertedUUID === false) {
                continue;
            }

            foreach ($detailsData as $detail) {
                $detail['transaction_id'] = $insertedUUID;
                $transactionDetailModel->save($detail);
            }
        }
    }
}