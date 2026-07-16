<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role, string $email): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $email,
            'password' => 'password',
            'role' => $role,
            'status' => 'active',
        ]);
    }

    private function makeCourse(): Course
    {
        return Course::create([
            'title' => 'Test Course',
            'slug' => 'test-course-'.uniqid(),
            'description' => 'A course used in tests.',
            'level' => 'beginner',
            'price' => 0,
            'duration_hours' => 2,
            'status' => 'published',
        ]);
    }

    private function makeQuiz(Course $course): Quiz
    {
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'Chapter Quiz',
            'pass_score' => 50,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'What is 2 + 2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'b',
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'What color is the sky?',
            'option_a' => 'Green',
            'option_b' => 'Red',
            'option_c' => 'Blue',
            'option_d' => 'Yellow',
            'correct_option' => 'c',
        ]);

        return $quiz;
    }

    public function test_student_can_submit_quiz_attempt(): void
    {
        $student = $this->makeUser('student', 'quiz-student@test.local');
        $course = $this->makeCourse();
        $quiz = $this->makeQuiz($course);

        $enrollment = Enrollment::create([
            'course_id' => $course->id,
            'user_id' => $student->id,
            'enrolled_at' => today(),
            'status' => 'active',
        ]);

        $answers = [];
        foreach ($quiz->questions as $question) {
            $answers[$question->id] = $question->correct_option;
        }

        $this->actingAs($student)
            ->post("/my/courses/{$enrollment->id}/quizzes/{$quiz->id}/submit", ['answers' => $answers])
            ->assertRedirect("/my/courses/{$enrollment->id}/quizzes/{$quiz->id}");

        $this->assertDatabaseHas('quiz_attempts', [
            'enrollment_id' => $enrollment->id,
            'quiz_id' => $quiz->id,
            'score' => 100,
            'passed' => true,
        ]);
    }

    public function test_non_instructor_cannot_create_quiz(): void
    {
        $student = $this->makeUser('student', 'quiz-blocked@test.local');
        $course = $this->makeCourse();

        $this->actingAs($student)
            ->post("/admin/courses/{$course->id}/quizzes", [
                'title' => 'Blocked Quiz',
                'pass_score' => 70,
            ])
            ->assertForbidden();
    }
}
