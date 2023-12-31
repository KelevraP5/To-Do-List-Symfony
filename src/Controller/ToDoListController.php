<?php

namespace App\Controller;

use App\Entity\ToDoList;
use App\Form\ToDoListType;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ToDoListController extends AbstractController
{
    #[Route('/', name: 'app_to_do_list_index', methods: ['GET'])]
    public function index(ToDoListRepository $toDoListRepository): Response
    {
        return $this->render('to_do_list/index.html.twig', [
            'to_do_lists' => $toDoListRepository->findAll(),
        ]);
    }

    #[Route('/todo/new', name: 'app_to_do_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $toDoList = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($toDoList);
            $entityManager->flush();

            return $this->redirectToRoute('app_to_do_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('to_do_list/new.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form,
        ]);
    }

    #[Route('/todo/{id}', name: 'app_to_do_list_show', methods: ['GET'])]
    public function show(ToDoList $toDoList): Response
    {
        return $this->render('to_do_list/show.html.twig', [
            'to_do_list' => $toDoList,
        ]);
    }

    #[Route('/todo/{id}/edit', name: 'app_to_do_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ToDoList $toDoList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ToDoListType::class, $toDoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_to_do_list_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('to_do_list/edit.html.twig', [
            'to_do_list' => $toDoList,
            'form' => $form,
        ]);
    }

    #[Route('/todo/{id}', name: 'app_to_do_list_delete', methods: ['POST'])]
    public function delete(Request $request, ToDoList $toDoList, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$toDoList->getId(), $request->request->get('_token'))) {
            $entityManager->remove($toDoList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_to_do_list_index', [], Response::HTTP_SEE_OTHER);
    }
}
