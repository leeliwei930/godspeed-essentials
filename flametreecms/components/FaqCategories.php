<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\FaqCategory as FaqCategoryModel;
use October\Rain\Database\Builder;

class FaqCategories extends ComponentBase
{
    /**
     * @var array
     */
    public $categories = [];

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
            'show_empty_records' => [
                "title" => "Show category if empty",
                "type" => "checkbox",
                "description" => "Show the category even there is no faqs associate with this category",
                "default" => 0
            ]
        ];
    }

    /**
     *
     */
    public function onRun()
    {
        $this->prepareVars();
    }

    /**
     *
     */
    public function prepareVars()
    {
        $this->categories = $this->fetchCategories();
    }

    /**
     * Fetch all the categories
     * @return FaqCategoryModel[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function fetchCategories()
    {
        $showEmptyRecords = $this->property('show_empty_records');
        if (!$showEmptyRecords) {
            return FaqCategoryModel::whereHas('faqs')->get();
        } else {
            return FaqCategoryModel::all();
        }
    }
}
