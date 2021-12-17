<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * L'encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($u=0; $u < 10; $u++) { 
            $chrono = 1;
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'secret123');
            $user->setLastName($faker->lastName)
                 ->setFirstName($faker->firstName)
                 ->setEmail($faker->email)
                 ->setPassword($hash);
            $manager->persist($user);
            for($c  = 0; $c < mt_rand(10,30); $c++){
                $customer = new Customer();
                $customer->setFirstName($faker->firstName())
                       ->setLastName($faker->lastName())
                       ->setCompany($faker->company)
                       ->setEmail($faker->email)
                       ->setUser($user);
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
        }
       
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
