<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\FaqCategory as FaqCategoryModel;
use Illuminate\Support\Facades\Redirect;

/**
 * Class FaqCategory
 * @package GodSpeed\FlametreeCMS\Components
 */
class FaqCategory extends ComponentBase
{
    /**
     * @var array
     */
    public $faqCategory;

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'FaqCategory',
            'description' => 'List all the specific faqs'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => "Category Field",
                "default" => ':slug',
                "type" => "string"
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
        $this->faqCategory = $this->fetchFaqCategory();
    }

    /**
     * @return mixed
     */
    protected function fetchFaqCategory()
    {
        return FaqCategoryModel::with('faqs')
            ->where('slug', $this->property('slug'))
            ->get()
            ->first();
    }

    public function onRender()
    {
        parent::onRender();
        if (is_null($this->faqCategory)) {
            return Redirect::to('/404');
        }
    }
}
