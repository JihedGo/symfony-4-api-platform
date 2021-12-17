<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $chrono = 1;
        for($c  = 0; $c < 50; $c++){
            $customer = new Customer();
            $customer->setFirstName($faker->firstName())
                   ->setLastName($faker->lastName())
                   ->setCompany($faker->company)
                   ->setEmail($faker->email);
            $manager->persist($customer);
            for($v = 0; $v < mt_rand(3,10); $v++){
                $invoice = new Invoice();
                $invoice->setAmount($faker->randomFloat(2, 250, 500))
                        ->setSentAt($faker->dateTimeBetween('-6 months','now'))
                        ->setStatus($faker->randomElement(['SENT','PAID', 'CANCELLED']))
                        ->setCustomer($customer)
                        ->setChrono($chrono);
                        $chrono++;
                $manager->persist($invoice);

            }
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
