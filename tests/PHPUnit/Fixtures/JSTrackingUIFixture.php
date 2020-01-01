<?php
/**
 * Piwik - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */

namespace Piwik\Tests\Fixtures;

use Piwik\Plugins\GeoIp2\LocationProvider\GeoIp2\Php;
use Piwik\Plugins\PrivacyManager\IPAnonymizer;
use Piwik\Plugins\UserCountry\LocationProvider;
use Piwik\Tests\Framework\Fixture;

class JSTrackingUIFixture extends Fixture
{
    public function setUp()
    {
        parent::setUp();

        self::resetPluginsInstalledConfig();
        self::updateDatabase();
        self::installAndActivatePlugins($this->getTestEnvironment());
        self::updateDatabase();

        // for proper geolocation
        LocationProvider::setCurrentProvider(Php::ID);
        IPAnonymizer::deactivate();

        Fixture::createWebsite('2012-02-02 00:00:00');
    }

    public function performSetUp($setupEnvironmentOnly = false)
    {
        $this->extraTestEnvVars = array(
            'loadRealTranslations' => 1,
        );
        $this->extraPluginsToLoad = array(
            'ExampleTracker', // TODO: in exampletracker, add tracker.js that uses random value & sets custom dimensions via request processor
        );

        parent::performSetUp($setupEnvironmentOnly);

        $this->testEnvironment->overlayUrl = UiTestFixture::getLocalTestSiteUrl();
        UITestFixture::createOverlayTestSite($idSite = 1);

        $this->testEnvironment->tokenAuth = self::getTokenAuth();
        $this->testEnvironment->save();
    }
}