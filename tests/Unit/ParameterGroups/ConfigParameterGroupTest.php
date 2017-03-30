<?php

namespace Heidelpay\Tests\PhpApi\Unit\ParameterGroup;

use PHPUnit\Framework\TestCase;
use Heidelpay\PhpApi\ParameterGroups\ConfigParameterGroup as Config;

/**
 * Unit test for ConfigParameterGroup
 *
 * @license Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 * @copyright Copyright © 2016-present Heidelberger Payment GmbH. All rights reserved.
 *
 * @link  https://dev.heidelpay.de/PhpApi
 *
 * @author  Jens Richter
 *
 * @category unittest
 */
class ConfigParameterGroupTest extends TestCase
{
    /**
     * BankCountry getter/setter test
     */
    public function testBankCountry()
    {
        $Config = new Config();

        $value = array('NL' => 'Niederlande');
        $Config->set('bankcountry', json_encode($value));

        $this->assertEquals($value, $Config->getBankCountry());
    }

    /**
     * BankCountry getter/setter test
     */
    public function testBrands()
    {
        $Config = new Config();

        $value = array(
            'ING_TEST' => 'Test Bank',
            'INGBNL2A' => 'Issuer Simulation V3 - ING',
            'RABONL2U' => 'Issuer Simulation V3 - RABO'
        );

        $Config->set('brands', json_encode($value));

        $this->assertEquals($value, $Config->getBrands());
    }

    /**
     * Getter/Setter Test for optin text
     *
     * @test
     */
    public function OptinText()
    {
        $Config = new Config();

        $value = array(
            "optin" => "Ich willige in die Übermittlung meiner oben angegebenen personenbezogenen Daten"
                . "sowie der Informationen über meinen Zahlungsverlauf durch die Heidelberger Payment GmbH an die Santander"
                . " Consumer Bank AG („Santander“) sowie die Verwendung durch Santander für die postalische Zusendung von "
                . "Angeboten, etwa zur Umschuldung oder Refinanzierung durch Santander, gemäß der Santander-Datenschutzerklärung"
                . " zu. Mir ist bekannt, dass ich diese Einwilligung jederzeit wie in der Datenschutzerklärung beschrieben, "
                . "mit Wirkung für die Zukunft widerrufen kann.",
            "privacy_policy" => "Santander Datenschutzerklärung: Die Santander Consumer Bank AG, Santander-Platz 1, "
                . "D-41061 Mönchengladbach („Santander“) unterstützt die Heidelberger Payment GmbH dabei, Ihnen die "
                . "Möglichkeit eines Rechnungskaufs für Produkte oder Dienstleistungen, die sie erwerben wollen, anzubieten. "
                . "Werbezwecke Santander wird Ihren Namen und Ihre Adresse nutzen, um Ihnen postalisch Informationen über "
                . "Angebote der Santander Consumer Bank AG zuzusenden. Sollten Sie in Zahlungsverzug geraten, ist es "
                . "Santander auch gestattet, Ihnen Angebote zur Umschuldung bzw. Refinanzierung auf postalischem Weg zu "
                . "unterbreiten. Es ist Santander gestattet, sämtliche Santander vom Rechnungskaufanbieter zugänglich "
                . "gemachten oder sonst vorliegenden Daten über Ihren Zahlungsverlauf auszuwerten, um Ihnen ein "
                . "maßgeschneidertes Angebot unterbreiten zu können. Sie haben jederzeit die Möglichkeit, die erteilte "
                . "Einwilligung in Übermittlung Ihrer personenbezogenen Daten an Santander sowie deren Verwendung "
                . "für die Zusendung von Werbung unter Verwendung des Widerrufsformulars oder per E-Mail zu widerrufen. "
                . "Bitte berücksichtigen Sie, dass der Widerruf Ihrer oben beschriebenen Einwilligung keinen Einfluss auf "
                . "sonstige Santander gegenüber erteilte Einwilligungen hat."
        );

        $Config->set('optin_text', json_encode($value));

        $this->assertEquals($value, $Config->getOptinText());
    }
}
