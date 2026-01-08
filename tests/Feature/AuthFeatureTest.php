namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_product()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('products.store'), [
            'name' => 'iPhone 15',
            'description' => 'Neuf, jamais utilisé',
            'price' => 999.99,
            'category_id' => $category->id,
            'image' => UploadedFile::fake()->image('iphone.jpg')
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('products', [
            'name' => 'iPhone 15',
            'user_id' => $user->id
        ]);
    }

    public function test_guest_cannot_create_product()
    {
        $response = $this->post(route('products.store'), [
            'name' => 'iPhone 15',
            'price' => 999.99,
        ]);

        $response->assertRedirect(route('login'));
    }
}
