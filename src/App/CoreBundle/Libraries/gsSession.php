<?php

namespace App\CoreBundle\Libraries;

use Symfony\Component\HttpFoundation\Session;

/**
 * gsSession object.
 */


class gsSession extends Session
{
    /**
     * Add a "UserAnswer" object in session.
     *
     * @param $userAnswer
     */
    function addAnswer($userAnswer)
    {
        $answers   = $this->get('answers', array());
        $answers[] = $userAnswer;
        $this->set('answers', $answers);
    }

    /**
     * Return all the answers of the user.
     *
     * @return mixed
     */
    function getAnswers()
    {
        return $this->get('answers', array());
    }

    /**
     * Set a "notice" flash message.
     *
     * @param $message
     * @return mixed
     */
    function setNotice($message)
    {
        return $this->setFlash('notice', $message);
    }
}


