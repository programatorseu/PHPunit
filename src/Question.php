<?php

namespace App;

class Question
{
    protected $question;
    protected $solution;
    protected $answer;
    protected $correct;



    public function __construct($question, $solution)
    {
        $this->question = $question;
        $this->solution = $solution;
    }

    public function answer($answer)
    {
        $this->answer = $answer;
        return $this->correct = $answer === $this->solution;
    }
    public function isCorrect()
    {
        return $this->correct;
    }
    public function answered()
    {
        return isset($this->answer);
    }
}
