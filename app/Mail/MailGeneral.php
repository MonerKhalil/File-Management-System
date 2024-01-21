<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailGeneral extends Mailable
{
    use Queueable, SerializesModels;

    private $subjectMail , $dataMail , $viewMail, $fromMail,$pathLogo,$titleMail;

    public function __construct(mixed $dataMail = [],string $titleMail = null,string $pathLogo = null,string $subjectMail = null,
                                string $viewMail = null,$fromMail = null)
    {
        $this->dataMail = $dataMail;
        $this->subjectMail = is_null($subjectMail) ? "New mail" : $subjectMail;
        $this->viewMail = is_null($viewMail) ? "mail-templates.MailGeneral" : $viewMail;
        $this->pathLogo = is_null($pathLogo) ? "https://tourism.tep-sy.com/website/assets/images/logo/colored_logo.png" : $pathLogo;
        $this->fromMail = $fromMail;
        $this->titleMail = $titleMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $build = $this->subject($this->subjectMail)
            ->markdown($this->viewMail)
            ->with([
                "data" => $this->dataMail,
                "logo" => $this->pathLogo,
                "title" => $this->titleMail,
                "subject" => $this->subjectMail,
            ]);
        if (!is_null($this->fromMail)){
            $build = $build->from($this->fromMail);
        }
        return $build;
    }
}
