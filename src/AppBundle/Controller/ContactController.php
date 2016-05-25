<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Jan Góralski <jan.goralski@lakion.com>
 */
class ContactController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendMailAction(Request $request)
    {
        $form = $this->createForm(new ContactType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = \Swift_Message::newInstance()
                ->setSubject($form->get('subject')->getData())
                ->setFrom($form->get('email')->getData())
                ->setTo('bob@caponica.com')
                ->setBody(
                    $this->renderView(
                        'Email/contact.html.twig',
                        ['email' => $form]
                    ),
                    'text/html')
            ;
            $this->get('swiftmailer.mailer')->send($email);

            $this->addFlash('success', 'Your message has been sent. Thank you for your feedback.');

            return $this->redirectToRoute('sylius_contact');
        }

        return $this->render('@SyliusWeb/Frontend/contact.html.twig', ['form' => $form->createView()]);
    }
}
