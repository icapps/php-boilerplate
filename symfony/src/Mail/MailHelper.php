<?php

namespace App\Mail;

use App\Component\Model\ProfileInterface;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailHelper
{

    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
        private RouterInterface $router
    ) {
        //
    }

    /**
     * Send registration activation e-mail.
     *
     * @param User $user
     * @param ProfileInterface $profile
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendRegistrationActivationMail(User $user, ProfileInterface $profile): void
    {
        // Check user requirements.
        if (!$userEmail = $user->getEmail()) {
            $this->logger->critical('No email found for user: ' . $user->getId());
            return;
        }

        // Generate activation link.
        $link = $this->router->generate(
            'icapps_website.user.confirmation',
            ['token' => $user->getActivationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // User language.
        $defaultLocale = $this->translator->getLocale();
        $userLanguage = $user->getLanguage();
        $this->translator->setLocale($userLanguage);

        // Get activation mail body.
        $body = $this->twig->render(
            'emails/registration-activation.html.twig',
            [
                'username' => $profile->getFirstName(),
                'link' => $link,
                'email' => $user->getEmail(),
            ]
        );

        $this->translator->setLocale($defaultLocale);

        // Send mail.
        $this->sendMail(
            $this->translator->trans('icapps.mail.activation.title', ['%brand' => $_ENV['BRAND']], 'messages', $userLanguage),
            $body,
            $userEmail,
            'registration'
        );
    }

    /**
     * Sends confirmation mail for a pending email address.
     *
     * @param User $user
     * @param ProfileInterface $profile
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendPendingEmailActivation(User $user, ProfileInterface $profile): void
    {
        // Check user requirements.
        if (!$userPendingEmail = $user->getPendingEmail()) {
            $this->logger->critical('No pending email found for user: ' . $user->getId());
            return;
        }

        // Generate activation link.
        $link = $this->router->generate(
            'icapps_website.user.confirmation.pending_email',
            ['token' => $user->getActivationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // User language.
        $defaultLocale = $this->translator->getLocale();
        $userLanguage = $user->getLanguage();
        $this->translator->setLocale($userLanguage);

        // Get mail body.
        $body = $this->twig->render(
            'emails/pending-email-activation.html.twig',
            [
                'username' => $profile->getFirstName(),
                'link' => $link,
                'email' => $user->getPendingEmail(),
            ]
        );

        $this->translator->setLocale($defaultLocale);

        // Send mail.
        $this->sendMail(
            $this->translator->trans('icapps.mail.pending.title', ['%brand' => $_ENV['BRAND']], 'messages', $userLanguage),
            $body,
            $userPendingEmail,
            'registration'
        );
    }

    /**
     * Send registration confirmation e-mail.
     *
     * @param User $user
     * @param ProfileInterface $profile
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendRegistrationConfirmationMail(User $user, ProfileInterface $profile): void
    {
        // Check user requirements.
        if (!$userEmail = $user->getEmail()) {
            $this->logger->critical('No email found for user: ' . $user->getId());
            return;
        }

        // User language.
        $defaultLocale = $this->translator->getLocale();
        $userLanguage = $user->getLanguage();
        $this->translator->setLocale($userLanguage);

        // Get activation mail body.
        $body = $this->twig->render(
            'emails/registration-confirmation.html.twig',
            [
                'username' => $profile->getFirstName(),
            ]
        );

        $this->translator->setLocale($defaultLocale);

        // Send mail.
        $this->sendMail(
            $this->translator->trans('icapps.mail.confirmation.title', ['%brand' => $_ENV['BRAND']], 'messages', $userLanguage),
            $body,
            $userEmail,
            'registration'
        );
    }

    /**
     * Send password reset e-mail.
     *
     * @param User $user
     * @param ProfileInterface $profile
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function sendUserPasswordResetMail(User $user, ProfileInterface $profile): void
    {
        // Check user requirements.
        if (!$userEmail = $user->getEmail()) {
            $this->logger->critical('No email found for user: ' . $user->getId());
            return;
        }

        // Generate reset link.
        $link = $this->router->generate(
            'icapps_website.user.reset',
            ['token' => $user->getResetToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        // User language.
        $defaultLocale = $this->translator->getLocale();
        $userLanguage = $user->getLanguage();
        $this->translator->setLocale($userLanguage);

        // Get reset mail body.
        $body = $this->twig->render(
            'emails/user-password-reset.html.twig',
            [
                'username' => $profile->getFirstName(),
                'link' => $link,
            ]
        );

        $this->translator->setLocale($defaultLocale);

        // Send mail.
        $this->sendMail(
            $this->translator->trans('icapps.mail.reset_password.title', ['%brand' => $_ENV['BRAND']], 'messages', $userLanguage),
            $body,
            $userEmail,
            'password-reset'
        );
    }

    /**
     * @param string $subject
     * @param string $body
     * @param string $recipient
     * @param string $mg_tag
     * @param string|null $sender
     * @param bool $html
     * @param string|null $plainText
     * @param array $attachments
     */
    public function sendMail(
        string $subject,
        string $body,
        string $recipient,
        string $mg_tag = 'general',
        ?string $sender = null,
        bool $html = true,
        ?string $plainText = null,
        array $attachments = []
    ): void {
        // Create message.
        $message = new Email();

        // Set defaults.
        if (!$sender) {
            $sender = $_ENV['BRAND_EMAIL'] ?? $_ENV['ADMIN_EMAIL'];
        }

        if ($html) {
            $message->html($body);
        }

        $message->from($sender);
        $message->to($recipient);
        $message->subject($subject);

        // Add tags.
        $message->getHeaders()->addTextHeader('X-Mailgun-Tag', $mg_tag);

        // Plain text.
        if (null != $plainText) {
            $message->text($plainText);
        }

        // Include attachments.
        if (!empty($attachments)) {
            foreach ($attachments as $key => $file) {
                if ($file instanceof SplFileInfo) {
                    $path = $file->getPathname();
                    $name = $key;

                    // If uploaded file get original name.
                    if ($file instanceof UploadedFile) {
                        $name = $file->getClientOriginalName();
                    }

                    if (filter_var($path, FILTER_VALIDATE_URL) && $attachment = fopen($path, 'r')) {
                        // [INFO] Symfony webserver: this can crash locally if you use proxy domain, use BASE_URL 127.0.0.1:8000 for symfony server testing
                        $message->attach($attachment, $name);
                    } else {
                        $message->attachFromPath($path, $name);
                    }
                }
            }
        }

        try {
            $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            $this->logger->critical('Could not send email ' . $subject . ' :' . $e->getMessage() . ' / debug: ' . $e->getDebug());
            $this->logMailAction($subject, $sender, $recipient, $subject, $body, $plainText);
        }
    }

    /**
     * @param mixed $title
     * @param mixed $sender
     * @param mixed $recipient
     * @param mixed $subject
     * @param mixed $body
     * @param mixed $plainText
     */
    public function logMailAction(
        mixed $title,
        mixed $sender,
        mixed $recipient,
        mixed $subject,
        mixed $body,
        mixed $plainText
    ): void {
        $this->logger->warning(
            sprintf(
                'Error for mail %s: ' . PHP_EOL .
                '   From: %s' . PHP_EOL .
                '   To: %s' . PHP_EOL .
                '   Subject: %s' . PHP_EOL .
                '   Body: %s' . PHP_EOL .
                '   Plain text: %s' . PHP_EOL,
                is_string($title) ? $title : serialize($title),
                is_string($sender) ? $sender : serialize($sender),
                is_string($recipient) ? $recipient : serialize($recipient),
                is_string($subject) ? $subject : serialize($subject),
                is_string($body) ? $body : serialize($body),
                is_string($plainText) ? $plainText : serialize($plainText)
            )
        );
    }
}
