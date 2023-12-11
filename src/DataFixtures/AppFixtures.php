<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\InvoiceDetails;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordHasher = $passwordEncoder;
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $companies = [];
        $customers = [];
        $invoices = [];

        for ($i = 0; $i < 10; $i++) {
            $company = new Company();
            $company
                ->setName($faker->company())
                ->setFullAdress($faker->address())
                ->setEmail('contact@' . $faker->domainName())
                ->setBusinessSector($faker->jobTitle())
                ->setLegalStatus($faker->jobTitle())
                ->setActivityType($faker->jobTitle())
                ->setCountry($faker->country())
                ->setPhoneNumber($faker->phoneNumber());
            array_push($companies, $company);
            $manager->persist($company);


        }

        foreach ($companies as $company) {
            for ($i = 0; $i < 10; $i++) {
                $customer = new Customer();
                $customer
                    ->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->email())
                    ->setFullAdress($faker->address())
                    ->setPhoneNumber($faker->phoneNumber())
                    ->setIsCompany(false)
                    ->setCompany($company);
                array_push($customers, $customer);
                $manager->persist($customer);
            }
        }

        foreach ($customers as $customer) {
            for ($i = 0; $i < 1; $i++) {
                $invoice = new Invoice();
                $invoice->setCustomer($customer)
                    ->setCompany($company)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setDueDate(new \DateTimeImmutable())
                    ->setState(0)
                    ->setType(0);
                $invoiceDetails = new InvoiceDetails();
                $invoiceDetails
                    ->setElement($faker->sentence())
                    ->setQuantity($faker->numberBetween(1, 10))
                    ->setUnitPrice($faker->numberBetween(100, 1000))
                    ->setInvoice($invoice);
                $manager->persist($invoiceDetails);
                $invoice->addDetail($invoiceDetails);
                $invoice->setTotalPrice($invoiceDetails->getQuantity() * $invoiceDetails->getUnitPrice());
                array_push($invoices, $invoice);
                $manager->persist($invoice);
            }
        }

        $userAdmin = new User();
        $userAdmin->setEmail('admin@factureo.com');
        $userAdmin->setFirstName('Admin');
        $userAdmin->setLastName('Admin');
        $userAdmin->setCompany($company);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword($this->passwordHasher->hashPassword($userAdmin, 'admin'));
        $manager->persist($userAdmin);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
