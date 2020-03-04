<?php

namespace App\Command;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanupCommand extends Command
{
    protected static $defaultName = 'app:cleanup';
    private $entityManager;

    /**
     * CleanupCommand constructor.
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
            ->setDescription('Efface toutes les réservations');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        foreach ($this->entityManager->getRepository(Reservation::class)->findAll() as $reservation) {
            $this->entityManager->remove($reservation);
        }
        $this->entityManager->flush();

        $io->success('Reservations effacées !');

        return 0;
    }
}
