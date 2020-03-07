<?php

namespace App\Command;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends Command
{
    protected static $defaultName = 'app:cleanup';
    private          $entityManager;

    /**
     * CleanupCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Efface toutes les réponses marquées pour suppression quotidienne');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        foreach ($this->entityManager->getRepository(Question::class)
                     ->findBy(array("estRAZQuotidien" => true)) as $question) {
            foreach ($question->getReponses() as $reponse) {
                $this->entityManager->remove($reponse);
            }
        }
        $this->entityManager->flush();

        $io->success('Réponses effacées !');

        return 0;
    }
}
