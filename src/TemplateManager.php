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
        $searchTerms = [];
        $replaceTerms = [];

        if ($quote) {
            $quoteItem = QuoteRepository::getInstance()->getById($quote->id);
            $quoteTokenBase = 'quote';

            foreach (Quote::getTokenList() as $quoteToken)
            {
                if (strpos($text, "[$quoteTokenBase:$quoteToken]") !== false) {
                    $renderedToken = $quoteItem->renderToken($quoteToken);
                    if (!is_null($renderedToken)) {
                        $searchTerms[] = "[$quoteTokenBase:$quoteToken]";
                        $replaceTerms[] = $renderedToken;
                    }
                }
            }
        }

        if (isset($data['user']) and ($data['user']  instanceof User)) {
            $user = $data['user'];
        } else {
            $user = ApplicationContext::getInstance()->getCurrentUser();
        }

        foreach (User::getTokenList() as $userToken)
        {
            $userTokenBase = 'user';
            if (strpos($text, "[$userTokenBase:$userToken]") !== false) {
                $renderedToken = $user->renderToken($userToken);
                if (!is_null($renderedToken)) {
                    $searchTerms[] = "[$userTokenBase:$userToken]";
                    $replaceTerms[] = $renderedToken;
                }
            }
        }

        $text = str_replace($searchTerms, $replaceTerms, $text);
        return $text;
    }
}
