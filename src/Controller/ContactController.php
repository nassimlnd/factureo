<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setType('contact');

            $entityManager->persist($message);
            $entityManager->flush();

            $this->sendEmail($message, $logger, $mailer);

            return $this->redirectToRoute('app_contact', ['status' => 'success'], Response::HTTP_SEE_OTHER);
        }


        return $this->render('contact/index.html.twig', [
            'contactForm' => $form
        ]);
    }

    public function sendEmail(Message $message, LoggerInterface $logger, MailerInterface $mailer): void
    {
        $email = new TemplatedEmail();

        $email
            ->from(new Address('contact.factureo.fr@gmail.com', 'Contact - Facturéo'))
            ->to($message->getEmail())
            ->subject('Confirmation de votre demande de contact')
            ->htmlTemplate('contact/confirmation_email.html');

        $mailer->send($email);

        $logger->info('Sending contact confirmation email to ' . $message->getEmail());
    }
}
