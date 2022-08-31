<?php

namespace Tests;

use App\Quiz;
use App\Question;


use PHPUnit\Framework\TestCase;

class QuizTest extends TestCase
{
    /** @test */
    public function contains_questions()
    {
        $quiz = new Quiz();
        $quiz->addQuestion(new Question('Capital of Poland?', "Warsaw"));
        $this->assertCount(1, $quiz->questions());
    }
    /** @test */
    public function grades_perfect_quiz()
    {
        $quiz = new Quiz();
        $quiz->addQuestion(new Question('Capital of Poland?', "Warsaw"));


        $question = $quiz->nextQuestion();
        $question->answer("Warsaw");
        $this->assertEquals(100, $quiz->grade());
    }
    /** @test */
    public function grades_failed_quiz()
    {
        $quiz = new Quiz();
        $quiz->addQuestion(new Question('Capital of Poland?', "Warsaw"));

        $question = $quiz->nextQuestion();
        $question->answer("Cracov");
        $this->assertEquals(0, $quiz->grade());
    }
    /** @test */
    public function cannot_be_grade_till_all_questions_answered()
    {
        $quiz = new Quiz();
        $quiz->addQuestion(new Question('Capital of Poland?', "Warsaw"));

        $this->expectException(\Exception::class);
        $quiz->grade();
    }

    /** @test */
    public function it_correctly_tracks_next_question_in_queue()
    {
        $quiz = new Quiz();
        $quiz->addQuestion($question1 = new Question("whats is 2+2", 4));
        $quiz->addQuestion($question2 = new Question("whats is 2+4", 6));
        $this->assertEquals($question1, $quiz->nextQuestion());
        $this->assertEquals($question2, $quiz->nextQuestion());
    }

    /** @test */
    public function knows_when_completed()
    {
        $quiz = new Quiz();
        $quiz->addQuestion(new Question('Capital of Poland?', "Warsaw"));
        $this->assertFalse($quiz->isComplete());
        $quiz->nextQuestion()->answer('Warsaw');
        $this->assertTrue($quiz->isComplete());
    }
}
