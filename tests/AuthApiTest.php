<?php
//
// /**
//  * This file is part of the Scorpion API
//  *
//  * (c) Hare Digital
//  *
//  * For the full copyright and license information, please view the LICENSE
//  * file that was distributed with this source code.
//  *
//  * @package     scorpion/api
//  * @version     0.1.0
//  * @copyright   Copyright (c) Hare Digital
//  * @license     LICENSE
//  * @link        README.MD Documentation
//  */
//
// use Laravel\Lumen\Testing\DatabaseTransactions;
//
// /**
//  * Testing for the /user endpoints
//  *
//  * @author  Miles Croxford <hello@milescroxford.com>
//  * @author  James Wallen-Jones <james@jamosaur.xyz>
//  */
// class AuthApiTest extends AbstractApiTest
// {
//     use DatabaseTransactions;
//
//     public function testLoginAsUser()
//     {
//         $response = $this->login();
//         $this->assertResponseOk();
//         $this->assertNotNull($response->auth->data->token);
//
//         $this->assertCount(1, $response->user->data->vehicles->data);
//         $this->assertObjectHasAttribute('position', $response->user->data->vehicles->data[0]);
//     }
//
//     public function testLoginWithIncorrectPasswordFails()
//     {
//         $resp = $this->login('chris+customer@haredigital.com', 'lmao');
//         $this->assertResponseStatus(403);
//     }
//
//     public function testValidationForEmailWorks()
//     {
//         $response = $this->login('invalid', 'password');
//         $this->assertResponseStatus(422);
//         $this->assertEquals('Your supplied email is not valid', $response->errors[0]);
//         $this->assertNotEmpty($response->errors);
//     }
//
//     public function testValidationForEmailRequiredWorks()
//     {
//         $response = $this->login('', 'password');
//         $this->assertResponseStatus(422);
//         $this->assertEquals('email is required', $response->errors[0]);
//         $this->assertNotEmpty($response->errors);
//     }
//
//     public function testValidationForPasswordRequired()
//     {
//         $response = $this->login('test@email.com', '');
//         $this->assertResponseStatus(422);
//         $this->assertEquals('password is required', $response->errors[0]);
//         $this->assertNotEmpty($response->errors);
//     }
//
//     public function testIncorrectLoginReturns404()
//     {
//         $response = $this->login('doest@exist.com', 'password');
//         $this->assertResponseStatus(404);
//         $this->assertNotEmpty($response->errors);
//     }
//
//     public function testForgottenPasswordEndpointReturnsUrl()
//     {
//         $response = json_decode($this->call('GET', '/v1/auth/forgotten-password')->getContent());
//         $this->assertResponseOk();
//         $this->assertEquals(scorpion_url('login/forgotpassword'), $response->url);
//     }
//
//     public function testConfigRoute()
//     {
//         $resp = json_decode($this->call('GET', '/config?platform=ANDROID&locale=en_GB&version=1&mode=scorpiontrack')->getContent());
//         $this->assertResponseOk();
//         $this->assertEquals(getenv('POLL_TIME'), $resp->config->data->poll_time);
//         $this->assertEquals(getenv('APP_URL') . '/v' . getenv('API_VERSION'), $resp->config->data->base_url);
//         $this->assertEquals(scorpion_url('home/terms'), $resp->config->data->terms_url);
//         $this->assertEquals(scorpion_url('login/forgotpassword'), $resp->config->data->forgotten_password_url);
//     }
// }
