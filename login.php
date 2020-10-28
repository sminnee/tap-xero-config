<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();

$provider = new \Calcinai\OAuth2\Client\Provider\Xero([
    'clientId'          => $_ENV['XERO_CLIENT_ID'],
    'clientSecret'      => $_ENV['XERO_CLIENT_SECRET'],
    'redirectUri'       => 'https://tap-xero-config.lndo.site/login.php',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl([
        'scope' => 'openid email profile offline_access accounting.transactions.read accounting.journals.read accounting.contacts.read accounting.settings.read'
    ]);

    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {
    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    echo "<h1>tap-xero dev config generator</h1>";
    echo "<p>Choose one of the following config sets based on the organsiation you wish to access. Fill in the correct start date.</p>";

    $tenants = $provider->getTenants($token);
    foreach ($tenants as $tenant) {
        echo "<h2>$tenant->tenantName</h2>";
        // Generate a config for tap-xero
        $config = [
            'start_date' => 'yyyy-mm-dd',
            'client_id' => $_ENV['XERO_CLIENT_ID'],
            'client_secret' => $_ENV['XERO_CLIENT_SECRET'],
            'tenant_id' => $tenant->tenantId,
            'refresh_token' =>  $token->getRefreshToken(),
        ];

        echo '<pre>';
        echo htmlentities(json_encode($config, JSON_PRETTY_PRINT));
        echo '</pre>';
    }
}
