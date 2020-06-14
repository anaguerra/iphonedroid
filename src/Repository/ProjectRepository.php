<?php

namespace App\Repository;

use App\Entity\Projects;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @method Projects|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projects|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projects[]    findAll()
 * @method Projects[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $em;
    private SerializerInterface $serializer;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, Projects::class);
        $this->em = $em;
    }

    public function save(Projects $projects)
    {
        $this->em->persist($projects);
        $this->em->flush();
    }

    public function remove(Projects $projects)
    {
        $this->em->remove($projects);
        $this->em->flush();
    }
}
