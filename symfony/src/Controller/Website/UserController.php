<?php

namespace App\Controller\Website;

use App\Entity\User;
use App\Form\PasswordResetType;
use App\Repository\ProfileRepository;
use App\Service\Website\UserService;
use App\Utils\CompanyHelper;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package App\Controller\Website
 * @Route("/user", name="icapps_website.user")
 */
class UserController extends AbstractController
{

    public function __construct(private MailerInterface $mailer, private LoggerInterface $logger, private TranslatorInterface $translator, private UserService $userService, private ProfileRepository $profileRepository, private CompanyHelper $companyHelper)
    {
    }

    /**
     * User confirmation/activation
     * @Route("/confirmation/{token}", name=".confirmation")
     * @param string $token
     * @return Response
     */
    public function confirmation(string $token): Response
    {
        $user = $this->userService->activateUser($token);

        return $this->confirmationResponse($user);
    }

    /**
     * User pending email confirmation
     * @Route("/confirmation/pending-email/{token}", name=".confirmation.pending_email")
     * @param string $token
     * @return Response
     */
    public function confirmationPendingEmail(string $token): Response
    {
        $user = $this->userService->activatePendingEmailOfUser($token);

        return $this->confirmationResponse($user);
    }

    /**
     * User password reset.
     * @Route("/password-reset/{token}", name=".reset")
     * @param string $token
     * @param Request $request
     * @return Response
     */
    public function passwordReset(string $token, Request $request): Response
    {
        // Validate reset token.
        $user = $this->userService->validatePasswordResetToken($token);
        if (!$user) {
            return $this->render('general/status.html.twig', [
                'companyData' => $this->companyHelper->getBaseCompanyInfo(),
                'title' => $this->translator->trans('icapps.website.lbl_user.reset.failed_title', [], 'messages'),
                'message' => $this->translator->trans('icapps.website.lbl_user.reset.failed_message', [], 'messages'),
            ]);
        }

        // Get reset form.
        $form = $this->createForm(PasswordResetType::class, [], [
            'attr' => ['class' => 'user--reset-form validate-form'],
        ]);

        // Include submit button.
        $form->add('submit', SubmitType::class, [
            'label' => $this->translator->trans('icapps.website.lbl_user.reset.form_submit', [], 'messages'),
            'attr' => ['class' => 'c-button--btn'],
            'disabled' => true,
        ]);

        $companyData = $this->companyHelper->getBaseCompanyInfo();

        // Check submission and validation.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve data.
            $data = $form->getData();
            $password = $data['password'] ?? null;

            // Reset password.
            $user = $this->userService->passwordResetUser($user, $password);
            $profile = $this->profileRepository->findById($user->getProfileId());

            // Success.
            return $this->render('general/status.html.twig', [
                'companyData' => $companyData,
                'title' => $this->translator->trans('icapps.website.lbl_user.reset.completed_title', ['%username' => $profile->getFirstName()], 'messages'),
                'message' => $this->translator->trans('icapps.website.lbl_user.reset.completed_message', [], 'messages'),
            ]);
        }

        // Password reset form.
        return $this->render('user/password-reset.html.twig', [
            'companyData' => $companyData,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param ?User $user
     * @return Response
     */
    private function confirmationResponse(?User $user): Response
    {
        $companyData = $this->companyHelper->getBaseCompanyInfo();
        if (!$user) {
            return $this->render('general/status.html.twig', [
                'companyData' => $companyData,
                'title' => $this->translator->trans('icapps.website.lbl_user.activation.failed_title', [], 'messages'),
                'message' => $this->translator->trans('icapps.website.lbl_user.activation.failed_message', [], 'messages'),
            ]);
        }

        $profile = $this->profileRepository->findById($user->getProfileId());

        return $this->render('general/status.html.twig', [
            'companyData' => $companyData,
            'title' => $this->translator->trans('icapps.website.lbl_user.activation.completed_title', ['%username' => $profile->getFirstName()], 'messages'),
            'message' => $this->translator->trans('icapps.website.lbl_user.activation.completed_message', [], 'messages'),
        ]);
    }
}
