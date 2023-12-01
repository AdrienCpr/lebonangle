<?php

namespace App\Command;

use App\Entity\Picture;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'DeleteOrphanedPictures',
    description: 'Delete all pictures with null advert_id and created X days ago'
)]
class DeleteOrphanedPicturesCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        parent::configure();
        $this
            ->addArgument('days', null, InputOption::VALUE_REQUIRED, 'Number of days ago')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $daysAgo = $input->getArgument('days');

            if (!is_numeric($daysAgo) || $daysAgo <= 0) {
                $io->error('Invalid number of days provided.');
                return Command::FAILURE;
            }

            $cutoffDate = new DateTime();
            $cutoffDate->modify('-' . $daysAgo . ' days');

            $io->success('Searching for orphaned pictures created ' . $daysAgo . ' days ago...');

            $pictures = $this->entityManager->getRepository(Picture::class)->createQueryBuilder('p')
                ->where('p.advert IS NULL')
                ->andWhere('p.createdAt <= :cutoffDate')
                ->setParameter('cutoffDate', $cutoffDate)
                ->getQuery()
                ->getResult();

            foreach ($pictures as $picture) {
                $this->entityManager->remove($picture);
            }

            $this->entityManager->flush();

            $io->success('All orphaned pictures created ' . $daysAgo . ' days ago have been deleted.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('An error occurred: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
