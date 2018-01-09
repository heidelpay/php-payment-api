<?php

namespace Heidelpay\Tests\PhpPaymentApi\Unit\ParameterGroup;

use Codeception\TestCase\Test;
use Heidelpay\PhpPaymentApi\ParameterGroups\ConfigParameterGroup as Config;

/**
 * Unit test for ConfigParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  http://dev.heidelpay.com/heidelpay-php-api/
 *
 * @author  Jens Richter
 *
 * @package heidelpay\php-payment-api\tests\unit
 */
class ConfigParameterGroupTest extends Test
{
    /**
     * BankCountry getter/setter test
     */
    public function testBankCountry()
    {
        $config = new Config();

        $value = array('NL' => 'Niederlande');
        $config->set('bankcountry', json_encode($value));

        $this->assertEquals($value, $config->getBankCountry());
    }

    /**
     * BankCountry getter/setter test
     */
    public function testBrands()
    {
        $config = new Config();

        $value = array(
            'ING_TEST' => 'Test Bank',
            'INGBNL2A' => 'Issuer Simulation V3 - ING',
            'RABONL2U' => 'Issuer Simulation V3 - RABO'
        );

        $config->set('brands', json_encode($value));

        $this->assertEquals($value, $config->getBrands());
    }

    /**
     * Getter/Setter Test for optin text
     *
     * @test
     */
    public function optinTextTest()
    {
        $config = new Config();

        $value = array(
            'optin' => 'Ich willige in die Übermittlung meiner oben angegebenen personenbezogenen Daten '
                . 'sowie der Informationen über meinen Zahlungsverlauf durch die Heidelberger Payment GmbH an die '
                . 'Santander Consumer Bank AG („Santander“) sowie die Verwendung durch Santander für die postalische '
                . 'Zusendung von Angeboten, etwa zur Umschuldung oder Refinanzierung durch Santander, gemäß der '
                . 'Santander-Datenschutzerklärung zu. Mir ist bekannt, dass ich diese Einwilligung jederzeit wie '
                . 'in der Datenschutzerklärung beschrieben, mit Wirkung für die Zukunft widerrufen kann.',
            'privacy_policy' => 'Santander Datenschutzerklärung: Die Santander Consumer Bank AG, Santander-Platz 1, '
                . 'D-41061 Mönchengladbach („Santander“) unterstützt die Heidelberger Payment GmbH dabei, Ihnen die '
                . 'Möglichkeit eines Rechnungskaufs für Produkte oder Dienstleistungen, die sie erwerben wollen, '
                . 'anzubieten. Werbezwecke Santander wird Ihren Namen und Ihre Adresse nutzen, um Ihnen postalisch '
                . 'Informationen über Angebote der Santander Consumer Bank AG zuzusenden. Sollten Sie in '
                . 'Zahlungsverzug geraten, ist es Santander auch gestattet, Ihnen Angebote zur Umschuldung bzw. '
                . 'Refinanzierung auf postalischem Weg zu unterbreiten. Es ist Santander gestattet, sämtliche '
                . 'Santander vom Rechnungskaufanbieter zugänglich gemachten oder sonst vorliegenden Daten über Ihren '
                . 'Zahlungsverlauf auszuwerten, um Ihnen ein maßgeschneidertes Angebot unterbreiten zu können. '
                . 'Sie haben jederzeit die Möglichkeit, die erteilte Einwilligung in Übermittlung Ihrer '
                . 'personenbezogenen Daten an Santander sowie deren Verwendung für die Zusendung von Werbung unter '
                . 'Verwendung des Widerrufsformulars oder per E-Mail zu widerrufen. Bitte berücksichtigen Sie, dass '
                . 'der Widerruf Ihrer oben beschriebenen Einwilligung keinen Einfluss auf sonstige Santander gegenüber '
                . 'erteilte Einwilligungen hat.'
        );

        $config->set('optin_text', json_encode($value));

        $this->assertEquals($value, $config->getOptinText());

        $config->set('optin_text', null);
        $this->assertEquals(null, $config->getOptinText());
    }
}
