<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\RegistrationCompanyFormType;
use App\Form\RegistrationFormType;
use App\Form\RegistrationUserFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use function Symfony\Component\Translation\t;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register_user')]
    public function startRegistration(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('app_user_dashboard');
        }

        $user = new User();
        $form = $this->createForm(RegistrationUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $request->getSession()->set('user_data', $user);

            return $this->redirectToRoute('app_register_company');
        }

        return $this->render('registration/register_user.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/company', name: 'app_register_company')]
    public function registerCompany(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(RegistrationCompanyFormType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('company_data', $company);

            return $this->redirectToRoute('app_register_offer');
        }


        return $this->render('registration/register_company.html.twig', [
            'registrationCompanyForm' => $form->createView(),
        ]);
    }

    #[Route('/register/offer', name: 'app_register_offer')]
    public function registerOffer(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offers = [
            [
                'price' => 'Gratuit',
                'name' => 'Starter',
                'description' => 'Parfait pour n’importe qui, un auto-entrepreneur qui vient de débuter',
                'isPrincipal' => false,
                'features' => [
                    '5 clients maximum',
                    'Factures illimités',
                    'Devis illimités'
                ]
            ],
            [
                'price' => '5€',
                'name' => 'Petite entreprise',
                'description' => 'Parfait pour les petites / moyennes entreprises',
                'isPrincipal' => true,
                'features' => [
                    '30 clients maximum',
                    'Factures illimités',
                    'Devis illimités',
                    'Envoi de factures automatisé',
                    'Statistiques et rapports'
                ]
            ],
            [
                'price' => '15€',
                'name' => 'Grandes entreprises',
                'description' => 'Parfait pour les grandes entreprises, quelque soit la taille',
                'isPrincipal' => false,
                'features' => [
                    'Clients illimités',
                    'Factures illimités',
                    'Devis illimités',
                    'Envoi de factures automatisé',
                    'Statistiques et rapports',
                    'Accès à toutes les fonctionnalités exclusives'
                ]
            ],
        ];

        return $this->render('registration/register_offer.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/register/success', name: 'app_register_success')]
    public function registerSuccess(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $user = $request->getSession()->get('user_data', new User());
        $company = $request->getSession()->get('company_data', new Company());

        $selectedOffer = $request->query->get('offer');


        $entityManager->persist($company);
        $entityManager->flush();

        $user->setCompany($company);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('contact.factureo.fr@gmail.com', 'Contact - Facturéo'))
                ->to($user->getEmail())
                ->subject('Confirmation de votre e-mail')
                ->htmlTemplate('registration/confirmation_email.html'),
            $logger
        );

        $request->getSession()->remove('user_data');
        $request->getSession()->remove('company_data');

        $this->redirectToRoute('app_register_success');

        return $this->render('registration/register_success.html.twig');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register_user');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register_user');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register_user');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        // $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_user_dashboard');
    }
}
