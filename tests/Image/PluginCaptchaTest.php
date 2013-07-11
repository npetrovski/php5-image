<?php


class PluginCaptchaTest extends PHPUnit_Framework_TestCase {

    public function testPluginCaptcha() {

        $image = new Image_Image();
        $this->assertEquals($image->attach(new Image_Draw_Captcha()), true);

    }

}
