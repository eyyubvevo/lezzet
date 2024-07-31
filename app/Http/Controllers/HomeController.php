<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\EloquentGeneralQuestionRepository;
use App\Repositories\EloquentPageMetaTagRepository;
use App\Repositories\EloquentSliderRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;

class HomeController extends Controller
{
    protected EloquentSliderRepository $sliderRepository;
    protected EloquentGeneralQuestionRepository $generalQuestionRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;

//
    public function __construct(
        EloquentSliderRepository          $sliderRepository,
        EloquentGeneralQuestionRepository $generalQuestionRepository,
        EloquentPageMetaTagRepository     $pageMetaTagRepository
    )
    {
        $this->sliderRepository = $sliderRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
        $this->generalQuestionRepository = $generalQuestionRepository;
    }
    public function error(){return redirect()->route('az.home');}
 

    public function index()
    {
        try {
            $sliders = $this->sliderRepository->all(
                filters: [
                    'status' => ['operator' => '=', 'value' => 1],
                ],
                columns: ['id', 'title', 'order', 'image', 'status', 'created_at'],
                orderBy: 'order',
                sortBy: 'DESC',
                relations: [],
                perPage: null,
                limit: null,
                withTrashed: false,
                trashed: 'no',
                joins: [],
                useCache: false,
            );

            $categories = Category::where('status', 1)->orderBy('order','asc')->get();

            $general_questions = $this->generalQuestionRepository->all(
                filters: ['status' => ['operator' => '=', 'value' => 1],],
                columns: ['id', 'answer', 'question', 'status', 'created_at'],
                orderBy: 'created_at',
                sortBy: 'DESC',
                relations: [],
                perPage: NULL,
                limit: null,
                withTrashed: false,
                trashed: 'no',
                joins: [],
                useCache: false,
            );
            $filters = [
                'page_name' => ['operator' => '=', 'value' => 'Ana Səhifə'],
            ];
            $columns = ['id', 'title', 'description', 'keywords'];
            $page_meta_tag = $this->pageMetaTagRepository->first(
                filters: $filters,
                columns: $columns,
            );
            if ($page_meta_tag) {
                
                Meta::setTitleSeparator('->')
                    // ->prependTitle($page_meta_tag->getTranslation('title', app()->getLocale()))
                    ->setTitle($page_meta_tag->getTranslation('title', app()->getLocale()))
                    ->setDescription($page_meta_tag->getTranslation('description', app()->getLocale()))
                    ->setKeywords($page_meta_tag->getTranslation('keywords', app()->getLocale()))
                    ->setRobots('Vaqif,Shamil')
                    ->setContentType('text/html')
                    ->setViewport('width=device-width, initial-scale=1')
                    ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
                    ->setCanonical(config('app.url'));
            }
            return view('front.home', compact('sliders', 'categories', 'general_questions'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }


}
