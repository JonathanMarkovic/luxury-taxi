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

        $to = "taliamuro3@gmail.com";
        $subject = "Client Question";
        $headers = "From: $email";

        if (!empty($email) && !empty($message)) {
            if (mail($to, $subject, $message, $headers)) {
                FlashMessage::success("Email sent successfully!");
            } else {
                FlashMessage::error("Email sending failed.");
            }
        } else {
            FlashMessage::error("Please fill in all fields.");
        }

        return $response->withHeader('Location', APP_USER_URL . '/faqs')->withStatus(302);
    }
}
