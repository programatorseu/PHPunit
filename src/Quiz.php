<?php

namespace App;

class Quiz
{
    protected array $questions;
    protected $currentQuestion = 1;
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }
    public function questions()
    {
        return $this->questions;
    }
    public function nextQuestion()
    {
        $question = $this->questions[$this->currentQuestion - 1];
        $this->currentQuestion++;
        return $question;
    }
    public function grade()
    {
        if (!$this->isComplete()) {
            throw new \Exception("This quiz has not yet been completed");
        }
        $correct = count(array_filter($this->questions, function ($question) {
            return $question->isCorrect();
        }));
        $total = count($this->questions);
        return $correct / $total * 100;
    }

    public function isComplete()
    {
        $answeredQuestions = count(array_filter($this->questions, fn ($question) => $question->answered()));
        $totalQuestions = count($this->questions);
        return $answeredQuestions === $totalQuestions;
    }
}
