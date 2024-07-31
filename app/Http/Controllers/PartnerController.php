<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentPageMetaTagRepository;
use App\Repositories\EloquentPartnerRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    protected EloquentPartnerRepository $partnerRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;

    public function __construct(
        EloquentPartnerRepository  $partnerRepository,
        EloquentPageMetaTagRepository     $pageMetaTagRepository
    )
    {
        $this->partnerRepository = $partnerRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }


    public function index()
    {
        try {
            $partners = $this->partnerRepository->all(
                filters: [
                    'status' => ['operator' => '=', 'value' => 1],
                ],
                columns: ['id', 'name', 'url', 'image', 'created_at'],
            );
            $filters = [
                'page_name' => ['operator' => '=', 'value' => 'Partnyorlar'],
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
            return view('front.partners', compact('partners'));
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
