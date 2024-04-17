<?php

use function Pest\Laravel\{get};

use App\Contracts\Receipt;
use App\Helpers\Email;
use App\Mail\PostHtmlMail;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use App\Models\Service;
use App\Models\Template;
use App\Services\EmailService;
use Illuminate\Support\Facades\Mail;

it('must return a 404', function () {

    $sample = [
        'name' => fake()->unique()->catchPhrase(),
        'sku' => fake()->unique()->ean8(),
        'barcode' => fake()->ean13(),
        'options' => ['Colour', 'Size'],
        'published_at' => fake()->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d H:i'),
        'status' => fake()->boolean(),
        'quantity' => fake()->randomNumber(2),
        'price' => fake()->randomFloat(2, 1, 1000),
    ];

    $response = $this->post('/api/services/demos/send', $sample);
    $response->assertStatus(404);
});


it('must return a 201', function () {

    Template::factory()->create();
    $service = Service::factory()->create();

    $data = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "customer_email" => fake()->email(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email()
        ]
    ];

    $response = $this->postJson("/api/services/{$service->slug}/send", $data);
    $response->assertStatus(201);
});


it('can see a sucessful result', function () {

    Template::factory()->create();
    $service = Service::factory()->create();

    $data = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "customer_email" => fake()->email(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email()
        ]
    ];

    $response = $this->postJson("/api/services/{$service->slug}/send", $data);
    $response->assertJsonStructure([
        'message',
        'queue_id'
    ]);
});


it(' is sending a missing data', function () {

    Template::factory()->create();
    $service = Service::factory()->create();

    $data = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email()
        ]
    ];

    $response = $this->postJson("/api/services/{$service->slug}/send", $data);
    $response->assertJsonStructure([
        'message',
        'queue_id'
    ]);
});

it(' is sending a wrong data', function () {

    Template::factory()->create();
    $service = Service::factory()->create();

    $data = [
        
    ];

    $response = $this->postJson("/api/services/{$service->slug}/send", $data);
    $response->assertJsonStructure([
        'message',
        'errors'
    ]);
});

it(' is sending multiple email', function () {

    Template::factory()->create();
    $service = Service::factory()->create();

    $data = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email(), fake()->email()
        ]
    ];

    $response = $this->postJson("/api/services/{$service->slug}/send", $data);
    $response->assertJsonStructure([
        'message',
        'queue_id'
    ]);
});

test('mail sent', function () {
    Mail::fake();

    Template::factory()->create();
    $service = Service::factory()->create();

    $data = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "customer_email" => fake()->email(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email()
        ]
    ];

    $this->postJson("/api/services/{$service->slug}/send", $data);

    Mail::assertQueued(PostHtmlMail::class);
});

test(' contains the correct variables', function () {
    Mail::fake();

    Template::factory()->create();
    $service = Service::factory()->create();

    $template_subject = $service->template_subject;
    $request = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "customer_email" => fake()->email(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email()
        ]
    ];
 
    $email = new Email();
    $email->setService($service);
    $email->createContent($request['data']);
    $email->setTransactionId();

/*     $to_emails = $email->getEmailsFromRequest($data);

    $contactGroups = $service->contactGroups()->with('contacts')->get();
    $receipt = (new Receipt())
                ->setToEmails($to_emails)
                ->setGroupEmails($contactGroups);

    $emailService = new EmailService(); */


    $mailable = new PostHtmlMail($email);
    $mailable->assertSeeInHtml($request['data']['customer_name']);
    $mailable->assertSeeInHtml($request['data']['customer_email']);
    $mailable->assertSeeInHtml($request['data']['product_name']);
    $mailable->assertHasSubject($template_subject);    
});
 

test(' is sending the email coorect  the correct receipts', function () {
    Mail::fake();

    Template::factory()->create();
    $service = Service::factory()->create();

    $request = [
        'data' =>
        [
            "customer_name" => fake()->name(),
            "customer_email" => fake()->email(),
            "product_name" => fake()->catchPhrase()
        ],
        'to' => [
            fake()->email()
        ]
    ];
 
    $email = new Email();
    $email->setService($service);
    $email->createContent($request['data']);
    $email->setTransactionId();

    $to_emails = $email->getEmailsFromRequest($request['to']);

    $contactGroups = $service->contactGroups()->with('contacts')->get();
    $receipt = (new Receipt())
                ->setToEmails($to_emails)
                ->setGroupEmails($contactGroups);
 
    $mailable = new PostHtmlMail($email);
    $email_collection = $receipt->getEmailCollection();
    $chunks = $email_collection->chunk(1);
   
    foreach ($chunks as $chunk) {
        $mailable->to($chunk);
    }

    foreach ($chunks as $chunk) {
        $mailable->assertTo($chunk);
    }
});