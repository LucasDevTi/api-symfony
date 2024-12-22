<?php

namespace App\Command;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'App:Seed:User', 
    description: 'Insere um usuário padrão no banco de dados.',
)]
class AppSeedUserCommand extends Command
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $existingUser = $this->entityManager->getRepository(Users::class)->findOneBy(['email' => 'lucas@tecnologia.com']);
        if ($existingUser) {
            $io->note('O usuário "lucas@tecnologia.com" já existe no banco de dados!');
            return Command::SUCCESS;
        }

        $user = new Users();
        $user->setNome('lucas');
        $user->setEmail('lucas@tecnologia.com');
        $user->setTipoUsuario(1);  
        $user->setPassword($this->passwordHasher->hashPassword($user, '1234')); 

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Usuário "lucas@tecnologia.com" criado com sucesso!');

        return Command::SUCCESS;
    }
}
