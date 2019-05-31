<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo")
 */
class TodoController extends AbstractController
{
    /**
     * @Route("/", name="todo_index", methods={"GET", "POST"})
     */
    public function index(TodoRepository $todoRepository): Response
    {
	    if($_SERVER['REQUEST_METHOD'] == 'POST'){
					$todo = new Todo();
					$todo->setName($_POST['newTodo']);
					$todo->setIsChecked(false);
					$em = $this->getDoctrine()->getManager();
					$em->persist($todo);
					$em->flush();
					return $this->redirectToRoute('todo_index');
	    }
	
        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findAll(),
        ]);
    }

   



    /**
     * @Route("/edit", name="todo_edit", methods={"GET","POST"})
     */
    public function edit()
    {
	
	    if($_SERVER['REQUEST_METHOD'] == 'POST'){
	    	$em = $this->getDoctrine()->getManager();
	    	$todo = $em->find(Todo::class, key($_POST));
	    	if($todo->getIsChecked()){
	    		$todo->setIsChecked(false);
		    }else{
			    $todo->setIsChecked(true);
		    }
		    $em->persist($todo);
		    $em->flush();
	    	return $this->redirectToRoute('todo_index');
	    }
    }

    /**
     * @Route("/{id}", name="todo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Todo $todo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todo_index');
    }
}
