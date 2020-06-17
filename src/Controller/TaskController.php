<?php


namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class TaskController
 * @package App\Controller
 * @Route("/api", name="task_api")
 */
class TaskController extends AbstractController
{

    private TaskRepository $taskRepository;
    private SerializerInterface $serializer;

    public function __construct(TaskRepository $taskRepository, SerializerInterface $serializer)
    {
        $this->taskRepository = $taskRepository;
        $this->serializer = $serializer;
    }

    /**
     * @return JsonResponse
     * @Route("/tasks", name="task_index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $data = $this->taskRepository->findAll();
        return new JsonResponse($this->serializer->serialize(
            $data,
            'json', ['groups' => 'read']
        ), 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @Route("/tasks/{id}", name="task_show", methods={"GET"})
     */
    public function show($id) : JsonResponse
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            $data = [
                'status' => 404,
                'errors' => "Task not found",
            ];
            return new JsonResponse(['data' => $data], 404);
        }
        return new JsonResponse($this->serializer->serialize(
            $task,
            'json', ['groups' => 'read']
        ), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/tasks", name="task_store", methods={"POST"})
     */
    public function store(Request $request ): JsonResponse
    {
        try {
            $content = $request->getContent();
            $json = json_decode($content);

            if (!$json || !$json->name || !$json->description || !$json->project_id) {
                throw new \Exception();
            }

            $project = $this->taskRepository->findProject($json->project_id);

            $tasks = new Task();
            $tasks->setProject($project)
                ->setName($json->name)
                ->setDescription($json->description);

            $this->taskRepository->save($tasks);

            $data = [
                'status' => 200,
                'success' => "Task added successfully",
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
     * @Route("/tasks/{id}", name="task_update", methods={"PUT"})
     */
    public function update(Request $request, int $id)
    {
        try {
            $task = $this->taskRepository->find($id);

            if (!$task) {
                $data = [
                    'status' => 404,
                    'errors' => "Task not found",
                ];
                return new JsonResponse(['data' => $data], 404);
            }
            $content = $request->getContent();
            $json = json_decode($content);

            if (!$json || !$json->name || !$json->description) {
                throw new \Exception();
            }

            $task->setName($json->name)
                ->setDescription($json->description);

            $this->taskRepository->save($task);

            $data = [
                'status' => 200,
                'errors' => "Task updated successfully",
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
     * @Route("/tasks/{id}", name="task_delete", methods={"DELETE"})
     */
    public function delete($id)
    {
        $task= $this->taskRepository->find($id);

        if (!$task) {
            $data = [
                'status' => 404,
                'errors' => "Task not found",
            ];
            return new JsonResponse(['data' => $data], 404);
        }
        $this->taskRepository->remove($task);

        $data = [
            'status' => 200,
            'errors' => "Task deleted successfully",
        ];
        return new JsonResponse(['data' => $data]);
    }
}
