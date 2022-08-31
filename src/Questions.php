<?php

namespace App;

use Countable;

class Questions implements Countable
{
    protected array $questions;
    public function __construct(array $questions = [])
    {
        $this->questions = $questions;
    }
    public function add(Question $question)
    {
        $this->questions[] = $question;
    }
    public function count(): int
    {
        return count($this->questions);
    }
}
