<?php

namespace App\HelperClasses\Mail;

use App\Exceptions\MainException;
use Modules\EmailTemplate\Http\Repositories\Eloquent\EmailTemplateRepository;
use Illuminate\Support\Facades\Mail;
use Modules\EmailTemplate\Mail\MailContent;

abstract class MailProcess
{
    const BLADE_TEMPLATE_MAIL = "emailtemplate::mail-templates.content";

    private $repoMailTemplate;

    public function __construct()
    {
        $this->repoMailTemplate = new EmailTemplateRepository(\app());
    }

    /**
     * @description the array is [ key is variable => value ]
     * @return array
     */
    protected abstract function variables():array;

    /**
     * @param string $email
     * @param mixed|null $dataExtra
     * @return bool
     */
    public abstract function mainSender(mixed $email, mixed $dataExtra = null):bool;

    /**
     * @param $slugTemplate
     * @return array => keys is only (body and subject)
     * @author moner khalil
     */
    private function getTemplateAndSubject($slugTemplate):array{
        $template = $this->repoMailTemplate->find($slugTemplate,null,"slug",false,false);
        if (is_null($template)){
            return [
                "variables" => [],
                "body" => "",
                "subject" => "",
                "template" => null,
            ];
        }
        $varsTemplate = $template->variables_backend;
        return [
            "variables" =>  is_null($varsTemplate) ? [] : explode(',',$varsTemplate),
            "body" => checkObjectInstanceofTranslation($template,"body"),
            "subject" => checkObjectInstanceofTranslation($template,"subject"),
            "template" => $template,
        ];
    }

    protected function sendMail($slug_template ,mixed $email,$dataExtra = []){
        try {
            $content = $this->getTemplateAndSubject($slug_template);
            $template = $content['template'];
            $email = is_object($email) ? $email?->email ?? null : $email;
            if (!is_null($template) && !is_null($email)){
                $subject = $content['subject'];
                $content = $this->getContentFinal($content['variables'],$content['body'],$dataExtra);
                $social_media = $template->social_media;
                Mail::to($email)->send(new MailContent($subject,compact("template","content","social_media")));
            }
            return true;
        }catch (\Exception $exception){
            return false;
        }
    }

    /**
     * @param array $variables
     * @param string $content
     * @param array $addVariables ====> add values to function variables
     * @return mixed
     */
    protected function getContentFinal(array $variables,string $content,array $addVariables = []){
        $searchVars = $this->writeVarInTemplate(array_merge($variables,array_flip($addVariables)));
        $valuesVars = array_merge($this->variables(),$addVariables);
        foreach ($searchVars as $key => $value){
            $content = str_replace($value, $valuesVars[$key] ?? "-", $content);
        }
        return $content;
    }

    private function writeVarInTemplate(array $variables){
        $finalVariable = [];
        foreach ($variables as $variable){
            $finalVariable[$variable] = '{' . $variable . '}';
        }
        return $finalVariable;
    }
}
