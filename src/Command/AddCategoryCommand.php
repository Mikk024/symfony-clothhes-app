<?php

namespace App\Command;

use App\Entity\Category;
use App\Entity\Type;
use App\Repository\CategoryRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'category:add',
    description: 'Add a short description for your command',
)]
class AddCategoryCommand extends Command
{

    private $entityManager;
    private $typeRepository;

    public function __construct(EntityManagerInterface $entityManager, TypeRepository $typeRepository)
    {
        $this->entityManager = $entityManager;
        $this->typeRepository = $typeRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $categoryQuestion = new Question('Enter type name: ');
        $name = $helper->ask($input, $output, $categoryQuestion);

        $types = $this->typeRepository->findAll();
        $choices = [];
        foreach ($types as $type) {
            $choices[$type->getid()] = $type->getName();
        }

        $output->writeln($choices);

        $typeQuestion = new ChoiceQuestion('Choose a category: ', $choices);

        $typeName = $helper->ask($input, $output, $typeQuestion);

        $type = $this->typeRepository->findOneBy(['name' => $typeName]);

        $category = new Category();
        $category->setName($name);
        $category->setType($type);

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $output->writeln('You successfully added ' . $name .  ' category');

        return Command::SUCCESS;
    }
}
