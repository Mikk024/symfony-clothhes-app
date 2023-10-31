<?php

namespace App\Command;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


#[AsCommand(
    name: 'types:seed',
    description: 'Seed the database with types',
)]
class SeedTypesCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $types = [
            'Clothes',
            'Shoes'
        ];

        foreach ($types as $typeName) {
            $type = new Type();
            $type->setName($typeName);
            $this->entityManager->persist($type);
        }

        $this->entityManager->flush();

        $output->writeln('The types were successfully seeded');

        return Command::SUCCESS;
    }
}
