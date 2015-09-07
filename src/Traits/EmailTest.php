<?php

namespace Ree\Testbench\Traits;

/**
 * Testing email sending in ReeCMS
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
trait EmailTest
{

    /**
     * Init the mailer mock.
     * 
     * Call this method before using any of `expect*` methods in this trait.
     */
    protected function initMailService()
    {
        $this->_mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $transport = $this->getMockBuilder(\Swift_Transport_NullTransport::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_mailer->expects($this->any())
            ->method('getTransport')
            ->willReturn($transport);

        $this->app['mailer']->setSwiftMailer($this->_mailer);
    }

    /**
     * Assert that one email is sent
     * 
     * @param callable $assertion
     */
    protected function expectMailSendOnce($assertion)
    {
        $this->_mailer->expects($this->once())
            ->method('send')
            ->willReturnCallback(function($message) use ($assertion) {
                call_user_func($assertion, $message);
            });
    }

    /**
     * Assert that no email is sent
     */
    protected function expectNoMailSent()
    {
        $this->_mailer->expects($this->never())
            ->method('send')
            ->willReturn(true);
    }
}
