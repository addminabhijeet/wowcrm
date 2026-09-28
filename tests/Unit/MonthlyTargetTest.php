<?php

namespace Tests\Unit;

use App\Models\MonthlyTarget;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class MonthlyTargetTest extends TestCase
{
    private Manager $database;
    private $originalResolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->originalResolver = Model::getConnectionResolver();
        $this->database = new Manager();
        $this->database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
        $this->database->bootEloquent();
        $this->database->getConnection()->getSchemaBuilder()->create('monthly_targets', function ($table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('year');
            $table->unsignedInteger('month');
            $table->integer('target');
            $table->timestamps();
            $table->unique(['user_id', 'year', 'month']);
        });
    }

    protected function tearDown(): void
    {
        $this->database->getConnection()->disconnect();
        if ($this->originalResolver) {
            Model::setConnectionResolver($this->originalResolver);
        } else {
            Model::unsetConnectionResolver();
        }
        parent::tearDown();
    }

    public function test_missing_months_are_created_without_overwriting_existing_targets(): void
    {
        $existing = MonthlyTarget::create(['user_id' => 1, 'year' => 2026, 'month' => 3, 'target' => 2500]);
        $before = $existing->fresh()->getAttributes();

        MonthlyTarget::ensureDefaults(1, 2026);

        $this->assertSame(12, MonthlyTarget::count());
        $this->assertSame($before, $existing->fresh()->getAttributes());
        $this->assertSame(11, MonthlyTarget::where('target', 1000)->count());
    }

    public function test_complete_year_uses_one_read_and_does_not_write(): void
    {
        MonthlyTarget::ensureDefaults(1, 2026);
        $before = MonthlyTarget::orderBy('id')->get()->toArray();
        $connection = $this->database->getConnection();
        $connection->enableQueryLog();

        MonthlyTarget::ensureDefaults(1, 2026);

        $queries = $connection->getQueryLog();
        $this->assertCount(1, $queries);
        $this->assertStringStartsWith('select', $queries[0]['query']);
        $this->assertSame($before, MonthlyTarget::orderBy('id')->get()->toArray());
    }

    public function test_other_users_and_years_do_not_hide_missing_months(): void
    {
        MonthlyTarget::ensureDefaults(1, 2025);
        MonthlyTarget::ensureDefaults(2, 2026);
        MonthlyTarget::ensureDefaults(1, 2026);

        $this->assertSame(36, MonthlyTarget::count());
        $this->assertSame(12, MonthlyTarget::where('user_id', 1)->where('year', 2026)->count());
    }
}
