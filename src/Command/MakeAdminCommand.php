<?php

namespace App\Command;

use App\Entity\Address;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

#[AsCommand(
    name: 'make:admin',
    description: 'Create admin',
)]
class MakeAdminCommand extends Command
{

    private $entityManager;

    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $address = new Address();
        $address->setStreet('Admin');
        $address->setCity('Admin');
        $address->setCountry('Admin');
        $address->setPostcode('Admin');
        $address->setState('Admin');
        $address->setFirstName('Admin');
        $address->setLastName('Admin');

        $admin = new User();
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $_ENV['ADMIN_PASSWORD']));
        $admin->setEmail('admin@admin.com');
        $admin->setAddress($address);
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        $this->entityManager->persist($admin);
        
        $this->entityManager->flush();

        $output->writeln('Admin successfully created');

        return Command::SUCCESS;
    }
}
