Testing Jargon

terminology differs from group to group :):)

# 1. Unit test 

folder structure :

``` mkdir src tests
 composer init
 ````



composer.json : 

```json
{
    "name": "piotrsadowski/unit-test",
    "authors": [
        {
            "name" : "Piotr Sadowski",
            "email" : "programatorseu@gmail.com"
        }
    ],
    "require": {},
    "autoload": {
        "psr-4": {
            "DevPiotr\\": "src/"
        }
    },
    "autload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    }
}


```

```bash
composer require phpunit/phpunit
```

create TagParserTeset

- example of what it does / how it should work 

> we have tag for blog, we put in form as comma seperated list 
>
> we want tu put them into array and then into db

**Test Driven Development : **

0 extend TestCase class 

1. create class that does not exist yet 

2. call parse method on class (pass arguments)
3. what do we expect to return ? - array - **we are going to use assertion**

**write at least amount of code to pass a test**



```php
<?php
namespace Tests;
use PHPUnit\Framework\TestCase;
use App\TagParser;
class TagParserTest extends TestCase
{
    /** @test */
    public function it_parses_a_comma_seperated_list_of_tags()
    {
        $parser = new TagParser;
        $result = $parser->parse('personal');
        $expected = ["personal"];
        $this->assertSame($expected, $result);
    }
}

```



```bash
<?php
namespace App;
class TagParser
{
    public function parse(string $tags)
    {
        return [$tags];
    }
}

```

```bash
vendor/bin/phpunit tests --colors
```

unit test - focus on 1 unit of our application / more isolated - for single class or small collection

we test specific test 

**end-to-end**

open browser - visit page - fill in form - submit - expect result 

Test for multiple tags

we are going to use preg_split 

```php
    public function test_it_parses_a_pipe_seperated_list_of_tags()
    {
        $parser = new TagParser;
        $result = $parser->parse('personal | money | family');
        $expected = ['personal', 'money', 'family'];
        $this->assertSame($expected, $result);
    }

```

parse : 

```php
<?php
namespace App;
class TagParser
{
    public function parse(string $tags): array
    {
        return preg_split('/[,|] ?/', $tags);
    }
}

```

> Failed asserting that two arrays are identical.
> --- Expected
> +++ Actual
> @@ @@
> Array &0 (
>
> -    0 => 'personal'
> -    1 => 'money'
>
> +    0 => 'personal '
> +    1 => 'money '
>      2 => 'family'
>       )

we got space after personal

```php
<?php
namespace App;
class TagParser
{
    public function parse(string $tags): array
    {
        $tags = preg_split('/[,|] ?/', $tags);
        return array_map(function($tags) {
            return trim($tags);
        }, $tags);
    }
}

```

## 2. Setup the World

AAA - Arrange / Action / Asset 



```php
        //given == Arrange
        $parser = new TagParser();
        // setup env = Act
        $result = $parser->parse('personal');
        $expected = ["personal"];
        //then == assert
        $this->assertSame($expected, $result);
```

we have a lot of repetition 

we can use setup function : 

```php
class TagParserTest extends TestCase
{
    protected TagParser $parser;

    protected function setUp():void
    {
        $this->parser = new TagParser();
    }
    /** @test */
    public function it_parses_a_single_tag()
    {
        // setup env = Act
        $result = $this->parser->parse('personal');
        $expected = ["personal"];
        //then == assert
        $this->assertSame($expected, $result);
    }
```

## 3.  **When to Reach for Data Providers**

data providers to simplify 

- add dataProvider flag 

Data Providers allow us to define tests once and run them multiple times with different datasets. 

Using a data provider requires three steps:

1. @dataProvider <name of provider>

2. create method when we return : 

   [input, whatWeExpect]

```php
<?php
namespace Tests;
use PHPUnit\Framework\TestCase;
use App\TagParser;
class TagParserTest extends TestCase
{
    /**
    * @dataProvider tagsProvider
    */
    public function test_it_parses_tags($input, $expected)
    {
        $parser = new TagParser();
        $result = $parser->parse($input);
        $this->assertSame($expected, $result);
    }
    public function tagsProvider()
    {
        return [
            ["personal", ["personal"]],
            ["personal, money, family", ["personal", "money", "family"]],
            ["personal | money | family", ["personal", "money", "family"]]
            // ["personal | money | family", ["personal", "money", "family"]]
        ];
    }
```

## 4. Unit test is not locked to single class

it may touch 2 or 3 classes 

unit test can interact database 

QuizTest:

```php
<?php
namespace Tests;
use App\Quiz;
use App\Question;

use PHPUnit\Framework\TestCase;

class QuizTest extends TestCase
{
    /** @test **/
    public function it_consists_of_questions()
    {
        $quiz = new Quiz();

        $quiz->addQuestion(new Question("What is 2 +2", 4));

        $this->assertCount(1, $quiz->questions());
    }
}

```

quiz & question class 

```php
<?php
namespace App;
class Quiz
{
    protected $questions;
    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }
    public function questions()
    {
        return $this->questions;
    }
}

```



add another test

- quiz can be graded 

> it is like scratchpad to check how we would like to interact with our quiz 

```php
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
}

```

```php
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

```

insid Quiz 

```php
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

```

## 5. Test Dummy

```php
    /** @test */
    public function creating_a_subscription_marks_user_as_subscribed()
    {
        $gateway = new Gateway();
        $subscription = new Subscription($gateway);
        $user = new User('Piotrek');
        $this->assertFalse($user->isSubscribed());
        // $subscription->create($user);
        // $this->assertTrue($user->isSubscribed());
    }
}
```

with GateWay -we make http call - that is slow request

we can mimic with FakeGateway - dummy version of it 

inside tests create FakeGatweay

```php
<?php

namespace Tests;

class FakeGateway
{

    public function create()
    {
    }
}
```

use php 8 constructor option : 

```php
    public function __construct(protected Gateway $gateway)
```

Make Gateway as interface



=> `createMock` - create that dummy for us

```php
    public function creating_a_subscription_marks_user_as_subscribed()
    {
        $gateway = $this->createMock(Gateway::class);
```

### 6. Stubs

Test double is a subtistitute for real object 

in case of mock and dummy - any method will return just null to fill in parameter list 

Dummy -> 

Mock  if sth needs to be delivered or run 

Mock includes exepctations 

return dummy object into a stub 

inside our test : 

- `$gateway->method('create')->willReturn('receipt-stub');`

Subscription@create:

```php
    public function create(User $user)
    {
        $receipt = $this->gateway->create();
        die(var_dump($receipt));
```

we expect 1 call to deliver method with argumnets 



**stub**:

- no expectation

if create method is called this is what i want you return 

**but not has to !! **

```php
   $gateway = $this->createMock(Gateway::class);
    $gateway->method('create')->willReturn('receipt-stub');
```

**dummy**

object that will meet criteria / fill parameter list 

```php
$this->createMock(Mailer::class)
```

**mock**

Mock includes exepctations 

- we need to assert that delivered method was called on it 

```php
        $mailer = $this->createMock(Mailer::class);
        $mailer
            ->expects($this->once())
            ->method('deliver')
            ->with('Your receipt number is: receipt-stub');
```



### 7. Feature Test

- feature of system
- zoomed out perspective

when we make post request --> hit registration point and send data --> user will be authenticated and redirected 0



## 8. Continous Integration 

-> ingegrate code changes into main repository

Github Actios 

Travis CI 
