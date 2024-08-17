<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentGeneralQuestionRepository;
use App\Repositories\EloquentPageMetaTagRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;
use Illuminate\Http\Request;

class GeneralQuestionController extends Controller
{
    protected EloquentGeneralQuestionRepository $generalQuestionRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;

    public function __construct(
        EloquentGeneralQuestionRepository $generalQuestionRepository,
        EloquentPageMetaTagRepository     $pageMetaTagRepository
    )
    {
        $this->generalQuestionRepository = $generalQuestionRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }

    public function index()
    {
        try {
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
                'page_name' => ['operator' => '=', 'value' => 'Tez-tez verilən suallar'],
            ];
            $columns = ['id', 'title', 'description', 'keywords'];
            $page_meta_tag = $this->pageMetaTagRepository->first(
                filters: $filters,
                columns: $columns,
            );
            if ($page_meta_tag) {
                Meta::setTitleSeparator('->')
                    ->prependTitle(setting('title'))
                    ->setTitle($page_meta_tag->getTranslation('title', app()->getLocale()))
                    ->setDescription($page_meta_tag->getTranslation('description', app()->getLocale()))
                    ->setKeywords($page_meta_tag->getTranslation('keywords', app()->getLocale()))
                    ->setRobots('Vaqif,Shamil')
                    ->setContentType('text/html')
                    ->setViewport('width=device-width, initial-scale=1')
                    ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
                    ->setCanonical(config('app.url'));
            }
            return view('front.general_questions', compact('general_questions'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
