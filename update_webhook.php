<?php
require_once('vendor/autoload.php');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Dotenv\Dotenv;

// Load the PayMongo API key from environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get the PayMongo secret key from environment variables
$secret_key = $_ENV['PAYMONGO_SECRET_KEY'] ?? null;
if (!$secret_key) {
    die("PayMongo secret key not set in environment variables.\n");
}

// Webhook details
$webhook_url = 'https://017f-27-49-8-118.ngrok-free.app/onlshoe/webhook.php'; // Corrected the extra " at the end
$webhook_description = 'GCash Payment Webhook'; // Description for the webhook
$webhook_events = ['source.chargeable', 'payment.paid', 'payment.failed']; // Events to listen for

// Validate the webhook URL
if (!filter_var($webhook_url, FILTER_VALIDATE_URL) || parse_url($webhook_url, PHP_URL_SCHEME) !== 'https') {
    die("Invalid webhook URL. It must be a valid HTTPS URL.\n");
}

// Create the client
$client = new Client([
    'base_uri' => 'https://api.paymongo.com',
    'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode($secret_key . ':'),
    ],
    'verify' => false, // Temporarily disable SSL verification (for development only)
]);

try {
    // Check for existing webhooks
    $response = $client->get('/v1/webhooks');
    $webhooks = json_decode($response->getBody(), true);

    foreach ($webhooks['data'] as $webhook) {
        if ($webhook['attributes']['url'] === $webhook_url) {
            die("Webhook already exists with ID: " . $webhook['id'] . "\n");
        }
    }

    // Create a new webhook
    $response = $client->post('/v1/webhooks', [
        'json' => [
            'data' => [
                'attributes' => [
                    'url' => $webhook_url,
                    'description' => $webhook_description,
                    'events' => $webhook_events,
                ],
            ],
        ],
    ]);

    // Decode and validate the response
    $response_data = json_decode($response->getBody(), true);
    if (!isset($response_data['data']['id'])) {
        die("Invalid response: Webhook ID not found.\n");
    }

    // Output the webhook details
    echo "Webhook created successfully!\n";
    echo "Webhook ID: " . $response_data['data']['id'] . "\n";
    echo "Webhook URL: " . $response_data['data']['attributes']['url'] . "\n";
    echo "Events: " . implode(', ', $response_data['data']['attributes']['events']) . "\n";

    // Save the webhook secret key securely
    $webhook_secret = $response_data['data']['attributes']['secret_key'];
    file_put_contents('webhook_secret.txt', $webhook_secret);
    echo "Webhook Secret Key saved successfully.\n";
} catch (RequestException $e) {
    // Log errors
    file_put_contents('error_log.txt', "Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);

    if ($e->hasResponse()) {
        $response = $e->getResponse();
        file_put_contents('error_log.txt', "Response Status: " . $response->getStatusCode() . PHP_EOL, FILE_APPEND);
        file_put_contents('error_log.txt', "Response Body: " . $response->getBody() . PHP_EOL, FILE_APPEND);
    }

    // Display the error to the user
    echo "Error creating webhook. Check the error_log.txt for details.\n";
}
