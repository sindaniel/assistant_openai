<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Este comando sube un archivo PDF a OpenAI y lo convierte en un vector store
Artisan::command('upload', function () {

    $apiKey = config('services.openai.key');
    $client = OpenAI::client($apiKey);
    $path = public_path('colombia.pdf');
    $name = 'Solvo Colombia 2025';
    
    //Primero lo subo a storage
    $response = $client->files()->upload([
        'purpose' => 'assistants',
        'file' => fopen($path, 'r'),
    ]);

   // dd($response);
    $file_id = $response->id;

    //Luego lo convierto a vector store
    $response = $client->vectorStores()->create([
        'file_ids' => [
            $file_id,
        ],
        'name' => $name,
    ]);

    $vector_store_id = $response->id;
    $this->comment($vector_store_id);

});

// Este comando crea un nuevo hilo de conversación con el asistente
Artisan::command('create_thread', function () {
    $apiKey = config('services.openai.key');
    $client = OpenAI::client($apiKey);

    $response = $client->threads()->create([]);
    $thread_id = $response->id;
    $this->comment($thread_id);
});

// Este comando crea un nuevo asistente con instrucciones específicas y acceso al PDF
Artisan::command('create_assistant', function () {
    $apiKey = config('services.openai.key');
    $client = OpenAI::client($apiKey);
    $name = 'Solvo Colombia 2025';

    $response = $client->assistants()->create([
        'name' => $name,
        'instructions' => 'Tu eres un asistente que solo va responder en base al pdf,dado, tampoco referirse al documento sino a la base de datos, SOLO DAR RESPUESTA DEL PDF. antes de todo debes de preguntar el area para que sepas dar una mejor respuesta ya que existen varias areas: ADMINISTRATIVE PROCESSES AND COMPENSATION ATTRITION AND RETENTION BE WELL BEING CYBERSECURITY ADMINISTRATION AND FACILITIES HR VENSURE INFORMATION TECHNOLOGY MARKETING OPERATIONS PEOPLE AND CULTURE SOLVO INTERNSHIP SOLVO SOCIAL TALENT ACQUISITION TRAINING LABOR RELATIONS',
        'tools' => [
            [
                'type' => 'file_search',
            ],
        ],
        'tool_resources' => [
            'file_search' => [
                'vector_store_ids' => [
                    'vs_RBm5Vf5c5oL8F740zG4OjO0d',
                ],
            ],
        ],
        'model' => 'gpt-3.5-turbo-0125',
    ]);

    $assistant_id = $response->id;
    $this->comment($assistant_id);
});

// Este comando inicia una conversación con el asistente usando un hilo y asistente específicos
Artisan::command('chat', function () {
    $apiKey = config('services.openai.key');
    $client = OpenAI::client($apiKey); 

    $threadId = 'thread_Han3NyplDGUlA182DtshqCSu';
    $assistant_id='asst_rhx8mjhKKFiVR08GtUM2jQvW';

    // Crea un nuevo mensaje en el hilo
    $response = $client->threads()->messages()->create($threadId, [
        'role' => 'user',
        'content' => 'el de operations',
    ]);

    // Inicia una nueva ejecución con el asistente
    $response = $client->threads()->runs()->create(
        threadId: $threadId, 
        parameters: [
            'assistant_id' => $assistant_id,
        ],
    );

    // Espera mientras el asistente procesa la respuesta
    while ($response->status === 'queued' || $response->status === 'in_progress') {
        sleep(3);
        $response = $client->threads()->runs()->retrieve($threadId, $response->id);
    }

    // Obtiene y muestra la última respuesta
    $messages = $client->threads()->messages()->list($threadId, [
        'limit' => 1,
    ]);

    $this->comment($messages->data[0]->content[0]->text->value);
    $this->comment($response->usage->totalTokens);
    
  #  dd($response,);
});

// Este comando muestra una frase inspiradora
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
