<?php


class PluginCaptchaTest extends PHPUnit_Framework_TestCase {

    public function testPluginCaptcha() {

        $image = new Image\Canvas();
        $this->assertNotEmpty($image->attach(new Image\Draw\Captcha()));

    }

}
