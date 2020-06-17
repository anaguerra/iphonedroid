<?php


namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProjectController
 * @package App\Controller
 * @Route("/api", name="task_api")
 */
class ProjectController extends AbstractController
{

    private ProjectRepository $projectRepository;
    private SerializerInterface $serializer;

    public function __construct(ProjectRepository $projectRepository, SerializerInterface $serializer)
    {
        $this->projectRepository = $projectRepository;
        $this->serializer = $serializer;
    }

    /**
     * @return JsonResponse
     * @Route("/projects", name="project_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $data = $this->projectRepository->findAll();
        return new JsonResponse($this->serializer->serialize(
            $data,
            'json', ['groups' => 'read']
        ), 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @Route("/projects/{id}", name="project_show", methods={"GET"})
     */
    public function show($id) : JsonResponse
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            $data = [
                'status' => 404,
                'errors' => "Project not found",
            ];
            return new JsonResponse(['data' => $data], 404);
        }
        return new JsonResponse($this->serializer->serialize(
            $project,
            'json', ['groups' => 'read']
        ), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     * @Route("/projects", name="project_store", methods={"POST"})
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $content = $request->getContent();
            $json = json_decode($content);

            if (!$json || !$json->name || !$json->description) {
                throw new \Exception();
            }

            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $projects = new Project();
            $projects->setUser($user)
                ->setName($json->name)
                ->setDescription($json->description);

            $this->projectRepository->save($projects);

            $data = [
                'status' => 200,
                'success' => "Project added successfully",
            ];
            return new JsonResponse(['data' => $data]);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return new JsonResponse(['data' => $data]);
        }
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @Route("/projects/{id}", name="project_update", methods={"PUT"})
     */
    public function update(Request $request, $id)
    {
        try {
            $project = $this->projectRepository->find($id);

            if (!$project) {
                $data = [
                    'status' => 404,
                    'errors' => "Project not found",
                ];
                return new JsonResponse(['data' => $data], 404);
            }
            $content = $request->getContent();
            $json = json_decode($content);

            if (!$json || !$json->name || !$json->description) {
                throw new \Exception();
            }

            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $project->setUser($user)
                ->setName($json->name)
                ->setDescription($json->description);

            $this->projectRepository->save($project);

            $data = [
                'status' => 200,
                'errors' => "Project updated successfully",
            ];
            return new JsonResponse(['data' => $data]);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return new JsonResponse(['data' => $data], 422);
        }
    }


    /**
     * @param $id
     * @return JsonResponse
     * @Route("/projects/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            $data = [
                'status' => 404,
                'errors' => "Project not found",
            ];
            return new JsonResponse(['data' => $data], 404);
        }
        $this->projectRepository->remove($project);

        $data = [
            'status' => 200,
            'errors' => "Project deleted successfully",
        ];
        return new JsonResponse(['data' => $data]);
    }
}
