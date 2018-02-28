<?php
/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */
namespace Web\ContactBundle\Controller;

use App\UserBundle\Entity\EmailReporting;
use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Web\ContactBundle\Form\ContactType;
use App\CoreBundle\Controller\BaseController;

/**
* Frontend Contact controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/
class ContactController extends BaseController
{
    protected $namespace = 'WebContactBundle';
    protected $module = 'Contact';    
    protected $fieldName = "Contact";
    protected $redirectShow = "_pages_show";    
    
    /**
    * show action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response    
    */
    public function showAction(Request $request) {
        
        $form = $this->createForm(ContactType::class);
        if ($request->get("contact")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                               
            }
        }
                                        
        return $this->render('WebContactBundle:Contact:sent.html.twig', array(
            'form' => $form->createView(),            
        ));
    }
    
    /**
    * send action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response    
    */
    public function sentAction(Request $request) {
        
        $form = $this->createForm(ContactType::class);
                        
        if ($request->get("contact")) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                
                $formMail = $form->getData();
                $bodyHtml = $this->renderView('WebWebBundle:Emails:_email_contact.html.twig', array(
                            'mail' => $formMail
                                ));           
                                
                $mailerSender = $this->getParameter("mailer_sender");
                $message = Swift_Message::newInstance()
                    ->setSubject('Wiadomość z Orient-Med od '.$formMail["firstname"].' '.$formMail["lastname"])
                    ->setFrom($mailerSender["email"])
                    ->setTo($this->get("repo.settings")->getMainEmail())
                    ->setBody($bodyHtml, 'text/html')
                    //->addPart($bodyText,'text/plain')
                ;
                                
                
               try{
                    $this->get('mailer')->send($message);                              
                    $this->get("repo.emailReporting")->saveRaport($message,EmailReporting::TYPE_CONTACT);
                    
                }catch(\Exception $ex) {
                    $this->get("repo.emailReporting")->saveRaport($message,EmailReporting::TYPE_CONTACT,$ex->getMessage());
                }
                
                
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html
                $confirm["note"] = "ok";
                $confirm["msg"] = "Wiadomość została wysłana pomyślnie. Wkrótce się skontaktujemy";
            }
        }
        
        $confirm["html"] =  $this->render('WebContactBundle:Contact:sent.html.twig', array(
            'form' => $form->createView(),     
            "note"=>"info",
            "msg"=>"",
        ));
        
        
         $output = json_encode($confirm);        
        return new Response($output);
    }              
}
