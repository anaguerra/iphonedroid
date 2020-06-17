<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    private ProjectRepository $projectRepository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, ProjectRepository $projectRepository)
    {
        parent::__construct($registry, Task::class);
        $this->em = $em;
        $this->projectRepository = $projectRepository;
    }

    public function save(Task $projects)
    {
        $this->em->persist($projects);
        $this->em->flush();
    }

    public function remove(Task $projects)
    {
        $this->em->remove($projects);
        $this->em->flush();
    }

    public function findProject(int $id) : Project
    {
        return $this->projectRepository->find($id);
    }
}
