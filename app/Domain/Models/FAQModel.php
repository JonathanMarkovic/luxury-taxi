<?php

namespace App\Domain\Models;

use App\Helpers\Core\PDOService;

class FAQModel extends BaseModel
{
    private $faq_table = "faq";
    public function __construct(PDOService $pdoservice) {
        parent::__construct($pdoservice);
    }

    /**
     * Summary of fetchFAQ
     * Fetched all FAQ from the database
     * @return array
     */
    public function fetchFAQ(): mixed {
        $sql = "SELECT * FROM {$this->faq_table}";

        $faq = $this->selectAll($sql);
        return $faq;
    }

    public function fetchFAQById($faq_id) {
        $sql = "SELECT * FROM faq WHERE faq_id = :faq_id";
        return $this->selectOne($sql, [':faq_id' => $faq_id]);

    }

    public function createFAQ(array $data) {
        $sql = "INSERT INTO faq (question, answer) VALUES (:question, :answer)";
        return $this->execute($sql, [
            ':question' => $data['question'],
            ':answer' => $data['answer']
        ]);
    }

    public function updateFAQ($faq_id, array $data) {
        $sql = "UPDATE faq SET question = :question, answer = :answer WHERE faq_id = :faq_id";
        return $this->execute($sql, [
            ':faq_id' => $faq_id,
            ':question' => $data['question'],
            ':answer' => $data['answer']
        ]);
    }

    public function deleteFAQ($faq_id) {
        $sql = "DELETE FROM faq WHERE faq_id = :faq_id";
        $this->execute($sql, [':faq_id' => $faq_id]);
        return true;
    }

}
