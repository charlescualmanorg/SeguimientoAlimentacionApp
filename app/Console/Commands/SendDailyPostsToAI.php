<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Services\OpenAIService;
use Carbon\Carbon;

class SendDailyPostsToAI extends Command
{
    protected $signature = 'posts:send-to-ai';
    protected $description = 'Envía los posts del día a la IA para análisis nutricional';
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        parent::__construct();
        $this->openAIService = $openAIService;
    }

    public function handle()
    {
        $fechaHoy = Carbon::today()->toDateString();

        // Obtener usuarios con publicaciones del día actual
        $usuarios = User::with(['posts' => function ($query) use ($fechaHoy) {
            $query->whereDate('created_at', $fechaHoy)->with('images');
        }])->get();

        foreach ($usuarios as $usuario) {
            // Si el usuario no tiene publicaciones, continuar con el siguiente
            if ($usuario->posts->isEmpty()) {
                continue;
            }

            foreach ($usuario->posts as $post) {
                // Verificar si ya ha sido analizado
                if ($post->is_analyzed) {
                    continue;
                }

                // Decodificar el campo JSON de enfermedades
                $diseases = json_decode($usuario->diseases, true) ?? [];

                // Crear un array con la información del usuario para pasar a la IA
                $userInfo = [
                    'age' => $usuario->age,
                    'height' => $usuario->height,
                    'weight' => $usuario->weight,
                    'diseases' => $diseases,  // Ya decodificado desde JSON
                ];

                // Registrar la información en la bitácora
                \Log::info('Enviando post a OpenAI para análisis', [
                    'post_id' => $post->id,
                    'user_id' => $usuario->id,
                    'content' => $post->content,
                    'user_info' => $userInfo
                ]);

                // Enviar el contenido del post y la información del usuario a la IA
                $response = $this->openAIService->analyzePostContent($post->content, $userInfo);

                // Verificar si hubo un error en la respuesta de la IA
                if (isset($response['error'])) {
                    $this->error("Error al analizar el post ID: {$post->id}");
                } else {
                    $this->info("Análisis completado para el post ID: {$post->id}");

                    // Guardar la respuesta de la IA en el campo 'ai_response' del post
                    $post->ai_response = $response['choices'][0]['message']['content'];
                    $post->is_analyzed = true;
                    $post->save();

                    // Crear un comentario automático para la publicación
                    Comment::create([
                        'post_id' => $post->id,
                        'user_id' => 'IA',  // Comentario del sistema
                        'content' => 'Tenemos ayuda para mejorar tu alimentación. Haz clic aquí para más detalles.',
                        'is_system_generated' => true,
                    ]);
                }
            }
        }
    }
}
