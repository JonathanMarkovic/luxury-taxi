<?php

namespace App\Controllers;

use App\Domain\Models\FAQModel;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicFaqController extends BaseController
{
    public function __construct(Container $container, private FAQModel $faqModel)
    {
        parent::__construct($container);
    }

    /**
     * Summary of index
     * The index page for the FAQ admin dashboard.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array $args
     * @return float|int
     */
    public function index(Request $request, Response $response, array $args): Response
    {
        $faq = $this->faqModel->fetchFAQ();
        $data = [
            'title' => 'FAQ',
            'faq' => $faq,
            'current_page' => 'faq'
        ];
        return $this->render($response, '/public/faq/faqListView.php', $data);
    }

    public function submit(Request $request, Response $response, array $args)
    {
        $questionFormData = $request->getParsedBody();
        $email = $questionFormData['email'];
        $message = $questionFormData['message'];

        $to = SEND_FROM;
        $subject = "Client Question";
        $message = $message . "\r\n\r\nSent from $email";

        if (!empty($email) && !empty($message)) {
            try {
                sendMail($to, $subject, $message);
                FlashMessage::success("Email sent! Our team will get back to you as soon as possible.");
            } catch (\Exception $e) {
                FlashMessage::error("Failed to send email. Please try again.");
            }
        } else {
            FlashMessage::error("Please fill in all fields.");
        }

        return $response->withHeader('Location', APP_USER_URL . '/faqs')->withStatus(302);
    }
}
