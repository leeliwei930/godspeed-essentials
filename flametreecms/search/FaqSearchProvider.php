<?php


/**
 * Created by Li Wei Lee, on 26/4/2020.
 */

namespace GodSpeed\FlametreeCMS\Search;

use Cms\Classes\Page;

use GodSpeed\FlametreeCMS\Models\Faq;

use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;

class FaqSearchProvider extends ResultsProvider
{
    public function search()
    {
        $query = $this->query;

        $matching = Faq::where('question', 'like', "%{$query}%")->get();


        foreach ($matching as $match) {
            $result = $this->newResult();

            $result->relevance = 1;
            $result->title = $match->question;
            $result->excerpt = $match->answer;
            $result->url = Page::url('frequently-ask-questions', ['id' => $match->id]);
            $result->model = $match;
            $this->addResult($result);

        }

        return $this;
    }

    public function displayName()
    {
        return "FAQ";
    }

    public function identifier()
    {
        return "GodSpeed.FlametreeCMS";
    }


}
