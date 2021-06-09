<?php

namespace App\Controller\Website\User;

use App\Entity\User;
use App\Form\PasswordResetType;
use App\Repository\ProfileRepository;
use App\Service\Website\User\UserService;
use App\Utils\ProfileHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController
 * @package App\Controller\Website\User
 * @Route("/user", name="icapps_website.user")
 */
class UserController extends AbstractController
{

    public function __construct(
        private TranslatorInterface $translator,
        private UserService $userService,
        private ProfileHelper $profileHelper
    ) {
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
                'title' => $this->translator->trans('icapps.website.lbl_user.reset.failed_title', [], 'messages'),
                'message' => $this->translator->trans('icapps.website.lbl_user.reset.failed_message', [], 'messages'),
            ]);
        }

        // Get reset form.
        $form = $this->createForm(PasswordResetType::class);

        // Check submission and validation.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieve data.
            $data = $form->getData();
            $password = $data['password'] ?? null;

            // Reset password.
            $user = $this->userService->passwordResetUser($user, $password);
            $profile = $this->profileHelper->getProfile($user);

            // Success.
            return $this->render('general/status.html.twig', [
                'title' => $this->translator->trans('icapps.website.lbl_user.reset.completed_title', ['%username' => $profile->getFirstName()], 'messages'),
                'message' => $this->translator->trans('icapps.website.lbl_user.reset.completed_message', [], 'messages'),
            ]);
        }

        // Password reset form.
        return $this->render('user/password-reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param ?User $user
     * @return Response
     */
    private function confirmationResponse(?User $user): Response
    {
        if (!$user) {
            return $this->render('general/status.html.twig', [
                'title' => $this->translator->trans('icapps.website.lbl_user.activation.failed_title', [], 'messages'),
                'message' => $this->translator->trans('icapps.website.lbl_user.activation.failed_message', [], 'messages'),
            ]);
        }

        $profile = $this->profileHelper->getProfile($user);

        return $this->render('general/status.html.twig', [
            'title' => $this->translator->trans('icapps.website.lbl_user.activation.completed_title', ['%username' => $profile->getFirstName()], 'messages'),
            'message' => $this->translator->trans('icapps.website.lbl_user.activation.completed_message', [], 'messages'),
        ]);
    }
}
