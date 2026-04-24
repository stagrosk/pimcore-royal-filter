<?php

declare(strict_types=1);

namespace App\Service;

use App\Vendure\WebhookClient;
use OpenDxp\Model\DataObject\FilterRegistration;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FilterRegistrationNotificationService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly WebhookClient $webhookClient,
        private readonly LoggerInterface $logger,
        private readonly string $adminEmail
    ) {
    }

    public function notifyAdmin(FilterRegistration $registration): void
    {
        try {
            $email = $registration->getEmail() ?? 'no-email';
            $id = $registration->getId();

            $body = sprintf(
                "Nová registrácia filtra #%d\n\n" .
                "Email zákazníka: %s\n" .
                "Telefón: %s\n\n" .
                "Filter:\n" .
                "- Originálny kód: %s\n" .
                "- Výška: %s mm\n" .
                "- Priemer: %s mm\n" .
                "- Horný typ: %s\n" .
                "- Spodný typ: %s\n",
                $id,
                $email,
                $registration->getPhone() ?? '-',
                $registration->getOriginalLabel() ?? '-',
                $registration->getFilterHeight() ?? '-',
                $registration->getFilterDiameter() ?? '-',
                $registration->getTopType() ?? '-',
                $registration->getBottomType() ?? '-'
            );

            if ($registration->getHotTubBrand()) {
                $body .= sprintf(
                    "\nVírivka:\n" .
                    "- Značka: %s\n" .
                    "- Model: %s\n" .
                    "- Rok výroby: %s\n",
                    $registration->getHotTubBrand(),
                    $registration->getHotTubModel() ?? '-',
                    $registration->getYearOfManufacture() ?? '-'
                );
            }

            $body .= sprintf(
                "\nDetail v Pimcore: /admin/login/deeplink?object_%d_object",
                $id
            );

            $message = (new Email())
                ->to($this->adminEmail)
                ->subject(sprintf('Nová registrácia filtra #%d', $id))
                ->text($body);

            $this->mailer->send($message);

            $this->logger->info(sprintf('[FilterRegistration] Admin notification sent for #%d', $id));
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[FilterRegistration] Failed to send admin notification: %s', $e->getMessage()));
        }
    }

    public function requestDiscountCode(FilterRegistration $registration): void
    {
        try {
            $this->webhookClient->sendToVendureWebhook([
                'class' => get_class($registration),
                'type' => 'filter-registration',
                'id' => $registration->getId(),
                'action' => 'discount-code-request',
                'email' => $registration->getEmail(),
            ]);

            $this->logger->info(sprintf(
                '[FilterRegistration] Discount code request sent to Vendure for #%d',
                $registration->getId()
            ));
        } catch (\Throwable $e) {
            $this->logger->error(sprintf(
                '[FilterRegistration] Failed to request discount code: %s',
                $e->getMessage()
            ));
        }
    }
}
