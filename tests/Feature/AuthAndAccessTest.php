<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndAccessTest extends TestCase
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

    private function makeCourse(string $status = 'published'): Course
    {
        return Course::create([
            'title' => 'Test Course',
            'slug' => 'test-course-'.uniqid(),
            'description' => 'A course used in tests.',
            'level' => 'beginner',
            'price' => 0,
            'duration_hours' => 2,
            'status' => $status,
        ]);
    }

    public function test_guest_can_view_public_home_and_courses(): void
    {
        $this->get('/')->assertOk();
        $this->get('/courses')->assertOk();
    }

    public function test_guest_is_redirected_from_dashboard_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_owner_can_access_courses_admin(): void
    {
        $owner = $this->makeUser('owner', 'owner@test.local');

        $this->actingAs($owner)->get('/admin/courses')->assertOk();
        $this->actingAs($owner)->get('/admin/users')->assertOk();
    }

    public function test_student_cannot_access_courses_admin(): void
    {
        $student = $this->makeUser('student', 'student@test.local');

        $this->actingAs($student)->get('/admin/courses')->assertForbidden();
        $this->actingAs($student)->get('/admin/users')->assertForbidden();
    }

    public function test_student_can_view_my_courses(): void
    {
        $student = $this->makeUser('student', 'learner@test.local');

        $this->actingAs($student)->get('/my/courses')->assertOk();
    }

    public function test_student_can_enroll_in_published_course(): void
    {
        $student = $this->makeUser('student', 'enrollee@test.local');
        $course = $this->makeCourse();

        $this->actingAs($student)
            ->post("/courses/{$course->slug}/enroll")
            ->assertRedirect('/my/courses');

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $course->id,
            'user_id' => $student->id,
            'status' => 'active',
        ]);
    }

    public function test_owner_can_download_progress_export(): void
    {
        $owner = $this->makeUser('owner', 'export-owner@test.local');
        $course = $this->makeCourse();

        $response = $this->actingAs($owner)->get("/admin/courses/{$course->id}/progress-export");

        $response->assertOk();
        $this->assertStringStartsWith('text/csv', $response->headers->get('content-type'));
        $response->assertDownload("{$course->slug}-progress.csv");
    }

    public function test_student_cannot_download_progress_export(): void
    {
        $student = $this->makeUser('student', 'export-student@test.local');
        $course = $this->makeCourse();

        $this->actingAs($student)
            ->get("/admin/courses/{$course->id}/progress-export")
            ->assertForbidden();
    }
}
