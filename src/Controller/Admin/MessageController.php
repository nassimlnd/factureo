<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/message')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_admin_message_index', methods: ['GET'])]
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('admin/message/index.html.twig', [
            'messages' => $messageRepository->findParents(),
        ]);
    }

    #[Route('/new', name: 'app_admin_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_message_show', methods: ['GET'])]
    public function show(Message $message, MessageRepository $messageRepository): Response
    {
        $conversation = $messageRepository->findByParent($message->getId());

        return $this->render('admin/message/show.html.twig', [
            'messages' => $conversation,
            'parent' => $message,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_message_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/response', name: 'app_admin_message_response', methods: ['POST'])]
    public function response(Request $request, EntityManagerInterface $entityManager, int $id, Message $parentMessage, LoggerInterface $logger) : Response
    {
        $data = json_decode($request->getContent(), true);
        $content = $data['messageContent'];

        $message = new Message();
        $message->setContent($content);
        $message->setParentId($id);
        $message->setFullName($this->getUser()->getLastName() . $this->getUser()->getFirstName());
        $message->setEmail($this->getUser()->getEmail());
        $message->setSubject('RE: ' . $parentMessage->getSubject());
        $message->setType('contact-response');

        $entityManager->persist($message);
        $entityManager->flush();

        return new Response(status: 200);
    }
}
