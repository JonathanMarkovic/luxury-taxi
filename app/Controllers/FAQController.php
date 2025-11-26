<?php

namespace App\Controllers;

use App\Domain\Models\FAQModel;
use App\Helpers\FlashMessage;
use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FAQController extends BaseController
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
        $data['data'] = [
            'title' => 'Admin FAQ',
            'faq' => $faq
        ];
        return $this->render(
            $response,
            '/admin/faq/faqIndexView.php',
            $data
        );
    }

    public function edit(Request $request, Response $response, array $args): Response
    {
        $faq_id = $args['faq_id'];
        $faq = $this->faqModel->fetchFAQById($faq_id);

        $data = [
            'title' => 'FAQ',
            'faq' => $faq
        ];

        return $this->render(
            $response,
            '/admin/faq/faqEditView.php',
            $data
        );
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $faq_id = $args['faq_id'] ?? null;
        if (!empty($faq_id)) {
            $this->faqModel->updateFAQ($faq_id, $request->getParsedBody());
            FlashMessage::success('FAQ updated successfully.');
        }
        return $response->withHeader('Location', APP_ADMIN_URL . '/faq')->withStatus(302);
    }

    public function add(Request $request, Response $response, array $args): Response
    {
        $data = [
            'title' => 'Add FAQ',
        ];

        return $this->render($response, '/admin/faq/faqCreateView.php', $data);
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $postedData = $request->getParsedBody();
        $question = $postedData['question'] ?? null;
        $answer = $postedData['answer'] ?? null;
        $data = [
            'question' => $question,
            'answer' => $answer
        ];
        if (!empty($question) && !empty($answer)) {
            $this->faqModel->createFAQ($data);
            FlashMessage::success('FAQ created successfully.');
        }
        return $response->withHeader('Location', APP_ADMIN_URL . '/faq')->withStatus(302);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $faq_id = $args['faq_id'] ?? null;
        if (!empty($faq_id)) {
            $this->faqModel->deleteFAQ($faq_id);
            FlashMessage::success('FAQ deleted successfully.');
        }
        return $response->withHeader('Location', APP_ADMIN_URL . '/faq')->withStatus(302);
    }
}
