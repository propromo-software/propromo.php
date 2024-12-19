        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration {
            public function up(): void
            {
                Schema::create('repositories', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->boolean('is_custom')->nullable();
                    $table->integer('custom_repository_id')->unique()->nullable();
                    $table->foreignId('monitor_id');
                    $table->timestamps();
                });
            }

            public function down(): void
            {
                Schema::dropIfExists('repositories');
            }
        };
