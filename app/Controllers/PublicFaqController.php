<?php

namespace App\Controllers;

use App\Domain\Models\FAQModel;
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
        $data['data'] = [
            'title' => 'Admin FAQ',
            'faq' => $faq
        ];
        return $this->render($response, '/public/faq/faqListView.php', $data);
    }
}
