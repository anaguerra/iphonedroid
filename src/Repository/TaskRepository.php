<?php

namespace App\Repository;

use App\Entity\Projects;
use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasks[]    findAll()
 * @method Tasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $em;
    private ProjectRepository $projectRepository;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em, ProjectRepository $projectRepository)
    {
        parent::__construct($registry, Tasks::class);
        $this->em = $em;
        $this->projectRepository = $projectRepository;
    }

    public function save(Tasks $projects)
    {
        $this->em->persist($projects);
        $this->em->flush();
    }

    public function remove(Tasks $projects)
    {
        $this->em->remove($projects);
        $this->em->flush();
    }

    public function findProject(int $id) : Projects
    {
        return $this->projectRepository->find($id);
    }
}
