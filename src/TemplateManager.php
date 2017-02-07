<?php

class TemplateManager
{
    public function getTemplateComputed(Template $tpl, array $data)
    {
        if (!$tpl) {
            throw new \RuntimeException('no tpl given');
        }

        $replaced = clone($tpl);
        $replaced->subject = $this->computeText($replaced->subject, $data);
        $replaced->content = $this->computeText($replaced->content, $data);

        return $replaced;
    }

    private function computeText($text, array $data)
    {
        $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;

        if ($quote) {
            $quoteItem = QuoteRepository::getInstance()->getById($quote->id);

            $text = $this->replaceTokensInText($text, $quoteItem);

        }

        if (isset($data['user']) and ($data['user']  instanceof User)) {
            $user = $data['user'];
        } else {
            $user = ApplicationContext::getInstance()->getCurrentUser();
        }

        $text = $this->replaceTokensInText($text, $user);

        return $text;
    }

    private function replaceTokensInText($text, AbstractEntity $entityObject)
    {
        $searchTerms = [];
        $replaceTerms = [];
        $tokenList = $entityObject::getTokenList();
        $tokenBase = $entityObject::getTokenBase();

        if (empty($tokenList) || is_null($tokenBase)) {
            return $text;
        }

        foreach ($tokenList as $token)
        {
            if (strpos($text, "[$tokenBase:$token]") !== false) {
                $renderedToken = $entityObject->renderToken($token);
                if (!is_null($renderedToken)) {
                    $searchTerms[] = "[$tokenBase:$token]";
                    $replaceTerms[] = $renderedToken;
                }
            }
        }

        return str_replace($searchTerms, $replaceTerms, $text);
    }
}
