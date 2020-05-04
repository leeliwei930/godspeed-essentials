<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use GodSpeed\FlametreeCMS\Models\Faq;
use GodSpeed\FlametreeCMS\Models\FaqCategory as FaqCategoryModel;
use October\Rain\Database\Builder;

class FaqCategories extends ComponentBase
{
    /**
     * @var array
     */
    public $categories = [];
    public $faqs = [];
    public $selectedCategory = null;


    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Faq Categories',
            'description' => 'List all the faq categories records'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'category_parameter_key' => [
                "title" => "Category URL parameter query",
                'type' => "string",
                'default' => "category"
            ],
            'faqs_page' => [
                'title' => "FAQ Page",
                'type' => 'dropdown',
                'options' => Page::getNameList()
            ]
        ];
    }

    /**
     *
     */
    public function onRun()
    {
        $this->getCategoryParam();
        $this->prepareVars();
    }

    /**
     *
     */
    public function prepareVars()
    {
        $this->categories = $this->page['categories'] = $this->fetchCategories();
        $this->faqs = $this->page['faqs'] = $this->fetchFaqs();
    }

    /**
     * Fetch all the categories
     * @return FaqCategoryModel[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function fetchCategories()
    {
            return FaqCategoryModel::all();
    }

    protected function fetchFaqs()
    {
        if ($this->selectedCategory == null) {
            $faqCategory = FaqCategoryModel::with('faqs')->first();
            $this->selectedCategory = $faqCategory;

            return $faqCategory['faqs'];
        } else {
            $faqCategory = FaqCategoryModel::with('faqs')->where('slug', $this->selectedCategory)->first();
            if (is_null($faqCategory)) {
                return $this->throw404();
            }
            $this->selectedCategory = $faqCategory;
            return $faqCategory['faqs'];
        }
    }

    protected function getCategoryParam()
    {
        $parameterKey = $this->property('category_parameter_key');
        if (!is_null(get($parameterKey)) ) {
            $this->selectedCategory = get($parameterKey);
        }
    }

    protected function throw404()
    {
        $this->setStatusCode(404);
        $this->controller->run('404');
    }

    public function getCategoryLink($slug){
        return Page::url($this->property('faqs_page'))."?{$this->property('category_parameter_key')}={$slug}";
    }
}
