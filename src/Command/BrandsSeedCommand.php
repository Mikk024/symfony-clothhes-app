<?php

namespace App\Command;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'brands:seed',
    description: 'Seed the database with brands',
)]
class BrandsSeedCommand extends Command
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $brands = [
            'Adidas',
            'Nike',
            'Jordan',
            'Carhartt',
            'Reebok'
        ];

        foreach ($brands as $brandName) {
            $brand = new Brand();
            $brand->setName($brandName);
            $this->entityManager->persist($brand);
        }

        $this->entityManager->flush();

        $output->writeln('The brands were successfully seeded');

        return Command::SUCCESS;
    }
}
