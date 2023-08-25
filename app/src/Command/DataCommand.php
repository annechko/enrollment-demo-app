<?php

declare(strict_types=1);

namespace App\Command;

use App\Core\Admin\Entity\AdminUser\AdminUser;
use App\Core\School\Entity\Campus\Campus;
use App\Core\School\Entity\Course\Course;
use App\Core\School\Entity\Course\Intake\Intake;
use App\Core\School\Entity\School\School;
use App\Core\School\Entity\School\StaffMember;
use App\Core\Student\Entity\Application\Application;
use App\Core\Student\Entity\Student\Student;
use App\DbDefaultData\AdminUserCreator;
use App\DbDefaultData\SchoolDataCreator;
use App\DbDefaultData\SchoolDefaultAccountCreator;
use App\DbDefaultData\StudentUserCreator;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:data',
    description: 'Clear db and fill with data.',
)]
class DataCommand extends Command
{
    private const ENTITIES_TO_TRUNCATE = [
        AdminUser::class,
        Campus::class,
        Course::class,
        Intake::class,
        School::class,
        StaffMember::class,
        Student::class,
        Application::class,
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Connection $connection,
        private readonly AdminUserCreator $adminUserCreator,
        private readonly SchoolDefaultAccountCreator $schoolDefaultAccountCreator,
        private readonly StudentUserCreator $studentUserCreator,
        private readonly SchoolDataCreator $schoolDataCreator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDefinition(
                [
                    new InputOption(
                        'no-clear',
                        '',
                        InputOption::VALUE_NONE,
                        'Do not delete any data, add default data'
                    ),
                    new InputOption(
                        'clear-only',
                        'c',
                        InputOption::VALUE_NONE,
                        'Delete all data, do not add any data'
                    ),
                ]
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$input->getOption('no-clear')) {
            $this->clearDb($io);
        }
        if (!$input->getOption('clear-only')) {
            $this->addData($io);
        }

        $io->success('Success.');

        return Command::SUCCESS;
    }

    private function clearDb(SymfonyStyle $io): void
    {
        foreach (self::ENTITIES_TO_TRUNCATE as $name) {
            $io->info("Truncating $name");
            $this->truncateTable($this->entityManager->getClassMetadata($name)->getTableName());
        }
        $io->info('Truncated all.');
    }

    private function truncateTable(string $tableName): void
    {
        $this->connection
            ->executeStatement("TRUNCATE $tableName CASCADE;");
    }

    private function addData(SymfonyStyle $io): void
    {
        $io->info('Create admin.');
        $this->entityManager->persist(
            $this->adminUserCreator->create()
        );
        $this->entityManager->flush();

        $io->info('Create school.');
        $this->entityManager->persist(
            $this->schoolDefaultAccountCreator->create()
        );
        $this->entityManager->flush();

        $io->info('Create student.');
        $this->entityManager->persist(
            $this->studentUserCreator->create()
        );
        $this->entityManager->flush();

        $io->info('Create schools, courses, etc.');
        $entities = $this->schoolDataCreator->createEntities();
        $c = 0;
        foreach ($entities as $entity) {
            ++$c;
            $this->entityManager->persist($entity);
            if ($c % 200 === 0) {
                $this->entityManager->flush();
            }
        }
        $this->entityManager->flush();

        $io->info('Done.');
    }
}
